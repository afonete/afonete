<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenWithdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token_amount',
        'coin_value_at_request',
        'wallet_address',
        'transaction_no',
        'status',
        'admin_note',
        'approved_by',
        'source_wallet',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
