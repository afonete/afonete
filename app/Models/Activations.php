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

    protected $fillable = [
        'invoiceid',
        'code',
        'package',
        'stutus',
        'is_auto_code',
        'credit_conditions',
        'token',
        'price',
        'email',
        'task',
        'withdrawmax',
        'period',
        'percentage',
        'countdown',
        'user_id',
    ];

    public function myOwner()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function purchaser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function redeemer()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public function myCredit(): HasOne
    {
        return $this->hasOne(Credit::class, "activation_id", "id");
    }

    /** §86: SUPER LEADER credit record tied to this activation code. */
    public function superLeaderCredit(): HasOne
    {
        return $this->hasOne(SuperLeaderCredit::class, "activation_id", "id");
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }
}
