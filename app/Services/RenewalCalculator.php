<?php

namespace App\Services;

use Carbon\Carbon;

/**
 * RenewalCalculator — single source of truth for the package renewal math.
 *
 * Per spec:
 *   • Daily ROI = (package_amount × 80%) × (Adventures.percentage / 100)
 *     → Cashout (25%): withdrawable anytime, min $10
 *     → Trading Voucher (75%): accumulates, used every 30 days for renewal
 *
 *   • Renewals pay IN ARREARS for the 30-day window that just ended.
 *     The first 30 days (Day 1–30) are a grace period: you earn first,
 *     then pay renewal #1 AT day 30 to cover days 1–30. Renewal #2 is
 *     due at day 60 covering days 31–60, etc.
 *
 *   • Each regular renewal covers exactly 30 days at the full monthly fee.
 *     If the package duration is not a multiple of 30, there is ONE extra
 *     final renewal due on the EXPIRY DAY that covers the remaining days
 *     (pro-rated fee). For example, a 100-day UVP has:
 *         #1 day 30  → covers days  1–30  (30 days, full fee)
 *         #2 day 60  → covers days 31–60  (30 days, full fee)
 *         #3 day 90  → covers days 61–90  (30 days, full fee)
 *         #4 day 100 → covers days 91–100 (10 days, pro-rated)
 *     giving 30 + 30 + 30 + 10 = 100 days total covered.
 *
 *   • Renewal fee = monthly_fee × (days_covered / 30)
 *     where monthly_fee = daily_income × 75% × 30
 *                     = ($amount × 0.80 × percentage / 100) × 0.75 × 30
 *                     = $amount × percentage / 100 × 18
 *
 *   • Tokens received = renewal_fee / renewal_price  (from token_settings)
 *
 *   • Package has a FIXED expiration (set at purchase). Renewals do NOT extend
 *     it — they just settle payment for each elapsed window so income can
 *     keep flowing. Package ends at purchase date + duration regardless.
 *
 *   • Daily income is paused on any day i where the renewal that covers
 *     the preceding 30-day window hasn't been completed yet:
 *       $neededRenewal = floor((i - 1) / 30)
 *     Day 1–30   → 0 renewals needed (grace period — earn first, pay at day 30)
 *     Day 31–60  → renewal #1 must be done
 *     Day 61–90  → renewal #2 must be done
 *     Day 91–100 → renewal #3 must be done  (100-day package)
 *     Renewal #4 (final partial for 100-day) is due AT expiry (day 100)
 *     as a closing settlement — it doesn't gate in-period earning.
 */
class RenewalCalculator
{
    /**
     * Compute the full renewal breakdown for one renewal cycle.
     *
     * @param  float  $packageAmount       e.g. 1000
     * @param  float  $percentage          e.g. 2.0 (daily ROI %)
     * @param  int    $packageDuration     e.g. 100, 200, 600
     * @param  int    $renewalNumber       1-indexed (1, 2, 3, ...)
     * @param  int    $renewalsDone        how many renewals have been completed
     * @param  float  $renewalPrice        e.g. 0.0025 USD/token
     * @return array{
     *     monthly_fee: float,
     *     renewal_fee: float,
     *     tokens_received: float,
     *     days_covered: int,
     *     is_partial: bool,
     *     is_last_renewal: bool,
     *     leftover_days: int,
     *     max_renewals: int,
     *     is_valid: bool,
     *     reason: ?string
     * }
     */
    public static function compute(
        float $packageAmount,
        float $percentage,
        int $packageDuration,
        int $renewalNumber,
        int $renewalsDone,
        float $renewalPrice
    ): array {
        $maxRenewals = self::maxRenewals($packageDuration);
        $isValid = ($renewalsDone + 1) === $renewalNumber && $renewalNumber <= $maxRenewals;

        // leftover_days = remainder when package duration is divided by 30.
        // e.g. 100 → 10,  400 → 10,  180 → 0,  30 → 0
        $leftoverDays = $packageDuration % 30;

        if (!$isValid) {
            return [
                'monthly_fee'      => 0.0,
                'renewal_fee'      => 0.0,
                'tokens_received'  => 0.0,
                'days_covered'     => 0,
                'is_partial'       => false,
                'is_last_renewal'  => false,
                'leftover_days'    => $leftoverDays,
                'max_renewals'     => $maxRenewals,
                'is_valid'         => false,
                'reason'           => "Renewal #{$renewalNumber} is not valid. Max renewals for {$packageDuration}-day package: {$maxRenewals}.",
            ];
        }

        // Daily Trading Voucher = ($amount × 80% × percentage%) × 75%
        $dailyTrading = ($packageAmount * 0.80 * ($percentage / 100)) * 0.75;
        $monthlyFee   = round($dailyTrading * 30, 2);

        // The final renewal is pro-rated (covers $leftoverDays) ONLY when
        // duration is not an exact multiple of 30. All other renewals
        // (including the one due at day (maxRenewals-1)*30 — e.g. day 90
        // for a 100-day package) cover a full 30 days at the full monthly fee.
        $isLastRenewal = ($renewalNumber === $maxRenewals);
        $isPartial     = $isLastRenewal && $leftoverDays > 0;

        if ($isPartial) {
            $daysCovered = $leftoverDays;
            $renewalFee  = round($monthlyFee * ($leftoverDays / 30), 2);
        } else {
            $daysCovered = 30;
            $renewalFee  = $monthlyFee;
        }

        $tokensReceived = $renewalPrice > 0
            ? round($renewalFee / $renewalPrice, 4)
            : 0.0;

        return [
            'monthly_fee'      => $monthlyFee,
            'renewal_fee'      => $renewalFee,
            'tokens_received'  => $tokensReceived,
            'days_covered'     => $daysCovered,
            'is_partial'       => $isPartial,
            'is_last_renewal'  => $isLastRenewal,
            'leftover_days'    => $leftoverDays,
            'max_renewals'     => $maxRenewals,
            'is_valid'         => true,
            'reason'           => null,
        ];
    }

