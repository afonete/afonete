<?php

namespace App\Services;

use App\Models\User;
use App\Models\Payment;
use App\Models\FomIncentiveTier;
use App\Models\FomIncentiveAward;
use App\Models\ChartAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * FOM Licence Miner incentive engine.
 *
 * Rule: within `duration_days` of the sponsor's LATEST FOM activation
 * (the anchor), if the sum of DIRECT referrals' FOM investments made in
 * that window reaches `achievement`, the sponsor receives `bonus` to
 * CASHOUT immediately. Each tier can be earned once per anchor (a new
 * FOM activation restarts all windows).
 *
 * Evaluated after every direct-referral FOM activation (backend, no UI
 * needed for users) and idempotent via the (user, tier, anchor) unique key.
 */
class FomIncentiveService
{
    /**
     * The sponsor's LATEST active FOM payment — the incentive anchor.
     */
    public static function anchorPayment(User $sponsor): ?Payment
    {
        return Payment::where('user', $sponsor->id)
            ->where('status', 1)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get()
            ->first(fn ($p) => $p->isFom());
    }

    /**
     * Sum of DIRECT referrals' FOM investments inside the window.
     */
    public static function directFomVolumeInWindow(User $sponsor, Carbon $start, Carbon $end): float
    {
        $directIds = User::where('referee_id', $sponsor->id)->pluck('id');
        if ($directIds->isEmpty()) {
            return 0.0;
        }

        return (float) Payment::whereIn('user', $directIds->map(fn ($i) => (string) $i)->all())
            ->where('status', 1)
            ->whereBetween('created_at', [$start, $end])
            ->get()
            ->filter(fn ($p) => $p->isFom())
            ->sum(fn ($p) => (float) ($p->paid ?? $p->amount ?? 0));
    }

    /**
     * Evaluate all tiers for one sponsor. Called after each direct
     * referral's FOM activation. Awards every newly-met tier: bonus →
     * CASHOUT immediately + award row for the admin list.
     *
     * @return FomIncentiveAward[] newly created awards
     */
    public static function evaluateFor(?User $sponsor): array
    {
        if (!$sponsor) {
            return [];
        }

        FomIncentiveTier::ensureTableAndData();

        $anchor = self::anchorPayment($sponsor);
        if (!$anchor) {
            return []; // incentive requires the sponsor to hold a FOM package
        }

        $anchorStart = Carbon::parse($anchor->created_at);
        $now         = Carbon::now();
        $awards      = [];

        $tiers = FomIncentiveTier::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();

        foreach ($tiers as $tier) {
            $windowEnd = $anchorStart->copy()->addDays((int) $tier->duration_days);

            // Window must still be open OR the achievement met before it closed;
            // we evaluate volume only inside [anchor, windowEnd].
            $volumeEnd = $now->lt($windowEnd) ? $now : $windowEnd;
            if ($volumeEnd->lt($anchorStart)) {
                continue;
            }

            // One award per (user, tier, anchor) — idempotent.
            $already = FomIncentiveAward::where('user_id', $sponsor->id)
                ->where('tier_id', $tier->id)
                ->where('anchor_payment_id', $anchor->id)
                ->exists();
            if ($already) {
                continue;
            }

            // Only award while inside the window (achievement must happen
            // within duration_days of the anchor).
            if ($now->gt($windowEnd)) {
                // Window closed without award at evaluation time. A late
                // evaluation may still award if the volume WITHIN the window
                // reached the target (fair for cron/sync-driven checks).
                $achieved = self::directFomVolumeInWindow($sponsor, $anchorStart, $windowEnd);
            } else {
                $achieved = self::directFomVolumeInWindow($sponsor, $anchorStart, $volumeEnd);
            }

            if ($achieved + 1e-9 < (float) $tier->achievement) {
                continue;
            }

            try {
                $award = DB::transaction(function () use ($sponsor, $tier, $anchor, $anchorStart, $windowEnd, $achieved) {
                    $award = FomIncentiveAward::create([
                        'user_id'           => $sponsor->id,
                        'tier_id'           => $tier->id,
                        'achievement'       => $tier->achievement,
                        'duration_days'     => $tier->duration_days,
                        'bonus'             => $tier->bonus,
                        'achieved_amount'   => $achieved,
                        'window_start'      => $anchorStart,
                        'window_end'        => $windowEnd,
                        'awarded_at'        => Carbon::now(),
                        'anchor_payment_id' => $anchor->id,
                    ]);

                    // Bonus → CASHOUT immediately
                    ChartAccount::creditLocked(
                        $sponsor->id,
                        'CASHOUT',
                        (float) $tier->bonus,
                        "FOM incentive tier " . number_format((float) $tier->achievement) . " achieved"
                    );

                    try {
                        \App\Models\Transaction::create([
                            'user_id'             => $sponsor->id,
                            'transaction_no'      => method_exists(\App\Models\Transaction::class, 'generateTransactionNo')
                                ? \App\Models\Transaction::generateTransactionNo()
                                : 'FOM-INC-' . time() . '-' . rand(100, 999),
                            'transaction_type'    => 'FOM_INCENTIVE_BONUS',
                            'transaction_details' => json_encode([
                                'achievement'     => (float) $tier->achievement,
                                'duration_days'   => (int) $tier->duration_days,
                                'bonus'           => (float) $tier->bonus,
                                'achieved_amount' => $achieved,
                                'window_start'    => $anchorStart->toDateTimeString(),
                                'window_end'      => $windowEnd->toDateTimeString(),
                                'to'              => 'CASHOUT',
                            ]),
                        ]);
                    } catch (\Throwable $e) {
                        Log::warning('FOM incentive transaction log failed: ' . $e->getMessage());
                    }

                    return $award;
                });

                $awards[] = $award;
            } catch (\Throwable $e) {
                // Unique-key race (double evaluation) or DB failure — bonus
                // is never double-paid because the award insert leads.
                Log::error("FomIncentiveService award failed (user #{$sponsor->id}, tier #{$tier->id}): " . $e->getMessage());
            }
        }

        return $awards;
    }

    /**
     * Hook: a FOM payment was activated — evaluate its DIRECT sponsor.
     */
    public static function onFomActivation(?Payment $payment): array
    {
        if (!$payment || !$payment->isFom()) {
            return [];
        }

        $buyer = User::find($payment->user);
        if (!$buyer || empty($buyer->referee_id)) {
            return [];
        }

        $sponsor = User::find($buyer->referee_id);

        return self::evaluateFor($sponsor);
    }
}
