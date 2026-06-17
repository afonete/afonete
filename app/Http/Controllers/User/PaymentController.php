<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Teams;
use App\Models\balance;
use App\Models\Payment as Paymodel;
use App\Models\Activations;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivationEmail;
use Plisio\PlisioSdkLaravel\Payment;
use GuzzleHttp\Client;
use App\Helpers\PlisioHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Omnipay\Omnipay;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Deposits;
use App\Models\Earnings;
use App\Models\adventures;
use App\Models\Transaction;
use App\Models\DailyIncome;
use App\Models\FCpackage;
use App\Models\TokenSetting;
use App\Models\ChartAccount;

class PaymentController extends Controller
{
    private $gateway;
        public function __construct() {

        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId('AYZrFUtQU92DgPCUhDsUFw3AdxODpp5TarlpyDY5IyAqVFkuawn-AIwSur8IgW11cZKMUcUVEDSw6sGq');
        $this->gateway->setSecret('EBmPPGmGGyfkqJ3liP0Xpon-kRhCDfUhlrmLFLZKm_PjNLL98EnYdto13GYbRPuiBrYbwA9WXxXeRiFQ');
        $this->gateway->setTestMode(true);
    }




  public function blockpay(Request $request)
  {



    if($request->option == 'DEPOSIT'){


        return redirect()->route('user.payment.deposits');
    }
    if($request->option=='crypto'){
        // $api_key = '31T8xOus55ePakEZlzlgqUmZ5w34xcF5lJNPvIKqw7M';

        $value=$request->amount;
        $package=$request->name;
        $email = $request->email;

        // dd($request);

        $length = 8;
        $orderNumber = Str::random($length);
        $client = new Client();
        $url = 'https://plisio.net/api/v1/invoices/new';

        $response = $client->get($url, [
            'query' => [
                'source_currency' => 'USD',
                'amount' =>$request->amount,
                'order_number' => $orderNumber,
                'currency' => 'USDT',
                'email' => Auth::User()->email,
                'order_name' => 'package activation',
                'callback_url' => 'http://focoin.eu/user/package/payment/status',
                'expire_min'=>15,
                'api_key' => 'rPs1vyRlJZChOsYy9F--yeiEUTNgCOzCcnG4bKu_sp3hM5SP64GzWqqdadDM6x95', // Replace with your actual secret key
            ],
        ]);


        // $response = $client->get($url, [
        //     'query' => [
        //         'source_currency' => 'USD',
        //         'amount' => $value,
        //         'order_number' => $orderNumber,
        //         'currency' => 'USDT',
        //         'email' => $email,
        //         'order_name' => 'package activation',
        //         'callback_url' => 'http://127.0.0.1:8000/user/venture/payment/status?json=true',
        //         'expire_min' => 15,
        //         'api_key' => 'rPs1vyRlJZChOsYy9F--yeiEUTNgCOzCcnG4bKu_sp3hM5SP64GzWqqdadDM6x95', // Replace with your actual secret key
        //     ],
        // ]);


        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        $responseData = json_decode($body, true);

        // Get the necessary data from the response
        $txnId = $responseData['data']['txn_id'];

        $invoiceUrl = $responseData['data']['invoice_url'];
        //registering payment
        $user=Auth::user();
         $username=$user->user;

        $userPayment = Paymodel::where('user', $username)->where("category","FC")->orderBy('id','desc')->first();

        $p = FCpackage::where("name",$request->package)->first();
        if (!$userPayment) {


        // Paymodel::create([
        //     'user' => $username,
        //     'package' => $package,
        //     'amount' => $value,
        //     'paid'=>0,
        //     'over_paid'=>0,
        //     'status' => 0,
        // ]);
        // dd($package);

            $create_payable = new  Paymodel([
                'user' => Auth::User()->id,
                'package' => $p->name,
                'amount' => $value,
                'paid'=>0,
                'over_paid'=>0,
                'status' => 0,
                "expiration_date"=>Carbon::now()->addDays(100),
                "duration"=>100,
                "category"=>"FC",
                "category_id"=>2
            ]);
            $pay =  $p->payments()->save($create_payable);
        }
        else {
            $userPayment->update([
            'package' => $package,
            'amount' => $value
            ]);
        }
        // Redirect the user to the invoice URL
        return Redirect::away($invoiceUrl);

        // return redirect()->route('user.venture.contract')->with('message', 'You have  activated '. $package.' account ,
        // Enjoy  earning on Fonepo');

   }

       if($request->option=='paypal'){
          //   return ('1');

                try {

                $response = $this->gateway->purchase(array(
                            'amount' => $request->amount,
                            'currency' => 'USD',
                            'returnUrl' => url('success'),
                            'cancelUrl' => url('error')
                        ))->send();

                if ($response->isRedirect()) {
                    $response->redirect();
                }
                else{
                    return $response->getMessage();
                }

            } catch (\Throwable $th) {
                return $th->getMessage();
            }
       }


  }

