<?php

namespace App\Console\Commands;

use App\Jobs\ProcessBlockchainWithdrawal;
use App\Models\withdrawals;
use Illuminate\Console\Command;

class ProcessQueuedBlockchainWithdrawals extends Command
{
    protected $signature = 'blockchain:process-withdrawals {--limit=25}';
    protected $description = 'Dispatch queued/direct USDT TRC20 withdrawals to the queue worker';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');

        $items = withdrawals::where('status', withdrawals::STATUS_PROCESSING)
            ->where('method', withdrawals::METHOD_CRYPTO)
            ->where('currency', 'USDT')
            ->where('network', 'TRC-20')
            ->whereNull('blockchain_tx_hash')
            ->where(function ($q) {
                $q->whereNull('locked_at')->orWhere('locked_at', '<', now()->subMinutes(10));
            })
            ->orderBy('created_at')
            ->limit($limit)
            ->get();

        foreach ($items as $withdrawal) {
            ProcessBlockchainWithdrawal::dispatch($withdrawal->id);
            $this->info('Dispatched withdrawal #' . $withdrawal->id);
        }

        $this->info('Done. Dispatched: ' . $items->count());
        return 0;
    }
}
