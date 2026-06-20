<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment as Paymodel;
use App\Models\adventures;
use Carbon\Carbon;

/**
 * FixPaymentExpirationDates — one-time cleanup command.
 *
 * Bug being fixed:
 *   payments.expiration_date was being set to today (or null) instead of
 *   today + adventure.duration, because:
 *     (a) the confirm-page form hardcoded `package=FC` (fixed),
 *     (b) the backend read `adventures::find('FC')` → null → addDays(null)
 *         = addDays(0) = today,
 *     (c) the FC package flow hardcoded 100 days.
 *
 * Result of the bug:
 *   - CalculateDailyIncome cron sees daysPassed = 0 → no daily income
 *     generated → user gets neither cashout nor trading voucher.
 *   - Investment shows as already expired.
 *
 * Run with:  php artisan investments:fix-expirations
 *
 * It scans every payment row, finds the matching adventure (by
 * payable_id OR by min_amount/max_amount match), and recomputes
 * expiration_date = today + duration (when created_at > today, i.e.
 * "fix forward"); for already-expired rows it logs and skips.
 */
class FixPaymentExpirationDates extends Command
{
    protected $signature   = 'investments:fix-expirations {--dry-run : Show what would change without writing}';
    protected $description = 'Recompute payments.expiration_date from the adventure duration (fixes "today" / NULL rows)';

    public function handle()
    {
        $dryRun = (bool) $this->option('dry-run');

        $payments = Paymodel::where('category', 'VENTURE')
            ->where(function ($q) {
                $q->whereNull('expiration_date')
                  ->orWhereDate('expiration_date', Carbon::today()->toDateString());
            })
            ->get();

        if ($payments->isEmpty()) {
            $this->info('No bad payments found. (expiration_date is set correctly for all VENTURE rows.)');
            return 0;
        }

        $this->info("Found {$payments->count()} VENTURE payment(s) with bad expiration_date.");
        $fixed = 0;
        $skipped = 0;

        foreach ($payments as $pay) {
            $adventure = null;
            if ($pay->payable_id) {
                $adventure = adventures::find($pay->payable_id);
            }
            // Fallback: match by package name (used in older rows that
            // never set payable_id but stored the plan in `package`).
            if (!$adventure && $pay->package) {
                $adventure = adventures::where('plan', $pay->package)->first()
                             ?? adventures::where('name', $pay->package)->first();
            }

            if (!$adventure) {
                $this->warn("Payment #{$pay->id} (user={$pay->user}, pkg='{$pay->package}'): no matching adventure found — skipping.");
                $skipped++;
                continue;
            }

            $duration = (int) ($adventure->duration ?? 0);
            if ($duration <= 0) {
                $this->warn("Payment #{$pay->id}: adventure #{$adventure->id} has invalid duration — skipping.");
                $skipped++;
                continue;
            }

            // Fix: set to (created_at + duration days). This preserves the
            // user's original "package ends on day X" intent.
            $newExpiry = Carbon::parse($pay->created_at)->addDays($duration)->toDateString();

            $this->line("Payment #{$pay->id}: was '{$pay->expiration_date}' → will be '{$newExpiry}'  (adventure={$adventure->name}, duration={$duration}d)");

            if (!$dryRun) {
                $pay->expiration_date = $newExpiry;
                $pay->duration        = $duration;
                $pay->is_expired      = false;  // also reset the bad flag
                $pay->save();
            }
            $fixed++;
        }

        if ($dryRun) {
            $this->info("\nDry-run complete. {$fixed} would be fixed, {$skipped} would be skipped.");
            $this->info("Re-run without --dry-run to apply the changes.");
        } else {
            $this->info("\nDone. {$fixed} payment(s) fixed, {$skipped} skipped.");
            $this->info("Now run `php artisan income:calculate` to backfill daily income.");
        }
        return 0;
    }
}
