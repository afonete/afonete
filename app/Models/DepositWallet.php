<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositWallet extends Model
{
    use HasFactory;

    protected $table = 'deposit_wallets';

    protected $fillable = [
        'network','label','wallet_address','currency',
        'min_amount','max_amount','is_active','notes',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
    ];

    /**
     * Return a query builder for active deposit wallets.
     * Callers should chain ->get() (or paginate()) to execute the query.
     *
     * NOTE: Previously this method called ->get() internally and returned
     * a Collection, which caused `Collection::get()` to be called with 0 args
     * when callers did `DepositWallet::active()->get()` again.
     */
    public static function active()
    {
        return self::where('is_active', true)->orderBy('network');
    }

    /** Convenience helper: returns the Collection directly (use sparingly) */
    public static function activeList()
    {
        return self::active()->get();
    }
}
