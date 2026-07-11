<?php

namespace App\Console\Commands;

use App\Models\BlockchainAuditLog;
use App\Models\Deposits;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CancelStalePendingDeposits extends Command
{
    protected $signature = 'deposits:cancel-stale-pending
        {--hours=24 : Cancel pending deposits older than this many hours}
        {--limit=500 : Maximum deposits to cancel per run}
        {--dry-run : Show what would be cancelled without updating records}';

    protected $description = 'Cancel pending deposit records that are older than the configured age';

    public function handle(): int
    {
        $hours = max(1, (int) $this->option('hours'));
        $limit = max(1, (int) $this->option('limit'));
        $dryRun = (bool) $this->option('dry-run');
        $cutoff = now()->subHours($hours);

        $query = Deposits::where('status', 'pending')
            ->where('created_at', '<=', $cutoff)
            ->orderBy('created_at')
            ->limit($limit);

        $deposits = $query->get();

        if ($deposits->isEmpty()) {
            $this->info("No pending deposits older than {$hours} hour(s) found.");
            return 0;
        }

        $this->info('Found ' . $deposits->count() . " pending deposit(s) older than {$hours} hour(s). Cutoff: {$cutoff}");

        if ($dryRun) {
            $this->table(
                ['ID', 'User ID', 'Amount', 'Method', 'Network', 'Created At', 'Reference'],
                $deposits->map(function (Deposits $deposit) {
                    return [
                        $deposit->id,
                        $deposit->user_id,
                        number_format((float) $deposit->amount_deposited, 2),
                        $deposit->deposit_method,
                        $deposit->network,
                        optional($deposit->created_at)->toDateTimeString(),
                        $deposit->transaction_id,
                    ];
                })->all()
            );
            $this->warn('DRY RUN: no deposits were cancelled.');
            return 0;
        }

        $cancelled = 0;

        foreach ($deposits as $deposit) {
            try {
                $comment = trim(($deposit->comment ? $deposit->comment . "\n" : '') .
                    "Auto-cancelled because it remained pending for more than {$hours} hour(s).");

                $deposit->update([
                    'status'  => 'cancelled',
                    'comment' => $comment,
                ]);

                $cancelled++;

                BlockchainAuditLog::record('deposit.auto_cancelled_stale_pending', [
                    'user_id'        => $deposit->user_id,
                    'auditable_type' => Deposits::class,
                    'auditable_id'   => $deposit->id,
                    'tx_hash'        => $deposit->blockchain_tx_hash,
                    'address'        => $deposit->deposit_address ?: $deposit->user_wallet_address,
                    'amount'         => (float) $deposit->amount_deposited,
                    'currency'       => $deposit->currency_type,
                    'network'        => $deposit->network,
                    'request_id'     => $deposit->transaction_id,
                    'message'        => "Pending deposit auto-cancelled after {$hours} hour(s).",
                    'context'        => [
                        'created_at'      => optional($deposit->created_at)->toDateTimeString(),
                        'cancelled_at'    => now()->toDateTimeString(),
                        'payment_context' => $deposit->payment_context,
                        'package_type'    => $deposit->package_type,
                        'package_id'      => $deposit->package_id,
                    ],
                ]);
            } catch (\Throwable $e) {
                Log::error('Failed to auto-cancel stale pending deposit: ' . $e->getMessage(), [
                    'deposit_id' => $deposit->id,
                ]);

                BlockchainAuditLog::record('deposit.auto_cancel_failed', [
                    'level'          => 'error',
                    'user_id'        => $deposit->user_id,
                    'auditable_type' => Deposits::class,
                    'auditable_id'   => $deposit->id,
                    'message'        => $e->getMessage(),
                ]);
            }
        }

        $this->info("Auto-cancelled {$cancelled} stale pending deposit(s).");

        return 0;
    }
}
