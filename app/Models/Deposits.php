<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'deposit_address',
        'network',
        'comment',
        'proof_of_payment',
        'payment_context',      // e.g. PACKAGE_PAYMENT for direct package invoices
        'package_type',         // VENTURE or FC
        'package_id',
        'package_name',
        'activated_payment_id',
        'activated_at',
        'expires_at',
        'blockchain_tx_hash',   // new for direct blockchain
        'confirmations',
        'detected_at',
        'credited_at',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
        'credited_at' => 'datetime',
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function generateTransactionNo(): string
    {
        do {
            $uniqueNumber = intval(microtime(true) * 1000000) + date('Y');
            $transactionNo = 'TRX-' . strtoupper(Str::random(10)) . '-' . $uniqueNumber;
        } while (self::where('transaction_id', $transactionNo)->exists());

        return $transactionNo;
    }

    /**
     * Self-heal: backfill missing 'used' ledger rows for FOM package
     * purchases made before buyFomPackage() wrote them.
     *
     * Historic FOM purchases debited ONLY the ChartAccount DEPOSIT wallet;
     * no Deposits row was recorded. Every "available deposit" computation
     * on the platform is (approved − used) over THIS table, so those users
     * saw their TOTAL deposit as still available — and the wallet-page
     * ChartAccount sync even restored the spent funds from the stale ledger.
     *
     * Idempotent: keyed on transaction_id = the FOM_PACKAGE_PURCHASE
     * transaction_no, so each purchase is backfilled at most once.
     *
     * @return int number of ledger rows created
     */
    public static function ensureFomPurchaseLedgerRows(int $userId): int
    {
        $created = 0;

        try {
            $txns = \App\Models\Transaction::where('user_id', $userId)
                ->where('transaction_type', 'FOM_PACKAGE_PURCHASE')
                ->orderBy('created_at')
                ->get();

            foreach ($txns as $t) {
                if (self::where('user_id', $userId)->where('transaction_id', $t->transaction_no)->exists()) {
                    continue;
                }

                $details = json_decode((string) $t->transaction_details, true) ?: [];
                $cost = (float) ($details['total_cost'] ?? 0);
                if ($cost <= 0) {
                    continue;
                }

                $last = self::where('user_id', $userId)
                    ->whereNotNull('user_wallet_address')
                    ->latest()
                    ->first();

                $row = self::create([
                    'user_id'             => $userId,
                    'amount_deposited'    => 0,
                    'amount_removed'      => $cost,
                    'currency_type'       => 'DOLLAR',
                    'deposit_method'      => 'FOM_PACKAGE_PURCHASE',
                    'user_wallet_address' => ($last && !empty($last->user_wallet_address)) ? $last->user_wallet_address : 'INTERNAL_DEPOSIT_WALLET',
                    'network'             => ($last && !empty($last->network)) ? $last->network : 'TRC-20',
                    'status'              => 'used',
                    'transaction_id'      => $t->transaction_no,
                    'comment'             => 'Backfilled ledger row for FOM package purchase (self-heal): '
                        . ($details['quantity'] ?? 1) . 'x ' . ($details['package'] ?? 'FOM'),
                ]);

                // Keep the ledger chronology truthful.
                $row->created_at = $t->created_at;
                $row->save();

                $created++;
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('ensureFomPurchaseLedgerRows failed for user ' . $userId . ': ' . $e->getMessage());
        }

        return $created;
    }
}
