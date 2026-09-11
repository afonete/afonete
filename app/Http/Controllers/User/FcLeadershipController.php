<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\FcLeadershipProgress;

class FcLeadershipController extends Controller
{
    /**
     * FC Leadership Progress page — dedicated view for the FC VIP
     * Leadership Bonus (100 VB per direct referral + 6 milestone tiers).
     */
    public function index()
    {
        $user = Auth::user();

        // Self-heal progress counters from payment history before showing.
        try {
            \App\Services\FcLeadershipService::reconcileProgressFromPayments();
        } catch (\Throwable $e) {
            \Log::warning('FC leadership reconcile failed on page view: ' . $e->getMessage());
        }

        $progress = FcLeadershipProgress::forUser($user->id);
        $tiers    = FcLeadershipProgress::TIERS;

        // Determine the NEXT unearned tier for progress display.
        $nextTierIdx = null;
        foreach ($tiers as $i => $t) {
            if ((int) $progress->highest_tier_paid < $i) {
                $nextTierIdx = $i;
                break;
            }
        }

        // Already completed all tiers.
        $allDone = ($nextTierIdx === null);

        return view('user.fc-leadership', compact('progress', 'tiers', 'nextTierIdx', 'allDone'));
    }
}
