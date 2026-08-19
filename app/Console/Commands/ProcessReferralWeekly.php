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

        // Self-heal any FOM purchases missing their referral accruals
        // BEFORE the payout run, so legacy purchases are included.
        $fomSynced = \App\Services\FomReferralService::syncMissingFomBonuses();
        $this->info("FOM referral self-heal created {$fomSynced} missing bonus row(s).");

        // FOM Licence Miner weekly payout: weaker-side binary volume × 10%
        // + accrued Direct Sponsors bonuses → CASHOUT (eligible users only).
        $fomPaid = \App\Services\FomReferralService::processWeekly();
        $this->info("FOM weekly referral payout completed for {$fomPaid} user(s).");

        // FOM RANK detection sweep: create pending rank rows for any user
        // who newly qualifies (admin approves → reward to Cashout).
        $rankRows = \App\Services\FomRankService::detectAll();
        $this->info("FOM rank detection found {$rankRows} new pending rank(s).");

        // Weekly LEADERBOARD: regenerate the new week's top-10 board
        // (runs after the FOM payout so this Monday's payouts are counted
        // in LAST week's window; admin pins for the new week survive).
        $lbRows = \App\Models\FomLeaderboardEntry::generateForWeek();
        $this->info("Weekly leaderboard generated with {$lbRows} system entr" . ($lbRows === 1 ? 'y' : 'ies') . '.');

        return 0;
    }
}
