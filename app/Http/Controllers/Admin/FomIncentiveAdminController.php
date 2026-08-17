<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FomIncentiveTier;
use App\Models\FomIncentiveAward;
use Illuminate\Http\Request;

/**
 * Admin: configure FOM incentive tiers + list users who achieved them.
 */
class FomIncentiveAdminController extends Controller
{
    public function index()
    {
        FomIncentiveTier::ensureTableAndData();

        $tiers = FomIncentiveTier::orderBy('sort_order')->orderBy('achievement')->get();

        $awards = FomIncentiveAward::with(['user', 'tier'])
            ->orderByDesc('awarded_at')
            ->paginate(10)
            ->withQueryString();

        $totals = [
            'awards_count' => FomIncentiveAward::count(),
            'bonus_paid'   => (float) FomIncentiveAward::sum('bonus'),
        ];

        return view('admin.fom-incentive.index', compact('tiers', 'awards', 'totals'));
    }

    public function storeTier(Request $request)
    {
        FomIncentiveTier::ensureTableAndData();

        $request->validate([
            'achievement'   => 'required|numeric|min:1',
            'duration_days' => 'required|integer|min:1',
            'bonus'         => 'required|numeric|min:0',
            'sort_order'    => 'nullable|integer|min:0',
        ]);

        FomIncentiveTier::create([
            'achievement'   => (float) $request->achievement,
            'duration_days' => (int) $request->duration_days,
            'bonus'         => (float) $request->bonus,
            'is_active'     => $request->has('is_active'),
            'sort_order'    => (int) ($request->sort_order ?: 0),
        ]);

        return back()->with('success', 'Incentive tier created.');
    }

    public function updateTier(Request $request, $id)
    {
        FomIncentiveTier::ensureTableAndData();
        $tier = FomIncentiveTier::findOrFail($id);

        $request->validate([
            'achievement'   => 'required|numeric|min:1',
            'duration_days' => 'required|integer|min:1',
            'bonus'         => 'required|numeric|min:0',
            'sort_order'    => 'nullable|integer|min:0',
        ]);

        $tier->update([
            'achievement'   => (float) $request->achievement,
            'duration_days' => (int) $request->duration_days,
            'bonus'         => (float) $request->bonus,
            'is_active'     => $request->has('is_active'),
            'sort_order'    => (int) ($request->sort_order ?: $tier->sort_order),
        ]);

        return back()->with('success', 'Incentive tier updated.');
    }

    public function toggleTier($id)
    {
        $tier = FomIncentiveTier::findOrFail($id);
        $tier->is_active = !$tier->is_active;
        $tier->save();

        return back()->with('success', 'Tier status updated.');
    }

    public function destroyTier($id)
    {
        FomIncentiveTier::findOrFail($id)->delete();

        return back()->with('success', 'Incentive tier deleted. Past awards are kept for audit.');
    }
}
