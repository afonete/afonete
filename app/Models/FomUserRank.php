<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

/**
 * A user's achieved (or pending) FOM rank.
 * Flow: system detects qualification → status 'pending' → admin approves
 * → reward credits to CASHOUT (status 'approved') or admin rejects.
 */
class FomUserRank extends Model
{
    protected $table = 'fom_user_ranks';

    protected $fillable = [
        'user_id', 'fom_rank_id', 'rank_name', 'rank_level', 'status',
        'detected_at', 'reviewed_at', 'reviewed_by', 'reward_paid',
        'criteria_snapshot', 'admin_notes',
    ];

    protected $casts = [
        'detected_at'       => 'datetime',
        'reviewed_at'       => 'datetime',
        'reward_paid'       => 'decimal:2',
        'criteria_snapshot' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rank()
    {
        return $this->belongsTo(FomRank::class, 'fom_rank_id');
    }

    public static function ensureTable(): void
    {
        try {
            if (!Schema::hasTable('fom_user_ranks')) {
                Schema::create('fom_user_ranks', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id')->index();
                    $table->unsignedBigInteger('fom_rank_id');
                    $table->string('rank_name', 60);
                    $table->unsignedInteger('rank_level');
                    $table->string('status', 20)->default('pending'); // pending|approved|rejected
                    $table->timestamp('detected_at')->nullable();
                    $table->timestamp('reviewed_at')->nullable();
                    $table->unsignedBigInteger('reviewed_by')->nullable();
                    $table->decimal('reward_paid', 20, 2)->default(0);
                    $table->text('criteria_snapshot')->nullable();
                    $table->text('admin_notes')->nullable();
                    $table->timestamps();
                    $table->unique(['user_id', 'fom_rank_id']);
                });
            }
        } catch (\Throwable $e) {
            Log::error('FomUserRank ensureTable failed: ' . $e->getMessage());
        }
    }

    /** Highest APPROVED rank level for a user (0 = none). */
    public static function highestApprovedLevel(int $userId): int
    {
        try {
            return (int) self::where('user_id', $userId)->where('status', 'approved')->max('rank_level');
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /** Users' ids holding an APPROVED rank with this name (for team criteria). */
    public static function holdersOf(string $rankName)
    {
        try {
            return self::where('rank_name', $rankName)->where('status', 'approved')->pluck('user_id');
        } catch (\Throwable $e) {
            return collect();
        }
    }
}
