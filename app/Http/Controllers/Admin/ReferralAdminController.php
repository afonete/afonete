<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\RankSetting;
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
use Illuminate\Support\Facades\Storage;

class ReferralAdminController extends Controller
{
    /* ===========================================================
     *  BONUSES OVERVIEW
     * =========================================================== */

    public function bonuses()
    {
        $totals = ReferralService::platformTotals();

        // Per-user totals, top earners first
        $perUser = DB::table('referral_bonuses')
            ->select('user_id',
                DB::raw('SUM(bonus_amount) AS total_bonus'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN bonus_amount ELSE 0 END) AS pending'),
                DB::raw('SUM(CASE WHEN status = "withdrawable" THEN bonus_amount ELSE 0 END) AS withdrawable'),
                DB::raw('SUM(CASE WHEN status = "withdrawn" THEN bonus_amount ELSE 0 END) AS withdrawn'),
                DB::raw('COUNT(*) AS row_count')
            )
            ->groupBy('user_id')
            ->orderByDesc('total_bonus')
            ->paginate(50);

        // Pull user names in one go
        $userIds = $perUser->pluck('user_id')->all();
        $users   = User::whereIn('id', $userIds)->get()->keyBy('id');

        return view('admin.referral.bonuses', compact('totals','perUser','users'));
    }

    public function userDetail($userId)
    {
        $user   = User::findOrFail($userId);
        $totals = ReferralBonus::totalsForUser($userId);
        $rows   = ReferralBonus::where('user_id', $userId)
            ->with('sourceUser')
            ->orderByDesc('created_at')
            ->paginate(50);

        $weekly = WeeklyWithdrawal::where('user_id', $userId)->orderByDesc('created_at')->limit(20)->get();

        // ── Pull all of this user's investments ──────────────────────
        $investments = \App\Models\Payment::where('user', $user->id)
            ->orderByDesc('created_at')
            ->limit(30)
            ->get();

        return view('admin.referral.user-detail', compact('user','totals','rows','weekly','investments'));
    }

    /* ===========================================================
     *  WEEKLY WITHDRAWAL QUEUE
     * =========================================================== */

    public function withdrawals()
    {
        $pending  = WeeklyWithdrawal::where('status','pending')->with('user')->orderBy('created_at')->get();
        $approved = WeeklyWithdrawal::whereIn('status',['approved','paid'])->with('user')->orderByDesc('processed_at')->limit(50)->get();
        $rejected = WeeklyWithdrawal::where('status','rejected')->with('user')->orderByDesc('processed_at')->limit(30)->get();

        return view('admin.referral.withdrawals', compact('pending','approved','rejected'));
    }

    public function approveWithdrawal(Request $request, $id)
    {
        $w = WeeklyWithdrawal::findOrFail($id);
        if ($w->status !== 'pending') {
            return back()->with('error', 'Already processed.');
        }
        $w->update([
            'status'       => $request->input('mark_paid') ? 'paid' : 'approved',
            'processed_by' => Auth::id(),
            'processed_at' => now(),
            'admin_notes'  => $request->admin_notes,
            'payment_method'=> $request->payment_method ?? 'manual',
        ]);
        return back()->with('success', 'Withdrawal marked ' . ($request->input('mark_paid') ? 'PAID' : 'APPROVED') . '.');
    }

    public function rejectWithdrawal(Request $request, $id)
    {
        $w = WeeklyWithdrawal::findOrFail($id);
        if ($w->status !== 'pending') {
            return back()->with('error', 'Already processed.');
        }

        DB::transaction(function () use ($w, $request) {
            // Refund: flip those bonus rows back to 'withdrawable'
            ReferralBonus::where('user_id', $w->user_id)
                ->where('status', 'withdrawn')
                ->where('withdrawn_at', '>=', $w->created_at)
                ->update(['status' => 'withdrawable', 'withdrawn_at' => null]);

            $w->update([
                'status'       => 'rejected',
                'processed_by' => Auth::id(),
                'processed_at' => now(),
                'admin_notes'  => $request->admin_notes ?? 'Rejected by admin',
            ]);
        });

        return back()->with('success', 'Withdrawal rejected and bonus refunded.');
    }

    /* ===========================================================
     *  RANK APPLICATIONS
     * =========================================================== */

    public function rankApplications()
    {
        $pending  = UserRank::where('status','pending')
            ->with(['user','rank'])->orderBy('detected_at')->get();
        $approved = UserRank::where('status','approved')->with(['user','rank'])->orderByDesc('reviewed_at')->paginate(30);
        $rejected = UserRank::where('status','rejected')->with(['user','rank'])->orderByDesc('reviewed_at')->paginate(30);

        return view('admin.referral.rank-applications', compact('pending','approved','rejected'));
    }

