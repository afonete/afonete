<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamLeaderSocial extends Model
{
    use HasFactory;

    protected $table = 'team_leader_socials';

    protected $fillable = [
        'user_id',
        'platform',
        'profile_link',
        'views_count',
        'status',
        'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
