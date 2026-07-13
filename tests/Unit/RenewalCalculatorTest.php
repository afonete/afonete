<?php

namespace Tests\Unit\Services;

use App\Services\RenewalCalculator;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for RenewalCalculator — the source of truth for the renewal fee
 * math, partial-month pro-rating, and income-pause window logic.
 *
 * Covers three real-world package durations from the Adventures table:
 *   • 100 days  → max 3 renewals, last one covers 10 days (partial)
 *   • 200 days  → max 6 renewals, last one covers 20 days (partial)
 *   • 600 days  → max 19 renewals, last one covers 30 days (full month)
 *
 * Plus edge cases:
 *   • 30 days   → max 0 renewals
 *   • 60 days   → max 1 renewal (full month)
 *   • 90 days   → max 2 renewals (both full months)
 *   • 31 days   → max 1 renewal covering 1 day (partial)
 */
class RenewalCalculatorTest extends TestCase
{
    // ──────────────────────────────────────────────────────────────────
    //  maxRenewals() — derive renewal count from package duration
    // ──────────────────────────────────────────────────────────────────

    /** @test */
    public function max_renewals_returns_zero_for_thirty_day_package(): void
    {
        $this->assertSame(0, RenewalCalculator::maxRenewals(30));
    }

    /** @test */
    public function max_renewals_returns_one_for_sixty_day_package(): void
    {
        $this->assertSame(1, RenewalCalculator::maxRenewals(60));
    }

    /** @test */
    public function max_renewals_returns_two_for_ninety_day_package(): void
    {
        $this->assertSame(2, RenewalCalculator::maxRenewals(90));
    }

    /** @test */
    public function max_renewals_returns_three_for_hundred_day_package(): void
    {
        $this->assertSame(3, RenewalCalculator::maxRenewals(100));
    }

    /** @test */
    public function max_renewals_returns_six_for_two_hundred_day_package(): void
    {
        $this->assertSame(6, RenewalCalculator::maxRenewals(200));
    }

    /** @test */
    public function max_renewals_returns_nineteen_for_six_hundred_day_package(): void
    {
        $this->assertSame(19, RenewalCalculator::maxRenewals(600));
    }

    /** @test */
    public function max_renewals_returns_one_for_thirty_one_day_package(): void
    {
        // 31 days = 1 month + 1 day leftover → 1 renewal covering 1 day
        $this->assertSame(1, RenewalCalculator::maxRenewals(31));
    }

    // ──────────────────────────────────────────────────────────────────
    //  renewalsRequiredForDay() — income pause formula
    // ──────────────────────────────────────────────────────────────────

    /**
     * @test
     * @dataProvider renewalDayProvider
     */
    public function renewals_required_for_day_matches_spec(int $dayIndex, int $expected): void
    {
        $this->assertSame(
            $expected,
            RenewalCalculator::renewalsRequiredForDay($dayIndex),
            "Day {$dayIndex} should require {$expected} renewal(s)."
        );
    }

    public static function renewalDayProvider(): array
    {
        return [
            'day 1'   => [1, 0],
            'day 15'  => [15, 0],
            'day 30'  => [30, 0],   // last day of the free window
            'day 31'  => [31, 1],   // renewal #1 required
            'day 45'  => [45, 1],
            'day 60'  => [60, 1],
            'day 61'  => [61, 2],   // renewal #2 required
            'day 90'  => [90, 2],
            'day 91'  => [91, 3],   // renewal #3 required
            'day 100' => [100, 3],
            'day 120' => [120, 3],  // for 100-day pkg, would exceed — would not be reached
            'day 200' => [200, 6],  // for 200-day pkg
            'day 600' => [600, 19], // for 600-day pkg
        ];
    }

    // ──────────────────────────────────────────────────────────────────
    //  100-DAY PACKAGE (Issue 2 spec example: $1000 @ 2% ROI)
    // ──────────────────────────────────────────────────────────────────

