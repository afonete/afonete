<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Balance as balance1;
use App\Models\Payments;
use App\Models\Wallet;
use App\Models\ChartAccount;
use GuzzleHttp\Client;
use App\Models\Activations;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivationEmail;
use Plisio\PlisioSdkLaravel\Payment;
use App\Helpers\PlisioHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\withdrawals as WithdrawalModel;
use App\Models\Transaction;


class Balance extends Controller{

    //
    public function Receive(Request $request){
        $user = Auth::User();

        return view("user.UserReceive",["address"=>0.09]);
    }

    public function UserWithdrawal(){
        $user = Auth::User();
        $cashout = $user->ChartAccount()->where("acc_type","CASHOUT")->sum("amount");


        $data = [
            "Focoin"=>0.00,
            "Bitcoin"=>0.00,
            "Bnb"=>0.00,
            "Ethereum"=>0.00,   
            "USDT TRON"=>0.00

        ];



        return view("user.UserWithdrawal",["data"=>$data,"cashout"=>$cashout]);
    }
    public function index(){
        $user = Auth::User();
        $CASHOUT = $user->ChartAccount()->where("acc_type","CASHOUT")->sum("amount");
        $TRADING = $user->ChartAccount()->where("acc_type","TRADING")->sum("amount");
        // $ = $user->ChartAccount()->where("acc_type","TRADING")->orderby("earned_at","desc");
        $total = $CASHOUT + $TRADING;
        $todayEarning = $user->earnings()->orderBy("created_at","desc")->first();
        $totalEarning = $user->earnings()->sum("amount");
        $credit = $user->have_activation_code->myCredit;
        $fomo = 0;
        $incomeventure = 0;
        $freecoin = 0;
        $initial = $user->deposits()->sum("amount_deposited");
        $used = $user->deposits()->sum("amount_removed");
        $deposit = $initial - $used;


        return view('user.balance.balance',[
            "trading"=>$TRADING,
            "cashout"=>$CASHOUT,
            "total"=>$total,
            "todayEarning"=>$todayEarning->amount,
            "totalEarning"=>$totalEarning,
            "credit"=>$credit ?? 0,
            "fomo"=>$fomo,
            "incomeventure"=>$incomeventure,
            "freecoin"=>$freecoin,
            'deposit'=>$deposit

        ]);
    }


public function withdraw_money(Request $request)
    {
        $user = Auth::User();
        $settings = \App\Models\WithdrawalSetting::current();

        // ── Validate input ──
        // FIX (W5): API key from config; (W1): min from settings; (W10): TRC-20 format
        $request->validate([
            'amount'  => 'required|numeric|min:' . $settings->min_amount,
            'address' => [
                'required','string',
                // FIX (W10): TRC-20 addresses start with T and are ~34 chars
                function ($attr, $value, $fail) use ($settings) {
                    if (!$settings->validate_trc20_format) return;
                    $addr = trim($value);
                    if (!preg_match('/^T[a-zA-Z0-9]{' . ((int)$settings->default_trc20_min_length - 1) . ',}$/', $addr)) {
                        $fail("Invalid USDT TRC-20 wallet address format. Should start with 'T' and be ~" . $settings->default_trc20_min_length . " chars.");
                    }
                },
            ],
        ]);

        $amount  = (float) $request->amount;
        $address = $request->address;

        // ── FIX (W4): Enforce per-transaction + daily + monthly limits ──
        if ($amount > (float) $settings->max_per_transaction) {
            return back()->with('error', 'Per-transaction maximum is $' . number_format($settings->max_per_transaction, 2));
        }
        $todaySpent = \App\Models\WithdrawalSetting::withdrawnToday($user->id);
        if ($settings->daily_limit && ($todaySpent + $amount) > (float) $settings->daily_limit) {
            return back()->with('error', 'Daily limit is $' . number_format($settings->daily_limit, 2) . '. You have already withdrawn $' . number_format($todaySpent, 2) . ' today.');
        }
        $monthSpent = \App\Models\WithdrawalSetting::withdrawnThisMonth($user->id);
        if ($settings->monthly_limit && ($monthSpent + $amount) > (float) $settings->monthly_limit) {
            return back()->with('error', 'Monthly limit is $' . number_format($settings->monthly_limit, 2) . '. You have already withdrawn $' . number_format($monthSpent, 2) . ' this month.');
        }

        // ── Check CASHOUT balance ──
        $cashoutBalance = $user->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount');

        if ($cashoutBalance < $amount) {
            return redirect()->route('user.dashboard.withdraw')
                ->with('error', 'Insufficient balance. You have $' . number_format($cashoutBalance, 2) . ' available.');
        }

        $trxNo = Transaction::generateTransactionNo();
        $apiKey = config('services.plisio.api_key');

        // Hold the amount (deduct now, refund if rejected)
        $newBalance = $cashoutBalance - $amount;
        $user->ChartAccount()->where('acc_type', 'CASHOUT')->update(['amount' => $newBalance]);

        // ── Call Plisio API ──
        $client   = new Client();
        $apiUrl   = 'https://plisio.net/api/v1/operations/withdraw';
        $gasFee   = 0.0;
        $netAmount = $amount;
        $plisioId = null;
        $plisioStatus = 'pending';
        $errorMessage = null;

        try {
            $response = $client->get($apiUrl, [
                'query' => [
                    'currency' => 'USDT',
                    'type'     => 'cash_out',
                    'to'       => $address,
                    'amount'   => $amount,
                    'api_key'  => $apiKey,
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            if (($responseData['status'] ?? '') !== 'success') {
                $errorMessage = $responseData['data']['message'] ?? 'Plisio withdrawal failed.';
            } else {
                $plisioId = $responseData['data']['txn_id'] ?? null;
                $gasFee   = (float) ($responseData['data']['fee'] ?? 0);
                $netAmount = max(0, $amount - $gasFee);
                // FIX (W3): Plisio returns 'pending' or 'completed' — we map accordingly
                $plisioStatus = match ($responseData['data']['status'] ?? 'pending') {
                    'completed', 'success' => 'processing', // wait for on-chain confirmation
                    'failed', 'error'      => 'failed',
                    default                 => 'processing',
                };
            }
        } catch (\Exception $e) {
            $errorMessage = 'Withdrawal could not be processed: ' . $e->getMessage();
        }

        if ($errorMessage) {
            // Refund CASHOUT
            $user->ChartAccount()->where('acc_type', 'CASHOUT')->update(['amount' => $cashoutBalance]);
            return redirect()->route('user.dashboard.withdraw')->with('error', $errorMessage);
        }

        // ── Record withdrawal ──
        WithdrawalModel::create([
            'user_id'         => $user->id,
            'amount'          => $amount,
            'gas_fee'         => $gasFee,
            'net_amount'      => $netAmount,
            'wallet_address'  => $address,
            'currency'        => 'USDT',
            'transaction_no'  => $trxNo,
            'plisio_txn_id'   => $plisioId,
            'status'          => $plisioStatus, // processing, not "completed"
        ]);

        // ── Record transaction log ──
        Transaction::create([
            'user_id'             => $user->id,
            'transaction_no'      => $trxNo,
            'transaction_type'    => 'WITHDRAWAL',
            'receiver_id'         => 0,
            'transaction_details' => json_encode([
                'amount'         => $amount,
                'gas_fee'        => $gasFee,
                'net_amount'     => $netAmount,
                'currency'       => 'USDT',
                'wallet_address' => $address,
                'plisio_txn_id'  => $plisioId,
                'date'           => now()->toDateTimeString(),
                'status'         => $plisioStatus,
                'username'       => $user->name,
            ]),
        ]);

        $msg = 'Withdrawal of $' . number_format($amount, 2) . ' submitted to Plisio. '
             . ($gasFee > 0 ? "Gas fee: \${$gasFee}. Net you'll receive: \$" . number_format($netAmount, 2) . '. ' : '')
             . 'Reference: ' . $trxNo;

        return redirect()->route('user.dashboard.withdraw')->with('success', $msg);
    }
     public function withdraw (){
        $user = Auth::User();
        $CASHOUT = $user->ChartAccount()->where("acc_type","CASHOUT")->sum("amount");

        return view('user.balance.withdraw',["availlableBalance"=>$CASHOUT]);
    }

     public function deposit(){
        $user = Auth::user();
        $wallets = \App\Models\DepositWallet::active()->get();
        $deposits = $user->deposits()->latest()->take(10)->get();
        $cashout  = $user->ChartAccount()->where('acc_type','CASHOUT')->sum('amount');
        return view('user.balance.deposit', compact('wallets','deposits','cashout'));
    }

    /**
     * User's full deposit history with pagination + filters.
     */
    public function depositHistory(Request $request)
    {
        $user = Auth::user();
        $query = $user->deposits()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $deposits = $query->paginate(20)->withQueryString();
        $totals = [
            'pending'   => (float) $user->deposits()->where('status','pending')->sum('amount_deposited'),
            'approved'  => (float) $user->deposits()->where('status','approved')->sum('amount_deposited'),
            'rejected'  => (float) $user->deposits()->where('status','rejected')->sum('amount_deposited'),
            'used'      => (float) $user->deposits()->where('status','used')->sum('amount_removed'),
            'available' => (float) $user->deposits()->where('status','approved')->sum('amount_deposited')
                       - (float) $user->deposits()->where('status','used')->sum('amount_removed'),
        ];

        return view('user.balance.deposit-history', compact('deposits','totals'));
    }
     public function wallet(Request $request)
     {
           $user = Auth::user();
           $username = $user->user;
           $wallet=$request->wallet;
          try{
               Wallet::create([
                'user'=>$username,
                'wallet'=>$wallet,
           ]);
           return view('user.wallet')->with('success','Your wallet address have been saved well,');
          }
          catch(Exception $e)
          {
               return view('user.wallet')->with('failed','failed to save  wallet address ,');
          }
     }

     public function api(Request $request){
      $user = Auth::user();
    $userId = $user->id;
    $email =$user->email;
    if($request->amount<10)
    {
        return view('user.balance.deposit')->with('ammount','less amount!! minimum ammount is $10');
    }

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
                'email' => $email,
                'order_name' => 'account deposit',
                // Use route() helper instead of hardcoded domain URLs (fix D2)
                'callback_url'        => route('user.deposit.status'),
                'success_callback_url'=> route('user.deposit.success'),
                'fail_callback_url'   => route('user.deposit.fail'),
                'expire_min'=>15,
                'api_key' => config('services.plisio.api_key'), // from .env, not hardcoded (fix D6)
            ],
        ]);

        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        $responseData = json_decode($body, true);

        // Get the necessary data from the response
        $txnId = $responseData['data']['txn_id'];
        $invoiceUrl = $responseData['data']['invoice_url'];

        // Redirect the user to the invoice URL
        return redirect::away($invoiceUrl);
         }

