<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class BlockchainDepositAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'currency',
        'network',
        'address',
        'encrypted_private_key',
        'derivation_path',
        'is_active',
        'last_scanned_at',
        'last_tx_hash',
        'metadata',
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'last_scanned_at' => 'datetime',
        'metadata'        => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setPrivateKeyAttribute(?string $privateKey): void
    {
        if ($privateKey) {
            $this->attributes['encrypted_private_key'] = Crypt::encryptString($privateKey);
        }
    }

    public function getPrivateKeyAttribute(): ?string
    {
        if (empty($this->encrypted_private_key)) {
            return null;
        }

        return Crypt::decryptString($this->encrypted_private_key);
    }

    public static function activeTronUsdt()
    {
        return self::query()
            ->where('is_active', true)
            ->where('currency', 'USDT')
            ->where('network', 'TRC-20');
    }

}
