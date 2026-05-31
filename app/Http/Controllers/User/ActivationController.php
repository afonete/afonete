<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\TeamLeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment as Paymodel;
use App\Models\User;
use App\Models\Activations;
use App\Models\balance;
use App\Models\Credit;
use Carbon\Carbon;
use App\Models\Transaction;


use Illuminate\Support\Facades\DB;

class ActivationController extends Controller
{
public function index(){
    $user=Auth::user();
    $userId = $user->id;
    $user = User::find($userId);
    $package = Paymodel::where("user",$userId)->get();

          return view('user.activation-controller',['user'=>$user,"packages"=>$package]);
}

public function upgrade(Request $request){
        $code=$request->code;
        $user = Auth::user();
        // dd($user);
        $userId = $user->id;
        $userId = $user->id;
        $email = $user->email;
        $name=$user->user;

        $results = Activations::where("code",$code)->first();

        // $balance = new balance();
     if ($results) {

            if ($results->stutus=="used") {
                return redirect()->route('user.dashboard.activate')->with('message',' the activation code  has been expired, please try to buy an other');
             }
           else{
            try {

                $balance = new Balance();
                $balance->user = $userId;
                $balance->reserved_token = $results->token;
                $results->status = "used";
                $results->email = $user->email;
                $update_user=DB::update('UPDATE users set has_paid_package=:paid,has_free_package=:free where id=:uuid',
                ['paid'=>$results->package,'free'=>'no','uuid'=>$userId]);


                if ($update_user) {
                    $balance->save();
                    $update_activation = DB::update('UPDATE activations SET stutus = :st, email = :em, updated_at = now() WHERE code = :cd', [
                        'st' => 'used',
                        'em' => $user->email,
                        'cd' => $results->code
                    ]);

                    $transactionNo= Transaction::generateTransactionNo();
                    $start_date = Carbon::now()->addHours(24);
                    $transaction =  Transaction::create([
                        'user_id'=>$user->id,
                        'transaction_no' => $transactionNo,
                        'transaction_type' => 'SUBSCRIPTION', // or any other type you define
                        'transaction_details' => json_encode([
                            'product' => $results->package,
                            'user' => $user->email,
                            'plan' => null,
                            'start_date' =>null,
                            'end_date'=>null,
                            'package'=>$results->package,
                            'price'=>$results->price,
                            'period'=>'unknown',
                            'revenue_earned'=>0,
                            'revenue_type'=>'soon',
                            'status'=>'success',
                            'purchase_date'=>$start_date ,
                            'username'=>$user->name,
                            'code'=>$results->code

                            // 'leadership_bonus' => 0,
                            // 'debit' => 0.00,
                            // 'cash' => 0.00, // 20% cash
                            // 'trading_voucher' => 0.00, // 80% trading voucher
                            // 'sender' => 'Sender Name',
                            // 'username' => $user->username,
                            // 'sender_id' => 12345,
                            // 'transaction_type' => 'Deposit',
                            // 'description' => 'Initial deposit for the package',
                            // 'details' => 'Transaction details here'
                        ])
                    ]);

                    $currentDate = Carbon::now();
                    $newDate = $currentDate->addDays(200);
                    $formattedDate = $newDate->toDateString();
                    $expDate = $formattedDate;



                    $create_payable = new Paymodel([
                        'user' => $user->id,
                        'package' => $results->package,
                        'amount' => $results->price,
                        'paid' => $results->price,
                        'over_paid' => 0,
                        'status' => 1,
                        'expiration_date' => $expDate,
                        "duration" => 200,
                        "category" => $results->package,
                        "category_id" => 1
                    ]);
                    $pay =  $results->payments()->save($create_payable);

                    return redirect()->route('user.dashboard')->with('message', 'You have been activated Fonepo account. Enjoy unlimited earning on Fonepo!');

                } else {


                    return back()->with('message',' Invalid activation code, re-enter code again');

                }
            } catch (\Exception $e) {

                return redirect()->back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
            }



             }

       }
        else{
          return redirect()->route('user.dashboard.activate')->with('message',' Invalid activation code, re-enter code again');

         }
    }



public function g_upgrade(Request $request){
    // dd($request);
        $code=$request->code;
        $userA = Auth::user();
        $userId = $user->id;
        $email = $user->email;
        $name = $user->name;//for now
        $user=User::where("id",$userId);
        // $results = DB::select('SELECT * FROM activations where code= :code',['code'=>$code]);
        $activation = Activations::where("code",$code)->where("stutus","not")->first();


    // Check if the user exists in the TeamLeader table
    $teamLeader = TeamLeader::where('User_name', $userA->user)
        ->orWhere('Email', $userA->email)
        ->orWhere('Phone', $userA->phone)
        ->first();
    \Log::info($teamLeader);

    if ($teamLeader) {
        if ($teamLeader->status === 'pending') {
            // Logout the user and redirect to the "leader pending" page
//                Auth::logout();
            return redirect()->to(url('/team-leader/pending-approval?username='.$teamLeader->User_name));
        }

        if ($teamLeader->status === 'rejected') {
            // Logout the user and redirect to the "leader rejected" page
//                Auth::logout();
            return redirect()->to(url('/team-leader/rejected?username='.$teamLeader->User_name));

        }
    }

        // dd($activation);
        if(!$activation){
            return redirect()->route('user.package')->with('message', 'Invalid Activation Code. Please try to buy another.');

        }
        else{

                    $user->has_paid_package=$activation->package;
                    $user->has_free_package='no';
                    $balance=new balance();
                    $balance->user=$userA->id;
                    $balance->reserved_token=$activation->token;
                    $balance->save();
                    $update=DB::update('UPDATE activations set user_id=:uuid,stutus= :st,email=:em, updated_at=now() where code=:cd',['uuid'=>$userA->id,
                    'st'=>'used','cd'=>$code,'em'=>$email]);
                    $user_update=DB::update('UPDATE users set has_paid_package=:paid,has_free_package=:free where id=:uuid',['paid'=>$activation->package,
                    'free'=>'no','uuid'=>$userA->id]);
                    $transactionNo= Transaction::generateTransactionNo();
                    $start_date = Carbon::now()->addHours(24);

                    $transaction =  Transaction::create([
                        'user_id'=>$userA->id,
                        'transaction_no' => $transactionNo,
                        'transaction_type' => 'SUBSCRIPTION', // or any other type you define
                        'transaction_details' => json_encode([
                            'product' => $activation->package,
                            'user' => $userA->email,
                            'plan' => null,
                            'start_date' =>null,
                            'end_date'=>null,
                            'package'=>$activation->package,
                            'price'=>$activation->price,
                            'period'=>'unknown',
                            'revenue_earned'=>0,
                            'revenue_type'=>'soon',
                            'status'=>'success',
                            'purchase_date'=>$start_date ,
                            'username'=>$userA->name,
                            'code'=>$activation->code

                            // 'leadership_bonus' => 0,
                            // 'debit' => 0.00,
                            // 'cash' => 0.00, // 20% cash
                            // 'trading_voucher' => 0.00, // 80% trading voucher
                            // 'sender' => 'Sender Name',
                            // 'username' => $user->username,
                            // 'sender_id' => 12345,
                            // 'transaction_type' => 'Deposit',
                            // 'description' => 'Initial deposit for the package',
                            // 'details' => 'Transaction details here'
                        ])
                    ]);
                    $currentDate = Carbon::now();
                    $newDate = $currentDate->addDays(200);
                    $formattedDate = $newDate->toDateString();
                    $expDate = $formattedDate;

                    $create_payable = new Paymodel([
                        'user' => $userA->id,
                        'package' => $activation->package,
                        'amount' => $activation->price,
                        'paid' => $activation->price,
                        'over_paid' => 0,
                        'status' => 1,
                        'expiration_date' => $expDate,
                        "duration" => 200,
                        "category" => $activation->package,
                        "category_id" => 1
                    ]);
                    $pay =  $activation->payments()->save($create_payable);

                    if($user_update && $update){
                            return redirect()->route('user.dashboard')->with('message', 'You have been  activated Fonepo account , Enjoy unlimited earning on Fonepo');
                    }



            }
        }
public function success()
{
    return ('success');
}

public function error()
{
    return ('error');
}
public function package()
{
    $user=Auth::user();
    $userId = $user->id;
    $user = User::find($userId);
    $package = Paymodel::where("user",$userId)->get();

    return view('user.package-history',['user'=>$user,"packages"=>$package]);
    // return view('user.package-history');
}
public function saveCode(Request $request)
{
    $activations= new Activations();




            $code=$request->code;
            $activations->code=$code;
            $activations->package=$request->act_type;
            $activations->stutus='not';
            $activations->token = $request->locked_token;
            $activations->price = $request->price;
            $activations->task = $request->task;
            $activations->percentage = $request->percentage;
            $activations->period = $request->period;
            $activations->withdrawmax = $request->withdrawmax;
            // dd($activations)

             // $activations->email='';



             if ($activations->save()) {
                if($request->act_type == 'TM'){
                    // dd($activations);
                    Credit::create([
                        "activation_id"=>$activations->id,
                        "amount"=>$request->credit
                    ]);
                }
                 return redirect()->route("admin.ft")->with('success','FT Activation code saved well');
             }
             else{
             return view('admin.ft-manage')->with('message','FT Activation code not saved well');
}
}

public function updateCode(Request $request,$id)
{

    // Find the activation by ID
    $activation = Activations::findOrFail($id);
    // Update the activation fields

    // dd($request);
    $activation->price = $request->input('price');
    $activation->token = $request->input('locked_token');
    // $activation->task = $request->task;
    $activation->percentage = $request->percentage;
    $activation->period = $request->period;
    $activation->countdown = $request->countdown;
    // $activation->withdrawmax = $request->withdrawmax;
    if($request->act_type == 'TM'){
        $credit = Credit::where("activation_id",$id)->first();
        if(!$credit){
            // dd($activation);
           $credit =  Credit::create([
                "activation_id"=>$activation->id,
                "amount"=>$request->price
            ]);

        }else{
            $credit ->amount = $request->credit;
             $credit->save();
        }

    }
    $activation->save();

    // dd($activation);

    // Redirect back with a success message
    return back()->with('success', $request->act_type.' Activation details updated successfully.');
}



public function updateCodeTask(Request $request,$id)
{

    // Find the activation by ID
    $activation = Activations::findOrFail($id);
    // Update the activation fields

    $activation->task = $request->task;
    $activation->turnover = $request->sales;
    $activation->exceptionalTask = $request->task;
    $activation->view = $request->view;
    $activation->withdrawmax = $request->withdrawmax;
    $activation->task_period = $request->period;

    // if($request->act_type == 'TM'){
    //     $credit = Credit::where("activation_id",$id)->first();
    //     if(!$credit){
    //         // dd($activation);
    //        $credit =  Credit::create([
    //             "activation_id"=>$activation->id,
    //             "amount"=>$request->price
    //         ]);

    //     }else{
    //         $credit ->amount = $request->credit;
    //          $credit->save();
    //     }

    // }
    $activation->save();
    // Redirect back with a success message
    return back()->with('success', $request->act_type.' Task details updated successfully.');
}


public function deleteCode($id)
{

    $activation = Activations::findOrFail($id);

    if($activation->myCredit()){
        $activation->myCredit()->delete();
    }

    $activation->delete();

    return redirect()->route('admin.ft')->with('success', 'Activation record deleted successfully.');
}


public function approveCredit(Request $request){
    $credit = Credit::where("id",$request->act_id)->first();

    if($credit){
        $credit->status = 'approved';
        $credit->save();
        return back()->with("success","Credit Have Been Approved Successfully");

    }
    else{
        return back()->with("success","Credit Was Not Found");

    }

}



public function rejectCredit(Request $request){
    $credit = Credit::where("id",$request->act_id)->first();

    if($credit){
        $credit->status = 'rejected';
        $credit->save();
        return back()->with("success","Credit Have Been Rejected Successfully");

    }
    else{
        return back()->with("success","Credit Was Not Found");

    }

}

public function reactivateCredit(Request $request){
    $credit = Credit::where("id",$request->act_id)->first();

    if($credit){
        $credit->status = 'approved';
        $credit->save();
        return back()->with("success","Credit Have Been Reactivated Successfully");

    }
    else{
        return back()->with("success","Credit Was Not Found");

    }

}
}
