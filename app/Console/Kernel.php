<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Mark expired packages and send reminder emails — runs at midnight
        $schedule->command('packages:check')->dailyAt('00:00');

        // Calculate and credit daily income for all active VENTURE packages — runs 5 min after midnight
        // Running slightly after packages:check ensures expired packages are already flagged first
        $schedule->command('income:calculate')->dailyAt('00:05');

        // Auto-detect users who meet rank criteria → create pending applications for admin
        $schedule->command('ranks:check')->dailyAt('00:10');

        // Weekly Monday referral bonus window:
        //   - Promote all pending bonus rows whose week_start has arrived → withdrawable
        //   - Compute and credit Associate Manager weekly 20% bonus
        $schedule->command('referrals:process-weekly')
                 ->weeklyOn(1, '00:15'); // 1 = Monday
    }



    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected $commands = [
        \App\Console\Commands\ClearAllStorage::class,
        \App\Console\Commands\CheckPackages::class,
        \App\Console\Commands\CalculateDailyIncome::class,
        \App\Console\Commands\ProcessReferralWeekly::class,
        \App\Console\Commands\CheckRankEligibility::class,
    ];
}