    public function approveRank(Request $request, $id)
    {
        $ur = UserRank::findOrFail($id);
        if ($ur->status !== 'pending') {
            return back()->with('error', 'Already processed.');
        }

        $request->validate([
            'congratulation_image' => 'required|image|max:4096',
            'admin_notes'          => 'nullable|string|max:2000',
        ]);

        $path = $request->file('congratulation_image')->store('rank-pictures', 'public');

        DB::transaction(function () use ($ur, $request, $path) {
            $ur->update([
                'status'               => 'approved',
                'reviewed_by'          => Auth::id(),
                'reviewed_at'          => now(),
                'congratulation_image' => $path,
                'admin_notes'          => $request->admin_notes,
            ]);

            // If this rank has a fixed reward, credit it as a referral_bonus row
            $rank = $ur->rank;
            if ($rank && $rank->reward_type === 'fixed' && (float) $rank->reward_amount > 0) {
                $weekStart = ReferralService::nextMonday();

                $bonus = ReferralBonus::create([
                    'user_id'        => $ur->user_id,
                    'source_user_id' => $ur->user_id, // self-attributed
                    'level'          => 0,
                    'percentage'     => 0,
                    'source_amount'  => (float) $rank->reward_amount,
                    'bonus_amount'   => (float) $rank->reward_amount,
                    'week_start'     => $weekStart,
                    'status'         => 'pending',
                    'source'         => 'rank_reward',
                    'source_ref'     => $rank->slug,
                    'notes'          => 'Rank reward: ' . $rank->name,
                ]);

                $ur->update([
                    'reward_amount'      => (float) $rank->reward_amount,
                    'referral_bonus_id'  => $bonus->id,
                ]);

                Transaction::create([
                    'user_id'             => $ur->user_id,
                    'transaction_no'      => 'RANK-' . strtoupper(\Illuminate\Support\Str::random(8)),
                    'transaction_type'    => 'RANK_REWARD',
                    'receiver_id'         => 0,
                    'transaction_details' => json_encode([
                        'rank'      => $rank->name,
                        'amount'    => (float) $rank->reward_amount,
                        'date'      => now()->toDateTimeString(),
                        'user_rank_id' => $ur->id,
                        'status'    => 'pending_withdrawal',
                    ]),
                ]);
            }
        });

        return back()->with('success', 'Rank approved, image uploaded, reward credited.');
    }

    public function rejectRank(Request $request, $id)
    {
        $ur = UserRank::findOrFail($id);
        if ($ur->status !== 'pending') {
            return back()->with('error', 'Already processed.');
        }
        $ur->update([
            'status'      => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => $request->admin_notes ?? 'Rejected by admin',
        ]);
        return back()->with('success', 'Rank application rejected.');
    }

    /* ===========================================================
     *  ELIGIBLE USERS (criteria overview)
     * =========================================================== */

    public function eligible()
    {
        $users = User::where('utype','USR')->orderBy('id')->limit(500)->get();
        $rows = [];

        foreach ($users as $user) {
            $progress = \App\Http\Controllers\User\ReferralController::rankProgress($user);
            foreach ($progress as $slug => $info) {
                if ($info['met']) {
                    // Skip if already approved for this rank
                    $alreadyApproved = UserRank::where('user_id', $user->id)
                        ->where('rank_slug', $slug)
                        ->where('status','approved')
                        ->exists();
                    $alreadyPending = UserRank::where('user_id', $user->id)
                        ->where('rank_slug', $slug)
                        ->where('status','pending')
                        ->exists();
                    if (!$alreadyApproved && !$alreadyPending) {
                        $rows[] = [
                            'user'   => $user,
                            'rank'   => $info['rank'],
                            'actual' => $info['actual'],
                        ];
                    }
                }
            }
        }
        return view('admin.referral.eligible', compact('rows'));
    }

    /**
     * Auto-create pending UserRank rows for every user that meets a rank criteria.
     * Called by the daily `ranks:check` console command.
     * Returns the number of new applications created.
     */
    public static function autoCreateApplications(): int
    {
        $users = User::where('utype','USR')->get();
        $count = 0;

        foreach ($users as $user) {
            $progress = \App\Http\Controllers\User\ReferralController::rankProgress($user);
            foreach ($progress as $slug => $info) {
                if (!$info['met']) continue;
                $rank = $info['rank'];

                $alreadyApproved = UserRank::where('user_id', $user->id)
                    ->where('rank_slug', $slug)
                    ->where('status','approved')
                    ->exists();
                $alreadyPending = UserRank::where('user_id', $user->id)
                    ->where('rank_slug', $slug)
                    ->where('status','pending')
                    ->exists();
                if ($alreadyApproved || $alreadyPending) continue;

                UserRank::create([
                    'user_id'            => $user->id,
                    'rank_id'            => $rank->id,
                    'rank_name'          => $rank->name,
                    'rank_slug'          => $rank->slug,
                    'rank_level'         => $rank->level,
                    'status'             => 'pending',
                    'detected_at'        => now(),
                    'criteria_snapshot'  => $info['actual'],
                ]);
                $count++;
            }
        }
        return $count;
    }