     public function status(Request $request)
{
    // FIX (D4+D5): Actually verify the callback AND process the deposit.
    if (!verifyCallbackData()) {
        \Log::warning('Plisio deposit callback failed verification', $request->all());
        return view('user.balance.deposit', ['status' => 'error', 'message' => 'Invalid callback data']);
    }

    $user = Auth::user();
    if (!$user) {
        return view('user.balance.deposit', ['status' => 'error', 'message' => 'User not authenticated']);
    }

    // Pull Plisio fields
    $txnId   = $request->input('txn_id');
    $amount  = (float) $request->input('amount', 0);
    $status  = $request->input('status'); // 'completed' | 'pending' | 'failed' | 'error'

    if ($amount <= 0) {
        return view('user.balance.deposit', ['status' => 'error', 'message' => 'Invalid amount']);
    }

    // Idempotent: if we already have a deposit with this txn_id, do nothing
    $existing = Deposits::where('transaction_id', $txnId)->first();
    if ($existing) {
        return view('user.balance.deposit', ['status' => 'success deposit']);
    }

    $depositStatus = match (true) {
        in_array($status, ['completed', 'success']) => 'approved',
        in_array($status, ['failed', 'error', 'cancelled']) => 'rejected',
        default => 'pending',
    };

    $deposit = Deposits::create([
        'user_id'           => $user->id,
        'amount_deposited'  => $amount,
        'amount_removed'    => 0,
        'currency_type'     => 'USDT',
        'deposit_method'    => 'PLISIO',
        'transaction_id'    => $txnId,
        'network'           => $request->input('source_currency', 'USDT'),
        'user_wallet_address'=> $request->input('sender'),
        'status'            => $depositStatus,
    ]);

    // Only credit CASHOUT on confirmed success
    if ($depositStatus === 'approved') {
        $existingCashout = $user->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount');
        \App\Models\ChartAccount::updateOrCreate(
            ['user_id' => $user->id, 'acc_type' => 'CASHOUT'],
            ['amount'  => $existingCashout + $amount]
        );

        \App\Models\Transaction::create([
            'user_id'             => $user->id,
            'transaction_no'      => $txnId,
            'transaction_type'    => 'DEPOSIT',
            'receiver_id'         => 0,
            'transaction_details' => json_encode([
                'amount'    => $amount,
                'method'    => 'PLISIO',
                'currency'  => 'USDT',
                'plisio_id' => $txnId,
                'date'      => now()->toDateTimeString(),
                'status'    => 'approved',
                'username'  => $user->name,
            ]),
        ]);
    }

    return view('user.balance.deposit', ['status' => $depositStatus === 'approved' ? 'success deposit' : 'deposit ' . $depositStatus]);
}
 public function success(Request $request)
  {
    // FIX (D4): If Plisio redirects user here after payment, also credit CASHOUT
    // in case the server-side callback never fires. Idempotent.
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('user.dashboard.deposit')
            ->with('error', 'Please log in to complete your deposit.');
    }

