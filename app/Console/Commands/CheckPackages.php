<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Payment as Paymodel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderEmail;
use App\Mail\ExpirationEmail;
use App\Models\User;

class CheckPackages extends Command
{
    protected $signature = 'packages:check';
    protected $description = 'Check packages for reminders and expiration';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = Carbon::now();
        $users =  User::where('utype', 'USR')
                     -> where('has_paid_package','yes')
                     ->where('has_free_package','no')->get();



        foreach ($users as $user) {
            $package = Paymodel::where('user',$user->id)
                            ->where('is_expired', false)
                            // ->where('')
                            ->where('expiration_date', '>', $today)
                            ->first();

            $isInfinityPackage = is_null($package->expiration_date);
            $created_at = Carbon::parse($package->created_at);
            $elapsedDays = $created_at->diffInDays($today);
            $reminderDates = [27, 57, 87];

            if(in_array($package->package, ['uvp1', 'uvp2', 'uvp3', 'uvp4'] )){
                $reminderDates = [27, 57, 87];
            }else if(in_array($package->package, ['uvp5', 'uvp6', 'uvp7', 'uvp8'] )){
                $reminderDates = [27, 57, 87, 117, 147, 177];
            }
           else if(in_array($package->package, ['uvp9', 'uvp10', 'uvp11', 'uvp12'] )){
                $reminderDates = [
                    27, 57, 87, 117, 147, 177, 207, 237, 267, 297, 327, 357, 387, 417, 447, 477, 507, 537, 567, 597
                ];

            }

            if (in_array($elapsedDays, $reminderDates)) {

                Mail::to($user->email)->send(new ReminderEmail($package));
            }
            else{
                echo "failed";
            }

            if ($elapsedDays >= 100 && in_array($package->package, ['uvp1', 'uvp2', 'uvp3', 'uvp4'] )) {
                $package->is_expired = true;
                $package->save();
                Mail::to($package->user->email)->send(new ExpirationEmail($package));
            }
            if ($elapsedDays >= 200 && in_array($package->package, ['uvp5', 'uvp6', 'uvp7', 'uvp8'] )) {
                $package->is_expired = true;
                $package->save();
                Mail::to($package->user->email)->send(new ExpirationEmail($package));
            }
            if ($elapsedDays >= 600 && in_array($package->package, ['uvp9', 'uvp10', 'uvp11', 'uvp12'] )) {
                $package->is_expired = true;
                $package->save();
                Mail::to($package->user->email)->send(new ExpirationEmail($package));
            }
        }

        return 0;
    }
}

?>
