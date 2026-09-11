<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment as Paymodel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderEmail;
use App\Mail\ExpirationEmail;
use App\Models\ChartAccount;


class CheckPackages extends Command
{
    protected $signature   = 'packages:check';
    protected $description = 'Mark expired packages and send renewal/expiration reminders';

    public function handle()
    {
        $today = Carbon::now();

        // All users with an active paid package
        $payments = Paymodel::where('is_expired', false)
                            ->where('status', 1)
                            ->get();

        foreach ($payments as $package) {

            // ── Guard: skip if expiration_date not set ──
            if (!$package->expiration_date) {
                continue;
            }

            // ── §84 Guard: Team Leader / Super Leader activation rows never
            // expire — leader access is tied to the activation code, not a
            // timed package (mirrors the dashboard's own rule). Without this,
            // the 200-day expiration_date on the leader Payment row would
            // downgrade has_paid_package to 'no' and destroy leader status.
            if ($package->isLeaderPayment()) {
                continue;
            }

            // ── FC VIP packages NEVER expire (lifetime membership). They
            // carry expiration_date = null and must not be marked expired
            // by the cron even if some legacy row incorrectly has a date.
            if (strtoupper(trim((string) $package->category)) === 'FC'
                || (isset($package->payable_type) && $package->payable_type === \App\Models\FCpackage::class)) {
                $package->is_expired = false;
                if (!empty($package->expiration_date)) {
                    // Self-heal legacy rows that were given an expiration.
                    $package->expiration_date = null;
                    $package->duration = null;
                    $package->save();
                }
                continue;
            }

            $expiry    = Carbon::parse($package->expiration_date);
            $createdAt = Carbon::parse($package->created_at);
            $daysPassed = (int) $createdAt->diffInDays($today);

            // ── 1. Mark expired if expiration_date has passed ──
            if ($today->gte($expiry)) {
                $package->is_expired = true;
                $package->save();

                $isFomPackage = $package->isFom();

                // Update the user's package status
                $user = User::find($package->user);
                if ($user) {
                    // Only downgrade has_paid_package if the user has NO other
                    // active package left. A user can hold a FOM licence and a
                    // UVP package at once — expiring one must not clobber the
                    // other's dashboard access.
                    $otherActive = Paymodel::where('user', $user->id)
                        ->where('id', '!=', $package->id)
                        ->where('is_expired', false)
                        ->where('status', 1)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    if ($otherActive) {
                        if (strtoupper((string) $user->has_paid_package) === strtoupper((string) $package->package)) {
                            $user->has_paid_package = $otherActive->package;
                            $user->save();
                        }
                    } else {
                        $user->has_paid_package = 'no';
                        $user->save();
                    }

                    // ── Auto-transfer remaining UVP LOCKED_TOKEN → AVAILABLE_TOKEN on UVP expiry ──
                    // UVP locks tokens for the package duration (100 days) and releases them
                    // in a single shot at expiry. FC VIP credits LOCKED_TOKEN too, but releases
                    // them in 12 equal monthly installments via `tokens:release-fcp`
                    // (FcpTokenService), so FC-owned balances must NOT be swept here. FOM
                    // packages use ESCROW_TOKEN with their own installments; leader activation
                    // rows are not packages at all.
                    $hasOtherActiveUvp = Paymodel::where('user', $user->id)
                        ->where('id', '!=', $package->id)
                        ->excludeFom()
                        ->excludeLeader()
                        ->excludeFc()
                        ->where(function ($q) {
                            $q->where('category', 'VENTURE')
                              ->orWhere('category', 'UVP')
                              ->orWhere('payable_type', \App\Models\Adventures::class);
                        })
                        ->where('is_expired', false)
                        ->where('status', 1)
                        ->exists();

                    $isUvpPackage = !$isFomPackage
                        && !$package->isFc()
                        && !$package->isLeaderPayment();

                    if ($isUvpPackage && !$hasOtherActiveUvp) {
                        $lockedBalance = $user->ChartAccount()->where('acc_type', 'LOCKED_TOKEN')->sum('amount');
                        if ($lockedBalance > 0) {
                            $user->ChartAccount()->where('acc_type', 'LOCKED_TOKEN')
                                 ->update(['amount' => 0]);

                            $existingAvailable = $user->ChartAccount()->where('acc_type', 'AVAILABLE_TOKEN')->sum('amount');
                            ChartAccount::updateOrCreate(
                                ['user_id' => $user->id, 'acc_type' => 'AVAILABLE_TOKEN'],
                                ['amount'  => $existingAvailable + $lockedBalance]
                            );
                            $this->info("User {$user->id}: {$lockedBalance} LOCKED_TOKEN → AVAILABLE_TOKEN (package expired).");
                        }
                    }
                }

                // Send expiration email (wrapped so one bad address can't crash the loop)
                try {
                    if ($user) {
                        Mail::to($user->email)->send(new ExpirationEmail($package));
                    }
                } catch (\Exception $e) {
                    $this->warn("Failed to send expiration email for payment {$package->id}: " . $e->getMessage());
                }

                $this->info("Package {$package->id} (user {$package->user}) marked expired.");
                continue; // nothing more to do for this package
            }

            // ── 2. Send renewal reminder emails at day 27, 57, 87 ──
            // FOM Licence Miner packages do not follow the UVP renewal cycle
            // (their tokens release via escrow installments), so they must
            // never receive UVP renewal reminders.
            if ($package->isFom()) {
                continue;
            }

            $reminderDays = [27, 57, 87];

            if (in_array($daysPassed, $reminderDays)) {
                $user = User::find($package->user);
                try {
                    if ($user) {
                        Mail::to($user->email)->send(new ReminderEmail($package));
                        $this->info("Renewal reminder sent to user {$package->user} (day {$daysPassed}).");
                    }
                } catch (\Exception $e) {
                    $this->warn("Failed to send reminder email for payment {$package->id}: " . $e->getMessage());
                }
            }
        }

        $this->info('packages:check completed.');
        return 0;
    }
}
