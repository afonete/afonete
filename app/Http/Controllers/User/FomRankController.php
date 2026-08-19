<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\FomRank;
use App\Models\FomUserRank;
use App\Services\FomRankService;
use Illuminate\Support\Facades\Auth;

class FomRankController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        FomRank::ensureTableAndData();
        FomUserRank::ensureTable();

        // Self-heal: detect any rank the user newly qualifies for.
        FomRankService::detectFor($user);

        $allRanks = FomRank::ordered()->get();

        $myRanks = FomUserRank::where('user_id', $user->id)
            ->orderByDesc('rank_level')
            ->get();

        $currentLevel = FomUserRank::highestApprovedLevel($user->id);
        $current = $currentLevel > 0 ? $allRanks->firstWhere('level', $currentLevel) : null;
        $nextRank = $allRanks->firstWhere('level', $currentLevel + 1);

        $nextChecks = [];
        if ($nextRank) {
            $q = FomRankService::qualification($user, $nextRank);
            $nextChecks = $q['checks'];
        }

        return view('user.fom-rank', [
            'allRanks'         => $allRanks,
            'myRanks'          => $myRanks,
            'currentRankLevel' => $currentLevel,
            'currentRankName'  => $current->name ?? 'No Rank',
            'currentGroup'     => $current->group_name ?? null,
            'nextRank'         => $nextRank,
            'nextChecks'       => $nextChecks,
            'myVb'             => FomRankService::lifetimeVb($user->id),
            'myPt'             => FomRankService::personalTurnover($user->id),
            'myTt'             => FomRankService::teamTurnover($user->id),
            'ninety'           => FomRankService::ninetyDayStatus($user->id),
        ]);
    }
}
