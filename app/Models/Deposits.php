<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use Illuminate\Support\Str;

class Deposits extends Model
{
    use HasFactory;
    protected $fillable = [
      'user_id',
      'amount_deposited',
      'amount_removed',
      'currency_type',
      'deposit_method',
      'status',
      'transaction_id',
      'user_wallet_address',
      'network'
    ];

    // public function
    /**
     * Get the user that owns the deposits
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function generateTransactionNo()
    {
        do {
            // Generate a random unique identifier
            $microtime = microtime(true);
        $uniqueNumber = intval($microtime * 1000000)+date('Y');

            $transactionNo = 'TRX-' . strtoupper(Str::random(10)).'-'.$uniqueNumber;
        } while (self::where('transaction_id', $transactionNo)->exists());

        return $transactionNo;
    }
    
}
