<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FomResidualLevel;
use App\Models\FomResidualApproval;
use App\Models\Transaction;
use App\Services\FomResidualService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Admin: Residual Income Matching Bonus — level configuration
 * (fully modifiable) + weekly payout audit + manual run.
 */
class FomResidualAdminController extends Controller
{
    public function index()
    {
        FomResidualLevel::ensureTableAndData();
        FomResidualApproval::ensureTable();

        $levels = FomResidualLevel::orderBy('level')->get();

        $approvals = FomResidualApproval::with('user')
            ->where('status', 'pending')
            ->orderBy('detected_at')
            ->paginate(10, ['*'], 'approvals_page');

        $payouts = Transaction::where('transaction_type', 'FOM_RESIDUAL_MATCHING')
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $totalPaid = 0.0;
        foreach (Transaction::where('transaction_type', 'FOM_RESIDUAL_MATCHING')->get() as $t) {
            $d = json_decode((string) $t->transaction_details, true) ?: [];
            $totalPaid += (float) ($d['total'] ?? 0);
        }

        return view('admin.fom-residual.index', compact('levels', 'approvals', 'payouts', 'totalPaid'));
    }

    /** Approve a leader's pending level (unlocks its weekly % payout). */
    public function approveLevel(Request $request, int $id)
    {
        $request->validate(['notes' => 'nullable|string|max:500']);

        if (!FomResidualService::approveLevel($id, (int) Auth::id(), $request->input('notes'))) {
            return back()->with('error', 'Approval failed — the row is not pending.');
        }

        return back()->with('success', 'Level approved — the leader now earns this level\'s % every Monday (to Cashout).');
    }

    public function rejectLevel(Request $request, int $id)
    {
        $request->validate(['notes' => 'nullable|string|max:500']);

        if (!FomResidualService::rejectLevel($id, (int) Auth::id(), $request->input('notes'))) {
            return back()->with('error', 'Rejection failed — the row is not pending.');
        }

        return back()->with('success', 'Level rejected.');
    }

    /** Refresh the pending-approvals queue from current structures. */
    public function detect()
    {
        $created = FomResidualService::detectApprovalsAll();

        return back()->with('success', "Detection complete — {$created} new pending level approval(s).");
    }

    /** Update a level's requirement/per-parent/income (admin-modifiable). */
    public function updateLevel(Request $request, int $id)
    {
        $data = $request->validate([
            'required_members' => 'required|integer|min:1|max:100000',
            'per_parent'       => 'required|integer|min:1|max:1000',
            'income_percent'   => 'required|numeric|min:0|max:100',
        ]);

        $level = FomResidualLevel::findOrFail($id);
        $level->update($data);

        return back()->with('success', "Level {$level->level} updated.");
    }

    public function toggleLevel(int $id)
    {
        $level = FomResidualLevel::findOrFail($id);
        $level->update(['is_active' => !$level->is_active]);

        return back()->with('success', "Level {$level->level} " . ($level->is_active ? 'activated' : 'deactivated') . '.');
    }

    /** Manual weekly run (idempotent per leader per week). */
    public function runWeekly()
    {
        $paid = FomResidualService::processWeekly();

        return back()->with('success', "Residual matching run complete — {$paid} leader(s) paid.");
    }
}
