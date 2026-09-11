<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment as Paymodel;
use App\Models\User;
use App\Models\DailyIncome;
use App\Models\ChartAccount;
use App\Models\Transaction;
use App\Models\Adventures;
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

            $package2 = null;
            if ($package->payable_type && $package->payable_id) {
                $package2 = $package->payable_type::find($package->payable_id);
            }
            if (!$package2 && $package->payable_id) {
                $package2 = Adventures::find($package->payable_id);
            }
            if (!$package2 && $package->package) {
                $package2 = Adventures::where('name', $package->package)->first();
            }
            if (!$package2 && (float)($package->paid ?? $package->amount ?? 0) > 0) {
                $pPaid = (float) ($package->paid ?? $package->amount ?? 0);
                $package2 = Adventures::where('min_amount', '<=', $pPaid)
                    ->where('max_amount', '>=', $pPaid)
                    ->first();
            }

            if (!$package2) {
                $this->warn("Adventure package could not be resolved for payment {$package->id}, skipping.");
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

            $packageDuration = (int) $package2->duration;
            // max_renewals = ceil(duration/30). Renewals pay in arrears:
            //   100-day package → 4 renewals (30,60,90 full + 100 final pro-rated)
            // Only the first (maxRenewals - 1) renewals gate in-period daily income.
            // The final renewal (when it is a pro-rated partial due at expiry) is
            // a closing settlement and does NOT block days within the final window.
            $maxRenewals = (int) ceil($packageDuration / 30);

            for ($i = 1; $i <= $daysPassed; $i++) {
                $earnedAt = $startDate->copy()->addDays($i);

                // Hard stop at expiration
                if ($earnedAt->gt($expirationDate)) {
                    break;
                }

                // Renewal pause check (Issue 6 fix) — delegated to RenewalCalculator.
                //
                // Formula: $neededRenewal = floor((i - 1) / 30)
                //   day 1–30   → 0  (no renewal needed)
                //   day 31–60  → 1  (renewal #1 must be done at or before day i)
                //   day 61–90  → 2  (renewal #2 must be done at or before day i)
                //   day 91–100 → 3  (renewal #3 must be done at or before day i)
                //
                // If the needed renewal hasn't been completed yet, that day's
                // income is skipped (the days are "lost in his income" per spec).
                $neededRenewal = \App\Services\RenewalCalculator::renewalsRequiredForDay($i);

                if ($neededRenewal > 0 && $neededRenewal <= $maxRenewals) {
                    if (!isset($renewalCompletedAt[$neededRenewal]) ||
                        $earnedAt->lt($renewalCompletedAt[$neededRenewal])) {
                        continue; // skip this day's income — renewal was missed or not yet done
                    }
                }

                // Skip if already recorded
                $incomeExists = DailyIncome::where('user_id', $user->id)
                                            ->where('payment_id', $package->id)
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
                    'user_id'    => $user->id,
                    'payment_id' => $package->id,
                    'amount'     => $dailyIncome,
                    'earned_at'  => $earnedAt,
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
