<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;


use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\activation;
use App\Models\User;
use Illuminate\Http\Request;
use Omnipay\Omnipay; 
 use Illuminate\Support\Facades\Mail;
 use App\Mail\ActivationEmail;
use App\Services\TripleAService;
use Illuminate\Support\Facades\Redirect;
use Socialite;
use GuzzleHttp\Client;
class PaypalController extends Controller
{
    protected $tripleAService;

   
        
   
    private $gateway;
private $payed; private $package;
    public function __construct(TripleAService $tripleAService) {
      $this->package='';
      $this->tripleAService = $tripleAService;
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId('AcidJBtn7-5f7F2p2Z_WHyk21-t2uLqJVfl3cJwOM-unt91NwODl03SbstXkqBCnnU9ZGQxA2Am0kIr0');
        $this->gateway->setSecret('EMureBBonD6dw4-mR9EiG0xSWhLXCqyIew_SFarmOf6WOKrS9Qc7HbP_RAo2ByhgDi1rYcIFGwaazZB6');
        $this->gateway->setTestMode(true);
    }
  
    public function pay(Request $request)
    {

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

                $payment = new Payment();
                $payment->payment_id = $arr['id'];
                $payment->payer_id = $arr['payer']['payer_info']['payer_id'];
                $payment->payer_email = $arr['payer']['payer_info']['email'];
                $payment->amount = $arr['transactions'][0]['amount']['total'];
                $payment->currency = 'USD';
                $payment->payment_status = $arr['state'];

                $payment->save();
                $amm= $arr['transactions'][0]['amount']['total'];
                // $data=[
                //     'id'=>$arr['id'],
                //     'message'=>'You have successfull paid package of fc'. $arr['transactions'][0]['amount']['total'],
                // ];
              
              
                    // User is logged in
                    $user=Auth::user();
                    $userId = $user->id;
                    // Do something with the user ID
                 //   return( "Logged-in user ID:  . $userId");
                    $user = User::find($userId);
                    $email=$user->email;
                    
                     if ($amm==100.00) {
                     $pack='fc1';
                     }
                     else{
                     $pack='fc2';
                     }
                    
                     $activation = $this->generateActivationCode(20);
         response()->json($activation);
                 
                   // $user->has_pai 
        // $user = Auth::user();
                       //$user->activation=$activation;d_package = '$pack';
                    $user->has_free_package = 'yes';
$user->save();
         $activations= new activation();
                     
       // $userId = $user->id;
       // $given= $user->activation;
            $activations->code=$activation;
            $activations->package=$pack;
            $activations->stutus='not';
                   
                    // Save the changes to the database
                 if($activations->save())
      { 
        $send=$this->SendCode($email,$activation);
         response()->json($send);
         return redirect()->route('user.dashboard')->with('message','We have sent you an activation code, check your email');
                    //return view('livewire.user.user-dashboard-component')->layout('layouts.user-dashboard-base');
                }
                 else{
                    return 'none';
                }
                  
            }
            else{
                return $response->getMessage();
            }
        }
        else{
            return 'Payment declined!!';
        }
    }

//activation function
  private  function generateActivationCode($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';

    for ($i = 0; $i < $length; $i++) {
        $randomIndex = mt_rand(0, strlen($characters) - 1);
        $code .= $characters[$randomIndex];
    }

    return $code;
}
private function SendCode($emaili,$activation) {
 
// $subjects = "Activation code of your package";
// $messages = "hello dear. We are happy to have you in system. enter this code $activation to activate your package.This is individual email from Afonete don't share it.";
// $headers = "From: infoafonete@gmail.com\r\n" .
//            "Reply-To: infoafonete@gmail.com\r\n" .
//            "X-Mailer: PHP/" . phpversion();

$email=$emaili;
    $mail = new ActivationEmail($activation);
    Mail::to($email)->send($mail);
  return redirect()->route('user.dashboard');
}

    public function free()
    {

        $user=Auth::user();
                    $userId = $user->id;
                 
                    $user = User::find($userId);
                    $user->has_paid_package = 'standard';
                    $user->has_free_package = 'yes'; 
            if($user->save())
      { 
         return redirect()->route('user.dashboard');
                   
                }
                else{
         return redirect()->route('user.package')->with('message','error while savig');

                    
                }
    }

    public function error()
    {
        return 'User declined the payment!';  
         
    }

 

    public function triple()
    {
        // Perform necessary logic to create a payment using Triple-A integration
 //        $amount = 100.00; // Example amount
 //        $orderId = 123456; // Example order ID

 //        $response = $this->tripleAService->createPayment($amount, $orderId);

 //        // Handle the response from Triple-A and proceed accordingly
 // if ($response && isset($response['payment_id']) && isset($response['status'])) {
 //        return view('livewire.user.triple', ['response' => $response]);
 //    }
 //        return ("error");
        $client = new Client();

$response = $client->post('https://dashboard.triple-a.io/oauth/token', [
    'form_params' => [
        'grant_type' => 'client_credentials',
        'client_id' => 'oacid-cli5ckns027q86eiseinj1ah9',
        'client_secret' => '5f53acc1c5ad43483806a7bab142dabf2d3cba6f25d32ead36300ad1f98aed89',
    ],
]);

$accessToken = json_decode($response->getBody(), true)['access_token'];
if ($accessToken){
return ($accessToken);}


    }
    public function redirectToTripleA()
    {
        return Socialite::driver('triplea')->redirect();
    }

    public function handleTripleACallback()
{
    $user = Socialite::driver('triplea')->user();
    // Retrieve the access token from the user object
    $accessToken = $user->token;
    // Perform necessary actions with the access token (store, authenticate, etc.)
    // Redirect or display a success message
}
}