<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\ReferralAdminController;
use App\Services\ReferralService;
use Illuminate\Console\Command;

class ProcessReferralWeekly extends Command
{
    protected $signature   = 'referrals:process-weekly';
    protected $description = 'Promote pending referral bonuses to withdrawable + credit Associate Manager weekly bonus. Runs Mondays.';

    public function handle()
    {
        $promoted = ReferralService::promotePendingToWithdrawable();
        $this->info("Promoted {$promoted} referral bonus rows from pending → withdrawable.");

        $amBonus = ReferralAdminController::processAssociateManagerWeekly();
        $this->info("Credited {$amBonus} Associate Manager weekly bonus rows.");

        return 0;
    }
}
