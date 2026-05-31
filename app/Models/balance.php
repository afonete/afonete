<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class balance extends Model
{
    use HasFactory;
    protected $fillable = [
        'user',
        'cash',
        'deposit' ,
        'earning' ,
        'ads_earning',
        'reserved_token',
        'used',
    ] ;
}
