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
        'initial_supply',
        'initial_liquidity',
        'staking_yield_1_year',
        'staking_yield_2_year',
        'staking_yield_3_year',
        'staking_yield_4_year',
        'staking_yield_5_year',
    ];

    /**
     * Helper: get the one settings row, or a default object.
     *
     * Defaults per spec:
     *   uvp_price      = 0.0025 USD/token (package purchase rate)
     *   renewal_price  = 0.0025 USD/token (30-day renewal rate)
     *   swap_price     = 0.002  USD/token (FREE_TOKEN → CASHOUT conversion)
     *   trading_price  = 0.0025 USD/token (reserved for buy/sell module)
     *   package_price  = 0.0025 USD/token (reserved for referral package)
     *   coin_value     = 0.002  USD/token (display value; not used in swap math)
     */
    public static function settings(): self
    {
        static $cached = null;
        if ($cached === null) {
            $cached = self::first() ?? new self([
                'uvp_price'         => 0.0025,
                'renewal_price'     => 0.0025,
                'swap_price'        => 0.002,
                'trading_price'     => 0.0025,
                'package_price'     => 0.0025,
                'coin_value'        => 0.002,
                'token_symbol'      => 'FONE',
                'initial_supply'    => 120000000000,
                'initial_liquidity' => 40000000000,
            ]);
        }
        return $cached;
    }

    /** Price used when buying a UVP package (investment / uvp_price = LOCKED tokens) */
    public static function uvpPrice(): float   { return (float) self::settings()->uvp_price; }

    /** Price used for 30-day renewals (trading_voucher / renewal_price = AVAILABLE tokens) */
    public static function renewalPrice(): float { return (float) self::settings()->renewal_price; }

    /** Price used when swapping tokens in internal exchange (`swap_price` from token_settings) */
    public static function swapPrice(): float  { return (float) (self::settings()->swap_price ?: 0.0025); }

    /** Price used for Trading Wallet buy/swap module (`trading_price` / "Trading Price Reserved" from token_settings) */
    public static function tradingPrice(): float { return (float) (self::settings()->trading_price ?: 0.0025); }

    /** USD value per 1 token for display */
    public static function coinValue(): float  { return (float) (self::settings()->coin_value ?: 0.0025); }

    /** Token symbol configured by admin (`token_symbol` from token_settings) */
    public static function currentSymbol(): string { return self::settings()->token_symbol ?? 'FOCOIN'; }

    /** Get the configurable initial supply (fallback to 120 Billion) */
    public static function initialSupply(): float
    {
        $settings = self::settings();
        return (float) (isset($settings->initial_supply) ? $settings->initial_supply : 120000000000);
    }

    /** Get the configurable initial liquidity pool (fallback to 40 Billion) */
    public static function initialLiquidity(): float
    {
        $settings = self::settings();
        return (float) (isset($settings->initial_liquidity) ? $settings->initial_liquidity : 40000000000);
    }

    /** Legacy — maps to uvpPrice for backward compatibility */
    public static function currentPrice(): float { return self::uvpPrice(); }
}
