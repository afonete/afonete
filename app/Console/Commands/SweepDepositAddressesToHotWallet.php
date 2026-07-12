<?php

namespace App\Console\Commands;

use App\Models\BlockchainAuditLog;
use App\Models\BlockchainDepositAddress;
use App\Services\TronBlockchainService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SweepDepositAddressesToHotWallet extends Command
{
    protected $signature = 'blockchain:sweep-deposit-addresses
        {--dry-run : Only show what would be swept}
        {--address= : Sweep/check one deposit address or blockchain_deposit_addresses.id only}
        {--to= : Override destination wallet; defaults to TRON_HOT_WALLET_ADDRESS}
        {--min-usdt= : Minimum USDT balance required before sweeping}
        {--min-trx= : Minimum TRX balance required on source address before sweeping}
        {--fund-trx : Auto-fund low-TRX deposit addresses from TRON_FEE_WALLET before sweeping}
        {--no-fund-trx : Disable auto-funding even if TRON_AUTO_FUND_DEPOSIT_ADDRESSES=true}
        {--target-trx= : Target TRX balance to fund each source address up to}
        {--max-trx-per-address= : Maximum TRX to send to one source address in one run}
        {--max-trx-per-run= : Maximum total TRX to spend funding source addresses in one run}
        {--limit=100 : Maximum number of deposit addresses to check}';

    protected $description = 'Sweep USDT from unique user TRON deposit addresses into the hot wallet';

    public function handle(TronBlockchainService $tron): int
    {
        $destination = trim((string) ($this->option('to') ?: $tron->getHotWalletAddress()));
        $dryRun = (bool) $this->option('dry-run');
        $minUsdt = $this->option('min-usdt') !== null
            ? (float) $this->option('min-usdt')
            : (float) (config('services.tron.deposit_sweep_min_usdt') ?: env('TRON_DEPOSIT_SWEEP_MIN_USDT', 10));
        $minTrx = $this->option('min-trx') !== null
            ? (float) $this->option('min-trx')
            : (float) (config('services.tron.deposit_sweep_min_trx') ?: env('TRON_DEPOSIT_SWEEP_MIN_TRX', 20));
        $autoFundConfigured = filter_var(
            config('services.tron.auto_fund_deposit_addresses') ?? env('TRON_AUTO_FUND_DEPOSIT_ADDRESSES', false),
            FILTER_VALIDATE_BOOLEAN
        );
        $autoFundTrx = ((bool) $this->option('fund-trx') || $autoFundConfigured) && !(bool) $this->option('no-fund-trx');
        $targetTrx = $this->option('target-trx') !== null
            ? (float) $this->option('target-trx')
            : (float) (config('services.tron.deposit_sweep_target_trx') ?: env('TRON_DEPOSIT_SWEEP_TARGET_TRX', 25));
        $targetTrx = max($targetTrx, $minTrx);
        $maxTrxPerAddress = $this->option('max-trx-per-address') !== null
            ? (float) $this->option('max-trx-per-address')
            : (float) (config('services.tron.max_trx_fund_per_address') ?: env('TRON_MAX_TRX_FUND_PER_ADDRESS', 30));
        $maxTrxPerRun = $this->option('max-trx-per-run') !== null
            ? (float) $this->option('max-trx-per-run')
            : (float) (config('services.tron.max_trx_fund_per_run') ?: env('TRON_MAX_TRX_FUND_PER_RUN', 200));
        $feeWalletReserve = (float) (config('services.tron.fee_wallet_min_reserve_trx') ?: env('TRON_FEE_WALLET_MIN_RESERVE_TRX', 50));
        $limit = max(1, (int) ($this->option('limit') ?: 100));

        if ($destination === '') {
            $this->error('No destination configured. Set TRON_HOT_WALLET_ADDRESS or pass --to=T...');
            return 1;
        }

        if (!$this->isValidTronAddress($destination)) {
            $this->error('Sweep destination/hot wallet is not a valid TRON address. Check TRON_HOT_WALLET_ADDRESS.');
            return 1;
        }

        if ($destination === $tron->getUsdtContract()) {
            $this->error('TRON_HOT_WALLET_ADDRESS is set to the USDT token contract address. Set it to your company hot wallet address instead.');
            return 1;
        }

        $query = BlockchainDepositAddress::activeTronUsdt()
            ->whereNotNull('encrypted_private_key')
            ->orderBy('id');

        $filter = trim((string) $this->option('address'));
        if ($filter !== '') {
            $query->where(function ($q) use ($filter) {
                $q->where('address', $filter);
                if (ctype_digit($filter)) {
                    $q->orWhere('id', (int) $filter);
                }
            });
        }

        $addresses = $query->limit($limit)->get();

        if ($addresses->isEmpty()) {
            $this->info('No active TRON USDT deposit addresses found.');
            return 0;
        }

        $this->info('Checking ' . $addresses->count() . ' deposit address(es).');
        $this->line('Destination: ' . $destination);
        $this->line('Minimum USDT to sweep: ' . $minUsdt);
        $this->line('Minimum TRX required on source: ' . $minTrx);
        $this->line('Auto-fund TRX: ' . ($autoFundTrx ? 'enabled' : 'disabled'));
        if ($autoFundTrx) {
            $this->line('Fee wallet: ' . ($tron->getFeeWalletAddress() ?: 'not configured'));
            $this->line('Target TRX per source: ' . $targetTrx);
            $this->line('Max TRX fund per address/run: ' . $maxTrxPerAddress . ' / ' . $maxTrxPerRun);
            $this->line('Fee wallet reserve: ' . $feeWalletReserve . ' TRX');
        }
        if ($dryRun) {
            $this->warn('DRY RUN: no transactions will be broadcast.');
        }

        $checked = 0;
        $swept = 0;
        $skippedLowUsdt = 0;
        $needsTrx = 0;
        $failed = 0;
        $trxFunded = 0;
        $trxFundingSkipped = 0;
        $totalSwept = 0.0;
        $totalTrxFunded = 0.0;

        foreach ($addresses as $depositAddress) {
            $checked++;
            $address = $depositAddress->address;
            $this->line('');
            $this->line("#{$depositAddress->id} {$address} user={$depositAddress->user_id}");

            try {
                $usdtBalance = $tron->getUsdtBalance($address);
                $trxBalance = $tron->getTrxBalance($address);
                $this->line("  USDT={$usdtBalance} TRX={$trxBalance}");

                if ($usdtBalance + 0.000001 < $minUsdt) {
                    $skippedLowUsdt++;
                    $this->line('  Skipped: below minimum USDT threshold.');
                    if (!$dryRun) {
                        $this->rememberSweepCheck($depositAddress, $usdtBalance, $trxBalance, 'below_min_usdt');
                    }
                    continue;
                }

                if ($trxBalance + 0.000001 < $minTrx) {
                    $needsTrx++;
                    $message = "Needs TRX fee funding before sweep. Send at least {$minTrx} TRX to {$address}.";
                    $this->warn('  ' . $message);

                    $fundedThisAddress = false;
                    $fundingMessage = null;

                    if ($autoFundTrx) {
                        $privateKey = null;
                        try {
                            $privateKey = $depositAddress->private_key;
                        } catch (\Throwable $e) {
                            $fundingMessage = 'Cannot auto-fund because deposit private key cannot be decrypted: ' . $e->getMessage();
                        }

                        if (!$privateKey) {
                            $fundingMessage = $fundingMessage ?: 'Cannot auto-fund because deposit private key is missing.';
                        } else {
                            $neededToTarget = max(0, $targetTrx - $trxBalance);
                            $remainingRunBudget = max(0, $maxTrxPerRun - $totalTrxFunded);
                            $fundAmount = min($neededToTarget, $maxTrxPerAddress, $remainingRunBudget);
                            $fundAmount = floor($fundAmount * 1_000_000) / 1_000_000;

                            if ($fundAmount <= 0) {
                                $fundingMessage = 'Auto-fund skipped: funding budget exhausted or target already reached.';
                            } elseif (!$tron->getFeeWalletAddress()) {
                                $fundingMessage = 'Auto-fund skipped: TRON_FEE_WALLET_ADDRESS is not configured.';
                            } elseif ($dryRun) {
                                $fundedThisAddress = true;
                                $this->info("  DRY RUN: would auto-fund {$fundAmount} TRX from fee wallet to {$address}; sweep will run after confirmation.");
                            } else {
                                $feeWalletBalance = $tron->getTrxBalance($tron->getFeeWalletAddress());
                                $availableForFunding = $feeWalletBalance - $feeWalletReserve - $totalTrxFunded;

                                if ($availableForFunding + 0.000001 < $fundAmount) {
                                    $fundingMessage = 'Auto-fund skipped: fee wallet balance/reserve is insufficient. Fee wallet balance: '
                                        . $feeWalletBalance . ' TRX, reserve: ' . $feeWalletReserve . ' TRX.';
                                } else {
                                    $fundRequestId = 'deposit-trx-fund-' . $depositAddress->id . '-' . (string) Str::uuid();
                                    $fundResult = $tron->sendTrxFromFeeWallet($address, $fundAmount, $fundRequestId);
                                    $fundTxid = $fundResult['txid'] ?? null;
                                    $fundedThisAddress = true;
                                    $trxFunded++;
                                    $totalTrxFunded += $fundAmount;

                                    $this->info('  Auto-funded ' . $fundAmount . ' TRX. TX: ' . ($fundTxid ?: 'unknown') . '. Sweep will run after confirmation.');

                                    $this->rememberTrxFunding(
                                        $depositAddress,
                                        $usdtBalance,
                                        $trxBalance,
                                        $fundAmount,
                                        $fundTxid,
                                        $address,
                                        $destination
                                    );

                                    BlockchainAuditLog::record('deposit_address.trx_funded', [
                                        'user_id'        => $depositAddress->user_id,
                                        'auditable_type' => BlockchainDepositAddress::class,
                                        'auditable_id'   => $depositAddress->id,
                                        'tx_hash'        => $fundTxid,
                                        'address'        => $address,
                                        'amount'         => $fundAmount,
                                        'currency'       => 'TRX',
                                        'network'        => 'TRC-20',
                                        'request_id'     => $fundRequestId,
                                        'message'        => 'Deposit address auto-funded with TRX for USDT sweep fees.',
                                        'context'        => [
                                            'usdt_balance'        => $usdtBalance,
                                            'trx_balance_before'  => $trxBalance,
                                            'target_trx'          => $targetTrx,
                                            'fee_wallet'          => $tron->getFeeWalletAddress(),
                                            'sweep_destination'   => $destination,
                                            'raw'                 => $fundResult['raw'] ?? [],
                                        ],
                                    ]);
                                }
                            }
                        }
                    }

                    if ($fundedThisAddress) {
                        // Wait for the TRX funding transaction to confirm before sweeping USDT.
                        continue;
                    }

                    if ($fundingMessage) {
                        $trxFundingSkipped++;
                        $this->warn('  ' . $fundingMessage);
                    }

                    if (!$dryRun) {
                        $this->rememberSweepCheck($depositAddress, $usdtBalance, $trxBalance, 'needs_trx', $fundingMessage);
                    }

                    // Record every retry attempt. This makes it visible that the scheduler
                    // is re-checking the address until TRX funding/sweep succeeds.
                    if (!$dryRun) {
                        BlockchainAuditLog::record('deposit_address.sweep_needs_trx', [
                            'level'          => 'warning',
                            'user_id'        => $depositAddress->user_id,
                            'auditable_type' => BlockchainDepositAddress::class,
                            'auditable_id'   => $depositAddress->id,
                            'address'        => $address,
                            'amount'         => $usdtBalance,
                            'currency'       => 'USDT',
                            'network'        => 'TRC-20',
                            'message'        => $fundingMessage ?: $message,
                            'context'        => [
                                'trx_balance' => $trxBalance,
                                'min_trx'     => $minTrx,
                                'destination' => $destination,
                                'auto_fund'   => $autoFundTrx,
                            ],
                        ]);
                    }
                    continue;
                }

                $amount = $this->sweepableUsdtAmount($usdtBalance);
                if ($amount <= 0) {
                    $skippedLowUsdt++;
                    $this->line('  Skipped: sweepable amount rounded to zero.');
                    if (!$dryRun) {
                        $this->rememberSweepCheck($depositAddress, $usdtBalance, $trxBalance, 'zero_sweepable_amount');
                    }
                    continue;
                }

                if ($dryRun) {
                    $this->info("  DRY RUN: would sweep {$amount} USDT to {$destination}");
                    continue;
                }

                $privateKey = $depositAddress->private_key;
                if (!$privateKey) {
                    throw new \RuntimeException('Deposit address private key is missing or cannot be decrypted.');
                }

                $requestId = 'deposit-sweep-' . $depositAddress->id . '-' . (string) Str::uuid();
                $result = $tron->sendUsdtFromPrivateKey(
                    $address,
                    $privateKey,
                    $destination,
                    $amount,
                    $requestId
                );

                $txid = $result['txid'] ?? null;
                $swept++;
                $totalSwept += $amount;
                $this->info('  Swept ' . $amount . ' USDT. TX: ' . ($txid ?: 'unknown'));

                // ── Recycle remaining TRX back to Fee Wallet (Zero Dust Policy) ──
                if (!$dryRun) {
                    try {
                        $feeWallet = $tron->getFeeWalletAddress();
                        if ($feeWallet && $feeWallet !== $address) {
                            $this->info('  Waiting 3 seconds for transaction finality to query remaining TRX...');
                            sleep(3);
                            $updatedTrxBalance = (float) $tron->getTrxBalance($address);
                            
                            // Only sweep if there is a meaningful amount of TRX left (e.g. > 0.1 TRX)
                            if ($updatedTrxBalance > 0.1) {
                                $this->info("  Recycling remaining {$updatedTrxBalance} TRX back to Fee Wallet (Zero Dust)...");
                                $trxSweepRequestId = 'deposit-trx-recycle-' . $depositAddress->id . '-' . (string) Str::uuid();
                                $trxSweepResult = $tron->sendTrxFromPrivateKey(
                                    $address,
                                    $privateKey,
                                    $feeWallet,
                                    $updatedTrxBalance,
                                    $trxSweepRequestId
                                );
                                $trxSweepTxid = $trxSweepResult['txid'] ?? 'unknown';
                                $this->info("  Successfully recycled remaining TRX! TX: {$trxSweepTxid}. Address balance is now 0 TRX.");
                                
                                BlockchainAuditLog::record('deposit_address.trx_recycled', [
                                    'user_id'        => $depositAddress->user_id,
                                    'auditable_type' => BlockchainDepositAddress::class,
                                    'auditable_id'   => $depositAddress->id,
                                    'tx_hash'        => $trxSweepTxid,
                                    'address'        => $address,
                                    'amount'         => $updatedTrxBalance,
                                    'currency'       => 'TRX',
                                    'network'        => 'TRC-20',
                                    'request_id'     => $trxSweepRequestId,
                                    'message'        => 'Remaining TRX recycled back to fee wallet.',
                                    'context'        => [
                                        'fee_wallet' => $feeWallet,
                                        'raw'        => $trxSweepResult['raw'] ?? [],
                                    ],
                                ]);
                            }
                        }
                    } catch (\Throwable $trxEx) {
                        $this->warn('  Warning: Failed to recycle remaining TRX: ' . $trxEx->getMessage());
                    }
                }

                $metadata = $depositAddress->metadata ?: [];
                $metadata['last_sweep_checked_at'] = now()->toDateTimeString();
                $metadata['last_sweep_status'] = 'swept';
                $metadata['last_usdt_balance'] = $usdtBalance;
                $metadata['last_trx_balance'] = $trxBalance;
                $metadata['last_sweep_tx_hash'] = $txid;
                $metadata['last_swept_amount'] = $amount;
                $metadata['last_swept_at'] = now()->toDateTimeString();
                $metadata['last_sweep_destination'] = $destination;
                $depositAddress->metadata = $metadata;
                $depositAddress->save();

                BlockchainAuditLog::record('deposit_address.swept_to_hot_wallet', [
                    'user_id'        => $depositAddress->user_id,
                    'auditable_type' => BlockchainDepositAddress::class,
                    'auditable_id'   => $depositAddress->id,
                    'tx_hash'        => $txid,
                    'address'        => $address,
                    'amount'         => $amount,
                    'currency'       => 'USDT',
                    'network'        => 'TRC-20',
                    'request_id'     => $requestId,
                    'message'        => 'USDT swept from unique deposit address to hot wallet.',
                    'context'        => [
                        'destination' => $destination,
                        'trx_balance' => $trxBalance,
                        'raw'         => $result['raw'] ?? [],
                    ],
                ]);
            } catch (\Throwable $e) {
                $failed++;
                $this->error('  Failed: ' . $e->getMessage());
                Log::error('Deposit address sweep failed: ' . $e->getMessage(), [
                    'deposit_address_id' => $depositAddress->id,
                    'address'            => $address,
                ]);

                if (!$dryRun) {
                    $this->rememberSweepCheck($depositAddress, null, null, 'failed', $e->getMessage());
                }

                if (!$dryRun) {
                    BlockchainAuditLog::record('deposit_address.sweep_failed', [
                        'level'          => 'error',
                        'user_id'        => $depositAddress->user_id,
                        'auditable_type' => BlockchainDepositAddress::class,
                        'auditable_id'   => $depositAddress->id,
                        'address'        => $address,
                        'currency'       => 'USDT',
                        'network'        => 'TRC-20',
                        'message'        => $e->getMessage(),
                        'context'        => [
                            'destination' => $destination,
                        ],
                    ]);
                }
            }
        }

        $this->line('');
        $this->info('Deposit address sweep finished.');
        $this->table(
            ['Checked', 'Swept', 'Total swept', 'Below min USDT', 'Need TRX', 'TRX funded', 'TRX skipped', 'Failed'],
            [[
                $checked,
                $swept,
                number_format($totalSwept, 6) . ' USDT',
                $skippedLowUsdt,
                $needsTrx,
                $trxFunded . ' (' . number_format($totalTrxFunded, 6) . ' TRX)',
                $trxFundingSkipped,
                $failed,
            ]]
        );

        return $failed > 0 ? 1 : 0;
    }

    private function sweepableUsdtAmount(float $balance): float
    {
        // USDT TRC20 uses 6 decimals. Floor to avoid trying to send dust beyond precision.
        return floor($balance * 1_000_000) / 1_000_000;
    }

    private function isValidTronAddress(?string $address): bool
    {
        return is_string($address) && (bool) preg_match('/^T[a-zA-Z0-9]{33}$/', trim($address));
    }

    private function rememberTrxFunding(
        BlockchainDepositAddress $depositAddress,
        float $usdtBalance,
        float $trxBalance,
        float $fundAmount,
        ?string $fundTxid,
        string $sourceAddress,
        string $destination
    ): void {
        try {
            $metadata = $depositAddress->metadata ?: [];
            $metadata['last_sweep_checked_at'] = now()->toDateTimeString();
            $metadata['last_sweep_status'] = 'trx_funded_waiting_sweep';
            $metadata['last_usdt_balance'] = $usdtBalance;
            $metadata['last_trx_balance'] = $trxBalance;
            $metadata['last_trx_funded_at'] = now()->toDateTimeString();
            $metadata['last_trx_funded_amount'] = $fundAmount;
            $metadata['last_trx_fund_tx_hash'] = $fundTxid;
            $metadata['last_trx_fund_source'] = 'fee_wallet';
            $metadata['last_sweep_source_address'] = $sourceAddress;
            $metadata['last_sweep_destination'] = $destination;
            $metadata['trx_funding_attempts'] = (int) ($metadata['trx_funding_attempts'] ?? 0) + 1;
            $metadata['total_trx_funded'] = (float) ($metadata['total_trx_funded'] ?? 0) + $fundAmount;
            $depositAddress->metadata = $metadata;
            $depositAddress->save();
        } catch (\Throwable $e) {
            Log::warning('Could not update deposit address TRX funding metadata: ' . $e->getMessage(), [
                'deposit_address_id' => $depositAddress->id,
            ]);
        }
    }

    private function rememberSweepCheck(
        BlockchainDepositAddress $depositAddress,
        ?float $usdtBalance,
        ?float $trxBalance,
        string $status,
        ?string $message = null
    ): void {
        try {
            $metadata = $depositAddress->metadata ?: [];
            $metadata['last_sweep_checked_at'] = now()->toDateTimeString();
            $metadata['last_sweep_status'] = $status;
            if ($usdtBalance !== null) {
                $metadata['last_usdt_balance'] = $usdtBalance;
            }
            if ($trxBalance !== null) {
                $metadata['last_trx_balance'] = $trxBalance;
            }
            if ($message) {
                $metadata['last_sweep_message'] = $message;
            }
            $depositAddress->metadata = $metadata;
            $depositAddress->save();
        } catch (\Throwable $e) {
            Log::warning('Could not update deposit address sweep metadata: ' . $e->getMessage(), [
                'deposit_address_id' => $depositAddress->id,
            ]);
        }
    }
}
