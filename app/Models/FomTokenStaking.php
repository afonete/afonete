<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\ChartAccount;
use App\Models\TokenSetting;
use App\Models\Transaction;
use Carbon\Carbon;

class FomTokenStaking extends Model
{
    use HasFactory;

    protected $table = 'fom_token_stakings';

    protected $fillable = [
        'user_id',
        'principal_amount',
        'yield_percent',
        'profit_amount',
        'total_staked',
        'lock_years',
        'release_date',
        'status',
        'processed_at',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    /**
     * Ensures table exists.
     */
    public static function ensureTable()
    {
        try {
            if (!Schema::hasTable('fom_token_stakings')) {
                Schema::create('fom_token_stakings', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id');
                    $table->decimal('principal_amount', 20, 2)->default(0.00);
                    $table->decimal('yield_percent', 8, 2)->default(0.00);
                    $table->decimal('profit_amount', 20, 2)->default(0.00);
                    $table->decimal('total_staked', 20, 2)->default(0.00);
                    $table->integer('lock_years')->default(1);
                    $table->dateTime('release_date');
                    $table->string('status')->default('pending');
                    $table->dateTime('processed_at')->nullable();
                    $table->timestamps();
                });
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("FomTokenStaking ensureTable error: " . $e->getMessage());
        }
    }

    /**
     * Helper to get yield % configured by admin for 1 to 5 years lock.
     */
    public static function getYieldPercent(int $years): float
    {
        try {
            if (Schema::hasTable('token_settings')) {
                $setting = TokenSetting::first();
                if ($setting) {
                    $col = 'staking_yield_' . $years . '_year';
                    if (isset($setting->$col) && is_numeric($setting->$col)) {
                        return (float) $setting->$col;
                    }
                }
            }
        } catch (\Throwable $e) {}

        $defaults = [
            1 => 10.00,
            2 => 25.00,
            3 => 45.00,
            4 => 70.00,
            5 => 100.00,
        ];

        return $defaults[$years] ?? 10.00;
    }

    /**
     * Processes any completed stakings (where release_date <= now) and returns Total Staked (Principal + Profit) from Independent Escrow Wallet (ESCROW_TOKEN) back to Available Token.
     */
    public static function processDueStakings($user)
    {
        if (!$user) return;
        self::ensureTable();

        $dueStakings = self::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('release_date', '<=', Carbon::now())
            ->get();

        foreach ($dueStakings as $staking) {
            $totalStaked = (float) $staking->total_staked;

            // 1. Deduct from Independent Escrow Wallet (ESCROW_TOKEN)
            $escrowBal = (float) $user->ChartAccount()->where('acc_type', 'ESCROW_TOKEN')->sum('amount');
            $newEscrow = max(0, $escrowBal - $totalStaked);
            ChartAccount::updateOrCreate(
                ['user_id' => $user->id, 'acc_type' => 'ESCROW_TOKEN'],
                ['amount' => $newEscrow]
            );

            // 2. Add Total Staked (Principal + Profit) to Available Token (AVAILABLE_TOKEN)
            $availBal = (float) $user->ChartAccount()->where('acc_type', 'AVAILABLE_TOKEN')->sum('amount');
            ChartAccount::updateOrCreate(
                ['user_id' => $user->id, 'acc_type' => 'AVAILABLE_TOKEN'],
                ['amount' => $availBal + $totalStaked]
            );

            // 3. Mark staking as completed
            $staking->update([
                'status'       => 'completed',
                'processed_at' => Carbon::now(),
            ]);

            // 4. Log transaction
            $txnNo = class_exists(Transaction::class) && method_exists(Transaction::class, 'generateTransactionNo')
                ? Transaction::generateTransactionNo()
                : 'FOM-STAKE-REL-' . time() . '-' . rand(100, 999);

            Transaction::create([
                'user_id'             => $user->id,
                'transaction_no'      => $txnNo,
                'transaction_type'    => 'FOM_ESCROW_STAKING_RELEASE',
                'transaction_details' => json_encode([
                    'lock_years'     => $staking->lock_years,
                    'principal'      => $staking->principal_amount,
                    'profit'         => $staking->profit_amount,
                    'total_released' => $totalStaked,
                    'status'         => 'completed',
                    'processed_at'   => Carbon::now()->toDateTimeString(),
                ]),
            ]);
        }
    }
}
