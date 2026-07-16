<?php

namespace Tests\Feature;

use App\Models\Adventures;
use App\Models\ChartAccount;
use App\Models\Payment;
use App\Models\User;
use App\Services\RenewalCalculator;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackageRenewalTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that when a user has multiple packages, each package can be
     * viewed and renewed independently with accurate and distinct renewal details.
     */
    public function test_multiple_packages_independent_view_and_renewal()
    {
        // 1. Create a user
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'has_paid_package'  => 'yes',
        ]);

        // 2. Create distinct Adventure Packages
        $adventureSmall = Adventures::create([
            'name'             => 'VENTURE LIGHT',
            'plan'             => 'LIGHT',
            'min_amount'       => 100.0,
            'max_amount'       => 500.0,
            'percentage'       => 2.0, // 2% daily ROI
            'duration'         => 100, // 100 days
            'total_return'     => '200%',
            'percentage_range' => '2-2',
        ]);

        $adventureLarge = Adventures::create([
            'name'             => 'VENTURE PRO',
            'plan'             => 'PRO',
            'min_amount'       => 1000.0,
            'max_amount'       => 5000.0,
            'percentage'       => 3.0, // 3% daily ROI
            'duration'         => 200, // 200 days
            'total_return'     => '600%',
            'percentage_range' => '3-3',
        ]);

        // 3. Create active Payments for both packages
        $paymentSmall = Payment::create([
            'user'            => $user->id,
            'package'         => 'VENTURE LIGHT',
            'payable_type'    => Adventures::class,
            'payable_id'      => $adventureSmall->id,
            'category'        => 'VENTURE',
            'amount'          => 200.0,
            'paid'            => 200.0,
            'status'          => '1',
            'is_expired'      => false,
            'expiration_date' => Carbon::now()->addDays(100)->toDateString(),
            'created_at'      => Carbon::now()->subDays(31), // renewal is due (day 31)
        ]);

        $paymentLarge = Payment::create([
            'user'            => $user->id,
            'package'         => 'VENTURE PRO',
            'payable_type'    => Adventures::class,
            'payable_id'      => $adventureLarge->id,
            'category'        => 'VENTURE',
            'amount'          => 2000.0,
            'paid'            => 2000.0,
            'status'          => '1',
            'is_expired'      => false,
            'expiration_date' => Carbon::now()->addDays(200)->toDateString(),
            'created_at'      => Carbon::now()->subDays(31), // renewal is due (day 31)
        ]);

        // Fund user's TRADING (Trading Voucher) wallet so they have enough to renew both
        ChartAccount::create([
            'user_id'  => $user->id,
            'acc_type' => 'TRADING',
            'amount'   => 5000.0, // plenty of funds
        ]);

        ChartAccount::create([
            'user_id'  => $user->id,
            'acc_type' => 'AVAILABLE_TOKEN',
            'amount'   => 0.0,
        ]);

        // Act as the user
        $this->actingAs($user);

        // 4. Visit the Investments index page
        $indexResponse = $this->get(route('user.investments'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('VENTURE LIGHT');
        $indexResponse->assertSee('VENTURE PRO');

        // 5. Check Investment Details for the SMALL package
        $smallDetailsResponse = $this->get(route('user.investments.show', $paymentSmall->id));
        $smallDetailsResponse->assertStatus(200);
        $smallDetailsResponse->assertSee('VENTURE LIGHT');
        $smallDetailsResponse->assertSee(route('packageRenew', $paymentSmall->id));

        // 6. Check Investment Details for the LARGE package
        $largeDetailsResponse = $this->get(route('user.investments.show', $paymentLarge->id));
        $largeDetailsResponse->assertStatus(200);
        $largeDetailsResponse->assertSee('VENTURE PRO');
        $largeDetailsResponse->assertSee(route('packageRenew', $paymentLarge->id));

        // 7. Verify the Renewal Page for SMALL package specifically
        $smallRenewPageResponse = $this->get(route('packageRenew', $paymentSmall->id));
        $smallRenewPageResponse->assertStatus(200);
        
        // Fee for small: 200 * 0.80 * 2% * 0.75 * 30 = $72
        $smallRenewPageResponse->assertSee('$72.00');

        // 8. Verify the Renewal Page for LARGE package specifically
        $largeRenewPageResponse = $this->get(route('packageRenew', $paymentLarge->id));
        $largeRenewPageResponse->assertStatus(200);
        
        // Fee for large: 2000 * 0.80 * 3% * 0.75 * 30 = $1,080
        $largeRenewPageResponse->assertSee('$1,080.00');

        // 9. Process Renewal Payment for the SMALL package
        $smallRenewPayResponse = $this->post(route('packageRenewPay'), [
            'payment_id' => $paymentSmall->id,
        ]);
        
        $smallRenewPayResponse->assertRedirect(route('user.dashboard'));
        $smallRenewPayResponse->assertSessionHas('message');

        // Verify SMALL package has recorded 1 renewal
        $this->assertDatabaseHas('package_renewals', [
            'user_id'    => $user->id,
            'payment_id' => $paymentSmall->id,
            'amount_paid'=> 72.0,
        ]);

        // Verify LARGE package does NOT have renewals yet
        $this->assertDatabaseMissing('package_renewals', [
            'payment_id' => $paymentLarge->id,
        ]);

        // Verify balance updates: TRADING deducted by 72 (5000 - 72 = 4928)
        $this->assertEquals(4928.0, ChartAccount::where('user_id', $user->id)->where('acc_type', 'TRADING')->sum('amount'));

        // Verify AVAILABLE_TOKEN credited with tokens:
        // Token setting default price is 0.0025 (or check token_price_at_renewal)
        // 72 / 0.0025 = 28,800 tokens received
        $this->assertGreaterThan(0.0, ChartAccount::where('user_id', $user->id)->where('acc_type', 'AVAILABLE_TOKEN')->sum('amount'));
    }
}
