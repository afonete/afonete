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

    $myCodes = Activations::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('email', $user->email);
        })
        ->whereNotIn('package', ['TEAM_LEADER', 'SUPER_LEADER', 'TM'])
        ->orderBy('id', 'desc')
        ->paginate(10);

    // Check if the user exists in the TeamLeader table
    $teamLeader = TeamLeader::where('User_name', $user->user)
        ->orWhere('Email', $user->email)
        ->orWhere('Phone', $user->phone)
        ->first();

    if ($teamLeader) {
        if ($teamLeader->status === 'pending') {
            return redirect()->to(url('/team-leader/pending-approval?username='.$teamLeader->User_name));
        }
        if ($teamLeader->status === 'rejected') {
            return redirect()->to(url('/team-leader/rejected?username='.$teamLeader->User_name));
        }
    }

    return view('user.activation-controller',['user'=>$user,"packages"=>$package, 'myCodes'=>$myCodes]);
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

            // Exclusivity check: Application-generated leader codes are restricted exclusively to that specific team leader
            $isApplicationLeaderCode = in_array($results->package, ['TEAM_LEADER', 'SUPER_LEADER']) && !$results->is_auto_code;

            if ($isApplicationLeaderCode) {
                $assignedEmail = strtolower(trim((string)$results->email));
                $userEmail     = strtolower(trim((string)$user->email));

                $matchingLeader = TeamLeader::where('User_name', $user->user)
                    ->orWhere('Email', $user->email)
                    ->first();

                $isEmailMatch = ($assignedEmail !== '' && $assignedEmail === $userEmail);
                $isLeaderMatch = $matchingLeader && (
                    strtolower(trim((string)$matchingLeader->Email)) === $assignedEmail ||
                    strtolower(trim((string)$matchingLeader->Email)) === $userEmail
                );

                if (!$isEmailMatch && !$isLeaderMatch) {
                    return redirect()->route('user.dashboard.activate')
                        ->with('message', "This activation code is reserved exclusively for the designated team leader account ('{$results->email}'). You cannot use it on this account.");
                }
            }

            // Active Team Leader double-activation check:
            // Currently active Team Leaders CANNOT use a Team Leader activation code again
            // UNLESS their Team Leader account has been deactivated or their leader package period has expired.
            $isLeaderCode = in_array(strtoupper((string) $results->package), ['TEAM_LEADER', 'SUPER_LEADER']);

            if ($isLeaderCode) {
                $matchingLeader = TeamLeader::where('User_name', $user->user)
                    ->orWhere('Email', $user->email)
                    ->first();

                $hasActiveLeaderPackage = in_array(strtoupper((string) $user->has_paid_package), ['TEAM_LEADER', 'SUPER_LEADER']);

                $leaderCredit = $matchingLeader ? $matchingLeader->superLeaderCredit : null;
                $isCreditDisabled = $leaderCredit ? in_array(strtolower($leaderCredit->status), ['disabled', 'deactivated', 'rejected']) : false;

                $latestLeaderPayment = Paymodel::where('user', $user->id)
                    ->whereIn('package', ['TEAM_LEADER', 'SUPER_LEADER'])
                    ->orderBy('created_at', 'desc')
                    ->first();

                $isLeaderExpired = false;
                if ($latestLeaderPayment) {
                    if ($latestLeaderPayment->is_expired) {
                        $isLeaderExpired = true;
                    } elseif ($latestLeaderPayment->expiration_date && \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($latestLeaderPayment->expiration_date))) {
                        $isLeaderExpired = true;
                    }
                }

                $isCurrentlyActiveLeader = $hasActiveLeaderPackage
                    && $matchingLeader
                    && $matchingLeader->status === 'confirmed'
                    && !$isCreditDisabled
                    && !$isLeaderExpired;

                if ($isCurrentlyActiveLeader) {
                    return redirect()->route('user.dashboard.activate')
                        ->with('message', "Team Leader Activation Error: Your {$user->has_paid_package} account is currently active. You cannot redeem another Team Leader activation code while your current leader status is active unless it has expired or been deactivated.");
                }
            }

            // UVP Package Tier progression check: Cannot activate a UVP package below highest previously purchased UVP package amount
            $isUvpPackage = in_array(strtoupper((string)$results->package), ['VENTURE', 'UVP'])
                || \App\Models\Adventures::where('name', $results->package)->exists();

            if ($isUvpPackage) {
                $highestUvp = $user->highestUvpPackageAmount();
                $codePrice  = (float) ($results->price ?? 0);

                if ($highestUvp > 0 && $codePrice > 0 && $codePrice < $highestUvp) {
                    return redirect()->route('user.dashboard.activate')
                        ->with('message', "UVP Activation Error: This UVP activation code ($" . number_format($codePrice, 2) . ") is below your highest previously purchased UVP package amount ($" . number_format($highestUvp, 2) . "). You can only activate UVP packages of $" . number_format($highestUvp, 2) . " or higher.");
                }
            }

            // FOM Licence Miner Package Check
            $isFomPackage = \App\Models\FomLicenceMiner::where('name', $results->package)->exists()
                || str_starts_with($results->code, 'FOM-');

            if ($isFomPackage) {
                // Check if user has ALREADY activated an active package with this EXACT package name
                $alreadyActivatedSamePackage = \App\Models\Payment::where('user', $user->id)
                    ->where('package', $results->package)
                    ->where('is_expired', false)
                    ->exists() || (strtoupper((string)$user->has_paid_package) === strtoupper((string)$results->package));

                if ($alreadyActivatedSamePackage) {
                    return redirect()->route('user.dashboard.activate')
                        ->with('message', "You have already activated a {$results->package} package on your account. You cannot activate a second {$results->package} package. You can copy this activation code ({$results->code}) and give it to another user to activate on their account!");
                }

                $totalReturn = (float) $results->token;
                if ($totalReturn <= 0) {
                    $fomPkg = \App\Models\FomLicenceMiner::where('name', $results->package)->first();
                    if ($fomPkg) {
                        $totalReturn = \App\Models\FomLicenceMiner::cleanNum($fomPkg->total_return);
                    }
                }

                // Mark activation code as used
                $results->stutus = "used";
                $results->email = $user->email;
                $results->save();

                // Update user package status
                $user->has_paid_package = $results->package;
                $user->has_free_package = 'no';
                $user->save();

                // Record Payment
                \App\Models\Payment::create([
                    'user'            => $user->id,
                    'package'         => $results->package,
                    'amount'          => $results->price,
                    'amount_paid'     => $results->price,
                    'paid'            => $results->price,
                    'over_paid'       => 0,
                    'status'          => 1,
                    'is_expired'      => false,
                    'duration'        => 600,
                    'category'        => $results->package ?: 'FOM',
                    'category_id'     => 1,
                    'deposit_method'  => 'ACTIVATION_CODE',
                    'payable_type'    => \App\Models\Activations::class,
                    'payable_id'      => $results->id,
                    'expiration_date' => \Carbon\Carbon::now()->addDays(600)->toDateTimeString(),
                ]);

                // Credit Independent Escrow Wallet (ESCROW_TOKEN)
                $escrowBal = (float) $user->ChartAccount()->where('acc_type', 'ESCROW_TOKEN')->sum('amount');
                \App\Models\ChartAccount::updateOrCreate(
                    ['user_id' => $user->id, 'acc_type' => 'ESCROW_TOKEN'],
                    ['amount' => $escrowBal + $totalReturn]
                );

                // Create 12 Monthly Installments Schedule
                \App\Models\FomTokenInstallment::createSchedule($user->id, $results->id, $results->package, $totalReturn);

                // Process any due installments
                \App\Models\FomTokenInstallment::processDueInstallments($user);

                $tokenSetting = \App\Models\TokenSetting::first();
                $tokenSymbol = $tokenSetting->token_symbol ?? 'FOCOIN';

                return redirect()->route('user.dashboard.activate')
                    ->with('message', "Package '{$results->package}' activated successfully! " . number_format($totalReturn) . " {$tokenSymbol} credited to your Escrow Wallet (Locked Tokens). Released in 12 monthly installments into your Available Token balance.");
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
                        "category_id" => 1,
                        "payable_id" => $results->id,
                        "payable_type" => get_class($results)
                    ]);
                    $create_payable->save();
                    $pay = $create_payable;

                    // Credit referral bonus to upline
                    if ($pay && $pay instanceof \App\Models\Payment) {
                        \App\Services\ReferralService::creditForPayment($pay);
                    }

                    // Ensure Team Leader record & Super Leader credits if leader code
                    $this->ensureTeamLeaderRecord($user, $results);

                    $user->refresh();
                    $target = $this->dashboardRouteForUser($user);
                    $message = $target === 'user.dashboard'
                        ? 'You have been activated Fonepo account. Enjoy unlimited earning on Fonepo!'
                        : 'Your account has been activated. Please sign the contract before accessing your dashboard.';

                    return redirect()->route($target)->with('message', $message);

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
        $userId = $userA->id;
        $email = $userA->email;
        $name = $userA->name;//for now
        $user=User::find($userId);
        // $results = DB::select('SELECT * FROM activations where code= :code',['code'=>$code]);
        $activation = Activations::where("code",$code)->where("stutus","not")->first();


    // Check if the user exists in the TeamLeader table
    $teamLeader = TeamLeader::where('User_name', $userA->user)
        ->orWhere('Email', $userA->email)
        ->orWhere('Phone', $userA->phone)
        ->first();
    \Log::info($teamLeader);

    if ($teamLeader) {
        $alreadyActivated = in_array($user->has_paid_package, ['TEAM_LEADER', 'SUPER_LEADER']);

        $leaderCredit = $teamLeader->superLeaderCredit;
        $isCreditDisabled = $leaderCredit ? in_array(strtolower($leaderCredit->status), ['disabled', 'deactivated', 'rejected']) : false;

        $latestLeaderPayment = Paymodel::where('user', $user->id)
            ->whereIn('package', ['TEAM_LEADER', 'SUPER_LEADER'])
            ->orderBy('created_at', 'desc')
            ->first();

        $isLeaderExpired = false;
        if ($latestLeaderPayment) {
            if ($latestLeaderPayment->is_expired) {
                $isLeaderExpired = true;
            } elseif ($latestLeaderPayment->expiration_date && \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($latestLeaderPayment->expiration_date))) {
                $isLeaderExpired = true;
            }
        }

        $isActiveLeader = $alreadyActivated && $teamLeader->status === 'confirmed' && !$isCreditDisabled && !$isLeaderExpired;

        if ($isActiveLeader) {
            return redirect()->route('team-leader.all')->with('message', 'Your Team Leader account is currently active.');
        }

        if ($teamLeader->status === 'pending') {
            return redirect()->to(url('/team-leader/pending-approval?username='.$teamLeader->User_name));
        }

        if ($teamLeader->status === 'rejected') {
            return redirect()->to(url('/team-leader/rejected?username='.$teamLeader->User_name));
        }

        if ($teamLeader->status === 'suspended') {
            return redirect()->route('team.leader')->with('message', 'Your Team Leader account is suspended.');
        }

        // status === 'confirmed' && (deactivated or expired) → fall through to activation flow below
    }

        // dd($activation);
        if(!$activation){
            return redirect()->back()->with('message', 'Invalid Activation Code. Please try to buy another.');

        }

        // Exclusivity check: Application-generated leader codes are restricted exclusively to that specific team leader
        $isApplicationLeaderCode = in_array($activation->package, ['TEAM_LEADER', 'SUPER_LEADER']) && !$activation->is_auto_code;

        if ($isApplicationLeaderCode) {
            $assignedEmail = strtolower(trim((string)$activation->email));
            $userEmail     = strtolower(trim((string)$userA->email));

            $matchingLeader = TeamLeader::where('User_name', $userA->user)
                ->orWhere('Email', $userA->email)
                ->first();

            $isEmailMatch = ($assignedEmail !== '' && $assignedEmail === $userEmail);
            $isLeaderMatch = $matchingLeader && (
                strtolower(trim((string)$matchingLeader->Email)) === $assignedEmail ||
                strtolower(trim((string)$matchingLeader->Email)) === $userEmail
            );

            if (!$isEmailMatch && !$isLeaderMatch) {
                return redirect()->back()
                    ->with('message', "This activation code is reserved exclusively for the designated team leader account ('{$activation->email}'). You cannot use it on this account.");
            }
        }

        // Active Team Leader double-activation check:
        // Currently active Team Leaders CANNOT use a Team Leader activation code again
        // UNLESS their Team Leader account has been deactivated or their leader package period has expired.
        $isLeaderCode = in_array(strtoupper((string) $activation->package), ['TEAM_LEADER', 'SUPER_LEADER']);

        if ($isLeaderCode) {
            $matchingLeader = TeamLeader::where('User_name', $userA->user)
                ->orWhere('Email', $userA->email)
                ->first();

            $hasActiveLeaderPackage = in_array(strtoupper((string) $userA->has_paid_package), ['TEAM_LEADER', 'SUPER_LEADER']);

            $leaderCredit = $matchingLeader ? $matchingLeader->superLeaderCredit : null;
            $isCreditDisabled = $leaderCredit ? in_array(strtolower($leaderCredit->status), ['disabled', 'deactivated', 'rejected']) : false;

            $latestLeaderPayment = Paymodel::where('user', $userA->id)
                ->whereIn('package', ['TEAM_LEADER', 'SUPER_LEADER'])
                ->orderBy('created_at', 'desc')
                ->first();

            $isLeaderExpired = false;
            if ($latestLeaderPayment) {
                if ($latestLeaderPayment->is_expired) {
                    $isLeaderExpired = true;
                } elseif ($latestLeaderPayment->expiration_date && \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($latestLeaderPayment->expiration_date))) {
                    $isLeaderExpired = true;
                }
            }

            $isCurrentlyActiveLeader = $hasActiveLeaderPackage
                && $matchingLeader
                && $matchingLeader->status === 'confirmed'
                && !$isCreditDisabled
                && !$isLeaderExpired;

            if ($isCurrentlyActiveLeader) {
                return redirect()->back()
                    ->with('message', "Team Leader Activation Error: Your {$userA->has_paid_package} account is currently active. You cannot redeem another Team Leader activation code while your current leader status is active unless it has expired or been deactivated.");
            }
        }

        // UVP Package Tier progression check: Cannot activate a UVP package below highest previously purchased UVP package amount
        $isUvpPackage = in_array(strtoupper((string)$activation->package), ['VENTURE', 'UVP'])
            || \App\Models\Adventures::where('name', $activation->package)->exists();

        if ($isUvpPackage) {
            $highestUvp = $userA->highestUvpPackageAmount();
            $codePrice  = (float) ($activation->price ?? 0);

            if ($highestUvp > 0 && $codePrice > 0 && $codePrice < $highestUvp) {
                return redirect()->back()
                    ->with('message', "UVP Activation Error: This UVP activation code ($" . number_format($codePrice, 2) . ") is below your highest previously purchased UVP package amount ($" . number_format($highestUvp, 2) . "). You can only activate UVP packages of $" . number_format($highestUvp, 2) . " or higher.");
            }
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
                        "category_id" => 1,
                        "payable_id" => $activation->id,
                        "payable_type" => get_class($activation)
                    ]);
                    $create_payable->save();
                    $pay = $create_payable;

                    // Credit referral bonus to upline
                    if ($pay && $pay instanceof \App\Models\Payment) {
                        \App\Services\ReferralService::creditForPayment($pay);
                    }

                    // Ensure Team Leader record & Super Leader credits if leader code
                    $this->ensureTeamLeaderRecord($userA, $activation);

                    if($user_update && $update){
                            $user = User::find($userA->id);
                            $target = $this->dashboardRouteForUser($user);
                            $message = $target === 'user.dashboard'
                                ? 'You have been activated Fonepo account, Enjoy unlimited earning on Fonepo'
                                : 'Your account has been activated. Please sign the contract before accessing your dashboard.';

                            return redirect()->route($target)->with('message', $message);
                    }



            }
        }
