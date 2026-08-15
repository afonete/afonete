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

                    // ── Auto-transfer LOCKED_TOKEN → AVAILABLE_TOKEN on package expiry ──
                    // LOCKED_TOKEN belongs to the UVP/FC token cycle. FOM packages
                    // never credit LOCKED_TOKEN (they use ESCROW_TOKEN with their
                    // own installment releases), so a FOM expiry must not dump a
                    // UVP package's still-locked tokens. Also skip while another
                    // active UVP/FC package remains.
                    $hasOtherActiveUvpFc = Paymodel::where('user', $user->id)
                        ->where('id', '!=', $package->id)
                        ->excludeFom()
                        ->where('is_expired', false)
                        ->where('status', 1)
                        ->exists();

                    if (!$isFomPackage && !$hasOtherActiveUvpFc) {
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