  public function SaveDeposits(Request $request)
  {
    $request->validate([
        'amount'      => 'required|numeric|min:10',
        'paymentMethod' => 'required|string',
        'proof_of_payment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
    ]);

    $user   = Auth::user();
    $userId = $user->id;
    $transactionId = Deposits::generateTransactionNo();
    $have_any_recent_deposits = $user->deposits->count();

    // Handle proof-of-payment upload
    $proofPath = null;
    if ($request->hasFile('proof_of_payment')) {
        $proofPath = $request->file('proof_of_payment')
                             ->store('deposits/proofs', 'public');
    }

    Deposits::create([
        'user_id'            => $userId,
        'amount_deposited'   => $request->amount,
        'amount_removed'     => 0,
        'currency_type'      => 'USD',
        'deposit_method'     => $request->paymentMethod,
        'transaction_id'     => $transactionId,
        'network'            => $request->network,
        'user_wallet_address'=> $request->paymentaccount,
        'proof_of_payment'   => $proofPath,
        'status'             => 'pending',
    ]);

    // FIX (D3): A deposit alone should NOT mark the user as "paid" —
    // they still need to actually buy a package. We only set the
    // first-deposit free-package flag if they have no activation yet.

    if ($user->have_activation_code == null && $have_any_recent_deposits == 0) {
        $user->has_free_package = 'yes';
        $user->save();
    }

    return redirect()->route('user.dashboard')
        ->with('message', 'Deposit of $'.$request->amount.' submitted. Awaiting admin approval.');









}

protected function commissionsTrx($userId,$sourceId,$earnings,$trxId,$name){
    Earnings::updateOrCreate(
        [
            'user_id' => $userId,
            'source_id' => $sourceId,
            'source_type' => Paymodel::class,
        ],
        [
            'amount' => $earnings
        ]
    );


    // Accumulate into ChartAccount (fetch existing balance first, then add)
    $existingCommission = ChartAccount::where('user_id', $userId)
                            ->where('acc_type', 'COMMISSION')
                            ->sum('amount');
    ChartAccount::updateOrCreate(
        [
            'user_id' => $userId,
            'acc_type' => 'COMMISSION',
        ],
        [
            'amount' => $existingCommission + $earnings
        ]
    );

    $transaction =  Transaction::create([
        'user_id'=>$userId,//JYEWE
        'transaction_no'=> $trxId,
        'transaction_type'=> 'COMMISSION', // or any other type you define
        'receiver_id'=>0,//
        'transaction_details' => json_encode([
            "amount"=>$earnings,
            'daily'=>0,
            'fomo'=>0,
            "stacking"=>0,
            "directAds"=>0,
            "volume_bonus"=>0,
            "sales_bonus"=>0,
            "leadership_bonus"=>0,
            "royal_fc"=>0,
            "stream_bonus"=>0,
            "direct_bonus"=>0,
            "fomo_bonus"=>0,
            "residual"=>0,
            "team_build"=>0,
            "opportunity"=>0,
            'username'=>$name,
            "eshop"=>0,
            "incetives"=>0
        ])
    ]);
}

protected function calculateEarnings($payment)
{
    /*
     * REFERRAL ENGINE — 3-level system per spec:
     *   L1 (direct)   : 10.00 % of package amount
     *   L2 (indirect) :  1.00 %
     *   L3 (3rd)      :  0.50 %
     *
     * This method now delegates to ReferralService::creditForPayment(),
     * which writes a row per level into `referral_bonuses` (with the
     * Monday withdrawable date). It ALSO updates the legacy `Earnings`
     * table + `ChartAccount` COMMISSION bucket for backward compatibility
     * with existing dashboards.
     */
    $bonusRows = \App\Services\ReferralService::creditForPayment($payment);

    foreach ($bonusRows as $row) {
        $referrer = User::find($row->user_id);
        if (!$referrer) continue;

        // Legacy: keep `earnings` table in sync (one row per source, per level)
        Earnings::updateOrCreate(
            [
                'user_id'     => $referrer->id,
                'source_id'   => $payment->id,
                'source_type' => Paymodel::class,
            ],
            [
                'amount' => (float) $row->bonus_amount,
            ]
        );

        // Legacy: keep ChartAccount COMMISSION bucket growing
        $existingL = ChartAccount::where('user_id', $referrer->id)
                        ->where('acc_type', 'COMMISSION')->sum('amount');
        ChartAccount::updateOrCreate(
            [
                'user_id' => $referrer->id,
                'acc_type' => 'COMMISSION',
            ],
            [
                'amount' => $existingL + $row->bonus_amount,
            ]
        );

        // Mirror transaction log (one entry per level, for user history)
        $trxId = Transaction::generateTransactionNo();
        $description = match ($row->level) {
            1       => 'Direct Referral Commission (10%)',
            2       => 'Indirect Referral Commission (1%)',
            3       => '3rd-Level Referral Commission (0.5%)',
            default => 'Referral Commission L' . $row->level,
        };
        Transaction::create([
            'user_id'             => $referrer->id,
            'transaction_no'      => $trxId,
            'transaction_type'    => 'COMMISSION',
            'receiver_id'         => 0,
            'transaction_details' => json_encode([
                'amount'      => (float) $row->bonus_amount,
                'description' => $description,
                'level'       => $row->level,
                'percentage'  => (float) $row->percentage,
                'source'      => $payment->user,
                'week_start'  => $row->week_start->toDateString(),
                'username'    => $referrer->name,
            ]),
        ]);
    }
}


protected function verifyPlisioCallback($data)
{
    // Example: Verify Plisio signature (depends on Plisio's implementation)
    $secret = 'rPs1vyRlJZChOsYy9F--yeiEUTNgCOzCcnG4bKu_sp3hM5SP64GzWqqdadDM6x95'; // Your Plisio secret key
    $signature = $data['signature'] ?? '';
    unset($data['signature']);

    ksort($data);
    $computedSignature = hash_hmac('sha512', json_encode($data), $secret);

    return hash_equals($signature, $computedSignature);
}


public function paymentFromDeposits(Request $request){
  $user = Auth::user();



  $transactionNo = Deposits::generateTransactionNo();
  $venture = adventures::find($request->package);
  $exp = $venture->duration;

  // Get the current date
  $currentDate = Carbon::now();

  // Add 200 days
  $newDate = $currentDate->addDays($exp);
  // Format the date as needed
  $formattedDate = $newDate->toDateString();
  $expDate = $formattedDate;
 // Registering payment

 $username = $user->id;
 $email = $user->email;
 $amount = $request->amount;
 $purchased = $amount;
//  dd($request);
$user = Auth::User();


  if($request->payment_method == 'FROM_DEPOSITS'){

    $transactionNo= Transaction::generateTransactionNo();

    //Check user wallet
    $lastDeposit = Deposits::where('user_id', $user->id)
                            ->latest()
                            ->first();

    // check for recent investment
    $mostRecentPayment = $user->investments()
                              ->where("is_expired",0)
                              ->where("status",1)
                              ->where("category",'VENTURE')
                              ->orderBy('created_at', 'desc')
                              ->first();

    $totally = $user->investments()
                    ->where("is_expired",0)
                    ->where("status",1)
                    ->where("category" , "!=",'VENTURE')
                    ->orderBy('created_at', 'desc')
                    ->sum("paid");



    // Each investment is independent — don't accumulate into the existing amount.


    $p = adventures::where('id',$request->uvp_id)->first(); // find a range of bought venture

    if(!$p){
        return back()->with("error","Invalid Package, Please make another account to perform desired investment");
    }




        $muser = User::where("id",$user->id)->first();
        $start_date = Carbon::now()->addHours(24);
        $end_date = $start_date->copy()->addDays($p->duration);
        $deposits = Deposits::create([
            'user_id'=>$username,
            'amount_deposited'=>0,
            'amount_removed'=>$request->amount,
            'currency_type'=>'DOLLAR',
            'deposit_method'=>"FROM_DEPOSITS",
            'status'=>"used",
            'transaction_id'=>$transactionNo,
            'user_wallet_address' => $lastDeposit->user_wallet_address,
            'network' => $lastDeposit->network
        ]);

        // ── Token crediting on package purchase ──
        // Uses uvp_price from token_settings (admin-set)
        $uvpPrice         = \App\Models\TokenSetting::uvpPrice();
        $investmentAmount = (float) $request->amount;

        // LOCKED_TOKEN = full investment / uvp_price
        // Locked for 100 days, then auto-transferred to AVAILABLE_TOKEN by CheckPackages.
        // This is the token balance shown to the user on their dashboard.
        $lockedTokens = $uvpPrice > 0 ? round($investmentAmount / $uvpPrice, 4) : 0;

        // GAS_FEE = 20% of investment / uvp_price
        // Internal accounting only. Shown to admin only — never to the user.
        $gasAmount    = $investmentAmount * 20 / 100;
        $gasFeeTokens = $uvpPrice > 0 ? round($gasAmount / $uvpPrice, 4) : 0;

        // Credit LOCKED_TOKEN (full investment tokens locked for 100 days)
        $existingLocked = $user->ChartAccount()->where('acc_type', 'LOCKED_TOKEN')->sum('amount');
        ChartAccount::updateOrCreate(
            ['user_id' => Auth::User()->id, 'acc_type' => 'LOCKED_TOKEN'],
            ['amount'  => $existingLocked + $lockedTokens]
        );

        // Credit GAS_FEE (20% charges — admin-only)
        $existingGas = $user->ChartAccount()->where('acc_type', 'GAS_FEE')->sum('amount');
        ChartAccount::updateOrCreate(
            ['user_id' => Auth::User()->id, 'acc_type' => 'GAS_FEE'],
            ['amount'  => $existingGas + $gasFeeTokens]
        );

        $transaction =  Transaction::create([
            'user_id'=>$user->id,
            'transaction_no' => $transactionNo,
            'transaction_type' => 'SUBSCRIPTION', // or any other type you define
            'receiver_id'=>0,
            'transaction_details' => json_encode([
                'product' => $p->name,
                'user' => $muser->name,
                'plan' => $p->plan,
                'start_date' =>$start_date,
                'end_date'=>$end_date,
                'package'=>$p->plan,
                'price'=>$purchased,
                'current_price'=>$amount,
                'token'=>$uvpPrice > 0 ? round($purchased / $uvpPrice, 4) : 0,
                'current_token'=>$uvpPrice > 0 ? round($amount / $uvpPrice, 4) : 0,
                'poolcapital'=>$purchased*80/100,
                'current_poolcapital'=>$amount*80/100,
                'LP'=>$purchased*20/100,
                'current_LP'=>$amount*20/100,
                'period'=>$p->duration.' days',
                'revenue_earned'=>0,
                'revenue_type'=>'soon',
                'status'=>'success',
                'purchase_date'=>$start_date ,
                'username'=>$muser->user
            ])
        ]);

        // ── Always create a NEW investment record — never overwrite an existing one.
        // Each investment is independent: its own expiration, its own renewal cycle,
        // its own daily income calculation, its own token grant.
        $create_payable = new Paymodel([
            'user'            => $username,
            'package'         => $p->plan,
            'amount'          => $request->amount,
            'paid'            => $request->amount,
            'over_paid'       => 0,
            'status'          => 1,
            'expiration_date' => $expDate,
            'duration'        => $exp,
            'category'        => 'VENTURE',
            'category_id'     => 1,
        ]);

        $pay = $p->payments()->save($create_payable);
        $this->calculateEarnings($pay);
        $this->calculatePackageMetrics($pay);

        $muser->update(["has_paid_package" => $p->name, "has_free_package" => "no"]);
        return redirect()->route('user.dashboard')
            ->with('message', 'Investment of $'.number_format($request->amount, 2).' activated successfully.');
  }

}
public function blockpayventure(Request $request)
{


  $venture = adventures::find($request->package);
  $exp = $venture->duration;


  // Get the current date
  $currentDate = Carbon::now();

  // Add 200 days
  $newDate = $currentDate->addDays($exp);
  // Format the date as needed
  $formattedDate = $newDate->toDateString();
  $expDate = $formattedDate;
 // Registering payment
 $user = Auth::user();
 $username = $user->id;
 $email = $user->email;
 $amount = $request->amount;
 $purchased = $amount;

  if($request->payment_method == 'FROM_DEPOSITS'){

    $transactionNo= Transaction::generateTransactionNo();
    // check for recent investment
    $mostRecentPayment = $user->investments()->where("is_expired",0)->where("status",1)->where("category",'VENTURE')->orderBy('created_at', 'desc')->first();
    $totally = $user->investments()
                    ->where("is_expired",0)
                    ->where("status",1)
                    ->where("category" , "!=",'VENTURE')
                    ->orderBy('created_at', 'desc')
                    ->sum("paid");



    // Each investment is independent — don't accumulate into the existing amount.
    // $p = adventures::where("id",$request->package)->first();
    $p = adventures::where('min_amount', '<=', $amount)
                    ->where('max_amount', '>=', $amount)
                    ->first(); // find a range of bought venture

    if(!$p){
        return back()->with("error","Invalid Package, Please make another account to perform desired investment");
    }

        $muser = User::where("id",$user->id)->first();
        $start_date = Carbon::now()->addHours(24);
        $end_date = $start_date->copy()->addDays($p->duration);
        $deposits = Deposits::create([
            'user_id'=>$username,
            'amount_deposited'=>0,
            'amount_removed'=>$request->amount,
            'currency_type'=>'DOLLAR',
            'deposit_method'=>"FROM_DEPOSITS",
            'status'=>"used"
        ]);

        $transaction =  Transaction::create([
            'user_id'=>$user->id,
            'transaction_no' => $transactionNo,
            'transaction_type' => 'SUBSCRIPTION', // or any other type you define
            'receiver_id'=>0,
            'transaction_details' => json_encode([
                'product' => $p->name,
                'user' => $muser->name,
                'plan' => $p->plan,
                'start_date' =>$start_date,
                'end_date'=>$end_date,
                'package'=>$p->plan,
                'price'=>$purchased,
                'current_price'=>$amount,
                'poolcapital'=>$purchased*80/100,
                'current_poolcapital'=>$amount*80/100,
                'LP'=>$purchased*20/100,
                'current_LP'=>$amount*20/100,
                'period'=>$p->duration.' days',
                'revenue_earned'=>0,
                'revenue_type'=>'soon',
                'status'=>'success',
                'purchase_date'=>$start_date ,
                'username'=>$muser->user
            ])
        ]);

        // ── Always create a NEW investment record — never overwrite an existing one.
        $create_payable = new Paymodel([
            'user'            => $username,
            'package'         => $p->plan,
            'amount'          => $request->amount,
            'paid'            => $request->amount,
            'over_paid'       => 0,
            'status'          => 1,
            'expiration_date' => $expDate,
            'duration'        => $exp,
            'category'        => 'VENTURE',
            'category_id'     => 1,
        ]);

        $pay = $p->payments()->save($create_payable);
        $this->calculateEarnings($pay);
        $this->calculatePackageMetrics($pay);

        $muser->update(["has_paid_package" => $p->name, "has_free_package" => "no"]);
        return redirect()->route('user.dashboard')
            ->with('message', 'Investment of $'.number_format($request->amount, 2).' activated successfully.');


  }

  if ($request->option == 'crypto') {
    $value = $request->amount;

    if($value < 10){
        return view("user.payment-custom-error",["amount"=>$value]);
    }
    // dd($value);
    $package = $request->package;
    $length = 8;
    $orderNumber = Str::random($length);

    $client = new Client();
    $url = 'https://plisio.net/api/v1/invoices/new';

    try {
        $response = $client->get($url, [
            'query' => [
                'source_currency' => 'USD',
                'amount' => $value,
                'order_number' => $orderNumber,
                'currency' => 'USDT',
                'email' => $email,
                'order_name' => 'package activation',
                'callback_url' => 'http://127.0.0.1:8000/user/venture/payment/status?json=true',
                'expire_min' => 15,
                'api_key' => 'rPs1vyRlJZChOsYy9F--yeiEUTNgCOzCcnG4bKu_sp3hM5SP64GzWqqdadDM6x95', // Replace with your actual secret key
            ],
        ]);


        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        $responseData = json_decode($body, true);

        if ($statusCode === 200 && isset($responseData['data'])) {
            $txnId = $responseData['data']['txn_id'];
            $invoiceUrl = $responseData['data']['invoice_url'];



            $userPayment = Paymodel::where('user', $username)
                ->where("is_expired", 0)
                ->where("status", 1)
                ->orderBy('id', 'desc')
                ->first();

                session(['payment_intent' => [
                    'user' => $username,
                    'package' => $package,
                    'amount' => $value,
                    'paid' => 0,
                    'over_paid' => 0,
                    'status' => 0,
                    'expiration_date' => $expDate,
                    "duration" => $exp,
                    "category" => "VENTURE",
                    "category_id" => 1
                ]]);

                return Redirect::away($invoiceUrl);

        } else {
            // Handle API error response
            return redirect()->back()->with('error', 'Failed to create invoice. Please try again later.');
        }
    } catch (RequestException $e) {
        // Customize error handling
        $errorResponse = $e->getResponse();
        dd($errorResponse);
        $errorBody = json_decode($errorResponse->getBody()->getContents(), true);
        $customErrorMessage = $this->parseError($errorBody);

        // return redirect()->back()->with('error', $customErrorMessage);
    }
}



}



public function calculatePackageMetrics($package)
        {
            // The amount paid for the package
            $amount = $package->paid;
            // $dailyIncome = $amount * 25 /100;



            // Calculate Fcoin
            // $fcoin = $amount / 0.0025;
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
            // $t75PercentOfDailyIncome = $dailyIncome * 75 / 100;
            // $shopping = $t75PercentOfDailyIncome;

            // The date the package was created
            // $createdDate = $package->created_at;

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

protected function parseError($errorBody)
{
    // Customize this function to parse and translate API errors into user-friendly messages
    $errorMessage = $errorBody['data']['message'] ?? 'An unexpected error occurred.';

    // return 'Custom Error: ' . $errorMessage;
}



 // paypal success




    public function error()
    {
          return redirect()->route('user.dashboard')->with('message','Oppss, you declained the payment');

    }

  private  function generateActivationCode($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';

    for ($i = 0; $i < $length; $i++) {
        $randomIndex = mt_rand(0, strlen($characters) - 1);
        $code .= $characters[$randomIndex];
    }

    return $code;
}

public function successVenture(Request $request)
{
    // Handling registered payment

    $user = Auth::user();
    $username = $user->user;
    $paymentIntent = session('payment_intent');

    if (!$paymentIntent) {
        return response()->json(["message" => "Invalid payment intent."], 400);
    }

    $first_payment = Paymodel::where('user', $username)
                            ->orderBy('created_at', 'asc')
                            ->first();

    $overPaid = 0;
    $status = 0;
    $value = $request->amount;

    if (!$first_payment) {
        if ($paymentIntent['amount'] > $value) {
            $status = 1; // Underpayment
        } else if ($paymentIntent['amount'] < $value) {
            $status = 3; // Overpayment
            $overPaid = $value - $paymentIntent['amount'];
        }

        Paymodel::create([
            'user' => $paymentIntent['username'],
            'package' => $paymentIntent['package'],
            'amount' => $paymentIntent['amount'],
            'paid' => $value,
            'over_paid' => $overPaid,
            'status' => $status,
            'expiration_date' => $paymentIntent['expiration_date'],
            "duration" => $paymentIntent['duration'],
            "category" => "VENTURE",
            "category_id" => 0
        ]);

    } else if ($first_payment->status == 2) {
        $recent_paid = $first_payment->paid;
        $ac = $recent_paid + $value;
        $status = $this->determineStatus($first_payment->amount, $ac);
        $overPaid = $ac > $first_payment->amount ? $ac - $first_payment->amount : 0;
        $first_payment->paid = $ac - $overPaid;
        $first_payment->over_paid = $overPaid;
        $first_payment->status = $status;
        $first_payment->save();
    }

    $payments = Paymodel::where('user', $username)
                        ->orderBy('created_at', 'asc')
                        ->first();

    if ($payments->status == 2) {
        return view('user.venture-underpayment');
    } else if ($payments->status == 1 || $payments->status == 3) {
        $user->has_paid_package = 'UVP';

        if ($payments->status == 3) {
            $amount_overPaid = $payments->paid - $payments->amount;
            balance::create([
                'user' => $username,
                'cash' => $amount_overPaid,
            ]);
        }

        if ($user->save()) {
            $this->calculateEarnings($payments);
            return redirect()->route('user.venture.contract');
        } else {

            return response()->json(["message" => "Updating User Failed"], 500);
        }
    }
}

public function ventureError(){
    return view('user.pay')->with('p_failed','Payment failed, Retry again');
}


private function MyDepositBalance(){
    $user=Auth::user();
    $userId = $user->id;
    $user = User::find($userId);



    $deposits = $user->deposits->filter(function ($deposit) {
        return $deposit->status == 'approved';
    });
    $deposits_used = $user->deposits->filter(function ($deposit) {
        return $deposit->status == 'used';
    });
  $differences = $deposits->sum('amount_deposited') - $deposits_used->sum('amount_removed');

 $sum = $differences;

 return $sum;
}


public function ventureCallback(Request $request)
{
    // Get the data from the callback
    $data = $request->all();

    // Verify the callback signature (optional but recommended)
    $isValid = $this->verifyPlisioCallback($data);
    if (!$isValid) {
        return response()->json(['message' => 'Invalid signature'], 400);
    }

    // Extract relevant payment details from the callback
    $callbackUsername = $data['user'];
    $amountPaid = $data['amount'];
    $callbackStatus = isset($data['status']) ? $this->mapPlisioStatus($data['status']) : 0;

    // Retrieve the authenticated user and payment intent from the session
    $user = Auth::User();
    $username = $user->user;
    $userId = $user->id;
    $paymentIntent = session('payment_intent');

    if (!$paymentIntent) {
        return response()->json(["message" => "Invalid payment intent."], 400);
    }

    // Retrieve the first payment record for the user
    $firstPayment = Paymodel::where('user', $userId)
                             ->where("category","VENTURE")
                            ->orderBy('created_at', 'asc')
                            ->first();

    $overPaid = 0;
    $status = $callbackStatus;

    if (!$firstPayment) {
        // If this is the first payment, determine the status
        if ($paymentIntent['amount'] > $amountPaid) {
            $status = 1; // Underpayment
        } elseif ($paymentIntent['amount'] < $amountPaid) {
            $status = 3; // Overpayment
            $overPaid = $amountPaid - $paymentIntent['amount'];
        }

        // Create a new payment record

        $p = adventures::where("id",$paymentIntent['package'])->first();
        $muser = User::where("id",$user->id)->first();
        $muser->update(["has_paid_package"=>$p->name,"has_free_package"=>"no"]);

        Paymodel::create([
            'user' => $username,
            'package' => $paymentIntent['package'],
            'amount' => $paymentIntent['amount'],
            'paid' => $amountPaid - $overPaid,
            'over_paid' => $overPaid,
            'status' => $status,
            'expiration_date' => $paymentIntent['expiration_date'],
            'duration' => $paymentIntent['duration'],
            'category' => 'VENTURE',
            'category_id' => 0
        ]);

    } elseif ($firstPayment->status == 2) {
        // If this is an additional payment, update the existing record
        $recentPaid = $firstPayment->paid;
        $totalPaid = $recentPaid + $amountPaid;
        $status = $this->determineStatus($firstPayment->amount, $totalPaid);

        $overPaid = $totalPaid > $firstPayment->amount ? $totalPaid - $firstPayment->amount : 0;
        $firstPayment->paid = $totalPaid - $overPaid;
        $firstPayment->over_paid = $overPaid;
        $firstPayment->status = $status;
        $firstPayment->save();
    }

    // Retrieve the updated payment record
    $payments = Paymodel::where('user', $userI)
                        ->where("category","VENTURE")
                        ->orderBy('created_at', 'asc')
                        ->first();

    if ($payments->status == 2) {

        return view('user.venture-underpayment');
    } elseif ($payments->status == 1 || $payments->status == 3) {
        $user->has_paid_package = 'UVP';

        if ($payments->status == 3) {
            $amountOverPaid = $payments->paid - $payments->amount;
            balance::create([
                'user' => $username,
                'cash' => $amountOverPaid,
            ]);
        }

        if ($user->save()) {
            // Redirect to sign contract
            return redirect()->route('user.venture.contract');
        } else {
            return response()->json(["message" => "Updating User Failed"], 500);
        }
    }

    // Handle any other unexpected statuses
    return response()->json(["message" => "Unhandled payment status."], 400);
}



protected function mapPlisioStatus($status)
{
    // Map Plisio status to your application's status codes
    switch ($status) {
        case 'completed':
            return 1; // Paidwell
        case 'pending':
            return 2; // Tried
        case 'underpaid':
            return 2; // Underpayment
        case 'overpaid':
            return 3; // Overpayment
        default:
            return 0; // Unknown status or failed
    }
}

// public function ventureCallback(Request $request)
// {
//     if (verifyCallbackData()) {
//             $status = 'success';
//             return view('user.callback', compact('status'));
//         }
//         else {
//             // Invalid callback data
//             $status = 'error';
//             $message = 'Invalid callback data';
//             return view('user.callback', compact('status', 'message'));
//         }
// }


private function determineStatus($amount, $paid)
{
    if ($paid == $amount) {
        return 1;  // The payment is exactly what was expected.
    } elseif ($paid < $amount) {
        return 2;  // The payment is less than expected.
    } else {
        return 3;  // The payment is more than expected.
    }
}


public function pssuccess(Request $request)
  {
    //  Handling regsistered payment
    $user=Auth::user();
    $username=$user->user;
    $userPayment = Paymodel::where('user', $username)->orderBy('id','desc')->first();
    $toBePid=$userPayment->amount;

    $package=$userPayment->package;
    $totalPaid = Paymodel::where('user', $username)->sum('paid');
    if (empty($totalPaid)) {
        $totalPaid = 0;
    }

   $value=$request->amount;
   $paidNow=$value+$totalPaid;
       if ($paidNow<$toBePid) {
     Paymodel::create([
        'user' => $username,
        'package' => $package,
        'amount' => $toBePid,
        'paid'=>$value,
        'over_paid'=>0,
        'status' => 2,
    ]);

     return view('user.underpayment');
    }
   if ($paidNow>$toBePid) {

   $overPaid=$totalPaid-$value;
     Paymodel::create([
        'user' => $username,
        'package' => $package,
        'amount' => $toBePid,
        'paid'=>0,
        'over_paid'=>$overPaid,

        'status' => 3,
    ]);
    //store in cashout
      $overPaid=$overPaid+0.0000;
      balance::create([
        'user' => $username,
        'cash'=>$overPaid,
    ]);

    }
       if ($paidNow==$toBePid) {

   $overPaid=$totalPaid-$value;
     Paymodel::create([
        'user' => $username,
        'package' => $package,
        'amount' => $toBePid,
        'paid'=>$paidNow,
        'over_paid'=>0,

        'status' => 1,
    ]);

    }
     //updating value
     $value=$package==100?100:200;

    $pack=$value==100?'FC $100':'FC $200';
    $token=$value==100?'10000':'20000';

        $activation = $this->generateActivationCode(20);
        response()->json($activation);
        $user = Auth::user();
        $userId = $user->id;
        $email =$user->email;
        $a_user=$user->user;
        $referral=$user->referre_id;
        $balance=new balance();
        $balance->user=$a_user;
        $balance->reserved_token=$token;
        $balance->save();
        $user = User::find($userId);
        $user->has_paid_package = $package;
    //   $payment = new Payments();
    //                 $payment->invoiceid = $userId;
    //                 // $payment->payer_email = $arr['payer']['payer_info']['email'];
    //                 $payment->amount = $
    //                 // $payment->currency = 'USD';
    //                 $payment->status = $arr['state'];

    //                 $payment->save();
    $user->has_free_package = 'yes';
    $user->activation = $activation;
    $activations= new activations();

      // $userId = $user->id;
      // $given= $user->activation;
            $activations->code=$activation;
            $activations->package=$package;
            $activations->stutus='used';

                     $activations->email=$email;
                    // Save the changes to the database
                 if($activations->save())

    $send = $this->SendCode($email, $activation,$pack);
    response()->json($send);
    $pay=DB::SELECT('SELECT * from users where activation =:refer limit 1',['refer'=>$referral]);
    global $paybalance;
    if(count($pay)){

            $payer=$pay[0]->user;
           $paybalance=new balance();
            $paybalance->user=$payer;
            $paybalance->reserved_token=500;

    }

    if ($user->save() && $paybalance->save()) {
      return redirect()->route('user.dashboard')->with('message', 'You have been  activated '.$pack.' account , Enjoy unlimited earning on Fonepo');

    } else {
      return redirect()->route('user.package')->with('message', 'error while savig');
    }
    }

//   venture purchase success



public function free(Request $request)
    {
      $activation = $this->generateActivationCode(20);
        response()->json($activation);
      $user = Auth::user();
      $userId = $user->id;
      $email =$user->email;
      $user = User::find($userId);
      $user->has_paid_package = 'standard';
      $user->contract='Signed';
      $package='standard';
      $name=$user->user;
      $user->has_free_package = 'yes';

      $send = $this->SendCode($email, $activation,$package);
      response()->json($send);


      if ($user->save() ) {
        return redirect()->route('user.dashboard')->with('message', 'You have been  activated standard account , Enjoy free earning on Fonepo');

      } else {
        return redirect()->route('user.package')->with('message', 'error while savig');
      }

    }


  //activation function



  private function SendCode($emaili,$activation,$package) {


$email=$emaili;
    $mail = new ActivationEmail($activation,$package);
    Mail::to($email)->send($mail);
  return redirect()->route('user.dashboard');
}

 public function unconfirmed(){
      return view('user.user-package')->with('message',' activation failed,try again');
                    //return view('user.dshboard');
 }

  public function fc1(){
      return view('user.pay')->with('package','fc1');
    // return('fc1');

 }

 public function fc($id){
    $package = FCpackage::find($id);
    return view('user.pay',['package'=>$package]);
  // return('fc1');

}

//  PAYMENT ERROR


// public function paymentError(Request $request){

//     return view("user.payment-custom-error.blade");
// }


 public function ventures(Request $venture){ // 2000

    $user = Auth::user();
    $recent = $user->investments()
                        ->where("is_expired", 0)
                        ->where('status', 1)
                        ->orderBy("created_at", 'desc')
                        ->first();
    $currentBalance = $this->MyDepositBalance();
    $requiredAmount = $currentBalance - $venture->amount_invest;
    $status = ($requiredAmount < 0) ? 'i':'s';
    if($requiredAmount < 0){
        $requiredAmount = -($requiredAmount);
    }
    
    if($recent->paid >= $venture->amount_invest  && $recent->category == 'VENTURE' ){
        return view("user.confirm-package-payments-error",[
            "amount"=>$venture->amount_invest,
            "venture"=>$adventure,
            "currentBalance"=>$currentBalance,
            "requiredAmount"=>$requiredAmount,
            "status"=>$status,
            "recent"=>$recent
        ]);
    }


    $payment_method = $venture->payment_method;
    if($payment_method == "FROM_DEPOSITS"){

        if($venture->venture == "FC"){
            
            
            return view("user.confirm-package-payments",[
                "amount"=>$venture->amount_invest,
                "package"=>$venture->venture,
                "routes"=>'fc',
                "name"=>$venture->routes,
                'id'=>$venture->package,
                "currentBalance"=>$currentBalance,
                "requiredAmount"=>number_format($requiredAmount),
                "status"=>$status

            ]);
        }

        $adventure = adventures::where("id",$venture->venture)->first();
       
        if($requiredAmount < 0){
            $requiredAmount = -($requiredAmount);
        }
        return view("user.confirm-package-payments",[
            "amount"=>$venture->amount_invest,
            "venture"=>$adventure,
            "currentBalance"=>$currentBalance,
            "requiredAmount"=>$requiredAmount,
            "status"=>$status,
            "recent"=>$recent
        ]);
    }
   else if($payment_method == "FROM_DEPOSITS_FC"){
       
      

        return view("user.confirm-package-payments",[
                                                    "amount"=>$venture->amount_invest,
                                                    "venture"=>$adventure,
                                                    "currentBalance"=>$currentBalance,
                                                    "requiredAmount"=>$requiredAmount,
                                                    "status"=>$status
                                                ]);
    }
    else{
        return view('user.venture',["venture"=>$venture]);
    }


}


  public function fc2(){
      return view('user.pay')->with('package','fc2');

 }


 public function pscallback(Request $request)
{
    if (verifyCallbackData()) {

            $status = 'success';
            return view('user.callback', compact('status'));
        }
        else {
            // Invalid callback data
            $status = 'error';
            $message = 'Invalid callback data';
            return view('user.callback', compact('status', 'message'));
        }
}

 public function pserror()
{
    return view('user.pay')->with('p_failed','Payment failed, Retry again');
}



public function PaymentFcFromDeposit(Request $request){

    $user = Auth::User();
    $amount = $request->amount;
    $expireAt = Carbon::now()->addDays(100);

    $mostRecentPayment = $user->investments()
                              ->where("is_expired",0)
                              ->where("status",1)
                              ->where("category",'FC')
                              ->orderBy('created_at', 'desc')
                              ->first();
    $p = FCpackage::where("name",$request->pack)->first();
    if(!$mostRecentPayment){
        $create_payable =  new Paymodel([
            'user' => $user->id,
            'package' => $p->name,
            'amount' => $p->price,
            'paid' => $amount,
            'over_paid' => 0,
            'status' => 1,
            'expiration_date' => $expireAt,
            'duration' => 100,
            'category' => 'FC',
            'category_id' => 0
        ]);

        $pay =  $p->payments()->save($create_payable);
        $this->updateHasPaidPackage($p->name);

    }
    else{
            $newAmount = $mostRecentPayment->amount + $p->price;
            $mostRecentPayment->update([
                'package' => $p->name,
                'amount' => $newAmount,
                'paid' => $newAmount,
            ]);
            $this->updateHasPaidPackage($p->name);
    }

    $deposits = Deposits::create([
        'user_id'=>Auth::User()->id,
         'amount_deposited'=>0,
         'amount_removed'=>$amount,
         'currency_type'=>'DOLLAR',
         'deposit_method'=>"FROM_DEPOSITS",
         'status'=>"used"
    ]);


    $transactionNo= Transaction::generateTransactionNo();
    $transaction =  Transaction::create([
        'user_id'=>$user->id,
        'transaction_no' => $transactionNo,
        'transaction_type' => 'SUBSCRIPTION', // or any other type you define
        'receiver_id'=>0,
        'transaction_details' => json_encode([
            'product' => $p->name,
            'user' => Auth::User()->email,
            'plan' => 'soon',
            'start_date' =>Carbon::now(),
            'end_date'=>Carbon::now()->addDays(100),
            'package'=>$p->name,
            'price'=>$p->price,
            'token'=>$p->default_token,
            'current_price'=>$mostRecentPayment->amount,
            'poolcapital'=>0,
            'current_poolcapital'=>0,
            'LP'=>0,
            'current_LP'=>0,
            'period'=>$p->duration.' days',
            'revenue_earned'=>0,
            'revenue_type'=>'soon',
            'status'=>'success',
            'purchase_date'=>Carbon::now() ,
            'username'=>Auth::User()->name


        ])
    ]);

return redirect()->route("user.dashboard");

}
private function updateHasPaidPackage($packageName)
{
    Auth::user()->update([
        'has_free_package'=>'no',
        'has_paid_package' => $packageName
    ]);
}


 }
