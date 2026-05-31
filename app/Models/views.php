<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class views extends Model
{
    use HasFactory;
    protected $fillable=[
    'ads',
    'video',
    'user',
    'has_answer',
    ];
}