    /** @test */
    public function hundred_day_package_renewal_one_is_full_month(): void
    {
        $r = RenewalCalculator::compute(
            packageAmount:   1000.0,
            percentage:      2.0,
            packageDuration: 100,
            renewalNumber:   1,
            renewalsDone:    0,
            renewalPrice:    0.0025
        );

        // Monthly fee = 1000 × 0.80 × 2% × 0.75 × 30 = $360
        $this->assertSame(360.0, $r['monthly_fee']);
        $this->assertSame(360.0, $r['renewal_fee']);
        $this->assertSame(30,    $r['days_covered']);
        $this->assertFalse($r['is_partial']);
        $this->assertFalse($r['is_last_renewal']);
        $this->assertSame(3,     $r['max_renewals']);
        $this->assertSame(10,    $r['leftover_days']);
        // 360 / 0.0025 = 144,000 tokens
        $this->assertSame(144000.0, $r['tokens_received']);
        $this->assertTrue($r['is_valid']);
    }

    /** @test */
    public function hundred_day_package_renewal_three_is_partial_ten_days(): void
    {
        $r = RenewalCalculator::compute(
            packageAmount:   1000.0,
            percentage:      2.0,
            packageDuration: 100,
            renewalNumber:   3,
            renewalsDone:    2,
            renewalPrice:    0.0025
        );

        // Last renewal covers 10 leftover days (100 - 3×30 = 10)
        $this->assertSame(10, $r['days_covered']);
        $this->assertSame(120.0, $r['renewal_fee']);    // 360 × (10/30)
        $this->assertTrue($r['is_partial']);
        $this->assertTrue($r['is_last_renewal']);
        $this->assertSame(48000.0, $r['tokens_received']); // 120 / 0.0025
    }

    /** @test */
    public function hundred_day_package_third_renewal_rejects_after_max(): void
    {
        $r = RenewalCalculator::compute(
            packageAmount:   1000.0,
            percentage:      2.0,
            packageDuration: 100,
            renewalNumber:   4,         // past the limit
            renewalsDone:    3,
            renewalPrice:    0.0025
        );

        $this->assertFalse($r['is_valid']);
        $this->assertNotNull($r['reason']);
        $this->assertSame(0.0, $r['renewal_fee']);
    }

    // ──────────────────────────────────────────────────────────────────
    //  200-DAY PACKAGE
    // ──────────────────────────────────────────────────────────────────

    /** @test */
    public function two_hundred_day_package_has_six_renewals(): void
    {
        $r = RenewalCalculator::compute(
            packageAmount:   1000.0,
            percentage:      2.0,
            packageDuration: 200,
            renewalNumber:   6,
            renewalsDone:    5,
            renewalPrice:    0.0025
        );

        $this->assertSame(6, $r['max_renewals']);
        // Last renewal covers 20 leftover days (200 - 6×30 = 20)
        $this->assertSame(20, $r['days_covered']);
        $this->assertSame(240.0, $r['renewal_fee']);    // 360 × (20/30)
        $this->assertTrue($r['is_partial']);
        $this->assertSame(96000.0, $r['tokens_received']); // 240 / 0.0025
    }

    /** @test */
    public function two_hundred_day_package_first_renewal_is_full_month(): void
    {
        $r = RenewalCalculator::compute(
            packageAmount:   1000.0,
            percentage:      2.0,
            packageDuration: 200,
            renewalNumber:   1,
            renewalsDone:    0,
            renewalPrice:    0.0025
        );

        $this->assertSame(360.0, $r['renewal_fee']);
        $this->assertSame(30, $r['days_covered']);
        $this->assertFalse($r['is_partial']);
    }

    // ──────────────────────────────────────────────────────────────────
    //  600-DAY PACKAGE
    // ──────────────────────────────────────────────────────────────────

