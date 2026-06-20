<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Earnings;
use App\Models\User;
use App\Models\adventures;

class Payment extends Model
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
    'is_expired',
    'duration',
    'payable_id',
    'payable_type',
    ];

public function VenturePayable()
{
    return $this->morphTo();
}


public function user()
{
    return $this->belongsTo(User::class,"user","id");
}
public function package(){
    return $this->belongsTo(adventures::class,"package","id");
}
public function earnings()
{
    return $this->morphMany(Earnings::class, 'source');
}

}
