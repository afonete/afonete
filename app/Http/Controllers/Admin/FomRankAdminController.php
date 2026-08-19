<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FomRank;
use App\Models\FomUserRank;
use App\Services\FomRankService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Admin: FOM rank ladder + pending approvals.
 */
class FomRankAdminController extends Controller
{
    public function index(Request $request)
    {
        FomRank::ensureTableAndData();
        FomUserRank::ensureTable();

        $ranks = FomRank::ordered()->get();

        $pending = FomUserRank::with('user')
            ->where('status', 'pending')
            ->orderBy('detected_at')
            ->paginate(10, ['*'], 'pending_page');

        $recent = FomUserRank::with('user')
            ->whereIn('status', ['approved', 'rejected'])
            ->orderByDesc('reviewed_at')
            ->paginate(10, ['*'], 'recent_page');

        $totals = [
            'pending'      => FomUserRank::where('status', 'pending')->count(),
            'approved'     => FomUserRank::where('status', 'approved')->count(),
            'rewards_paid' => (float) FomUserRank::where('status', 'approved')->sum('reward_paid'),
        ];

        return view('admin.fom-rank.index', compact('ranks', 'pending', 'recent', 'totals'));
    }

    public function approve(Request $request, int $id)
    {
        $request->validate(['notes' => 'nullable|string|max:500']);

        if (!FomRankService::approve($id, (int) Auth::id(), $request->input('notes'))) {
            return back()->with('error', 'Approval failed — the row is not pending (or was already reviewed).');
        }

        return back()->with('success', 'Rank approved — reward credited to the user\'s Cashout balance.');
    }

    public function reject(Request $request, int $id)
    {
        $request->validate(['notes' => 'nullable|string|max:500']);

        if (!FomRankService::reject($id, (int) Auth::id(), $request->input('notes'))) {
            return back()->with('error', 'Rejection failed — the row is not pending.');
        }

        return back()->with('success', 'Rank rejected.');
    }

    /** Manual detection sweep (also runs weekly via cron). */
    public function detect()
    {
        $created = FomRankService::detectAll();

        return back()->with('success', "Detection sweep complete — {$created} new pending rank(s) found.");
    }
}
