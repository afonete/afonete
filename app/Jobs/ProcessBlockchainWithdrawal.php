<?php

namespace App\Jobs;

use App\Models\BlockchainAuditLog;
use App\Models\ChartAccount;
use App\Models\withdrawals;
use App\Services\TronBlockchainService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessBlockchainWithdrawal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    protected int $withdrawalId;

    public function __construct(int $withdrawalId)
    {
        $this->withdrawalId = $withdrawalId;
    }

    public function handle(TronBlockchainService $tron): void
    {
        $withdrawal = DB::transaction(function () {
            /** @var withdrawals|null $w */
            $w = withdrawals::where('id', $this->withdrawalId)->lockForUpdate()->first();

            if (!$w) {
                return null;
            }

            if ($w->status === withdrawals::STATUS_COMPLETED && ($w->blockchain_tx_hash || $w->txn_hash)) {
                return null;
            }

            if ($w->status !== withdrawals::STATUS_PROCESSING) {
                BlockchainAuditLog::record('withdrawal.skipped_not_processing', [
                    'user_id'        => $w->user_id,
                    'auditable_type' => withdrawals::class,
                    'auditable_id'   => $w->id,
                    'message'        => 'Withdrawal job skipped because status is not processing.',
                    'context'        => ['status' => $w->status],
                ]);
                return null;
            }

            if ($w->locked_at && $w->locked_at->gt(now()->subMinutes(10))) {
                return null;
            }

            $w->locked_at = now();
            $w->last_attempt_at = now();
            $w->attempts = ((int) $w->attempts) + 1;
            $w->save();

            return $w->fresh();
        });

        if (!$withdrawal) {
            return;
        }

        try {
            if ($withdrawal->method !== withdrawals::METHOD_CRYPTO || strtoupper((string) $withdrawal->network) !== 'TRC-20' || strtoupper((string) $withdrawal->currency) !== 'USDT') {
                throw new \RuntimeException('Only USDT TRC-20 crypto withdrawals can be processed automatically.');
            }

            $hotWallet = $tron->getHotWalletAddress();
            if ($hotWallet) {
                $hotBalance = $tron->getUsdtBalance($hotWallet);
                if ($hotBalance + 0.000001 < (float) $withdrawal->amount) {
                    throw new \RuntimeException('Insufficient hot-wallet USDT balance for automatic payout.');
                }
                
                // ── THE FIX: Validate that the hot wallet has enough TRX for fees (Min 20 TRX) ──
                $hotTrxBalance = $tron->getTrxBalance($hotWallet);
                if ($hotTrxBalance < 20.0) {
                    throw new \RuntimeException('Insufficient hot-wallet TRX balance to pay for network fee (min 20 TRX required). Hot wallet has ' . $hotTrxBalance . ' TRX.');
                }
            }

            $requestId = $withdrawal->signer_request_id ?: $withdrawal->transaction_no;
            $result = $tron->sendUsdt($withdrawal->wallet_address, (float) $withdrawal->amount, $requestId);
            $txid = $result['txid'];

            $withdrawal->update([
                'status'             => withdrawals::STATUS_COMPLETED,
                'txn_hash'           => $txid,
                'blockchain_tx_hash' => $txid,
                'signer_request_id'  => $result['requestId'] ?? $requestId,
                'signer_response'    => $result['raw'] ?? $result,
                'failure_reason'     => null,
                'locked_at'          => null,
                'processed_at'       => now(),
                'admin_note'         => trim(($withdrawal->admin_note ? $withdrawal->admin_note . "\n" : '') . 'Auto-sent on-chain via TRON signer.'),
            ]);

            BlockchainAuditLog::record('withdrawal.broadcasted', [
                'user_id'        => $withdrawal->user_id,
                'auditable_type' => withdrawals::class,
                'auditable_id'   => $withdrawal->id,
                'tx_hash'        => $txid,
                'address'        => $withdrawal->wallet_address,
                'amount'         => $withdrawal->amount,
                'currency'       => $withdrawal->currency,
                'network'        => $withdrawal->network,
                'request_id'     => $result['requestId'] ?? $requestId,
                'message'        => 'USDT TRC-20 withdrawal broadcasted and marked completed.',
                'context'        => $result['raw'] ?? [],
            ]);
        } catch (\Throwable $e) {
            $this->failAndRefund($withdrawal, $e);
        }
    }

    protected function failAndRefund(withdrawals $withdrawal, \Throwable $e): void
    {
        Log::error('Blockchain withdrawal job failed: ' . $e->getMessage(), ['withdrawal_id' => $withdrawal->id]);

        DB::transaction(function () use ($withdrawal, $e) {
            /** @var withdrawals|null $fresh */
            $fresh = withdrawals::where('id', $withdrawal->id)->lockForUpdate()->first();
            if (!$fresh || $fresh->status === withdrawals::STATUS_COMPLETED) {
                return;
            }

            $fresh->update([
                'status'         => withdrawals::STATUS_FAILED,
                'failure_reason' => $e->getMessage(),
                'locked_at'      => null,
            ]);

            $user = $fresh->user;
            if ($user) {
                // Lock the ChartAccount row for update to prevent concurrent double-refunding race conditions
                $account = $user->ChartAccount()->where('acc_type', 'CASHOUT')->lockForUpdate()->first();
                if ($account) {
                    $account->amount = (float) $account->amount + (float) $fresh->amount;
                    $account->save();
                } else {
                    ChartAccount::create([
                        'user_id'  => $user->id,
                        'acc_type' => 'CASHOUT',
                        'amount'   => (float) $fresh->amount
                    ]);
                }
            }

            BlockchainAuditLog::record('withdrawal.failed_refunded', [
                'level'          => 'error',
                'user_id'        => $fresh->user_id,
                'auditable_type' => withdrawals::class,
                'auditable_id'   => $fresh->id,
                'address'        => $fresh->wallet_address,
                'amount'         => $fresh->amount,
                'currency'       => $fresh->currency,
                'network'        => $fresh->network,
                'message'        => $e->getMessage(),
            ]);
        });
    }
}
