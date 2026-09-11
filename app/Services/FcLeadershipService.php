<?php

namespace App\Services;

use App\Models\FcLeadershipProgress;
use App\Models\Payment;
use App\Models\ReferralBonus;
use App\Models\ChartAccount;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * FcLeadershipService — FC VIP Leadership bonus engine.
 *
 * Credited when a direct referral's FC VIP payment is confirmed:
 *   1. The DIRECT referrer earns +100 VB Volume Bonus (qualification counter
 *      + visible balance) on top of the standard L1 10% cash commission.
 *   2. If this referral pushes the leader across any of the 6 milestone
 *      thresholds (10 / 30 / 50 / 150 / 500 / 1000 direct FC referrals),
 *      the corresponding lump-sum reward (3 / 15 / 40 / 150 / 750 / 2000 VB)
 *      is written into `referral_bonuses` as a Monday-withdrawable cash
 *      bonus (converted to USD at the configured VB price), in exactly the
 *      same pipeline as UVP referral commissions → cashout on Monday.
 *
 * Indirect (L2 / L3) referrals for FC VIP payments are handled identically
 * to UVP by ReferralService::creditForPayment() — no extra FC leadership
 * rules apply there, per spec: "Referral is same as UVP Packages".
 */
class FcLeadershipService
{
    /** ChartAccount bucket used for tracking VB Volume Bonus balances. */
    public const ACC_VB = 'FC_VOLUME_BONUS';

    /**
     * Called after an FC VIP payment is confirmed (Paid-in-Full, status=1).
     *
     * Walks the buyer's upline and, for the DIRECT (L1) referrer ONLY:
     *   - credits 100 VB to their FC_VOLUME_BONUS ChartAccount bucket
     *   - increments direct_fc_count and volume_bonus_vb
     *   - awards any newly-crossed FC Leadership milestone bonus(es).
     *
     * Returns the list of ReferralBonus rows created (leadership rewards).
     */
    public static function onFcPaymentConfirmed(Payment $payment): array
    {
        if (!$payment) {
            return [];
        }

        // Only fire for genuine FC VIP payments.
        $category = strtoupper(trim((string) ($payment->category ?? '')));
        $isFc = $category === 'FC'
            || (isset($payment->payable_type) && $payment->payable_type === \App\Models\FCpackage::class);
        if (!$isFc) {
            return [];
        }

        if ((int) ($payment->status ?? 0) !== 1) {
            return [];
        }

        $buyer = User::find($payment->user);
        if (!$buyer) {
            return [];
        }

        // Find the direct (L1) referrer.
        $directReferrerId = (int) ($buyer->referee_id ?? 0);
        if ($directReferrerId <= 0) {
            // Auto-repair referee_id from Teams table if needed.
            $team = \App\Models\Teams::where('team_user_id', $buyer->id)->first();
            if ($team && $team->user_id) {
                $buyer->referee_id = $team->user_id;
                $buyer->save();
                $directReferrerId = (int) $team->user_id;
            }
        }
        if ($directReferrerId <= 0 || $directReferrerId === (int) $buyer->id) {
            return [];
        }

        $referrer = User::find($directReferrerId);
        if (!$referrer) {
            return [];
        }

        // Free users do NOT earn FC leadership bonuses (same rule as UVP referrals).
        if (ReferralService::isFreeUser($referrer)) {
            return [];
        }

        // Idempotency: don't double-credit if this payment has already produced
        // an fc_direct_vb row for this referrer.
        $alreadyCredited = ReferralBonus::where('user_id', $referrer->id)
            ->where('source_payment_id', $payment->id)
            ->where('source', 'fc_direct_vb')
            ->exists();
        if ($alreadyCredited) {
            return self::awardNewLeadershipTiers($referrer);
        }

        return DB::transaction(function () use ($referrer, $payment, $buyer) {
            // ── 1. Credit 100 VB per direct FC referral ──
            $vbAmount = FcLeadershipProgress::VB_PER_DIRECT_REFERRAL;

            // Persist to progress counter.
            $progress = FcLeadershipProgress::forUser($referrer->id);
            $progress->direct_fc_count   = (int) $progress->direct_fc_count + 1;
            $progress->volume_bonus_vb   = (int) $progress->volume_bonus_vb + $vbAmount;
            $progress->save();

            // Mirror to ChartAccount FC_VOLUME_BONUS bucket (visible to user).
            ChartAccount::applyDeltaLocked(
                $referrer->id,
                self::ACC_VB,
                $vbAmount,
                false,
                "FC direct VB (+{$vbAmount}) from {$buyer->name} payment #{$payment->id}"
            );

            // Write a referral_bonuses ledger row for the 100 VB direct reward
            // itself — purely informational (it is NOT a cash commission),
            // recorded so the user sees the credit on their referral history.
            // We mark it as status='vb_only' so it is NOT included in
            // withdrawable cash totals (cash rewards come from milestone tiers
            // only — per spec: "added to referral bonus and send to cashout
            // Monday" applies only to the 3/15/40/... milestone rewards, not
            // to each individual 100VB credit).
            try {
                ReferralBonus::create([
                    'user_id'           => $referrer->id,
                    'source_user_id'    => $buyer->id,
                    'source_payment_id' => $payment->id,
                    'level'             => 1,
                    'percentage'        => 0,
                    'source_amount'     => (float) ($payment->paid ?? $payment->amount ?? 0),
                    'bonus_amount'      => 0, // $0 cash for this 100VB entry
                    'week_start'        => ReferralService::nextMonday(),
                    'status'            => 'vb_only',
                    'source'            => 'fc_direct_vb',
                    'source_ref'        => '+100VB',
                    'notes'             => "+{$vbAmount} FC Volume Bonus from direct referral " . ($buyer->name ?? $buyer->user ?? $buyer->email),
                ]);
            } catch (\Throwable $e) {
                Log::warning('FcLeadershipService: could not write fc_direct_vb ledger row: ' . $e->getMessage());
            }

            // ── 2. Award any newly-crossed milestone tier bonuses ──
            return self::awardNewLeadershipTiers($referrer, $payment);
        });
    }

