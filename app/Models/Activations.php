<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Credit;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Payment;
use App\Models\User;
class Activations extends Model
{
    use HasFactory;
        protected $fillable=[
'invoiceid',
'code',
'package',
'stutus',
'is_auto_code',
'credit_conditions',
'token',
'price','email','task','withdrawmax','period',
'percentage','countdown'
];


// /**
//  * Get the user associated with the Activations
//  *
//  * @return \Illuminate\Database\Eloquent\Relations\HasOne
//  */
// public function user(): HasOne
// {
//     return $this->hasOne(User::class, 'foreign_key', 'local_key');
// }
public function myOwner() {
    return $this->belongsTo(User::class, 'user_id');
}

public function myCredit():HasOne
    {
        return $this->hasOne(Credit::class,"activation_id","id");
    }

    public function payments()
    {
        return $this->morphTo(Payment::class, 'payable');
    }
}

