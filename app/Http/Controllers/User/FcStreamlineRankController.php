<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\FcStreamlineRank;
use App\Services\FcStreamlineRankService;

class FcStreamlineRankController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Opportunistic evaluation (cron hourly is the backstop).
        try {
            FcStreamlineRankService::evaluate($user);
        } catch (\Throwable $e) {
            \Log::warning('FC streamline rank evaluate failed on page view: ' . $e->getMessage());
        }

        $highestCompleted = FcStreamlineRank::highestCompletedPin($user->id);
        $activeChallenge  = FcStreamlineRank::currentActiveForUser($user->id);
        $pendingReview    = FcStreamlineRank::where('user_id', $user->id)
            ->where('status', FcStreamlineRank::STATUS_PENDING_ADMIN)
            ->first();

        $directFc = FcStreamlineRankService::countDirectFcReferrals($user->id);
        $teamFc   = FcStreamlineRankService::countTeamClubFc($user->id);
        $pinCounts= FcStreamlineRankService::countDownlinePins($user->id);

        $ranksData = [];
        foreach (FcStreamlineRank::RANKS as $level => $def) {
            $progressRow = FcStreamlineRank::forUser($user->id, $level);

            $activationDirectMet = $directFc >= (int) $def['activation_direct_fc'];
            $activationPinsMet   = true;
            $havePins = 0;
            if ((int) $def['activation_pins'] > 0 && $def['activation_pin_type']) {
                $havePins = (int) ($pinCounts[$def['activation_pin_type']] ?? 0);
                $activationPinsMet = $havePins >= (int) $def['activation_pins'];
            }

            $ownMet  = $directFc >= (int) $def['own_fc_direct'];
            $clubMet = $teamFc   >= (int) $def['team_club'];

            $status = $progressRow->status; // locked | active | pending_admin | completed | expired
            $isCurrent = false;
            $daysRemaining = null;
            $deadline = null;

            if ($activeChallenge && $activeChallenge->rank_level === $level) {
                $isCurrent = true;
                $daysRemaining = $activeChallenge->daysRemaining();
                $deadline = $activeChallenge->deadline_at;
            } elseif ($pendingReview && $pendingReview->rank_level === $level) {
                $isCurrent = true;
                $status = 'pending_admin';
            } elseif ((int) $highestCompleted >= $level) {
                $status = 'completed';
            }

            $priorExpired = FcStreamlineRank::where('user_id', $user->id)
                ->where('status', FcStreamlineRank::STATUS_EXPIRED)
                ->where('rank_level', '<', $level)
                ->exists();

            $ranksData[] = [
                'level'              => $level,
                'pin'                => $def['pin'],
                'title'              => $def['title'],
                'activation_direct'  => $def['activation_direct_fc'],
                'activation_pins'    => $def['activation_pins'],
                'activation_pin_type'=> $def['activation_pin_type'],
                'own_fc_target'      => $def['own_fc_direct'],
                'team_club_target'   => $def['team_club'],
                'reward_usd'         => $def['reward_usd'],
                'reward_tokens'      => $def['reward_tokens'],
                'status'             => $status,
                'is_current'         => $isCurrent,
                'days_remaining'     => $daysRemaining,
                'deadline'           => $deadline,
                'direct_now'         => $directFc,
                'team_now'           => $teamFc,
                'pins_now'           => $havePins,
                'activation_direct_met' => $activationDirectMet,
                'activation_pins_met'   => $activationPinsMet,
                'own_met'            => $ownMet,
                'club_met'           => $clubMet,
                'locked_out'         => $priorExpired,
            ];
        }

        return view('user.fc-streamline-ranks', compact(
            'ranksData',
            'directFc',
            'teamFc',
            'pinCounts',
            'highestCompleted',
            'activeChallenge',
            'pendingReview'
        ));
    }
}
