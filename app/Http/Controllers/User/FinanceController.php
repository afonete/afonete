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
        $param = trim($request->query("ac", ""));

        $accMap = [
            'Cashout Wallet'  => 'CASHOUT',
            'Payout Wallet'   => 'CASHOUT',
            'CASHOUT'         => 'CASHOUT',
            'Deposit Wallet'  => 'DEPOSIT',
            'Fund Wallet'     => 'DEPOSIT',
            'DEPOSIT'         => 'DEPOSIT',
            'Purchase Wallet' => 'PURCHASE',
            'UPurchase Wallet'=> 'PURCHASE',
            'PURCHASE'        => 'PURCHASE',
            'Reward Wallet'   => 'REWARD',
            'REWARD'          => 'REWARD',
            'FOMO Wallet'     => 'FOMO',
            'FOMO'            => 'FOMO',
            'Trading Wallet'  => 'TRADING_WALLET',
            'radind Wallet'   => 'TRADING_WALLET',
            'TRADING_WALLET'  => 'TRADING_WALLET',
            'TRADING'         => 'TRADING',
        ];

        $accType = $accMap[$param] ?? $param;

        if ($accType === 'DEPOSIT') {
            $approvedDeposit = (float) $user->deposits()->where('status', 'approved')->sum('amount_deposited');
            $usedDeposit     = (float) $user->deposits()->where('status', 'used')->sum('amount_removed');
            $depositBal      = max(0, $approvedDeposit - $usedDeposit);

            // Keep ChartAccount DEPOSIT synced
            ChartAccount::updateOrCreate(
                ['user_id' => $user->id, 'acc_type' => 'DEPOSIT'],
                ['amount'  => $depositBal]
            );

            return response()->json([
                "balance" => number_format($depositBal, 2, '.', ''),
                "message" => "changed account"
            ]);
        }

        $charts = $user->ChartAccount()->where("acc_type", $accType)->first();

        // Fallback for Trading Wallet if TRADING_WALLET is empty, check TRADING
        if (!$charts && $accType === 'TRADING_WALLET') {
            $charts = $user->ChartAccount()->where("acc_type", 'TRADING')->first();
        }

        $bal = $charts ? (float) $charts->amount : 0.00;

        return response()->json([
            "balance" => number_format($bal, 2, '.', ''),
            "message" => "changed account"
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
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        if ($message = $this->transactionPasswordError($request, $user)) {
            return back()->withInput()->with('error-touser', $message);
        }

        $identifier = trim($request->input('recipient', $request->input('recipient_email', '')));

        if (empty($identifier)) {
            return back()->with('error-touser', 'Please enter a recipient Username or 7-Digit Transfer Code.');
        }

        $amount = (float) $request->amount;
        $recipient = User::where('user', $identifier)
            ->orWhere('transfer_code', $identifier)
            ->orWhere('email', $identifier)
            ->first();

        if (!$recipient) {
            return back()->with('error-touser', "No member found with Username, Transfer Code, or Email '{$identifier}'.");
        }

        if ($recipient->id === $user->id) {
            return back()->with('error-touser', 'You cannot transfer funds to yourself.');
        }

        $cashoutBal = (float) $user->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount');
        if ($cashoutBal < $amount) {
            return back()->with('error-touser', 'Insufficient Funds in Cashout Wallet. You have $' . number_format($cashoutBal, 2) . ' available.');
        }

        DB::transaction(function () use ($user, $recipient, $amount) {
            // Debit sender's CASHOUT
            $user->ChartAccount()->where('acc_type', 'CASHOUT')
                 ->update(['amount' => $user->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount') - $amount]);

            // Credit recipient's CASHOUT
            $recipientBal = (float) $recipient->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount');
            ChartAccount::updateOrCreate(
                ['user_id' => $recipient->id, 'acc_type' => 'CASHOUT'],
                ['amount'  => $recipientBal + $amount]
            );

            $senderTrxNo = Transaction::generateTransactionNo();
            $recipientTrxNo = Transaction::generateTransactionNo();

            // Sender Record
            Transaction::create([
                'user_id'             => $user->id,
                'transaction_no'      => $senderTrxNo,
                'transaction_type'    => 'CASHOUT_TRANSFER_SENT',
                'receiver_id'         => $recipient->id,
                'transaction_details' => json_encode([
                    'amount'         => $amount,
                    'currency'       => 'USDT',
                    'trx_type'       => 'Member to Member Cashout Transfer',
                    'to_username'    => $recipient->user,
                    'to_code'        => $recipient->transfer_code,
                    'to_email'       => $recipient->email,
                    'to_name'        => $recipient->name,
                    'date'           => now()->toDateTimeString(),
                    'status'         => 'completed',
                ]),
            ]);

            // Recipient Record
            Transaction::create([
                'user_id'             => $recipient->id,
                'transaction_no'      => $recipientTrxNo,
                'transaction_type'    => 'CASHOUT_TRANSFER_RECEIVED',
                'receiver_id'         => $user->id,
                'transaction_details' => json_encode([
                    'amount'         => $amount,
                    'currency'       => 'USDT',
                    'trx_type'       => 'Member to Member Cashout Transfer',
                    'from_username'  => $user->user,
                    'from_code'      => $user->transfer_code,
                    'from_email'     => $user->email,
                    'from_name'      => $user->name,
                    'date'           => now()->toDateTimeString(),
                    'status'         => 'completed',
                ]),
            ]);
        });

        return back()->with('success-touser',
            '$' . number_format($amount, 2) . ' Cashout USD successfully transferred to @' . ($recipient->user ?: $recipient->name) . ' (Transfer Code: ' . $recipient->transfer_code . ').'
        );
    }

    /**
     * AJAX lookup for CASHOUT transfer: find user by Username, Transfer Code, or Email.
     */
    public function cashoutTransferLookup(Request $request)
    {
        $identifier = trim((string) $request->query('query', $request->query('email', $request->query('q', ''))));

        if (empty($identifier)) {
            return response()->json(['found' => false, 'message' => 'Please enter Username or 7-Digit Transfer Code.']);
        }

        $recipient = User::where('id', '!=', Auth::id())
            ->where(function ($q) use ($identifier) {
                $q->where('user', $identifier)
                  ->orWhere('transfer_code', $identifier)
                  ->orWhere('email', $identifier);
            })
            ->select('id', 'name', 'user', 'transfer_code', 'email')
            ->first();

        if (!$recipient) {
            return response()->json(['found' => false, 'message' => 'No member found with that Username, Transfer Code, or Email.']);
        }

        return response()->json([
            'found'         => true,
            'name'          => $recipient->name,
            'username'      => $recipient->user,
            'transfer_code' => $recipient->getTransferCode(),
            'email'         => $recipient->email,
        ]);
    }


    public function transferToTradingAccount (Request $request){
        $user = Auth::User();
        $validatedData = $request->validate([
            'amount' => 'required|max:25',
        ]);

        if ($message = $this->transactionPasswordError($request, $user)) {
            return back()->withInput()->with('error-tr', $message);
        }

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

        if ($message = $this->transactionPasswordError($request, $user)) {
            return back()->withInput()->with('error-trx', $message);
        }
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

    public function downline(Request $request)
    {
        $user = Auth::user();

        // 1. Walk the ENTIRE network tree (Direct + Indirect downline)
        $allDownlineUsers = collect();
        $queue = collect([$user->id]);

        while ($queue->isNotEmpty()) {
            $currentId = $queue->shift();
            $directs = User::where('referee_id', $currentId)->get();

            foreach ($directs as $direct) {
                if (!$allDownlineUsers->contains('id', $direct->id)) {
                    $allDownlineUsers->push($direct);
                    $queue->push($direct->id);
                }
            }
        }

        // Direct Referrals (Level 1)
        $directReferrals = User::where('referee_id', $user->id)->get();
        $directCount     = $directReferrals->count();

        // Total Direct + Indirect Referral Count
        $totalNetworkCount = $allDownlineUsers->count();

        // Direct Referral Investment Total ($ USD)
        $directInvestment = \App\Models\Payment::whereIn('user', $directReferrals->pluck('id'))
            ->where('status', 1)
            ->sum(\DB::raw('CAST(COALESCE(paid, amount, 0) AS DECIMAL(12,2))'));

        // Total Network Investment ($ USD) (Direct + Indirect)
        $totalNetworkInvestment = \App\Models\Payment::whereIn('user', $allDownlineUsers->pluck('id'))
            ->where('status', 1)
            ->sum(\DB::raw('CAST(COALESCE(paid, amount, 0) AS DECIMAL(12,2))'));

        // Left vs Right Team Counts (Direct + Indirect)
        $leftCount = 0;
        $rightCount = 0;

        foreach ($allDownlineUsers as $downlineUser) {
            $teamSide = $downlineUser->teamSide ? $downlineUser->teamSide->side : null;
            if ($teamSide === 'LEFT') {
                $leftCount++;
            } elseif ($teamSide === 'RIGHT') {
                $rightCount++;
            } else {
                $rootSide = $this->determineRootTeamSide($user->id, $downlineUser->id);
                if ($rootSide === 'LEFT') $leftCount++;
                elseif ($rootSide === 'RIGHT') $rightCount++;
            }
        }

        // Table List: Combine Direct + Indirect Downline Users (Sorted by newest)
        $sortedMembers = $allDownlineUsers->sortByDesc('created_at')->values();

        // Paginate table list by 10 records per page
        $page    = (int) $request->query('page', 1);
        $perPage = 10;
        $offset  = ($page - 1) * $perPage;

        $paginatedMembers = new \Illuminate\Pagination\LengthAwarePaginator(
            $sortedMembers->slice($offset, $perPage)->values(),
            $sortedMembers->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('user.team.downline', [
            'team_members'     => $totalNetworkCount,                            // Total direct and indirect referral count
            'person_members'   => '$' . number_format($totalNetworkInvestment, 2), // Total network investment (Direct and indirect)
            'person_customers' => $directCount,                                  // Total direct referral count
            'merchants'        => '$' . number_format($directInvestment, 2),       // Total Direct referral investment
            'total_members'    => $totalNetworkCount,                            // Total referral (Direct and indirect)
            'left_team'        => $leftCount,                                    // Total referral on left side
            'right_team'       => $rightCount,                                   // Total referral on right side
            'members'          => $paginatedMembers,                             // Paginated table list (10 per page)
            'personM'          => collect(),                                     // Empty collection for legacy compatibility
        ]);
    }

    private function determineRootTeamSide(int $rootUserId, int $targetUserId): ?string
    {
        $current = User::find($targetUserId);
        $side = null;

        while ($current && $current->referee_id && $current->referee_id !== $rootUserId) {
            if ($current->teamSide) {
                $side = $current->teamSide->side;
            }
            $current = User::find($current->referee_id);
        }

        if ($current && $current->referee_id === $rootUserId && $current->teamSide) {
            return $current->teamSide->side;
        }

        return $side;
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
        $user = Auth::user();
        $adventures = \App\Models\Adventures::all();

        $approvedDeposit = (float) $user->deposits()->where('status', 'approved')->sum('amount_deposited');
        $usedDeposit     = (float) $user->deposits()->where('status', 'used')->sum('amount_removed');
        $depositBalance  = max(0, $approvedDeposit - $usedDeposit);

        $highestUvpAmount = $user->highestUvpPackageAmount();

        // Recursively traverse the full downline network (Direct + Indirect)
        $directLeft  = \App\Models\Teams::where('user_id', $user->id)->where('side', 'LEFT')->get();
        $directRight = \App\Models\Teams::where('user_id', $user->id)->where('side', 'RIGHT')->get();

        $collectDownline = function ($directTeams, $branchSide) use ($user) {
            $result = collect();
            $visitedUserIds = [$user->id];
            $queue = collect();

            foreach ($directTeams as $t) {
                if ($t->teamMember) {
                    $queue->push([
                        'user'         => $t->teamMember,
                        'level'        => 1,
                        'is_direct'    => true,
                        'sponsor_user' => $user->user,
                        'sponsor_id'   => $user->id,
                        'branch_side'  => $branchSide,
                    ]);
                    $visitedUserIds[] = $t->teamMember->id;
                }
            }

            while ($queue->isNotEmpty()) {
                $item = $queue->shift();
                $m = $item['user'];

                $result->push([
                    'id'            => $m->id,
                    'user'          => $m->user ?? 'N/A',
                    'name'          => $m->name ?? 'N/A',
                    'transfer_code' => $m->transfer_code ?? '—',
                    'package'       => !empty($m->has_paid_package) && !in_array(strtolower(trim($m->has_paid_package)), ['no', 'standard', '']) ? strtoupper($m->has_paid_package) : 'FREE',
                    'joined'        => $m->created_at ? $m->created_at->format('M d, Y') : 'N/A',
                    'created_at'    => $m->created_at,
                    'level'         => $item['level'],
                    'is_direct'     => $item['is_direct'],
                    'sponsor_user'  => $item['sponsor_user'],
                    'sponsor_id'    => $item['sponsor_id'],
                    'branch_side'   => $item['branch_side'],
                ]);

                // Find next level downlines
                $nextTeams = \App\Models\Teams::where('user_id', $m->id)->get();
                foreach ($nextTeams as $nt) {
                    if ($nt->teamMember && !in_array($nt->teamMember->id, $visitedUserIds)) {
                        $visitedUserIds[] = $nt->teamMember->id;
                        $queue->push([
                            'user'         => $nt->teamMember,
                            'level'        => $item['level'] + 1,
                            'is_direct'    => false,
                            'sponsor_user' => $m->user,
                            'sponsor_id'   => $m->id,
                            'branch_side'  => $item['branch_side'],
                        ]);
                    }
                }
            }

            // Order chronologically by created_at ASC ("base on how they came")
            return $result->sortBy('created_at')->values();
        };

        $leftMembers  = $collectDownline($directLeft, 'LEFT');
        $rightMembers = $collectDownline($directRight, 'RIGHT');

        return view('user.team.team-structure', compact('adventures', 'depositBalance', 'highestUvpAmount', 'leftMembers', 'rightMembers'));
    }

    public function registerTeamMemberFromDeposit(Request $request)
    {
        $sponsor = Auth::user();

        // 1. Calculate sponsor's available Deposit Wallet Balance
        $approvedDeposit = (float) $sponsor->deposits()->where('status', 'approved')->sum('amount_deposited');
        $usedDeposit     = (float) $sponsor->deposits()->where('status', 'used')->sum('amount_removed');
        $availableDeposit = max(0, $approvedDeposit - $usedDeposit);

        // 2. Validate form fields
        $request->validate([
            'name'         => 'required|string|max:255',
            'user'         => 'required|string|min:4|max:10|unique:users,user',
            'email'        => 'required|email|max:255|unique:users,email',
            'phone'        => 'required|string|max:50',
            'country'      => 'required|string|max:100',
            'password'     => 'required|string|min:6|confirmed',
            'side'         => 'required|in:LEFT,RIGHT',
            'package_id'   => 'required|exists:adventures,id',
            'amount'       => 'required|numeric|min:1',
        ], [
            'user.unique'  => 'This username is already taken. Please choose another username.',
            'email.unique' => 'An account with this email address already exists.',
            'side.in'      => 'Please select either LEFT or RIGHT team placement.',
        ]);

        if ($message = $this->transactionPasswordError($request, $sponsor)) {
            return back()->withInput()->with('error', $message);
        }

        $amount = (float) $request->amount;
        $adventure = \App\Models\Adventures::findOrFail($request->package_id);

        // Check min/max for package
        $min = (float) ($adventure->min_amount ?? 0);
        $max = (float) ($adventure->max_amount ?? 0);
        if ($min > 0 && $amount < $min) {
            return back()->withInput()->with('error', "Minimum investment amount for {$adventure->name} is $" . number_format($min, 2) . ".");
        }
        if ($max > 0 && $amount > $max) {
            return back()->withInput()->with('error', "Maximum investment amount for {$adventure->name} is $" . number_format($max, 2) . ".");
        }

        // Check sponsor's deposit wallet balance
        if ($amount > $availableDeposit) {
            return back()->withInput()->with('error', "Insufficient deposit balance. You have $" . number_format($availableDeposit, 2) . " in your deposit wallet, but $" . number_format($amount, 2) . " is required for this activation.");
        }

        // 3. Create the new team member user account
        $newUser = User::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'user'             => $request->user,
            'password'         => \Illuminate\Support\Facades\Hash::make($request->password),
            'phone'            => $request->phone,
            'country'          => $request->country,
            'activation'       => rand(1111111, 9999999),
            'referee_id'       => $sponsor->id, // ALWAYS link to sponsor for referral commissions
            'father'           => null,
            'has_request'      => 'approved',
            'contract'         => 'Not Signed', // Contract NOT signed upon registration; user signs on dashboard later
            'has_paid_package' => $adventure->name ?: $adventure->plan,
            'has_free_package' => 'no',
        ]);

        // 4. Create team placement
        \App\Models\Teams::create([
            'user_id'      => $sponsor->id,
            'team_user_id' => $newUser->id,
            'side'         => strtoupper($request->side),
        ]);

        // 5. Deduct amount from sponsor's deposit balance
        $lastDeposit = \App\Models\Deposits::where('user_id', $sponsor->id)
            ->whereNotNull('user_wallet_address')
            ->latest()
            ->first();

        $userWalletAddress = ($lastDeposit && !empty($lastDeposit->user_wallet_address))
            ? $lastDeposit->user_wallet_address
            : 'INTERNAL_DEPOSIT_WALLET';

        $network = ($lastDeposit && !empty($lastDeposit->network))
            ? $lastDeposit->network
            : 'TRC-20';

        $trxNo = \App\Models\Deposits::generateTransactionNo();
        \App\Models\Deposits::create([
            'user_id'             => $sponsor->id,
            'amount_deposited'    => 0,
            'amount_removed'      => $amount,
            'currency_type'       => 'DOLLAR',
            'deposit_method'      => 'DEPOSIT_WALLET_TEAM_ACTIVATION',
            'user_wallet_address' => $userWalletAddress,
            'network'             => $network,
            'status'              => 'used',
            'transaction_id'      => $trxNo,
            'comment'             => 'Used deposit balance to register and activate team member @' . $newUser->user . ' (' . $newUser->email . ')',
        ]);

        // Update sponsor's ChartAccount DEPOSIT balance
        $currentDepChart = (float) $sponsor->ChartAccount()->where('acc_type', 'DEPOSIT')->sum('amount');
        \App\Models\ChartAccount::updateOrCreate(
            ['user_id' => $sponsor->id, 'acc_type' => 'DEPOSIT'],
            ['amount'  => max(0, $currentDepChart - $amount)]
        );

        // 6. Create active investment record for new team member (100% identical to normal package activation)
        $newPayment = \App\Services\InvestmentFactory::buildVenture(
            userId:    $newUser->id,
            adventure: $adventure,
            amount:    $amount,
            paid:      $amount,
            status:    1
        );
        $pSaved = $adventure->payments()->save($newPayment);

        // Update new user's package status
        $newUser->update([
            'has_paid_package' => $adventure->name ?: $adventure->plan,
            'has_free_package' => 'no',
            'has_request'      => 'approved',
        ]);

        // Token & Gas Fee crediting on package purchase
        $uvpPrice     = \App\Models\TokenSetting::uvpPrice();
        $lockedTokens = $uvpPrice > 0 ? round($amount / $uvpPrice, 4) : 0;
        $gasFeeTokens = $uvpPrice > 0 ? round(($amount * 20 / 100) / $uvpPrice, 4) : 0;

        // Credit LOCKED_TOKEN to new team member
        \App\Models\ChartAccount::updateOrCreate(
            ['user_id' => $newUser->id, 'acc_type' => 'LOCKED_TOKEN'],
            ['amount'  => $lockedTokens]
        );

        // Credit GAS_FEE (20% internal accounting)
        \App\Models\ChartAccount::updateOrCreate(
            ['user_id' => $newUser->id, 'acc_type' => 'GAS_FEE'],
            ['amount'  => $gasFeeTokens]
        );

        // Record Subscription Transaction for new team member
        $subTrxNo  = \App\Models\Transaction::generateTransactionNo();
        $startDate = \Carbon\Carbon::now();
        $endDate   = $startDate->copy()->addDays((int) $adventure->duration);

        \App\Models\Transaction::create([
            'user_id'          => $newUser->id,
            'transaction_no'   => $subTrxNo,
            'transaction_type' => 'SUBSCRIPTION',
            'receiver_id'      => 0,
            'transaction_details' => json_encode([
                'product'             => $adventure->name ?: $adventure->plan,
                'user'                => $newUser->name,
                'plan'                => $adventure->plan,
                'start_date'          => $startDate->toDateTimeString(),
                'end_date'            => $endDate->toDateTimeString(),
                'package'             => $adventure->name ?: $adventure->plan,
                'price'               => $amount,
                'current_price'       => $amount,
                'token'               => $lockedTokens,
                'current_token'       => $lockedTokens,
                'poolcapital'         => $amount * 80 / 100,
                'current_poolcapital' => $amount * 80 / 100,
                'LP'                  => $amount * 20 / 100,
                'current_LP'          => $amount * 20 / 100,
                'period'              => $adventure->duration . ' days',
                'status'              => 'success',
                'purchase_date'       => $startDate->toDateTimeString(),
                'username'            => $newUser->user,
            ]),
        ]);

        // 7. Credit 10% L1 Referral Bonus to Sponsor immediately
        if ($pSaved) {
            \App\Services\ReferralService::creditForPayment($pSaved);
        }

        return redirect()->route('team.structure')->with('success', "Team member @{$newUser->user} ({$newUser->name}) successfully registered and activated on {$request->side} side! $" . number_format($amount, 2) . " deducted from your deposit balance.");
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
        return redirect()->route('user.referral.rank');
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
    public function packageRenewPage($payment_id = null)
    {
        $user = Auth::user();

        // Always load Trading Voucher balance  (acc_type = TRADING in ChartAccount)
        $tradingVoucherBalance = $user->ChartAccount()
            ->where('acc_type', 'TRADING')
            ->sum('amount');

        if ($payment_id) {
            // SINGLE PACKAGE DETAILED RENEWAL VIEW
            $activePayment = \App\Models\Payment::where('user', $user->id)
                ->where('id', $payment_id)
                ->where('is_expired', false)
                ->where('status', '1')
                ->first();

            if (!$activePayment) {
                return redirect()->route('packageRenew')
                    ->with('error', 'The requested active package was not found or is expired.');
            }

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
                return redirect()->route('packageRenew')
                    ->with('info', 'You have completed all renewals for this package cycle.');
            }

            // ── Renewal fee (Issue 2) ── delegated to RenewalCalculator
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
                'isSingleView'         => true,
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

        // MULTI-PACKAGE RENEWAL DASHBOARD VIEW (NO $payment_id PROVIDED)
        $activePayments = \App\Models\Payment::where('user', $user->id)
            ->where('is_expired', false)
            ->where('status', '1')
            ->orderBy('created_at', 'desc')
            ->get();

        $packagesData = [];

        foreach ($activePayments as $payment) {
            $renewalsDone = \App\Models\PackageRenewal::where('user_id', $user->id)
                ->where('payment_id', $payment->id)
                ->count();

            $activePkg2  = \App\Models\Adventures::find($payment->payable_id);
            $pkgDuration = $activePkg2 ? (int) $activePkg2->duration : 100;
            $maxRenewals = RenewalCalculator::maxRenewals($pkgDuration);

            $renewalNumber = $renewalsDone + 1;
            $allRenewalsDone = $renewalsDone >= $maxRenewals;

            $renewalDueDate = null;
            $isDue = false;
            $renewalFee = 0.0;
            $tokensToReceive = 0.0;

            if (!$allRenewalsDone) {
                $packageStart   = Carbon::parse($payment->created_at);
                $renewalDueDate = $packageStart->copy()->addDays(30 * $renewalNumber);
                $isDue          = Carbon::now()->gte($renewalDueDate->startOfDay());

                $calc = RenewalCalculator::compute(
                    packageAmount:   (float) $payment->amount,
                    percentage:      (float) ($activePkg2->percentage ?? 0),
                    packageDuration: $pkgDuration,
                    renewalNumber:   $renewalNumber,
                    renewalsDone:    $renewalsDone,
                    renewalPrice:    \App\Models\TokenSetting::renewalPrice(),
                );
                $renewalFee      = $calc['renewal_fee'];
                $tokensToReceive = $calc['tokens_received'];
            }

            $packagesData[] = [
                'payment'         => $payment,
                'renewalsDone'    => $renewalsDone,
                'maxRenewals'     => $maxRenewals,
                'renewalNumber'   => $renewalNumber,
                'allRenewalsDone' => $allRenewalsDone,
                'renewalDueDate'  => $renewalDueDate,
                'isDue'           => $isDue,
                'renewalFee'      => $renewalFee,
                'tokensToReceive' => $tokensToReceive,
                'pkgDuration'     => $pkgDuration,
            ];
        }

        return view('user.package-renew', [
            'isSingleView'         => false,
            'tradingVoucherBalance'=> $tradingVoucherBalance,
            'packagesData'         => $packagesData,
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

        $paymentId = $request->input('payment_id');

        if ($paymentId) {
            $activePayment = \App\Models\Payment::where('user', $user->id)
                ->where('id', $paymentId)
                ->where('is_expired', false)
                ->where('status', '1')
                ->first();
        } else {
            // Active package
            $activePayment = \App\Models\Payment::where('user', $user->id)
                ->where('is_expired', false)
                ->where('status', '1')
                ->orderBy('created_at', 'desc')
                ->first();
        }

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
            return redirect()->route('packageRenew', ['payment_id' => $activePayment->id])
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
        $myTransferCode = $user->getTransferCode();

        $history = \App\Models\Transaction::where('user_id', $user->id)
                    ->where('transaction_type', 'TOKEN_TRANSFER')
                    ->latest()->take(20)->get()
                    ->map(function ($t) {
                        $d = json_decode($t->transaction_details, true) ?? [];
                        $t->to_name          = $d['to_name'] ?? ($d['to_user'] ?? '—');
                        $t->to_username      = $d['to_username'] ?? ($d['to_user_column'] ?? '—');
                        $t->to_transfer_code = $d['to_transfer_code'] ?? ($d['to_activation'] ?? '—');
                        $t->tok_amt          = $d['token_amount'] ?? 0;
                        return $t;
                    });

        return view('user.token.transfer', compact('freeBal', 'symbol', 'history', 'myTransferCode'));
    }

    /** AJAX: look up recipient by 7-digit Transfer Code OR username (`users.user`). */
    public function tokenTransferLookup(Request $request)
    {
        $identifier = trim((string) $request->query('identifier', $request->query('recipient', $request->query('q', ''))));

        if ($identifier === '') {
            return response()->json([
                'found' => false,
                'message' => 'Enter recipient username or 7-digit Transfer Code.',
            ]);
        }

        $recipient = $this->findTokenTransferRecipient($identifier, Auth::id());

        if (!$recipient) {
            return response()->json([
                'found' => false,
                'message' => 'No user found with that username or 7-digit Transfer Code.',
            ]);
        }

        return response()->json([
            'found'         => true,
            'name'          => $recipient->name,
            'username'      => $recipient->user,
            'transfer_code' => $recipient->getTransferCode(),
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
                $query->where('user', $identifier)
                    ->orWhere('transfer_code', $identifier);
            })
            ->when($excludeUserId, function ($query) use ($excludeUserId) {
                $query->where('id', '!=', $excludeUserId);
            })
            ->select('id', 'name', 'user', 'transfer_code', 'email')
            ->first();
    }

    public function tokenTransfer(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'recipient_identifier' => 'required|string|max:100',
            'token_amount'         => 'required|numeric|min:1',
        ], [
            'recipient_identifier.required' => 'Please enter recipient username or 7-digit Transfer Code.',
        ]);

        $identifier = trim((string) $request->recipient_identifier);
        $recipient  = $this->findTokenTransferRecipient($identifier, $user->id);

        if (!$recipient) {
            return back()->withInput()->with('error', 'No user found with that username or 7-digit Transfer Code.');
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
                'token_amount'      => $tokenAmount,
                'to_name'           => $recipient->name,
                'to_username'       => $recipient->user,
                'to_transfer_code'  => $recipient->getTransferCode(),
                'to_user'           => $recipient->name,
                'from_user'         => $user->name,
                'from_username'     => $user->user,
                'date'              => now()->toDateTimeString(),
                'status'            => 'completed',
            ]),
        ]);

        return back()->with('success', number_format($tokenAmount, 0) . ' ' .
            \App\Models\TokenSetting::currentSymbol() . ' transferred to ' . $recipient->name . ' (@' . $recipient->user . ', Transfer Code: ' . $recipient->getTransferCode() . ').');
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

    /* ===========================================================
     *  INTERNAL EXCHANGE & WALLET TRANSFERS
     * =========================================================== */

    public function internalExchangePage()
    {
        $user = Auth::user();

        // 6 Internal Wallets + Available Token
        $cashoutBal = (float) $user->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount');

        // 2. Reward Wallet (REWARD — separate from COMMISSION / Referral Bonus)
        $rewardBal = (float) $user->ChartAccount()->where('acc_type', 'REWARD')->sum('amount');

        // 3. Deposit Wallet (Available Deposit Balance = Approved Deposits - Used Deposits)
        $approvedDeposit = (float) $user->deposits()->where('status', 'approved')->sum('amount_deposited');
        $usedDeposit     = (float) $user->deposits()->where('status', 'used')->sum('amount_removed');
        $depositBal      = max(0, $approvedDeposit - $usedDeposit);

        // Keep ChartAccount DEPOSIT synced with available deposit balance
        ChartAccount::updateOrCreate(
            ['user_id' => $user->id, 'acc_type' => 'DEPOSIT'],
            ['amount'  => $depositBal]
        );

        // 4. Fomo Wallet
        $fomoBal = (float) $user->ChartAccount()->where('acc_type', 'FOMO')->sum('amount');

        // 5. Trading Wallet (TRADING_WALLET — separate from Trading Voucher TRADING)
        $tradingBal = (float) $user->ChartAccount()->where('acc_type', 'TRADING_WALLET')->sum('amount');

        // 6. Purchase Wallet
        $purchaseBal = (float) $user->ChartAccount()->where('acc_type', 'PURCHASE')->sum('amount');

        // Available Tokens
        $availableTokenBal = (float) $user->ChartAccount()->where('acc_type', 'AVAILABLE_TOKEN')->sum('amount');

        $swapPrice    = \App\Models\TokenSetting::swapPrice() ?: 0.0025;
        $tradingPrice = \App\Models\TokenSetting::tradingPrice() ?: 0.0025;
        $symbol       = \App\Models\TokenSetting::currentSymbol() ?: 'FOCOIN';

        $transactions = Transaction::where('user_id', $user->id)
            ->whereIn('transaction_type', [
                'TOKEN_SWAP',
                'TOKEN_PURCHASE'
            ])
            ->orderByDesc('created_at')
            ->paginate(5);

        return view('user.internal-exchange', compact(
            'cashoutBal', 'rewardBal', 'depositBal', 'fomoBal',
            'tradingBal', 'purchaseBal', 'availableTokenBal',
            'swapPrice', 'tradingPrice', 'symbol', 'transactions'
        ));
    }

    public function internalWalletTransfer(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'from_wallet' => 'required|string|in:CASHOUT,REWARD,DEPOSIT,FOMO,PURCHASE',
            'to_wallet'   => 'required|string|in:CASHOUT,REWARD,DEPOSIT,FOMO,TRADING_WALLET,PURCHASE',
            'amount'      => 'required|numeric|min:0.01',
        ]);

        if ($message = $this->transactionPasswordError($request, $user)) {
            return back()->withInput()->with('error-internal', $message);
        }

        $from = strtoupper(trim($request->from_wallet));
        $to   = strtoupper(trim($request->to_wallet));
        $amount = (float) $request->amount;

        if ($from === $to) {
            return back()->with('error-internal', 'Source and destination wallets cannot be the same.');
        }

        // Enforce Wallet Transfer Matrix Rules:
        $allowedMatrix = [
            'CASHOUT'    => ['REWARD', 'DEPOSIT', 'FOMO', 'TRADING_WALLET', 'PURCHASE'],
            'REWARD'     => ['DEPOSIT', 'FOMO', 'TRADING_WALLET', 'PURCHASE'],
            'DEPOSIT'    => ['TRADING_WALLET', 'PURCHASE'],
            'FOMO'       => ['DEPOSIT', 'TRADING_WALLET', 'PURCHASE'],
            'PURCHASE'   => ['FOMO', 'TRADING_WALLET'],
        ];

        $allowedTo = $allowedMatrix[$from] ?? [];
        if (!in_array($to, $allowedTo, true)) {
            return back()->with('error-internal', "Transfer from {$from} to {$to} is not permitted per internal exchange rules.");
        }

        // Calculate available source balance
        if ($from === 'DEPOSIT') {
            $approvedDeposit = (float) $user->deposits()->where('status', 'approved')->sum('amount_deposited');
            $usedDeposit     = (float) $user->deposits()->where('status', 'used')->sum('amount_removed');
            $sourceBal       = max(0, $approvedDeposit - $usedDeposit);
        } else {
            $sourceBal = (float) $user->ChartAccount()->where('acc_type', $from)->sum('amount');
        }

        if ($sourceBal < $amount) {
            return back()->with('error-internal', "Insufficient Funds in {$from} Wallet. You have $" . number_format($sourceBal, 2) . ' available.');
        }

        DB::transaction(function () use ($user, $from, $to, $amount, $sourceBal) {
            // Deduct from Source Wallet
            if ($from === 'DEPOSIT') {
                \App\Models\Deposits::create([
                    'user_id'          => $user->id,
                    'amount_deposited' => 0,
                    'amount_removed'   => $amount,
                    'currency_type'    => 'USD',
                    'deposit_method'   => 'INTERNAL_TRANSFER_' . $to,
                    'transaction_id'   => \App\Models\Deposits::generateTransactionNo(),
                    'status'           => 'used',
                    'comment'          => "Deposit balance transferred to {$to} Wallet.",
                ]);
                $newDepBal = max(0, $sourceBal - $amount);
                ChartAccount::updateOrCreate(
                    ['user_id' => $user->id, 'acc_type' => 'DEPOSIT'],
                    ['amount'  => $newDepBal]
                );
            } else {
                $user->ChartAccount()->where('acc_type', $from)
                    ->update(['amount' => $sourceBal - $amount]);
            }

            // Credit Destination Wallet
            if ($to === 'DEPOSIT') {
                $approvedDeposit = (float) $user->deposits()->where('status', 'approved')->sum('amount_deposited');
                $usedDeposit     = (float) $user->deposits()->where('status', 'used')->sum('amount_removed');
                $newDepBal       = max(0, $approvedDeposit - $usedDeposit) + $amount;

                ChartAccount::updateOrCreate(
                    ['user_id' => $user->id, 'acc_type' => 'DEPOSIT'],
                    ['amount'  => $newDepBal]
                );
            } else {
                $destBal = (float) $user->ChartAccount()->where('acc_type', $to)->sum('amount');
                ChartAccount::updateOrCreate(
                    ['user_id' => $user->id, 'acc_type' => $to],
                    ['amount'  => $destBal + $amount]
                );
            }

            $trxNo = Transaction::generateTransactionNo();
            Transaction::create([
                'user_id'             => $user->id,
                'transaction_no'      => $trxNo,
                'transaction_type'    => 'INTERNAL_WALLET_TRANSFER',
                'receiver_id'         => 0,
                'transaction_details' => json_encode([
                    'amount'      => $amount,
                    'from_wallet' => $from,
                    'to_wallet'   => $to,
                    'date'        => now()->toDateTimeString(),
                    'username'    => $user->name,
                ]),
            ]);
        });

        $displayNames = [
            'CASHOUT'        => 'Cashout Wallet',
            'REWARD'         => 'Reward Wallet',
            'DEPOSIT'        => 'Deposit Wallet',
            'FOMO'           => 'Fomo Wallet',
            'TRADING_WALLET' => 'Trading Wallet',
            'PURCHASE'       => 'Purchase Wallet',
        ];

        $fromName = $displayNames[$from] ?? $from;
        $toName   = $displayNames[$to] ?? $to;

        return back()->with('success-internal', "Transferred $" . number_format($amount, 2) . " from {$fromName} to {$toName} successfully!");
    }

    public function userToUserTransfer(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'recipient' => 'required|string|max:255',
            'amount'    => 'required|numeric|min:0.01',
        ]);

        if ($message = $this->transactionPasswordError($request, $user)) {
            return back()->withInput()->with('error', $message);
        }

        $amount     = (float) $request->amount;
        $identifier = trim($request->recipient);

        // Lookup recipient by Username, Transfer Code, or Email
        $recipient = User::where('user', $identifier)
            ->orWhere('transfer_code', $identifier)
            ->orWhere('email', $identifier)
            ->first();

        if (!$recipient) {
            return back()->with('error', "Recipient user ('{$identifier}') not found. Please verify the Username or 7-Digit Transfer Code.");
        }

        if ($recipient->id === $user->id) {
            return back()->with('error', 'You cannot transfer funds to yourself.');
        }

        $cashoutBal = (float) $user->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount');
        if ($cashoutBal < $amount) {
            return back()->with('error', 'Insufficient Funds in Cashout Wallet. You have $' . number_format($cashoutBal, 2) . ' available.');
        }

        DB::transaction(function () use ($user, $recipient, $amount, $cashoutBal) {
            // Debit Sender's Cashout
            $user->ChartAccount()->where('acc_type', 'CASHOUT')
                ->update(['amount' => $cashoutBal - $amount]);

            // Credit Recipient's Cashout
            $recCashout = (float) $recipient->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount');
            ChartAccount::updateOrCreate(
                ['user_id' => $recipient->id, 'acc_type' => 'CASHOUT'],
                ['amount'  => $recCashout + $amount]
            );

            $senderTrxNo = Transaction::generateTransactionNo();
            $recipientTrxNo = Transaction::generateTransactionNo();

            // Sender Transaction
            Transaction::create([
                'user_id'             => $user->id,
                'transaction_no'      => $senderTrxNo,
                'transaction_type'    => 'CASHOUT_TRANSFER_SENT',
                'receiver_id'         => $recipient->id,
                'transaction_details' => json_encode([
                    'amount'         => $amount,
                    'to_user'        => $recipient->user,
                    'to_code'        => $recipient->transfer_code,
                    'to_name'        => $recipient->name,
                    'related_trx_no' => $recipientTrxNo,
                    'date'           => now()->toDateTimeString(),
                ]),
            ]);

            // Recipient Transaction
            Transaction::create([
                'user_id'             => $recipient->id,
                'transaction_no'      => $recipientTrxNo,
                'transaction_type'    => 'CASHOUT_TRANSFER_RECEIVED',
                'receiver_id'         => $user->id,
                'transaction_details' => json_encode([
                    'amount'         => $amount,
                    'from_user'      => $user->user,
                    'from_code'      => $user->transfer_code,
                    'from_name'      => $user->name,
                    'related_trx_no' => $senderTrxNo,
                    'date'           => now()->toDateTimeString(),
                ]),
            ]);
        });

        return back()->with('success', "Transferred $" . number_format($amount, 2) . " Cashout USD to @" . ($recipient->user ?: $recipient->name) . " (Transfer Code: {$recipient->transfer_code}) successfully!");
    }

    public function tradingAction(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'action_type' => 'required|string|in:swap_token,buy_token,transfer_available_token',
            'amount'      => 'required|numeric|min:0.01',
        ]);

        if ($message = $this->transactionPasswordError($request, $user)) {
            return back()->withInput()->with('error', $message);
        }

        $actionType = $request->action_type;
        $amount     = (float) $request->amount;

        $tradingBal  = (float) $user->ChartAccount()->where('acc_type', 'TRADING_WALLET')->sum('amount');
        $availTokBal = (float) $user->ChartAccount()->where('acc_type', 'AVAILABLE_TOKEN')->sum('amount');

        $swapPrice    = \App\Models\TokenSetting::swapPrice() ?: 0.0025;
        $tradingPrice = \App\Models\TokenSetting::tradingPrice() ?: 0.0025;

        if ($actionType === 'swap_token') {
            if ($tradingBal < $amount) {
                return back()->with('error', 'Insufficient Funds in Trading Wallet. Available: $' . number_format($tradingBal, 2));
            }

            $tokensCredited = round($amount / $swapPrice, 4);

            DB::transaction(function () use ($user, $amount, $tradingBal, $tokensCredited, $availTokBal) {
                // Deduct USD from TRADING_WALLET
                $user->ChartAccount()->where('acc_type', 'TRADING_WALLET')->update(['amount' => $tradingBal - $amount]);

                // Credit AVAILABLE_TOKEN
                ChartAccount::updateOrCreate(
                    ['user_id' => $user->id, 'acc_type' => 'AVAILABLE_TOKEN'],
                    ['amount'  => $availTokBal + $tokensCredited]
                );

                $trxNo = Transaction::generateTransactionNo();
                Transaction::create([
                    'user_id'             => $user->id,
                    'transaction_no'      => $trxNo,
                    'transaction_type'    => 'TOKEN_SWAP',
                    'receiver_id'         => 0,
                    'transaction_details' => json_encode([
                        'usd_amount'   => $amount,
                        'tokens'       => $tokensCredited,
                        'rate'         => $swapPrice,
                        'date'         => now()->toDateTimeString(),
                    ]),
                ]);
            });

            return back()->with('success', "Swapped $" . number_format($amount, 2) . " USD from Trading Wallet to " . number_format($tokensCredited, 2) . " Available Tokens at $" . number_format($swapPrice, 4) . "/token!");
        }

        if ($actionType === 'buy_token') {
            if ($tradingBal < $amount) {
                return back()->with('error', 'Insufficient Funds in Trading Wallet. Available: $' . number_format($tradingBal, 2));
            }

            $tokensCredited = round($amount / $tradingPrice, 4);

            DB::transaction(function () use ($user, $amount, $tradingBal, $tokensCredited, $availTokBal) {
                // Deduct USD from TRADING_WALLET
                $user->ChartAccount()->where('acc_type', 'TRADING_WALLET')->update(['amount' => $tradingBal - $amount]);

                // Credit AVAILABLE_TOKEN
                ChartAccount::updateOrCreate(
                    ['user_id' => $user->id, 'acc_type' => 'AVAILABLE_TOKEN'],
                    ['amount'  => $availTokBal + $tokensCredited]
                );

                $trxNo = Transaction::generateTransactionNo();
                Transaction::create([
                    'user_id'             => $user->id,
                    'transaction_no'      => $trxNo,
                    'transaction_type'    => 'TOKEN_PURCHASE',
                    'receiver_id'         => 0,
                    'transaction_details' => json_encode([
                        'usd_amount'   => $amount,
                        'tokens'       => $tokensCredited,
                        'rate'         => $tradingPrice,
                        'date'         => now()->toDateTimeString(),
                    ]),
                ]);
            });

            return back()->with('success', "Purchased " . number_format($tokensCredited, 2) . " Available Tokens using $" . number_format($amount, 2) . " USD from Trading Wallet at $" . number_format($tradingPrice, 4) . "/token!");
        }

        if ($actionType === 'transfer_available_token') {
            if ($availTokBal < $amount) {
                return back()->with('error', 'Insufficient Available Tokens. You have ' . number_format($availTokBal, 2) . ' Available Tokens.');
            }

            $trxNo = Transaction::generateTransactionNo();
            Transaction::create([
                'user_id'             => $user->id,
                'transaction_no'      => $trxNo,
                'transaction_type'    => 'TOKEN_TRANSFER',
                'receiver_id'         => 0,
                'transaction_details' => json_encode([
                    'tokens' => $amount,
                    'date'   => now()->toDateTimeString(),
                ]),
            ]);

            return back()->with('success', "Transferred " . number_format($amount, 2) . " Available Tokens successfully!");
        }

        return back()->with('error', 'Invalid trading action.');
    }

}
