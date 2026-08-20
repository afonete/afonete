<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

/**
 * Royal Leader Bonus tiers (VIP1–VIP5) — admin-modifiable.
 *
 * sponsors_json: [{"package":"ADVANCED","count":5}, …] — direct referrals
 * who ACTIVATED that FOM package inside the user's Duration window.
 * Auto Promotion "hold_from → grant_to": user holding at least hold_from
 * gets grant_to auto-activated on approval.
 */
class FomRoyalTier extends Model
{
    protected $table = 'fom_royal_tiers';

    protected $fillable = [
        'name', 'level', 'sponsors_json', 'duration_days',
        'vb_earn_required', 'prize_pool_usd', 'promo_hold_package',
        'promo_grant_package', 'is_active',
    ];

    protected $casts = [
        'sponsors_json'    => 'array',
        'vb_earn_required' => 'decimal:2',
        'prize_pool_usd'   => 'decimal:2',
        'is_active'        => 'boolean',
    ];

    public static function ensureTableAndData(): void
    {
        try {
            if (!Schema::hasTable('fom_royal_tiers')) {
                Schema::create('fom_royal_tiers', function ($table) {
                    $table->id();
                    $table->string('name', 20)->unique();
                    $table->unsignedInteger('level')->unique();
                    $table->text('sponsors_json');
                    $table->unsignedInteger('duration_days');
                    $table->decimal('vb_earn_required', 20, 2)->default(0);
                    $table->decimal('prize_pool_usd', 20, 2)->default(0);
                    $table->string('promo_hold_package', 30);
                    $table->string('promo_grant_package', 30);
                    $table->boolean('is_active')->default(true);
                    $table->timestamps();
                });
            }
            if (self::count() === 0) {
                self::seedDefaults();
            }
        } catch (\Throwable $e) {
            Log::error('FomRoyalTier ensureTableAndData failed: ' . $e->getMessage());
        }
    }

    public static function seedDefaults(): void
    {
        $t = function ($name, $level, $sponsors, $days, $vb, $pool, $hold, $grant) {
            self::updateOrCreate(['name' => $name], [
                'level' => $level, 'sponsors_json' => $sponsors, 'duration_days' => $days,
                'vb_earn_required' => $vb, 'prize_pool_usd' => $pool,
                'promo_hold_package' => $hold, 'promo_grant_package' => $grant, 'is_active' => true,
            ]);
        };

        $t('VIP1', 1, [['package' => 'ADVANCED', 'count' => 5], ['package' => 'PREMIUM', 'count' => 3], ['package' => 'MASTER', 'count' => 2]],
            50, 6320, 1000, 'STARTER', 'PREMIUM');
        $t('VIP2', 2, [['package' => 'PREMIUM', 'count' => 4], ['package' => 'MASTER', 'count' => 2], ['package' => 'PRO MASTER', 'count' => 1]],
            40, 5200, 2000, 'LIGHT', 'TYCOON');
        $t('VIP3', 3, [['package' => 'TYCOON', 'count' => 4], ['package' => 'MASTER', 'count' => 3], ['package' => 'PRO MASTER', 'count' => 1]],
            35, 7000, 4000, 'PRO', 'MASTER');
        $t('VIP4', 4, [['package' => 'MASTER', 'count' => 4], ['package' => 'PRO MASTER', 'count' => 2], ['package' => 'SUPER', 'count' => 1]],
            25, 11550, 5000, 'ADVANCED', 'PRO MASTER');
        $t('VIP5', 5, [['package' => 'MASTER', 'count' => 5], ['package' => 'PRO MASTER', 'count' => 3], ['package' => 'SUPER', 'count' => 2]],
            20, 18000, 10000, 'PREMIUM', 'PRO MASTER');
    }

    /** Human label: "5 Advanced, 3 Premium, 2 Master". */
    public function sponsorsLabel(): string
    {
        return collect($this->sponsors_json ?: [])
            ->map(fn ($s) => ($s['count'] ?? 0) . ' ' . ucwords(strtolower((string) ($s['package'] ?? ''))))
            ->implode(', ');
    }

    public static function ordered()
    {
        return self::where('is_active', true)->orderBy('level');
    }
}
