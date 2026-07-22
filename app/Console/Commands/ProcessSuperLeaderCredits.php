<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SuperLeaderCredit;
use App\Models\User;
use App\Models\ChartAccount;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProcessSuperLeaderCredits extends Command
{
    protected $signature = 'credits:process-super-leaders';
    protected $description = 'Process SUPER LEADER credit releases, turnover milestones, and auto-withdrawals';

    public function handle()
    {
        $credits = SuperLeaderCredit::where('status', 'active')->get();

        if ($credits->isEmpty()) {
            $this->info('No active SUPER LEADER credits to process.');
            return 0;
        }

        $processed = 0;

        foreach ($credits as $credit) {
            $user = User::find($credit->user_id);
            if (!$user) continue;

            $this->processTurnoverMilestones($credit, $user);
            $this->processPendingCashout($credit, $user);
            $this->processAutoWithdrawal($credit, $user);

            $processed++;
        }

        $this->info("Processed {$processed} SUPER LEADER credit(s).");
        return 0;
    }

    /**
     * 1. TURNOVER MILESTONES
     * 
     * Calculate the leader's total referral sales turnover.
     * If turnover reaches the next milestone (target * target_percent%),
     * release reward_percent of credit_amount.
     * Released amount goes to pending_cashout with a 5-min timer.
     */
    private function processTurnoverMilestones(SuperLeaderCredit $credit, User $user)
    {
        if ($credit->turnover_target_percent <= 0 || $credit->turnover_reward_percent <= 0) return;
        if ($credit->remaining_credit <= 0) return;

        // Calculate total referral turnover (all levels, active packages)
        $currentTurnover = $this->calculateReferralTurnover($user);

        // Update last_turnover for tracking
        $credit->last_turnover = $currentTurnover;

        // Milestone threshold = sales_turnover_target * (turnover_target_percent / 100)
        $milestoneThreshold = $credit->sales_turnover_target * ($credit->turnover_target_percent / 100);

        if ($milestoneThreshold <= 0) {
            $credit->save();
            return;
        }

        // How many milestones have been reached based on current turnover?
        $milestonesReached = (int) floor($currentTurnover / $milestoneThreshold);

        // New milestones since last check
        $newMilestones = $milestonesReached - $credit->turnover_milestones_reached;

        if ($newMilestones > 0) {
            // Reward per milestone = turnover_reward_percent% of credit_amount
            $rewardPerMilestone = $credit->credit_amount * ($credit->turnover_reward_percent / 100);
            $totalReward = $rewardPerMilestone * $newMilestones;

            // Cap at remaining credit
            $totalReward = min($totalReward, $credit->remaining_credit);

            if ($totalReward > 0) {
                // Deduct from remaining credit
                $credit->remaining_credit -= $totalReward;

                // Add to pending cashout (5-min timer)
                $credit->pending_cashout += $totalReward;
                $credit->pending_cashout_at = Carbon::now();

                Log::info("SUPER LEADER credit milestone: user={$user->user}, " .
                    "turnover={$currentTurnover}, milestones={$milestonesReached}, " .
                    "new={$newMilestones}, reward={$totalReward}");
            }

            $credit->turnover_milestones_reached = $milestonesReached;
        }

        $credit->save();
    }

    /**
     * 2. PENDING CASHOUT → CASHOUT (5-minute timer)
     * 
     * If pending_cashout > 0 and 5 minutes have passed since
     * pending_cashout_at, move it to the user's ChartAccount CASHOUT.
     */
    private function processPendingCashout(SuperLeaderCredit $credit, User $user)
    {
        if ($credit->pending_cashout <= 0 || !$credit->pending_cashout_at) return;

        // 5 minutes must have passed
        if (Carbon::now()->lessThan($credit->pending_cashout_at->copy()->addMinutes(5))) return;

        $amount = $credit->pending_cashout;

        // Add to ChartAccount CASHOUT
        $cashoutAccount = ChartAccount::where('user_id', $user->id)
            ->where('acc_type', 'CASHOUT')
            ->first();

        if ($cashoutAccount) {
            $cashoutAccount->amount = (float)$cashoutAccount->amount + $amount;
            $cashoutAccount->save();
        } else {
            ChartAccount::create([
                'user_id'  => $user->id,
                'acc_type' => 'CASHOUT',
                'amount'   => $amount,
            ]);
        }

        // Update credit record
        $credit->cashout_amount += $amount;
        $credit->pending_cashout = 0;
        $credit->pending_cashout_at = null;
        $credit->save();

        // Sync to legacy credits table
        if ($credit->activation_id) {
            \App\Models\Credit::where('activation_id', $credit->activation_id)
                ->update(['amount' => $credit->remaining_credit]);
        }

        Log::info("SUPER LEADER credit → cashout: user={$user->user}, amount={$amount}");
    }

    /**
     * 3. AUTO WITHDRAWAL (1-hour after activation)
     * 
     * If credit is active, 1 hour has passed since activated_at,
     * and auto_withdrawal hasn't been processed yet:
     * Release auto_withdrawal_percent% of credit_amount to cashout.
     */
    private function processAutoWithdrawal(SuperLeaderCredit $credit, User $user)
    {
        if ($credit->auto_withdrawal_processed) return;
        if ($credit->auto_withdrawal_percent <= 0) return;
        if (!$credit->activated_at) return;
        if ($credit->remaining_credit <= 0) return;

        // 1 hour must have passed since activation
        if (Carbon::now()->lessThan($credit->activated_at->copy()->addHour())) return;

        $autoAmount = $credit->credit_amount * ($credit->auto_withdrawal_percent / 100);
        $autoAmount = min($autoAmount, $credit->remaining_credit);

        if ($autoAmount > 0) {
            // Deduct from remaining credit
            $credit->remaining_credit -= $autoAmount;

            // Add directly to ChartAccount CASHOUT (no 5-min wait for auto-withdrawal)
            $cashoutAccount = ChartAccount::where('user_id', $user->id)
                ->where('acc_type', 'CASHOUT')
                ->first();

            if ($cashoutAccount) {
                $cashoutAccount->amount = (float)$cashoutAccount->amount + $autoAmount;
                $cashoutAccount->save();
            } else {
                ChartAccount::create([
                    'user_id'  => $user->id,
                    'acc_type' => 'CASHOUT',
                    'amount'   => $autoAmount,
                ]);
            }

            $credit->cashout_amount += $autoAmount;
        }

        $credit->auto_withdrawal_processed = true;
        $credit->save();

        // Sync to legacy credits table
        if ($credit->activation_id) {
            \App\Models\Credit::where('activation_id', $credit->activation_id)
                ->update(['amount' => $credit->remaining_credit]);
        }

        Log::info("SUPER LEADER auto-withdrawal: user={$user->user}, amount={$autoAmount}");
    }

    /**
     * Calculate total referral sales turnover for a user.
     * Sums all active package amounts across all referral levels (up to 10).
     */
    private function calculateReferralTurnover(User $user): float
    {
        $totalTurnover = 0.0;
        $visited = [$user->id];
        $currentLevelIds = $user->referrals()->pluck('id')->all();

        // Walk the ENTIRE referral tree — no depth limit.
        // The $visited array prevents infinite loops from circular data.
        while (!empty($currentLevelIds)) {
            $visited = array_merge($visited, $currentLevelIds);

            // Sum of active paid packages at this level
            $totalTurnover += (float) Payment::whereIn('user', $currentLevelIds)
                ->where('status', 1)
                ->where('is_expired', false)
                ->sum('amount');

            // Next level
            $currentLevelIds = User::whereIn('referee_id', $currentLevelIds)
                ->whereNotIn('id', $visited)
                ->pluck('id')->all();
        }

        return $totalTurnover;
    }
}
