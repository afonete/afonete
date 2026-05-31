<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hasposition extends Model
{
    use HasFactory;
    protected $fillable=[
'user',
'unique_task',
'general_task',
'cashout',
'duration',
'withdraw',
'daily_bonus',
];
}
