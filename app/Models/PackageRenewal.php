<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageRenewal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_id',
        'amount_paid',
        'token_price_at_renewal',
        'tokens_received',
        'renewal_number',
        'max_renewals',
        'renewed_at',
        'next_renewal_due',
        'transaction_no',
        'status',
    ];

    protected $casts = [
        'renewed_at'       => 'date',
        'next_renewal_due' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
