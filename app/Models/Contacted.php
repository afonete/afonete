<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacted extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'sur',
        'phone',
        'email',
        'message',
        ];
}
