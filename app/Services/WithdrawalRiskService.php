<?php

namespace App\Services;

use App\Models\User;
use App\Models\withdrawals;
use Carbon\Carbon;

class WithdrawalRiskService
{
    public function assess(User $user, float $amount, string $address, ?string $network = null): array
    {
        $flags = [];
        $score = 0;

        $cashoutBalance = (float) $user->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount');

        if ($cashoutBalance > 0 && $amount >= ($cashoutBalance * 0.75)) {
            $score += 20;
            $flags[] = 'withdraws_more_than_75_percent_of_available_balance';
        }

        $completedToAddress = withdrawals::where('user_id', $user->id)
            ->where('wallet_address', $address)
            ->where('status', withdrawals::STATUS_COMPLETED)
            ->exists();

        if (!$completedToAddress) {
            $score += 20;
            $flags[] = 'new_destination_address';
        }

        $recentCount = withdrawals::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->count();

        if ($recentCount >= (int) env('WITHDRAWAL_SUSPICIOUS_DAILY_COUNT', 3)) {
            $score += 25;
            $flags[] = 'many_withdrawals_in_24h';
        }

        $sameAddressRecent = withdrawals::where('wallet_address', $address)
            ->where('user_id', '!=', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->exists();

        if ($sameAddressRecent) {
            $score += 35;
            $flags[] = 'same_destination_used_by_another_user_recently';
        }

        if (strtoupper((string) $network) === 'TRC-20' && !preg_match('/^T[a-zA-Z0-9]{33}$/', $address)) {
            $score += 100;
            $flags[] = 'invalid_trc20_address_shape';
        }

        return [
            'score' => $score,
            'flags' => $flags,
        ];
    }
}
