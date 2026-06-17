<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RankSetting extends Model
{
    use HasFactory;

    protected $table = 'rank_settings';

    protected $fillable = [
        'slug','name','level','order',
        'min_active_direct_referrals',
        'min_associates_from_direct',
        'min_directors_from_direct',
        'min_regional_supervisors_from_direct',
        'min_direct_referral_investment',
        'min_total_investment',
        'reward_type','reward_amount','reward_percentage',
        'am_min_active_direct_investment_users',
        'am_per_user_min_investment',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function userRanks(): HasMany
    {
        return $this->hasMany(UserRank::class, 'rank_id');
    }

    /** Pretty reward label */
    public function rewardLabel(): string
    {
        if ($this->reward_type === 'fixed') {
            return '$' . number_format((float) $this->reward_amount, 0);
        }
        return rtrim(rtrim(number_format((float) $this->reward_percentage, 2), '0'), '.') . '% weekly';
    }

    /**
     * Return a query builder for active ranks in display order.
     * Callers should chain ->get() (or paginate()) to execute the query.
     */
    public static function ordered()
    {
        return self::where('is_active', true)->orderBy('order')->orderBy('level');
    }

    /** Convenience helper: returns the Collection directly (use sparingly) */
    public static function orderedList()
    {
        return self::ordered()->get();
    }
}
