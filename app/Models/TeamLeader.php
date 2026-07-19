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
        'whatsapp',
        'instagram',
    ];
    public function uniqueIds()
    {
        return [
            'User_name' => 'unique:team_leaders,User_name',
            'Email' => 'unique:team_leaders,Email',
            'Phone' => 'unique:team_leaders,Phone'
        ];
    }

    
}
