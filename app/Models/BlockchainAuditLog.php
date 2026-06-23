<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockchainAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'event',
        'level',
        'user_id',
        'auditable_type',
        'auditable_id',
        'tx_hash',
        'address',
        'amount',
        'currency',
        'network',
        'ip_address',
        'request_id',
        'message',
        'context',
    ];

    protected $casts = [
        'context' => 'array',
        'amount'  => 'decimal:6',
    ];

    public static function record(string $event, array $data = []): self
    {
        return self::create(array_merge([
            'event'      => $event,
            'level'      => $data['level'] ?? 'info',
            'ip_address' => request()?->ip(),
        ], $data));
    }
}
