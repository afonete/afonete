<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ads extends Model
{
    use HasFactory;
    protected $fillable=[
'url',
'banner',
'tittle',
'description',
'targeted_views',
'reached_views',
'targeted_duration',
'location',
'user',
'question',
'answer',
'status',
'total_paid',
'user_paid',
'rejected_reason',

    ];
}
