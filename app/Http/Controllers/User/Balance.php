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

        // ── Validate input ──
        $request->validate([
            'amount'  => 'required|numeric|min:12',
            'address' => 'required|string',
        ]);

        $amount  = (float) $request->amount;
        $address = $request->address;

        // ── Check CASHOUT balance ──
        $cashoutBalance = $user->ChartAccount()->where('acc_type', 'CASHOUT')->sum('amount');

        if ($cashoutBalance < $amount) {
            return redirect()->route('user.dashboard.withdraw')
                ->with('error', 'Insufficient balance. You have $' . number_format($cashoutBalance, 2) . ' available.');
        }

        $trxNo  = Transaction::generateTransactionNo();
        $apiKey = config('services.plisio.api_key');

        // ── Call Plisio API ──
        $client   = new Client();
        $apiUrl   = 'https://plisio.net/api/v1/operations/withdraw';
        $plisioId = null;

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
                $errMsg = $responseData['data']['message'] ?? 'Plisio withdrawal failed.';
                return redirect()->route('user.dashboard.withdraw')->with('error', $errMsg);
            }

            $plisioId = $responseData['data']['txn_id'] ?? null;

        } catch (\Exception $e) {
            return redirect()->route('user.dashboard.withdraw')
                ->with('error', 'Withdrawal could not be processed: ' . $e->getMessage());
        }

        // ── Deduct from CASHOUT balance ──
        $newBalance = $cashoutBalance - $amount;
        $user->ChartAccount()->where('acc_type', 'CASHOUT')->update(['amount' => $newBalance]);

        // ── Record withdrawal ──
        WithdrawalModel::create([
            'user_id'        => $user->id,
            'amount'         => $amount,
            'wallet_address' => $address,
            'currency'       => 'USDT',
            'transaction_no' => $trxNo,
            'plisio_txn_id'  => $plisioId,
            'status'         => 'completed',
        ]);

        // ── Record transaction log ──
        Transaction::create([
            'user_id'             => $user->id,
            'transaction_no'      => $trxNo,
            'transaction_type'    => 'WITHDRAWAL',
            'receiver_id'         => 0,
            'transaction_details' => json_encode([
                'amount'         => $amount,
                'currency'       => 'USDT',
                'wallet_address' => $address,
                'plisio_txn_id'  => $plisioId,
                'date'           => now()->toDateTimeString(),
                'status'         => 'completed',
                'username'       => $user->name,
            ]),
        ]);

        return redirect()->route('user.dashboard.withdraw')
            ->with('success', 'Withdrawal of $' . number_format($amount, 2) . ' USDT submitted successfully. Transaction: ' . $trxNo);
    }
     public function withdraw (){
        $user = Auth::User();
        $CASHOUT = $user->ChartAccount()->where("acc_type","CASHOUT")->sum("amount");

        return view('user.balance.withdraw',["availlableBalance"=>$CASHOUT]);
    }

     public function deposit(){
        return view('user.balance.deposit');
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
    if($request->amount<12)
    {
        return view('user.balance.deposit')->with('ammount','less amount!! minimum ammount is 12$');
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
                'callback_url' => 'http://fonepo.com/user/dashboard/deposit/status',
                'success_callback_url'=>'http://fonepo.com/user/dashboard/deposit/success',
                'fail_callback_url '=>'http://fonepo.com/user/dashboard/deposit/fail',
                'expire_min'=>15,
                'api_key' => 'rPs1vyRlJZChOsYy9F--yeiEUTNgCOzCcnG4bKu_sp3hM5SP64GzWqqdadDM6x95', // Replace with your actual secret key
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
    if (verifyCallbackData())
    {
            $status = 'success deposit';
            return view('user.balance.deposit', compact('status'));
     }
        else
        {
            // Invalid callback data
            $status = 'error while deposit';
            $message = 'Invalid callback data';
            return view('user.balance.deposit', compact('status', 'message'));
        }
}
 public function success(Request $request)
  {
//
    $value = $request->amount;

      $user = Auth::user();
    $userId = $user->id;
    $email =$user->email;
    $name=$user->user;
    $balance=new balance1();
    $balance->user=$name;
    $balance->deposit=$value;
    if($balance->save())
    {
         return redirect()->route('user.dashboard.deposit')->with('message', 'Thank u for depositing. you deposit amount has been credited.');
    }

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

        $request->validate([
            'amount'  => 'required|numeric|min:12',
            'address' => 'required|string',
        ]);

        $amount  = (float) $request->amount;
        $address = $request->address;

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
