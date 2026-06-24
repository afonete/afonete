<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositWallet extends Model
{
    use HasFactory;

    protected $table = 'deposit_wallets';

    protected $fillable = [
        'type','network','label','wallet_address','currency',
        'min_amount','max_amount','is_active','display_order','notes','instructions',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'display_order' => 'integer',
    ];

    /** Type constants */
    public const TYPE_CRYPTO        = 'crypto';
    public const TYPE_ADVCASH       = 'advcash';
    public const TYPE_PERFECT_MONEY = 'perfect_money';

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
        return self::where('is_active', true)
                    ->orderBy('display_order')
                    ->orderBy('network');
    }

    /** Convenience helper: returns the Collection directly (use sparingly) */
    public static function activeList()
    {
        return self::active()->get();
    }

    /**
     * Return active rows of one type only — used by the user deposit page
     * to render the right tab.
     */
    public static function activeOfType(string $type)
    {
        return self::where('type', $type)
                    ->where('is_active', true)
                    ->orderBy('display_order')
                    ->orderBy('network')
                    ->get();
    }

    /**
     * The crypto address with the right URI prefix so wallet apps
     * (Trust Wallet, MetaMask, etc.) auto-fill the right network
     * when the QR code is scanned.
     *
     *   TRC-20  →  tron:{address}
     *   ERC-20  →  ethereum:{address}
     *   BEP-20  →  bsc:{address}  (Binance Smart Chain)
     *   POLYGON →  polygon:{address}
     *   BITCOIN→  bitcoin:{address}
     */
    public function qrPayload(): string
    {
        $addr = trim((string) $this->wallet_address);
        if ($addr === '') return $addr;

        // Already a URI? leave alone.
        if (preg_match('#^[a-z]+:#i', $addr)) return $addr;

        return match (strtoupper((string) $this->network)) {
            'TRC-20'  => $addr,
            'BEP-20'  => $addr,
            'ERC-20'  => $addr,
            'POLYGON' => $addr,
            'BITCOIN' => $addr,
            default   => $addr,
        };
    }

    /**
     * URL for a QR code image from a free public API. Used by the
     * user deposit page. No composer dependency required.
     */
    public function qrImageUrl(int $size = 220): string
    {
        $payload = urlencode($this->qrPayload());
        // api.qrserver.com is free, no auth needed.
        return "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$payload}&margin=10";
    }

    /** Human-friendly kind label ("Crypto", "Advcash", "Perfect Money") */
    public function kindLabel(): string
    {
        return match ($this->type) {
            self::TYPE_ADVCASH       => 'Advcash',
            self::TYPE_PERFECT_MONEY => 'Perfect Money',
            default                 => 'Crypto',
        };
    }

    /**
     * Validate a crypto wallet address has roughly the right shape.
     * Cheap heuristic — NOT a substitute for a real check.
     */
    public function addressLooksValid(): bool
    {
        $addr = trim((string) $this->wallet_address);
        if ($addr === '') return false;
        return match (strtoupper((string) $this->network)) {
            'TRC-20'  => (bool) preg_match('/^T[a-zA-Z0-9]{33}$/', $addr),
            'BITCOIN' => (bool) preg_match('/^(1|3|bc1)[a-zA-Z0-9]{25,90}$/', $addr),
            'ERC-20', 'BEP-20', 'POLYGON' => (bool) preg_match('/^0x[a-fA-F0-9]{40}$/', $addr),
            default   => strlen($addr) >= 4,
        };
    }
}
