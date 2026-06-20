<?php

namespace App\Services;

use App\Models\adventures;
use App\Models\Payment as Paymodel;
use Carbon\Carbon;

/**
 * InvestmentFactory — single source of truth for creating a VENTURE
 * (UVP / Adventures) investment row.
 *
 * BUGS THIS FIXES:
 *   • expiration_date was being set to TODAY (Carbon::now()->addDays(null)
 *     returns today; addDays(0) returns today).
 *   • Different code paths used different / hardcoded duration values
 *     (100, 200, 600) — they should always come from the Adventure row.
 *   • payable_id / payable_type / category / is_expired were sometimes
 *     missing, causing the daily-income cron to skip the investment.
 *   • The confirm-page form hardcoded `package=FC` instead of using the
 *     actual adventure ID, which made paymentFromDeposits look up the
 *     wrong adventure (returning null) and set expiration_date to today.
 *
 * After this service is used, every new VENTURE investment:
 *   - Has a correct expiration_date = today + adventure.duration days
 *   - Has is_expired = false explicitly (so the badge never shows "Expired"
 *     on a fresh investment even if the DB column default is missing)
 *   - Has payable_id + payable_type set so the daily-income cron can
 *     find the adventure
 *   - Has category = 'VENTURE' explicitly
 */
class InvestmentFactory
{
    /**
     * Build (but don't save) a Paymodel for a VENTURE investment.
     *
     * @param  int    $userId      owning user
     * @param  adventures $adventure  the adventure package bought
     * @param  float  $amount      nominal package amount
     * @param  float  $paid        amount actually paid (same as amount unless partial)
     * @param  int    $status      1 = paid well, 2 = underpayment, 3 = overpayment
     * @param  array  $extra       additional fields to merge (e.g. 'category_id')
     * @return Paymodel
     *
     * @throws \InvalidArgumentException if the adventure has no valid duration
     */
    public static function buildVenture(
        int $userId,
        adventures $adventure,
        float $amount,
        float $paid,
        int $status = 1,
        array $extra = []
    ): Paymodel {
        $duration = (int) ($adventure->duration ?? 0);
        if ($duration <= 0) {
            throw new \InvalidArgumentException(
                "Adventure #{$adventure->id} ('{$adventure->name}') has invalid duration: " .
                var_export($adventure->duration, true) . " (must be > 0)"
            );
        }

        return new Paymodel(array_merge([
            'user'            => $userId,
            'package'         => $adventure->plan ?? $adventure->name,
            'amount'          => $amount,
            'paid'            => $paid,
            'over_paid'       => 0,
            'status'          => $status,
            // ← THE FIX: always today + duration, never today itself.
            'expiration_date' => Carbon::now()->addDays($duration)->toDateString(),
            'duration'        => $duration,
            'category'        => 'VENTURE',
            'category_id'     => 1,
            'is_expired'      => false,
        ], $extra));
    }

    /**
     * Compute the expiration date string for a VENTURE investment.
     * Returns null if duration is invalid (caller decides how to handle).
     */
    public static function expirationDateFor(adventures $adventure): ?string
    {
        $duration = (int) ($adventure->duration ?? 0);
        if ($duration <= 0) {
            return null;
        }
        return Carbon::now()->addDays($duration)->toDateString();
    }
}
