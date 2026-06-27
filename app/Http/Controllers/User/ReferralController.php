<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\ReferralBonus;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserRank;
use App\Models\WeeklyWithdrawal;
use App\Services\ReferralService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReferralController extends Controller
{
    /* ===========================================================
     *  BONUS HISTORY + WITHDRAWAL
     * =========================================================== */

    public function bonus()
    {
        $user    = Auth::user();
        $totals  = ReferralBonus::totalsForUser($user->id);

        $rows = ReferralBonus::where('user_id', $user->id)
            ->with('sourceUser')
            ->orderByDesc('created_at')
            ->paginate(30);

        $nextMonday    = ReferralService::nextMonday();
        $isMonday      = ReferralService::isMondayNow();
        $pendingCount  = ReferralBonus::where('user_id', $user->id)->where('status', 'pending')->count();
        $alreadyReq    = WeeklyWithdrawal::where('user_id', $user->id)
            ->where('week_start', $nextMonday->toDateString())
            ->whereIn('status', ['pending','approved'])
            ->exists();

        return view('user.referral.bonus', compact(
            'totals','rows','nextMonday','isMonday','pendingCount','alreadyReq'
        ));
    }

    /**
     * Submit a weekly withdrawal request.
     * Per spec: only on Monday.
     */
    public function withdraw(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'transaction_password' => ['required', new \App\Rules\ValidTransactionPassword($user)],
        ]);

        if (!ReferralService::isMondayNow()) {
            return back()->with('error', 'Referral bonuses can only be withdrawn on Monday.');
        }

        $withdrawable = (float) ReferralBonus::where('user_id', $user->id)
            ->where('status', 'withdrawable')
            ->sum('bonus_amount');

        if ($withdrawable <= 0) {
            return back()->with('error', 'No withdrawable referral bonus available this week.');
        }

        // Already requested for this Monday?
        $nextMonday = ReferralService::nextMonday();
        if (WeeklyWithdrawal::where('user_id', $user->id)
            ->where('week_start', $nextMonday->toDateString())
            ->whereIn('status', ['pending','approved'])
            ->exists()) {
            return back()->with('error', 'You already submitted a withdrawal for this Monday.');
        }

        DB::transaction(function () use ($user, $withdrawable, $nextMonday) {
            $trxNo = 'RWD-' . strtoupper(\Illuminate\Support\Str::random(10));
            WeeklyWithdrawal::create([
                'user_id'        => $user->id,
                'week_start'     => $nextMonday->toDateString(),
                'amount'         => $withdrawable,
                'transaction_no' => $trxNo,
                'status'         => 'pending',
            ]);

            // Move all withdrawable rows for this user into a "withdrawn" pending state
            // (we mark them with withdrawn_at = null + a flag so admin can re-mark on payment)
            ReferralBonus::where('user_id', $user->id)
                ->where('status', 'withdrawable')
                ->update(['status' => 'withdrawn', 'withdrawn_at' => now()]);

            // Log a Transaction entry for the user history
            Transaction::create([
                'user_id'             => $user->id,
                'transaction_no'      => $trxNo,
                'transaction_type'    => 'REFERRAL_WITHDRAWAL',
                'receiver_id'         => 0,
                'transaction_details' => json_encode([
                    'amount'    => $withdrawable,
                    'week'      => $nextMonday->toDateString(),
                    'status'    => 'pending',
                    'date'      => now()->toDateTimeString(),
                    'username'  => $user->name,
                ]),
            ]);
        });

        return back()->with('success', 'Weekly withdrawal request submitted. Awaiting admin approval.');
    }

    /* ===========================================================
     *  DOWNLINE / TREE VIEW
     * =========================================================== */

    public function downline()
    {
        $user = Auth::user();
        $directCount   = $user->referrals()->count();
        $activeDirect  = $user->activeDirectReferralCount();
        $directInv     = $user->directReferralActiveInvestment();
        $totalInv      = $user->totalReferralActiveInvestment();

        $directs = $user->referrals()
            ->with(['investments' => function ($q) {
                $q->where('category','VENTURE')->where('status',1)->where('is_expired', false);
            }, 'userRanks' => function ($q) {
                $q->where('status','approved')->orderByDesc('rank_level');
            }])
            ->orderBy('created_at','desc')
            ->paginate(50);

        return view('user.referral.downline', compact(
            'directCount','activeDirect','directInv','totalInv','directs'
        ));
    }

    /* ===========================================================
     *  RANK PAGE — current rank + history + downloadable images
     * =========================================================== */

    public function rank()
    {
        $user = Auth::user();
        $current = $user->currentRank();
        $history = $user->userRanks()->with('rank')->orderByDesc('created_at')->paginate(30);
        $allRanks = \App\Models\RankSetting::orderedList();

        // Compute live criteria snapshot for the next rank up
        $progress = self::rankProgress($user);

        return view('user.referral.rank', compact(
            'current','history','allRanks','progress'
        ));
    }

    /**
     * Build a criteria-progress snapshot for the user.
     * Returns array keyed by rank slug → ['met' => bool, 'actual' => array, 'required' => array]
     */
    public static function rankProgress(User $user): array
    {
        $directInv   = $user->directReferralActiveInvestment();
        $totalInv    = $user->totalReferralActiveInvestment();
        $directCount = $user->activeDirectReferralCount();

        $assoc   = $user->directReferralsWithRank('associate');
        $dir     = $user->directReferralsWithRank('director');
        $rs      = $user->directReferralsWithRank('regional_supervisor');

        $out = [];
        foreach (\App\Models\RankSetting::orderedList() as $rank) {
            $actual = [
                'active_direct_referrals' => $directCount,
                'associates_from_direct'  => $assoc,
                'directors_from_direct'   => $dir,
                'regional_supervisors_from_direct' => $rs,
                'direct_referral_investment' => $directInv,
                'total_investment'        => $totalInv,
            ];
            $required = [
                'active_direct_referrals' => $rank->min_active_direct_referrals,
                'associates_from_direct'  => $rank->min_associates_from_direct,
                'directors_from_direct'   => $rank->min_directors_from_direct,
                'regional_supervisors_from_direct' => $rank->min_regional_supervisors_from_direct,
                'direct_referral_investment' => (float) $rank->min_direct_referral_investment,
                'total_investment'        => (float) $rank->min_total_investment,
            ];
            $met = true;
            foreach ($required as $k => $v) {
                if ((float) $actual[$k] < (float) $v) { $met = false; break; }
            }
            $out[$rank->slug] = [
                'rank'     => $rank,
                'met'      => $met,
                'actual'   => $actual,
                'required' => $required,
            ];
        }
        return $out;
    }
}