    /**
     * Compute the max number of renewals allowed for a package duration.
     *
     * Renewals pay IN ARREARS, with one renewal per 30-day window and
     * (optionally) one final pro-rated renewal on the expiry day for any
     * remainder less than 30 days. This is ceil(duration / 30).
     *
     *   100 days → ceil(100/30) = 4 renewals (30, 60, 90 full + 100 partial)
     *   400 days → ceil(400/30) = 14 renewals (13 full + 400 partial)
     *   180 days → ceil(180/30) = 6 renewals (all 30-day, no partial)
     *   30 days  → ceil(30/30)  = 1 renewal (30-day full, no partial)
     */
    public static function maxRenewals(int $packageDuration): int
    {
        if ($packageDuration <= 0) return 0;
        return (int) ceil($packageDuration / 30);
    }

    /**
     * Compute the number of renewals required to cover day index i (1-based).
     *
     * Day 1-30   → 0  (no renewal needed)
     * Day 31-60  → 1  (renewal #1 must be done at or before day i)
     * Day 61-90  → 2  (renewal #2 must be done at or before day i)
     * Day 91-100 → 3  (renewal #3 must be done at or before day i)
     *
     * Used by CalculateDailyIncome to pause income for windows where the
     * corresponding renewal has not yet been recorded.
     */
    public static function renewalsRequiredForDay(int $dayIndex): int
    {
        return (int) floor(($dayIndex - 1) / 30);
    }

    /**
     * Compute the renewal due day (days since package start) for renewal #N
     * of a package with given duration.
     *
     * Regular renewals are due every 30 days (day 30, 60, 90, …).
     * The final (pro-rated) renewal is due ON THE EXPIRY DAY when duration
     * is not a multiple of 30 — e.g. for a 100-day package, renewal #4 is
     * due at day 100 (not day 120).
     */
    public static function renewalDueDay(int $renewalNumber, int $packageDuration = 0): int
    {
        $maxRenewals = self::maxRenewals($packageDuration);
        $leftoverDays = $packageDuration % 30;
        if ($packageDuration > 0 && $leftoverDays > 0 && $renewalNumber === $maxRenewals) {
            return $packageDuration; // final pro-rated renewal due at expiry
        }
        return $renewalNumber * 30;
    }

    /**
     * Compute the daily breakdown (cashout + trading voucher) for a package.
     *
     * @param  float $amount     package investment amount (USD)
     * @param  float $percentage Adventures.percentage (daily ROI %)
     * @return array{
     *     pool_capital: float,
     *     daily_income: float,
     *     daily_cashout: float,
     *     daily_trading: float,
     * }
     */
    public static function dailyBreakdown(float $amount, float $percentage): array
    {
        $poolCapital   = round($amount * 0.80, 2);
        $dailyIncome   = round($poolCapital * ($percentage / 100), 2);
        $dailyCashout  = round($dailyIncome * 0.25, 2);
        $dailyTrading  = round($dailyIncome * 0.75, 2);

        return [
            'pool_capital'  => $poolCapital,
            'daily_income'  => $dailyIncome,
            'daily_cashout' => $dailyCashout,
            'daily_trading' => $dailyTrading,
        ];
    }

    /**
     * Build the full renewal schedule for a package (useful for UI display).
     *
     * Returns one row per renewal with the due date and expected fee/tokens.
     *
     * @return array<int, array{
     *     renewal_number: int,
     *     due_at_day: int,
     *     due_at_date: string,
     *     days_covered: int,
     *     fee: float,
     *     tokens: float,
     *     is_partial: bool
     * }>
     */
    public static function schedule(
        float $packageAmount,
        float $percentage,
        int $packageDuration,
        float $renewalPrice,
        ?Carbon $packageStart = null
    ): array {
        $packageStart = $packageStart ?? Carbon::now();
        $maxRenewals  = self::maxRenewals($packageDuration);
        $schedule     = [];

        for ($n = 1; $n <= $maxRenewals; $n++) {
            $result  = self::compute($packageAmount, $percentage, $packageDuration, $n, $n - 1, $renewalPrice);
            $dueDay  = self::renewalDueDay($n, $packageDuration);
            $schedule[] = [
                'renewal_number' => $n,
                'due_at_day'     => $dueDay,
                'due_at_date'    => $packageStart->copy()->addDays($dueDay)->toDateString(),
                'days_covered'   => $result['days_covered'],
                'fee'            => $result['renewal_fee'],
                'tokens'         => $result['tokens_received'],
                'is_partial'     => $result['is_partial'],
            ];
        }
        return $schedule;
    }
}
