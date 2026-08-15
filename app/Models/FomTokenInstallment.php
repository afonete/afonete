<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Schema\Blueprint;
use App\Models\ChartAccount;
use App\Models\Transaction;
use Carbon\Carbon;

class FomTokenInstallment extends Model
{
    use HasFactory;

    protected $table = 'fom_token_installments';

    protected $fillable = [
        'user_id',
        'activation_id',
        'package_name',
        'total_return',
        'installment_number',
        'amount',
        'release_date',
        'status',
        'processed_at',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    /** Per-request memo — avoids a Schema::hasTable() query on every call. */
    protected static $ensured = false;

    /**
     * Ensures table exists.
     */
    public static function ensureTable()
    {
        if (static::$ensured) {
            return;
        }
        static::$ensured = true;

        try {
            if (!Schema::hasTable('fom_token_installments')) {
                Schema::create('fom_token_installments', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id');
                    $table->unsignedBigInteger('activation_id')->nullable();
                    $table->string('package_name')->nullable();
                    $table->decimal('total_return', 20, 2)->default(0.00);
                    $table->integer('installment_number')->default(1);
                    $table->decimal('amount', 20, 2)->default(0.00);
                    $table->dateTime('release_date');
                    $table->string('status')->default('pending');
                    $table->dateTime('processed_at')->nullable();
                    $table->timestamps();
                    $table->index(['user_id', 'status', 'release_date'], 'fti_user_status_release_idx');
                });
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("FomTokenInstallment ensureTable error: " . $e->getMessage());
        }
    }

    /**
     * Creates 12 monthly installments for an activated FOM package.
     *
     * Amounts are rounded to 2 decimals and the final installment absorbs the
     * rounding remainder, so the 12 rows always sum EXACTLY to $totalReturn.
     *
     * Runs inside a transaction (participates in the caller's transaction if
     * one is already open) so a crash can never leave a partial schedule.
     */
    public static function createSchedule($userId, $activationId, string $packageName, float $totalReturn)
    {
        self::ensureTable();

        $totalReturn = round($totalReturn, 2);
        $baseAmount  = floor(($totalReturn / 12) * 100) / 100; // round DOWN to 2dp
        $lastAmount  = round($totalReturn - ($baseAmount * 11), 2); // absorbs remainder

        DB::transaction(function () use ($userId, $activationId, $packageName, $totalReturn, $baseAmount, $lastAmount) {
            for ($i = 1; $i <= 12; $i++) {
                self::create([
                    'user_id'            => $userId,
                    'activation_id'      => $activationId,
                    'package_name'       => $packageName,
                    'total_return'       => $totalReturn,
                    'installment_number' => $i,
                    'amount'             => ($i === 12) ? $lastAmount : $baseAmount,
                    'release_date'       => Carbon::now()->addMonths($i),
                    'status'             => 'pending',
                ]);
            }
        });
    }

    /**
     * Processes any due installments for a user, moving tokens from the
     * Independent Escrow Wallet (ESCROW_TOKEN) to AVAILABLE_TOKEN.
     *
     * Concurrency-safe: each installment is processed in its own
     * DB::transaction() with the installment row AND the wallet rows locked
     * (SELECT ... FOR UPDATE). The pending status is re-checked after
     * acquiring the lock, so two concurrent requests can never release the
     * same installment twice.
     *
     * Escrow underflow is NOT silently ignored: the shortfall is logged as a
     * warning by ChartAccount::debitLocked() and recorded in the transaction
     * details for audit, while the user still receives the scheduled amount.
     */
    public static function processDueInstallments($user)
    {
        if (!$user) return;
        self::ensureTable();

        $dueIds = self::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('release_date', '<=', Carbon::now())
            ->pluck('id');

        foreach ($dueIds as $id) {
            try {
                DB::transaction(function () use ($user, $id) {
                    // Lock the installment row and re-check status to prevent
                    // double-release under concurrency.
                    $inst = self::where('id', $id)->lockForUpdate()->first();
                    if (!$inst || $inst->status !== 'pending' || $inst->release_date > Carbon::now()) {
                        return; // already processed by a concurrent request
                    }

                    $amount = (float) $inst->amount;
                    $context = "FOM installment #{$inst->installment_number} ({$inst->package_name}) id={$inst->id}";

                    // 1. Deduct from Independent Escrow Wallet (ESCROW_TOKEN).
                    //    Non-strict: honour the schedule, but log + record any shortfall.
                    $debit = ChartAccount::debitLocked($user->id, 'ESCROW_TOKEN', $amount, false, $context);

                    // 2. Add to Available Token (AVAILABLE_TOKEN)
                    ChartAccount::creditLocked($user->id, 'AVAILABLE_TOKEN', $amount, $context);

                    // 3. Mark installment as completed
                    $inst->update([
                        'status'       => 'completed',
                        'processed_at' => Carbon::now(),
                    ]);

                    // 4. Log transaction
                    $txnNo = class_exists(Transaction::class) && method_exists(Transaction::class, 'generateTransactionNo')
                        ? Transaction::generateTransactionNo()
                        : 'FOM-INST-' . time() . '-' . rand(100, 999);

                    $details = [
                        'package'            => $inst->package_name,
                        'installment_number' => $inst->installment_number,
                        'tokens'             => $amount,
                        'status'             => 'completed',
                        'processed_at'       => Carbon::now()->toDateTimeString(),
                    ];
                    if ($debit['shortfall'] > 0) {
                        $details['escrow_shortfall'] = $debit['shortfall'];
                    }

                    Transaction::create([
                        'user_id'             => $user->id,
                        'transaction_no'      => $txnNo,
                        'transaction_type'    => 'FOM_MONTHLY_TOKEN_INSTALLMENT',
                        'transaction_details' => json_encode($details),
                    ]);
                });
            } catch (\Throwable $e) {
                // One failed installment must not block the others; it stays
                // 'pending' and will be retried on the next page load.
                Log::error("FomTokenInstallment::processDueInstallments failed for installment #{$id}: " . $e->getMessage());
            }
        }
    }
}
