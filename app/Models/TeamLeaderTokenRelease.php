<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamLeaderTokenRelease extends Model
{
    use HasFactory;

    protected $table = 'team_leader_token_releases';

    protected $fillable = [
        'user_id',
        'team_leader_id',
        'activation_id',
        'total_locked_tokens',
        'released_tokens',
        'remaining_locked_tokens',
        'status',
        'duration_days',
        'activated_at',
        'eligible_at',
        'approved_at',
        'admin_id',
        'admin_notes',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'eligible_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function teamLeader()
    {
        return $this->belongsTo(TeamLeader::class, 'team_leader_id');
    }

    public function activation()
    {
        return $this->belongsTo(Activations::class, 'activation_id');
    }

    /**
     * Get total released tokens for a user.
     */
    public static function releasedTokensForUser($userId)
    {
        return (float) static::where('user_id', $userId)
            ->where('status', 'approved')
            ->sum('released_tokens');
    }
}
