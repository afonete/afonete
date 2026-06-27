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
use App\Models\Deposits;


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
        $PAYOUT  = $user->ChartAccount()->where("acc_type","PAYOUT")->sum("amount");

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
            "payout" => $PAYOUT,
            "total"=>$total,
            "todayEarning"=>$todayEarning ? $todayEarning->amount : 0,
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
            'transaction_password' => ['required', $this->transactionPasswordRule($user)],
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

        
        if ($message = $this->transactionPasswordError($request, $user)) {
            return back()->withInput()->with('error', $message);
        }

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
    
    // ════════════════════════════════════════════════════════════
    // METHOD 1: withdraw()  (line 225)
    // ════════════════════════════════════════════════════════════
    public function withdraw ()
    {
        $user   = Auth::User();
        $CASHOUT = $user->ChartAccount()->where("acc_type","CASHOUT")->sum("amount");
        $settings = \App\Models\WithdrawalSetting::current();

        // Pre-group active crypto wallets by currency for the picker
        $cryptoByCurrency = \App\Models\DepositWallet::activeOfType('crypto')
            ->groupBy('currency');

        return view('user.balance.withdraw', [
            'availlableBalance' => $CASHOUT,
            'settings'          => $settings,
            'cryptoByCurrency'  => $cryptoByCurrency,
            'advcashActive'     => \App\Models\DepositWallet::activeOfType('advcash'),
            'perfectMoneyActive'=> \App\Models\DepositWallet::activeOfType('perfect_money'),
        ]);
    }


    /**
     * This is the deposit page you want at /user/dashboard/deposit
     * It uses the beautiful tabbed view with QR codes, copy buttons,
     * and admin-configured wallets (Crypto / Advcash / Perfect Money / Auto).
     */
    public function deposit()
    {
        $user = Auth::user();

        $cryptoWallets       = \App\Models\DepositWallet::activeOfType('crypto');
        $advcashWallets      = \App\Models\DepositWallet::activeOfType('advcash');
        $perfectMoneyWallets = \App\Models\DepositWallet::activeOfType('perfect_money');

        $deposits = $user->deposits()->latest()->take(10)->get();

        $minDeposit = (float) (\App\Models\WithdrawalSetting::current()->min_deposit_amount ?? 10);

        // Phase 2: unique user deposit address for automatic USDT TRC20 crediting.
        // If the signer is not running/configured yet, we fail gracefully and keep manual methods visible.
        $directDepositAddress = null;
        try {
            $directDepositAddress = app(\App\Services\TronBlockchainService::class)->getOrCreateDepositAddressForUser($user);
        } catch (\Throwable $e) {
            \Log::warning('Direct deposit address unavailable: ' . $e->getMessage(), ['user_id' => $user->id]);
        }

        return view('user.balance.deposit', compact(
            'cryptoWallets',
            'advcashWallets',
            'perfectMoneyWallets',
            'directDepositAddress',
            'deposits',
            'minDeposit'
        ));
    }

    /**
     * Full deposit history page (linked from advanced deposit page)
     * Route: GET /user/deposits  → name: user.deposits.history
     */
    public function depositHistory(Request $request)
    {
        $user = Auth::user();

        $query = $user->deposits()->latest();

        if ($request->filled('status')) {
            $status = $request->status;
            $query->where('status', $status);
        }

        $deposits = $query->paginate(20);

        // Calculate summary totals for the cards
        $all = $user->deposits;

        $approvedSum   = $all->where('status', 'approved')->sum('amount_deposited');
        $usedSum       = $all->where('status', 'used')->sum('amount_removed');

        $totals = [
            'pending'   => $all->where('status', 'pending')->sum('amount_deposited'),
            'approved'  => $approvedSum,
            'used'      => $usedSum,
            'available' => $approvedSum - $usedSum,
        ];

        return view('user.balance.deposit-history', compact('deposits', 'totals'));
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

    // ==================== DEPOSIT LOGIC ====================

    public function api(Request $request){
        $user = Auth::user();
        $amount = (float) $request->amount;

        if($amount < 10){
            return back()->with('error', 'Minimum deposit is $10');
        }

        $transactionId = Deposits::generateTransactionNo();

        Deposits::create([
            'user_id'           => $user->id,
            'amount_deposited'  => $amount,
            'amount_removed'    => 0,
            'currency_type'     => $request->currency ?? 'USDT',
            'deposit_method'    => $request->paymentMethod ?? 'MANUAL',
            'transaction_id'    => $transactionId,
            'status'            => 'pending',
        ]);

        return back()->with('message', 'Deposit request submitted. Awaiting approval.');
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

    // Only credit DEPOSIT on confirmed success. Deposits are not withdrawable.
    if ($depositStatus === 'approved') {
        $existingDeposit = $user->ChartAccount()->where('acc_type', 'DEPOSIT')->sum('amount');
        \App\Models\ChartAccount::updateOrCreate(
            ['user_id' => $user->id, 'acc_type' => 'DEPOSIT'],
            ['amount'  => $existingDeposit + $amount]
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
                'account'   => 'DEPOSIT',
                'username'  => $user->name,
            ]),
        ]);
    }

    return view('user.balance.deposit', ['status' => $depositStatus === 'approved' ? 'success deposit' : 'deposit ' . $depositStatus]);
}
 public function success(Request $request)
  {
    // FIX (D4): If Plisio redirects user here after payment, also credit DEPOSIT
    // in case the server-side callback never fires. Idempotent.
    // Deposits are not withdrawable; CASHOUT is reserved for withdrawable funds.
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('user.dashboard.deposit')
            ->with('error', 'Please log in to complete your deposit.');
    }

    $txnId  = $request->input('txn_id', $request->input('id'));
    $amount = (float) $request->input('amount', 0);

    // If no txn_id provided, fall back to a generic legacy flow without crediting
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

        // Credit DEPOSIT, not CASHOUT. Deposits are package-spendable only.
        $existingDeposit = $user->ChartAccount()->where('acc_type', 'DEPOSIT')->sum('amount');
        \App\Models\ChartAccount::updateOrCreate(
            ['user_id' => $user->id, 'acc_type' => 'DEPOSIT'],
            ['amount'  => $existingDeposit + $amount]
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
                'account'   => 'DEPOSIT',
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

    // ════════════════════════════════════════════════════════════
    // METHOD 2: requestManualWithdrawal()  (line 486)
    // ════════════════════════════════════════════════════════════
    public function requestManualWithdrawal(Request $request)
    {
        $user = Auth::user();
        $settings = \App\Models\WithdrawalSetting::current();
        $minAmount = (float) $settings->min_amount;

        $request->validate([
            'amount'  => "required|numeric|min:{$minAmount}|max:" . (float) $settings->max_per_transaction,
            'transaction_password' => ['required', $this->transactionPasswordRule($user)],
            'method'  => 'required|string|in:crypto,advcash,perfect_money',
            'network' => 'nullable|string|max:30',
            'currency'=> 'required|string|max:10',
            'address' => 'required|string|max:255',
        ], [
            'amount.min'  => "Minimum withdrawal is \$" . number_format($minAmount, 2) . ".",
            'amount.max'  => "Per-transaction maximum is \$" . number_format((float) $settings->max_per_transaction, 2) . ".",
            'method.in'   => 'Invalid withdrawal method.',
            'currency.required' => 'Please select a currency.',
            'address.required'  => 'Please enter your destination wallet address / account number.',
        ]);

        
        if ($message = $this->transactionPasswordError($request, $user)) {
            return back()->withInput()->with('error', $message);
        }

        $amount    = (float) $request->amount;
        $method    = $request->method;
        $network   = $request->network;
        $currency  = strtoupper((string) $request->currency);
        $address   = trim($request->address);
        $notes     = $request->notes ?? null;

        if ($method === 'crypto') {
            $w = new \App\Models\withdrawals();
            $w->network = $network;
            $w->wallet_address = $address;
            if (!$w->addressLooksValid()) {
                return back()->with('error', 'Invalid wallet address for ' . $network . '. Please double-check.');
            }
        } elseif ($method === 'advcash' || $method === 'perfect_money') {
            if (strlen($address) < 6) {
                return back()->with('error', 'Please enter a valid ' . ucfirst(str_replace('_', ' ', $method)) . ' account number.');
            }
        }

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

        // Duplicate withdrawal protection: same user + same amount + same destination in the last 10 minutes.
        $recentDuplicate = \App\Models\withdrawals::where('user_id', $user->id)
            ->where('wallet_address', $address)
            ->where('currency', $currency)
            ->where('network', $network)
            ->whereBetween('amount', [$amount - 0.000001, $amount + 0.000001])
            ->whereIn('status', ['pending', 'processing'])
            ->where('created_at', '>=', now()->subMinutes(10))
            ->exists();

        if ($recentDuplicate) {
            return back()->with('error', 'Duplicate withdrawal blocked. A similar request was already submitted recently.');
        }

        $risk = app(\App\Services\WithdrawalRiskService::class)->assess($user, $amount, $address, $network);
        $isAutoCapable = $method === 'crypto' && $currency === 'USDT' && strtoupper((string) $network) === 'TRC-20';

        $approvalRequired = (bool) $settings->require_admin_approval
            || !$isAutoCapable
            || !(bool) ($settings->auto_withdrawals_enabled ?? false)
            || $amount >= (float) ($settings->admin_approval_threshold ?? 100)
            || $amount > (float) ($settings->max_auto_withdrawal ?? 100)
            || (int) $risk['score'] >= (int) ($settings->manual_review_risk_score ?? 50);

        $cashoutBalance = (float) $user->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount');
        if ($cashoutBalance < $amount) {
            return redirect()->route('user.dashboard.withdraw')
                ->with('error', 'Insufficient balance. You have $' . number_format($cashoutBalance, 2) . ' available.');
        }

        $trxNo = Transaction::generateTransactionNo();
        $idempotencyKey = hash('sha256', implode('|', [
            $user->id,
            $method,
            $currency,
            $network,
            $address,
            number_format($amount, 6, '.', ''),
            floor(time() / 600), // 10 minute bucket
        ]));

        $methodLabel = match ($method) {
            'advcash'       => 'Advcash '        . $currency,
            'perfect_money' => 'Perfect Money '  . $currency,
            default         => trim($currency . ' ' . ($network ?? '')),
        };

        try {
            $withdrawal = DB::transaction(function () use ($user, $cashoutBalance, $amount, $method, $network, $currency, $address, $trxNo, $approvalRequired, $risk, $notes, $idempotencyKey) {
                $user->ChartAccount()->where('acc_type', 'CASHOUT')->update(['amount' => $cashoutBalance - $amount]);

                return \App\Models\withdrawals::create([
                    'user_id'           => $user->id,
                    'method'            => $method,
                    'network'           => $network,
                    'amount'            => $amount,
                    'currency'          => $currency,
                    'wallet_address'    => $address,
                    'transaction_no'    => $trxNo,
                    'idempotency_key'   => $idempotencyKey,
                    'status'            => $approvalRequired ? 'pending' : 'processing',
                    'approval_required' => $approvalRequired,
                    'risk_score'        => $risk['score'],
                    'risk_flags'        => $risk['flags'],
                    'notes'             => $notes,
                    'signer_request_id' => $trxNo,
                ]);
            });
        } catch (\Throwable $e) {
            \Log::error('Withdrawal request failed: ' . $e->getMessage());
            return back()->with('error', 'Could not create withdrawal request. Please try again.');
        }

        Transaction::create([
            'user_id'             => $user->id,
            'transaction_no'      => $trxNo,
            'transaction_type'    => 'WITHDRAWAL_REQUEST',
            'receiver_id'         => 0,
            'transaction_details' => json_encode([
                'amount'            => $amount,
                'method'            => $method,
                'currency'          => $currency,
                'network'           => $network,
                'wallet_address'    => $address,
                'date'              => now()->toDateTimeString(),
                'status'            => $approvalRequired ? 'pending' : 'processing',
                'approval_required' => $approvalRequired,
                'risk_score'        => $risk['score'],
                'risk_flags'        => $risk['flags'],
                'method_label'      => $methodLabel,
                'username'          => $user->name,
            ]),
        ]);

        \App\Models\BlockchainAuditLog::record('withdrawal.requested', [
            'user_id'        => $user->id,
            'auditable_type' => \App\Models\withdrawals::class,
            'auditable_id'   => $withdrawal->id,
            'address'        => $address,
            'amount'         => $amount,
            'currency'       => $currency,
            'network'        => $network,
            'request_id'     => $trxNo,
            'message'        => $approvalRequired ? 'Withdrawal requires admin/manual review.' : 'Withdrawal queued for automatic blockchain processing.',
            'context'        => ['risk' => $risk, 'approval_required' => $approvalRequired],
        ]);

        if (!$approvalRequired) {
            \App\Jobs\ProcessBlockchainWithdrawal::dispatch($withdrawal->id);
        }

        try {
            $admins = \App\Models\User::where('utype','ADM')->get();
            foreach ($admins as $admin) {
                \Mail::to($admin->email)->send(new \App\Mail\WithdrawalRequested($user, $amount, $address, $trxNo));
            }
        } catch (\Exception $e) {
            \Log::warning('Could not send withdrawal request email: ' . $e->getMessage());
        }

        $statusMsg = $approvalRequired ? 'Awaiting admin approval/manual review.' : 'Queued for automatic on-chain processing.';
        return redirect()->route('user.dashboard.withdraw')
            ->with('success', 'Withdrawal request of $' . number_format($amount, 2) . ' (' . $methodLabel . ') submitted. Reference: ' . $trxNo . '. ' . $statusMsg);
    }

    /**
     * Show the user their own withdrawal history
     */
    // ════════════════════════════════════════════════════════════
    // METHOD 3: withdrawalHistory()  (line 615)
    // ════════════════════════════════════════════════════════════
    public function withdrawalHistory()
    {
        $user        = Auth::user();
        $cashout     = $user->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount');
        $settings    = \App\Models\WithdrawalSetting::current();
        $history     = WithdrawalModel::where('user_id', $user->id)->latest()->paginate(20);

        $cryptoByCurrency = \App\Models\DepositWallet::activeOfType('crypto')->groupBy('currency');

        return view('user.balance.withdraw', [
            'availlableBalance' => $cashout,
            'settings'          => $settings,
            'cryptoByCurrency'  => $cryptoByCurrency,
            'advcashActive'     => \App\Models\DepositWallet::activeOfType('advcash'),
            'perfectMoneyActive'=> \App\Models\DepositWallet::activeOfType('perfect_money'),
            'history'           => $history,
        ]);
    }

    /**
     * DIRECT BLOCKCHAIN WITHDRAWAL (TRON USDT TRC20)
     *
     * Kept for backward compatibility with the old direct form. It now uses the
     * same safe path as normal withdrawals: limits, duplicate checks, risk
     * review, balance hold, queue processing and audit logging.
     */
    public function directBlockchainWithdraw(Request $request)
    {
        $request->merge([
            'method'   => 'crypto',
            'currency' => 'USDT',
            'network'  => 'TRC-20',
        ]);

        return $this->requestManualWithdrawal($request);
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
}
