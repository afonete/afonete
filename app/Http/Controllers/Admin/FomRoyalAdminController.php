<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FomLicenceMiner;
use App\Models\FomRoyalTier;
use App\Models\FomRoyalAward;
use App\Services\FomRoyalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Admin: Royal Leader Bonus — tier configuration (fully modifiable) +
 * pending approvals (admin inputs the focoin price at approval).
 */
class FomRoyalAdminController extends Controller
{
    public function index()
    {
        FomRoyalTier::ensureTableAndData();
        FomRoyalAward::ensureTable();

        $tiers = FomRoyalTier::orderBy('level')->get();

        $pending = FomRoyalAward::with('user', 'tier')
            ->where('status', 'pending')
            ->orderBy('detected_at')
            ->paginate(10, ['*'], 'pending_page');

        $recent = FomRoyalAward::with('user')
            ->whereIn('status', ['approved', 'rejected'])
            ->orderByDesc('reviewed_at')
            ->paginate(10, ['*'], 'recent_page');

        $packages = FomLicenceMiner::orderBy('id')->pluck('name');

        $totals = [
            'pending'     => FomRoyalAward::where('status', 'pending')->count(),
            'approved'    => FomRoyalAward::where('status', 'approved')->count(),
            'tokens_paid' => (float) FomRoyalAward::where('status', 'approved')->sum('tokens_paid'),
        ];

        return view('admin.fom-royal.index', compact('tiers', 'pending', 'recent', 'packages', 'totals'));
    }

    /** Update a tier (all qualification/reward fields modifiable). */
    public function updateTier(Request $request, int $id)
    {
        $data = $request->validate([
            'duration_days'       => 'required|integer|min:1|max:3650',
            'vb_earn_required'    => 'required|numeric|min:0',
            'prize_pool_usd'      => 'required|numeric|min:0',
            'promo_hold_package'  => 'required|string|max:30',
            'promo_grant_package' => 'required|string|max:30',
            'sponsors'            => 'required|string|max:500',
            'is_active'           => 'nullable|boolean',
        ]);

        $tier = FomRoyalTier::findOrFail($id);

        // Parse "5 ADVANCED, 3 PREMIUM, 2 MASTER" → sponsors_json
        $sponsors = [];
        foreach (explode(',', $data['sponsors']) as $part) {
            $part = trim($part);
            if ($part === '') {
                continue;
            }
            if (!preg_match('/^(\d+)\s+(.+)$/', $part, $m)) {
                return back()->with('error', "Could not parse sponsor rule '{$part}' — use e.g. '5 ADVANCED, 3 PREMIUM'.");
            }
            $pkgName = strtoupper(trim($m[2]));
            if (!FomLicenceMiner::whereRaw('UPPER(name) = ?', [$pkgName])->exists()) {
                return back()->with('error', "Unknown FOM package '{$pkgName}' in sponsor rules.");
            }
            $sponsors[] = ['package' => $pkgName, 'count' => (int) $m[1]];
        }
        if (empty($sponsors)) {
            return back()->with('error', 'At least one Direct Sponsor rule is required.');
        }

        foreach (['promo_hold_package', 'promo_grant_package'] as $f) {
            $pkgName = strtoupper(trim($data[$f]));
            if (!FomLicenceMiner::whereRaw('UPPER(name) = ?', [$pkgName])->exists()) {
                return back()->with('error', "Unknown FOM package '{$pkgName}'.");
            }
            $data[$f] = $pkgName;
        }

        $tier->update([
            'sponsors_json'       => $sponsors,
            'duration_days'       => (int) $data['duration_days'],
            'vb_earn_required'    => (float) $data['vb_earn_required'],
            'prize_pool_usd'      => (float) $data['prize_pool_usd'],
            'promo_hold_package'  => $data['promo_hold_package'],
            'promo_grant_package' => $data['promo_grant_package'],
            'is_active'           => (bool) ($data['is_active'] ?? $tier->is_active),
        ]);

        return back()->with('success', "{$tier->name} updated.");
    }

    public function toggleTier(int $id)
    {
        $tier = FomRoyalTier::findOrFail($id);
        $tier->update(['is_active' => !$tier->is_active]);

        return back()->with('success', "{$tier->name} " . ($tier->is_active ? 'activated' : 'deactivated') . '.');
    }

    /** Approve: admin inputs the focoin price (PRIZE POOL rule). */
    public function approve(Request $request, int $id)
    {
        $request->validate([
            'focoin_price' => 'required|numeric|gt:0',
            'notes'        => 'nullable|string|max:500',
        ]);

        $result = FomRoyalService::approve($id, (int) Auth::id(), (float) $request->input('focoin_price'), $request->input('notes'));

        return back()->with($result['ok'] ? 'success' : 'error', $result['message']);
    }

    public function reject(Request $request, int $id)
    {
        $request->validate(['notes' => 'nullable|string|max:500']);

        if (!FomRoyalService::reject($id, (int) Auth::id(), $request->input('notes'))) {
            return back()->with('error', 'Rejection failed — the award is not pending.');
        }

        return back()->with('success', 'Royal Leader Bonus rejected.');
    }

    public function detect()
    {
        $created = FomRoyalService::detectAll();

        return back()->with('success', "Detection sweep complete — {$created} new pending award(s).");
    }
}
