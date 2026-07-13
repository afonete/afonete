<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Teams;
use App\Models\ChartAccount;
use App\Models\user_transactions as UserTransactions;
use App\Services\RenewalCalculator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function overview()
    {
        $user = Auth::User();
        $transactions = $user->transactions()->where("transaction_type","INCOME")->get();

        // dd($transactions);
        $added = Auth::User()->deposits()->sum("amount_deposited");
        $removed = Auth::User()->deposits()->sum("amount_removed");
        // $balance = $added - $removed;
        $balance = $user->ChartAccount()->where("acc_type","CASHOUT")->sum("amount");

        $send = Auth::User()->Transferred()->sum("amount");
        $received = Auth::User()->Received()->sum("amount");
        // $balance += $received - $send;

        return view('user.overview',["transaction"=>$transactions,"balance"=>$balance]); // Adjust the view path as needed
    }

    public function transaction()
    {
        $user = Auth::User();
        $transactions = Transaction::where("transaction_type","TRANSFER")
      ->where('user_id',$user->id)->orWhere('receiver_id',$user->id)->get();

        // dd($transactions);
        $added = Auth::User()->deposits()->sum("amount_deposited");
        $removed = Auth::User()->deposits()->sum("amount_removed");
        $balance = $added - $removed;
        $send = Auth::User()->Transferred()->sum("amount");
        $received = Auth::User()->Received()->sum("amount");
        // $balance += $received - $send;
        $balance = $user->ChartAccount()->where("acc_type","CASHOUT")->sum("amount");


        return view('user.transaction',["trns"=>$transactions,"balance"=>$balance]); // Adjust the view path as needed
    }

    public function transfer(Request $request){
        $user = Auth::User();
        $validatedData = $request->validate([
            'amount' => 'required|max:25',
            'name' => 'required|exists:users',
        ],[
            'name.exists'=>"Selected User Does not exist in our records"
        ]);

        $receiver = User::where("name",$request->name)->first();
        $trx = UserTransactions::create([
            "sender_id"=>$user->id,
            "receiver_id"=>$receiver->id,
            'amount'=>$request->amount,
            "transaction_type"=>"TRANSFER"
        ]);

        $details = 0;
        $trxNo = Transaction::generateTransactionNo();

        $transaction =  Transaction::create([
            'user_id'=>$user->id,
            'transaction_no' => $trxNo,
            'transaction_type' => 'TRANSFER',
            "receiver_id"=>$receiver->id,
            'transaction_details' => json_encode([
                'week' => Carbon::now(),
                'daily_vup' => 0,
                'direct_bonus' => 0.00,
                'volume_bonus' => 0.00,
                'leader_bonus' => 0.00,
                'amount' => $request->amount,
                'total' => 0.00,
                'cash' => 0.00,
                'trx_voucher' => 0.00,
                'trx_type' => 'Member to Member Transfer',
                'revenue_type'=>'soon',
                'status'=>'success',
                'date'=>Carbon::now(),
                'receiver'=>$request->name,
                'sender'=>$user->id,
                'to_receiver'=>'You have received '.$request->amount.' From'.$request->name,
                'to_sender'=>'You have sent '.$request->amount.' To '.$request->name,
                'status'=>'success'
            ])
        ]);


        return back()->with("success","You Have Successfull Transferred ".$request->amount." To ".$request->name);

    }


    public function getAccountBalance(Request $request){

        $user = Auth::User();
        $param = $request->query("ac");


        $charts = $user->ChartAccount()->where("acc_type",$param)->first();

        if(!$charts){
            return response()->json([
                "balance"=>0.00,
                "message"=>"channged account"
            ]);
        }

        return response()->json([
            "balance"=>$charts->amount,
            "message"=>"channged account"
        ]);



    }

    /**
     * Issue 3 (fix) — Transfer CASHOUT to another user.
     *
     * Spec (per user):
     *   - User inputs recipient EMAIL
     *   - Clicks "Find" → sees recipient's full name as confirmation
     *   - Inputs amount
     *   - Clicks Send → amount moves from sender's CASHOUT → recipient's CASHOUT
     *
     * Bugs fixed:
     *   - Recipient was never credited (only sender was debited)
     *   - Hard "same chain" restriction (user can transfer to any other user)
     *   - Form used `name` (full name) as identifier — switched to email
     */
    public function transferToUser(Request $request){
        $user = Auth::user();
        $validatedData = $request->validate([
            'amount'          => 'required|numeric|min:0.01',
            'recipient_email' => 'required|email|exists:users,email',
        ], [
            'recipient_email.exists' => "No user found with that email address.",
        ]);

        $amount    = (float) $request->amount;
        $recipient = User::where('email', $request->recipient_email)->first();

        if (!$recipient) {
            return back()->with('error', 'Recipient not found.');
        }

        if ($recipient->id === $user->id) {
            return back()->with('error', 'You cannot transfer funds to yourself.');
        }

        $cashoutBal = $user->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount');
        if ($cashoutBal < $amount) {
            return back()->with('error', 'Insufficient Funds. You have $' . number_format($cashoutBal, 2) . ' available.');
        }

        DB::transaction(function () use ($user, $recipient, $amount) {
            // Debit sender's CASHOUT
            $user->ChartAccount()->where('acc_type', 'CASHOUT')
                 ->update(['amount' => $user->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount') - $amount]);

            // Credit recipient's CASHOUT (FIX — was missing before)
            $recipientBal = $recipient->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount');
            ChartAccount::updateOrCreate(
                ['user_id' => $recipient->id, 'acc_type' => 'CASHOUT'],
                ['amount'  => $recipientBal + $amount]
            );

            $trxNo = Transaction::generateTransactionNo();

            // Sender-side transaction record
            Transaction::create([
                'user_id'             => $user->id,
                'transaction_no'      => $trxNo,
                'transaction_type'    => 'CASHOUT_TRANSFER_SENT',
                'receiver_id'         => $recipient->id,
                'transaction_details' => json_encode([
                    'amount'         => $amount,
                    'currency'       => 'USDT',
                    'trx_type'       => 'Member to Member Cashout Transfer',
                    'from_email'     => $user->email,
                    'from_name'      => $user->name,
                    'to_email'       => $recipient->email,
                    'to_name'        => $recipient->name,
                    'date'           => now()->toDateTimeString(),
                    'status'         => 'completed',
                ]),
            ]);

            // Mirror record on recipient's history
            Transaction::create([
                'user_id'             => $recipient->id,
                'transaction_no'      => $trxNo,
                'transaction_type'    => 'CASHOUT_TRANSFER_RECEIVED',
                'receiver_id'         => $user->id,
                'transaction_details' => json_encode([
                    'amount'    => $amount,
                    'currency'  => 'USDT',
                    'trx_type'  => 'Member to Member Cashout Transfer',
                    'from_email'=> $user->email,
                    'from_name' => $user->name,
                    'to_email'  => $recipient->email,
                    'to_name'   => $recipient->name,
                    'date'      => now()->toDateTimeString(),
                    'status'    => 'completed',
                ]),
            ]);
        });

        return back()->with('success',
            '$' . number_format($amount, 2) . ' successfully transferred to ' . $recipient->name . ' (' . $recipient->email . ').'
        );
    }

    /**
     * AJAX lookup for CASHOUT transfer: find user by email, return name for preview.
     */
    public function cashoutTransferLookup(Request $request)
    {
        $recipient = User::where('email', $request->email)
                        ->where('id', '!=', Auth::id())
                        ->select('id', 'name', 'email')
                        ->first();

        if (!$recipient) {
            return response()->json(['found' => false, 'message' => 'No user found with that email.']);
        }
        return response()->json(['found' => true, 'name' => $recipient->name, 'email' => $recipient->email]);
    }


    public function transferToTradingAccount (Request $request){
        $user = Auth::User();
        $validatedData = $request->validate([
            'amount' => 'required|max:25',
        ]);

        $amount = $request->amount;
        $remaining = $user->ChartAccount()->where("acc_type","CASHOUT")->sum("amount") - $amount;
        $balance = $user->ChartAccount()->where("acc_type","TRADING")->sum("amount") + $amount;

        // dd($remaining);
        if($remaining < 0){
            return back()->with('error-tr' , "Insufficient Funds.");
        }
        // dd($remaining);
        $user->ChartAccount()->where("acc_type","CASHOUT")->update(['amount' => $remaining]);
        $user->ChartAccount()->where("acc_type","TRADING")->update(['amount' => $balance]);

        $details = 0;
        $trxNo = Transaction::generateTransactionNo();
        $transaction =  Transaction::create([
            'user_id'=>$user->id,
            'transaction_no' => $trxNo,
            'transaction_type' => 'TRANSFER',
            "receiver_id"=>0,
            'transaction_details' => json_encode([
                'week' => Carbon::now(),
                'daily_vup' => 0,
                'direct_bonus' => 0.00,
                'volume_bonus' => 0.00,
                'leader_bonus' => 0.00,
                'amount' => $request->amount,
                'total' => 0.00,
                'cash' => 0.00,
                'trx_voucher' => $trxNo,
                'trx_type' => 'Transfer To Trading Account',
                'revenue_type'=>'soon',
                'status'=>'success',
                'date'=>Carbon::now(),
                'status'=>'success'
            ])
        ]);

        return back()->with("success-tr","You Have Successfull Transferred ".$request->amount."$ To Trading Account");
    }

    public function transferToAccount (Request $request){
        $user = Auth::User();
        $validatedData = $request->validate([
            'amount' => 'required|max:25',
            'account'=>'required',
        ]);
        $amount = $request->amount;
        $receiver_account = $request->account;
        $remaining = $user->ChartAccount()->where("acc_type","CASHOUT")->sum("amount") - $amount;
        $balance = $user->ChartAccount()->where("acc_type",$receiver_account)->sum("amount") + $amount;

        if($remaining < 0){
            return back()->with('error-trx' , "Insufficient Funds.");
        }

        $user->ChartAccount()->where("acc_type","CASHOUT")->update(['amount' => $remaining]);
        $user->ChartAccount()->where("acc_type","TRADING")->updateOrCreate(
            [
                'user_id' => $user->id,
                'acc_type' => $receiver_account,
            ],
            [
                'amount' => $balance
            ]
        );

        $details = 0;
        $trxNo = Transaction::generateTransactionNo();
        $transaction =  Transaction::create([
            'user_id'=>$user->id,
            'transaction_no' => $trxNo,
            'transaction_type' => 'TRANSFER',
            "receiver_id"=>0,
            'transaction_details' => json_encode([
                'week' => Carbon::now(),
                'daily_vup' => 0,
                'direct_bonus' => 0.00,
                'volume_bonus' => 0.00,
                'leader_bonus' => 0.00,
                'amount' => $request->amount,
                'total' => 0.00,
                'cash' => 0.00,
                'trx_voucher' => $trxNo,
                'trx_type' => 'Transfer To '.$receiver_account.' Account',
                'revenue_type'=>'0',
                'status'=>'success',
                'date'=>Carbon::now(),
                'status'=>'success'
            ])
        ]);

        return back()->with("success-trx","You Have Successfull Transferred ".$request->amount."$");
    }




    private function isUserInSameChain($user, $receiver) {
        // Check if the receiver is in the same direct team
        $userTeams = $user->ownedTeams()->pluck('team_user_id')->toArray();

        // dd($userTeams);
        if (in_array($receiver->id, $userTeams)) {
            return true;
        }

        // Check if the receiver is an indirect team member
        $indirectUsers = $this->findIndirectUsers($user,$user->teamSide->side); // Null for both LEFT and RIGHT

        return $indirectUsers->contains($receiver->id);
    }

    private function findIndirectUsers($user, $side = null)
    {
        $indirectUsers = collect();

        $teams = $user->ownedTeams();

        if ($side) {
            $teams->where('side', $side);
        }

        $teamMembers = $teams->pluck('team_user_id');

        foreach ($teamMembers as $teamMemberId) {
            $teamMember = User::find($teamMemberId);

            if ($teamMember) {
                $indirectUsers->push($teamMemberId);
                $indirectUsers = $indirectUsers->merge($this->findIndirectUsers($teamMember, $side));
            }
        }

        return $indirectUsers;
    }


    public function commissionDetails(Request $request){



        $user = Auth::User();
        $startdate = Carbon::parse($request->input('startdate'))->startOfDay();
        $enddate = Carbon::parse($request->input('enddate'))->endOfDay();

        $chartAc = $user->ChartAccount()->where("acc_type","COMMISSION")->whereBetween('created_at', [$startdate, $enddate])->get();
        $trx = $user->transactions()
                    ->where("transaction_type", "COMMISSION")
                    ->whereBetween('created_at', [$startdate, $enddate])
                    ->get();

        $previousWeekStart = $startdate->copy()->subWeek()->startOfWeek();
        $previousWeekEnd = $enddate->copy()->subWeek()->endOfWeek();

        $previousWeekTransactions = $user->ChartAccount()
                                        ->where("acc_type","COMMISSION")
                                        ->whereBetween('created_at', [$previousWeekStart, $previousWeekEnd])
                                        ->get();

        $previousWeekTotal = $previousWeekTransactions->sum('amount');

        $balance = $chartAc->sum("amount");

        return view('user.commission-details',["balance"=>$balance,"trx"=>$trx,"chartAc"=>$chartAc,"previousWeekTotal"=> $previousWeekTotal]); // Adjust the view path as needed
    }

    public function commission()
    {
        $user = Auth::User();

        // ── Total commission balance ──
        $totalCommission = $user->ChartAccount()->where('acc_type', 'COMMISSION')->sum('amount');

        // ── All commission transactions ──
        $commissionTrx = \App\Models\Transaction::where('user_id', $user->id)
            ->where('transaction_type', 'COMMISSION')
            ->latest()
            ->get()
            ->map(function ($t) {
                $details = json_decode($t->transaction_details, true) ?? [];
                $t->parsed_amount      = $details['amount'] ?? 0;
                $t->parsed_description = $details['description'] ?? ($details['trx_type'] ?? 'Referral Bonus');
                $t->from_user          = $details['username'] ?? '—';
                $t->direct_bonus       = $details['direct_bonus'] ?? 0;
                return $t;
            });

        // ── Direct referrals with their bonus amounts ──
        $directReferrals = $user->referrals()
            ->with(['investments' => function ($q) {
                $q->where('status', 1)->where('category', 'VENTURE');
            }])
            ->get()
            ->map(function ($ref) use ($user) {
                $totalInvested = $ref->investments->sum('amount');
                $bonusEarned   = $totalInvested * 10 / 100;
                $ref->total_invested = $totalInvested;
                $ref->bonus_earned   = $bonusEarned;
                $ref->is_active      = $ref->investments->isNotEmpty();
                return $ref;
            });

        // ── Indirect referrals (level 2) ──
        $indirectReferrals = collect();
        foreach ($user->referrals as $direct) {
            foreach ($direct->referrals as $indirect) {
                $totalInvested = $indirect->investments()
                    ->where('status', 1)->where('category', 'VENTURE')->sum('amount');
                $indirect->total_invested   = $totalInvested;
                $indirect->bonus_earned     = $totalInvested * 1 / 100;
                $indirect->referred_through = $direct->name;
                $indirect->is_active        = $totalInvested > 0;
                $indirectReferrals->push($indirect);
            }
        }

        // ── Summary stats ──
        $directBonusTotal   = $directReferrals->sum('bonus_earned');
        $indirectBonusTotal = $indirectReferrals->sum('bonus_earned');
        $totalDirectCount   = $directReferrals->count();
        $activeDirectCount  = $directReferrals->where('is_active', true)->count();

        // ── Previous week total ──
        $previousWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $previousWeekEnd   = Carbon::now()->subWeek()->endOfWeek();
        $previousWeekTotal = \App\Models\Transaction::where('user_id', $user->id)
            ->where('transaction_type', 'COMMISSION')
            ->whereBetween('created_at', [$previousWeekStart, $previousWeekEnd])
            ->get()
            ->sum(function ($t) {
                $d = json_decode($t->transaction_details, true);
                return $d['amount'] ?? 0;
            });

        return view('user.commission', compact(
            'totalCommission', 'commissionTrx',
            'directReferrals', 'indirectReferrals',
            'directBonusTotal', 'indirectBonusTotal',
            'totalDirectCount', 'activeDirectCount',
            'previousWeekTotal'
        ));
    }

    public function downline()
    {

        $user = Auth::User();

        $personM = $user->referrals;
        $person = $user->referrals()->count();

        $teams_members = $user->ownedTeams()->count();
        $members = $user->teamMembers;

        $left_team = $user->ownedTeams->filter(function ($mbr){
            return $mbr->side == 'LEFT';
        })->count();

        $right_team =  $user->ownedTeams->filter(function ($mbr){
            return $mbr->side == 'RIGHT';
        })->count();

        $total = $left_team + $right_team + $person;


        // dd(array_merge($members->toArray(),$personM->toArray()));

        return view('user.team.downline',[
            'team_members'=>$teams_members,
            'person_members'=>$person,
            'left_team'=>$left_team,
            'right_team'=>$right_team,
            'members'=>$members,
            'personM'=>$personM,
            'total_members'=>$total
        ]);
    }


    public function freereferrals(){
        return view('user.team.freereferrals');
    }
    public function paidreferrals(){
        return view('user.team.paidreferrals');
    }

    private function getUserTree($user)
{
    $children = $user->ownedTeams()->get();
    $owner = Teams::where('team_user_id', $user->id)->first(); 
    return [
        // 'id' => ($owner->user_id??$user->id).".".$owner->user->id,
        'id'=>$user->id,
        'name' => $user->name,
        "owner"=> ["name"=>$owner->user->name,"id"=>$owner->user->id],
        'profile'=> $user->profile_photo_path,
        'children' => $children->map(fn($child) => $this->getUserTree($child->teamMember))->toArray(),
    ];
}



public function getTeamTree(Request $request,$id){


    // dd($id);

    $user = User::where("id",$id)->first();
    // dd($user);
    $tree = $this->getUserTree($user);
    // dd($tree);

    return response()->json($tree);

}
    public function teamStructure()
    {
        
        

        return view('user.team.team-structure');
    }

    public function teamGenealogy()
    {

        return view('user.team.team-genealogy');
    }


    public function volumePoints()
    {

        return view('user.team.volume-points');
    }

    public function teamRanking()
    {

        return view('user.team.team-ranking');
    }

    public function teamsGroups()
    {

        return view('user.team.groups');
    }

    public function myAwards()
    {

        return view('user.team.myawards');
    }



    public function focoinPoint()
    {

        return view('user.team.focoin-point');
    }

    public function foneCommission()
    {

        return view('user.team.fone-commission');
    }

    public function fomoCommission()
    {

        return view('user.team.fomo-commission');
    }

    public function merchant()
    {
        return view('user.team.merchant');
    }


    public function subscription()
    {
        $user = Auth::User();
        $trans = $user->transactions()
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);
        $transactions = $trans->filter(function ($trx){
            return $trx->transaction_type == 'SUBSCRIPTION';
        });

      $balance = $user->investments()->where('status',1)->sum('amount');
    //   dd($balance);
    // $balance = $user->DailyIncomes()->where("is_redeemed",false)->sum("amount");

        return view('user.subscription',['transaction'=>$transactions,'balance'=>$balance.'.00']); // Adjust the view path as needed
    }

    public function getDirectAndIndirectReferrals()
    {
        $directReferrals = Auth::user()->referrals;;


        $indirectReferrals = collect();

        foreach ($directReferrals as $referral) {
            $indirectReferrals = $indirectReferrals->merge($referral->referrals);
        }

        return [
            'direct' => $directReferrals,
            'indirect' => $indirectReferrals,
        ];
    }


    public function teambuilding()
    {


        $data = $this->getDirectAndIndirectReferrals();
        // dd($data);
        return view('user.teambuilding',["direct"=>$data['direct'],"indirect"=>$data['indirect']]);
        // Adjust the view path as needed
    }


    // ─────────────────────────────────────────────────────────────────────────
    //  PACKAGE RENEWAL  (every 30 days within the 100-day package window)
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Show the renewal details page.
     * The user sees:
     *  - Their current Trading Voucher balance (deducted from)
     *  - The renewal fee (= the original package amount)
     *  - The current token price (set by admin via token_settings table)
     *  - How many tokens they will receive
     *  - Which renewal cycle this is (1 / 3)
     */
    /**
     * Renewal page — shows Trading Voucher balance, renewal fee,
     * tokens to receive, etc.
     *
     * Per spec (Issue 2):
     *   • Package runs 100 days → 3 monthly renewals (day 30, 60, 90).
     *     The 3rd renewal also covers the leftover 10 days (day 91-100)
     *     so the user doesn't lose income on the partial month.
     *   • Renewal fee = what 30 days of Trading Voucher income would
     *     accumulate (= daily_income × 75% × 30).
     *   • For the LAST renewal covering N leftover days (<30), the fee is
     *     pro-rated: monthly_fee × (N / 30).
     */
    public function packageRenewPage()
    {
        $user = Auth::user();

        // Active paid package
        $activePayment = \App\Models\Payment::where('user', $user->id)
            ->where('is_expired', false)
            ->where('status', '1')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$activePayment) {
            return redirect()->route('user.dashboard')
                ->with('error', 'You have no active package to renew.');
        }

        // Trading Voucher balance  (acc_type = TRADING in ChartAccount)
        $tradingVoucherBalance = $user->ChartAccount()
            ->where('acc_type', 'TRADING')
            ->sum('amount');

        // How many renewals has this user already done for this payment?
        $renewalsDone = \App\Models\PackageRenewal::where('user_id', $user->id)
            ->where('payment_id', $activePayment->id)
            ->count();

        // Package runs 100 days → renewals at day 30, 60, 90  (max 3)
        // max_renewals derived from adventure package duration
        $activePkg2  = \App\Models\Adventures::find($activePayment->payable_id);
        $pkgDuration = $activePkg2 ? (int) $activePkg2->duration : 100;
        $maxRenewals = RenewalCalculator::maxRenewals($pkgDuration);

        $renewalNumber = $renewalsDone + 1;

        if ($renewalNumber > $maxRenewals) {
            return redirect()->route('user.dashboard')
                ->with('info', 'You have completed all renewals for this package cycle.');
        }

        // ── Renewal fee (Issue 2) ── delegated to RenewalCalculator
        // Math is identical to what was inline before; centralised so it's testable.
        $calc = RenewalCalculator::compute(
            packageAmount:   (float) $activePayment->amount,
            percentage:      (float) ($activePkg2->percentage ?? 0),
            packageDuration: $pkgDuration,
            renewalNumber:   $renewalNumber,
            renewalsDone:    $renewalsDone,
            renewalPrice:    \App\Models\TokenSetting::renewalPrice(),
        );

        $monthlyFee    = $calc['monthly_fee'];
        $renewalFee    = $calc['renewal_fee'];
        $daysCovered   = $calc['days_covered'];
        $isPartialFee  = $calc['is_partial'];
        $leftoverDays  = $calc['leftover_days'];
        $tokenPrice    = \App\Models\TokenSetting::renewalPrice();
        $tokensToReceive = $calc['tokens_received'];

        // Is renewal due? (every 30 days from package purchase)
        $packageStart   = Carbon::parse($activePayment->created_at);
        $renewalDueDate = $packageStart->copy()->addDays(30 * $renewalNumber);
        // No 1-day-early grace (Issue 6): renewal is due on the exact day or after.
        $isDue          = Carbon::now()->gte($renewalDueDate->startOfDay());

        // Pre-computed schedule for the upcoming renewal display
        $schedule = RenewalCalculator::schedule(
            packageAmount:   (float) $activePayment->amount,
            percentage:      (float) ($activePkg2->percentage ?? 0),
            packageDuration: $pkgDuration,
            renewalPrice:    $tokenPrice,
            packageStart:    $packageStart,
        );

        return view('user.package-renew', [
            'activePayment'        => $activePayment,
            'tradingVoucherBalance'=> $tradingVoucherBalance,
            'renewalFee'           => $renewalFee,
            'monthlyFee'           => $monthlyFee,
            'daysCovered'          => $daysCovered,
            'isPartialFee'         => $isPartialFee,
            'leftoverDays'         => $leftoverDays,
            'pkgDuration'          => $pkgDuration,
            'tokenPrice'           => $tokenPrice,
            'tokensToReceive'      => $tokensToReceive,
            'renewalNumber'        => $renewalNumber,
            'maxRenewals'          => $maxRenewals,
            'renewalDueDate'       => $renewalDueDate,
            'isDue'                => $isDue,
            'schedule'             => $schedule,
        ]);
    }

    /**
     * Process the Trading Voucher renewal payment.
     *
     * Flow:
     *  1. Validate user has enough Trading Voucher balance
     *  2. Deduct renewal fee from TRADING ChartAccount
     *  3. Record a PackageRenewal row
     *  4. Log a Transaction
     *  5. Extend payment expiration by 30 days
     */
    public function packageRenewPay(Request $request)
    {
        $user = Auth::user();

        // Active package
        $activePayment = \App\Models\Payment::where('user', $user->id)
            ->where('is_expired', false)
            ->where('status', '1')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$activePayment) {
            return redirect()->route('user.dashboard')
                ->with('error', 'No active package found.');
        }

        // How many renewals done already?
        $renewalsDone  = \App\Models\PackageRenewal::where('user_id', $user->id)
            ->where('payment_id', $activePayment->id)
            ->count();
        $renewalNumber = $renewalsDone + 1;

        // Calculate maxRenewals from package duration
        $activePkg2ForPay = \App\Models\Adventures::find($activePayment->payable_id);
        $pkgDurationPay   = $activePkg2ForPay ? (int) $activePkg2ForPay->duration : 100;
        $maxRenewals      = RenewalCalculator::maxRenewals($pkgDurationPay);

        if ($renewalNumber > $maxRenewals) {
            return redirect()->route('user.dashboard')
                ->with('info', 'All renewals for this package cycle are complete.');
        }

        // ── Renewal fee (Issue 2) — delegated to RenewalCalculator ──
        $calc = RenewalCalculator::compute(
            packageAmount:   (float) $activePayment->amount,
            percentage:      (float) ($activePkg2ForPay->percentage ?? 0),
            packageDuration: $pkgDurationPay,
            renewalNumber:   $renewalNumber,
            renewalsDone:    $renewalsDone,
            renewalPrice:    \App\Models\TokenSetting::renewalPrice(),
        );

        $renewalFee   = $calc['renewal_fee'];
        $daysCovered  = $calc['days_covered'];
        $isLastRenewal = $calc['is_last_renewal'];
        $isPartialFee = $calc['is_partial'];

        // Current Trading Voucher balance
        $currentBalance = $user->ChartAccount()
            ->where('acc_type', 'TRADING')
            ->sum('amount');

        if ($currentBalance < $renewalFee) {
            return redirect()->route('packageRenew')
                ->with('error', 'Insufficient Trading Voucher balance. You need $' . number_format($renewalFee, 2) . ' but have $' . number_format($currentBalance, 2) . '.');
        }

        // Token price + tokens already computed via RenewalCalculator above
        $tokenPrice    = \App\Models\TokenSetting::renewalPrice();
        $tokensReceived = $calc['tokens_received'];

        // 1. Deduct from TRADING ChartAccount
        $newBalance = $currentBalance - $renewalFee;
        $user->ChartAccount()
            ->where('acc_type', 'TRADING')
            ->update(['amount' => $newBalance]);

        // 2. Award tokens to AVAILABLE_TOKEN account
        // Renewal tokens go to AVAILABLE_TOKEN (earned via Trading Voucher), not LOCKED_TOKEN.
        // Formula: trading_voucher_used / token_price  → available tokens
        $currentAvailable = $user->ChartAccount()
            ->where('acc_type', 'AVAILABLE_TOKEN')
            ->sum('amount');
        \App\Models\ChartAccount::updateOrCreate(
            ['user_id' => $user->id, 'acc_type' => 'AVAILABLE_TOKEN'],
            ['amount'  => $currentAvailable + $tokensReceived]
        );

        // 3. Record the renewal
        $renewedAt      = Carbon::now();
        // next_renewal_due: null if this was the last renewal for this package duration
        $nextRenewalDue = $renewalNumber < $maxRenewals
            ? $renewedAt->copy()->addDays(30)
            : null;

        $trxNo = Transaction::generateTransactionNo();

        \App\Models\PackageRenewal::create([
            'user_id'               => $user->id,
            'payment_id'            => $activePayment->id,
            'fcpackage_id'          => null, // token price now from token_settings table
            'amount_paid'           => $renewalFee,
            'token_price_at_renewal'=> $tokenPrice,
            'tokens_received'       => $tokensReceived,
            'renewal_number'        => $renewalNumber,
            'renewed_at'            => $renewedAt,
            'next_renewal_due'      => $nextRenewalDue,
            'transaction_no'        => $trxNo,
            'status'                => 'completed',
        ]);

        // 4. Log a Transaction
        Transaction::create([
            'user_id'             => $user->id,
            'transaction_no'      => $trxNo,
            'transaction_type'    => 'PACKAGE_RENEWAL',
            'receiver_id'         => 0,
            'transaction_details' => json_encode([
                'date'             => $renewedAt->toDateTimeString(),
                'trx_type'         => 'Package Renewal #' . $renewalNumber,
                'amount'           => $renewalFee,
                'token_price'      => $tokenPrice,
                'tokens_received'  => $tokensReceived,
                'renewal_number'   => $renewalNumber,
                'days_covered'     => $daysCovered,
                'is_partial'       => $isLastRenewal,
                'next_renewal_due' => $nextRenewalDue?->toDateString(),
                'status'           => 'success',
            ]),
        ]);

        // 5. DO NOT extend the package expiration date.
        // Per spec: the package has a fixed duration (set at purchase) and expires
        // at that fixed date regardless of renewals. Renewals just unlock the
        // income for the next window(s); the package itself ends at the original
        // duration (100, 200, 600, etc.) — see CalculateDailyIncome::handle().

        $msg = "Package renewed successfully! You received " . number_format($tokensReceived, 4) . " tokens.";
        if ($nextRenewalDue) {
            $msg .= " Next renewal due: " . $nextRenewalDue->format('d M Y') . ".";
        } else {
            $msg .= " This was your final renewal for this package cycle.";
        }

        return redirect()->route('user.dashboard')->with('message', $msg);

    }
    
    // ═══════════════════════════════════════════════════════════════════════
    //  TOKEN TRANSFER — user sends LOCKED tokens to another user
    // ═══════════════════════════════════════════════════════════════════════

    public function tokenTransferPage()
    {
        $user    = Auth::user();
        $freeBal = $user->ChartAccount()->where('acc_type', 'FREE_TOKEN')->sum('amount');
        $symbol  = \App\Models\TokenSetting::currentSymbol();
        $history = \App\Models\Transaction::where('user_id', $user->id)
                    ->where('transaction_type', 'TOKEN_TRANSFER')
                    ->latest()->take(20)->get()
                    ->map(function ($t) {
                        $d = json_decode($t->transaction_details, true) ?? [];
                        $t->to_name       = $d['to_name'] ?? ($d['to_user'] ?? '—');
                        $t->to_username   = $d['to_username'] ?? ($d['to_user_column'] ?? '—');
                        $t->to_activation = $d['to_activation'] ?? '—';
                        $t->tok_amt       = $d['token_amount'] ?? 0;
                        return $t;
                    });

        return view('user.token.transfer', compact('freeBal', 'symbol', 'history'));
    }

    /** AJAX: look up recipient by activation code OR username (`users.user`). */
    public function tokenTransferLookup(Request $request)
    {
        $identifier = trim((string) $request->query('identifier', $request->query('recipient', $request->query('q', ''))));

        if ($identifier === '') {
            return response()->json([
                'found' => false,
                'message' => 'Enter recipient activation code or username.',
            ]);
        }

        $recipient = $this->findTokenTransferRecipient($identifier, Auth::id());

        if (!$recipient) {
            return response()->json([
                'found' => false,
                'message' => 'No user found with that activation code or username.',
            ]);
        }

        return response()->json([
            'found'      => true,
            'name'       => $recipient->name,
            'username'   => $recipient->user,
            'activation' => $recipient->activation,
        ]);
    }

    private function findTokenTransferRecipient(string $identifier, ?int $excludeUserId = null)
    {
        $identifier = trim($identifier);

        if ($identifier === '') {
            return null;
        }

        return \App\Models\User::query()
            ->where(function ($query) use ($identifier) {
                $query->where('activation', $identifier)
                    ->orWhere('user', $identifier);
            })
            ->when($excludeUserId, function ($query) use ($excludeUserId) {
                $query->where('id', '!=', $excludeUserId);
            })
            ->select('id', 'name', 'user', 'activation')
            ->first();
    }

    public function tokenTransfer(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'recipient_identifier' => 'required|string|max:100',
            'token_amount'         => 'required|numeric|min:1',
        ], [
            'recipient_identifier.required' => 'Please enter recipient activation code or username.',
        ]);

        $identifier = trim((string) $request->recipient_identifier);
        $recipient  = $this->findTokenTransferRecipient($identifier, $user->id);

        if (!$recipient) {
            return back()->withInput()->with('error', 'No user found with that activation code or username.');
        }

        if ($recipient->id === $user->id) {
            return back()->withInput()->with('error', 'You cannot transfer tokens to yourself.');
        }

        if ($message = $this->transactionPasswordError($request, $user)) {
            return back()->withInput()->with('error', $message);
        }

        $tokenAmount = (float) $request->token_amount;
        $freeBal     = $user->ChartAccount()->where('acc_type', 'FREE_TOKEN')->sum('amount');

        if ($freeBal < $tokenAmount) {
            return back()->withInput()->with('error', 'Insufficient Free Token balance. You have ' . number_format($freeBal, 0) . ' tokens.');
        }

        // Deduct from sender FREE_TOKEN
        $user->ChartAccount()->where('acc_type', 'FREE_TOKEN')
             ->update(['amount' => $freeBal - $tokenAmount]);

        // Add to recipient FREE_TOKEN
        $recipientBal = $recipient->ChartAccount()->where('acc_type', 'FREE_TOKEN')->sum('amount');
        \App\Models\ChartAccount::updateOrCreate(
            ['user_id' => $recipient->id, 'acc_type' => 'FREE_TOKEN'],
            ['amount'  => $recipientBal + $tokenAmount]
        );

        $trxNo = \App\Models\Transaction::generateTransactionNo();
        \App\Models\Transaction::create([
            'user_id'             => $user->id,
            'transaction_no'      => $trxNo,
            'transaction_type'    => 'TOKEN_TRANSFER',
            'receiver_id'         => $recipient->id,
            'transaction_details' => json_encode([
                'token_amount' => $tokenAmount,
                'to_name'       => $recipient->name,
                'to_username'   => $recipient->user,
                'to_activation' => $recipient->activation,
                'to_user'       => $recipient->name,
                'from_user'     => $user->name,
                'from_username' => $user->user,
                'date'         => now()->toDateTimeString(),
                'status'       => 'completed',
            ]),
        ]);

        return back()->with('success', number_format($tokenAmount, 0) . ' ' .
            \App\Models\TokenSetting::currentSymbol() . ' transferred to ' . $recipient->name . ' (@' . $recipient->user . ').');
    }

    // ═══════════════════════════════════════════════════════════════════════
    //  TOKEN SWAP — LOCKED tokens → CASHOUT (internal)
    //  Formula: cashout_received = token_amount × coin_value
    // ═══════════════════════════════════════════════════════════════════════

    public function tokenSwapPage()
    {
        $user      = Auth::user();
        $freeBal   = $user->ChartAccount()->where('acc_type', 'FREE_TOKEN')->sum('amount');
        // ── Swap uses `swap_price` (admin-controlled rate for token→cashout conversion) ──
        $swapPrice = \App\Models\TokenSetting::swapPrice();
        // `coin_value` is also shown for reference (display value per token)
        $coinValue = \App\Models\TokenSetting::coinValue();
        $symbol    = \App\Models\TokenSetting::currentSymbol();

        return view('user.token.swap', compact('freeBal', 'swapPrice', 'coinValue', 'symbol'));
    }

    public function tokenSwap(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'token_amount' => 'required|numeric|min:1',
            'transaction_password' => ['required', $this->transactionPasswordRule($user)],
        ]);

        if ($message = $this->transactionPasswordError($request, $user)) {
            return back()->withInput()->with('error', $message);
        }

        $tokenAmount = (float) $request->token_amount;
        $freeBal     = $user->ChartAccount()->where('acc_type', 'FREE_TOKEN')->sum('amount');

        if ($freeBal < $tokenAmount) {
            return back()->with('error', 'Insufficient Free Token balance.');
        }

        // ── Use `swap_price` for the conversion rate (per spec) ──
        $swapPrice   = \App\Models\TokenSetting::swapPrice();
        $coinValue   = \App\Models\TokenSetting::coinValue();
        $cashReceived = round($tokenAmount * $swapPrice, 2);

        // Deduct from FREE_TOKEN
        $user->ChartAccount()->where('acc_type', 'FREE_TOKEN')
             ->update(['amount' => $freeBal - $tokenAmount]);

        // Credit CASHOUT
        $cashBal = $user->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount');
        \App\Models\ChartAccount::updateOrCreate(
            ['user_id' => $user->id, 'acc_type' => 'CASHOUT'],
            ['amount'  => $cashBal + $cashReceived]
        );

        $trxNo = \App\Models\Transaction::generateTransactionNo();
        \App\Models\Transaction::create([
            'user_id'             => $user->id,
            'transaction_no'      => $trxNo,
            'transaction_type'    => 'TOKEN_SWAP',
            'receiver_id'         => 0,
            'transaction_details' => json_encode([
                'token_amount'  => $tokenAmount,
                'swap_price'    => $swapPrice,
                'coin_value'    => $coinValue,
                'cash_received' => $cashReceived,
                'symbol'        => \App\Models\TokenSetting::currentSymbol(),
                'date'          => now()->toDateTimeString(),
                'status'        => 'completed',
            ]),
        ]);

        return back()->with('success', number_format($tokenAmount, 0) . ' tokens swapped for $' .
            number_format($cashReceived, 2) . ' in your Cashout account.');
    }

    // ═══════════════════════════════════════════════════════════════════════
    //  TOKEN WITHDRAWAL — user requests withdrawal to FONE wallet
    //  Admin must approve. Tokens are held (deducted on request).
    // ═══════════════════════════════════════════════════════════════════════

    public function tokenWithdrawPage()
    {
        $user      = Auth::user();
        $freeBal   = $user->ChartAccount()->where('acc_type', 'FREE_TOKEN')->sum('amount');
        $coinValue = \App\Models\TokenSetting::coinValue();
        $symbol    = \App\Models\TokenSetting::currentSymbol();
        $history   = \App\Models\TokenWithdrawal::where('user_id', $user->id)->latest()->get();

        return view('user.token.withdraw', compact('freeBal', 'coinValue', 'symbol', 'history'));
    }

    public function tokenWithdrawRequest(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'token_amount'   => 'required|numeric|min:1',
            'wallet_address' => 'required|string',
            'transaction_password' => ['required', $this->transactionPasswordRule($user)],
        ]);

        if ($message = $this->transactionPasswordError($request, $user)) {
            return back()->withInput()->with('error', $message);
        }

        $tokenAmount = (float) $request->token_amount;
        $freeBal     = $user->ChartAccount()->where('acc_type', 'FREE_TOKEN')->sum('amount');

        if ($freeBal < $tokenAmount) {
            return back()->with('error', 'Insufficient Free Token balance.');
        }

        // Hold the tokens immediately (deducted from FREE_TOKEN; refunded if rejected)
        $user->ChartAccount()->where('acc_type', 'FREE_TOKEN')
             ->update(['amount' => $freeBal - $tokenAmount]);

        $trxNo = \App\Models\Transaction::generateTransactionNo();

        \App\Models\TokenWithdrawal::create([
            'user_id'                => $user->id,
            'token_amount'           => $tokenAmount,
            'coin_value_at_request'  => \App\Models\TokenSetting::coinValue(),
            'wallet_address'         => $request->wallet_address,
            'transaction_no'         => $trxNo,
            'status'                 => 'pending',
        ]);

        \App\Models\Transaction::create([
            'user_id'             => $user->id,
            'transaction_no'      => $trxNo,
            'transaction_type'    => 'TOKEN_WITHDRAWAL_REQUEST',
            'receiver_id'         => 0,
            'transaction_details' => json_encode([
                'token_amount'   => $tokenAmount,
                'wallet_address' => $request->wallet_address,
                'coin_value'     => \App\Models\TokenSetting::coinValue(),
                'date'           => now()->toDateTimeString(),
                'status'         => 'pending',
            ]),
        ]);

        return back()->with('success', 'Token withdrawal request of ' . number_format($tokenAmount, 0) .
            ' ' . \App\Models\TokenSetting::currentSymbol() . ' submitted. Reference: ' . $trxNo . '. Awaiting admin approval.');
    }

    // ═══════════════════════════════════════════════════════════════════════
    //  AVAILABLE → FREE TOKEN TRANSFER
    //  User manually moves tokens from Available Token to Free Token wallet.
    //  Only from Free Token can they transfer/swap/withdraw.
    // ═══════════════════════════════════════════════════════════════════════

    public function availableTokenPage()
    {
        $user         = Auth::user();
        $availableBal = $user->ChartAccount()->where('acc_type', 'AVAILABLE_TOKEN')->sum('amount');
        $freeBal      = $user->ChartAccount()->where('acc_type', 'FREE_TOKEN')->sum('amount');
        $symbol       = \App\Models\TokenSetting::currentSymbol();
        $history      = \App\Models\Transaction::where('user_id', $user->id)
                         ->where('transaction_type', 'AVAILABLE_TO_FREE')
                         ->latest()->take(20)->get()
                         ->map(function ($t) {
                             $d = json_decode($t->transaction_details, true) ?? [];
                             $t->tok_amt = $d['token_amount'] ?? 0;
                             return $t;
                         });

        return view('user.token.available', compact('availableBal', 'freeBal', 'symbol', 'history'));
    }

    public function availableToFree(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'token_amount' => 'required|numeric|min:1',
        ]);

        $tokenAmount  = (float) $request->token_amount;
        $availableBal = $user->ChartAccount()->where('acc_type', 'AVAILABLE_TOKEN')->sum('amount');

        if ($availableBal < $tokenAmount) {
            return back()->with('error', 'Insufficient Available Token balance. You have ' . number_format($availableBal, 0) . ' tokens.');
        }

        // Deduct from AVAILABLE_TOKEN
        $user->ChartAccount()->where('acc_type', 'AVAILABLE_TOKEN')
             ->update(['amount' => $availableBal - $tokenAmount]);

        // Credit FREE_TOKEN
        $freeBal = $user->ChartAccount()->where('acc_type', 'FREE_TOKEN')->sum('amount');
        \App\Models\ChartAccount::updateOrCreate(
            ['user_id' => $user->id, 'acc_type' => 'FREE_TOKEN'],
            ['amount'  => $freeBal + $tokenAmount]
        );

        $trxNo = \App\Models\Transaction::generateTransactionNo();
        \App\Models\Transaction::create([
            'user_id'             => $user->id,
            'transaction_no'      => $trxNo,
            'transaction_type'    => 'AVAILABLE_TO_FREE',
            'receiver_id'         => 0,
            'transaction_details' => json_encode([
                'token_amount' => $tokenAmount,
                'from'         => 'AVAILABLE_TOKEN',
                'to'           => 'FREE_TOKEN',
                'date'         => now()->toDateTimeString(),
                'status'       => 'completed',
            ]),
        ]);

        return back()->with('success', number_format($tokenAmount, 0) . ' ' .
            \App\Models\TokenSetting::currentSymbol() . ' moved to your Free Token wallet.');
    }


    // ═══════════════════════════════════════════════════════════════════════
    //  LOCKED TOKEN INFO PAGE (read-only — no withdrawal from here)
    // ═══════════════════════════════════════════════════════════════════════

    public function lockedTokenPage()
    {
        $user      = Auth::user();
        $lockedBal = $user->ChartAccount()->where('acc_type', 'LOCKED_TOKEN')->sum('amount');
        $symbol    = \App\Models\TokenSetting::currentSymbol();
        $uvpPrice  = \App\Models\TokenSetting::uvpPrice();

        // Active package for this user (any category that credits LOCKED_TOKEN)
        // Per spec: locked tokens are released to Available after package duration ends.
        $package = \App\Models\Payment::where('user', $user->id)
                    ->where('is_expired', false)
                    ->where('status', 1)
                    ->whereIn('category', ['VENTURE', 'FC'])
                    ->orderBy('created_at', 'desc')
                    ->first();

        // Total package amount for the lock-amount example
        $packageAmount = $package ? (float) $package->amount : 0;
        $exampleTokens = $uvpPrice > 0 ? round($packageAmount / $uvpPrice, 0) : 0;

        return view('user.token.locked-info', compact('lockedBal', 'symbol', 'package', 'packageAmount', 'exampleTokens', 'uvpPrice'));
    }

    /**
     * Inline transaction password validator.
     * Avoids autoload issues with custom Rule classes and returns user-friendly messages.
     */
    private function transactionPasswordRule($user)
    {
        return function ($attribute, $value, $fail) use ($user) {
            if (! $user) {
                $fail('Please login first.');
                return;
            }

            if (empty($user->transaction_password)) {
                $fail('Please set your second transaction password first from /user/password.');
                return;
            }

            if (! \Illuminate\Support\Facades\Hash::check((string) $value, $user->transaction_password)) {
                $fail('Second transaction password is wrong.');
            }
        };
    }

    private function transactionPasswordError(Request $request, $user): ?string
    {
        $password = (string) $request->input('transaction_password', '');

        if (! $user) {
            return 'Please login first.';
        }

        if (empty($user->transaction_password)) {
            return 'Please set your second transaction password first from /user/password.';
        }

        if ($password === '') {
            return 'Second transaction password is required.';
        }

        if (! \Illuminate\Support\Facades\Hash::check($password, $user->transaction_password)) {
            return 'Second transaction password is wrong.';
        }

        return null;
    }

}