    /* ===========================================================
     *  RANK SETTINGS (admin-editable criteria)
     * =========================================================== */

    public function rankSettings()
    {
        $ranks = RankSetting::orderedList();
        return view('admin.referral.rank-settings', compact('ranks'));
    }

    public function updateRankSettings(Request $request)
    {
        foreach ($request->input('ranks', []) as $id => $data) {
            $rank = RankSetting::find($id);
            if (!$rank) continue;
            $rank->update([
                'min_active_direct_referrals'           => (int) ($data['min_active_direct_referrals'] ?? 0),
                'min_associates_from_direct'            => (int) ($data['min_associates_from_direct'] ?? 0),
                'min_directors_from_direct'             => (int) ($data['min_directors_from_direct'] ?? 0),
                'min_regional_supervisors_from_direct'  => (int) ($data['min_regional_supervisors_from_direct'] ?? 0),
                'min_direct_referral_investment'        => (float) ($data['min_direct_referral_investment'] ?? 0),
                'min_total_investment'                  => (float) ($data['min_total_investment'] ?? 0),
                'reward_amount'                         => (float) ($data['reward_amount'] ?? 0),
                'reward_percentage'                     => (float) ($data['reward_percentage'] ?? 0),
                'am_min_active_direct_investment_users' => (int) ($data['am_min_active_direct_investment_users'] ?? 10),
                'am_per_user_min_investment'            => (float) ($data['am_per_user_min_investment'] ?? 500000),
                'is_active'                             => isset($data['is_active']),
            ]);
        }
        return back()->with('success','Rank criteria updated.');
    }

    /* ===========================================================
     *  ASSOCIATE MANAGER WEEKLY BONUS (system-triggered)
     * =========================================================== */

    /**
     * Compute & credit weekly Associate Manager bonuses.
     * For each approved Associate Manager, find their 10 qualifying direct refs,
     * sum the referral_bonuses those refs generated this past week (Mon→Sun),
     * and credit 20% of that as a new referral_bonuses row.
     * Returns the count of new bonus rows.
     */
    public static function processAssociateManagerWeekly(): int
    {
        $amRank = RankSetting::where('slug','associate_manager')->first();
        if (!$amRank) return 0;

        $percentage = (float) $amRank->reward_percentage; // 20.00
        if ($percentage <= 0) return 0;

        // Current week (Monday → upcoming Sunday)
        $now = Carbon::now();
        $weekStart = $now->copy()->startOfWeek(Carbon::MONDAY);
        $weekEnd   = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);

        $managers = UserRank::where('status','approved')
            ->where('rank_slug','associate_manager')
            ->pluck('user_id');

        $count = 0;
        foreach ($managers as $managerId) {
            $user = User::find($managerId);
            if (!$user) continue;

            $qualifyingRefs = $user->qualifyingDirectRefsForAssociateManager();
            if (count($qualifyingRefs) < (int) $amRank->am_min_active_direct_investment_users) {
                continue;
            }

            // Sum the referral bonuses these refs generated this week (from their downlines)
            $weeklyBonus = (float) ReferralBonus::whereIn('source_user_id', $qualifyingRefs)
                ->whereBetween('created_at', [$weekStart, $weekEnd])
                ->where('status', '!=', 'reversed')
                ->sum('bonus_amount');

            if ($weeklyBonus <= 0) continue;

            $managerCut = round($weeklyBonus * $percentage / 100, 4);
            if ($managerCut <= 0) continue;

            ReferralBonus::create([
                'user_id'        => $user->id,
                'source_user_id' => $user->id,
                'level'          => 0,
                'percentage'     => $percentage,
                'source_amount'  => $weeklyBonus,
                'bonus_amount'   => $managerCut,
                'week_start'     => ReferralService::nextMonday($now),
                'status'         => 'pending',
                'source'         => 'associate_manager',
                'source_ref'     => 'AM-' . $weekStart->format('Ymd'),
                'notes'          => "AM weekly cut (20%) of $" . number_format($weeklyBonus, 2) . " from " . count($qualifyingRefs) . " qualifying refs",
            ]);
            $count++;
        }
        return $count;
    }
}
