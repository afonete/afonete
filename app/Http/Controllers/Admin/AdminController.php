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

use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{


    public function Withdrawal(){

        return view("admin.Withdrawal");
    }
    public function users(){
        $users = User::where('utype', '!=', 'ADM')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.users-list',["users"=>$users]);
    }

    public function depositedPayment(){

        return view('admin.payments',["deposits"=>Deposits::where('status', '!=', 'used')->get()]);
    }
    public function ApproveDeposit(Request $request){
        $deposit = Deposits::where('id',$request->deposit)->first();
        $user =User::where('id',$deposit->user_id)->first();
        $deposit->update(['status' => 'approved']);
        // Create a corresponding payment record
        // $trxno = Transaction::generateTransactionNo();

        // $transaction =  Transaction::create([
        //     'user_id'=>$user->id,
        //     'transaction_no' => $transactionNo,
        //     'transaction_type' => 'SUBSCRIPTION', // or any other type you define
        //     'transaction_details' => json_encode([
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


    }
