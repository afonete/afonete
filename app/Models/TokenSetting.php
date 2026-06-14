<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'token_price',   // legacy / master price
        'uvp_price',
        'renewal_price',
        'swap_price',
        'trading_price',
        'package_price',
        'coin_value',
        'currency',
        'token_symbol',
        'notes',
        'updated_by',
    ];

    /** Helper: get the one settings row, or a default object */
    public static function settings(): self
    {
        static $cached = null;
        if ($cached === null) {
            $cached = self::first() ?? new self([
                'uvp_price'     => 0.0025,
                'renewal_price' => 0.0025,
                'swap_price'    => 0.0025,
                'trading_price' => 0.0025,
                'package_price' => 0.0025,
                'coin_value'    => 0.002,
                'token_symbol'  => 'FONE',
            ]);
        }
        return $cached;
    }

    /** Price used when buying a UVP package (investment / uvp_price = LOCKED tokens) */
    public static function uvpPrice(): float   { return (float) self::settings()->uvp_price; }

    /** Price used for 30-day renewals (trading_voucher / renewal_price = AVAILABLE tokens) */
    public static function renewalPrice(): float { return (float) self::settings()->renewal_price; }

    /** Price used when swapping LOCKED tokens → CASHOUT */
    public static function swapPrice(): float  { return (float) self::settings()->swap_price; }

    /** USD value per 1 token for swap/withdrawal display */
    public static function coinValue(): float  { return (float) self::settings()->coin_value; }

    public static function currentSymbol(): string { return self::settings()->token_symbol ?? 'FONE'; }

    /** Legacy — maps to uvpPrice for backward compatibility */
    public static function currentPrice(): float { return self::uvpPrice(); }
}