    /**
     * Scan every user's FC leadership progress against current counts
     * (idempotent backfill) and award any milestones not yet paid.
     * Called from the weekly Monday cron so that any missed rewards
     * (due to race conditions / retroactively-fixed referee chains)
     * are still paid on the next Monday run.
     *
     * @return int total milestone bonus rows created
     */
    public static function processWeekly(): int
    {
        // First, self-heal the progress counters from actual payment history
        // (covers fixes to referee_id chains made after purchase).
        self::reconcileProgressFromPayments();

        $count = 0;
        $progresses = FcLeadershipProgress::all();
        foreach ($progresses as $progress) {
            $created = self::awardNewLeadershipTiers(User::find($progress->user_id));
            $count += count($created);
        }
        return $count;
    }

    /**
     * Rebuild each user's direct_fc_count / volume_bonus_vb from the
     * authoritative payments table. Safe to run repeatedly.
     */
    public static function reconcileProgressFromPayments(): void
    {
        try {
            $directCounts = Payment::where('status', 1)
                ->where('is_expired', false)
                ->where(function ($q) {
                    $q->where('category', 'FC')
                      ->orWhere('payable_type', \App\Models\FCpackage::class);
                })
                ->selectRaw('`user`, COUNT(*) AS cnt')
                ->groupBy('user')
                ->pluck('cnt', 'user');

            // For each user who is a referee of those buyers, increment their count.
            $buyerIds = $directCounts->keys()->map(fn ($id) => (int) $id)->all();
            if (empty($buyerIds)) {
                return;
            }

            $refereeMap = User::whereIn('id', $buyerIds)
                ->pluck('referee_id', 'id');

            $totals = []; // referrer_id => count
            foreach ($refereeMap as $buyerId => $refId) {
                $refId = (int) $refId;
                if ($refId <= 0) {
                    continue;
                }
                $totals[$refId] = ($totals[$refId] ?? 0) + (int) $directCounts->get($buyerId, 0);
            }

            foreach ($totals as $refId => $cnt) {
                $progress = FcLeadershipProgress::forUser((int) $refId);
                $progress->direct_fc_count = (int) $cnt;
                $progress->volume_bonus_vb   = (int) $cnt * FcLeadershipProgress::VB_PER_DIRECT_REFERRAL;
                $progress->save();

                // Sync ChartAccount bucket to match.
                $existing = (float) ChartAccount::where('user_id', (int) $refId)
                    ->where('acc_type', self::ACC_VB)
                    ->sum('amount');
                $expected = (float) $progress->volume_bonus_vb;
                if (abs($existing - $expected) > 0.0001) {
                    ChartAccount::applyDeltaLocked(
                        (int) $refId,
                        self::ACC_VB,
                        $expected - $existing,
                        false,
                        'FC leadership reconciliation'
                    );
                }
            }
        } catch (\Throwable $e) {
            Log::error('FcLeadershipService::reconcileProgressFromPayments failed: ' . $e->getMessage());
        }
    }

