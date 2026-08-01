<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\ReferralBonus;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

/**
 * ReferralService — single source of truth for referral commission logic.
 *
 * Per spec (3-level system):
 *   L1 (direct)  : 10.00 % of the package amount
 *   L2 (indirect):  1.00 %
 *   L3 (3rd)     :  0.50 %
 *
 * Each credit becomes a row in `referral_bonuses` with:
 *   - week_start = the upcoming Monday
 *   - status     = 'pending'  (will flip to 'withdrawable' on that Monday)
 */
class ReferralService
{
    /** Commission rates per level (percentage of source payment amount) */
    public const RATES = [
        1 => 10.00,
        2 =>  1.00,
        3 =>  0.50,
    ];

    /**
     * Self-repair & sync missing referral bonuses for ALL confirmed payments in the database.
     * Guarantees that any past or present confirmed payment has its corresponding
     * referral_bonuses rows created in the database.
     */
    public static function syncMissingBonuses(): int
    {
        try {
            // 1. Repair users where Teams record exists but users.referee_id = 0 or NULL
            $teams = \App\Models\Teams::all();
            foreach ($teams as $t) {
                if ($t->user_id && $t->team_user_id) {
                    User::where('id', $t->team_user_id)
                        ->where(function ($q) {
                            $q->where('referee_id', 0)->orWhereNull('referee_id');
                        })
                        ->update(['referee_id' => $t->user_id]);
                }
            }

            // 2. Repair users where referee_id > 0 but NO Teams record exists
            $referredUsers = User::where('referee_id', '>', 0)->get();
            foreach ($referredUsers as $u) {
                $hasTeam = \App\Models\Teams::where('team_user_id', $u->id)->exists();
                if (!$hasTeam) {
                    $leftCount  = \App\Models\Teams::where('user_id', $u->referee_id)->where('side', 'LEFT')->count();
                    $rightCount = \App\Models\Teams::where('user_id', $u->referee_id)->where('side', 'RIGHT')->count();
                    $side       = ($leftCount <= $rightCount) ? 'LEFT' : 'RIGHT';

                    \App\Models\Teams::create([
                        'user_id'      => $u->referee_id,
                        'team_user_id' => $u->id,
                        'side'         => $side,
                    ]);
                }
            }

            // 3. Scan all confirmed payments (status = 1) and credit missing referral bonuses
            $payments = Payment::where('status', 1)->get();
            $totalCreated = 0;

            foreach ($payments as $payment) {
                $created = self::creditForPayment($payment);
                $totalCreated += count($created);
            }

            return $totalCreated;
        } catch (\Throwable $e) {
            \Log::error('Error in ReferralService::syncMissingBonuses: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Compute and credit referral bonuses for a newly-paid package.
     *
     * Called from PaymentController whenever a package payment is confirmed
     * (blockpayventure → successVenture / ventureCallback, etc.).
     *
     * @param  Payment  $payment  the package payment that was just paid
     * @return array    list of ReferralBonus rows created (one per level filled)
     */
    public static function creditForPayment(Payment $payment): array
    {
        $buyer = User::find($payment->user);
        if (!$buyer) return [];

        $amount = (float) ($payment->paid ?? $payment->amount ?? 0);
        if ($amount <= 0) return [];

        // Auto-repair buyer's referee_id if missing but Teams record exists
        if (empty($buyer->referee_id) || (int)$buyer->referee_id === 0) {
            $team = \App\Models\Teams::where('team_user_id', $buyer->id)->first();
            if ($team && $team->user_id) {
                $buyer->referee_id = $team->user_id;
                $buyer->save();
                $buyer->refresh();
            }
        }

        $upline    = $buyer->uplineChain(3);
        $weekStart = self::nextMonday();

        $created = [];
        foreach ($upline as $slot) {
            $referrer = $slot['user'];
            $level    = $slot['level'];
            if (!$referrer) continue;

            // Check if bonus already credited for this referrer and payment
            $alreadyCredited = ReferralBonus::where('user_id', $referrer->id)
                ->where('source_payment_id', $payment->id)
                ->where('level', $level)
                ->exists();

            if ($alreadyCredited) continue;

            $rate = self::RATES[$level] ?? 0;
            if ($rate <= 0) continue;

            $bonus = round($amount * $rate / 100, 4);

            $created[] = ReferralBonus::create([
                'user_id'           => $referrer->id,
                'source_user_id'    => $buyer->id,
                'source_payment_id' => $payment->id,
                'level'             => $level,
                'percentage'        => $rate,
                'source_amount'     => $amount,
                'bonus_amount'      => $bonus,
                'week_start'        => $weekStart,
                'status'            => 'pending',
                'source'            => 'referral',
                'source_ref'        => 'L' . $level,
                'notes'             => "L{$level} commission from " . ($buyer->name ?? $buyer->user ?? $buyer->email),
            ]);
        }
        return $created;
    }

    /**
     * Promote all `pending` rows whose week_start has arrived to `withdrawable`.
     * Returns the number of rows promoted.
     */
    public static function promotePendingToWithdrawable(?Carbon $now = null): int
    {
        $now = $now ?? Carbon::now();
        $today = $now->toDateString();

        return ReferralBonus::where('status', 'pending')
            ->where('week_start', '<=', $today)
            ->update(['status' => 'withdrawable']);
    }

    /**
     * Snapshot totals for the platform (used on admin dashboard).
     */
    public static function platformTotals(): array
    {
        self::syncMissingBonuses();

        $row = ReferralBonus::selectRaw('
            SUM(CASE WHEN status = "pending"      THEN bonus_amount ELSE 0 END) AS pending_total,
            SUM(CASE WHEN status = "withdrawable" THEN bonus_amount ELSE 0 END) AS withdrawable_total,
            SUM(CASE WHEN status = "withdrawn"    THEN bonus_amount ELSE 0 END) AS paid_total,
            SUM(CASE WHEN status NOT IN ("reversed","expired") THEN bonus_amount ELSE 0 END) AS all_time
        ')->first();

        return [
            'pending'      => (float) ($row->pending_total      ?? 0),
            'withdrawable' => (float) ($row->withdrawable_total ?? 0),
            'paid'         => (float) ($row->paid_total         ?? 0),
            'all_time'     => (float) ($row->all_time           ?? 0),
        ];
    }

    /** Next Monday's date (today if today is Monday) */
    public static function nextMonday(?Carbon $now = null): Carbon
    {
        $now = $now ?? Carbon::now();
        // Carbon's isMonday() returns true if today is Monday
        if ($now->isMonday()) {
            return $now->copy()->startOfDay();
        }
        return $now->copy()->next(Carbon::MONDAY)->startOfDay();
    }

    /** Check if today is Monday (for withdrawal window) */
    public static function isMondayNow(?Carbon $now = null): bool
    {
        return ($now ?? Carbon::now())->isMonday();
    }
}
