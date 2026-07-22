<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperLeaderCredit extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_leader_id',
        'user_id',
        'activation_id',
        'credit_amount',
        'remaining_credit',
        'cashout_amount',
        'sales_turnover_target',
        'turnover_target_percent',
        'turnover_reward_percent',
        'auto_withdrawal_percent',
        'status',
        'activated_at',
        'turnover_milestones_reached',
        'last_turnover',
        'pending_cashout',
        'pending_cashout_at',
        'auto_withdrawal_processed',
    ];

    protected $casts = [
        'activated_at'             => 'datetime',
        'pending_cashout_at'       => 'datetime',
        'auto_withdrawal_processed'=> 'boolean',
    ];

    public function teamLeader()
    {
        return $this->belongsTo(TeamLeader::class, 'team_leader_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function activation()
    {
        return $this->belongsTo(Activations::class, 'activation_id');
    }
}
