<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment as Paymodel;
use App\Models\User;
use App\Models\DailyIncome;
use App\Models\adventures;
use App\Models\ChartAccount;
use App\Models\Transaction;
use App\Models\Claim;
use Carbon\Carbon;

class UserDashboardController extends Controller{


    public function restrictedFreeAccount(){
        return view("user.unauthorized-free-account");
    }
    public function upgradeVenturePackage(){
        return view("user.account-upgrade-package-venture");
    }
    public function mypayments() {

        $user = Auth::user();
        return view("user.Mypayments",["deposits"=>$user->deposits]);
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
    
    
    


    public function index()
    {
        $user=Auth::user();
        $userId = $user->id;
        // does user have claims
        $userHasClaims = $user->have_claims->where("is_fixed",false)->first();
        $user = User::where("id",$userId)->first();
        $portfolio = 0;

        $myteam = $user->ownedTeams();
        $right = $myteam->where("side","RIGHT")->count();
        $left = $myteam->where("side","LEFT")->count();
        $allUsers = $this->getAllDownlineUsers($user);
        // dd($allUsers);referral
        

        $mostRecentPayment = $user->investments()
                                  ->where("is_expired",0)
                                  ->where("status",1)
                                  ->orderBy('created_at', 'desc')
                                  ->first();


        // Daily income is now calculated by the scheduler (income:calculate command).
        // Do NOT call showDailyIncome() here — it would run on every page load.
        // $cashout and $shooping remain the CUMULATIVE totals (what the user can withdraw/spend).
        $cashout = $user->ChartAccount()->where("acc_type","CASHOUT")->sum("amount");
        $shooping = $user->ChartAccount()->where("acc_type","TRADING")->sum("amount");

        // Legacy cumulative total (kept for compatibility with views that show it as a badge).
        $dailyIncome = $user->DailyIncomes()->sum("amount");

        // ── Compute the PER-DAY breakdown for the active package ──
        // Per spec: Daily ROI = (package_amount × 80%) × (adventures.percentage / 100)
        //   → Cashout (25%): withdrawable anytime, min $10
        //   → Trading Voucher (75%): accumulates, used every 30 days for renewal
        $dailyIncomePerDay = 0.0;
        $dailyCashout      = 0.0;
        $dailyTrading      = 0.0;
        if ($package) {
            $adventureRow = \App\Models\adventures::find($package->payable_id);
            if ($adventureRow) {
                $poolCapital    = (float) $package->paid * 80 / 100;
                $dailyIncomePerDay = $poolCapital * ((float) $adventureRow->percentage / 100);
                $dailyCashout      = $dailyIncomePerDay * 25 / 100;
                $dailyTrading      = $dailyIncomePerDay * 75 / 100;
            }
        }

        if(($user->has_paid_package=='yes' || $user->has_paid_package=='ft' || $user->has_paid_package=='tm') && $user->contract != 'Signed'){
            return redirect()->route("user.contract");
        }
        $package = Paymodel::where("user",$userId)
                            ->where("is_expired",false)
                            ->where("status","1")
                            ->first();

        $deposits = $user->deposits->filter(function ($deposit) {
            return $deposit->status == 'approved';
        });

        $deposits_used = $user->deposits->filter(function ($deposit) {
            return $deposit->status == 'used';
        });
        $deposits_pending = $user->deposits->where('status', 'pending');
        $differences = $deposits->sum('amount_deposited') - $deposits_used->sum('amount_removed');
        $sum = $differences;
        // dd($user->has_paid_package);
        $credit = 0;
        $credit_status = '';

        $ftcurrentPortfolio = $user->currentPortfolio()->where("usage","CURRENT")->where("packagename","FT");
        $tmcurrentPortfolio = $user->currentPortfolio()->where("usage","CURRENT")->where("packagename","TM");
        $venturcurrentPortfolio = $user->currentPortfolio()->where("usage","CURRENT")->where("packagename","FT");
        // $ftcurrentPortfolio = $user->currentPortfolio()->where("usage","CURRENT")->where("packagename","FT");


        switch($user->has_paid_package){
        case 'ft':
        case 'FT':
        case 'VENTURE':
            $portfolio = $package->paid;
            break;
        case 'TM':
        case 'tm':
            $p = $user->have_activation_code;
            $portfolio = $p->price;
            $credit = $p->myCredit->amount;
            $credit_status = $p->myCredit->status;

                break;
            default:
            $portfolio = $package->paid;

        }

        $ranks = [
            "isAssociate"=>$this->isAssociate(Auth::User()),
            "isDirector"=>$this->isDirector(Auth::User()),
            "isRegionalSupervisor"=>$this->isRegionalSupervisor(Auth::User()),
            "isRegionalVicePresident"=>$this->isRegionalVicePresident(Auth::User()),
            "associateManager"=>$this->isAssociateManager(Auth::User()),
        ];



        $createdDate = $mypackage->created_at;
        $today = Carbon::now();
        // Calculate the difference in days
        $daysGone = isset($createdDate) ? $createdDate->diffInDays($today) : 0;
        $earnings = $user->earnings->sum("amount");

        // dd($this->isAssociate(Auth::User()));
        // dd($this->isDirector(Auth::User()));
        // dd($this->isRegionalSupervisor(Auth::User()));
        // dd($this->isRegionalVicePresident(Auth::User()));
        // dd($ranks);
        // $referrals = $this->referrals(Auth::User());

        $ChartAccount = $user->ChartAccount()->where("acc_type","TRADING")->first();
        $lockedToken    = $user->ChartAccount()->where("acc_type", "LOCKED_TOKEN")->sum("amount");
        $freeToken      = $user->ChartAccount()->where("acc_type", "FREE_TOKEN")->sum("amount");
        $availableToken = $user->ChartAccount()->where("acc_type", "AVAILABLE_TOKEN")->sum("amount");
        // GAS_FEE is admin-only — not read here
        $COMMISSION = $user->ChartAccount()->where("acc_type","COMMISSION")->sum("amount");

        // ── Referral bonus breakdown for dashboard (live, from referral_bonuses table) ──
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

        $purchaseDate = Carbon::parse($ChartAccount->created_at);
        $expirationDate = null;
        $show = false;

        if ($mostRecentPayment && $mostRecentPayment->category == "VENTURE") {
            // Use the package's actual expiration_date (set at purchase based on adventure.duration),
            // not a hardcoded 31 days. This works for 100, 200, 600-day packages etc.
            $expirationDate = $package && $package->expiration_date
                ? Carbon::parse($package->expiration_date)
                : $purchaseDate->copy()->addDays(100); // safe fallback for legacy rows
            $show = true;
        }

        // ── Package expiry state ──
        // A package is considered expired when:
        //   (a) is_expired flag is true, OR
        //   (b) no active payment exists at all
        $packageExpired = !$package || $package->is_expired;

        // If the package just expired, make sure has_paid_package is reset
        // (CheckPackages command does this nightly; this is a safety fallback)
        if ($packageExpired && $user->has_paid_package !== 'no') {
            $user->has_paid_package = 'no';
            $user->save();
        }

        // ── Renewal due state ──
        // Derived from package duration: max_renewals = floor((duration-1)/30).
        // Renewal #N is due from day (N*30 - 1) onward (1 day grace before the window).
        $renewalDue    = false;
        $renewalNumber = 0;

        if ($package && !$packageExpired) {
            $pkgStart    = Carbon::parse($package->created_at);
            $daysSince   = (int) $pkgStart->diffInDays(Carbon::now());
            $renewalsDone = \App\Models\PackageRenewal::where('user_id', $userId)
                                ->where('payment_id', $package->id)
                                ->count();

            // Get duration from the adventure package
            $activePkg2   = \App\Models\adventures::find($package->payable_id);
            $pkgDuration  = $activePkg2 ? (int) $activePkg2->duration : 100;
            $maxRenewals  = \App\Services\RenewalCalculator::maxRenewals($pkgDuration);

            $nextRenewalNum       = $renewalsDone + 1;
            $nextRenewalThreshold = $nextRenewalNum * 30;  // e.g. renewal #1 due at day 30

            if ($renewalsDone < $maxRenewals && $daysSince >= ($nextRenewalThreshold - 1)) {
                $renewalDue    = true;
                $renewalNumber = $nextRenewalNum;
            }
        }

        $comm = $this->commissions();

        // dd($deposits_pending);
        return view('user.dashboard',
        [
            "mypackage"              => $package,
            "show_timer"             => $show,
            "deposits"               => number_format($sum),
            "ranks"                  => $ranks,
            "amount"                 => 0,
            // ── Token balances (shown to user) ──
            // LOCKED_TOKEN   : full investment / uvp_price. Locked during package. Released to AVAILABLE_TOKEN on expiry (per spec).
            // AVAILABLE_TOKEN: receives (a) locked tokens released at package expiry, and (b) tokens earned each 30-day renewal (trading_voucher / renewal_price). Must be moved to FREE_TOKEN before use.
            // FREE_TOKEN     : usable tokens — transfer to user, swap to cashout, or withdraw to wallet.
            // GAS_FEE        : 20% charges — admin-only, never shown here.
            "locked"                 => $lockedToken,
            "free_token"             => $freeToken,
            "available_token"        => $availableToken,
            "fcoin"                  => number_format($freeToken, 0), // used in blade fcoin box
            "commission"             => $COMMISSION,
            "referral_bonus_totals"  => $referralBonusTotals,
            "current_rank"           => $currentRank,
            "next_rank"              => $nextRankInfo,
            "direct_referral_count"  => $directReferralCount,
            "active_referral_count"  => $activeReferralCount,
            "direct_bonus_earned"    => $directBonusEarned,
            "gasfees"                => 0,
            "pool"                   => 0,
            "dailyIncome"            => "$".$dailyIncome,
            "cashout"                => "$".$cashout,
            "shooping"               => "$".$shooping,
            // ── Per-day breakdown (Issue 2 + 6) ──
            "daily_income_per_day"   => number_format($dailyIncomePerDay, 2),
            "daily_cashout"          => number_format($dailyCashout, 2),
            "daily_trading"          => number_format($dailyTrading, 2),
            "max_renewals"           => $maxRenewals,
            "pkg_duration"           => $pkgDuration,
            "daysgone"               => $daysGone,
            "credit"                 => $credit,
            "portfolio"              => "$".$portfolio,
            "credit_status"          => $credit_status,
            "right"                  => $right,
            "left"                   => $left,
            "left_direct_uvp"        => 0,
            "left_indirect_uvp"      => 0,
            "right_direct_uvp"       => $comm['right_direct_uvp'],
            "right_indirect_uvp"     => $comm['right_indirect_uvp'],
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


    private function getPeriodDates($period)
    {
        switch ($period) {
            case 'this_week':
                return [now()->startOfWeek(), now()->endOfWeek()];

            case 'last_week':
                return [now()-> Week()->startOfWeek(), now()->subWeek()->endOfWeek()];

            case 'this_month':
                return [now()->startOfMonth(), now()->endOfMonth()];

            case 'last_month':
                return [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()];

            case 'this_year':
                return [now()->startOfYear(), now()->endOfYear()];

            case 'last_year':
                return [now()->subYear()->startOfYear(), now()->subYear()->endOfYear()];

            default:
                return [now()->startOfDay(), now()->endOfDay()];
        }
    }

    private function getEarnings($user,$period,$side){
        $directUsers = $user->ownedTeams()->where('side', $side)->get();
        $indirectUvpCount = 0;
        $zone = 0;
        $d = [];
        foreach ($directUsers as $directUser) {
            $indirectUsers = $this->findIndirectUsers($directUser->teamMember, $side);
            foreach ($indirectUsers as $indirectUser) {
                $individualUser = $indirectUser->teamMember;
                //  dd($indirectUser);
                $hasUvpInvestment = $individualUser->investments()->where('category', 'VENTURE')->exists();

                if($hasUvpInvestment){
                    $com = $individualUser->transactions()
                    ->whereBetween('created_at', $this->getPeriodDates($period))->get();

                    $commission = $com->filter(function($data) {
                        return $data->transaction_type == "COMMISSION";
                    })->sum(function ($data) {
                        $details = json_decode($data->transaction_details, true);
                        return $details['amount'] ?? 0;
                    });

                    $zone += $commission;


                }

            }
        }
        return $zone;

    }


    private function getDirectUvpPeriodically($teamMembers, $packageType,$period,$side) {
        $directUvpCount = 0;
        $commission = 0;

        // dd($teamMembers);
        foreach ($teamMembers as $teamMember) {
            // dd($teamMember->teamMember);
            $individualUser = $teamMember->teamMember;

            // $hasUvpInvestment = $du->investments()->where('category', 'VENTURE')->exists();
            // $earns = $du->ChartAccount()->where("acc_type","COMMISSION")->sum("amount");

            $co = $individualUser->transactions()
                        ->whereBetween('created_at', $this->getPeriodDates($period))->get();

                    $com = $co->filter(function($data) {
                            return $data->transaction_type == "COMMISSION";
                        })->sum(function ($data) {
                            $details = json_decode($data->transaction_details, true);
                            return $details['amount'] ?? 0;
                        });
            $commission += $com;

        }

        return $commission;
    }

    public function fetchCommissions (Request $request){
        $user   =   Auth::User();
        $myteam = $user->ownedTeams();
        $right  = $myteam->where("side","RIGHT")->get();
        $left   = $myteam->where("side","LEFT")->get();
        $period = $request->query('period');

        $data = $this->getEarnings($user,$period,'RIGHT');
        $left_data = $this->getEarnings($user,$period,'LEFT');
        $direct_left = $this->getDirectUvpPeriodically($left,"VENTURE",$period,"LEFT");
        $direct_right = $this->getDirectUvpPeriodically($right,"VENTURE",$period,"RIGHT");

        return response()->json([

            'iright'=>$data,
            'ileft'=>$left_data,
            'direct_left'=>$direct_left,
            'direct_right'=>$direct_right,
            'right'=>$data+$direct_right,
            'left'=>$left_data+$direct_left
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
        // Only VENTURE packages generate daily income
        $package = Paymodel::where("category", "VENTURE")
                            ->where("user", $userId)
                            ->where("is_expired", 0)
                            ->where("status", 1)
                            ->first();

        if (!$package) {
            return;
        }

        $package2 = adventures::where("id", $package->payable_id)->first();
        if (!$package2) {
            return;
        }

        $percentcharge = $package2->percentage;
        $amount        = (float) $package->paid;
        $user          = User::find($userId);
        $now           = Carbon::now();

        $startDate      = Carbon::parse($package->created_at);
        $expirationDate = Carbon::parse($package->expiration_date);

        // How many renewals has the user completed for this package?
        $renewalsDone = \App\Models\PackageRenewal::where("user_id", $userId)
                            ->where("payment_id", $package->id)
                            ->orderBy("renewal_number")
                            ->get();

        // Build lookup: renewal_number => date completed
        $renewalCompletedAt = [];
        foreach ($renewalsDone as $renewal) {
            $renewalCompletedAt[$renewal->renewal_number] = Carbon::parse($renewal->renewed_at);
        }

        // Ceiling: don't generate income past expiration_date or today
        $ceiling    = $expirationDate->lt($now) ? $expirationDate : $now;
        $daysPassed = (int) $startDate->diffInDays($ceiling);

        for ($i = 1; $i <= $daysPassed; $i++) {
            $earnedAt  = $startDate->copy()->addDays($i);

            // Hard stop at expiration
            if ($earnedAt->gt($expirationDate)) {
                break;
            }

            // Renewal pause logic — delegated to RenewalCalculator.
            // See RenewalCalculator::renewalsRequiredForDay() for the formula.
            $packageDuration = (int) $package2->duration;
            $maxRenewals     = \App\Services\RenewalCalculator::maxRenewals($packageDuration);
            $blocked         = false;

            $neededRenewal = \App\Services\RenewalCalculator::renewalsRequiredForDay($i);
            if ($neededRenewal > 0 && $neededRenewal <= $maxRenewals) {
                if (!isset($renewalCompletedAt[$neededRenewal]) ||
                    $earnedAt->lt($renewalCompletedAt[$neededRenewal])) {
                    $blocked = true;
                }
            }

            if ($blocked) {
                continue; // skipped days are never back-filled
            }

            // Already recorded?
            $incomeExists = DailyIncome::where("user_id", $userId)
                                        ->whereDate("earned_at", $earnedAt->toDateString())
                                        ->exists();
            if ($incomeExists) {
                continue;
            }

            // Calculate income
            $poolCapital = $amount * 80 / 100;
            $dailyIncome = $poolCapital * $percentcharge / 100;
            $trading     = $dailyIncome * 75 / 100;  // 75% Trading Voucher
            $cashout     = $dailyIncome * 25 / 100;  // 25% Cashout

            DailyIncome::create([
                "user_id"   => $userId,
                "amount"    => $dailyIncome,
                "earned_at" => $earnedAt,
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

            // Credit accounts — FIX: CASHOUT now uses $beforeCashout (was $beforeTrading)
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







    public function referral()
    {


        $user = Auth::user();
        $allUsers = $this->getAllDownlineUsers($user);
        

        $right_referrals = $allUsers->filter(function($refer){
            return $refer->side == "RIGHT";
        });
        $left_referrals = $allUsers->filter(function($refer){
            return $refer->side == "LEFT";
        });

        // dd($right_referrals);
        return view('user.referral',["right_referrals"=>$right_referrals,"left_referrals"=>$left_referrals,"all_referal"=>$allUsers]);
    }
     public function showTask()
    {

        return view('user.task');
    }

    public function payments()
    {

        $user = Auth::User();

        $amount =$user->ChartAccount()->where("acc_type","CASHOUT")->sum("amount");


        return view('user.payments',[
            "cashout"=>$amount
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
