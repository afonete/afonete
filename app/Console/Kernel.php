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
    ];
}
