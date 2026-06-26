<?php

namespace App\Console\Commands;

use App\Models\BlockchainAuditLog;
use App\Models\BlockchainDepositAddress;
use App\Models\ChartAccount;
use App\Models\Deposits;
use App\Models\Transaction;
use App\Models\User;
use App\Services\TronBlockchainService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckBlockchainDeposits extends Command
{
    protected $signature = 'blockchain:check-deposits {--once : Run only once instead of loop} {--limit=100}';
    protected $description = 'Poll TronGrid for incoming USDT TRC20 deposits and auto-credit them';

    public function handle(TronBlockchainService $service): int
    {
        $limit = (int) $this->option('limit');
        $credited = 0;

        $this->info('Scanning unique user deposit addresses...');
        $addresses = BlockchainDepositAddress::activeTronUsdt()->orderBy('last_scanned_at')->limit(250)->get();

        foreach ($addresses as $depositAddress) {
            $credited += $this->scanAddress($service, $depositAddress->address, $depositAddress->user, $depositAddress, $limit);
            $depositAddress->last_scanned_at = now();
            $depositAddress->save();
        }

        // Backward-compatible fallback: scan one old shared hot wallet and match pending requests.
        $hotWallet = $service->getHotWalletAddress();
        if ($hotWallet) {
            $this->info("Scanning legacy/shared hot wallet: {$hotWallet}");
            $credited += $this->scanAddress($service, $hotWallet, null, null, $limit);
        }

        $this->info("Done. New deposits credited: {$credited}");
        return 0;
    }

    protected function scanAddress(TronBlockchainService $service, string $address, ?User $knownUser = null, ?BlockchainDepositAddress $addressModel = null, int $limit = 100): int
    {
        $minTimestamp = null;
        if ($addressModel && $addressModel->last_scanned_at) {
            $minTimestamp = $addressModel->last_scanned_at->copy()->subMinutes(10)->timestamp * 1000;
        }

        $transactions = $service->getRecentUsdtTransactions($address, $limit, $minTimestamp);
        $credited = 0;

        foreach ($transactions as $tx) {
            try {
                if ($this->processTransaction($tx, $address, $knownUser, $addressModel)) {
                    $credited++;
                }
            } catch (\Throwable $e) {
                Log::error('Deposit processing failed: ' . $e->getMessage(), [
                    'address' => $address,
                    'tx'      => $tx,
                ]);
                BlockchainAuditLog::record('deposit.process_failed', [
                    'level'   => 'error',
                    'tx_hash' => $tx['transaction_id'] ?? $tx['txID'] ?? null,
                    'address' => $address,
                    'message' => $e->getMessage(),
                    'context' => $tx,
                ]);
            }
        }

        return $credited;
    }

    protected function processTransaction(array $tx, string $watchedAddress, ?User $knownUser = null, ?BlockchainDepositAddress $addressModel = null): bool
    {
        $from = $tx['from'] ?? null;
        $to = $tx['to'] ?? null;
        $txHash = $tx['transaction_id'] ?? $tx['txID'] ?? null;
        $amount = isset($tx['value']) ? (float) $tx['value'] / 1_000_000 : 0.0;
        $timestamp = $tx['block_timestamp'] ?? null;

        if (!$txHash || !$from || !$to || $to !== $watchedAddress || $amount <= 0) {
            return false;
        }

        if (Deposits::where('blockchain_tx_hash', $txHash)->orWhere('transaction_id', $txHash)->exists()) {
            return false;
        }

        return DB::transaction(function () use ($knownUser, $addressModel, $from, $to, $amount, $txHash, $timestamp, $tx) {
            if (Deposits::where('blockchain_tx_hash', $txHash)->orWhere('transaction_id', $txHash)->lockForUpdate()->exists()) {
                return false;
            }

            $deposit = null;
            $user = $knownUser;

            if ($addressModel && $knownUser) {
                $deposit = Deposits::where('status', 'pending')
                    ->where('user_id', $knownUser->id)
                    ->where(function ($q) use ($to) {
                        $q->where('deposit_address', $to)->orWhere('user_wallet_address', $to);
                    })
                    ->whereBetween('amount_deposited', [$amount - 0.000001, $amount + 0.000001])
                    ->lockForUpdate()
                    ->first();
            } else {
                // Legacy shared-wallet matching: user creates a pending deposit with sender wallet and amount.
                $deposit = Deposits::where('status', 'pending')
                    ->where('deposit_method', 'like', '%TRON%')
                    ->where('user_wallet_address', $from)
                    ->whereBetween('amount_deposited', [$amount - 0.000001, $amount + 0.000001])
                    ->lockForUpdate()
                    ->first();
                $user = $deposit ? $deposit->user : null;
            }

            if (!$user) {
                BlockchainAuditLog::record('deposit.unmatched', [
                    'tx_hash'  => $txHash,
                    'address'  => $to,
                    'amount'   => $amount,
                    'currency' => 'USDT',
                    'network'  => 'TRC-20',
                    'message'  => 'Incoming USDT transfer could not be matched to a user.',
                    'context'  => $tx,
                ]);
                return false;
            }

            if ($deposit) {
                $deposit->update([
                    'status'             => 'approved',
                    'transaction_id'     => $deposit->transaction_id ?: $txHash,
                    'blockchain_tx_hash' => $txHash,
                    'deposit_address'    => $to,
                    'user_wallet_address'=> $from,
                    'network'            => 'TRC-20',
                    'currency_type'      => 'USDT',
                    'confirmations'      => 1,
                    'detected_at'        => $timestamp ? date('Y-m-d H:i:s', (int) ($timestamp / 1000)) : now(),
                    'credited_at'        => now(),
                    'comment'            => 'Auto-confirmed on-chain via TronGrid',
                ]);
            } else {
                $deposit = Deposits::create([
                    'user_id'            => $user->id,
                    'amount_deposited'   => $amount,
                    'amount_removed'     => 0,
                    'currency_type'      => 'USDT',
                    'deposit_method'     => 'AUTO_TRON_DIRECT',
                    'transaction_id'     => $txHash,
                    'blockchain_tx_hash' => $txHash,
                    'network'            => 'TRC-20',
                    'user_wallet_address'=> $from,
                    'deposit_address'    => $to,
                    'status'             => 'approved',
                    'confirmations'      => 1,
                    'detected_at'        => $timestamp ? date('Y-m-d H:i:s', (int) ($timestamp / 1000)) : now(),
                    'credited_at'        => now(),
                    'comment'            => 'Auto-detected and credited from unique TRON deposit address',
                ]);
            }

            $this->creditUser($user->id, $amount, $deposit, $txHash, $from, $to);

            if ($addressModel) {
                $addressModel->last_tx_hash = $txHash;
                $addressModel->save();
            }

            BlockchainAuditLog::record('deposit.credited', [
                'user_id'        => $user->id,
                'auditable_type' => Deposits::class,
                'auditable_id'   => $deposit->id,
                'tx_hash'        => $txHash,
                'address'        => $to,
                'amount'         => $amount,
                'currency'       => 'USDT',
                'network'        => 'TRC-20',
                'message'        => 'USDT TRC20 deposit credited automatically.',
                'context'        => ['from' => $from, 'to' => $to],
            ]);

            $this->info("✓ Credited user #{$user->id}: {$amount} USDT, tx {$txHash}");
            return true;
        });
    }

    protected function creditUser(int $userId, float $amount, Deposits $deposit, string $txHash, string $from, string $to): void
    {
        // Deposits are not withdrawable. Credit the user's DEPOSIT balance,
        // while withdrawals continue to use CASHOUT only.
        $existing = (float) ChartAccount::where('user_id', $userId)->where('acc_type', 'DEPOSIT')->sum('amount');
        ChartAccount::updateOrCreate(
            ['user_id' => $userId, 'acc_type' => 'DEPOSIT'],
            ['amount'  => $existing + $amount]
        );

        if (!Transaction::where('transaction_no', $txHash)->exists()) {
            Transaction::create([
                'user_id'             => $userId,
                'transaction_no'      => $txHash,
                'transaction_type'    => 'DEPOSIT',
                'receiver_id'         => 0,
                'transaction_details' => json_encode([
                    'amount'          => $amount,
                    'method'          => 'AUTO_TRON_DIRECT',
                    'currency'        => 'USDT',
                    'network'         => 'TRC-20',
                    'from'            => $from,
                    'to'              => $to,
                    'deposit_id'      => $deposit->id,
                    'date'            => now()->toDateTimeString(),
                    'status'          => 'approved',
                ]),
            ]);
        }
    }
}
