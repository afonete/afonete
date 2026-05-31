<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FC extends Model
{
    use HasFactory;

    protected $fillable =[
        'user',
        'package',
        'category',
        'category_id',
        'amount',
        'status',
        'paid',
        'over_paid',
        'expiration_date',
        'is_expired'
        ];

        // public function investments()
        // {
        //     return $this->hasMany(Payment::class,"package","id");
        // }

}
