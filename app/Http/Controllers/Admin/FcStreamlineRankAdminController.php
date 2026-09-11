<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FcStreamlineRank;
use App\Services\FcStreamlineRankService;

class FcStreamlineRankAdminController extends Controller
{
    /**
     * Admin dashboard for FC Streamline Ranks.
     * Tabs: pending verification | active challenges | completed | expired.
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'pending');

        $base = FcStreamlineRank::with('user');

        switch ($tab) {
            case 'active':
                $rows = (clone $base)
                    ->where('status', FcStreamlineRank::STATUS_ACTIVE)
                    ->orderBy('deadline_at', 'asc')
                    ->paginate(25);
                break;
            case 'completed':
                $rows = (clone $base)
                    ->where('status', FcStreamlineRank::STATUS_COMPLETED)
                    ->orderByDesc('verified_at')
                    ->paginate(25);
                break;
            case 'expired':
                $rows = (clone $base)
                    ->where('status', FcStreamlineRank::STATUS_EXPIRED)
                    ->orderByDesc('expired_at')
                    ->paginate(25);
                break;
            case 'pending':
            default:
                $rows = (clone $base)
                    ->where('status', FcStreamlineRank::STATUS_PENDING_ADMIN)
                    ->orderBy('completed_at', 'asc')
                    ->paginate(25);
                $tab = 'pending';
                break;
        }

        $counts = [
            'pending'   => FcStreamlineRank::where('status', FcStreamlineRank::STATUS_PENDING_ADMIN)->count(),
            'active'    => FcStreamlineRank::where('status', FcStreamlineRank::STATUS_ACTIVE)->count(),
            'completed' => FcStreamlineRank::where('status', FcStreamlineRank::STATUS_COMPLETED)->count(),
            'expired'   => FcStreamlineRank::where('status', FcStreamlineRank::STATUS_EXPIRED)->count(),
        ];

        return view('admin.fc-streamline-ranks.index', compact('rows', 'tab', 'counts'));
    }

    public function approve(Request $request, FcStreamlineRank $rank)
    {
        $notes = $request->input('notes');

        $ok = FcStreamlineRankService::approveRank($rank, (int) Auth::id(), $notes);
        if (!$ok) {
            return back()->with('error', 'This rank is not pending admin verification.');
        }

        return back()->with('message', "FC Streamline rank \"{$rank->rankDefinition()['title']}\" approved for user #{$rank->user_id}. Reward \${$rank->reward_usd} and {$rank->reward_tokens} tokens credited.");
    }

    public function reject(Request $request, FcStreamlineRank $rank)
    {
        $notes = $request->input('notes');

        $ok = FcStreamlineRankService::rejectRank($rank, (int) Auth::id(), $notes);
        if (!$ok) {
            return back()->with('error', 'This rank is not pending admin verification.');
        }

        return back()->with('message', "FC Streamline rank \"{$rank->rankDefinition()['title']}\" rejected for user #{$rank->user_id}; returned to active challenge.");
    }
}
