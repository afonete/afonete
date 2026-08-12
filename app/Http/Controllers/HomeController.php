<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Contacted;
use App\Models\requested;
use App\Models\Balance;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(){
        return view('home.welcome');
    }

    public function about(){
        return view('home.about');
    }

    public function contact(){
        return view('home.contact');
    }

    public function contacted(Request $request){
        $name=$request->name;
        $sur=$request->sur;
        $email=$request->email;
        $phone=$request->phone;
        $message=$request->message;

        $contacted= new Contacted();
        $contacted->name=$name;
        $contacted->sur=$sur;
        $contacted->phone=$phone;
        $contacted->email=$email;
        $contacted->message=$message;

        if ($contacted->save()) {
            return view('home.contact')->with('message','success');
        } else {
            return view('home.contact')->with('message','error');
        }
    }

    public function project(){
        return view('home.project');
    }

    public function investmentPackage(){
        \App\Models\FomLicenceMiner::ensureTableAndData();
        $packages = \App\Models\FomLicenceMiner::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
        $tokenSetting = \App\Models\TokenSetting::first();
        $tokenSymbol = $tokenSetting->token_symbol ?? 'FOCOIN';

        $user = \Illuminate\Support\Facades\Auth::user();
        $depositWalletBalance = 0.0;
        if ($user) {
            $depositWalletBalance = (float) $user->ChartAccount()->where('acc_type', 'DEPOSIT')->sum('amount');
            \App\Models\FomTokenInstallment::processDueInstallments($user);
            \App\Models\FomTokenStaking::processDueStakings($user);
        }

        return view('user.investment-package', compact('packages', 'tokenSymbol', 'depositWalletBalance'));
    }

    public function buyFomPackage(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to purchase a package.');
        }

        $request->validate([
            'package_id' => 'required|integer',
            'quantity'   => 'required|integer|min:1',
        ]);

        $package = \App\Models\FomLicenceMiner::findOrFail($request->package_id);
        $quantity = (int) $request->quantity;

        $unitPrice   = \App\Models\FomLicenceMiner::cleanNum($package->price);
        $totalCost   = $unitPrice * $quantity;
        $unitReturn  = \App\Models\FomLicenceMiner::cleanNum($package->total_return);
        $totalReturn = $unitReturn * $quantity;

        // Check Deposit Wallet Balance
        $depositBalance = (float) $user->ChartAccount()->where('acc_type', 'DEPOSIT')->sum('amount');
        if ($depositBalance < $totalCost) {
            return back()->with('error', "Insufficient Deposit Wallet balance ($" . number_format($depositBalance, 2) . "). You need $" . number_format($totalCost, 2) . " to purchase {$quantity}x {$package->name} package(s). Please deposit funds into your Deposit Wallet first.");
        }

        // Debit Deposit Wallet
        \App\Models\ChartAccount::updateOrCreate(
            ['user_id' => $user->id, 'acc_type' => 'DEPOSIT'],
            ['amount' => $depositBalance - $totalCost]
        );

        // Generate Individual Activation Codes per unit
        $createdCodes = [];
        $firstActivation = null;

        for ($i = 0; $i < $quantity; $i++) {
            $code = 'FOM-' . strtoupper(\Illuminate\Support\Str::random(13));
            $act = \App\Models\Activations::create([
                'code'     => $code,
                'package'  => $package->name,
                'stutus'   => 'not',
                'price'    => $unitPrice,
                'token'    => $unitReturn,
                'email'    => $user->email,
                'user_id'  => $user->id,
                'period'   => ($package->duration_days ?: 600) . ' Days',
            ]);
            $createdCodes[] = [
                'id'   => $act->id,
                'code' => $code,
            ];
            if ($i === 0) {
                $firstActivation = $act;
            }
        }

        // Log transaction
        $txnNo = class_exists(\App\Models\Transaction::class) && method_exists(\App\Models\Transaction::class, 'generateTransactionNo')
            ? \App\Models\Transaction::generateTransactionNo()
            : 'FOM-BUY-' . time() . '-' . rand(100, 999);

        \App\Models\Transaction::create([
            'user_id'             => $user->id,
            'transaction_no'      => $txnNo,
            'transaction_type'    => 'FOM_PACKAGE_PURCHASE',
            'transaction_details' => json_encode([
                'package'      => $package->name,
                'quantity'     => $quantity,
                'total_cost'   => $totalCost,
                'total_return' => $totalReturn,
                'codes'        => array_column($createdCodes, 'code'),
                'paid_via'     => 'DEPOSIT_WALLET',
            ]),
        ]);

        return back()->with('purchase_success', true)
            ->with('created_codes', $createdCodes)
            ->with('activation_code', $firstActivation->code ?? '')
            ->with('activation_id', $firstActivation->id ?? 0)
            ->with('package_name', $package->name)
            ->with('quantity', $quantity)
            ->with('total_cost', $totalCost)
            ->with('total_return', $totalReturn);
    }

    public function activateFomCode(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to activate your package.');
        }

        $code = trim((string)$request->code);
        $activationId = $request->activation_id;

        $activation = null;
        if ($activationId) {
            $activation = \App\Models\Activations::where('id', $activationId)
                ->whereIn('stutus', ['not', 'pending'])
                ->first();
        }
        if (!$activation && $code !== '') {
            $activation = \App\Models\Activations::where('code', $code)
                ->whereIn('stutus', ['not', 'pending'])
                ->first();
        }

        if (!$activation) {
            return back()->with('error', 'Invalid or already activated code.');
        }

        // Check if user has ALREADY activated an active package with this EXACT package name
        $alreadyActivatedSamePackage = \App\Models\Payment::where('user', $user->id)
            ->where('package', $activation->package)
            ->where('is_expired', false)
            ->exists() || (strtoupper((string)$user->has_paid_package) === strtoupper((string)$activation->package));

        if ($alreadyActivatedSamePackage) {
            return back()->with('error', "You have already activated a {$activation->package} package on your account. You cannot activate a second {$activation->package} package. You can copy this activation code ({$activation->code}) and give it to another user to activate on their account!");
        }

        $totalReturn = (float) $activation->token;
        if ($totalReturn <= 0) {
            $fomPkg = \App\Models\FomLicenceMiner::where('name', $activation->package)->first();
            if ($fomPkg) {
                $totalReturn = \App\Models\FomLicenceMiner::cleanNum($fomPkg->total_return);
            }
        }

        // Mark code as used
        $activation->stutus = 'used';
        $activation->email = $user->email;
        $activation->save();

        // Update user package status & record Payment
        $user->has_paid_package = $activation->package;
        $user->has_free_package = 'no';
        $user->save();

        \App\Models\Payment::create([
            'user'            => $user->id,
            'package'         => $activation->package,
            'amount'          => $activation->price,
            'amount_paid'     => $activation->price,
            'paid'            => $activation->price,
            'over_paid'       => 0,
            'status'          => 1,
            'is_expired'      => false,
            'duration'        => 600,
            'category'        => $activation->package ?: 'FOM',
            'category_id'     => 1,
            'deposit_method'  => 'ACTIVATION_CODE',
            'payable_type'    => \App\Models\Activations::class,
            'payable_id'      => $activation->id,
            'expiration_date' => \Carbon\Carbon::now()->addDays(600)->toDateTimeString(),
        ]);

        // Credit Escrow Wallet (LOCKED_TOKEN)
        $lockedBal = (float) $user->ChartAccount()->where('acc_type', 'LOCKED_TOKEN')->sum('amount');
        \App\Models\ChartAccount::updateOrCreate(
            ['user_id' => $user->id, 'acc_type' => 'LOCKED_TOKEN'],
            ['amount' => $lockedBal + $totalReturn]
        );

        // Create 12 Monthly Installment Schedule
        \App\Models\FomTokenInstallment::createSchedule($user->id, $activation->id, $activation->package, $totalReturn);

        // Process any due installments
        \App\Models\FomTokenInstallment::processDueInstallments($user);

        $tokenSetting = \App\Models\TokenSetting::first();
        $tokenSymbol = $tokenSetting->token_symbol ?? 'FOCOIN';

        return back()->with('success', "Package '{$activation->package}' activated successfully! " . number_format($totalReturn) . " {$tokenSymbol} credited to your Escrow Wallet (Locked Tokens). It will be released in 12 monthly installments into your Available Token balance.");
    }

    public function stakerPackage(){
        return view('user.staker-package');
    }

    public function privacy(){
        return view('terms.privacy');
    }

    public function condition(){
        return view('terms.condition');
    }

    public function info(){
        return view('terms.getInfo');
    }

    public function error(){
        return view('user.user-package')->with('back','payment canceled, not confirmed');
    }

    public function try() {
        return view('user.coin');
    }

    public function requested() {
        return view('requested');
    }

    public function request(Request $request) {
        $email=$request->email;
        $name=$request->name;
        $phone=$request->phone;
        $referee=$request->referee_id;
        $username=$request->user;
        $country=$request->country;

        $requested= new requested();
        $requested->name=$name;
        $requested->email=$email;
        $requested->phone=$phone;
        $requested->status='pending';

        if ($requested->save()){
            return view('auth.register')->with('request','Thank u for requesting to become Bifonex member, You will be contacted very soon');
        } else {
            return view('auth.register')->with('request','request failed, -->consult live chat');
        }
    }

    public function balance() {
        $pay=DB::SELECT('SELECT * from users where activation=1874089 limit 1');
        if (count($pay)) {
            foreach($pay as $payee) {
                $payer=$payee->user;
                $paybalance=new Balance();
                $paybalance->user=$payer;
                $paybalance->reserved_token=500;

                if ($paybalance->save()):
                    return ('saved');
                endif;
                return ('failed');
            }
        }
    }
}
