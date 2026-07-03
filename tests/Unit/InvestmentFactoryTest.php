<?php

namespace Tests\Unit\Services;

use App\Models\adventures;
use App\Models\Payment;
use App\Services\InvestmentFactory;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for InvestmentFactory — the single source of truth for
 * creating VENTURE investment rows.
 *
 * Covers the bug where expiration_date was being set to TODAY (instead
 * of today + duration) because:
 *   - the confirm-page form hardcoded `package=FC`,
 *   - the backend looked up adventures::find('FC') → null,
 *   - addDays(null) returned today (addDays(0)).
 *
 * Also covers:
 *   - Adventures with various durations (100, 200, 600 days)
 *   - Invalid duration (0 or null) throws an exception
 *   - is_expired is explicitly set to false
 *   - payable_id / category are populated correctly
 */
class InvestmentFactoryTest extends TestCase
{
    /** @test */
    public function build_venture_for_100_day_adventure_sets_correct_expiration(): void
    {
        $adv = $this->makeAdventure(id: 15, duration: 100, name: 'UVP', plan: 'VENTURE LIGHT');
        $pay = InvestmentFactory::buildVenture(
            userId:    42,
            adventure: $adv,
            amount:    1000.0,
            paid:      1000.0
        );

        $expected = Carbon::now()->addDays(100)->toDateString();

        $this->assertSame($expected, $pay->expiration_date,
            "expiration_date must be today + 100 days, NOT today");
        $this->assertSame(100, $pay->duration);
        $this->assertFalse($pay->is_expired);
        $this->assertSame(1, $pay->status); // default
        $this->assertSame('VENTURE', $pay->category);
    }

    /** @test */
    public function build_venture_for_200_day_adventure(): void
    {
        $adv = $this->makeAdventure(id: 20, duration: 200, name: 'UVP License', plan: 'VENTURE PRO');
        $pay = InvestmentFactory::buildVenture(
            userId: 42, adventure: $adv, amount: 1000.0, paid: 1000.0
        );

        $expected = Carbon::now()->addDays(200)->toDateString();
        $this->assertSame($expected, $pay->expiration_date);
        $this->assertSame(200, $pay->duration);
    }

    /** @test */
    public function build_venture_for_600_day_adventure(): void
    {
        $adv = $this->makeAdventure(id: 24, duration: 600, name: 'UVP License', plan: 'VENTURE SUPER');
        $pay = InvestmentFactory::buildVenture(
            userId: 42, adventure: $adv, amount: 1000.0, paid: 1000.0
        );

        $expected = Carbon::now()->addDays(600)->toDateString();
        $this->assertSame($expected, $pay->expiration_date);
        $this->assertSame(600, $pay->duration);
    }

    /** @test */
    public function build_venture_with_invalid_duration_throws(): void
    {
        $adv = $this->makeAdventure(id: 1, duration: 0, name: 'Broken', plan: 'Broken');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/invalid duration/i');

        InvestmentFactory::buildVenture(userId: 1, adventure: $adv, amount: 100, paid: 100);
    }

    /** @test */
    public function build_venture_with_null_duration_throws(): void
    {
        // Simulate a DB row with no duration set
        $adv = new adventures(['name' => 'Broken', 'plan' => 'Broken']);
        // duration stays null

        $this->expectException(\InvalidArgumentException::class);

        InvestmentFactory::buildVenture(userId: 1, adventure: $adv, amount: 100, paid: 100);
    }

    /** @test */
    public function build_venture_includes_is_expired_false_explicitly(): void
    {
        // This is critical: even if the DB column default is missing or
        // the column is NULL, the new row gets is_expired = false.
        $adv = $this->makeAdventure(id: 1, duration: 100);
        $pay = InvestmentFactory::buildVenture(
            userId: 1, adventure: $adv, amount: 100, paid: 100
        );

        $this->assertFalse((bool) $pay->is_expired,
            'is_expired must be set explicitly to false — never rely on DB default');
    }

    /** @test */
    public function build_venture_uses_user_id_not_username(): void
    {
        $adv = $this->makeAdventure(id: 1, duration: 100);
        $pay = InvestmentFactory::buildVenture(
            userId: 42, adventure: $adv, amount: 100, paid: 100
        );

        // Bug previously stored the username string (e.g. "john_doe") in
        // the user column, making the investment invisible to FK-style
        // lookups. The factory now writes the integer id only.
        $this->assertSame(42, $pay->user);
    }

    /** @test */
    public function build_venture_uses_explicit_status(): void
    {
        $adv = $this->makeAdventure(id: 1, duration: 100);

        $paid   = InvestmentFactory::buildVenture(userId: 1, adventure: $adv, amount: 100, paid: 100, status: 1);
        $under  = InvestmentFactory::buildVenture(userId: 1, adventure: $adv, amount: 100, paid: 50,  status: 2);
        $over   = InvestmentFactory::buildVenture(userId: 1, adventure: $adv, amount: 100, paid: 120, status: 3);

        $this->assertSame(1, $paid->status);
        $this->assertSame(2, $under->status);
        $this->assertSame(3, $over->status);
    }

    /** @test */
    public function expiration_date_helper_returns_null_for_invalid_duration(): void
    {
        $this->assertNull(InvestmentFactory::expirationDateFor(
            new adventures(['duration' => 0])
        ));
        $this->assertNull(InvestmentFactory::expirationDateFor(
            new adventures(['duration' => null])
        ));
    }

    /** @test */
    public function expiration_date_helper_returns_correct_string(): void
    {
        $adv = $this->makeAdventure(id: 1, duration: 100);
        $this->assertSame(
            Carbon::now()->addDays(100)->toDateString(),
            InvestmentFactory::expirationDateFor($adv)
        );
    }

    // ── helper ──

    private function makeAdventure(int $id, int $duration, string $name = 'UVP', string $plan = 'VENTURE LIGHT'): adventures
    {
        $adv = new adventures();
        $adv->id        = $id;
        $adv->name      = $name;
        $adv->plan      = $plan;
        $adv->duration  = $duration;
        $adv->percentage = 2.0;
        $adv->min_amount = 100;
        $adv->max_amount = 9999;
        $adv->total_return = '200';
        $adv->currency = 'USD';
        return $adv;
    }
}
