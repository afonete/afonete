<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

/**
 * A user's Royal Leader Bonus (pending → admin approves with focoin
 * price → token payout + auto promotion, or rejected/ineligible).
 */
class FomRoyalAward extends Model
{
    protected $table = 'fom_royal_awards';

    protected $fillable = [
        'user_id', 'tier_id', 'tier_name', 'status',
        'window_start', 'window_end', 'detected_at',
        'reviewed_at', 'reviewed_by', 'focoin_price',
        'tokens_paid', 'promo_payment_id', 'criteria_snapshot', 'admin_notes',
    ];

    protected $casts = [
        'window_start'      => 'datetime',
        'window_end'        => 'datetime',
        'detected_at'       => 'datetime',
        'reviewed_at'       => 'datetime',
        'focoin_price'      => 'decimal:6',
        'tokens_paid'       => 'decimal:4',
        'criteria_snapshot' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tier()
    {
        return $this->belongsTo(FomRoyalTier::class, 'tier_id');
    }

    public static function ensureTable(): void
    {
        try {
            if (!Schema::hasTable('fom_royal_awards')) {
                Schema::create('fom_royal_awards', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id')->index();
                    $table->unsignedBigInteger('tier_id');
                    $table->string('tier_name', 20);
                    $table->string('status', 20)->default('pending'); // pending|approved|rejected
                    $table->timestamp('window_start')->nullable();
                    $table->timestamp('window_end')->nullable();
                    $table->timestamp('detected_at')->nullable();
                    $table->timestamp('reviewed_at')->nullable();
                    $table->unsignedBigInteger('reviewed_by')->nullable();
                    $table->decimal('focoin_price', 20, 6)->nullable();
                    $table->decimal('tokens_paid', 20, 4)->default(0);
                    $table->unsignedBigInteger('promo_payment_id')->nullable();
                    $table->text('criteria_snapshot')->nullable();
                    $table->text('admin_notes')->nullable();
                    $table->timestamps();
                    $table->unique(['user_id', 'tier_id']);
                });
            }
        } catch (\Throwable $e) {
            Log::error('FomRoyalAward ensureTable failed: ' . $e->getMessage());
        }
    }
}
