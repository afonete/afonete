<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Teams extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'team_user_id', 'side'];

    /**
     * Get the owner of the team.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who is part of this team.
     */
    public function teamMember()
    {
        return $this->belongsTo(User::class, 'team_user_id');
    }

}
