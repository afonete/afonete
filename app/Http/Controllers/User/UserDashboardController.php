<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment as Paymodel;
use App\Models\User;
use App\Models\DailyIncome;
use App\Models\Adventures;
use App\Models\ChartAccount;
use App\Models\Transaction;
use App\Models\Claim;
use Carbon\Carbon;

class UserDashboardController extends Controller{


    public function restrictedFreeAccount(){
        return view("user.unauthorized-free-account");
    }

    /**
     * Package Portfolio — ALL packages the user owns, both product lines:
     * UVP/FC investments AND FOM Licence Miner packages, kept in two
     * separate lists (UVP works its way, FOM works its way).
     * Linked from the dashboard "Account Status" card.
     */
    public function packagePortfolio()
    {
        $user = Auth::user();

        $uvpPackages = Paymodel::where('user', $user->id)
            ->excludeFom()
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'uvp_page');

        $fomPackages = Paymodel::where('user', $user->id)
            ->onlyFom()
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'fom_page');

        $isActive = function ($p) {
            return !$p->is_expired && (string) $p->status === '1';
        };

        $uvpAll = Paymodel::where('user', $user->id)->excludeFom()->get();
        $fomAll = Paymodel::where('user', $user->id)->onlyFom()->get();

        $totals = [
            'uvp_count'         => $uvpAll->count(),
            'uvp_active_count'  => $uvpAll->filter($isActive)->count(),
            'uvp_active_amount' => (float) $uvpAll->filter($isActive)->sum(function ($p) { return (float) ($p->paid ?? $p->amount ?? 0); }),
            'fom_count'         => $fomAll->count(),
            'fom_active_count'  => $fomAll->filter($isActive)->count(),
            'fom_active_amount' => (float) $fomAll->filter($isActive)->sum(function ($p) { return (float) ($p->paid ?? $p->amount ?? 0); }),
        ];

        return view('user.package-portfolio', [
            'uvpPackages' => $uvpPackages,
            'fomPackages' => $fomPackages,
            'totals'      => $totals,
        ]);
    }
    public function upgradeVenturePackage(){
        return view("user.account-upgrade-package-venture");
    }
    public function mypayments() {

        $user = Auth::user();
        $deposits = $user->deposits()->orderBy('created_at', 'desc')->paginate(10, ['*'], 'deposits_page');
        $investments = $user->investments()->orderBy('created_at', 'desc')->paginate(10, ['*'], 'purchases_page');
        return view("user.Mypayments",[
            "deposits" => $deposits,
            "investments" => $investments
        ]);
    }

    private function getAllDownlineUsers($user) {
        $allUsers = collect();
        $queue = collect([$user]); // Initialize with the starting user
    
        while ($queue->isNotEmpty()) {
            $currentUser = $queue->shift(); // Get the first user in the queue
            $directUsers = $currentUser->ownedTeams()->get(); // Get all direct downlines
    
            foreach ($directUsers as $directUser) {
                $allUsers->push($directUser);
                $queue->push($directUser->teamMember); // Push the next level user into the queue
            }
        }
    
        return $allUsers;
    }

    /**
     * Count ALL members (direct + indirect) in one binary leg.
     *
     * The branch is anchored by the root user's DIRECT placements on the
     * given side; every downline reached below those placements belongs to
     * that leg regardless of the side recorded deeper in the tree — the
     * same semantics used by teamStructure() on /user/teambuilding/team-structure.
     * A visited-set guards against referral cycles / duplicate placements.
     */
    private function countBranchMembers($user, string $side): int
    {
        try {
            $visited = [$user->id => true];
            $queue = [];

            $directTeams = \App\Models\Teams::where('user_id', $user->id)
                ->where('side', $side)
                ->get();

            foreach ($directTeams as $t) {
                $memberId = (int) $t->team_user_id;
                if ($memberId && !isset($visited[$memberId])) {
                    $visited[$memberId] = true;
                    $queue[] = $memberId;
                }
            }

            $count = count($queue);

            while (!empty($queue)) {
                $currentId = array_shift($queue);

                $nextTeams = \App\Models\Teams::where('user_id', $currentId)->get();
                foreach ($nextTeams as $nt) {
                    $memberId = (int) $nt->team_user_id;
                    if ($memberId && !isset($visited[$memberId])) {
                        $visited[$memberId] = true;
                        $queue[] = $memberId;
                        $count++;
                    }
                }
            }

            return $count;
        } catch (\Throwable $e) {
            // Never break the dashboard: fall back to direct-only count.
            try {
                return (int) $user->ownedTeams()->where('side', $side)->count();
            } catch (\Throwable $e2) {
                return 0;
            }
        }
    }
    
    
    


    public function index()
    {
        $user = Auth::user();
        if ($user) {
            \App\Models\FomTokenInstallment::processDueInstallments($user);
        }
        $userId = $user->id;
        // does user have claims
        $userHasClaims = $user->have_claims->where("is_fixed",false)->first();
        // refresh user
        $user = User::where("id",$userId)->first();
        $portfolio = 0;

        // TOTAL VOLUME card: Members = TOTAL referrals per leg (direct +
        // indirect). Branch side = side of the direct placement at the root,
        // same semantics as /user/teambuilding/team-structure. Cycle-safe.
        $right = $this->countBranchMembers($user, "RIGHT");
        $left = $this->countBranchMembers($user, "LEFT");
        $allUsers = $this->getAllDownlineUsers($user);

        // FOM binary side volumes (weekly volume bonus per leg, matched on
        // Mondays by referrals:process-weekly). Same source as /user/dashboard/balance
        // ("WEEKLY RIGHT & LEFT VOLUME BONUS") and /user/fom-referral.
        $fomVolLeft  = (float) $user->ChartAccount()->where("acc_type", \App\Services\FomReferralService::ACC_VOL_LEFT)->sum("amount");
        $fomVolRight = (float) $user->ChartAccount()->where("acc_type", \App\Services\FomReferralService::ACC_VOL_RIGHT)->sum("amount");

        // --- FIX: Load package BEFORE any calculations that depend on it ---
        // We order by created_at DESC to load the current newly activated package as the primary package
        // UVP/FC only — FOM Licence Miner payments are a separate product and
        // must never masquerade as the "VENTURE UVP" package on the card.
        $package = Paymodel::where("user",$userId)
                            ->excludeFom()
                            ->where("is_expired",false)
                            ->where("status","1")
                            ->orderBy("created_at", "desc")
                            ->first();

        $mostRecentPayment = $user->investments()
                                  ->excludeFom()
                                  ->where("is_expired",0)
                                  ->where("status",1)
                                  ->orderBy('created_at', 'desc')
                                  ->first();

        // Latest active FOM Licence Miner package (shown separately on the
        // Account Status card — UVP and FOM must both be visible).
        $latestFomPayment = Paymodel::where("user",$userId)
                            ->onlyFom()
                            ->where("is_expired",false)
                            ->where("status","1")
                            ->orderBy("created_at", "desc")
                            ->first();

        // fallback to mostRecentPayment if $package is null
        if (!$package && $mostRecentPayment) {
            $package = $mostRecentPayment;
        }

        // Daily income is now calculated by the scheduler (income:calculate command).
        // $cashout and $shooping remain the CUMULATIVE totals.
        $cashout = (float) $user->ChartAccount()->where("acc_type","CASHOUT")->sum("amount");
        $shooping = (float) $user->ChartAccount()->where("acc_type","TRADING")->sum("amount");

        // Legacy cumulative total
        $dailyIncome = (float) $user->DailyIncomes()->sum("amount");

        // ── Compute the PER-DAY breakdown for ALL active packages combined ──
        // Per spec: Daily ROI = (package_amount × 80%) × (adventures.percentage / 100)
        //   → Cashout (25%): withdrawable anytime, min $10
        //   → Trading Voucher (75%): accumulates, used every 30 days for renewal
        $dailyIncomePerDay = 0.0;
        $dailyCashout      = 0.0;
        $dailyTrading      = 0.0;
        $adventureRow = null;

        if ($package) {
            $adventureRow = \App\Models\Adventures::find($package->payable_id);
        }

        // UVP/FC only — FOM licences don't generate UVP daily ROI.
        $activePackages = Paymodel::where("user", $userId)
                                  ->excludeFom()
                                  ->where("is_expired", false)
                                  ->where("status", "1")
                                  ->get();

        if ($activePackages->isEmpty() && $package) {
            $activePackages = collect([$package]);
        }

        foreach ($activePackages as $p) {
            $pAdv = null;
            if ($p->payable_type && $p->payable_id) {
                $pAdv = $p->payable_type::find($p->payable_id);
            }
            if (!$pAdv && $p->payable_id) {
                $pAdv = \App\Models\Adventures::find($p->payable_id);
            }
            if (!$pAdv && $p->package) {
                $pAdv = \App\Models\Adventures::where('name', $p->package)->first();
            }
            if (!$pAdv && (float)($p->paid ?? $p->amount ?? 0) > 0) {
                $pPaid = (float) ($p->paid ?? $p->amount ?? 0);
                $pAdv = \App\Models\Adventures::where('min_amount', '<=', $pPaid)
                    ->where('max_amount', '>=', $pPaid)
                    ->first();
            }

            $packagePaid = (float) ($p->paid ?? $p->amount ?? 0);
            $percentage = $pAdv && (float)$pAdv->percentage > 0 ? (float)$pAdv->percentage : 2.5;

            $poolCapital = $packagePaid * 80 / 100;
            $pkgRate = $poolCapital * ($percentage / 100);

            $dailyIncomePerDay += $pkgRate;
        }

        $dailyCashout      = $dailyIncomePerDay * 25 / 100;
        $dailyTrading      = $dailyIncomePerDay * 75 / 100;

        // Contract gate - aligned with contract middleware
        $paidPackage = strtolower(trim((string) $user->has_paid_package));
        $isFreeUser = ($paidPackage === 'no' || $paidPackage === 'standard' || $paidPackage === '');
        if (!$isFreeUser && $user->contract !== 'Signed') {
            return redirect()->route("user.contract");
        }

        // Deposits summary — AVAILABLE deposit balance (approved − used).
        // Self-heal first: historic FOM purchases debited only the DEPOSIT
        // wallet without a 'used' ledger row, which made this formula report
        // the TOTAL deposit as available. Backfill is idempotent per purchase.
        \App\Models\Deposits::ensureFomPurchaseLedgerRows($user->id);
        $user->load('deposits');

        $deposits = $user->deposits->filter(function ($deposit) {
            return $deposit->status == 'approved';
        });

        $deposits_used = $user->deposits->filter(function ($deposit) {
            return $deposit->status == 'used';
        });
        $deposits_pending = $user->deposits->where('status', 'pending');
        $differences = max(0, $deposits->sum('amount_deposited') - $deposits_used->sum('amount_removed'));
        $sum = $differences;

        $credit = 0;
        $credit_status = '';

        $ftcurrentPortfolio = $user->currentPortfolio()->where("usage","CURRENT")->where("packagename","FT");
        $tmcurrentPortfolio = $user->currentPortfolio()->where("usage","CURRENT")->where("packagename","TM");
        $venturcurrentPortfolio = $user->currentPortfolio()->where("usage","CURRENT")->where("packagename","FT");

        // Portfolio value - safe null handling
        switch($user->has_paid_package){
            case 'ft':
            case 'FT':
            case 'VENTURE':
            case 'yes':
                $portfolio = $package ? (float)$package->paid : 0;
                break;
            case 'TM':
            case 'tm':
                $p = $user->have_activation_code ?? null;
                if ($p) {
                    $portfolio = (float) ($p->price ?? 0);
                }
                break;
            case 'TEAM_LEADER':
            case 'SUPER_LEADER':
                // ── Team Leader / Super Leader dashboard metrics ──
                $activation = $user->have_activation_code ?? null;
                if ($activation) {
                    $portfolio = (float) ($activation->price ?? 0);
                }
                break;
            default:
                $portfolio = $package ? (float)$package->paid : 0;
        }

        // ── Universal Super Leader & Activation Credit Resolution ──
        $slCredit = \App\Models\SuperLeaderCredit::where('user_id', $user->id)->first();
        if (!$slCredit) {
            $teamLeader = \App\Models\TeamLeader::where('User_name', $user->user)
                ->orWhere('Email', $user->email)
                ->first();
            if ($teamLeader) {
                $slCredit = $teamLeader->superLeaderCredit;
            }
        }
        if (!$slCredit) {
            $activation = \App\Models\Activations::where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->whereIn('package', ['SUPER_LEADER', 'TEAM_LEADER'])
                ->first();
            if ($activation) {
                $slCredit = \App\Models\SuperLeaderCredit::where('activation_id', $activation->id)->first();
            }
        }

        if ($slCredit) {
            $credit = (float) $slCredit->credit_amount;
            $credit_status = $slCredit->status; // 'active', 'pending', 'deactive'

            // If active, process super leader credits (turnover rewards, auto-withdrawals, pending cashouts)
            if ($slCredit->status === 'active') {
                try {
                    \Illuminate\Support\Facades\Artisan::call('credits:process-super-leaders');
                } catch (\Throwable $e) {}
            }
        } else {
            // Fallback to legacy activation credit / myCredit / credit_conditions
            $activation = $user->have_activation_code 
                ?? \App\Models\Activations::where('user_id', $user->id)->orWhere('email', $user->email)->first();
            if ($activation) {
                if (isset($activation->myCredit) && $activation->myCredit) {
                    $credit = (float) ($activation->myCredit->amount ?? 0);
                    $credit_status = $activation->myCredit->status ?? '';
                } elseif (!empty($activation->credit_conditions)) {
                    $conds = json_decode($activation->credit_conditions, true) ?: [];
                    if (isset($conds['credit_amount'])) {
                        $credit = (float) $conds['credit_amount'];
                        $credit_status = $conds['credit_status'] ?? 'pending';
                    }
                }
            }
        }

        $ranks = [
            "isAssociate"=>$this->isAssociate(Auth::User()),
            "isDirector"=>$this->isDirector(Auth::User()),
            "isRegionalSupervisor"=>$this->isRegionalSupervisor(Auth::User()),
            "isRegionalVicePresident"=>$this->isRegionalVicePresident(Auth::User()),
            "associateManager"=>$this->isAssociateManager(Auth::User()),
        ];

        // FIX: $mypackage was undefined – use $package
        $createdDate = $package ? $package->created_at : now();
        $today = Carbon::now();
        // Calculate the difference in days
        $daysGone = $createdDate ? Carbon::parse($createdDate)->diffInDays($today) : 0;
        $earnings = (float) $user->earnings->sum("amount");

        $ChartAccount = $user->ChartAccount()->where("acc_type","TRADING")->first();

        // ── 1. UVP Package Tokens (Bottom 4 light cards) ──
        $uvpLockedToken = (float) $user->ChartAccount()->where("acc_type", "LOCKED_TOKEN")->sum("amount");

        // Fallback for active UVP packages if ChartAccount LOCKED_TOKEN is 0
        if ($uvpLockedToken <= 0 && isset($activePackages) && $activePackages->isNotEmpty()) {
            $uvpPrice = \App\Models\TokenSetting::uvpPrice();
            if ($uvpPrice > 0) {
                foreach ($activePackages as $actPkg) {
                    $paidAmt = (float) ($actPkg->paid ?? 0);
                    if ($paidAmt > 0) {
                        $uvpLockedToken += round($paidAmt / $uvpPrice, 4);
                    }
                }
            }
        }

        $uvpFreeToken      = (float) $user->ChartAccount()->where("acc_type", "FREE_TOKEN")->sum("amount");
        $uvpAvailableToken = (float) $user->ChartAccount()->where("acc_type", "AVAILABLE_TOKEN")->sum("amount");

        $alreadyReleasedLeaderTokens = \App\Models\TeamLeaderTokenRelease::releasedTokensForUser($user->id);
        $netUvpAvailableToken        = max(0, $uvpAvailableToken - $alreadyReleasedLeaderTokens);
        $uvpTotalTokens             = (float) ($uvpLockedToken + $netUvpAvailableToken + $uvpFreeToken);

        // ── 2. Team Leader Tokens (Top dark box) ──
        $leaderLockedToken = 0.0;
        $activation = $user->have_activation_code ?? null;

        if ($activation && (float)($activation->token ?? 0) > 0) {
            $leaderLockedToken = (float) $activation->token;
        } else {
            $bal = \App\Models\balance::where('user', $user->id)->first();
            if ($bal) {
                $leaderLockedToken = (float) ($bal->reserved_token ?? 0);
            }
        }

        $leaderRemainingLocked = max(0, $leaderLockedToken - $alreadyReleasedLeaderTokens);
        $leaderTotalTokens     = (float) $leaderLockedToken;

        // ── 3. Combined Grand Total Tokens ──
        // Total UVP tokens of all user packages + Total of team leaders token
        $grandTotalTokens = (float) ($uvpTotalTokens + $leaderTotalTokens);

        $teamLeaderRecord = \App\Models\TeamLeader::where('User_name', $user->user)->first();
        $isTeamLeader     = in_array($user->has_paid_package, ['TEAM_LEADER', 'SUPER_LEADER']) || ($teamLeaderRecord && $teamLeaderRecord->status === 'confirmed');
        // GAS_FEE is admin-only — not read here
        $COMMISSION = (float) $user->ChartAccount()->where("acc_type","COMMISSION")->sum("amount");

        // ── Referral bonus breakdown for dashboard ──
        $referralBonusTotals = \App\Models\ReferralBonus::totalsForUser($user->id);
        $directReferralCount   = $user->referrals()->count();
        $activeReferralCount   = $user->referrals()->whereHas('investments', function ($q) {
            $q->where('status', 1)->where('category', 'VENTURE');
        })->count();
        // Direct bonus earned = sum of all L1 referral_bonus rows
        $directBonusEarned = (float) \App\Models\ReferralBonus::where('user_id', $user->id)
            ->where('level', 1)
            ->where('status', '!=', 'reversed')
            ->sum('bonus_amount');
        $directBonusEarned = round($directBonusEarned, 2);

        // ── Current rank + next rank progress ──
        $currentRank    = $user->currentRank();
        $nextRankInfo   = null;
        foreach (\App\Models\RankSetting::orderedList() as $r) {
            if ($currentRank && $r->level <= $currentRank->rank_level) continue;
            $nextRankInfo = $r; break;
        }

        $purchaseDate = $ChartAccount && $ChartAccount->created_at
            ? Carbon::parse($ChartAccount->created_at)
            : ($package ? Carbon::parse($package->created_at) : Carbon::now());

        $expirationDate = null;
        $show = false;

        if ($mostRecentPayment && $mostRecentPayment->category == "VENTURE") {
            // Use the package's actual expiration_date
            $expirationDate = $package && $package->expiration_date
                ? Carbon::parse($package->expiration_date)
                : $purchaseDate->copy()->addDays(100);
            $show = true;
        }

        // ── Package expiry state ──
        // Team leaders don't expire — their access is tied to activation, not a timed package.
        $isLeaderPackage = in_array($user->has_paid_package, ['TEAM_LEADER', 'SUPER_LEADER']);
        $packageExpired = $isLeaderPackage ? false : (!$package || ($package->is_expired ?? true));

        // If the package just expired, make sure has_paid_package is reset
        // (but NEVER reset TEAM_LEADER / SUPER_LEADER packages)
        if ($packageExpired && $user->has_paid_package !== 'no' && !$isLeaderPackage) {
            $user->has_paid_package = 'no';
            $user->save();
        }

        // ── Renewal due state ──
        $renewalDue    = false;
        $renewalNumber = 0;
        $maxRenewals   = 0;
        $pkgDuration   = $package && isset($adventureRow) && $adventureRow ? (int)$adventureRow->duration : 100;

        if ($package && !$packageExpired) {
            $pkgStart    = Carbon::parse($package->created_at);
            $daysSince   = (int) $pkgStart->diffInDays(Carbon::now());
            $renewalsDone = \App\Models\PackageRenewal::where('user_id', $userId)
                                ->where('payment_id', $package->id)
                                ->count();

            // Get duration from the adventure package
            $activePkg2   = $adventureRow ?: \App\Models\Adventures::find($package->payable_id);
            $pkgDuration  = $activePkg2 ? (int) $activePkg2->duration : 100;
            $maxRenewals  = \App\Services\RenewalCalculator::maxRenewals($pkgDuration);

            $nextRenewalNum       = $renewalsDone + 1;
            $nextRenewalThreshold = $nextRenewalNum * 30;

            if ($renewalsDone < $maxRenewals && $daysSince >= ($nextRenewalThreshold - 1)) {
                $renewalDue    = true;
                $renewalNumber = $nextRenewalNum;
            }
        } else {
            // defaults to avoid undefined variable in view
            $maxRenewals = 3;
            $pkgDuration = 100;
        }

        $comm = $this->commissions();

        // Also pass raw deposit balance for "Cash & Deposits" card clarity
        $depositBalance = (float) $sum;
        $cashoutBalance = $cashout;

        // Initial Left and Right Referral Bonus Earnings (all_time)
        $allTimeBonuses = \App\Models\ReferralBonus::where('user_id', $user->id)
            ->whereNotIn('status', ['reversed', 'expired'])
            ->get();

        $leftEarning  = 0.0;
        $rightEarning = 0.0;

        foreach ($allTimeBonuses as $b) {
            $sourceUserId = $b->source_user_id;
            if (!$sourceUserId) continue;

            $side = $this->determineRootTeamSide($user->id, $sourceUserId);

            if ($side === 'LEFT') {
                $leftEarning += (float) $b->bonus_amount;
            } elseif ($side === 'RIGHT') {
                $rightEarning += (float) $b->bonus_amount;
            }
        }

        return view('user.dashboard',
        [
            "mypackage"              => $package,
            // Latest active FOM Licence Miner package (Account Status card)
            "my_fom_package"         => $latestFomPayment,
            "fom_package_name"       => $latestFomPayment ? strtoupper(trim((string) ($latestFomPayment->category ?: $latestFomPayment->package ?: 'FOM'))) : null,
            "fom_package_paid"       => $latestFomPayment ? (float) ($latestFomPayment->paid ?? $latestFomPayment->amount ?? 0) : 0,
            "fom_package_expired"    => $latestFomPayment ? (bool) $latestFomPayment->is_expired : false,
            "active_packages_list"   => $activePackages,
            "active_packages_count"  => $activePackages->count(),
            "show_timer"             => $show,
            "deposits"               => number_format($depositBalance,2),
            "deposit_raw"            => $depositBalance,
            "left_earning"           => $leftEarning,
            "right_earning"          => $rightEarning,
            "ranks"                  => $ranks,
            "amount"                 => 0,
            // Token balances
            "uvp_locked"             => $uvpLockedToken,
            "uvp_available"          => $netUvpAvailableToken,
            "uvp_free"               => $uvpFreeToken,
            "uvp_total"              => $uvpTotalTokens,
            "leader_locked"          => $leaderRemainingLocked,
            "leader_initial_locked"  => $leaderLockedToken,
            "leader_released"        => $alreadyReleasedLeaderTokens,
            "leader_total"           => $leaderTotalTokens,
            "grand_total_tokens"     => $grandTotalTokens,

            // Legacy & Card mapping
            "locked"                 => $uvpLockedToken,
            "free_token"             => $uvpFreeToken,
            "available_token"        => $netUvpAvailableToken,
            "total_tokens"           => $grandTotalTokens,
            "is_team_leader"         => $isTeamLeader,
            "fcoin"                  => number_format($uvpFreeToken, 0),
            "commission"             => $COMMISSION,
            "referral_bonus_totals"  => $referralBonusTotals,
            "current_rank"           => $currentRank,
            "next_rank"              => $nextRankInfo,
            "direct_referral_count"  => $directReferralCount,
            "active_referral_count"  => $activeReferralCount,
            "direct_bonus_earned"    => $directBonusEarned,
            "gasfees"                => 0,
            "pool"                   => 0,
            "dailyIncome"            => "$".number_format($dailyIncome,2),
            "cashout"                => "$".number_format($cashoutBalance,2),
            "cashout_raw"            => $cashoutBalance,
            "shooping"               => "$".number_format($shooping,2),
            "trading_raw"            => $shooping,
            // Per-day breakdown
            "daily_income_per_day"   => number_format($dailyIncomePerDay, 2),
            "daily_cashout"          => number_format($dailyCashout, 2),
            "daily_trading"          => number_format($dailyTrading, 2),
            "max_renewals"           => $maxRenewals,
            "pkg_duration"           => $pkgDuration,
            "daysgone"               => $daysGone,
            "credit"                 => $credit,
            "portfolio"              => "$".number_format($portfolio,2),
            "portfolio_raw"          => $portfolio,
            "credit_status"          => $credit_status,
            "right"                  => $right,
            "left"                   => $left,
            "fom_vol_left"           => $fomVolLeft,
            "fom_vol_right"          => $fomVolRight,
            "left_direct_uvp"        => $comm['left_direct_uvp'] ?? 0,
            "left_indirect_uvp"      => $comm['left_indirect_uvp'] ?? 0,
            "right_direct_uvp"       => $comm['right_direct_uvp'] ?? 0,
            "right_indirect_uvp"     => $comm['right_indirect_uvp'] ?? 0,
            "zoneAearning"           => $comm['zoneA'],
            "zoneBearning"           => $comm['zoneB'],
            "have_pending_deposit"   => $deposits_pending,
            "expirationDate"         => $expirationDate,
            "I_have_claim"           => $userHasClaims,
            "referals"               => $allUsers->count(),
            // Package lifecycle
            "package_expired"        => $packageExpired,
            "renewal_due"            => $renewalDue,
            "renewal_number"         => $renewalNumber,
            // Extra helpers for blade
            "package_name"           => in_array($user->has_paid_package, ['TEAM_LEADER', 'SUPER_LEADER'])
                                        ? $user->has_paid_package
                                        : ($adventureRow ? $adventureRow->name : ($package ? 'VENTURE' : 'FREE')),
            "package_paid"           => $package ? (float)$package->paid : 0,
            "package_currency"       => $adventureRow->currency ?? 'USD',
        ]);
    }





    private function getIndirectUvpInvestors($user, $side) {
        $directUsers = $user->ownedTeams()->where('side', $side)->get();
        $indirectUvpCount = 0;
        $zone = 0;
        $d = [];

        // dd($user);
        foreach ($directUsers as $directUser) {
            $indirectUsers = $this->findIndirectUsers($directUser->teamMember, $side);

            foreach ($indirectUsers as $indirectUser) {
                 $individualUser = $indirectUser->teamMember;
                //  dd($indirectUser);
                $hasUvpInvestment = $individualUser->investments()->where('category', 'VENTURE')->exists();
                $zone += $indirectUser->teamMember->ChartAccount()
                                        ->where("acc_type","COMMISSION")
                                        ->sum("amount");


                if ($hasUvpInvestment) {
                    $indirectUvpCount++;
                }

            }
        }



        return [
            "indirectUvpCount"=>$indirectUvpCount,
            "zone"=>$zone
        ];
    }

    private function findIndirectUsers($user, $side) {
        $indirectUsers = collect();
        $queue = collect([$user]); // Initialize the queue with the starting user

        while ($queue->isNotEmpty()) {
            $currentUser = $queue->shift(); // Get the first user in the queue
            $referredUsers = $currentUser->ownedTeams()->where('side', $side)->get();

            foreach ($referredUsers as $referredUser) {
                $indirectUsers->push($referredUser);
                $queue->push($referredUser->teamMember);
            }
        }

        return $indirectUsers;
    }


    private function getDirectUvpInvestors($teamMembers, $packageType) {
        $directUvpCount = 0;
        $earnings = 0;

        // dd($teamMembers);
        foreach ($teamMembers as $teamMember) {
            // dd($teamMember->teamMember);
            $du = $teamMember->teamMember;

            $hasUvpInvestment = $du->investments()->where('category', 'VENTURE')->exists();
            $earns = $du->ChartAccount()->where("acc_type","COMMISSION")->sum("amount");
            $earnings += $earns;
            if ($hasUvpInvestment) {
                $directUvpCount++;
            }
        }

        return [
                 "directUvpCount"=>$directUvpCount,
                 "earnings"=>$earns
            ];
    }


    private function determineRootTeamSide(int $rootUserId, int $targetUserId): ?string
    {
        $current = User::find($targetUserId);
        $side = null;

        while ($current && $current->referee_id && (int)$current->referee_id !== (int)$rootUserId) {
            if ($current->teamSide) {
                $side = $current->teamSide->side;
            }
            $current = User::find($current->referee_id);
        }

        if ($current && (int)$current->referee_id === (int)$rootUserId && $current->teamSide) {
            return $current->teamSide->side;
        }

        return $side;
    }

    private function getPeriodDates($period): array
    {
        switch ($period) {
            case 'this_week':
                return [now()->startOfWeek(), now()->endOfWeek()];

            case 'last_week':
                return [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()];

            case 'this_month':
                return [now()->startOfMonth(), now()->endOfMonth()];

            case 'last_month':
                return [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()];

            case 'this_year':
                return [now()->startOfYear(), now()->endOfYear()];

            case 'last_year':
                return [now()->subYear()->startOfYear(), now()->subYear()->endOfYear()];

            case 'all_time':
            default:
                return [\Carbon\Carbon::parse('2020-01-01')->startOfDay(), now()->endOfCentury()];
        }
    }

    public function fetchCommissions(Request $request)
    {
        $user   = Auth::user();
        $period = $request->query('period', 'all_time');

        // Ensure missing referral bonuses are synced
        \App\Services\ReferralService::syncMissingBonuses();

        $dates     = $this->getPeriodDates($period);
        $startDate = $dates[0];
        $endDate   = $dates[1];

        $bonusesQuery = \App\Models\ReferralBonus::where('user_id', $user->id)
            ->whereNotIn('status', ['reversed', 'expired']);

        if ($period !== 'all_time') {
            $bonusesQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $bonuses = $bonusesQuery->get();

        $leftEarned  = 0.0;
        $rightEarned = 0.0;

        foreach ($bonuses as $b) {
            $sourceUserId = $b->source_user_id;
            if (!$sourceUserId) continue;

            $side = $this->determineRootTeamSide($user->id, $sourceUserId);

            if ($side === 'LEFT') {
                $leftEarned += (float) $b->bonus_amount;
            } elseif ($side === 'RIGHT') {
                $rightEarned += (float) $b->bonus_amount;
            }
        }

        return response()->json([
            'left'      => number_format($leftEarned, 2, '.', ''),
            'right'     => number_format($rightEarned, 2, '.', ''),
            'left_raw'  => $leftEarned,
            'right_raw' => $rightEarned,
        ]);
    }
    private  function commissions(){
        $user   =   Auth::User();
        $myteam = $user->ownedTeams();
        $right  = $myteam->where("side","RIGHT")->get();
        $left   = $myteam->where("side","LEFT")->get();
        $left_direct_uvp=$this->getDirectUvpInvestors($left,"VENTURE");     //done
        $left_indirect_uvp=$this->getIndirectUvpInvestors($user,"LEFT");    //done
        $right_direct_uvp=$this->getDirectUvpInvestors($right,"VENTURE");   //done
        $right_indirect_uvp=$this->getIndirectUvpInvestors($user,"RIGHT"); //done

        // dd($right_indirect_uvp);
       return  [
            "left_direct_uvp"=>$left_direct_uvp['directUvpCount'],
            "left_indirect_uvp"=>$left_indirect_uvp['indirectUvpCount'],
            "right_direct_uvp"=>$right_direct_uvp['directUvpCount'],
            "right_indirect_uvp"=>$right_indirect_uvp['indirectUvpCount'],
            "zoneA"=>$left_direct_uvp['earnings'] + $right_indirect_uvp['zone'] ,
            "zoneB"=>$right_direct_uvp['earnings'] + $right_indirect_uvp['zone']
        ];

    }
    public function calculatePackageMetrics($package)
    {
        // The amount paid for the package
        $amount = $package->paid;

        // Calculate Fcoin (uses admin-configurable UVP price from token_settings)
        $uvpPrice = \App\Models\TokenSetting::uvpPrice();
        $fcoin = $uvpPrice > 0 ? round($amount / $uvpPrice, 4) : 0;
        // Calculate percentages of the amount
        $percent20 = ($amount * 20 / 100); 
        $percent80 = ($amount * 80 / 100);

        // Gas fees are 20% of the amount
        $gasFees = $percent20;

        // Pool capital is 80% of the amount
        $poolCapital = $percent80;

        // 2% of the pool capital
        $twoPercentageOfPoolCapital = $poolCapital * 2 / 100;

        // Daily income is 2% of the pool capital
        $dailyIncome = $twoPercentageOfPoolCapital;

        // 25% of daily income for cashout
        $t5percentageOfDailyIncome = $dailyIncome * 25 / 100;
        $cashout = $t5percentageOfDailyIncome;

        // 75% of daily income for shopping
        $t75PercentOfDailyIncome = $dailyIncome * 75 / 100;
        $shopping = $t75PercentOfDailyIncome;

        // The date the package was created
        $createdDate = $package->created_at;

        // Return all calculated values as an associative array
        return [
            'fcoin' => $fcoin,
            'gasFees' => $gasFees,
            'poolCapital' => $poolCapital,
            'dailyIncome' => $dailyIncome,
            'cashout' => $cashout,
            'shopping' => $shopping,
            'createdDate' => $createdDate,
        ];
    }



    /**
     * Calculate and record any missing daily income entries for a user.
     *
     * Rules:
     *  1. Only runs for active VENTURE packages (is_expired=0, status=1).
     *  2. Hard stops at the package expiration_date (max 100 days).
     *  3. Pauses during any renewal window where the user has NOT yet renewed:
     *       - Day 30-59: paused until renewal #1 is recorded
     *       - Day 60-89: paused until renewal #2 is recorded
     *       - Day 90+:   paused until renewal #3 is recorded
     *     Income for paused days is permanently skipped (not back-filled).
     *  4. Fixes copy-paste bug: CASHOUT now uses $beforeCashout, not $beforeTrading.
     */
    public function showDailyIncome($userId)
    {
        // Get ALL active VENTURE packages for the user
        $packages = Paymodel::where("category", "VENTURE")
                            ->where("user", $userId)
                            ->where("is_expired", 0)
                            ->where("status", 1)
                            ->get();

        if ($packages->isEmpty()) {
            return;
        }

        $user = User::find($userId);
        if (!$user) return;
        $now  = Carbon::now();

        foreach ($packages as $package) {
            $package2 = null;
            if ($package->payable_type && $package->payable_id) {
                $package2 = $package->payable_type::find($package->payable_id);
            }
            if (!$package2 && $package->payable_id) {
                $package2 = Adventures::find($package->payable_id);
            }
            if (!$package2 && $package->package) {
                $package2 = Adventures::where('name', $package->package)->first();
            }
            if (!$package2 && (float)($package->paid ?? $package->amount ?? 0) > 0) {
                $pPaid = (float) ($package->paid ?? $package->amount ?? 0);
                $package2 = Adventures::where('min_amount', '<=', $pPaid)
                    ->where('max_amount', '>=', $pPaid)
                    ->first();
            }

            if (!$package2 || !$package->expiration_date) {
                continue;
            }

            $percentcharge  = (float) $package2->percentage;
            $amount         = (float) ($package->paid ?? $package->amount ?? 0);
            $startDate      = Carbon::parse($package->created_at);
            $expirationDate = Carbon::parse($package->expiration_date);

            $ceiling    = $expirationDate->lt($now) ? $expirationDate : $now;
            $daysPassed = (int) $startDate->diffInDays($ceiling);

            $renewalsDone = \App\Models\PackageRenewal::where("user_id", $userId)
                                ->where("payment_id", $package->id)
                                ->orderBy("renewal_number")
                                ->get();

            $renewalCompletedAt = [];
            foreach ($renewalsDone as $renewal) {
                $renewalCompletedAt[$renewal->renewal_number] = Carbon::parse($renewal->renewed_at);
            }

            $packageDuration = (int) $package2->duration;
            $maxRenewals     = (int) floor(($packageDuration - 1) / 30);

            for ($i = 1; $i <= $daysPassed; $i++) {
                $earnedAt = $startDate->copy()->addDays($i);

                if ($earnedAt->gt($expirationDate)) {
                    break;
                }

                $neededRenewal = \App\Services\RenewalCalculator::renewalsRequiredForDay($i);
                $blocked = false;
                if ($neededRenewal > 0 && $neededRenewal <= $maxRenewals) {
                    if (!isset($renewalCompletedAt[$neededRenewal]) ||
                        $earnedAt->lt($renewalCompletedAt[$neededRenewal])) {
                        $blocked = true;
                    }
                }

                if ($blocked) {
                    continue;
                }

                $incomeExists = DailyIncome::where("user_id", $userId)
                                            ->where("payment_id", $package->id)
                                            ->whereDate("earned_at", $earnedAt->toDateString())
                                            ->exists();
                if ($incomeExists) {
                    continue;
                }

                $poolCapital = $amount * 80 / 100;
                $dailyIncome = $poolCapital * $percentcharge / 100;
                $trading     = $dailyIncome * 75 / 100;
                $cashout     = $dailyIncome * 25 / 100;

                DailyIncome::create([
                    "user_id"    => $userId,
                    "payment_id" => $package->id,
                    "amount"     => $dailyIncome,
                    "earned_at"  => $earnedAt,
                ]);

                $transactionNo = Transaction::generateTransactionNo();
                Transaction::create([
                    "user_id"             => $userId,
                    "transaction_no"      => $transactionNo,
                    "transaction_type"    => "INCOME",
                    "receiver_id"         => 0,
                    "transaction_details" => json_encode([
                        "type"            => "UVP",
                        "user"            => $user->name,
                        "date"            => $earnedAt->toDateTimeString(),
                        "cash_25"         => $cashout,
                        "trading_75"      => $trading,
                        "amount"          => $dailyIncome,
                        "trx_name"        => "UVP INCOME",
                        "description"     => "Payment From Pool Capital",
                        "day_number"      => $i,
                        "status"          => "success",
                        "username"        => $user->name,
                        "leadership_bonus"=> 0,
                    ]),
                ]);

                $beforeCashout = $user->ChartAccount()->where("acc_type", "CASHOUT")->sum("amount");
                $beforeTrading = $user->ChartAccount()->where("acc_type", "TRADING")->sum("amount");

                ChartAccount::updateOrCreate(
                    ["user_id" => $userId, "acc_type" => "TRADING"],
                    ["amount"  => $beforeTrading + $trading]
                );

                ChartAccount::updateOrCreate(
                    ["user_id" => $userId, "acc_type" => "CASHOUT"],
                    ["amount"  => $beforeCashout + $cashout]
                );
            }
        }
    }


    public function investments(User $user)
    {
        return $user->investments;
    }

    public function getTotalInvestmentForVentures(User $user)
    {
        return $this->investments($user)->where('category', 'VENTURE')->sum('amount');
    }



    private function referrals (User $user){
        return  $user->referrals;

     }


    public function calculateTeamTurnoverzczczcc()
    {
        $r= $this->activeDirectReferralsForVentures();
        $a = $r->sum(function ($referral) {
            $d = $referral->investments()->where("category","VENTURE")->where("is_expired","0")->sum("amount");
            //  dd($d);
            return $d;
        });



        // This block was dead code with a dd() left in — removed to prevent crash
        // return $directReferralInvestments + $indirectReferralInvestments;
    }

    public function getTeamTurnoverForVentures(){}

    public function calculateTeamTurnover(User $user)
    {
        return $this->calculateUserTeamTurnover($user);
    }

    protected function calculateUserTeamTurnover(User $user)
    {
        $totalInvestment = 0;
        // dd($user->referrals[]);

        foreach ($user->referrals as $referral) {
            $referralInvestment = $referral->investments()
                ->where("category", "VENTURE")
                ->where("is_expired", "0")
                ->sum("amount");

                // dd($referral->referrals[0]->referrals);

                    if($referral->referrals){
                        //  echo $referral->name ."  ".$referral->id." invested => ".$referralInvestment."<br/>";
                        //  $this->calculateUserTeamTurnover($referral);
                         $totalInvestment += $referralInvestment;
                         $totalInvestment += $this->calculateUserTeamTurnover($referral);
                        //  $totalInvestment += $this->calculateUserTeamTurnover($referral);
                    }
                    else{
                        $totalInvestment += $referralInvestment;

                        // echo  "level2= ".$referral->id."  invested => ".$referralInvestment."<br/>";

                    }


        }


        return $totalInvestment;
    }


    public function activeDirectReferralsForVentures(User $user)
    {
        $referrals = $user->referrals;
        $r = $referrals->filter(function ($referral) {
            // dd($referral->investments()->where("category","VENTURE"));
            return $referral->investments()->where('category', 'VENTURE')->exists();
        });
        return $r;
    }

    public function isAssociate(User $user)
    {
        // Check personal investment
        if ($this->getTotalInvestmentForVentures($user) < 1000) {

            return false;
        }

        // Check active direct referrals
        $activeDirectReferrals = $this->activeDirectReferralsForVentures($user);

        if ($activeDirectReferrals->count() < 5) {
            return false;

        }

        // Check total investment of direct referrals
        // dd($activeDirectReferrals);
        // $activeDirectReferrals->map(function ($r){
        //     echo $r->id."<br/>";
        // });

        $directReferralInvestment = $activeDirectReferrals->sum(function ($referral) {
            $d = $referral->investments()->where("category","VENTURE")->where("is_expired","0")->sum("amount");
            //  dd($d);

            return $d;
        });


        if ($directReferralInvestment < 10000 || $directReferralInvestment >= 50000) {

            return false;
        }

        // Check team turnover
        // $teamTurnover = $this->getTeamTurnoverForVentures();

        $teamTurnover = $this->calculateTeamTurnover($user);

        if ($teamTurnover < 100000 || $teamTurnover >= 500000) {

            return false;
        }

        return true;
    }




    public function isDirector(User $user)
    {
        // Check personal investment
        $personalInvestment = $this->getTotalInvestmentForVentures($user);

        if ($personalInvestment < 10000 || $personalInvestment >= 25000) {
            return false;
        }

        // Check for 3 active associates from different lines
        $activeAssociates =
        $user->referrals->filter(function ($referral) {


            return $this->isAssociate($referral);
        });



        if ($activeAssociates->count() < 3) {
            return false;
        }

        // Check total investment of direct referrals
        // $directReferralInvestment = $activeAssociates->sum(function ($referral) {
        //     $d = $referral->investments()->where("category","VENTURE")->where("is_expired","0")->sum("amount");
        //     //  dd($d);
        //     return $d;

        // });

        $activeDirectReferrals = $this->activeDirectReferralsForVentures($user);
        $directReferralInvestment =
        $activeDirectReferrals->sum(function ($referral) {
            $d = $referral->investments()->where("category","VENTURE")->where("is_expired","0")->sum("amount");

            return $d;
        });



        if ($directReferralInvestment < 50000 || $directReferralInvestment >= 150000) {
            return false;
        }

        // Check team turnover
        // $teamTurnover = $this->getTeamTurnoverForVentures();
        $teamTurnover = $this->calculateTeamTurnover($user);
        if ($teamTurnover < 500000 || $teamTurnover >= 2000000) {
            return false;
        }

        return true;
    }



    public function isRegionalSupervisor(User $user)
    {
        $personalInvestment = $this->getTotalInvestmentForVentures($user);

        if ($personalInvestment < 25000 || $personalInvestment >= 100000) {
            return false;
        }
        $activeDirectors =  $user->referrals->filter(function ($referral) {
            return $this->isDirector($referral);
        });

        if ($activeDirectors->count() < 3) {
            return false;
        }


        $activeDirectReferrals = $this->activeDirectReferralsForVentures($user);

        $directReferralInvestment = $activeDirectReferrals->sum(function ($referral) {
            $d = $referral->investments()->where("category","VENTURE")->where("is_expired","0")->sum("amount");

            return $d;
        });


        // if ($directReferralInvestment < 150000 || $directReferralInvestment >= 500000) {
        //     return false;
        // }


        if ($directReferralInvestment < 10000 || $directReferralInvestment >= 500000) {
            return false;
        }

        $teamTurnover = $this->calculateTeamTurnover($user);
        // dd($teamTurnover);

        if ($teamTurnover < 2000000 || $teamTurnover >= 50000000) {
            return false;
        }

        return true;
    }



    public function isRegionalVicePresident(User $user)
    {
        // Check Personal Investment
        $personalInvestment = $this->getTotalInvestmentForVentures($user);
        if ($personalInvestment < 1000000) {
            return false;
        }
        // Check Active Regional Supervisors
        $activeRegionalSupervisors = $user->referrals->filter(function ($referral) {
            return $this->isRegionalSupervisor($referral);
        });

        // if ($activeRegionalSupervisors->count() < 5) {
        //     return false;
        // }

        if ($activeRegionalSupervisors->count() <= 0) {
            return false;
        }

        // Check Personal Turnover
        $activeDirectReferrals = $this->activeDirectReferralsForVentures($user); // personal Turnover

        $personalTurnover = $activeDirectReferrals->sum(function ($referral) {
            $d = $referral->investments()->where("category","VENTURE")->where("is_expired","0")->sum("amount");
            return $d;
        });



        if ($personalTurnover < 5000) { // for faster testing
            return false;
        }

        // if ($personalTurnover < 500000) {
        //     return false;
        // }

        // Check Team Turnover
        $teamTurnover = $this->calculateTeamTurnover($user);

        if ($teamTurnover < 50000000) {
            return false;
        }

        return true;
    }
    public function isAssociateManager(User $user)
    {
        // Check personal investment
        if ($this->getTotalInvestmentForVentures($user) < 1000) {
            return false;
        }
        // Check active direct referrals
        $activeDirectReferrals = $this->activeDirectReferralsForVentures($user);
        if ($activeDirectReferrals->count() < 10) {
            return false;

        }

        // Check total investment of direct referrals
        // dd($activeDirectReferrals);
        // $activeDirectReferrals->map(function ($r){
        //     echo $r->id."<br/>";
        // });
        $directReferralInvestment = $activeDirectReferrals->sum(function ($referral) {
            $d = $referral->investments()->where("category","VENTURE")->where("is_expired","0")->sum("amount");
            return $d;
        });
        if ($directReferralInvestment < 500000) {
            return false;
        }

        return true;
    }







    public function referral(Request $request)
    {
        $user = Auth::user();

        // 1. Referred By (Referrer of current user)
        $referrerUser = $user->referrer ?: (\App\Models\User::find($user->referee_id));

        // 2. Direct Referrals (Level 1) - Paginated by 5 records
        $directReferrals = User::where('referee_id', $user->id)
            ->latest()
            ->paginate(5, ['*'], 'direct_page');

        // 3. Indirect Referrals (Level 2+) - Paginated by 5 records
        $allDownlineIds = collect();
        $queue = collect([$user->id]);

        while ($queue->isNotEmpty()) {
            $currentId = $queue->shift();
            $directs = User::where('referee_id', $currentId)->pluck('id');
            foreach ($directs as $dId) {
                if (!$allDownlineIds->contains($dId)) {
                    $allDownlineIds->push($dId);
                    $queue->push($dId);
                }
            }
        }

        $directIds = User::where('referee_id', $user->id)->pluck('id')->toArray();
        $indirectIds = $allDownlineIds->reject(function ($id) use ($user, $directIds) {
            return $id === $user->id || in_array($id, $directIds);
        })->values()->all();

        $indirectReferrals = User::whereIn('id', $indirectIds)
            ->latest()
            ->paginate(5, ['*'], 'indirect_page');

        $allUsers = $this->getAllDownlineUsers($user);

        $right_referrals = $allUsers->filter(function($refer){
            return $refer->side == "RIGHT";
        });
        $left_referrals = $allUsers->filter(function($refer){
            return $refer->side == "LEFT";
        });

        return view('user.referral', [
            "referrerUser"      => $referrerUser,
            "directReferrals"   => $directReferrals,
            "indirectReferrals" => $indirectReferrals,
            "right_referrals"   => $right_referrals,
            "left_referrals"    => $left_referrals,
            "all_referal"       => $allUsers
        ]);
    }
     public function showTask()
    {

        return view('user.task');
    }

    public function payments(Request $request)
    {
        $user = Auth::user();
        $amount = (float) $user->ChartAccount()->where("acc_type", "CASHOUT")->sum("amount");

        $history = \App\Models\Transaction::where('user_id', $user->id)
            ->whereIn('transaction_type', [
                'CASHOUT_TRANSFER_SENT',
                'CASHOUT_TRANSFER_RECEIVED',
                'INTERNAL_WALLET_TRANSFER'
            ])
            ->orderByDesc('created_at')
            ->paginate(5);

        return view('user.payments', [
            "cashout" => $amount,
            "history" => $history
        ]);
    }

    public function TransferToInternal(Request $request){

        $user = Auth::User();
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'account' => 'required|string',
            'transaction_password' => ['required', new \App\Rules\ValidTransactionPassword($user)],
        ]);

        $from = "CASHOUT";
        $to = $request->account;
        $amount = $request->amount;
        $remaining = $user->ChartAccount()->where("acc_type","CASHOUT")->sum("amount") - $amount;

        ChartAccount::updateOrCreate(
            [
                'user_id' => $user->id,
                'acc_type' => $to,
            ],
            [
                'amount' => $amount
            ]
            );
         ChartAccount::updateOrCreate(
            [
                'user_id' => $user->id,
                'acc_type' => 'CASHOUT',
            ],
            [
                'amount' => $remaining
            ]
            );


        return redirect()->route("user.dashboard");

    }
    public function coinacc()
    {
        return view('user.payments');
    }



    public function tradingac()
    {
        return view('user.TradingAccount');
    }


    public function myinvoices()
    {
        return view('user.myinvoice');
    }




    function calculateAndInsertCashouts($user, $startDate, $endDate, $dailyIncome, $cashoutPercentage = 25)
    {
        // Initialize Carbon dates
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        // Period excluding weekends (Saturday and Sunday)
        $period = CarbonPeriod::create($startDate, $endDate)->filter(function (Carbon $date) {
            return !$date->isWeekend();
        });

        $investmentDaysCounter = 0;

        // Iterate over each valid day
        foreach ($period as $date) {
            $investmentDaysCounter++;

            // Check if the date is a Monday and if 5 investment days have passed
            if ($date->isMonday() && $investmentDaysCounter > 5) {
                $dailyCashout = ($dailyIncome * $cashoutPercentage) / 100;

                // Insert into cashouts table
                // Cashout::create([
                //     'user_id' => $user->id,
                //     'amount' => $dailyCashout,
                //     'status' => 'pending',
                // ]);
            }
        }
    }

}

// -----------------------------cashout , trading vouchers






// // Example usage:
// $user = Auth::user(); // Assuming the user is authenticated
// $startDate = '2024-08-01';
// $endDate = '2024-08-31';
// $dailyIncome = 100; // Example daily income

// calculateAndInsertCashouts($user, $startDate, $endDate, $dailyIncome);
