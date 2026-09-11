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
use App\Models\Adventures;
use App\Models\Transaction;
use App\Models\DailyIncome;
use App\Models\FCpackage;
use App\Models\TokenSetting;
use App\Models\ChartAccount;
use App\Services\DirectPackagePaymentService;

class PaymentController extends Controller
{
    private $gateway;
        public function __construct() {

        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId('AYZrFUtQU92DgPCUhDsUFw3AdxODpp5TarlpyDY5IyAqVFkuawn-AIwSur8IgW11cZKMUcUVEDSw6sGq');
        $this->gateway->setSecret('EBmPPGmGGyfkqJ3liP0Xpon-kRhCDfUhlrmLFLZKm_PjNLL98EnYdto13GYbRPuiBrYbwA9WXxXeRiFQ');
        $this->gateway->setTestMode(true);
    }


    /**
     * Create a direct USDT TRC20 package-payment invoice for UVP/FC.
     * This replaces Plisio for package purchases.
     */
    public function directPackagePayment(Request $request, DirectPackagePaymentService $directPayments)
    {
        $request->validate([
            'package_type' => 'required|string|in:VENTURE,UVP,FC',
            'package_id'   => 'required|integer|min:1',
            'amount'       => 'nullable|numeric|min:0.01',
            'network'      => 'nullable|string|in:TRC-20,TRC20,TRON',
        ]);

        try {
            $deposit = $directPayments->createPendingIntent(
                Auth::user(),
                $request->input('package_type'),
                (int) $request->input('package_id'),
                $request->filled('amount') ? (float) $request->input('amount') : null,
                $request->input('network', 'TRC-20')
            );

            return redirect()->route('payment.directPackage.show', $deposit->id);
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function showDirectPackagePayment(Deposits $deposit, DirectPackagePaymentService $directPayments)
    {
        if ((int) $deposit->user_id !== (int) Auth::id()) {
            abort(403);
        }

        if ($deposit->status === 'approved' && !$deposit->activated_payment_id) {
            try {
                $directPayments->activateFromDeposit($deposit);
                $deposit->refresh();
            } catch (\Throwable $e) {
                \Log::error('Direct package activation from invoice page failed: ' . $e->getMessage(), ['deposit_id' => $deposit->id]);
            }
        }

        if ($deposit->activated_payment_id) {
            $target = $this->dashboardRouteForUser(Auth::user());
            $message = $target === 'user.dashboard'
                ? 'Your ' . ($deposit->package_name ?: 'package') . ' has been activated successfully.'
                : 'Your ' . ($deposit->package_name ?: 'package') . ' has been activated successfully. Please sign the contract to access your dashboard.';

            return redirect()->route($target)->with('message', $message);
        }

        return view('user.direct-tron-package-payment', compact('deposit'));
    }

    public function directPackagePaymentStatus(Deposits $deposit, DirectPackagePaymentService $directPayments)
    {
        if ((int) $deposit->user_id !== (int) Auth::id()) {
            abort(403);
        }

        if ($deposit->status === 'approved' && !$deposit->activated_payment_id) {
            try {
                $directPayments->activateFromDeposit($deposit);
                $deposit->refresh();
            } catch (\Throwable $e) {
                \Log::error('Direct package activation status check failed: ' . $e->getMessage(), ['deposit_id' => $deposit->id]);
            }
        }

        $secondsRemaining = $deposit->expires_at
            ? max(0, now()->diffInSeconds($deposit->expires_at, false))
            : null;
        $expired = !$deposit->activated_payment_id && $deposit->status === 'pending' && $deposit->expires_at && now()->greaterThan($deposit->expires_at);

        return response()->json([
            'status'            => $deposit->status,
            'activated'         => (bool) $deposit->activated_payment_id,
            'expired'           => (bool) $expired,
            'cancelled'         => $deposit->status === 'cancelled',
            'expires_at'        => $deposit->expires_at ? $deposit->expires_at->toIso8601String() : null,
            'seconds_remaining' => $secondsRemaining,
            'package_name'      => $deposit->package_name,
            'amount'            => (float) $deposit->amount_deposited,
            'redirect_url'      => $deposit->activated_payment_id
                ? route($this->dashboardRouteForUser(Auth::user()))
                : null,
        ]);
    }

    public function cancelDirectPackagePayment(Deposits $deposit)
    {
        if ((int) $deposit->user_id !== (int) Auth::id()) {
            abort(403);
        }

        if ($deposit->activated_payment_id || $deposit->status === 'approved') {
            return redirect()->route('payment.directPackage.show', $deposit->id)
                ->with('error', 'This payment is already confirmed and cannot be cancelled.');
        }

        if ($deposit->status === 'pending') {
            $deposit->update([
                'status'  => 'cancelled',
                'comment' => trim(($deposit->comment ? $deposit->comment . "\n" : '') . 'User cancelled this automatic TRC-20 payment invoice to use manual deposit option.'),
            ]);
        }

        return redirect()->route('user.manual-deposit')->with('message', 'Automatic TRC-20 payment order cancelled. You can now complete your payment using the manual deposit option.');
    }

    public function manualDepositPage()
    {
        $wallets = \App\Models\DepositWallet::activeList();
        $minDeposit = (float) (\App\Models\WithdrawalSetting::current()->min_deposit_amount ?? 10);
        $pendingDeposit = Auth::user()->deposits()
            ->where('payment_context', 'MANUAL_DEPOSIT_ACCESS')
            ->where('status', 'pending')
            ->latest()
            ->first();

        return view('user.manual-deposit', compact('wallets', 'minDeposit', 'pendingDeposit'));
    }

    public function submitManualDeposit(Request $request)
    {
        $minDeposit = (float) (\App\Models\WithdrawalSetting::current()->min_deposit_amount ?? 10);

        $request->validate([
            'amount'           => 'required|numeric|min:' . $minDeposit,
            'wallet_id'        => 'required|integer|exists:deposit_wallets,id',
            'paymentaccount'   => 'required|string|max:255',
            'proof_of_payment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ], [
            'amount.min' => 'Minimum deposit is $' . number_format($minDeposit, 2) . '.',
            'wallet_id.required' => 'Please select a manual deposit method.',
            'paymentaccount.required' => 'Please enter the wallet/account you paid from.',
            'proof_of_payment.required' => 'Please upload proof of payment for admin approval.',
        ]);

        $wallet = \App\Models\DepositWallet::where('id', $request->wallet_id)
            ->where('is_active', true)
            ->firstOrFail();

        $amount = (float) $request->amount;
        $walletMin = (float) $wallet->min_amount;
        $walletMax = (float) $wallet->max_amount;
        if ($walletMin > 0 && $amount < $walletMin) {
            return back()->withInput()->with('error', 'Minimum for this method is $' . number_format($walletMin, 2) . '.');
        }
        if ($walletMax > 0 && $amount > $walletMax) {
            return back()->withInput()->with('error', 'Maximum for this method is $' . number_format($walletMax, 2) . '.');
        }

        $proofPath = $request->file('proof_of_payment')->store('deposits/proofs', 'public');

        $deposit = Deposits::create([
            'user_id'             => Auth::id(),
            'amount_deposited'    => $amount,
            'amount_removed'      => 0,
            'currency_type'       => $wallet->currency ?: 'USD',
            'deposit_method'      => 'MANUAL_' . strtoupper((string) $wallet->type),
            'payment_context'     => 'MANUAL_DEPOSIT_ACCESS',
            'transaction_id'      => Deposits::generateTransactionNo(),
            'network'             => $wallet->network,
            'deposit_address'     => $wallet->wallet_address,
            'user_wallet_address' => trim((string) $request->paymentaccount),
            'proof_of_payment'    => $proofPath,
            'status'              => 'pending',
            'expires_at'          => now()->addMinutes(15),
            'comment'             => 'Manual deposit submitted by user. Awaiting admin approval. 15-minute countdown shown to user/admin for review visibility.',
        ]);

        return redirect()->route('user.manual-deposit.waiting', $deposit->id)
            ->with('message', 'Deposit submitted. Please wait for admin approval.');
    }

    public function manualDepositWaiting(Deposits $deposit)
    {
        if ((int) $deposit->user_id !== (int) Auth::id()) {
            abort(403);
        }

        if ($deposit->status === 'approved') {
            $target = $this->dashboardRouteForUser(Auth::user());
            $message = $target === 'user.dashboard'
                ? 'Your deposit has been approved. You now have dashboard access.'
                : 'Your deposit has been approved. Please sign the contract before accessing your dashboard.';

            return redirect()->route($target)->with('message', $message);
        }

        return view('user.manual-deposit-waiting', compact('deposit'));
    }

    public function manualDepositStatus(Deposits $deposit)
    {
        if ((int) $deposit->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $secondsRemaining = $deposit->expires_at
            ? max(0, now()->diffInSeconds($deposit->expires_at, false))
            : null;

        $expired = $deposit->status === 'pending' && $deposit->expires_at && now()->greaterThan($deposit->expires_at);

        return response()->json([
            'status'            => $deposit->status,
            'expired'           => (bool) $expired,
            'seconds_remaining' => $secondsRemaining,
            'redirect_url'      => $deposit->status === 'approved'
                ? route($this->dashboardRouteForUser(Auth::user()))
                : null,
        ]);
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

    private function redirectToDirectPackagePayment(string $packageType, int $packageId, ?float $amount = null, string $network = 'TRC-20')
    {
        try {
            $deposit = app(DirectPackagePaymentService::class)->createPendingIntent(
                Auth::user(),
                $packageType,
                $packageId,
                $amount,
                $network
            );

            return redirect()->route('payment.directPackage.show', $deposit->id);
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }


    /**
     * §FC-INDEPENDENCE tier + duplicate guards:
     *   • User cannot buy an FC package at a price LOWER than their highest FC tier.
     *   • User cannot buy an FC package at the SAME price as a tier they already hold.
     *   • FC tier rules never compare against UVP history (kept separate).
     *
     * Returns null on success or an error message string on violation.
     */
    private function validateFcTierPurchase(User $user, FCpackage $package): ?string
    {
        $price   = round((float) $package->price, 2);
        $highest = round((float) $user->highestFcPackageAmount(), 2);

        if ($highest > 0 && $price < $highest) {
            return 'FC VIP Package Purchase Error: You cannot purchase an FC VIP package ($'
                . number_format($price, 2) . ') below your highest previously purchased FC VIP package ($'
                . number_format($highest, 2) . ').';
        }

        if ($highest > 0 && abs($price - $highest) < 0.01) {
            return 'FC VIP Package Purchase Error: You already hold the "'
                . $package->name . '" FC VIP package ($' . number_format($price, 2)
                . '). Duplicate purchases of the same FC tier are not allowed. Please choose a higher FC VIP tier to upgrade.';
        }

        return null;
    }


  public function blockpay(Request $request)
  {

    if($request->option == 'DEPOSIT'){

        return redirect()->route('user.payment.deposits');
    }
    if($request->option=='crypto'){
        $user    = Auth::user();
        $package = FCpackage::where("name", $request->package)->first();
        if (!$package) {
            return back()->with('error', 'Invalid FC package selected.');
        }
        if ($msg = $this->validateFcTierPurchase($user, $package)) {
            return back()->with('error', $msg);
        }

        // Plisio removed: create a direct USDT TRC20 package-payment invoice.
        return $this->redirectToDirectPackagePayment('FC', (int) $package->id, (float) $package->price, $request->input('network', 'TRC-20'));

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

    $deposit = Deposits::create([
        'user_id'            => $userId,
        'amount_deposited'   => $request->amount,
        'amount_removed'     => 0,
        'currency_type'      => $request->currency ?? 'USD',
        'deposit_method'     => $request->paymentMethod,
        'transaction_id'     => $transactionId,
        'network'            => $request->network ?? 'TRC-20',
        'user_wallet_address'=> $request->paymentaccount ?? 'User Wallet',
        'proof_of_payment'   => $proofPath,
        'status'             => 'pending',
        'expires_at'         => now()->addMinutes(15),
        'comment'            => 'Manual deposit submitted from user dashboard deposit page. 15-minute countdown active.',
    ]);

    // FIX (D3): A deposit alone should NOT mark the user as "paid" —
    // they still need to actually buy a package. We only set the
    // first-deposit free-package flag if they have no activation yet.

    if ($user->have_activation_code == null && $have_any_recent_deposits == 0) {
        $user->has_free_package = 'yes';
        $user->save();
    }

    return redirect()->route('user.manual-deposit.waiting', $deposit->id)
        ->with('message', 'Deposit of $'.$request->amount.' submitted. Please wait for admin approval.');









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
  $venture = Adventures::find($request->package);
  if (!$venture) {
      return back()->with('error', 'Invalid UVP package selected.');
  }
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


    $p = Adventures::where('id',$request->uvp_id)->first(); // find a range of bought venture

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
        //
        // FIX: expiration_date and duration are now computed via
        // InvestmentFactory from the actual adventure's duration — never
        // hardcoded to 100/200/600 and never accidentally set to today.
        $create_payable = \App\Services\InvestmentFactory::buildVenture(
            userId:    $username,
            adventure: $p,
            amount:    (float) $request->amount,
            paid:      (float) $request->amount,
            status:    1
        );

        $pay = $p->payments()->save($create_payable);
        $this->calculateEarnings($pay);
        // Skip calculatePackageMetrics() — it has a fatal error
        // (references undefined $fcoin, $shopping, $createdDate).
        // Daily income is computed by the income:calculate cron instead.

        $muser->update(["has_paid_package" => $p->name, "has_free_package" => "no"]);
        return redirect()->route('user.dashboard')
            ->with('message', 'Investment of $'.number_format($request->amount, 2).' activated successfully.');
  }

}
public function blockpayventure(Request $request)
{


  $venture = Adventures::find($request->package);
  if (!$venture) {
      return back()->with('error', 'Invalid UVP package selected.');
  }
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
    // $p = Adventures::where("id",$request->package)->first();
    $p = Adventures::where('min_amount', '<=', $amount)
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
        // FIX: use InvestmentFactory so expiration_date / duration
        // come from the actual adventure's duration field — never today.
        $create_payable = \App\Services\InvestmentFactory::buildVenture(
            userId:    $username,
            adventure: $p,
            amount:    (float) $request->amount,
            paid:      (float) $request->amount,
            status:    1
        );

        $pay = $p->payments()->save($create_payable);
        $this->calculateEarnings($pay);
        // Skip calculatePackageMetrics() — it has undefined vars.

        $muser->update(["has_paid_package" => $p->name, "has_free_package" => "no"]);
        return redirect()->route('user.dashboard')
            ->with('message', 'Investment of $'.number_format($request->amount, 2).' activated successfully.');


  }

  if ($request->option == 'crypto') {
    $value = (float) $request->amount;

    if ($value < 10) {
        return view("user.payment-custom-error", ["amount" => $value]);
    }

    // Plisio removed: create a direct USDT TRC20 package-payment invoice.
    return $this->redirectToDirectPackagePayment('VENTURE', (int) $venture->id, $value, $request->input('network', 'TRC-20'));
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
        // FIX: status codes per migration comment (0=tried, 1=paid well, 2=underpayment, 3=overpayment).
        // The previous code swapped 1 and 2 and missed the equal-payment case (defaulted to 0).
        if ($paymentIntent['amount'] > $value) {
            $status = 2; // Underpayment
        } else if ($paymentIntent['amount'] < $value) {
            $status = 3; // Overpayment
            $overPaid = $value - $paymentIntent['amount'];
        } else {
            $status = 1; // Paid well (exact match)
        }

        // FIX: was using $paymentIntent['username'] (the user's USERNAME
        // string like "john_doe") — should be the user ID. New investments
        // were silently invisible from "My Investments" because the user
        // column didn't match the FK-style lookup by id.
        // FIX: expiration_date was coming from a session value computed
        // BEFORE we knew the actual adventure. Now we look up the adventure
        // and use its duration to compute the real expiration_date.
        $adventure = \App\Models\Adventures::find($paymentIntent['package'] ?? null);

        if ($adventure) {
            $paymodel = \App\Services\InvestmentFactory::buildVenture(
                userId:    $user->id,                    // ← FIX: was username string
                adventure: $adventure,
                amount:    (float) $paymentIntent['amount'],
                paid:      (float) $value,
                status:    $status
            );
            // Apply the overpayment amount computed above
            $paymodel->over_paid = $overPaid;
        } else {
            // Adventure not found — fall back to session data, but normalise
            // status and user id.
            $paymodel = new Paymodel([
                'user'            => $user->id,
                'package'         => $paymentIntent['package'],
                'amount'          => $paymentIntent['amount'],
                'paid'            => $value,
                'over_paid'       => $overPaid,
                'status'          => $status,
                'expiration_date' => $paymentIntent['expiration_date'] ?? null,
                'duration'        => $paymentIntent['duration'] ?? null,
                'category'        => 'VENTURE',
                'category_id'     => 0,
                'is_expired'      => false,
            ]);
        }

        $paymodel->save();

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

        $p = Adventures::where("id",$paymentIntent['package'])->first();
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
      return redirect()->route('user.dashboard')->with('message', 'Activation successful! Your '.$pack.' account is ready. Enjoy unlimited earning opportunities.');

    } else {
      return redirect()->route('user.package')->with('message', 'error while savig');
    }
    }

//   venture purchase success



public function free(Request $request){
    {
      $user = Auth::user();
      if (!$user) {
          return redirect()->route('login');
      }

      // Requirement 1: Free user can't be free user again
      $alreadyFree = ($user->has_free_package === 'yes') || (strtolower(trim((string)$user->has_paid_package)) === 'standard');
      if ($alreadyFree) {
          return redirect()->back()->with('error', 'You have already activated your Free Standard account.');
      }

      // Requirement 2: User who activated UVP or FC VIP can't be free user
      $hasActiveUvp = ($user->highestUvpPackageAmount() > 0)
          || \App\Models\Payment::where('user', $user->id)
              ->where('status', 1)
              ->where('is_expired', false)
              ->where(function ($q) {
                  $q->where('category', 'VENTURE')
                    ->orWhere('category', 'UVP')
                    ->orWhere('payable_type', \App\Models\Adventures::class);
              })
              ->exists();

      $hasActiveFc = ($user->highestFcPackageAmount() > 0)
          || \App\Models\Payment::where('user', $user->id)
              ->where('status', 1)
              ->where('is_expired', false)
              ->where(function ($q) {
                  $q->where('category', 'FC')
                    ->orWhere('payable_type', \App\Models\FCpackage::class);
              })
              ->exists();

      if ($hasActiveUvp || $hasActiveFc || in_array(strtoupper(trim((string)$user->has_paid_package)), ['TEAM_LEADER', 'SUPER_LEADER'])) {
          return redirect()->back()->with('error', 'Users who have activated UVP packages, FC VIP packages, or Team Leader accounts cannot activate a Free Standard account.');
      }

      $activation = $this->generateActivationCode(20);
      $email = $user->email;
      $user->has_paid_package = 'standard';
      $user->has_free_package = 'yes';

      $send = $this->SendCode($email, $activation, 'standard');

      if ($user->save()) {
        return redirect()->route('user.dashboard')->with('message', 'Your standard account has been activated. You can access the dashboard as a free standard user.');
      } else {
        return redirect()->route('user.package')->with('error', 'Error while saving free standard account.');
      }
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
    $highestUvpAmount = $user->highestUvpPackageAmount();
    $highestFcAmount  = $user->highestFcPackageAmount();
    $newAmount = (float) $venture->amount_invest;

    $recent = $user->investments()
                        ->where("is_expired", 0)
                        ->where('status', 1)
                        ->orderBy("created_at", 'desc')
                        ->first();
    $currentBalance = $this->MyDepositBalance();

    // Detect FC VIP flow: `package_type == FC` (new form) OR legacy `venture == FC`.
    $isFcFlow = ($venture->package_type === 'FC') || ($venture->venture === 'FC');

    $fcPackage = null;
    if ($isFcFlow) {
        $fcId = $venture->package_id ?? $venture->package ?? null;
        $fcPackage = FCpackage::find($fcId);
        if (!$fcPackage) {
            return back()->with('error', 'Invalid FC VIP package selected.');
        }
        // Override amount_invest with the fixed package price for FC.
        $newAmount = (float) $fcPackage->price;
        $venture->merge(['amount_invest' => $newAmount]);

        // Independent FC tier checks (do NOT compare with UVP history):
        //   • cannot downgrade (new < highest)
        //   • cannot re-buy the same tier (new == highest already owned)
        if ($highestFcAmount > 0) {
            if ($newAmount < $highestFcAmount) {
                return view("user.confirm-package-payments-error",[
                    "amount"=>$newAmount,
                    "venture"=>null,
                    "currentBalance"=>$currentBalance,
                    "requiredAmount"=>max(0, $newAmount - $currentBalance),
                    "status"=>'i',
                    "recent"=>(object)['paid' => $highestFcAmount],
                    "error_message" => "FC VIP Package Purchase Error: You cannot purchase an FC VIP package ($" . number_format($newAmount, 2) . ") below your highest previously purchased FC VIP package amount ($" . number_format($highestFcAmount, 2) . "). FC VIP packages are independent from UVP packages; however, downgrading within the FC VIP product line is not permitted."
                ]);
            }
            if (abs($newAmount - $highestFcAmount) < 0.01) {
                return view("user.confirm-package-payments-error",[
                    "amount"=>$newAmount,
                    "venture"=>null,
                    "currentBalance"=>$currentBalance,
                    "requiredAmount"=>0,
                    "status"=>'i',
                    "recent"=>(object)['paid' => $highestFcAmount],
                    "error_message" => "FC VIP Package Purchase Error: You already own an FC VIP package at $" . number_format($highestFcAmount, 2) . " (\"" . $fcPackage->name . "\"). Duplicate purchases of the same FC tier are not allowed. Please choose a higher FC VIP tier to upgrade."
                ]);
            }
        }
    }

    $adventure = (!$isFcFlow && $venture->venture && $venture->venture !== 'FC' && is_numeric($venture->venture))
        ? Adventures::where("id", $venture->venture)->first()
        : null;
    $requiredAmount = $currentBalance - $newAmount;
    $status = ($requiredAmount < 0) ? 'i':'s';
    if($requiredAmount < 0){
        $requiredAmount = -($requiredAmount);
    }

    // Only enforce UVP tier restrictions for UVP (not for FC).
    if (!$isFcFlow && $highestUvpAmount > 0 && $newAmount < $highestUvpAmount) {
        return view("user.confirm-package-payments-error",[
            "amount"=>$newAmount,
            "venture"=>$adventure,
            "currentBalance"=>$currentBalance,
            "requiredAmount"=>$requiredAmount,
            "status"=>$status,
            "recent"=>(object)['paid' => $highestUvpAmount],
            "error_message" => "UVP Package Purchase Error: You cannot purchase a UVP package ($" . number_format($newAmount, 2) . ") below your highest previously purchased UVP package amount ($" . number_format($highestUvpAmount, 2) . "). Please enter an investment amount of $" . number_format($highestUvpAmount, 2) . " or higher."
        ]);
    }

    if(!$isFcFlow && $recent && $recent->paid >= $venture->amount_invest  && $recent->category == 'VENTURE' ){
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

        if($isFcFlow){
            return view("user.confirm-package-payments",[
                "amount"=>$newAmount,
                "package"=>"FC",
                "routes"=>'fc',
                "name"=>$fcPackage->name,
                'id'=>$fcPackage->id,
                "currentBalance"=>$currentBalance,
                "requiredAmount"=>number_format($requiredAmount, 2),
                "status"=>$status
            ]);
        }

        $adventure = Adventures::where("id",$venture->venture)->first();
       
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
        // Auto-payment fallback (used when DEPOSIT balance is insufficient).
        if ($isFcFlow && $fcPackage) {
            if ($msg = $this->validateFcTierPurchase($user, $fcPackage)) {
                return back()->with('error', $msg);
            }
            return $this->redirectToDirectPackagePayment('FC', (int) $fcPackage->id, (float) $fcPackage->price, $request->input('network', 'TRC-20'));
        }
        if ($adventure) {
            return $this->redirectToDirectPackagePayment('VENTURE', (int) $adventure->id, (float) $venture->amount_invest, $request->input('network', 'TRC-20'));
        }
        return back()->with('error', 'Invalid package selected.');
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

    // Resolve the FC VIP package by id (preferred) or name (legacy fallback).
    $p = null;
    if ($request->filled('package_id')) {
        $p = FCpackage::find($request->package_id);
    }
    if (!$p && $request->filled('pack')) {
        $p = FCpackage::where("name", $request->pack)->first();
    }
    if (!$p) {
        return back()->with('error', 'Invalid FC VIP package selected.');
    }

    $amount = (float) ($request->amount ?? $p->price);
    if ($amount <= 0) {
        $amount = (float) $p->price;
    }

    // Check sufficient deposit balance first.
    $currentBalance = (float) $this->MyDepositBalance();
    if ($currentBalance < $amount) {
        return back()->with('error', 'Insufficient deposit balance. Please use the automatic USDT TRC-20 payment option instead.');
    }

    // Independent FC tier check (never compare with UVP history):
    //   • cannot downgrade
    //   • cannot repurchase the same tier
    if ($msg = $this->validateFcTierPurchase($user, $p)) {
        return back()->with('error', $msg);
    }

    // Wrap in a transaction so concurrent clicks can't double-create the same FC tier.
    try {
        return DB::transaction(function () use ($user, $p, $amount) {
            // Re-read inside the transaction (lock the user row) to close the race window.
            $userLocked = User::whereKey($user->id)->lockForUpdate()->first();
            if ($userLocked->hasFcPackageAtPrice((float) $p->price)) {
                throw new \RuntimeException('You already own this FC VIP tier ("' . $p->name . '"). Duplicate purchases are not allowed.');
            }
            $highestFc = round((float) $userLocked->highestFcPackageAmount(), 2);
            if ($highestFc > 0 && (float) $p->price < $highestFc) {
                throw new \RuntimeException('FC VIP Package Purchase Error: You cannot purchase an FC VIP package below your highest FC tier.');
            }

            return $this->completeFcDepositActivation($userLocked, $p, $amount);
        });
    } catch (\Throwable $e) {
        return back()->with('error', $e->getMessage());
    }
}

/**
 * Inside a transaction, finalize an FC VIP deposit-funded activation
 * (create payment, debit deposit, write transaction, fire referrals/leadership).
 */
private function completeFcDepositActivation(User $user, FCpackage $p, float $amount)
{
    $startDate = Carbon::now();

    // §FC-INDEPENDENT: Each FC VIP purchase is an INDEPENDENT record
    // with its own referral credits/leadership bonuses. FC VIP is a
    // PERMANENT membership — no expiration_date, no duration, never expires.
    $create_payable = new Paymodel([
        'user'            => $user->id,
        'package'         => $p->name,
        'amount'          => (float) $p->price,
        'paid'            => $amount,
        'over_paid'       => 0,
        'status'          => 1,
        'expiration_date' => null,
        'duration'        => null,
        'category'        => 'FC',
        'category_id'     => 0,
        'is_expired'      => false,
        'payable_id'      => $p->id,
        'payable_type'    => FCpackage::class,
    ]);

    $pay = $p->payments()->save($create_payable);
    $this->calculateEarnings($pay);

    // §FC-TOKENS-12M: credit locked tokens on FC purchase (12 equal monthly releases).
    try {
        \App\Services\FcpTokenService::onFcPurchased($user, $p, $pay);
    } catch (\Throwable $e) {
        \Log::warning('FCP token credit (deposit-funded) failed for payment #' . $pay->id . ': ' . $e->getMessage());
    }

    // FC Leadership bonus engine: +100 VB per direct FC referral plus any
    // newly-crossed milestone tier rewards (Monday cashout pipeline).
    try {
        \App\Services\FcLeadershipService::onFcPaymentConfirmed($pay);
    } catch (\Throwable $e) {
        \Log::warning('FC leadership credit (deposit-funded) failed for payment #' . $pay->id . ': ' . $e->getMessage());
    }

    // FC Streamline Ranks: opportunistically evaluate rank progress for
    // buyer + direct referrer after every FC payment (cron hourly is backstop).
    try {
        \App\Services\FcStreamlineRankService::evaluate($user);
        $referrerId = (int) ($user->referee_id ?? 0);
        if ($referrerId > 0) {
            $ref = User::find($referrerId);
            if ($ref) {
                \App\Services\FcStreamlineRankService::evaluate($ref);
            }
        }
    } catch (\Throwable $e) {
        \Log::warning('FC streamline rank evaluation (deposit-funded) failed after payment #' . $pay->id . ': ' . $e->getMessage());
    }

    $lastDeposit = Deposits::where('user_id', $user->id)->latest()->first();
    Deposits::create([
        'user_id'             => $user->id,
        'amount_deposited'    => 0,
        'amount_removed'      => $amount,
        'currency_type'       => 'DOLLAR',
        'deposit_method'      => 'FROM_DEPOSITS_FC',
        'status'              => 'used',
        'transaction_id'      => Deposits::generateTransactionNo(),
        'user_wallet_address' => $lastDeposit->user_wallet_address ?? null,
        'network'             => $lastDeposit->network ?? 'TRC-20',
    ]);

    // Deduct DEPOSIT bucket from ChartAccount (matches how direct crypto
    // auto-payment consumes deposit balance in DirectPackagePaymentService).
    $existingDeposit = (float) ChartAccount::where('user_id', $user->id)->where('acc_type', 'DEPOSIT')->sum('amount');
    ChartAccount::updateOrCreate(
        ['user_id' => $user->id, 'acc_type' => 'DEPOSIT'],
        ['amount'  => max(0, $existingDeposit - $amount)]
    );

    $transactionNo = Transaction::generateTransactionNo();
    Transaction::create([
        'user_id'             => $user->id,
        'transaction_no'      => $transactionNo,
        'transaction_type'    => 'SUBSCRIPTION',
        'receiver_id'         => 0,
        'transaction_details' => json_encode([
            'product'            => $p->name,
            'user'               => $user->email,
            'plan'               => 'FC VIP (Lifetime)',
            'start_date'         => $startDate,
            'end_date'           => null,
            'package'            => $p->name,
            'price'              => (float) $p->price,
            'token'              => $p->default_token,
            'current_price'      => (float) $p->price,
            'poolcapital'        => 0,
            'current_poolcapital'=> 0,
            'LP'                 => 0,
            'current_LP'         => 0,
            'period'             => 'Lifetime',
            'revenue_earned'     => 0,
            'revenue_type'       => 'permanent',
            'status'             => 'success',
            'purchase_date'      => $startDate,
            'username'           => $user->name,
            'payment_method'     => 'FROM_DEPOSITS_FC',
            'payment_id'         => $pay->id,
            'amount_paid'        => $amount,
        ])
    ]);

    $this->updateHasPaidPackage($p->name);

    return redirect()->route("user.dashboard")
        ->with('message', 'FC VIP package "' . $p->name . '" ($' . number_format($amount, 2) . ') activated successfully from your deposit balance.');
}
private function updateHasPaidPackage($packageName)
{
    Auth::user()->update([
        'has_free_package'=>'no',
        'has_paid_package' => $packageName
    ]);
}


 }
