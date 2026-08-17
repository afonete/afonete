<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ReferralBonus;
use App\Models\ChartAccount;
use App\Services\FomReferralService;
use App\Services\ReferralService;
use Illuminate\Support\Facades\Auth;

/**
 * User-side FOM Licence Miner referral management:
 * side volumes, volume points, accrued direct bonuses, level history,
 * eligibility status and next-payout preview.
 */
class FomReferralController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Self-heal: credit any FOM purchases that predate the referral
        // engine (or failed mid-request) so uplines always see their
        // referrals and bonuses. Idempotent per referrer+payment+level.
        FomReferralService::syncMissingFomBonuses();

        $left  = (float) ChartAccount::where('user_id', $user->id)->where('acc_type', FomReferralService::ACC_VOL_LEFT)->sum('amount');
        $right = (float) ChartAccount::where('user_id', $user->id)->where('acc_type', FomReferralService::ACC_VOL_RIGHT)->sum('amount');
        $volumePoints = (float) ChartAccount::where('user_id', $user->id)->where('acc_type', 'VOLUME_POINT')->sum('amount');

        $weaker        = min($left, $right);
        $binaryPreview = $weaker > 0 ? round($weaker * (FomReferralService::AFFILIATE_VBONUS_RATE / 100), 4) : 0.0;

        $accruedDirect = (float) ReferralBonus::where('user_id', $user->id)
            ->where('source', 'fom_referral')
            ->where('status', 'accrued')
            ->sum('bonus_amount');

        // Next Monday payout preview: pays ONLY when both legs have volume.
        $willPay       = $weaker > 0;
        $payoutPreview = $willPay ? round($binaryPreview + $accruedDirect, 4) : 0.0;

        $isEligible   = FomReferralService::isAffiliateEligible($user);
        $directRate   = FomReferralService::directSponsorRate($user);
        $nextMonday   = ReferralService::nextMonday();

        // Paid history totals
        $paidTotal = (float) ReferralBonus::where('user_id', $user->id)
            ->where('source', 'fom_referral')
            ->where('status', 'paid')
            ->sum('bonus_amount');

        // Per-level summary
        $levelSummary = ReferralBonus::where('user_id', $user->id)
            ->where('source', 'fom_referral')
            ->selectRaw('level, COUNT(*) as cnt, SUM(bonus_amount) as direct_total')
            ->groupBy('level')
            ->orderBy('level')
            ->get();

        // Detailed rows (visible even when ineligible — "you referred
        // someone but no commission")
        $rows = ReferralBonus::where('user_id', $user->id)
            ->where('source', 'fom_referral')
            ->with('sourceUser')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        // Referral purchases AWAITING ACTIVATION: buying a FOM package only
        // creates activation codes — the Payment (and referral bonus) exists
        // only once the code is ACTIVATED. Show these so the upline can see
        // the purchase happened and knows why no bonus has accrued yet.
        $directIds = \App\Models\User::where('referee_id', $user->id)->pluck('id');
        $fomNames  = \App\Models\FomLicenceMiner::pluck('name')->map(fn ($n) => strtoupper(trim((string) $n)))->all();
        $pendingActivations = \App\Models\Activations::whereIn('user_id', $directIds)
            ->whereIn('stutus', ['not', 'pending'])
            ->get()
            ->filter(function ($a) use ($fomNames) {
                return str_starts_with(strtoupper((string) $a->code), 'FOM-')
                    || in_array(strtoupper(trim((string) $a->package)), $fomNames, true);
            })
            ->values();

        return view('user.fom-referral.index', compact(
            'left', 'right', 'weaker', 'binaryPreview', 'accruedDirect',
            'willPay', 'payoutPreview', 'isEligible', 'directRate',
            'nextMonday', 'paidTotal', 'volumePoints', 'levelSummary', 'rows',
            'pendingActivations'
        ));
    }
}
