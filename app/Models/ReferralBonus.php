<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralBonus extends Model
{
    use HasFactory;

    protected $table = 'referral_bonuses';

    protected $fillable = [
        'user_id', 'source_user_id', 'source_payment_id',
        'level', 'percentage', 'source_amount', 'bonus_amount',
        'week_start', 'status', 'withdrawn_at',
        'source', 'source_ref', 'notes',
    ];

    protected $casts = [
        'week_start'    => 'date',
        'withdrawn_at'  => 'datetime',
        'percentage'    => 'decimal:2',
        'source_amount' => 'decimal:2',
        'bonus_amount'  => 'decimal:4',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sourceUser()
    {
        return $this->belongsTo(User::class, 'source_user_id');
    }

    public function sourcePayment()
    {
        return $this->belongsTo(Payment::class, 'source_payment_id');
    }

    /** Human label for the level */
    public function levelLabel(): string
    {
        return [
            1 => 'Direct Referral (10%)',
            2 => 'Indirect Referral (1%)',
            3 => '3rd-Level Referral (0.5%)',
        ][$this->level] ?? "Level {$this->level}";
    }

    /** Pretty source label */
    public function sourceLabel(): string
    {
        return match ($this->source) {
            'rank_reward'        => '🏆 Rank Reward: ' . ($this->notes ?: $this->source_ref),
            'associate_manager'  => '⭐ Associate Manager Weekly Bonus',
            default              => '👥 ' . $this->levelLabel(),
        };
    }

    /**
     * Helper: total bonus balance for a user.
     *   - pending       : this week's bonuses (not yet withdrawable)
     *   - withdrawable  : available to withdraw (after Monday promotion)
     *   - total         : sum of all non-reversed bonuses
     *   - lifetime      : includes withdrawn
     */
    public static function totalsForUser(int $userId): array
    {
        $row = self::where('user_id', $userId)
            ->selectRaw('
                SUM(CASE WHEN status = "pending"      THEN bonus_amount ELSE 0 END) AS pending_total,
                SUM(CASE WHEN status = "withdrawable" THEN bonus_amount ELSE 0 END) AS withdrawable_total,
                SUM(CASE WHEN status NOT IN ("reversed","expired") THEN bonus_amount ELSE 0 END) AS total,
                SUM(CASE WHEN status = "withdrawn"    THEN bonus_amount ELSE 0 END) AS lifetime_withdrawn,
                SUM(CASE WHEN source = "associate_manager" AND status NOT IN ("reversed","expired") THEN bonus_amount ELSE 0 END) AS am_total
            ')->first();

        return [
            'pending'           => (float) ($row->pending_total       ?? 0),
            'withdrawable'      => (float) ($row->withdrawable_total  ?? 0),
            'total'             => (float) ($row->total              ?? 0),
            'lifetime_withdrawn'=> (float) ($row->lifetime_withdrawn ?? 0),
            'associate_manager' => (float) ($row->am_total           ?? 0),
        ];
    }
}
