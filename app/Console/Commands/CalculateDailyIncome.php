<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment as Paymodel;
use App\Models\User;
use App\Models\DailyIncome;
use App\Models\ChartAccount;
use App\Models\Transaction;
use App\Models\adventures;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CalculateDailyIncome extends Command
{
    protected $signature   = 'income:calculate';
    protected $description = 'Calculate and credit daily income for all users with active VENTURE packages';

    public function handle()
    {
        $now = Carbon::now();

        // All active VENTURE payments
        $payments = Paymodel::where('category', 'VENTURE')
                            ->where('is_expired', 0)
                            ->where('status', 1)
                            ->get();

        if ($payments->isEmpty()) {
            $this->info('No active VENTURE packages found.');
            return 0;
        }

        $processed = 0;
        $skipped   = 0;

        foreach ($payments as $package) {

            $user = User::find($package->user);
            if (!$user) {
                $this->warn("User {$package->user} not found for payment {$package->id}, skipping.");
                continue;
            }

            $package2 = adventures::find($package->payable_id);
            if (!$package2) {
                $this->warn("Adventure package {$package->payable_id} not found for payment {$package->id}, skipping.");
                continue;
            }

            if (!$package->expiration_date) {
                $this->warn("Payment {$package->id} has no expiration_date, skipping.");
                continue;
            }

            $percentcharge  = $package2->percentage;
            $amount         = (float) $package->paid;
            $startDate      = Carbon::parse($package->created_at);
            $expirationDate = Carbon::parse($package->expiration_date);

            // Ceiling: never generate past expiration or today
            $ceiling    = $expirationDate->lt($now) ? $expirationDate : $now;
            $daysPassed = (int) $startDate->diffInDays($ceiling);

            // Load renewal completions for this package
            $renewalCompletedAt = [];
            $renewals = \App\Models\PackageRenewal::where('user_id', $user->id)
                            ->where('payment_id', $package->id)
                            ->orderBy('renewal_number')
                            ->get();
            foreach ($renewals as $renewal) {
                $renewalCompletedAt[$renewal->renewal_number] = Carbon::parse($renewal->renewed_at);
            }

            for ($i = 1; $i <= $daysPassed; $i++) {
                $earnedAt = $startDate->copy()->addDays($i);

                // Hard stop at expiration
                if ($earnedAt->gt($expirationDate)) {
                    break;
                }

                // Renewal pause windows — dynamic based on package duration.
                // max_renewals = floor((duration - 1) / 30)
                // Renewal #N is required before day N*30 through day (N+1)*30 - 1.
                $packageDuration = (int) $package2->duration;
                $maxRenewals     = (int) floor(($packageDuration - 1) / 30);
                $blocked         = false;

                if ($maxRenewals > 0 && $i >= 30) {
                    // Which renewal window are we in? (1-indexed)
                    $windowIndex = (int) ceil($i / 30); // e.g. day 30-59 → 1, day 60-89 → 2 …
                    $neededRenewal = $windowIndex;      // renewal #neededRenewal must be done

                    if ($neededRenewal <= $maxRenewals) {
                        if (!isset($renewalCompletedAt[$neededRenewal]) ||
                            $earnedAt->lt($renewalCompletedAt[$neededRenewal])) {
                            $blocked = true;
                        }
                    }
                }

                if ($blocked) {
                    continue;
                }

                // Skip if already recorded
                $incomeExists = DailyIncome::where('user_id', $user->id)
                                            ->whereDate('earned_at', $earnedAt->toDateString())
                                            ->exists();
                if ($incomeExists) {
                    continue;
                }

                // Calculate
                $poolCapital = $amount * 80 / 100;
                $dailyIncome = $poolCapital * $percentcharge / 100;
                $trading     = $dailyIncome * 75 / 100;
                $cashout     = $dailyIncome * 25 / 100;

                // Record DailyIncome
                DailyIncome::create([
                    'user_id'   => $user->id,
                    'amount'    => $dailyIncome,
                    'earned_at' => $earnedAt,
                ]);

                // Record Transaction
                $transactionNo = Transaction::generateTransactionNo();
                Transaction::create([
                    'user_id'             => $user->id,
                    'transaction_no'      => $transactionNo,
                    'transaction_type'    => 'INCOME',
                    'receiver_id'         => 0,
                    'transaction_details' => json_encode([
                        'type'             => 'UVP',
                        'user'             => $user->name,
                        'date'             => $earnedAt->toDateTimeString(),
                        'cash_25'          => $cashout,
                        'trading_75'       => $trading,
                        'amount'           => $dailyIncome,
                        'trx_name'         => 'UVP INCOME',
                        'description'      => 'Payment From Pool Capital',
                        'day_number'       => $i,
                        'status'           => 'success',
                        'username'         => $user->name,
                        'leadership_bonus' => 0,
                    ]),
                ]);

                // Credit ChartAccounts
                $beforeCashout = $user->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount');
                $beforeTrading = $user->ChartAccount()->where('acc_type', 'TRADING')->sum('amount');

                ChartAccount::updateOrCreate(
                    ['user_id' => $user->id, 'acc_type' => 'TRADING'],
                    ['amount'  => $beforeTrading + $trading]
                );

                ChartAccount::updateOrCreate(
                    ['user_id' => $user->id, 'acc_type' => 'CASHOUT'],
                    ['amount'  => $beforeCashout + $cashout]
                );

                $processed++;
            }

            $skipped++;
        }

        $this->info("income:calculate done — {$processed} income entries created across {$skipped} packages.");
        return 0;
    }
}
