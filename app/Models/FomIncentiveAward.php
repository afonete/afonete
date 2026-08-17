<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** A tier a user achieved — the admin's achievers list rows. */
class FomIncentiveAward extends Model
{
    use HasFactory;

    protected $table = 'fom_incentive_awards';

    protected $fillable = [
        'user_id', 'tier_id', 'achievement', 'duration_days', 'bonus',
        'achieved_amount', 'window_start', 'window_end', 'awarded_at',
        'anchor_payment_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function tier()
    {
        return $this->belongsTo(FomIncentiveTier::class, 'tier_id', 'id');
    }
}