    /** @test */
    public function six_hundred_day_package_last_renewal_is_full_month_not_partial(): void
    {
        // For 600-day package: max=19, leftover=30 (= full month)
        // Critical edge case: last renewal should NOT be labeled "partial"
        // because the fee is identical to a regular monthly fee.
        $r = RenewalCalculator::compute(
            packageAmount:   1000.0,
            percentage:      2.0,
            packageDuration: 600,
            renewalNumber:   19,
            renewalsDone:    18,
            renewalPrice:    0.0025
        );

        $this->assertSame(19, $r['max_renewals']);
        $this->assertSame(30, $r['days_covered']);
        $this->assertSame(360.0, $r['renewal_fee']);     // full month fee
        $this->assertFalse($r['is_partial']);            // NOT partial!
        $this->assertTrue($r['is_last_renewal']);
        $this->assertSame(144000.0, $r['tokens_received']);
    }

    /** @test */
    public function six_hundred_day_package_first_eighteen_renewals_are_full_month(): void
    {
        // All 18 first renewals should be identical full-month renewals
        for ($n = 1; $n <= 18; $n++) {
            $r = RenewalCalculator::compute(
                packageAmount:   1000.0,
                percentage:      2.0,
                packageDuration: 600,
                renewalNumber:   $n,
                renewalsDone:    $n - 1,
                renewalPrice:    0.0025
            );

            $this->assertSame(360.0, $r['renewal_fee'], "Renewal #{$n} fee mismatch");
            $this->assertSame(30,    $r['days_covered'], "Renewal #{$n} days mismatch");
            $this->assertFalse($r['is_partial'], "Renewal #{$n} should not be partial");
        }
    }

    // ──────────────────────────────────────────────────────────────────
    //  dailyBreakdown() — Cashout 25% / Trading Voucher 75%
    // ──────────────────────────────────────────────────────────────────

    /** @test */
    public function daily_breakdown_for_thousand_dollar_package_at_two_percent(): void
    {
        $d = RenewalCalculator::dailyBreakdown(1000.0, 2.0);

        $this->assertSame(800.0, $d['pool_capital']);   // 1000 × 80%
        $this->assertSame(16.0,  $d['daily_income']);   // 800 × 2%
        $this->assertSame(4.0,   $d['daily_cashout']);  // 16 × 25%
        $this->assertSame(12.0,  $d['daily_trading']);  // 16 × 75%
    }

    /** @test */
    public function daily_breakdown_for_five_hundred_dollar_package_at_three_percent(): void
    {
        $d = RenewalCalculator::dailyBreakdown(500.0, 3.0);

        $this->assertSame(400.0, $d['pool_capital']);   // 500 × 80%
        $this->assertSame(12.0,  $d['daily_income']);   // 400 × 3%
        $this->assertSame(3.0,   $d['daily_cashout']);  // 12 × 25%
        $this->assertSame(9.0,   $d['daily_trading']);  // 12 × 75%
    }

    // ──────────────────────────────────────────────────────────────────
    //  schedule() — full renewal schedule for the UI
    // ──────────────────────────────────────────────────────────────────

    /** @test */
    public function schedule_for_hundred_day_package_lists_three_renewals(): void
    {
        $schedule = RenewalCalculator::schedule(
            packageAmount:   1000.0,
            percentage:      2.0,
            packageDuration: 100,
            renewalPrice:    0.0025,
            packageStart:    Carbon::parse('2026-01-01')
        );

        $this->assertCount(3, $schedule);

        // Renewal #1: day 30, full month, $360, NOT partial
        $this->assertSame(1,  $schedule[0]['renewal_number']);
        $this->assertSame(30, $schedule[0]['due_at_day']);
        $this->assertSame(360.0, $schedule[0]['fee']);
        $this->assertFalse($schedule[0]['is_partial']);
        $this->assertSame('2026-01-31', $schedule[0]['due_at_date']);

        // Renewal #2: day 60, full month, $360
        $this->assertSame(2,  $schedule[1]['renewal_number']);
        $this->assertSame(60, $schedule[1]['due_at_day']);
        $this->assertSame(360.0, $schedule[1]['fee']);

        // Renewal #3: day 90, partial 10 days, $120
        $this->assertSame(3,    $schedule[2]['renewal_number']);
        $this->assertSame(90,   $schedule[2]['due_at_day']);
        $this->assertSame(120.0, $schedule[2]['fee']);
        $this->assertTrue($schedule[2]['is_partial']);
        $this->assertSame(10,   $schedule[2]['days_covered']);
    }

