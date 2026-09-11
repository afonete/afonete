<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use User;

/**
 * FcStreamlineRank — per-user per-rank progress record for the
 * FC VIP Streamline Rank system (Silver / Gold / Diamond / Ambassador).
 *
 * Rank rules (sequential; 65-day challenge period):
 *
 * ┌─────────────┬──────────────┬───────────────┬────────────────┬─────────┬──────────┬─────────┐
 * │ Rank (Pin)  │ Activation   │ OWN FC (Dir.) │ TEAM CLUB      │ Reward  │  Tokens  │ Period  │
 * ├─────────────┼──────────────┼───────────────┼────────────────┼─────────┼──────────┼─────────┤
 * │ FC Team Leader (Silver)    │ 5 direct FC  │ 10 direct FC  │ 60+ FC downline│ $350    │ 1,000    │ 65 days │
 * │ FC Manager (Gold)          │ 5 Silver pins anywhere in team │ 30 direct FC │ 325+ FC downline │ $1,050 │ 5,000    │ 65 days │
 * │ FC Regional Chief Manager (Diamond) │ 30 Gold pins anywhere │ 100 direct FC │ 1,200+ FC downline │ $3,500 │ 10,000   │ 65 days │
 * │ FC Regional Supervisor (Ambassador) │ 100 Diamond pins anywhere │ 200+ direct FC │ 50,000+ FC downline │ $251,000 │ 50,000  │ 65 days │
 * └─────────────┴──────────────┴───────────────┴────────────────┴─────────┴──────────┴─────────┘
 *
 * Status flow:
 *   locked        → active (auto when activation threshold met)
 *   active        → pending_admin (auto when OWN FC + TEAM CLUB targets hit
 *                   within 65 days)
 *   pending_admin → completed (admin verifies; $ and tokens credited)
 *   active        → expired (65 days pass without hitting targets; rank is
 *                   permanently forfeited and cannot be re-challenged)
 */
class FcStreamlineRank extends Model
{
    use HasFactory;

    protected $table = 'fc_streamline_ranks';

    protected $fillable = [
        'user_id',
        'rank_level',
        'rank_pin',
        'status',
        'activated_at',
        'deadline_at',
        'completed_at',
        'direct_fc_at_completion',
        'team_fc_at_completion',
        'required_pins_at_completion',
        'verified_by',
        'verified_at',
        'admin_notes',
        'reward_usd',
        'reward_tokens',
        'expired_at',
    ];

    protected $casts = [
        'rank_level'                 => 'integer',
        'activated_at'               => 'datetime',
        'deadline_at'                => 'datetime',
        'completed_at'               => 'datetime',
        'verified_at'                => 'datetime',
        'expired_at'                 => 'datetime',
        'direct_fc_at_completion'    => 'integer',
        'team_fc_at_completion'      => 'integer',
        'required_pins_at_completion'=> 'integer',
        'reward_usd'                 => 'decimal:2',
        'reward_tokens'              => 'integer',
        'verified_by'                => 'integer',
    ];

    public const PERIOD_DAYS = 65;

    public const STATUS_LOCKED        = 'locked';
    public const STATUS_ACTIVE        = 'active';
    public const STATUS_PENDING_ADMIN = 'pending_admin';
    public const STATUS_COMPLETED     = 'completed';
    public const STATUS_EXPIRED       = 'expired';

    /** Rank definitions — sequential (1→2→3→4). */
    public const RANKS = [
        1 => [
            'pin'                   => 'silver',
            'title'                 => 'FC Team Leader',
            'activation_direct_fc'  => 5,    // 5 direct FC refs to activate challenge
            'activation_pins'       => 0,    // no prior pins required
            'activation_pin_type'   => null,
            'own_fc_direct'         => 10,   // must reach 10 direct FC in period
            'team_club'             => 60,   // 60+ FC-paid downline (any depth)
            'reward_usd'            => 350,
            'reward_tokens'         => 1000,
        ],
        2 => [
            'pin'                   => 'gold',
            'title'                 => 'FC Manager',
            // Activation: automatically starts the 65-day challenge the
            // moment the user's downline holds 5 completed Silver pins
            // (anywhere, any depth). No additional direct-FC gate.
            'activation_direct_fc'  => 0,
            'activation_pins'       => 5,   // 5 Silver pins ANYWHERE in downline
            'activation_pin_type'   => 'silver',
            'own_fc_direct'         => 30,  // OWN FC target during 65-day period
            'team_club'             => 325, // TEAM CLUB target (any depth)
            'reward_usd'            => 1050,
            'reward_tokens'         => 5000,
        ],
        3 => [
            'pin'                   => 'diamond',
            'title'                 => 'FC Regional Chief Manager',
            // Activation: 30 completed Gold pins anywhere in downline.
            'activation_direct_fc'  => 0,
            'activation_pins'       => 30,  // 30 Gold pins anywhere
            'activation_pin_type'   => 'gold',
            'own_fc_direct'         => 100,
            'team_club'             => 1200,
            'reward_usd'            => 3500,
            'reward_tokens'         => 10000,
        ],
        4 => [
            'pin'                   => 'ambassador',
            'title'                 => 'FC Regional Supervisor',
            // Activation: 100 completed Diamond pins anywhere in downline.
            'activation_direct_fc'  => 0,
            'activation_pins'       => 100, // 100 Diamond pins anywhere
            'activation_pin_type'   => 'diamond',
            'own_fc_direct'         => 200,
            'team_club'             => 50000,
            'reward_usd'            => 251000,
            'reward_tokens'         => 50000,
        ],
    ];

    /* ──────────────────────────────────────────────────────────
     *  Relations
     * ────────────────────────────────────────────────────────── */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /* ──────────────────────────────────────────────────────────
     *  Helpers
     * ────────────────────────────────────────────────────────── */

    /** Get or create a rank-progress row for a user at a given level. */
    public static function forUser(int $userId, int $rankLevel): self
    {
        $def = self::RANKS[$rankLevel] ?? null;
        if (!$def) {
            throw new \InvalidArgumentException("Invalid FC Streamline rank level: {$rankLevel}");
        }

        return self::firstOrCreate(
            ['user_id' => $userId, 'rank_level' => $rankLevel],
            [
                'rank_pin' => $def['pin'],
                'status'   => self::STATUS_LOCKED,
            ]
        );
    }

    /** Return the highest rank a user currently HOLDS (completed). 0 if none. */
    public static function highestCompletedPin(int $userId): int
    {
        return (int) self::where('user_id', $userId)
            ->where('status', self::STATUS_COMPLETED)
            ->max('rank_level');
    }

    /** Does the user hold a given completed pin? */
    public static function hasPin(int $userId, string $pin): bool
    {
        return self::where('user_id', $userId)
            ->where('rank_pin', $pin)
            ->where('status', self::STATUS_COMPLETED)
            ->exists();
    }

    /** Return the rank the user is currently challenging (active), or null. */
    public static function currentActiveForUser(int $userId): ?self
    {
        return self::where('user_id', $userId)
            ->where('status', self::STATUS_ACTIVE)
            ->first();
    }

    public function rankDefinition(): array
    {
        return self::RANKS[$this->rank_level] ?? [];
    }

    public function daysRemaining(): int
    {
        if (!$this->deadline_at) return 0;
        $now = \Carbon\Carbon::now();
        if ($now->gte($this->deadline_at)) return 0;
        return (int) $now->diffInDays($this->deadline_at, false);
    }

    public function daysElapsed(): int
    {
        if (!$this->activated_at) return 0;
        return (int) $this->activated_at->diffInDays(\Carbon\Carbon::now());
    }
}
