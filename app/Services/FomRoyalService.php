<?php

namespace App\Services;

use App\Models\User;
use App\Models\Payment;
use App\Models\ChartAccount;
use App\Models\FomLicenceMiner;
use App\Models\FomRoyalTier;
use App\Models\FomRoyalAward;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Royal Leader Bonus engine (VIP1–VIP5).
 *
 * 14 DAYS RULE: a user who has not activated a STARTER package within 14
 *   days of SIGNUP is not eligible at all.
 * Duration window (per user decision): starts at the user's STARTER
 *   activation. Direct Sponsors + VB earnings must be achieved inside it.
 * Direct Sponsor: direct referrals whose FOM payment for the required
 *   package was created inside the window.
 * Bonus Earn Requirement: total VB (both legs, accrual rows) inside the
 *   window.
 * Approval (admin): admin inputs the focoin price → prize_pool ÷ price
 *   tokens → AVAILABLE_TOKEN (user decision) + Auto Promotion: if the user
 *   holds ≥ hold_from package, grant_to is auto-activated like a normal
 *   FOM activation (Payment + ESCROW_TOKEN + installments + referral
 *   accruals + incentives).
 */
class FomRoyalService
{
    /** STARTER activation payment (first), or null. */
    public static function starterActivation(int $userId): ?Payment
    {
        try {
            return Payment::where('user', (string) $userId)
                ->onlyFom()
                ->where('status', '1')
                ->whereRaw('UPPER(COALESCE(category, package)) = ?', ['STARTER'])
                ->orderBy('created_at')
                ->first();
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * 14 DAYS RULE: eligible only when a STARTER was activated within 14
     * days of signup.
     * @return array{eligible: bool, reason: string, starter_at: ?string, deadline: ?string}
     */
    public static function eligibility(User $user): array
    {
        $signup = Carbon::parse($user->created_at);
        $deadline = $signup->copy()->addDays(14);
        $starter = self::starterActivation($user->id);

        if (!$starter) {
            $stillOpen = Carbon::now()->lte($deadline);
            return [
                'eligible'   => false,
                'reason'     => $stillOpen
                    ? 'No Starter package yet — activate one before ' . $deadline->format('d M Y') . ' to stay eligible.'
                    : 'Not eligible: no Starter package was activated within 14 days of signup.',
                'starter_at' => null,
                'deadline'   => $deadline->toDateTimeString(),
            ];
        }

        $starterAt = Carbon::parse($starter->created_at);
        if ($starterAt->gt($deadline)) {
            return [
                'eligible'   => false,
                'reason'     => 'Not eligible: Starter was activated ' . $starterAt->format('d M Y') . ', after the 14-day deadline (' . $deadline->format('d M Y') . ').',
                'starter_at' => $starterAt->toDateTimeString(),
                'deadline'   => $deadline->toDateTimeString(),
            ];
        }

        return [
            'eligible'   => true,
            'reason'     => 'Eligible — Starter activated within 14 days of signup.',
            'starter_at' => $starterAt->toDateTimeString(),
            'deadline'   => $deadline->toDateTimeString(),
        ];
    }

    /** Direct referrals who activated $package (FOM payment) inside the window. */
    public static function directSponsorsOf(int $userId, string $package, Carbon $from, Carbon $to): int
    {
        try {
            $directIds = User::where('referee_id', $userId)->pluck('id')->map(fn ($i) => (string) $i)->all();
            if (empty($directIds)) {
                return 0;
            }

            $count = 0;
            foreach (array_chunk($directIds, 500) as $chunk) {
                $count += Payment::whereIn('user', $chunk)
                    ->onlyFom()
                    ->where('status', '1')
                    ->whereRaw('UPPER(COALESCE(category, package)) = ?', [strtoupper(trim($package))])
                    ->whereBetween('created_at', [$from, $to])
                    ->distinct('user')
                    ->count(DB::raw('DISTINCT user'));
            }
            return $count;
        } catch (\Throwable $e) {
            Log::error("FomRoyalService directSponsorsOf failed for #{$userId}/{$package}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Qualification check for one user × one tier.
     * @return array{qualified: bool, window_start: ?string, window_end: ?string, checks: array}
     */
    public static function qualification(User $user, FomRoyalTier $tier): array
    {
        $checks = [];

        $elig = self::eligibility($user);
        $checks['fourteen_day_rule'] = ['need' => 'Starter within 14 days of signup', 'have' => $elig['reason'], 'pass' => $elig['eligible']];

        if (!$elig['eligible']) {
            return ['qualified' => false, 'window_start' => null, 'window_end' => null, 'checks' => $checks];
        }

        $windowStart = Carbon::parse($elig['starter_at']);
        $windowEnd   = $windowStart->copy()->addDays((int) $tier->duration_days);

        // Direct sponsors per required package, inside the window
        $allSponsorsPass = true;
        foreach ((array) ($tier->sponsors_json ?: []) as $req) {
            $pkg = strtoupper(trim((string) ($req['package'] ?? '')));
            $need = (int) ($req['count'] ?? 0);
            $have = self::directSponsorsOf($user->id, $pkg, $windowStart, $windowEnd);
            $pass = $have >= $need;
            $allSponsorsPass = $allSponsorsPass && $pass;
            $checks['sponsors_' . strtolower(str_replace(' ', '_', $pkg))] = ['need' => $need, 'have' => $have, 'pass' => $pass];
        }

        // VB earned inside the window (both legs)
        $vb = FomReferralService::sideVolumeBonusTotals($user->id, $windowStart, $windowEnd);
        $vbHave = (float) $vb['total'];
        $checks['vb_earned'] = ['need' => (float) $tier->vb_earn_required, 'have' => $vbHave, 'pass' => $vbHave >= (float) $tier->vb_earn_required];

        $qualified = collect($checks)->every(fn ($c) => $c['pass']);

        return [
            'qualified'    => $qualified,
            'window_start' => $windowStart->toDateTimeString(),
            'window_end'   => $windowEnd->toDateTimeString(),
            'checks'       => $checks,
        ];
    }

    /** Detect qualifying tiers for one user → pending awards. @return int created */
    public static function detectFor(User $user): int
    {
        FomRoyalTier::ensureTableAndData();
        FomRoyalAward::ensureTable();

        $created = 0;

        try {
            foreach (FomRoyalTier::ordered()->get() as $tier) {
                $exists = FomRoyalAward::where('user_id', $user->id)->where('tier_id', $tier->id)->exists();
                if ($exists) {
                    continue;
                }

                $q = self::qualification($user, $tier);
                if (!$q['qualified']) {
                    continue; // tiers are independent — check them all
                }

                FomRoyalAward::create([
                    'user_id'           => $user->id,
                    'tier_id'           => $tier->id,
                    'tier_name'         => $tier->name,
                    'status'            => 'pending',
                    'window_start'      => $q['window_start'],
                    'window_end'        => $q['window_end'],
                    'detected_at'       => Carbon::now(),
                    'criteria_snapshot' => $q['checks'],
                ]);
                $created++;
            }
        } catch (\Throwable $e) {
            Log::error("FomRoyalService detectFor failed for #{$user->id}: " . $e->getMessage());
        }

        return $created;
    }

    /** Sweep all users with FOM payments (weekly cron). @return int created */
    public static function detectAll(): int
    {
        FomRoyalTier::ensureTableAndData();
        FomRoyalAward::ensureTable();

        $created = 0;
        try {
            $userIds = Payment::query()->onlyFom()->where('status', '1')->pluck('user')->unique();
            foreach ($userIds as $uid) {
                $user = User::find((int) $uid);
                if ($user) {
                    $created += self::detectFor($user);
                }
            }
        } catch (\Throwable $e) {
            Log::error('FomRoyalService detectAll failed: ' . $e->getMessage());
        }

        return $created;
    }

    /**
     * ADMIN approval: focoin price → prize_pool ÷ price tokens to
     * AVAILABLE_TOKEN, + Auto Promotion (grant_to auto-activated like a
     * normal FOM package if the user holds ≥ hold_from). Atomic.
     */
    public static function approve(int $awardId, int $adminId, float $focoinPrice, ?string $notes = null): array
    {
        if ($focoinPrice <= 0) {
            return ['ok' => false, 'message' => 'Focoin price must be greater than zero.'];
        }

        try {
            return DB::transaction(function () use ($awardId, $adminId, $focoinPrice, $notes) {
                $award = FomRoyalAward::lockForUpdate()->find($awardId);
                if (!$award || $award->status !== 'pending') {
                    return ['ok' => false, 'message' => 'Award is not pending (or does not exist).'];
                }

                $tier = FomRoyalTier::find($award->tier_id);
                if (!$tier) {
                    return ['ok' => false, 'message' => 'Tier configuration missing.'];
                }

                // 1. PRIZE POOL → equivalent focoin into AVAILABLE_TOKEN
                $tokens = round((float) $tier->prize_pool_usd / $focoinPrice, 4);
                ChartAccount::creditLocked($award->user_id, 'AVAILABLE_TOKEN', $tokens,
                    "Royal Leader Bonus {$tier->name}: \${$tier->prize_pool_usd} ÷ {$focoinPrice}");

                // 2. AUTO PROMOTION: user holds ≥ hold_from → grant_to auto-activated
                $promoPaymentId = null;
                $holdRank  = FomRankService::highestLicenceRank((int) $award->user_id);
                $needRank  = \App\Models\FomRank::LICENCE_ORDER[strtoupper(trim((string) $tier->promo_hold_package))] ?? 0;
                $promoNote = 'not granted: user does not hold ' . $tier->promo_hold_package;

                if ($holdRank >= $needRank) {
                    $grant = FomLicenceMiner::whereRaw('UPPER(name) = ?', [strtoupper(trim((string) $tier->promo_grant_package))])->first();
                    if ($grant) {
                        $tokensReturn = FomLicenceMiner::cleanNum($grant->tokens ?? 0);

                        $promo = Payment::create([
                            'user'            => $award->user_id,
                            'package'         => $grant->name,
                            'amount'          => FomLicenceMiner::cleanNum($grant->price),
                            'paid'            => FomLicenceMiner::cleanNum($grant->price),
                            'over_paid'       => 0,
                            'status'          => 1,
                            'is_expired'      => false,
                            'duration'        => 600,
                            'category'        => $grant->name ?: 'FOM',
                            'category_id'     => 1,
                            'expiration_date' => Carbon::now()->addDays(600)->toDateTimeString(),
                        ]);
                        $promoPaymentId = $promo->id;

                        if ($tokensReturn > 0) {
                            ChartAccount::creditLocked($award->user_id, 'ESCROW_TOKEN', $tokensReturn,
                                "Royal Leader auto-promotion: {$grant->name}");
                            try {
                                \App\Models\FomTokenInstallment::createSchedule($award->user_id, 0, $grant->name, $tokensReturn);
                            } catch (\Throwable $e) {
                                Log::warning('Royal promo installment schedule failed: ' . $e->getMessage());
                            }
                        }

                        // Referral accruals + incentives like a normal activation
                        try {
                            FomReferralService::creditForFomPurchase($promo);
                        } catch (\Throwable $e) {
                            Log::warning('Royal promo referral accrual failed: ' . $e->getMessage());
                        }
                        try {
                            FomIncentiveService::onFomActivation($promo);
                        } catch (\Throwable $e) {
                            // incentive service optional
                        }

                        $promoNote = "granted: {$grant->name} auto-activated (payment #{$promo->id})";
                    } else {
                        $promoNote = 'not granted: package ' . $tier->promo_grant_package . ' not found';
                    }
                }

                $award->update([
                    'status'           => 'approved',
                    'reviewed_at'      => Carbon::now(),
                    'reviewed_by'      => $adminId,
                    'focoin_price'     => $focoinPrice,
                    'tokens_paid'      => $tokens,
                    'promo_payment_id' => $promoPaymentId,
                    'admin_notes'      => trim(($notes ? $notes . ' · ' : '') . $promoNote),
                ]);

                try {
                    \App\Models\Transaction::create([
                        'user_id'             => $award->user_id,
                        'transaction_no'      => method_exists(\App\Models\Transaction::class, 'generateTransactionNo')
                            ? \App\Models\Transaction::generateTransactionNo()
                            : 'FOM-ROYAL-' . time() . '-' . rand(100, 999),
                        'transaction_type'    => 'FOM_ROYAL_LEADER_BONUS',
                        'transaction_details' => json_encode([
                            'tier'         => $award->tier_name,
                            'prize_pool'   => (float) $tier->prize_pool_usd,
                            'focoin_price' => $focoinPrice,
                            'tokens'       => $tokens,
                            'promotion'    => $promoNote,
                        ]),
                    ]);
                } catch (\Throwable $e) {
                    Log::warning('Royal bonus transaction log failed: ' . $e->getMessage());
                }

                return ['ok' => true, 'message' => "Approved: {$tokens} focoin credited · {$promoNote}."];
            });
        } catch (\Throwable $e) {
            Log::error("FomRoyalService approve failed for award #{$awardId}: " . $e->getMessage());
            return ['ok' => false, 'message' => 'Approval failed — nothing was changed.'];
        }
    }

    public static function reject(int $awardId, int $adminId, ?string $notes = null): bool
    {
        try {
            $award = FomRoyalAward::find($awardId);
            if (!$award || $award->status !== 'pending') {
                return false;
            }
            $award->update([
                'status'      => 'rejected',
                'reviewed_at' => Carbon::now(),
                'reviewed_by' => $adminId,
                'admin_notes' => $notes,
            ]);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