    /** @test */
    public function schedule_for_six_hundred_day_package_lists_nineteen_renewals(): void
    {
        $schedule = RenewalCalculator::schedule(
            packageAmount:   1000.0,
            percentage:      2.0,
            packageDuration: 600,
            renewalPrice:    0.0025,
            packageStart:    Carbon::parse('2026-01-01')
        );

        $this->assertCount(19, $schedule);
        // Every renewal except the last should be a full $360
        for ($i = 0; $i < 18; $i++) {
            $this->assertSame(360.0, $schedule[$i]['fee']);
            $this->assertFalse($schedule[$i]['is_partial']);
        }
        // The 19th should also be $360 but NOT partial (leftover=30 = full month)
        $this->assertSame(360.0, $schedule[18]['fee']);
        $this->assertFalse($schedule[18]['is_partial']);
    }

    // ──────────────────────────────────────────────────────────────────
    //  Edge cases
    // ──────────────────────────────────────────────────────────────────

    /** @test */
    public function zero_renewal_price_yields_zero_tokens(): void
    {
        $r = RenewalCalculator::compute(
            packageAmount:   1000.0,
            percentage:      2.0,
            packageDuration: 100,
            renewalNumber:   1,
            renewalsDone:    0,
            renewalPrice:    0.0   // defensive: don't divide by zero
        );

        $this->assertSame(0.0, $r['tokens_received']);
        $this->assertSame(360.0, $r['renewal_fee']); // fee is unaffected
    }

    /** @test */
    public function invalid_renewal_number_returns_invalid_result(): void
    {
        $r = RenewalCalculator::compute(
            packageAmount:   1000.0,
            percentage:      2.0,
            packageDuration: 100,
            renewalNumber:   99,    // way past the limit
            renewalsDone:    3,
            renewalPrice:    0.0025
        );

        $this->assertFalse($r['is_valid']);
        $this->assertStringContainsString('Max renewals', $r['reason']);
        $this->assertSame(0.0, $r['renewal_fee']);
    }

    /** @test */
    public function total_fees_paid_for_hundred_day_package_equals_correct_sum(): void
    {
        // Total paid over the 3-renewal cycle should be 360 + 360 + 120 = $840
        // This is the renewal cost over 100 days. User's TRADING voucher
        // accumulates $12/day × 100 = $1200 if all renewals done, leaving $360.
        $total = 0.0;
        for ($n = 1; $n <= 3; $n++) {
            $r = RenewalCalculator::compute(
                packageAmount:   1000.0,
                percentage:      2.0,
                packageDuration: 100,
                renewalNumber:   $n,
                renewalsDone:    $n - 1,
                renewalPrice:    0.0025
            );
            $total += $r['renewal_fee'];
        }

        $this->assertSame(840.0, $total);
    }

    /** @test */
    public function total_fees_paid_for_six_hundred_day_package(): void
    {
        // All 19 renewals at full $360 = $6,840 total.
        // User's TRADING voucher over 600 days = $12 × 600 = $7,200.
        // Net TRADING balance = $7,200 - $6,840 = $360 if all renewals done.
        $total = 0.0;
        for ($n = 1; $n <= 19; $n++) {
            $r = RenewalCalculator::compute(
                packageAmount:   1000.0,
                percentage:      2.0,
                packageDuration: 600,
                renewalNumber:   $n,
                renewalsDone:    $n - 1,
                renewalPrice:    0.0025
            );
            $total += $r['renewal_fee'];
        }

        $this->assertSame(6840.0, $total);
    }
}
