<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Contacted;
use App\Models\requested;
use App\Models\Balance;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
//0782240429
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
         if( $contacted->save()){
//              $to = 'sagemargo11@gmail.com';
// $subject = 'Message received well  ';
// $message = 'Hello, Your message to afonete have been received well.\n\n Our support team  will reach to you soon! \n\n
// Thank you for contacting us';
// $headers = 'From: infoafonete@gmail.com' . "\r\n
// ".'Reply-To: infoafonete@gmail.com' . "\r\n" .
//         'X-Mailer: PHP/' . phpversion();;

// $mailSent = mail($to, $subject, $message, $headers);

// if ($mailSent) {
return view('home.contact')->with('message','success');
}
// else {
//     return view('home.contact')->with('message','email');
// }
// return ($name);
        // }
     else {
    return view('home.contact')->with('message','error');
 }

    }
       public function project(){
        return view('home.project');

    }


    public function investmentPackage(){

        return view('user.investment-package');

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
                    //return view('user.dshboard');
 }
 public function try()
 {
    return view('user.coin');
 }
   public function requested()
   {
    return view('requested');

   }
  public function request(Request $request)
 {
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
         if( $requested->save()){
             return view('auth.register')->with('request','Thank u for requesting to become Fonepo member, You will be contacted very soon');
         }
         else{
              return view('auth.register')->with('request','request failed, -->consult live chat');
         }

 }
  public function balance()
 {
    $pay=DB::SELECT('SELECT * from users where activation=1874089 limit 1');
    global $payed;
    if(count($pay)){
        foreach($pay as $payee)
        {
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