    $txnId  = $request->input('txn_id', $request->input('id'));
    $amount = (float) $request->input('amount', 0);

    // If no txn_id provided, fall back to a generic legacy flow but still credit CASHOUT
    if (!$txnId && $amount <= 0) {
        return redirect()->route('user.dashboard.deposit')
            ->with('message', 'Thank you! Your deposit is being processed.');
    }

    // Idempotent: if a deposit with this txn_id already exists, just redirect
    $existing = Deposits::where('transaction_id', $txnId)->first();

    if (!$existing && $amount > 0) {
        Deposits::create([
            'user_id'           => $user->id,
            'amount_deposited'  => $amount,
            'amount_removed'    => 0,
            'currency_type'     => 'USDT',
            'deposit_method'    => 'PLISIO',
            'transaction_id'    => $txnId,
            'status'            => 'approved',
        ]);

        // Credit CASHOUT
        $existingCashout = $user->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount');
        \App\Models\ChartAccount::updateOrCreate(
            ['user_id' => $user->id, 'acc_type' => 'CASHOUT'],
            ['amount'  => $existingCashout + $amount]
        );

        \App\Models\Transaction::create([
            'user_id'             => $user->id,
            'transaction_no'      => $txnId,
            'transaction_type'    => 'DEPOSIT',
            'receiver_id'         => 0,
            'transaction_details' => json_encode([
                'amount'    => $amount,
                'method'    => 'PLISIO',
                'currency'  => 'USDT',
                'plisio_id' => $txnId,
                'date'      => now()->toDateTimeString(),
                'status'    => 'approved',
                'username'  => $user->name,
                'source'    => 'plisio_success_callback',
            ]),
        ]);
    }

