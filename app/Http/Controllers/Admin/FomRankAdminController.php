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

    /**
     * ADMIN: edit/modify a rank's qualification + reward values.
     * criteria_json accepted as raw JSON (validated); blank keeps current.
     */
    public function updateRank(Request $request, int $id)
    {
        $data = $request->validate([
            'vb_required'       => 'required|numeric|min:0',
            'team_turnover'     => 'required|numeric|min:0',
            'personal_turnover' => 'required|numeric|min:0',
            'licence_min'       => 'required|string|max:30',
            'reward'            => 'required|numeric|min:0',
            'extra_bonuses'     => 'nullable|string|max:255',
            'criteria_label'    => 'nullable|string|max:255',
            'criteria_json'     => 'nullable|string|max:2000',
        ]);

        $rank = FomRank::findOrFail($id);

        $licence = strtoupper(trim($data['licence_min']));
        if (!array_key_exists($licence, FomRank::LICENCE_ORDER)) {
            return back()->with('error', "Unknown FOM licence '{$licence}' — use one of: " . implode(', ', array_keys(FomRank::LICENCE_ORDER)) . '.');
        }

        $update = [
            'vb_required'       => (float) $data['vb_required'],
            'team_turnover'     => (float) $data['team_turnover'],
            'personal_turnover' => (float) $data['personal_turnover'],
            'licence_min'       => $licence,
            'reward'            => (float) $data['reward'],
            'extra_bonuses'     => $data['extra_bonuses'] ?? $rank->extra_bonuses,
            'criteria_label'    => $data['criteria_label'] ?? $rank->criteria_label,
        ];

        if (trim((string) ($data['criteria_json'] ?? '')) !== '') {
            $decoded = json_decode($data['criteria_json'], true);
            if (!is_array($decoded) || !isset($decoded['type'])
                || !in_array($decoded['type'], ['bonus', 'active_directs', 'ranks'], true)) {
                return back()->with('error', 'criteria_json must be valid JSON with "type": bonus | active_directs | ranks.');
            }
            $update['criteria_json'] = $decoded;
        }

        $rank->update($update);

        return back()->with('success', "{$rank->name} updated.");
    }

    /** ADMIN: activate/deactivate a rank in the ladder. */
    public function toggleRank(int $id)
    {
        $rank = FomRank::findOrFail($id);
        $rank->update(['is_active' => !$rank->is_active]);

        return back()->with('success', "{$rank->name} " . ($rank->is_active ? 'activated' : 'deactivated') . '.');
    }
}
