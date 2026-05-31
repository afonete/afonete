<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Balance;
use App\Models\Payments;
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
class PaymentController extends Controller
{
    private $gateway;
        public function __construct() {

        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId('Ae9a97cTQtx4Op8RCe42cFyVSWc1XjIUwMODNz5IuqZb4Kjv4ky21Ex6LUNqLVy4nsV4GKOR3wzVmgBM');
        $this->gateway->setSecret('EOLX3EwCDieUaDVL4t8N2wkuNh_L3DOaXH9scfk2zy8C-NTjPCuQ0ZtR0FT6XwwaT0OIvavzJcLBAfyj');
        $this->gateway->setTestMode(false);
    }
  public function blockpay(Request $request)
  {
 if($request->option=='crypto'){
// $api_key = '31T8xOus55ePakEZlzlgqUmZ5w34xcF5lJNPvIKqw7M';


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
                'email' => $request->email,
                'order_name' => 'package activation',
                'callback_url' => 'http://fonepo.com/user/package/payment/status',
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
        return Redirect::away($invoiceUrl);

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

 // paypal success
 public function success(Request $request)
    {
        if ($request->input('paymentId') && $request->input('PayerID')) {
            $transaction = $this->gateway->completePurchase(array(
                'payer_id' => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId')
            ));

            $response = $transaction->send();

            if ($response->isSuccessful()) {

                $arr = $response->getData();

                // $payment = new Payments();
                // $payment->payment_id = $arr['id'];
                // $payment->payer_id = $arr['payer']['payer_info']['payer_id'];
                // $payment->payer_email = $arr['payer']['payer_info']['email'];
                // $payment->amount = $arr['transactions'][0]['amount']['total'];
                // $payment->currency = 'USD';
                // $payment->payment_status = $arr['state'];

                // $payment->save();
                $amm= $arr['transactions'][0]['amount']['total'];
                // $data=[
                //     'id'=>$arr['id'],
                //     'message'=>'You have successfull paid package of fc'. $arr['transactions'][0]['amount']['total'],
                // ];
              $pack='fc1';

                    // User is logged in
                    $user=Auth::user();
                    $userId = $user->id;
                    // Do something with the user ID
                 //   return( "Logged-in user ID:  . $userId");
                    $user = User::find($userId);
                    $email=$user->email;
                    $referral=$user->activation;


                        if ($amm==100.00) {
                     $pack='fc1';
                     }
                     else{
                     $pack='fc2';
                     }
$token=$pack=='fc1'?'10000':'20000';

                     $activation = $this->generateActivationCode(20);
         response()->json($activation);

                   // $user->has_pai
        // $user = Auth::user();
                       //$user->activation=$activation;d_package = '$pack';
                    $user->has_free_package = 'yes';
$user->save();
                    $activations= new activations();
                     $pack=$pack;
       // $userId = $user->id;
       // $given= $user->activation;
            $activations->code=$activation;
            $activations->package=$pack;
            $activations->stutus='yes';
             $activations->email=$email;

                   $a_user=$user->user;
    $balance=new Balance();
  $balance->user=$a_user;
  $balance->reserved_token=$token;
  $balance->save();
                    // Save the changes to the database
                 if($activations->save())
      {
        $send=$this->SendCode($email,$activation,$pack);
         response()->json($send);
         return redirect()->route('user.dashboard')->with('message','We have sent you an activation code, check your email');
                    //return view('user.dshboard');
                }
                else{
                     return redirect()->route('user.user-package')->with('message','Nothing selected, try again');

                }

            }
            else{
               return redirect()->route('user.dashboard')->with('message','payment declained, try again ');

            }
        }
        else{
            $pay=DB::SELECT('SELECT * from users where activation =:refer limit 1',['refer'=>$referral]);
    global $payed;
    if(count($pay)){
        foreach($pay as $payee)
        {
            $payer=$payee->user;
            $paybalance=new Balance();
            $paybalance->user=$payer;
            $paybalance->reserved_token=500;


            if ($paybalance->save())
            {
                 return redirect()->route('user.dashboard')->with('message','You have been  activated '.$pack.' account , Enjoy unlimited earning on Fonepo');
            }
            else{
                return  redirect()->route('user.dashboard')->with('message','error occour, call admin pr live chat');
            }

                    //return view('user.dshboard');
                    }
    }
        }
    }

 //error paypal
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
  public function pssuccess(Request $request)
  {
//
    $value = $request->amount;

$package=$value==100?'fc1':'fc2';
$pack=$value==100?'FC $100':'FC $200';
$token=$value==100?'10000':'20000';

      $activation = $this->generateActivationCode(20);
      response()->json($activation);
     $user = Auth::user();
    $userId = $user->id;
    $email =$user->email;
    $a_user=$user->user;
    $referral=$user->activation;
    $balance=new Balance();
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
        foreach($pay as $payee)
        {
            $payer=$payee->user;
           $paybalance=new Balance();
            $paybalance->user=$payer;
            $paybalance->reserved_token=500;
        }
    }

    if ($user->save() && $paybalance->save()) {
      return redirect()->route('user.dashboard')->with('message', 'You have been  activated '.$pack.' account , Enjoy unlimited earning on Fonepo');

    } else {
      return redirect()->route('user.package')->with('message', 'error while savig');
    }
    }

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
    //$user->activation = $name.rand(0000,9999);
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

// $subjects = "Activation code of your package";
// $messages = "hello dear. We are happy to have you in system. enter this code $activation to activate your package.This is individual email from Fonepo don't share it.";
// $headers = "From: infoFonepo@gmail.com\r\n" .
//            "Reply-To: infoFonepo@gmail.com\r\n" .
//            "X-Mailer: PHP/" . phpversion();

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
       $package = [
        "name"=>"fc1",
        "price"=>100
       ];
      return view('user.pay')->with(['package'=>$package]);
    // return('fc1');

 }
  public function fc2(){
    $package = [
        "name"=>"fc2",
        "price"=>200
       ];
       dd($package);
      return view('user.pay')->with(['package'=>$package]);

 }


 public function pscallback(Request $request)
{
    if (verifyCallbackData()) {
            // Callback data is valid, process the payment update
            // Add your logic here to handle the payment update

            $status = 'success';
            return view('user.callback', compact('status'));
        } else {
            // Invalid callback data
            $status = 'error';
            $message = 'Invalid callback data';
            return view('user.callback', compact('status', 'message'));
        }
}
//  public function pssuccess()
// {

// }
 public function pserror()
{
    return view('user.pay')->with('p_failed','Payment failed, Retry again');
}





 }
