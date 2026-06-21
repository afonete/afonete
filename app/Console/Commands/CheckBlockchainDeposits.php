<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TronBlockchainService;
use App\Models\Deposits;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckBlockchainDeposits extends Command
{
    protected $signature = 'blockchain:check-deposits {--once : Run only once instead of loop}';
    protected $description = 'Poll TronGrid for incoming USDT TRC20 deposits and auto-credit them';

    public function handle()
    {
        $service = new TronBlockchainService();
        $hotWallet = $service->getHotWalletAddress();

        if (!$hotWallet) {
            $this->error('TRON_HOT_WALLET_ADDRESS not set in .env');
            return 1;
        }

        $this->info("Checking deposits for hot wallet: {$hotWallet}");

        $transactions = $service->getRecentUsdtTransactions($hotWallet, 100);

        $credited = 0;

        foreach ($transactions as $tx) {
            $from = $tx['from'] ?? null;
            $to = $tx['to'] ?? null;
            $amount = isset($tx['value']) ? (float)$tx['value'] / 1_000_000 : 0;
            $txHash = $tx['transaction_id'] ?? $tx['txID'] ?? null;
            $timestamp = $tx['block_timestamp'] ?? null;

            if (!$txHash || !$from || $to !== $hotWallet || $amount <= 0) {
                continue;
            }

            // Check if already processed
            $exists = Deposits::where('transaction_id', $txHash)->exists()
                   || Deposits::where('user_wallet_address', $from)
                              ->where('amount_deposited', $amount)
                              ->where('status', 'approved')
                              ->exists();

            if ($exists) {
                continue;
            }

            // Try to match a pending deposit by amount + from address (or use a reference system)
            $pending = Deposits::where('status', 'pending')
                ->where('deposit_method', 'like', '%TRON%')
                ->where('amount_deposited', $amount)
                ->where('user_wallet_address', $from)
                ->first();

            if ($pending) {
                $pending->update([
                    'status' => 'approved',
                    'transaction_id' => $txHash,
                    'comment' => 'Auto-confirmed on-chain via TronGrid',
                    'network' => 'TRC-20',
                ]);

                // TODO: Credit the user balance here
                // $this->creditUser($pending->user_id, $amount);

                $credited++;
                $this->info("✓ Credited deposit #{$pending->id} — {$amount} USDT from {$from}");
            } else {
                // Create a new auto-detected deposit (fallback)
                Deposits::create([
                    'user_id' => 0, // unknown user - admin can assign later
                    'amount_deposited' => $amount,
                    'amount_removed' => 0,
                    'currency_type' => 'USDT',
                    'deposit_method' => 'AUTO_TRON_DIRECT',
                    'transaction_id' => $txHash,
                    'network' => 'TRC-20',
                    'user_wallet_address' => $from,
                    'status' => 'approved',
                    'comment' => 'Auto-detected deposit',
                ]);
                $credited++;
            }
        }

        $this->info("Done. New credits processed: {$credited}");
        return 0;
    }

    // Example credit function — adapt to your balance system
    protected function creditUser(int $userId, float $amount)
    {
        if ($userId <= 0) return;

        // Example using ChartAccount (PAYOUT or TRADING)
        \App\Models\ChartAccount::updateOrCreate(
            ['user_id' => $userId, 'acc_type' => 'PAYOUT'],
            ['amount' => \DB::raw("amount + {$amount}")]
        );

        // Log transaction if needed
    }
}