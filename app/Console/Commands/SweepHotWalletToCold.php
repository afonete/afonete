<?php

namespace App\Console\Commands;

use App\Models\BlockchainAuditLog;
use App\Models\WithdrawalSetting;
use App\Services\TronBlockchainService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SweepHotWalletToCold extends Command
{
    protected $signature = 'blockchain:sweep-hot-wallet {--dry-run : Only show what would be swept}';
    protected $description = 'Move excess USDT from the hot wallet to cold storage';

    public function handle(TronBlockchainService $tron): int
    {
        $settings = WithdrawalSetting::current();
        $hotWallet = $tron->getHotWalletAddress();
        $coldWallet = $settings->cold_wallet_address ?: env('TRON_COLD_WALLET_ADDRESS');
        $maxBalance = (float) ($settings->hot_wallet_max_balance ?: env('TRON_HOT_WALLET_MAX_USDT', 0));
        $reserve = (float) ($settings->hot_wallet_reserve_balance ?: env('TRON_HOT_WALLET_RESERVE_USDT', 100));

        if (!$hotWallet || !$coldWallet || $maxBalance <= 0) {
            $this->warn('Hot wallet, cold wallet, or max balance is not configured. Nothing to do.');
            return 0;
        }

        $balance = $tron->getUsdtBalance($hotWallet);
        $this->info("Hot wallet USDT balance: {$balance}");

        if ($balance <= $maxBalance) {
            $this->info('Hot wallet is below threshold. Nothing to sweep.');
            return 0;
        }

        $amount = max(0, $balance - $reserve);
        if ($amount <= 0) {
            $this->info('Reserve is greater than/equal to balance. Nothing to sweep.');
            return 0;
        }

        if ($this->option('dry-run')) {
            $this->info("DRY RUN: would sweep {$amount} USDT to {$coldWallet}");
            return 0;
        }

        $requestId = 'sweep-' . (string) Str::uuid();
        $result = $tron->sweepHotWalletToCold($coldWallet, $amount, $requestId);

        BlockchainAuditLog::record('hot_wallet.swept_to_cold', [
            'tx_hash'    => $result['txid'] ?? null,
            'address'    => $coldWallet,
            'amount'     => $amount,
            'currency'   => 'USDT',
            'network'    => 'TRC-20',
            'request_id' => $requestId,
            'message'    => 'Excess hot-wallet funds swept to cold wallet.',
            'context'    => $result['raw'] ?? [],
        ]);

        $this->info('Sweep broadcasted. TX: ' . ($result['txid'] ?? 'unknown'));
        return 0;
    }
}
