<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * Volume-Point → USDT redemption options (the 8 boxes on /user/rewards).
 * Admin-configurable: points_required deducted from the VOLUME_POINT
 * wallet, usdt_amount credited to CASHOUT immediately.
 */
class FomRedeemOption extends Model
{
    use HasFactory;

    protected $table = 'fom_redeem_options';

    protected $fillable = ['usdt_amount', 'points_required', 'is_active', 'sort_order'];

    protected static $ensured = false;

    public static function ensureTableAndData()
    {
        if (static::$ensured) {
            return;
        }
        static::$ensured = true;

        try {
            if (!Schema::hasTable('fom_redeem_options')) {
                Schema::create('fom_redeem_options', function (Blueprint $table) {
                    $table->id();
                    $table->decimal('usdt_amount', 20, 2);
                    $table->decimal('points_required', 20, 2);
                    $table->boolean('is_active')->default(true);
                    $table->integer('sort_order')->default(0);
                    $table->timestamps();
                });
            }

            if (!Schema::hasTable('fom_redeem_logs')) {
                Schema::create('fom_redeem_logs', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id');
                    $table->unsignedBigInteger('option_id')->nullable();
                    $table->decimal('points_spent', 20, 2);
                    $table->decimal('usdt_received', 20, 2);
                    $table->dateTime('redeemed_at');
                    $table->timestamps();
                    $table->index(['user_id'], 'frl_user_idx');
                });
            }

            if (self::count() === 0) {
                self::seedDefaults();
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('FomRedeemOption ensureTableAndData error: ' . $e->getMessage());
        }
    }

    /** The 8 boxes (100 points = 1 USDT, from the original rewards page). */
    public static function seedDefaults()
    {
        $defaults = [
            [1, 100], [5, 500], [7, 700], [10, 1000],
            [30, 3000], [100, 10000], [1000, 100000], [5000, 500000],
        ];

        foreach ($defaults as $i => [$usdt, $points]) {
            self::create([
                'usdt_amount'     => $usdt,
                'points_required' => $points,
                'is_active'       => true,
                'sort_order'      => $i + 1,
            ]);
        }
    }
}
