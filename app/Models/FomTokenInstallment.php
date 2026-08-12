<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
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

    /**
     * Ensures table exists.
     */
    public static function ensureTable()
    {
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
                });
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("FomTokenInstallment ensureTable error: " . $e->getMessage());
        }
    }

    /**
     * Creates 12 monthly installments for an activated FOM package.
     */
    public static function createSchedule($userId, $activationId, string $packageName, float $totalReturn)
    {
        self::ensureTable();

        $installmentAmount = $totalReturn / 12;

        for ($i = 1; $i <= 12; $i++) {
            self::create([
                'user_id'            => $userId,
                'activation_id'      => $activationId,
                'package_name'       => $packageName,
                'total_return'       => $totalReturn,
                'installment_number' => $i,
                'amount'             => $installmentAmount,
                'release_date'       => Carbon::now()->addMonths($i),
                'status'             => 'pending',
            ]);
        }
    }

    /**
     * Processes any due installments for a user, moving tokens from Escrow (LOCKED_TOKEN) to AVAILABLE_TOKEN.
     */
    public static function processDueInstallments($user)
    {
        if (!$user) return;
        self::ensureTable();

        $dueInstallments = self::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('release_date', '<=', Carbon::now())
            ->get();

        foreach ($dueInstallments as $inst) {
            $amount = (float) $inst->amount;

            // 1. Deduct from Escrow Wallet (LOCKED_TOKEN)
            $lockedBal = (float) $user->ChartAccount()->where('acc_type', 'LOCKED_TOKEN')->sum('amount');
            $newLocked = max(0, $lockedBal - $amount);
            ChartAccount::updateOrCreate(
                ['user_id' => $user->id, 'acc_type' => 'LOCKED_TOKEN'],
                ['amount' => $newLocked]
            );

            // 2. Add to Available Token (AVAILABLE_TOKEN)
            $availBal = (float) $user->ChartAccount()->where('acc_type', 'AVAILABLE_TOKEN')->sum('amount');
            ChartAccount::updateOrCreate(
                ['user_id' => $user->id, 'acc_type' => 'AVAILABLE_TOKEN'],
                ['amount' => $availBal + $amount]
            );

            // 3. Mark installment as completed
            $inst->update([
                'status'       => 'completed',
                'processed_at' => Carbon::now(),
            ]);

            // 4. Log transaction
            $txnNo = class_exists(Transaction::class) && method_exists(Transaction::class, 'generateTransactionNo')
                ? Transaction::generateTransactionNo()
                : 'FOM-INST-' . time() . '-' . rand(100, 999);

            Transaction::create([
                'user_id'             => $user->id,
                'transaction_no'      => $txnNo,
                'transaction_type'    => 'FOM_MONTHLY_TOKEN_INSTALLMENT',
                'transaction_details' => json_encode([
                    'package'            => $inst->package_name,
                    'installment_number' => $inst->installment_number,
                    'tokens'             => $amount,
                    'status'             => 'completed',
                    'processed_at'       => Carbon::now()->toDateTimeString(),
                ]),
            ]);
        }
    }
}
