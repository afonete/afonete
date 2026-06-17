<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRank extends Model
{
    use HasFactory;

    protected $table = 'user_ranks';

    protected $fillable = [
        'user_id','rank_id','rank_name','rank_slug','rank_level',
        'status','detected_at','reviewed_at','reviewed_by',
        'congratulation_image','admin_notes',
        'reward_amount','referral_bonus_id','criteria_snapshot',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'criteria_snapshot' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rank()
    {
        return $this->belongsTo(RankSetting::class, 'rank_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function bonus()
    {
        return $this->belongsTo(ReferralBonus::class, 'referral_bonus_id');
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            'pending'  => '<span class="badge badge-warning">Pending</span>',
            'approved' => '<span class="badge badge-success">Approved</span>',
            'rejected' => '<span class="badge badge-danger">Rejected</span>',
            default    => '<span class="badge badge-secondary">' . ucfirst($this->status) . '</span>',
        };
    }
}
