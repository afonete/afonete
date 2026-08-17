<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FomRedeemOption;
use App\Models\FomRedeemLog;
use Illuminate\Http\Request;

/**
 * Admin: configure volume-point redemption options + audit who redeemed.
 */
class FomRedeemAdminController extends Controller
{
    public function index()
    {
        FomRedeemOption::ensureTableAndData();

        $options = FomRedeemOption::orderBy('sort_order')->orderBy('points_required')->get();

        $logs = FomRedeemLog::with(['user', 'option'])
            ->orderByDesc('redeemed_at')
            ->paginate(10)
            ->withQueryString();

        $totals = [
            'redemptions'  => FomRedeemLog::count(),
            'points_spent' => (float) FomRedeemLog::sum('points_spent'),
            'usdt_paid'    => (float) FomRedeemLog::sum('usdt_received'),
        ];

        return view('admin.fom-redeem.index', compact('options', 'logs', 'totals'));
    }

    public function store(Request $request)
    {
        FomRedeemOption::ensureTableAndData();

        $request->validate([
            'usdt_amount'     => 'required|numeric|min:0.01',
            'points_required' => 'required|numeric|min:1',
            'sort_order'      => 'nullable|integer|min:0',
        ]);

        FomRedeemOption::create([
            'usdt_amount'     => (float) $request->usdt_amount,
            'points_required' => (float) $request->points_required,
            'is_active'       => $request->has('is_active'),
            'sort_order'      => (int) ($request->sort_order ?: 0),
        ]);

        return back()->with('success', 'Redemption option created.');
    }

    public function update(Request $request, $id)
    {
        $option = FomRedeemOption::findOrFail($id);

        $request->validate([
            'usdt_amount'     => 'required|numeric|min:0.01',
            'points_required' => 'required|numeric|min:1',
            'sort_order'      => 'nullable|integer|min:0',
        ]);

        $option->update([
            'usdt_amount'     => (float) $request->usdt_amount,
            'points_required' => (float) $request->points_required,
            'is_active'       => $request->has('is_active'),
            'sort_order'      => (int) ($request->sort_order ?: $option->sort_order),
        ]);

        return back()->with('success', 'Redemption option updated.');
    }

    public function toggle($id)
    {
        $option = FomRedeemOption::findOrFail($id);
        $option->is_active = !$option->is_active;
        $option->save();

        return back()->with('success', 'Option status updated.');
    }

    public function destroy($id)
    {
        FomRedeemOption::findOrFail($id)->delete();

        return back()->with('success', 'Redemption option deleted. Past redemptions are kept for audit.');
    }
}
