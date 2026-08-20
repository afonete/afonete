<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralBonus;
use App\Models\ChartAccount;
use App\Models\User;
use App\Services\FomReferralService;
use Illuminate\Http\Request;

/**
 * Admin FOM referral management: platform totals, per-user volumes,
 * bonus audit (incl. ineligible/forfeited rows), and a manual weekly run.
 */
class FomReferralAdminController extends Controller
{
    public function index(Request $request)
    {
        // Self-heal legacy FOM purchases into the referral plan (idempotent).
        FomReferralService::syncMissingFomBonuses();

        // Platform totals
        $totals = [
            'accrued_direct' => (float) ReferralBonus::where('source', 'fom_referral')->where('status', 'accrued')->sum('bonus_amount'),
            'paid_total'     => (float) ReferralBonus::where('source', 'fom_referral')->where('status', 'paid')->sum('bonus_amount'),
            'ineligible_cnt' => (int) ReferralBonus::where('source', 'fom_referral')->where('status', 'ineligible')->count(),
            'left_volume'    => (float) ChartAccount::where('acc_type', FomReferralService::ACC_VOL_LEFT)->sum('amount'),
            'right_volume'   => (float) ChartAccount::where('acc_type', FomReferralService::ACC_VOL_RIGHT)->sum('amount'),
            'volume_points'  => (float) ChartAccount::where('acc_type', 'VOLUME_POINT')->sum('amount'),
        ];

        // VOLUME CAP EXCESS (spec §70): weekly payouts clipped at each
        // user's package cap — the forfeited excess is reported here so
        // the admin can see the total of them (all-time + this week),
        // plus the most recent capped payouts.
        $capExcessTotal = 0.0;
        $capExcessWeek  = 0.0;
        $cappedRows     = collect();
        try {
            $weekStart = \Carbon\Carbon::now()->startOfWeek();
            $payoutTrx = \App\Models\Transaction::where('transaction_type', 'FOM_WEEKLY_REFERRAL_PAYOUT')
                ->orderByDesc('created_at')
                ->get();
            foreach ($payoutTrx as $t) {
                $d = json_decode((string) $t->transaction_details, true) ?: [];
                $forfeit = (float) ($d['cap_forfeited'] ?? 0);
                if ($forfeit <= 0) {
                    continue;
                }
                $capExcessTotal += $forfeit;
                if (\Carbon\Carbon::parse($t->created_at)->gte($weekStart)) {
                    $capExcessWeek += $forfeit;
                }
                if ($cappedRows->count() < 10) {
                    $cappedRows->push((object) [
                        'user'      => User::find($t->user_id),
                        'date'      => $t->created_at,
                        'uncapped'  => (float) ($d['binary_payout'] ?? 0),
                        'cap'       => (float) ($d['vb_cap'] ?? 0),
                        'paid'      => (float) ($d['capped_payout'] ?? 0),
                        'forfeited' => $forfeit,
                    ]);
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Cap-excess totals failed: ' . $e->getMessage());
        }
        $totals['cap_excess_total'] = round($capExcessTotal, 4);
        $totals['cap_excess_week']  = round($capExcessWeek, 4);

        // Per-user volume board: everyone holding leg volume or accrued rows
        $userIds = ChartAccount::whereIn('acc_type', [FomReferralService::ACC_VOL_LEFT, FomReferralService::ACC_VOL_RIGHT])
            ->where('amount', '>', 0)->pluck('user_id')
            ->merge(ReferralBonus::where('source', 'fom_referral')->where('status', 'accrued')->pluck('user_id'))
            ->unique()->values();

        $search = trim((string) $request->input('search', ''));

        $usersQuery = User::whereIn('id', $userIds);
        if ($search !== '') {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('user', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $usersQuery->orderBy('name')->paginate(10)->withQueryString();

        $board = collect($users->items())->map(function ($u) {
            $left  = (float) ChartAccount::where('user_id', $u->id)->where('acc_type', FomReferralService::ACC_VOL_LEFT)->sum('amount');
            $right = (float) ChartAccount::where('user_id', $u->id)->where('acc_type', FomReferralService::ACC_VOL_RIGHT)->sum('amount');
            $weaker = min($left, $right);
            return (object) [
                'user'          => $u,
                'left'          => $left,
                'right'         => $right,
                'weaker'        => $weaker,
                'next_binary'   => $weaker > 0 ? round($weaker * (FomReferralService::AFFILIATE_VBONUS_RATE / 100), 4) : 0.0,
                'accrued'       => (float) ReferralBonus::where('user_id', $u->id)->where('source', 'fom_referral')->where('status', 'accrued')->sum('bonus_amount'),
                'volume_points' => (float) ChartAccount::where('user_id', $u->id)->where('acc_type', 'VOLUME_POINT')->sum('amount'),
                'eligible'      => FomReferralService::isAffiliateEligible($u),
            ];
        });

        // FOM codes bought but not yet activated: no Payment exists yet, so
        // no referral accrual — surfaced here so admins can see why an
        // upline "can't see" a referral's purchase.
        $fomNames = \App\Models\FomLicenceMiner::pluck('name')->map(fn ($n) => strtoupper(trim((string) $n)))->all();
        $pendingActivations = \App\Models\Activations::whereIn('stutus', ['not', 'pending'])
            ->orderByDesc('id')
            ->limit(200)
            ->get()
            ->filter(function ($a) use ($fomNames) {
                return str_starts_with(strtoupper((string) $a->code), 'FOM-')
                    || in_array(strtoupper(trim((string) $a->package)), $fomNames, true);
            })
            ->take(10)
            ->values();

        return view('admin.fom-referral.index', compact('totals', 'users', 'board', 'search', 'pendingActivations', 'cappedRows'));
    }

    /** Per-user audit: all FOM bonus rows for one user. */
    public function userDetail($id)
    {
        $user = User::findOrFail($id);

        $left  = (float) ChartAccount::where('user_id', $user->id)->where('acc_type', FomReferralService::ACC_VOL_LEFT)->sum('amount');
        $right = (float) ChartAccount::where('user_id', $user->id)->where('acc_type', FomReferralService::ACC_VOL_RIGHT)->sum('amount');
        $volumePoints = (float) ChartAccount::where('user_id', $user->id)->where('acc_type', 'VOLUME_POINT')->sum('amount');
        $eligible = FomReferralService::isAffiliateEligible($user);

        $rows = ReferralBonus::where('user_id', $user->id)
            ->where('source', 'fom_referral')
            ->with('sourceUser')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.fom-referral.user-detail', compact('user', 'left', 'right', 'volumePoints', 'eligible', 'rows'));
    }

    /** Manual trigger of the Monday weekly run (idempotent). */
    public function runWeekly()
    {
        $paid = FomReferralService::processWeekly();

        return back()->with('success', "FOM weekly referral payout executed: {$paid} user(s) paid to cashout.");
    }
}
