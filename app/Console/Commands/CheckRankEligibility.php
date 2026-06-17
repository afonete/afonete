<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\ReferralAdminController;
use Illuminate\Console\Command;

class CheckRankEligibility extends Command
{
    protected $signature   = 'ranks:check';
    protected $description = 'Auto-detect users who meet rank criteria and create pending rank applications for admin review.';

    public function handle()
    {
        $created = ReferralAdminController::autoCreateApplications();
        $this->info("Created {$created} new rank applications for admin review.");
        return 0;
    }
}
