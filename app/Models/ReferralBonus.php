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

    /**
     * Human label for the level.
     * §82: no percentage here — the Level column on
     * /user/referral/bonus and admin/referral/user-detail already
     * shows the row's REAL stored percentage (L{n} · {pct}%), so a
     * hardcoded % in the Source label was redundant and could
     * contradict the actual rate on historical rows.
     */
    public function levelLabel(): string
    {
        if ($this->source === 'fc_direct_vb') {
            return 'FC Direct VB (+100)';
        }
        if ($this->source === 'fc_leadership') {
            return 'FC Leadership Tier Reward';
        }

        return [
            1 => 'Direct Referral',
            2 => 'Indirect Referral',
            3 => '3rd-Level Referral',
        ][$this->level] ?? "Level {$this->level}";
    }

    /** Pretty source label */
    public function sourceLabel(): string
    {
        return match ($this->source) {
            'rank_reward'        => '🏆 Rank Reward: ' . ($this->notes ?: $this->source_ref),
            'associate_manager'  => '⭐ Associate Manager Weekly Bonus',
            'fc_direct_vb'       => '💠 FC Direct Volume Bonus (+100 VB)',
            'fc_leadership'      => '👑 FC Leadership Milestone: ' . ($this->notes ?: $this->source_ref),
            default              => '👥 ' . $this->levelLabel(),
        };
    }

    /**
     * Helper: total bonus balance for a user.
     *   - pending          : this week's cash bonuses (not yet withdrawable)
     *   - withdrawable     : available to withdraw (after Monday promotion)
     *   - total            : sum of all non-reversed cash bonuses
     *   - lifetime_withdrawn : includes withdrawn
     *   - fc_vb            : informational FC Volume Bonus token balance (non-cash)
     * Cash totals exclude FOM referrals and rows with status='vb_only'
     * (non-cash informational entries like +100VB credits).
     */
    public static function totalsForUser(int $userId): array
    {
        \App\Services\ReferralService::syncMissingBonuses();

        $row = self::where('user_id', $userId)
            ->where(function ($q) {
                $q->whereNull('source')->orWhere('source', '!=', 'fom_referral');
            })
            ->where('status', '!=', 'vb_only')
            ->selectRaw('
                SUM(CASE WHEN status = "pending"      THEN bonus_amount ELSE 0 END) AS pending_total,
                SUM(CASE WHEN status = "withdrawable" THEN bonus_amount ELSE 0 END) AS withdrawable_total,
                SUM(CASE WHEN status NOT IN ("reversed","expired","vb_only") THEN bonus_amount ELSE 0 END) AS total,
                SUM(CASE WHEN status = "withdrawn"    THEN bonus_amount ELSE 0 END) AS lifetime_withdrawn,
                SUM(CASE WHEN source = "associate_manager" AND status NOT IN ("reversed","expired","vb_only") THEN bonus_amount ELSE 0 END) AS am_total,
                SUM(CASE WHEN source = "fc_leadership"  AND status NOT IN ("reversed","expired","vb_only") THEN bonus_amount ELSE 0 END) AS fc_leadership_total
            ')->first();

        // FC Volume Bonus (VB) token balance (non-cash, informational) from ChartAccount.
        $fcVbBalance = 0.0;
        try {
            $fcVbBalance = (float) \App\Models\ChartAccount::where('user_id', $userId)
                ->where('acc_type', \App\Services\FcLeadershipService::ACC_VB)
                ->sum('amount');
        } catch (\Throwable $e) {
            $fcVbBalance = 0.0;
        }

        return [
            'pending'            => (float) ($row->pending_total         ?? 0),
            'withdrawable'       => (float) ($row->withdrawable_total    ?? 0),
            'total'              => (float) ($row->total                  ?? 0),
            'lifetime_withdrawn' => (float) ($row->lifetime_withdrawn     ?? 0),
            'associate_manager'  => (float) ($row->am_total               ?? 0),
            'fc_leadership'      => (float) ($row->fc_leadership_total    ?? 0),
            'fc_vb'              => $fcVbBalance,
        ];
    }
}
