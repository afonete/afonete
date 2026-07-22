<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamLeader extends Model
{
    use HasFactory;
    protected $fillable = [
        'User_name',
        'Email',
        'Names',
        'Phone',
        'Country',
        'status',
        'leadership_level',
        'whatsapp',
        'instagram',
    ];

    public function superLeaderCredit()
    {
        return $this->hasOne(\App\Models\SuperLeaderCredit::class, 'team_leader_id');
    }
    public function uniqueIds()
    {
        return [
            'User_name' => 'unique:team_leaders,User_name',
            'Email' => 'unique:team_leaders,Email',
            'Phone' => 'unique:team_leaders,Phone'
        ];
    }

    
}
