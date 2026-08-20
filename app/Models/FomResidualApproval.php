<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

/**
 * Per-leader per-level approval for the Residual Income Matching Bonus.
 * The system detects a COMPLETE level → pending row; the ADMIN approves
 * it → only then does that level pay its weekly % (straight to CASHOUT).
 * Members snapshot records who qualified the leader at detection time.
 */
class FomResidualApproval extends Model
{
    protected $table = 'fom_residual_approvals';

    protected $fillable = [
        'user_id', 'level', 'status', 'members_snapshot',
        'detected_at', 'reviewed_at', 'reviewed_by', 'admin_notes',
    ];

    protected $casts = [
        'members_snapshot' => 'array',
        'detected_at'      => 'datetime',
        'reviewed_at'      => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function ensureTable(): void
    {
        try {
            if (!Schema::hasTable('fom_residual_approvals')) {
                Schema::create('fom_residual_approvals', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id')->index();
                    $table->unsignedInteger('level');
                    $table->string('status', 20)->default('pending'); // pending|approved|rejected
                    $table->text('members_snapshot')->nullable();
                    $table->timestamp('detected_at')->nullable();
                    $table->timestamp('reviewed_at')->nullable();
                    $table->unsignedBigInteger('reviewed_by')->nullable();
                    $table->text('admin_notes')->nullable();
                    $table->timestamps();
                    $table->unique(['user_id', 'level']);
                });
            }
        } catch (\Throwable $e) {
            Log::error('FomResidualApproval ensureTable failed: ' . $e->getMessage());
        }
    }

    /** Highest APPROVED level for a leader (0 = none approved yet). */
    public static function approvedLevel(int $userId): int
    {
        try {
            return (int) self::where('user_id', $userId)->where('status', 'approved')->max('level');
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
