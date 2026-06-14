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

                // Update the user's package status
                $user = User::find($package->user);
                if ($user) {
                    $user->has_paid_package = 'no';
                    $user->save();

                    // ── Auto-transfer LOCKED_TOKEN → AVAILABLE_TOKEN on package expiry ──
                    // When the package duration ends the locked tokens are released into
                    // Available Token. From there, the user can manually transfer to Free Token
                    // and then withdraw/swap/transfer to another user.
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
