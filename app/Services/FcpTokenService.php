<?php

namespace App\Services;

use App\Models\ChartAccount;
use App\Models\FCpackage;
use App\Models\FcpTokenRelease;
use App\Models\Payment as Paymodel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Credit locked tokens when an FC VIP package is purchased, and release
 * them monthly over 12 equal installments into AVAILABLE_TOKEN.
 *
 * Per spec: "Upon buying the FC package the user will also get tokens
 * equivalent to the token package (default_token). Tokens go into locked
 * tokens for 12 months and are released to available tokens every month
 * (total divided into 12)."
 *
 * We share the same LOCKED_TOKEN / AVAILABLE_TOKEN buckets as UVP so the
 * user sees a single locked balance on the dashboard, but we track FC
 * releases independently via the fcp_token_releases table so each FC
 * purchase's 12-month schedule doesn't interfere with UVP's 100-day
 * single-shot release at expiry.
 */
class FcpTokenService
{
    public const LOCK_MONTHS = 12;

    /**
     * Called once when an FC VIP payment is confirmed (deposit flow or
     * direct-crypto flow). Creates the release schedule and adds the total
     * tokens to the user's LOCKED_TOKEN ChartAccount bucket.
     */
    public static function onFcPurchased(User $user, FCpackage $package, Paymodel $payment): void
    {
        $total = (float) $package->default_token;
        if ($total <= 0) {
            return;
        }

        $months       = self::LOCK_MONTHS;
        $monthly      = round($total / $months, 4);
        $start        = Carbon::now()->startOfDay();
        $nextRelease  = (clone $start)->addMonthNoOverflow();
        $end          = (clone $start)->addMonthsNoOverflow($months);

        DB::transaction(function () use ($user, $package, $payment, $total, $months, $monthly, $start, $nextRelease, $end) {
            FcpTokenRelease::create([
                'user_id'           => $user->id,
                'payment_id'        => $payment->id,
                'package_id'        => $package->id,
                'total_tokens'      => $total,
                'monthly_amount'    => $monthly,
                'total_months'      => $months,
                'months_released'   => 0,
                'released_tokens'   => 0,
                'start_date'        => $start->toDateString(),
                'next_release_date' => $nextRelease->toDateString(),
                'end_date'          => $end->toDateString(),
                'status'            => 'active',
            ]);

            $existing = (float) ChartAccount::where('user_id', $user->id)
                ->where('acc_type', 'LOCKED_TOKEN')
                ->sum('amount');
            ChartAccount::updateOrCreate(
                ['user_id' => $user->id, 'acc_type' => 'LOCKED_TOKEN'],
                ['amount'  => round($existing + $total, 4)]
            );
        });
    }

    /**
     * Cron entrypoint (run daily from ReleaseFcpTokens or CheckPackages).
     * Releases any monthly FC token installments whose next_release_date
     * is today or earlier, up to 12 installments per schedule.
     */
    public static function releaseDueInstallments(\DateTimeInterface $asOf = null): int
    {
        $asOf = $asOf ? Carbon::parse($asOf)->startOfDay() : Carbon::now()->startOfDay();
        $released = 0;

        $schedules = FcpTokenRelease::where('status', 'active')
            ->whereNotNull('next_release_date')
            ->where('next_release_date', '<=', $asOf->toDateString())
            ->lockForUpdate()
            ->get();

        foreach ($schedules as $sch) {
            try {
                DB::transaction(function () use ($sch, $asOf, &$released) {
                    // Re-read under lock.
                    $s = FcpTokenRelease::whereKey($sch->id)->lockForUpdate()->first();
                    if (!$s || $s->status !== 'active') {
                        return;
                    }

                    $amountPer = (float) $s->monthly_amount;
                    $total     = (float) $s->total_tokens;
                    $done      = (int)   $s->months_released;

                    if ($amountPer <= 0 || $done >= (int) $s->total_months) {
                        $s->status = 'completed';
                        $s->save();
                        return;
                    }

                    // Release how many? Could be catching up if cron was offline.
                    $nextDate  = Carbon::parse($s->next_release_date);
                    $batches   = 0;
                    $toRelease = 0;
                    while ($nextDate->lte($asOf) && $done + $batches < (int) $s->total_months) {
                        $batches++;
                        $toRelease += $amountPer;
                        $nextDate->addMonthNoOverflow();
                    }

                    if ($batches === 0) {
                        return;
                    }

                    // Final batch gets the remainder to avoid rounding drift.
                    $newDone = $done + $batches;
                    if ($newDone >= (int) $s->total_months) {
                        $toRelease = round($total - (float) $s->released_tokens, 4);
                        $newDone   = (int) $s->total_months;
                    } else {
                        $toRelease = round($toRelease, 4);
                    }

                    // Move from LOCKED_TOKEN to AVAILABLE_TOKEN on the user's ChartAccount.
                    $user = User::find($s->user_id);
                    if ($user) {
                        $lockedBalance = (float) ChartAccount::where('user_id', $user->id)
                            ->where('acc_type', 'LOCKED_TOKEN')
                            ->sum('amount');
                        $deduct = min($lockedBalance, $toRelease);
                        if ($deduct > 0) {
                            ChartAccount::updateOrCreate(
                                ['user_id' => $user->id, 'acc_type' => 'LOCKED_TOKEN'],
                                ['amount'  => round(max(0, $lockedBalance - $deduct), 4)]
                            );
                            $avail = (float) ChartAccount::where('user_id', $user->id)
                                ->where('acc_type', 'AVAILABLE_TOKEN')
                                ->sum('amount');
                            ChartAccount::updateOrCreate(
                                ['user_id' => $user->id, 'acc_type' => 'AVAILABLE_TOKEN'],
                                ['amount'  => round($avail + $deduct, 4)]
                            );
                        }
                    }

                    $newReleased = round((float) $s->released_tokens + $toRelease, 4);
                    $s->months_released  = $newDone;
                    $s->released_tokens  = $newReleased;
                    $s->next_release_date = $nextDate->toDateString();
                    if ($newDone >= (int) $s->total_months || $newReleased >= $total - 0.0001) {
                        $s->status             = 'completed';
                        $s->next_release_date  = null;
                    }
                    $s->save();

                    $released++;
                });
            } catch (\Throwable $e) {
                Log::warning('FcpTokenService release failed for schedule #' . $sch->id . ': ' . $e->getMessage());
            }
        }

        return $released;
    }
}
