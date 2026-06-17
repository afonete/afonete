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
        $buyer   = User::find($payment->user);
        if (!$buyer) return [];

        $amount  = (float) $payment->amount;
        if ($amount <= 0) return [];

        $upline  = $buyer->uplineChain(3);
        $weekStart = self::nextMonday();

        $created = [];
        foreach ($upline as $slot) {
            $referrer = $slot['user'];
            $level    = $slot['level'];
            if (!$referrer) continue;

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
                'notes'             => "L{$level} commission from " . ($buyer->name ?? $buyer->email),
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
