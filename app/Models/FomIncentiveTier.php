<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * FOM Licence Miner incentive tiers (admin-configurable):
 * reach `achievement` USD of DIRECT-referral FOM investment within
 * `duration_days` of the user's LATEST FOM activation → `bonus` to cashout.
 */
class FomIncentiveTier extends Model
{
    use HasFactory;

    protected $table = 'fom_incentive_tiers';

    protected $fillable = ['achievement', 'duration_days', 'bonus', 'is_active', 'sort_order'];

    protected static $ensured = false;

    public static function ensureTableAndData()
    {
        if (static::$ensured) {
            return;
        }
        static::$ensured = true;

        try {
            if (!Schema::hasTable('fom_incentive_tiers')) {
                Schema::create('fom_incentive_tiers', function (Blueprint $table) {
                    $table->id();
                    $table->decimal('achievement', 20, 2);
                    $table->integer('duration_days');
                    $table->decimal('bonus', 20, 2);
                    $table->boolean('is_active')->default(true);
                    $table->integer('sort_order')->default(0);
                    $table->timestamps();
                });
            }

            if (!Schema::hasTable('fom_incentive_awards')) {
                Schema::create('fom_incentive_awards', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id');
                    $table->unsignedBigInteger('tier_id');
                    $table->decimal('achievement', 20, 2);
                    $table->integer('duration_days');
                    $table->decimal('bonus', 20, 2);
                    $table->decimal('achieved_amount', 20, 2)->default(0);
                    $table->dateTime('window_start');
                    $table->dateTime('window_end');
                    $table->dateTime('awarded_at');
                    $table->unsignedBigInteger('anchor_payment_id')->nullable();
                    $table->timestamps();
                    $table->unique(['user_id', 'tier_id', 'anchor_payment_id'], 'fia_user_tier_anchor_unique');
                    $table->index(['user_id'], 'fia_user_idx');
                });
            }

            if (self::count() === 0) {
                self::seedDefaults();
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('FomIncentiveTier ensureTableAndData error: ' . $e->getMessage());
        }
    }

    public static function seedDefaults()
    {
        $defaults = [
            [20000,      5,  500],
            [40000,      10, 1000],
            [100000,     12, 2000],
            [250000,     15, 4000],
            [500000,     18, 6500],
            [1000000,    20, 11500],
            [2000000,    25, 19200],
            [5000000,    30, 26000],
            [20000000,   50, 600000],
            [50000000,   60, 1700000],
        ];

        foreach ($defaults as $i => [$ach, $days, $bonus]) {
            self::create([
                'achievement'   => $ach,
                'duration_days' => $days,
                'bonus'         => $bonus,
                'is_active'     => true,
                'sort_order'    => $i + 1,
            ]);
        }
    }
}
