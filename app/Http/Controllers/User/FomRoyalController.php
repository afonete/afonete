<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\FomRoyalTier;
use App\Models\FomRoyalAward;
use App\Services\FomRoyalService;
use Illuminate\Support\Facades\Auth;

class FomRoyalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        FomRoyalTier::ensureTableAndData();
        FomRoyalAward::ensureTable();

        // Self-heal: detect newly-qualified tiers on view.
        FomRoyalService::detectFor($user);

        $tiers = FomRoyalTier::ordered()->get();
        $eligibility = FomRoyalService::eligibility($user);

        $myAwards = FomRoyalAward::where('user_id', $user->id)
            ->orderBy('tier_id')
            ->get()
            ->keyBy('tier_id');

        // Per-tier live progress (only meaningful when eligible)
        $progress = [];
        if ($eligibility['eligible']) {
            foreach ($tiers as $tier) {
                $progress[$tier->id] = FomRoyalService::qualification($user, $tier);
            }
        }

        return view('user.fom-royal', [
            'tiers'       => $tiers,
            'eligibility' => $eligibility,
            'myAwards'    => $myAwards,
            'progress'    => $progress,
        ]);
    }
}