    return redirect()->route('user.dashboard.deposit')
        ->with('message', 'Thank you! Your deposit of $' . number_format($amount, 2) . ' has been credited.');
  }
 public function error()
{
    return view('user.balance.deposit')->with('p_failed','Payment failed, Retry again');
}

    /**
     * User submits a manual withdrawal request.
     * Balance is held (deducted immediately), status = pending.
     * Admin approves → completed, or rejects → balance refunded.
     */
    public function requestManualWithdrawal(Request $request)
    {
        $user = Auth::user();
        $settings = \App\Models\WithdrawalSetting::current();

        $request->validate([
            'amount'  => 'required|numeric|min:' . $settings->min_amount,
            'address' => [
                'required','string',
                function ($attr, $value, $fail) use ($settings) {
                    if (!$settings->validate_trc20_format) return;
                    $addr = trim($value);
                    if (!preg_match('/^T[a-zA-Z0-9]{' . ((int)$settings->default_trc20_min_length - 1) . ',}$/', $addr)) {
                        $fail("Invalid USDT TRC-20 wallet address format. Should start with 'T' and be ~" . $settings->default_trc20_min_length . " chars.");
                    }
                },
            ],
        ]);

        $amount  = (float) $request->amount;
        $address = $request->address;

        // ── FIX (W4): Enforce limits ──
        if ($amount > (float) $settings->max_per_transaction) {
            return back()->with('error', 'Per-transaction maximum is $' . number_format($settings->max_per_transaction, 2));
        }
        $todaySpent = \App\Models\WithdrawalSetting::withdrawnToday($user->id);
        if ($settings->daily_limit && ($todaySpent + $amount) > (float) $settings->daily_limit) {
            return back()->with('error', 'Daily limit is $' . number_format($settings->daily_limit, 2) . '. You have already withdrawn $' . number_format($todaySpent, 2) . ' today.');
        }
        $monthSpent = \App\Models\WithdrawalSetting::withdrawnThisMonth($user->id);
        if ($settings->monthly_limit && ($monthSpent + $amount) > (float) $settings->monthly_limit) {
            return back()->with('error', 'Monthly limit is $' . number_format($settings->monthly_limit, 2) . '. You have already withdrawn $' . number_format($monthSpent, 2) . ' this month.');
        }

        // ── Check CASHOUT balance ──
        $cashoutBalance = $user->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount');

        if ($cashoutBalance < $amount) {
            return redirect()->route('user.dashboard.withdraw')
                ->with('error', 'Insufficient balance. You have $' . number_format($cashoutBalance, 2) . ' available.');
        }

        // ── Hold the amount (deduct now, refund if rejected) ──
        $user->ChartAccount()->where('acc_type', 'CASHOUT')
             ->update(['amount' => $cashoutBalance - $amount]);

        $trxNo = Transaction::generateTransactionNo();

        // ── Create pending withdrawal record ──
        WithdrawalModel::create([
            'user_id'        => $user->id,
            'amount'         => $amount,
            'wallet_address' => $address,
            'currency'       => 'USDT',
            'transaction_no' => $trxNo,
            'status'         => 'pending',
        ]);

        Transaction::create([
            'user_id'             => $user->id,
            'transaction_no'      => $trxNo,
            'transaction_type'    => 'WITHDRAWAL_REQUEST',
            'receiver_id'         => 0,
            'transaction_details' => json_encode([
                'amount'         => $amount,
                'currency'       => 'USDT',
                'wallet_address' => $address,
                'date'           => now()->toDateTimeString(),
                'status'         => 'pending',
                'username'       => $user->name,
                'note'           => 'Awaiting admin approval',
            ]),
        ]);

        // FIX (W6): Email admin about pending request
        try {
            $admins = \App\Models\User::where('utype','ADM')->get();
            foreach ($admins as $admin) {
                \Mail::to($admin->email)->send(new \App\Mail\WithdrawalRequested($user, $amount, $address, $trxNo));
            }
        } catch (\Exception $e) {
            \Log::warning('Could not send withdrawal request email: ' . $e->getMessage());
        }

        return redirect()->route('user.dashboard.withdraw')
            ->with('success', 'Withdrawal request of $' . number_format($amount, 2) . ' submitted. Reference: ' . $trxNo . '. Awaiting admin approval.');
    }

    /**
     * Show the user their own withdrawal history
     */
    public function withdrawalHistory()
    {
        $user        = Auth::user();
        $withdrawals = WithdrawalModel::where('user_id', $user->id)->latest()->paginate(20);
        $cashout     = $user->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount');

        return view('user.balance.withdraw', [
            'availlableBalance' => $cashout,
            'withdrawals'       => $withdrawals,
        ]);
    }
}
