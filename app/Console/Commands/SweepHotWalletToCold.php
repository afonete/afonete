<?php

namespace App\Console\Commands;

use App\Models\BlockchainAuditLog;
use App\Models\WithdrawalSetting;
use App\Services\TronBlockchainService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SweepHotWalletToCold extends Command
{
    protected $signature = 'blockchain:sweep-hot-wallet
        {--dry-run : Only show what would be swept}
        {--amount= : Sweep this exact USDT amount instead of using max/reserve threshold}
        {--min-trx= : Minimum TRX required in the hot wallet before sweeping USDT}';

    protected $description = 'Move excess USDT from the hot wallet to cold storage';

    public function handle(TronBlockchainService $tron): int
    {
        $settings = WithdrawalSetting::current();
        $hotWallet = trim((string) $tron->getHotWalletAddress());
        $coldWallet = trim((string) ($settings->cold_wallet_address ?: env('TRON_COLD_WALLET_ADDRESS')));
        $maxBalance = (float) ($settings->hot_wallet_max_balance ?: env('TRON_HOT_WALLET_MAX_USDT', 0));
        $reserve = (float) ($settings->hot_wallet_reserve_balance ?: env('TRON_HOT_WALLET_RESERVE_USDT', 100));
        $minTrx = $this->option('min-trx') !== null
            ? (float) $this->option('min-trx')
            : (float) (config('services.tron.hot_wallet_min_trx_for_sweep') ?: env('TRON_HOT_WALLET_MIN_TRX_FOR_SWEEP', 20));
        $dryRun = (bool) $this->option('dry-run');

        if (!$this->isValidTronAddress($hotWallet)) {
            $this->error('TRON_HOT_WALLET_ADDRESS is missing or invalid.');
            return 1;
        }

        if (!$this->isValidTronAddress($coldWallet)) {
            $this->error('Cold wallet is missing or invalid. Configure it in admin settings or TRON_COLD_WALLET_ADDRESS.');
            return 1;
        }

        if ($coldWallet === $hotWallet) {
            $this->error('Cold wallet must not be the same as the hot wallet.');
            return 1;
        }

        if ($coldWallet === $tron->getUsdtContract()) {
            $this->error('Cold wallet must be a wallet address, not the USDT token contract address.');
            return 1;
        }

        if ($maxBalance <= 0 && $this->option('amount') === null) {
            $this->warn('Hot wallet max balance is not configured. Set it in admin settings or TRON_HOT_WALLET_MAX_USDT, or pass --amount.');
            return 0;
        }

        if ($reserve < 0) {
            $this->error('Hot wallet reserve cannot be negative.');
            return 1;
        }

        $balance = $tron->getUsdtBalance($hotWallet);
        $trxBalance = $tron->getTrxBalance($hotWallet);

        $this->info("Hot wallet USDT balance: {$balance}");
        $this->info("Hot wallet TRX balance: {$trxBalance}");
        $this->line("Cold wallet: {$coldWallet}");
        $this->line("Reserve USDT: {$reserve}");
        $this->line("Minimum hot-wallet TRX required: {$minTrx}");

        if ($trxBalance + 0.000001 < $minTrx) {
            $message = "Hot wallet has insufficient TRX for TRC20 sweep fees. Required: {$minTrx}, current: {$trxBalance}.";
            $this->error($message);

            if (!$dryRun) {
                BlockchainAuditLog::record('hot_wallet.sweep_needs_trx', [
                    'level'    => 'warning',
                    'address'  => $hotWallet,
                    'amount'   => $trxBalance,
                    'currency' => 'TRX',
                    'network'  => 'TRC-20',
                    'message'  => $message,
                    'context'  => [
                        'min_trx'     => $minTrx,
                        'cold_wallet' => $coldWallet,
                    ],
                ]);
            }

            return 1;
        }

        $manualAmount = $this->option('amount');
        if ($manualAmount !== null && $manualAmount !== '') {
            $amount = (float) $manualAmount;
            if ($amount <= 0) {
                $this->error('--amount must be greater than 0.');
                return 1;
            }
        } else {
            if ($balance <= $maxBalance) {
                $this->info('Hot wallet is below threshold. Nothing to sweep.');
                return 0;
            }

            $amount = $balance - $reserve;
        }

        $amount = $this->sweepableUsdtAmount($amount);
        if ($amount <= 0) {
            $this->info('Sweep amount is zero after applying reserve/precision. Nothing to do.');
            return 0;
        }

        if ($amount + 0.000001 > $balance) {
            $this->error('Sweep amount exceeds hot wallet USDT balance.');
            return 1;
        }

        if (($balance - $amount) + 0.000001 < $reserve) {
            $this->error('Sweep would reduce hot wallet below the configured reserve. Lower --amount or reduce reserve.');
            return 1;
        }

        if ($dryRun) {
            $this->info("DRY RUN: would sweep {$amount} USDT to {$coldWallet}");
            return 0;
        }

        $requestId = 'sweep-' . (string) Str::uuid();

        try {
            $result = $tron->sweepHotWalletToCold($coldWallet, $amount, $requestId);

            BlockchainAuditLog::record('hot_wallet.swept_to_cold', [
                'tx_hash'    => $result['txid'] ?? null,
                'address'    => $coldWallet,
                'amount'     => $amount,
                'currency'   => 'USDT',
                'network'    => 'TRC-20',
                'request_id' => $requestId,
                'message'    => 'Excess hot-wallet funds swept to cold wallet.',
                'context'    => [
                    'hot_wallet'   => $hotWallet,
                    'cold_wallet'  => $coldWallet,
                    'reserve'      => $reserve,
                    'balance'      => $balance,
                    'trx_balance'  => $trxBalance,
                    'raw'          => $result['raw'] ?? [],
                ],
            ]);

            $this->info('Sweep broadcasted. TX: ' . ($result['txid'] ?? 'unknown'));
            return 0;
        } catch (\Throwable $e) {
            Log::error('Hot wallet cold sweep failed: ' . $e->getMessage(), [
                'hot_wallet'  => $hotWallet,
                'cold_wallet' => $coldWallet,
                'amount'      => $amount,
            ]);

            BlockchainAuditLog::record('hot_wallet.sweep_failed', [
                'level'    => 'error',
                'address'  => $coldWallet,
                'amount'   => $amount,
                'currency' => 'USDT',
                'network'  => 'TRC-20',
                'request_id' => $requestId,
                'message'  => $e->getMessage(),
                'context'  => [
                    'hot_wallet'  => $hotWallet,
                    'cold_wallet' => $coldWallet,
                    'reserve'     => $reserve,
                    'balance'     => $balance,
                    'trx_balance' => $trxBalance,
                ],
            ]);

            $this->error('Sweep failed: ' . $e->getMessage());
            return 1;
        }
    }

    private function isValidTronAddress(?string $address): bool
    {
        return is_string($address) && (bool) preg_match('/^T[a-zA-Z0-9]{33}$/', trim($address));
    }

    private function sweepableUsdtAmount(float $amount): float
    {
        // USDT TRC20 uses 6 decimals. Floor to avoid sending beyond precision.
        return floor($amount * 1_000_000) / 1_000_000;
    }
}
