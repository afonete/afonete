<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Position;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Models\Deposits;

use App\Mail\reminder;
use App\Mail\SendDepositApproval;
use App\Mail\CancelDeposit;
use App\Mail\RejectDeposit;
use App\Models\Activations;
use App\Models\ChartAccount;
use App\Models\Transaction;
use App\Models\withdrawals as WithdrawalModel;

use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{


    public function Withdrawal()
    {
        $pending    = WithdrawalModel::where('status', 'pending')->with('user')->latest()->get();
        $processing = WithdrawalModel::where('status', 'processing')->with('user')->latest()->get();
        $completed  = WithdrawalModel::where('status', 'completed')->with('user')->latest()->paginate(20);

        return view('admin.Withdrawal', compact('pending', 'processing', 'completed'));
    }

    public function approveWithdrawal(Request $request)
    {
        $withdrawal = WithdrawalModel::findOrFail($request->withdrawal_id);

        if ($withdrawal->status !== 'pending') {
            return redirect()->route('admin.withdrawal')->with('error', 'This withdrawal has already been processed.');
        }

        $withdrawal->update([
            'status'     => 'completed',
            'admin_note' => $request->admin_note,
        ]);

        return redirect()->route('admin.withdrawal')->with('message', 'Withdrawal approved and marked completed.');
    }

    public function rejectWithdrawal(Request $request)
    {
        $withdrawal = WithdrawalModel::findOrFail($request->withdrawal_id);

        if ($withdrawal->status !== 'pending') {
            return redirect()->route('admin.withdrawal')->with('error', 'This withdrawal has already been processed.');
        }

        $user = User::find($withdrawal->user_id);

        // ── Refund the amount back to CASHOUT ──
        if ($user) {
            $existing = $user->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount');
            ChartAccount::updateOrCreate(
                ['user_id' => $user->id, 'acc_type' => 'CASHOUT'],
                ['amount'  => $existing + $withdrawal->amount]
            );

            // Log refund transaction
            Transaction::create([
                'user_id'             => $user->id,
                'transaction_no'      => Transaction::generateTransactionNo(),
                'transaction_type'    => 'WITHDRAWAL_REFUND',
                'receiver_id'         => 0,
                'transaction_details' => json_encode([
                    'amount'     => $withdrawal->amount,
                    'reason'     => $request->admin_note ?? 'Rejected by admin',
                    'date'       => now()->toDateTimeString(),
                    'status'     => 'refunded',
                    'username'   => $user->name,
                ]),
            ]);
        }

        $withdrawal->update([
            'status'     => 'failed',
            'admin_note' => $request->admin_note ?? 'Rejected by admin',
        ]);

        return redirect()->route('admin.withdrawal')->with('message', 'Withdrawal rejected and balance refunded to user.');
    }
    public function users(){
        $users = User::where('utype', '!=', 'ADM')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.users-list',["users"=>$users]);
    }

    public function depositedPayment(){

        return view('admin.payments',["deposits"=>Deposits::where('status', '!=', 'used')->get()]);
    }
    public function ApproveDeposit(Request $request)
    {
        $deposit = Deposits::where('id', $request->deposit)->first();
        if (!$deposit) {
            return redirect()->route('admin.payments')->with('error', 'Deposit not found.');
        }

        $user = User::where('id', $deposit->user_id)->first();
        if (!$user) {
            return redirect()->route('admin.payments')->with('error', 'User not found.');
        }

        $deposit->update(['status' => 'approved']);

        // ── Credit CASHOUT account with the deposited amount ──
        $existing = $user->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount');
        ChartAccount::updateOrCreate(
            ['user_id' => $user->id, 'acc_type' => 'CASHOUT'],
            ['amount'  => $existing + $deposit->amount_deposited]
        );

        // ── Log transaction ──
        $trxNo = Transaction::generateTransactionNo();
        Transaction::create([
            'user_id'             => $user->id,
            'transaction_no'      => $trxNo,
            'transaction_type'    => 'DEPOSIT',
            'receiver_id'         => 0,
            'transaction_details' => json_encode([
                'amount'         => $deposit->amount_deposited,
                'method'         => $deposit->deposit_method,
                'currency'       => $deposit->currency_type,
                'approved_by'    => 'admin',
                'date'           => now()->toDateTimeString(),
                'status'         => 'approved',
                'username'       => $user->name,
            ]),
        ]);
        //         'amount' => $deposit->amount,
        //         'type'=>"DEPOSIT",
        //         'vup_income'=>"0.00",
        //         'date'=>Carbon::now(),
        //         'cash_25'=>
        //          'status'=>'success',



        //     ])
        // ]);


        // Send email notification
        Mail::to($user->email)->send(new SendDepositApproval($deposit));

    //     return redirect()->route('admin.deposits')->with('status', 'Deposit approved and payment recorded!');
    // }
        return redirect()->route("admin.payments")->with("message","Successfully Approved a Deposit!!");
    }

    public function OtherwiseDepositDecisions(Request $request){
        $deposit = Deposits::where('id',$request->deposit)->first();
        $user =User::where('id',$deposit->user_id)->first();
        $comment = $request->comment;
        $action = $request->action;
        // dd($user);


        $deposit->update(['status' => $action,'comment'=>$comment]);
        // Create a corresponding payment record
        // Payment::create([
        //     'user_id' => $deposit->user_id,
        //     'amount' => $deposit->amount,
        //     'status' => 'approved',
        //     'package_details' => [
        //         'package_name' => 'Sample Package',
        //         'validity_days' => 30,
        //     ],
        // ]);
        //  dd()
        if($action == 'rejected'){
            Mail::to($user->email)->send(new RejectDeposit($deposit, $comment));
        // dd("mail sent to $user->email");

            return redirect()->route("admin.payments")->with("message","Successfully Approved a Deposit!!");
        }

        if($action == 'cancelled'){
            Mail::to($user->email)->send(new CancelDeposit($deposit, $comment));
            return redirect()->route("admin.payments")->with("message","Successfully Approved a Deposit!!");
        }

        return redirect()->route("admin.payments")->with("message","Successfully Underreview!!");
    }


    public function index()
    {
        
        return view('admin.admin-dashboard');
        // return view('well');
    }

    public function plans()
    {

        $users = User::where('utype', '!=', 'ADM')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.admin-users-memberships',['users'=>$users]);
        // return view('well');
    }

      public function send(){
        $email='Maniraguhajp5@gmail.com';
        $username='Ubwoba1234';
        $mail=new reminder($email,$username);
        Mail::to($email)->send($mail);
       // return('send');
       return redirect()->back()->with('message','email sent weel to'.$email);
    }
      public function position()
    {
        return view('admin.positions');
        // return view('well');
    }
      public function pos_edit()
    {
        return view('admin.pos_edit');

    }
       public function position_approved()
    {
        $user=request()->query('user');
        try {
        $position=Position::where('user',$user)->first();
        $pos=$position->position;
        $position->update(['status'=>'approved','updated_at'=>now()]);
        return view('admin.positions')->with('message','user '.$user.' approved well on '.$pos.' position');

        } catch (Exception $e) {
        return view('admin.positions')->with('message','failed to approved user '.$user.' on '.$pos.' position');

        }

    }
      public function edit(Request $request)
    {
        $username=$request->user;
        $unique=$request->unique_task;
        $general=$request->general_task;
        $daily=$request->daily_bonus;
        $duration=$request->duration;
        $cashout=$request->cashout;
        $withdraw=$request->withdraw;

try {
    $position=Position::where('user',$username)->first();
    // $status='Unique task has been given';
    // if ($position->unique_task==$unique) {
    //         return view('admin.pos_edit',compact('status','username'));

    // }
$position->update([
     'unique_task'=>$unique,
    'general_task'=>$general,
    'cashout'=>$cashout,
    'unique_task'=>$unique,
    'withdraw'=>$withdraw,
    'daily_bonus'=>$daily,
    'duration'=>$duration,
]);
$status=$username.'s position edited weel';
            return view('admin.pos_edit',compact('status','username'));

        }
        catch (Exception $e) {
$status='failed to edit user position ';

            return view('admin.pos_edit',compact('status','username'));
        }
    }
        public function login()
    {
        return view('admin.login');
        // return view('well');
    }
         public function contacted()
    {
        return view('admin.contacted');
        // return view('well');
    }
  


public function check(Request $request) {

    $request->validate([
        'email'    => 'required',
        'password' => 'required',
    ]);
    $credentials = $request->only('email', 'password');
      if (Auth::attempt($credentials)) {
        $user = User::where('email', $request->email)->first();
        if ($user->utype == 'ADM') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->back()->with('error', 'Oppes! You have no permission ');
        }
    }
    return redirect()->back()->with('error', 'Oppes! You have entered invalid credentials');
} 


    public function ft(){
        $activations = Activations::all();
        $tms = $activations->filter(function ($tm){
            return $tm->package == 'TM';
        });

        $activation =  $activations->filter(function ($tm){
            return $tm->package == 'FT';
        });


    // dd($activation);
        return view('admin.ft-manage',["tms"=>$tms,"FT"=>$activation]);
    }
        public function verify(){
            return view('admin.verify');
        }
        public function pending(){
          return view('admin.pending_users');
      }
      public function fc1(){
        return view('admin.fc1');
    }
      public function fc2(){
        return view('admin.fc2');
    }
      public function logout(){


        session()->forget('admin');
        return view('admin.login');
    }


    /**
     * Admin: all users and their referral bonus balances.
     */
    public function referralBonuses()
    {
        $users = User::where('utype', '!=', 'ADM')
            ->with(['ChartAccount', 'referrals'])
            ->get()
            ->map(function ($u) {
                $u->commission_balance = $u->ChartAccount->where('acc_type', 'COMMISSION')->sum('amount');
                $u->total_referrals    = $u->referrals->count();
                $u->active_referrals   = $u->referrals->filter(function ($r) {
                    return $r->investments()->where('status', 1)->where('category', 'VENTURE')->exists();
                })->count();
                return $u;
            })
            ->filter(fn($u) => $u->commission_balance > 0 || $u->total_referrals > 0)
            ->sortByDesc('commission_balance')
            ->values();

        $totalCommissionPaid = $users->sum('commission_balance');

        return view('admin.referral-bonuses', compact('users', 'totalCommissionPaid'));
    }

    /**
     * Admin: full referral bonus detail for one user.
     */
    public function referralBonusDetail($userId)
    {
        $user = User::with(['referrals', 'ChartAccount'])->findOrFail($userId);

        $commissionBalance = $user->ChartAccount->where('acc_type', 'COMMISSION')->sum('amount');

        $transactions = \App\Models\Transaction::where('user_id', $userId)
            ->where('transaction_type', 'COMMISSION')
            ->latest()
            ->get()
            ->map(function ($t) {
                $d = json_decode($t->transaction_details, true) ?? [];
                $t->parsed_amount      = $d['amount'] ?? 0;
                $t->parsed_description = $d['description'] ?? 'Referral Bonus';
                $t->from_user          = $d['username'] ?? '—';
                return $t;
            });

        $directReferrals = $user->referrals()
            ->with(['investments' => fn($q) => $q->where('status', 1)->where('category', 'VENTURE')])
            ->get()
            ->map(function ($r) {
                $invested = $r->investments->sum('amount');
                $r->total_invested = $invested;
                $r->bonus_earned   = round($invested * 10 / 100, 2);
                $r->is_active      = $invested > 0;
                return $r;
            });

        $indirectReferrals = collect();
        foreach ($user->referrals as $direct) {
            foreach ($direct->referrals as $indirect) {
                $invested = $indirect->investments()->where('status', 1)->where('category', 'VENTURE')->sum('amount');
                $indirect->total_invested   = $invested;
                $indirect->bonus_earned     = round($invested * 1 / 100, 2);
                $indirect->referred_through = $direct->name;
                $indirect->is_active        = $invested > 0;
                $indirectReferrals->push($indirect);
            }
        }

        $directBonusTotal   = round($directReferrals->sum('bonus_earned'), 2);
        $indirectBonusTotal = round($indirectReferrals->sum('bonus_earned'), 2);

        return view('admin.referral-bonus-detail', compact(
            'user', 'commissionBalance', 'transactions',
            'directReferrals', 'indirectReferrals',
            'directBonusTotal', 'indirectBonusTotal'
        ));
    }

    // ─── Token Withdrawals (admin approve/reject) ────────────────────────
    public function tokenWithdrawals()
    {
        $pending   = \App\Models\TokenWithdrawal::where('status', 'pending')->with('user')->latest()->get();
        $completed = \App\Models\TokenWithdrawal::whereIn('status', ['approved','rejected'])->with('user')->latest()->paginate(30);
        // Gas fee totals per user (admin-only view)
        $gasFees   = \App\Models\ChartAccount::where('acc_type', 'GAS_FEE')
                        ->with('user')
                        ->get()
                        ->groupBy('user_id')
                        ->map(fn($rows) => $rows->sum('amount'));
        return view('admin.token-withdrawals', compact('pending', 'completed', 'gasFees'));
    }

    public function approveTokenWithdrawal(Request $request)
    {
        $tw = \App\Models\TokenWithdrawal::findOrFail($request->token_withdrawal_id);
        if ($tw->status !== 'pending') {
            return redirect()->route('admin.token-withdrawals')->with('error', 'Already processed.');
        }
        $tw->update(['status' => 'approved', 'admin_note' => $request->admin_note, 'approved_by' => Auth::id()]);
        return redirect()->route('admin.token-withdrawals')->with('message', 'Token withdrawal approved.');
    }

    public function rejectTokenWithdrawal(Request $request)
    {
        $tw = \App\Models\TokenWithdrawal::findOrFail($request->token_withdrawal_id);
        if ($tw->status !== 'pending') {
            return redirect()->route('admin.token-withdrawals')->with('error', 'Already processed.');
        }
        // Refund tokens back to user's FREE_TOKEN
        $user = User::find($tw->user_id);
        if ($user) {
            $bal = $user->ChartAccount()->where('acc_type', 'FREE_TOKEN')->sum('amount');
            ChartAccount::updateOrCreate(
                ['user_id' => $user->id, 'acc_type' => 'FREE_TOKEN'],
                ['amount'  => $bal + $tw->token_amount]
            );
        }
        $tw->update(['status' => 'rejected', 'admin_note' => $request->admin_note ?? 'Rejected by admin']);
        return redirect()->route('admin.token-withdrawals')->with('message', 'Token withdrawal rejected and tokens refunded.');
    }

    }
