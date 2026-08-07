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
     * Moves withdrawable referral bonuses directly to user's CASHOUT wallet for withdrawal.
     * Per spec: allowed on Monday.
     */
    public function withdraw(Request $request)
    {
        $user = Auth::user();

        if (!empty($user->transaction_password)) {
            $request->validate([
                'transaction_password' => ['required', new \App\Rules\ValidTransactionPassword($user)],
            ]);
        }

        if (!ReferralService::isMondayNow()) {
            return back()->with('error', 'Referral bonuses can only be withdrawn on Monday.');
        }

        ReferralService::promotePendingToWithdrawable();

        $withdrawable = (float) ReferralBonus::where('user_id', $user->id)
            ->where('status', 'withdrawable')
            ->sum('bonus_amount');

        if ($withdrawable <= 0) {
            return back()->with('error', 'No withdrawable referral bonus available this week.');
        }

        // Already requested / transferred for this Monday?
        $nextMonday = ReferralService::nextMonday();
        if (WeeklyWithdrawal::where('user_id', $user->id)
            ->where('week_start', $nextMonday->toDateString())
            ->whereIn('status', ['pending', 'approved', 'paid'])
            ->exists()) {
            return back()->with('error', 'You have already transferred your referral bonus to Cashout for this Monday.');
        }

        DB::transaction(function () use ($user, $withdrawable, $nextMonday) {
            $trxNo = 'RWD-' . strtoupper(\Illuminate\Support\Str::random(10));

            // 1. Credit the withdrawable referral bonus directly into ChartAccount CASHOUT
            $existingCashout = (float) \App\Models\ChartAccount::where('user_id', $user->id)
                ->where('acc_type', 'CASHOUT')->sum('amount');

            \App\Models\ChartAccount::updateOrCreate(
                ['user_id' => $user->id, 'acc_type' => 'CASHOUT'],
                ['amount' => $existingCashout + $withdrawable]
            );

            // 2. Create the WeeklyWithdrawal audit record (marked PAID to CASHOUT)
            WeeklyWithdrawal::create([
                'user_id'        => $user->id,
                'week_start'     => $nextMonday->toDateString(),
                'amount'         => $withdrawable,
                'transaction_no' => $trxNo,
                'status'         => 'paid',
                'payment_method' => 'CASHOUT_WALLET',
                'admin_notes'    => 'Transferred directly to user CASHOUT wallet for withdrawal.',
                'processed_at'   => now(),
            ]);

            // 3. Mark all withdrawable bonus rows as withdrawn
            ReferralBonus::where('user_id', $user->id)
                ->where('status', 'withdrawable')
                ->update(['status' => 'withdrawn', 'withdrawn_at' => now()]);

            // 4. Log Transaction history record
            Transaction::create([
                'user_id'             => $user->id,
                'transaction_no'      => $trxNo,
                'transaction_type'    => 'REFERRAL_BONUS_TO_CASHOUT',
                'receiver_id'         => 0,
                'transaction_details' => json_encode([
                    'amount'    => $withdrawable,
                    'week'      => $nextMonday->toDateString(),
                    'status'    => 'transferred_to_cashout',
                    'date'      => now()->toDateTimeString(),
                    'username'  => $user->name,
                ]),
            ]);
        });

        return redirect()->route('user.dashboard.withdraw')
            ->with('message', 'Success! Referral bonus of $' . number_format($withdrawable, 2) . ' has been transferred to your Cashout wallet. You can now request your withdrawal below.');
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
