<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class withdrawals extends Model
{
    use HasFactory;

    protected $table = 'withdrawals';

    protected $fillable = [
        'user_id',
        'method',         // crypto | advcash | perfect_money
        'network',        // TRC-20 | ERC-20 | BEP-20 | POLYGON | BITCOIN | ADVCASH | PERFECT_MONEY
        'amount',
        'currency',       // USDT | BTC | ETH | BNB | USD | EUR
        'wallet_address', // crypto address OR advcash/PM account number
        'transaction_no',
        'plisio_txn_id',
        'txn_hash',
        'blockchain_tx_hash',
        'gas_fee',
        'fee_amount',
        'net_amount',
        'status',         // pending | processing | completed | failed
        'approval_required',
        'risk_score',
        'risk_flags',
        'idempotency_key',
        'attempts',
        'locked_at',
        'last_attempt_at',
        'failure_reason',
        'signer_request_id',
        'signer_response',
        'notes',          // user notes
        'admin_note',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'amount'            => 'decimal:2',
        'gas_fee'           => 'decimal:6',
        'net_amount'        => 'decimal:6',
        'approval_required' => 'boolean',
        'risk_flags'        => 'array',
        'signer_response'   => 'array',
        'locked_at'         => 'datetime',
        'last_attempt_at'   => 'datetime',
        'processed_at'      => 'datetime',
    ];

    // Method / status / network constants — used everywhere in views & controllers
    public const METHOD_CRYPTO         = 'crypto';
    public const METHOD_ADVCASH        = 'advcash';
    public const METHOD_PERFECT_MONEY  = 'perfect_money';

    public const STATUS_PENDING        = 'pending';
    public const STATUS_PROCESSING     = 'processing';
    public const STATUS_COMPLETED      = 'completed';
    public const STATUS_FAILED         = 'failed';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Human-friendly method label ("Crypto", "Advcash", "Perfect Money")
     * for the admin queue and user history.
     */
    public function methodLabel(): string
    {
        return match ($this->method) {
            self::METHOD_ADVCASH       => 'Advcash',
            self::METHOD_PERFECT_MONEY => 'Perfect Money',
            default                    => 'Crypto',
        };
    }

    /**
     * Human-friendly processing type.
     */
    public function typeLabel(): string
    {
        if ($this->blockchain_tx_hash && !$this->plisio_txn_id) {
            return 'Direct Blockchain';
        }

        return $this->plisio_txn_id ? 'Instant' : 'Manual';
    }

    /**
     * Display label for the destination — includes method + network.
     *   "USDT (TRC-20) — TXYZabc..."
     *   "Advcash USD — U 8678..."
     */
    public function destinationLabel(): string
    {
        if ($this->method === self::METHOD_CRYPTO) {
            return trim(($this->currency ?? '') . ' (' . ($this->network ?? '') . ') — ' . $this->wallet_address);
        }
        return $this->methodLabel() . ' ' . ($this->currency ?? '') . ' — ' . $this->wallet_address;
    }

    /**
     * Cheap heuristic — same shape checks as DepositWallet.
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
