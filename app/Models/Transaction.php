<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_no',
        'transaction_type',
        'transaction_details',
        'user_id',
        'receiver_id'
    ];
    protected $hidden = [
        'user_id', // Hide user relationship when serializing
        'receiver_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateTransactionNo()
    {
        do {
            // Generate a random unique identifier
            $microtime = microtime(true);
        $uniqueNumber = intval($microtime * 1000000)+date('Y');

            $transactionNo = 'TRX-' . strtoupper(Str::random(10)).'-'.$uniqueNumber;
        } while (self::where('transaction_no', $transactionNo)->exists());

        return $transactionNo;
    }

}