    /**
     * Award milestone tier bonuses for any tier the referrer now qualifies
     * for that hasn't been paid yet. Increments highest_tier_paid.
     *
     * @return ReferralBonus[]
     */
    protected static function awardNewLeadershipTiers(?User $referrer, ?Payment $triggerPayment = null): array
    {
        if (!$referrer) {
            return [];
        }

        $progress = FcLeadershipProgress::forUser($referrer->id);
        $created  = [];
        $weekStart = ReferralService::nextMonday();

        foreach (FcLeadershipProgress::TIERS as $tierIdx => $tier) {
            if ((int) $progress->highest_tier_paid >= $tierIdx) {
                continue; // already paid
            }

            if ((int) $progress->direct_fc_count < (int) $tier['direct_required']) {
                break; // tiers are ordered ascending; no need to look further
            }
            if ((int) $progress->volume_bonus_vb < (int) $tier['pool_vb']) {
                break;
            }

            $rewardVb  = (float) $tier['reward_vb'];
            $usdReward = round($rewardVb * FcLeadershipProgress::vbUsdPrice(), 4);
            if ($usdReward <= 0) {
                // Still mark the tier paid so we don't spin here.
                $progress->highest_tier_paid = $tierIdx;
                $progress->save();
                continue;
            }

            // Idempotency double-check
            $existing = ReferralBonus::where('user_id', $referrer->id)
                ->where('source', 'fc_leadership')
                ->where('source_ref', 'FC-TIER-' . $tierIdx)
                ->exists();
            if ($existing) {
                $progress->highest_tier_paid = $tierIdx;
                $progress->save();
                continue;
            }

            $row = ReferralBonus::create([
                'user_id'           => $referrer->id,
                'source_user_id'    => $referrer->id,
                'source_payment_id' => $triggerPayment?->id,
                'level'             => 0,
                'percentage'        => (float) $tier['pct'],
                'source_amount'     => (float) $tier['pool_vb'],
                'bonus_amount'      => $usdReward,
                'week_start'        => $weekStart,
                'status'            => 'pending',
                'source'            => 'fc_leadership',
                'source_ref'        => 'FC-TIER-' . $tierIdx,
                'notes'             => sprintf(
                    'FC Leadership Tier %d bonus: %d direct FC referrals / %d VB pool × %.1f%% = %s VB ($%s).',
                    $tierIdx,
                    $tier['direct_required'],
                    $tier['pool_vb'],
                    $tier['pct'],
                    number_format($rewardVb, 0),
                    number_format($usdReward, 2)
                ),
            ]);

            // Sync to legacy Earnings table (same as other referral bonus types).
            \App\Models\Earnings::updateOrCreate(
                [
                    'user_id'     => $referrer->id,
                    'source_id'   => $row->id,
                    'source_type' => ReferralBonus::class,
                ],
                ['amount' => $usdReward]
            );

            // Credit to COMMISSION ChartAccount bucket so it flows into the
            // user's withdrawable cash balance (Monday cashout pipeline).
            $existingComm = (float) ChartAccount::where('user_id', $referrer->id)
                ->where('acc_type', 'COMMISSION')->sum('amount');
            ChartAccount::updateOrCreate(
                ['user_id' => $referrer->id, 'acc_type' => 'COMMISSION'],
                ['amount'  => $existingComm + $usdReward]
            );

            \App\Models\Transaction::create([
                'user_id'             => $referrer->id,
                'transaction_no'      => \App\Models\Transaction::generateTransactionNo(),
                'transaction_type'    => 'COMMISSION',
                'receiver_id'         => 0,
                'transaction_details' => json_encode([
                    'amount'        => $usdReward,
                    'description'   => "FC Leadership Tier {$tierIdx} reward",
                    'vb'            => $rewardVb,
                    'level'         => 0,
                    'percentage'    => (float) $tier['pct'],
                    'direct_refs'   => (int) $progress->direct_fc_count,
                    'pool_vb'       => (int) $tier['pool_vb'],
                    'username'      => $referrer->name,
                    'week_start'    => $weekStart->toDateString(),
                ]),
            ]);

            $progress->highest_tier_paid = $tierIdx;
            $progress->save();

            $created[] = $row;
        }

        return $created;
    }
}
