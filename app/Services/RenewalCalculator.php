<?php

namespace App\Services;

use Carbon\Carbon;

/**
 * RenewalCalculator — single source of truth for the package renewal math.
 *
 * Per spec (Issue 2 + 6):
 *   • Daily ROI = (package_amount × 80%) × (Adventures.percentage / 100)
 *     → Cashout (25%): withdrawable anytime, min $10
 *     → Trading Voucher (75%): accumulates, used every 30 days for renewal
 *
 *   • Each renewal covers 30 days, EXCEPT the last renewal which covers
 *     `leftover_days` if the package duration is not a multiple of 30.
 *
 *   • Renewal fee = monthly_fee × (days_covered / 30)
 *     where monthly_fee = daily_income × 75% × 30
 *                     = ($amount × 0.80 × percentage / 100) × 0.75 × 30
 *                     = $amount × percentage / 100 × 18
 *
 *   • Tokens received = renewal_fee / renewal_price  (from token_settings)
 *
 *   • Package has a FIXED expiration (set at purchase). Renewals do NOT extend
 *     it — they just unlock income for the next window(s). Package ends at
 *     purchase date + duration regardless of renewals.
 *
 *   • Daily income is paused on any day i > 30 where the renewal for that
 *     30-day window hasn't been completed yet:
 *       $neededRenewal = floor((i - 1) / 30)
 *     Day 1-30 → 0 renewals needed (free window)
 *     Day 31-60 → renewal #1 must be done
 *     Day 61-90 → renewal #2 must be done
 *     Day 91-100 → renewal #3 must be done  (for 100-day package, max=3)
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
        $maxRenewals = (int) floor(($packageDuration - 1) / 30);
        $isValid = ($renewalsDone + 1) === $renewalNumber && $renewalNumber <= $maxRenewals;

        if (!$isValid) {
            return [
                'monthly_fee'      => 0.0,
                'renewal_fee'      => 0.0,
                'tokens_received'  => 0.0,
                'days_covered'     => 0,
                'is_partial'       => false,
                'is_last_renewal'  => false,
                'leftover_days'    => $packageDuration - ($maxRenewals * 30),
                'max_renewals'     => $maxRenewals,
                'is_valid'         => false,
                'reason'           => "Renewal #{$renewalNumber} is not valid. Max renewals for {$packageDuration}-day package: {$maxRenewals}.",
            ];
        }

        // Daily Trading Voucher = ($amount × 80% × percentage%) × 75%
        $dailyTrading = ($packageAmount * 0.80 * ($percentage / 100)) * 0.75;
        $monthlyFee   = round($dailyTrading * 30, 2);

        // Days covered by this renewal (last renewal may be partial)
        $leftoverDays  = $packageDuration - ($maxRenewals * 30);
        $isLastRenewal = ($renewalNumber === $maxRenewals);
        // Partial = last renewal AND it covers fewer than 30 days.
        // When leftoverDays == 30, the last renewal is a full month and should
        // NOT be labeled as partial (it covers the same 30 days as any other).
        $isPartial     = $isLastRenewal && $leftoverDays > 0 && $leftoverDays < 30;

        if ($isLastRenewal && $leftoverDays > 0) {
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
     */
    public static function maxRenewals(int $packageDuration): int
    {
        return (int) floor(($packageDuration - 1) / 30);
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
     * Compute the renewal due date (in days from package start) for a given
     * renewal number. Renewal #N is due at day N*30.
     */
    public static function renewalDueDay(int $renewalNumber): int
    {
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
            $result = self::compute($packageAmount, $percentage, $packageDuration, $n, $n - 1, $renewalPrice);
            $schedule[] = [
                'renewal_number' => $n,
                'due_at_day'     => self::renewalDueDay($n),
                'due_at_date'    => $packageStart->copy()->addDays(self::renewalDueDay($n))->toDateString(),
                'days_covered'   => $result['days_covered'],
                'fee'            => $result['renewal_fee'],
                'tokens'         => $result['tokens_received'],
                'is_partial'     => $result['is_partial'],
            ];
        }
        return $schedule;
    }
}
