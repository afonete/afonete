<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/**
 * FcLeadershipProgress — tracks FC VIP Leadership bonus progress per referrer.
 *
 * Spec:
 *  • Direct & indirect referral commissions for FC VIP are the SAME as for
 *    UVP packages (L1 = 10%, L2 = 1%, L3 = 0.5%) and flow through
 *    ReferralService::creditForPayment() exactly like UVP.
 *  • IN ADDITION: the DIRECT referrer earns 100 VB Volume Bonus for every
 *    direct referral who purchases ANY FC VIP package. VB accrues into the
 *    leader's Volume Bonus pool (qualification counter).
 *  • When the leader's lifetime direct-FC-referral count crosses one of the
 *    milestone thresholds below, they earn a lump-sum FC Leadership Reward
 *    which is added to referral_bonuses and becomes withdrawable to cashout
 *    on the next Monday (same pipeline as weekly commission).
 *
 *       Tier  Direct Refs    VB Pool  Pct    VB Reward
 *       ────  ───────────    ───────  ───    ─────────
 *        1      10            1,000    0.3%   3    VB
 *        2      30            3,000    0.5%   15   VB
 *        3      50            5,000    0.8%   40   VB
 *        4     150           15,000    1.0%   150  VB
 *        5     500           50,000    1.5%   750  VB
 *        6    1000          100,000    2.0%   2000 VB
 *
 * Per the parenthetical formulas in the spec, each reward is paid ONCE as
 * a lump sum when its threshold is FIRST reached (incremental; no double
 * pay, no pay-when-downgrading).
 */
class FcLeadershipProgress extends Model
{
    use HasFactory;

    protected $table = 'fc_leadership_progress';

    protected $fillable = [
        'user_id',
        'direct_fc_count',
        'volume_bonus_vb',
        'highest_tier_paid',
    ];

    protected $casts = [
        'direct_fc_count'    => 'integer',
        'volume_bonus_vb'    => 'integer',
        'highest_tier_paid'  => 'integer',
    ];

    /** VB credited to the DIRECT referrer for each FC VIP package purchased by a downline. */
    public const VB_PER_DIRECT_REFERRAL = 100;

    /** FC Leadership milestone tiers (1-indexed).
     *
     * Pool sizes are cumulative: 10 referrals × 100 VB = 1,000 VB pool → 0.3% = 3 VB
     * reward; 30 referrals → 3,000 VB pool → 0.5% = 15 VB; etc.
     *
     * The parenthetical examples in the spec (3 / 15 / 40 / 150 / 750 / 2000)
     * are the USD value of each reward, meaning 1 VB = $1.00 USD for the
     * purposes of these milestone bonuses. */
    public const TIERS = [
        1 => ['direct_required' =>   10, 'pool_vb' =>   1000, 'pct' => 0.3, 'reward_vb' =>    3],
        2 => ['direct_required' =>   30, 'pool_vb' =>   3000, 'pct' => 0.5, 'reward_vb' =>   15],
        3 => ['direct_required' =>   50, 'pool_vb' =>   5000, 'pct' => 0.8, 'reward_vb' =>   40],
        4 => ['direct_required' =>  150, 'pool_vb' =>  15000, 'pct' => 1.0, 'reward_vb' =>  150],
        5 => ['direct_required' =>  500, 'pool_vb' =>  50000, 'pct' => 1.5, 'reward_vb' =>  750],
        6 => ['direct_required' => 1000, 'pool_vb' => 100000, 'pct' => 2.0, 'reward_vb' => 2000],
    ];

    /** USD value of 1 VB for FC Leadership rewards.
     *  The spec's parentheticals (1000 × 0.3% = 3, 3000 × 0.5% = 15, …)
     *  require 1 VB = $1.00 (the VB pool is a USD-denominated volume counter). */
    public const VB_USD_PRICE = 1.00;

    public static function vbUsdPrice(): float
    {
        return self::VB_USD_PRICE;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function forUser(int $userId): self
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            [
                'direct_fc_count'   => 0,
                'volume_bonus_vb'   => 0,
                'highest_tier_paid' => 0,
            ]
        );
    }
}
