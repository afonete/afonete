<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ChartAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "acc_type",
        "amount"
    ];


    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Atomically apply a delta (credit = positive, debit = negative) to a
     * user's wallet using SELECT ... FOR UPDATE row locking.
     *
     * MUST be called inside a DB::transaction() — the row lock is held until
     * the surrounding transaction commits/rolls back, which is what prevents
     * concurrent read-modify-write races (double-spend / double-release).
     *
     * Behaviour on insufficient balance for a debit:
     *   - $strict = true  : throws \RuntimeException (caller should catch and
     *                       roll back / show an error). Use for user-initiated
     *                       spends (purchases, stakes, transfers).
     *   - $strict = false : clamps the balance at 0, logs a warning, and
     *                       reports the shortfall in the returned array. Use
     *                       for scheduled releases where the credit side must
     *                       still honour the schedule but the mismatch must be
     *                       visible for audit.
     *
     * If duplicate rows exist for the same (user_id, acc_type) they are
     * consolidated: the full new balance is written to the first row and the
     * duplicates are zeroed, so the total (sum) stays correct.
     *
     * @return array{previous: float, balance: float, shortfall: float}
     */
    public static function applyDeltaLocked(int $userId, string $accType, float $delta, bool $strict = false, string $context = ''): array
    {
        $rows = self::where('user_id', $userId)
            ->where('acc_type', $accType)
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

        if ($rows->isEmpty()) {
            $created = self::create([
                'user_id'  => $userId,
                'acc_type' => $accType,
                'amount'   => 0,
            ]);
            $rows = self::where('id', $created->id)->lockForUpdate()->get();
        }

        $previous  = (float) $rows->sum('amount');
        $new       = $previous + $delta;
        $shortfall = 0.0;

        if ($new < 0) {
            $shortfall = -$new;

            if ($strict) {
                throw new \RuntimeException(
                    "Insufficient {$accType} balance for user #{$userId}: have " .
                    number_format($previous, 2) . ", need " . number_format(abs($delta), 2) .
                    ($context !== '' ? " ({$context})" : '')
                );
            }

            Log::warning("ChartAccount underflow on {$accType} for user #{$userId}: " .
                "balance " . number_format($previous, 2) . ", debit " . number_format(abs($delta), 2) .
                ", shortfall " . number_format($shortfall, 2) .
                ($context !== '' ? " ({$context})" : '') .
                ". Balance clamped to 0 — investigate escrow accounting.");

            $new = 0.0;
        }

        // Consolidate: full balance on the first row, duplicates zeroed.
        $first = $rows->first();
        $first->amount = $new;
        $first->save();

        foreach ($rows->slice(1) as $dup) {
            if ((float) $dup->amount != 0.0) {
                $dup->amount = 0;
                $dup->save();
            }
        }

        return ['previous' => $previous, 'balance' => $new, 'shortfall' => $shortfall];
    }

    /**
     * Atomically credit a wallet. Must run inside DB::transaction().
     */
    public static function creditLocked(int $userId, string $accType, float $amount, string $context = ''): array
    {
        return self::applyDeltaLocked($userId, $accType, abs($amount), false, $context);
    }

    /**
     * Atomically debit a wallet. Must run inside DB::transaction().
     * Throws \RuntimeException when $strict and the balance is insufficient.
     */
    public static function debitLocked(int $userId, string $accType, float $amount, bool $strict = true, string $context = ''): array
    {
        return self::applyDeltaLocked($userId, $accType, -abs($amount), $strict, $context);
    }
}
