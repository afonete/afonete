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
use Carbon\Carbon;

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

    public function transferToUser(Request $request){
        $user = Auth::User();
        $validatedData = $request->validate([
            'amount' => 'required|max:25',
            'name' => 'required|exists:users',
        ],[
            'name.exists'=>"Selected User Does not exist in our records"
        ]);
        $amount = $request->amount;
        $receiver = User::where("name",$request->name)->first();
        $remaining = $user->ChartAccount()->where("acc_type","CASHOUT")->sum("amount") - $amount;
        if($remaining < 0){
            return back()->with('error' , "Insufficient Funds.");
        }

        if($this->isUserInSameChain($user, $receiver)){
        $trx = UserTransactions::create([
            "sender_id"=>$user->id,
            "receiver_id"=>$receiver->id,
            'amount'=>$request->amount,
            "transaction_type"=>"TRANSFER"
        ]);
        ChartAccount::updateOrCreate(
            [
                'user_id' => $user->id,
                'acc_type' => 'CASHOUT',
            ],
            [
                'amount' => $remaining
            ]
            );

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


        return back()->with("success","You Have Successfull Transferred ".$request->amount."$ To ".$request->name);
    }
        return back()->with('error' , "You cannot transfer funds to a user who is not on the same team as yours.");
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
            'account'=>'required'
        ]);
        dd($request);
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
        $chartAc = $user->ChartAccount()->where("acc_type","COMMISSION")->get();
        $trx = $user->ChartAccount()
                    ->where("acc_type","COMMISSION")
                    ->get()
                    ->groupBy(function($date) {
                        return Carbon::parse($date->created_at)->startOfWeek()->format('Y-m-d');
                    });

        $previousWeekStart = Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d');
        $previousWeekEnd = Carbon::now()->subWeek()->endOfWeek()->format('Y-m-d');

        $previousWeekTransactions = $user->ChartAccount()
            ->where("acc_type","COMMISSION")
            ->whereBetween('created_at', [$previousWeekStart, $previousWeekEnd])
            ->get();

        $previousWeekTotal = $previousWeekTransactions->sum('amount');

        // $commissions =
        $balance = $chartAc->sum("amount");
        // dd($balance);
        return view('user.commission',["balance"=>$balance,"trx"=>$trx,"chartAc"=>$chartAc,"previousWeekTotal"=> $previousWeekTotal]); // Adjust the view path as needed
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


    // GET PAGE TO PAY/RENEW THE PACKAGE



    //  PAY PACKAGE RENEW AFTER 30 DAYS
    public function packageRenewPay (Request $request){
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
}
