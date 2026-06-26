<?php

namespace App\Console\Commands;

use App\Models\BlockchainAuditLog;
use App\Models\ChartAccount;
use App\Models\Deposits;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MoveApprovedDepositsToDepositAccount extends Command
{
    protected $signature = 'finance:move-deposits-to-deposit-account
                            {--apply : Actually update balances. Without this it is a dry run.}
                            {--user_id= : Only process one user ID.}
                            {--allow-negative-cashout : Allow CASHOUT to go below zero when subtracting historical deposits.}';

    protected $description = 'Move approved deposit balances from withdrawable CASHOUT to non-withdrawable DEPOSIT account';

    public function handle(): int
    {
        $apply = (bool) $this->option('apply');
        $allowNegative = (bool) $this->option('allow-negative-cashout');
        $userId = $this->option('user_id');

        $query = User::query()->orderBy('id');
        if ($userId) {
            $query->where('id', (int) $userId);
        }

        $this->warn($apply ? 'APPLY MODE: balances will be updated.' : 'DRY RUN: no balances will be changed. Add --apply to update.');

        $rows = [];
        $processed = 0;

        $query->chunkById(100, function ($users) use (&$rows, &$processed, $apply, $allowNegative) {
            foreach ($users as $user) {
                $approvedDeposits = (float) Deposits::where('user_id', $user->id)
                    ->where('status', 'approved')
                    ->sum('amount_deposited');

                $removedDeposits = (float) Deposits::where('user_id', $user->id)
                    ->sum('amount_removed');

                // This is the user's remaining non-withdrawable deposit balance according to the deposit ledger.
                $targetDepositBalance = max(0, $approvedDeposits - $removedDeposits);

                if ($targetDepositBalance <= 0) {
                    continue;
                }

                $cashout = (float) ChartAccount::where('user_id', $user->id)->where('acc_type', 'CASHOUT')->sum('amount');
                $currentDeposit = (float) ChartAccount::where('user_id', $user->id)->where('acc_type', 'DEPOSIT')->sum('amount');

                // Set DEPOSIT to at least the ledger target. Do not reduce if admin already put more there.
                $depositIncrease = max(0, $targetDepositBalance - $currentDeposit);
                $cashoutDecrease = $depositIncrease;
                $newCashout = $cashout - $cashoutDecrease;

                $note = '';
                if ($newCashout < 0 && !$allowNegative) {
                    $cashoutDecrease = $cashout;
                    $newCashout = 0;
                    $note = 'CASHOUT had less than the deposit amount; set CASHOUT to 0. Review user manually.';
                }

                $rows[] = [
                    $user->id,
                    $user->email,
                    number_format($approvedDeposits, 2),
                    number_format($removedDeposits, 2),
                    number_format($targetDepositBalance, 2),
                    number_format($cashout, 2),
                    number_format($cashoutDecrease, 2),
                    number_format($newCashout, 2),
                    $note,
                ];

                if ($apply && ($depositIncrease > 0 || $cashoutDecrease > 0)) {
                    DB::transaction(function () use ($user, $targetDepositBalance, $newCashout, $cashoutDecrease, $depositIncrease, $note) {
                        ChartAccount::updateOrCreate(
                            ['user_id' => $user->id, 'acc_type' => 'DEPOSIT'],
                            ['amount'  => $targetDepositBalance]
                        );

                        ChartAccount::updateOrCreate(
                            ['user_id' => $user->id, 'acc_type' => 'CASHOUT'],
                            ['amount'  => $newCashout]
                        );

                        BlockchainAuditLog::record('finance.deposit_reclassified', [
                            'user_id'  => $user->id,
                            'amount'   => $depositIncrease,
                            'currency' => 'USD',
                            'message'  => 'Approved deposits moved from withdrawable CASHOUT to non-withdrawable DEPOSIT account.',
                            'context'  => [
                                'deposit_balance_set_to' => $targetDepositBalance,
                                'cashout_decreased_by'   => $cashoutDecrease,
                                'new_cashout'            => $newCashout,
                                'note'                   => $note,
                            ],
                        ]);
                    });
                }

                $processed++;
            }
        });

        $this->table([
            'User ID', 'Email', 'Approved Deposits', 'Removed/Used', 'Target DEPOSIT',
            'Current CASHOUT', 'CASHOUT Decrease', 'New CASHOUT', 'Note'
        ], $rows);

        $this->info("Users needing review/update: {$processed}");
        return 0;
    }
}