private function dashboardRouteForUser($user): string
{
    if (!$user) {
        return 'user.contract';
    }

    if ($user->utype === 'ADM' || $user->contract === 'Signed') {
        return 'user.dashboard';
    }

    $paidPackage = strtolower(trim((string) $user->has_paid_package));
    $isFreeStandard = $user->has_free_package === 'yes'
        && $paidPackage === 'standard';

    return $isFreeStandard ? 'user.dashboard' : 'user.contract';
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

    $myCodes = Activations::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('email', $user->email);
        })
        ->whereNotIn('package', ['TEAM_LEADER', 'SUPER_LEADER', 'TM'])
        ->orderBy('id', 'desc')
        ->paginate(10);

    return view('user.package-history',['user'=>$user,"packages"=>$package, 'myCodes'=>$myCodes]);
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

    private function ensureTeamLeaderRecord($user, $activation)
    {
        if (in_array($activation->package, ['TEAM_LEADER', 'SUPER_LEADER'])) {
            $teamLeader = TeamLeader::where('User_name', $user->user)
                ->orWhere('Email', $user->email)
                ->first();

            $leaderData = [
                'Names'            => $user->name ?: $user->user,
                'User_name'        => $user->user,
                'Email'            => $user->email,
                'Phone'            => $user->phone ?: '',
                'Country'          => $user->country ?: '',
                'status'           => 'confirmed',
                'leadership_level' => $activation->package,
            ];

            if ($teamLeader) {
                $teamLeader->update($leaderData);
            } else {
                $teamLeader = TeamLeader::create($leaderData);
            }

            if ($activation->package === 'SUPER_LEADER') {
                $conditions = [];
                if (!empty($activation->credit_conditions)) {
                    $conditions = json_decode($activation->credit_conditions, true) ?: [];
                }

                $creditAmt             = isset($conditions['credit_amount']) ? (float)$conditions['credit_amount'] : (float)($activation->price ?: 1000);
                $salesTurnoverTarget   = isset($conditions['sales_turnover_target']) ? (float)$conditions['sales_turnover_target'] : 10000;
                $turnoverTargetPercent = isset($conditions['turnover_target_percent']) ? (float)$conditions['turnover_target_percent'] : 0;
                $turnoverRewardPercent = isset($conditions['turnover_reward_percent']) ? (float)$conditions['turnover_reward_percent'] : 0;
                $autoWithdrawalPercent = isset($conditions['auto_withdrawal_percent']) ? (float)$conditions['auto_withdrawal_percent'] : 0;

                $creditStatusSetting   = isset($conditions['credit_status']) ? $conditions['credit_status'] : 'active';
                $slStatus              = ($creditStatusSetting === 'pending') ? 'pending' : 'active';

                $slCredit = \App\Models\SuperLeaderCredit::where('team_leader_id', $teamLeader->id)->first();
                if (!$slCredit) {
                    $slCredit = \App\Models\SuperLeaderCredit::where('user_id', $user->id)->first();
                }
                if (!$slCredit) {
                    $slCredit = \App\Models\SuperLeaderCredit::where('activation_id', $activation->id)->first();
                }

                if (!$slCredit) {
                    \App\Models\SuperLeaderCredit::create([
                        'team_leader_id'          => $teamLeader->id,
                        'user_id'                 => $user->id,
                        'activation_id'           => $activation->id,
                        'credit_amount'           => $creditAmt,
                        'remaining_credit'        => $creditAmt,
                        'cashout_amount'          => 0,
                        'sales_turnover_target'   => $salesTurnoverTarget,
                        'turnover_target_percent' => $turnoverTargetPercent,
                        'turnover_reward_percent' => $turnoverRewardPercent,
                        'auto_withdrawal_percent' => $autoWithdrawalPercent,
                        'status'                  => $slStatus,
                        'activated_at'            => $slStatus === 'active' ? now() : null,
                    ]);
                } else {
                    $slCredit->update([
                        'user_id'                 => $user->id,
                        'activation_id'           => $activation->id,
                        'credit_amount'           => $creditAmt,
                        'remaining_credit'        => $creditAmt,
                        'sales_turnover_target'   => $salesTurnoverTarget,
                        'turnover_target_percent' => $turnoverTargetPercent,
                        'turnover_reward_percent' => $turnoverRewardPercent,
                        'auto_withdrawal_percent' => $autoWithdrawalPercent,
                        'status'                  => $slStatus,
                        'activated_at'            => $slStatus === 'active' ? ($slCredit->activated_at ?: now()) : $slCredit->activated_at,
                    ]);
                }

                // Sync to legacy credits table for dashboard compatibility
                \App\Models\Credit::updateOrCreate(
                    ['activation_id' => $activation->id],
                    ['amount' => $creditAmt, 'status' => ($slStatus === 'active' ? 'approved' : 'pending')]
                );
            }
        }
    }
}
