<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class withdrawals extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'wallet_address',
        'currency',
        'transaction_no',
        'plisio_txn_id',
        'status',
        'notes',
        'admin_note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
