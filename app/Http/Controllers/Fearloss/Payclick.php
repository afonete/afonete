<?php

namespace App\Http\Controllers\Fearloss;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\ads;
use App\Models\Balance;
use App\Models\ChartAccount;
use App\Models\Transaction;
use App\Models\views;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Payclick extends Controller
{
      public function Showclick()
    {
        $results = ads::orderBy("created_at", "DESC")->get();
        return view('user.fearloss.payclick',["results"=>$results]);
    }

       public function click(Request $request)
    {
        $id=$request->ads;
        $user=Auth::user();
        $name=$user->user;

        dd($request);
        $checked=array(
            'already'=> 'You have already watched this ads, try onather one to earn',
            'id'=> $id,
        );
         $ads=array(
            'id'=>$request->id,
            'errora'=>"failed to verify view",
        );

     $check=db::SELECT("SELECT * from views where user='$name' and ads='$id'");


     if (count($check)) {
       return view('user.fearloss.ads',$checked);

     }
     $views=new views();
     $views->user=$name;
     $views->ads=$id;

   if (!$views->save()){
       return view('user.fearloss.ads',$ads);

   }
     return view('user.fearloss.ads')->with('id',$id);
    }


    public function Asking(Request $request){

        $url = $request->input('url');
        $id = $request->input('ads');
        $add = ads::where("id",$id)->first();

        return view("user.fearloss.asking",["url"=>$url,"id"=>$id,"question"=>$add->question]);
    }

    public function ClaimReward(Request $request){

        $user = Auth::User();
        $id = $request->id;
        $userAnswer = $request->answer;
        $ads = ads::where("id",$id)->first();
        $realAnswer = $ads->answer;
        $earning =  $ads->user_paid / $ads->targeted_views;
        $check = views::where("user",$user->user)->where("ads",$id)->first();

        if($check){
            return back()->with("error","You have already watched this ads, try onather one to earn");
        }
        if($userAnswer !== $realAnswer){
            return back()->with("error","wrong answer,you failed to earn money try viewing and answer correctly with other ads");
        }
        $ads->reached_views += 1;
        $ads->save();
        $trxId =Transaction::generateTransactionNo();

        views::create([
            "ads"=>$ads->id,
            "has_answer"=>$ads->answer,
            "user"=>$user->user
        ]);

        ChartAccount::updateOrCreate(
            [
                'user_id' => $user->id,
                'acc_type' => 'PAID_ADS',
            ],
            [
                'amount' => $earning
            ]
        );


        $transaction =  Transaction::create([
                'user_id'=>$user->id,
                'transaction_no'=> $trxId,
                'transaction_type'=> 'PAID_ADS',
                'receiver_id'=>0,
                'transaction_details' => json_encode([
                    'amount' => $earning,
                    "has_answer"=>$ads->answer,
                    "message"=>"You have earned $earning",
                    "ads_details"=>[
                        "name"=>$ads->title,
                        "url"=>$ads->url,
                        "description"=>$ads->description
                    ],
                    "status"=>"success",
                    "type"=>"Normal Ads"
                ])
            ]);

            return redirect()->route("user.dashboard.payvideo.invoice");
    }

    public function url(Request $request){
         $user = Auth::User();
         $ad = $request->ads;
         $url = $request->url;
         $ads = ads::where("id",$ad)->first();

         $viewed = views::where("ads",$ad)->where("user",$user->user)->first();
        if($viewed){
            return back()->with(["error"=>"You have already watched this ads, try onather one to earn"]);
        }
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }

    if (filter_var($url, FILTER_VALIDATE_URL)) {

     // try {

        // $context = stream_context_create([
        //     'http' => [
        //         'timeout' => 10,
        //         'user_agent' => 'Mozilla/5.0',
        //         'follow_location' => true,
        //     ],
        // ]);

        // $externalContent = file_get_contents($url, false, $context);
        // if ($externalContent === false) {
        //     return view('user.myview', ['externalContent' => 'Failed to fetch URL']);
        // }

        // $check=db::SELECT("SELECT * from views where user='$name' and ads='$id'");

        // return view('user.myview',["url"=>$url,"ads"=>$id]);

    //  if (count($check)) {

    //     return view('user.myview', ['externalContent' => $externalContent,'checked'=>$checked]);
    //  }




        // $views=new views();
        // $views->user=$name;
        // $views->ads=$id;


    // }
    // catch (Exception $e) {
    //     // Handle exceptions (e.g., timeout, invalid URL) gracefully
    //     return view('user.myview', ['externalContent' => 'Error: ' . $e->getMessage()]);
    // }

// dd($ads);

    $earn = $ads->user_paid / $ads->targeted_views;
    return view("user.myview",["url"=>$url,"id"=>$ad,"earn"=>$earn,"answer"=>$ads->answer]);

    } else {
        // If the URL is not valid, return an error response
        return back()->with([
            'error' => 'Invalid URL',
        ]);
    }



    }


        public function Payads(Request $request)
    {
        $answer=$request->answer;
        $id=$request->id;
        $user=Auth::user();
        $name=$user->user;

    $message=array(
    'id'=>$request->id,
    'success'=>"Thank you for watching ads you earn 0.0001$",
        'already'=> 'You have already watched this ads, try onather one to earn',

   );
    $ads=array(
    'id'=>$request->id,
    'errora'=>"failed to verify view",
   );
     $balanc=array(
    'id'=>$request->id,
    'errorb'=>"failed to give balance",
   );
     $fmsg=array(
        'fail'=> 'wrong answer,you failed to earn money try viewing and answer correctly with other ads',
        'id'=> $request->id,
        'already'=> 'You have already watched this ads, try onather one to earn',


     );
       $checked=array(
        'already'=> 'You have already watched this ads, try onather one to earn',
        'id'=> $request->id,
     );
         $checke=array(
        'update'=> 'failed to increase view',
        'id'=> $request->id,
     );

     // $check=db::SELECT("SELECT * from views where user='$name' and ads='$id'");
     // if (count($check)) {
     //   return view('user.fearloss.ads',$checked);
     // }
      $check=db::SELECT("SELECT * from ads where id='$id'");
        foreach ($check as $value) {
        $reached=$value->reached_views;
        $new=$reached+1;
        $update=db::UPDATE("UPDATE ads set reached_views='$new' where id='$id'");
        if (!$update) {
           return view('user.fearloss.ads',$checke);
        }
        }
     $views=new views();
   $views->user=$name;
   $views->ads=$request->id;

   if (!$views->save())
   {
       return view('user.fearloss.ads',$ads);

   }
$results=db::SELECT("SELECT question from ads where id='$id' and answer='$answer'");

   if (count($results))
   {
$balance=new Balance();
$balance->user=$name;
$balance->earning=0.0001;
if ($balance->save()) {

       return view('user.fearloss.ads',$message);

} else{
       return view('user.fearloss.ads',$balanc);

   }

   }
   else{
       return view('user.fearloss.ads',$fmsg);
   }
    }

            public function Payurl(Request $request)
    {
        $answer=$request->answer;
        $id=$request->id;
        $user=Auth::user();
        $name=$user->user;

    $message=array(
    'id'=>$request->id,
    'success'=>"Thank you for watching ads you earn 0.0001$",
    'already'=> 'You have already watched this ads, try onather one to earn',

   );
    $ads=array(
    'id'=>$request->id,
    'errora'=>"failed to verify view",
   );
     $balanc=array(
    'id'=>$request->id,
    'errorb'=>"failed to give balance",
   );
     $fmsg=array(
        'fail'=> 'wrong answer,you failed to earn money try viewing and answer correctly with other ads',
        'id'=> $request->id,
        'already'=> 'You have already watched this ads, try onather one to earn',


     );
       $checked=array(
        'already'=> 'You have already watched this ads, try onather one to earn',
        'id'=> $request->id,
     );
         $checke=array(
        'update'=> 'failed to increase view',
        'id'=> $request->id,
     );

     // $check=db::SELECT("SELECT * from views where user='$name' and ads='$id'");
     // if (count($check)) {
     //   return view('user.fearloss.ads',$checked);
     // }
      $check=db::SELECT("SELECT * from ads where id='$id'");

        foreach ($check as $value) {
        $reached=$value->reached_views;
        $new=$reached+1;
        $update=db::UPDATE("UPDATE ads set reached_views='$new' where id='$id'");
        if (!$update) {
           return view('user.myview',$checke);
        }
        }
     $views=new views();
   $views->user=$name;
   $views->ads=$request->id;

   if (!$views->save())
   {
       return view('user.myview',$ads);

   }
$results=db::SELECT("SELECT question from ads where id='$id' and answer='$answer'");

   if (count($results))
   {
$balance=new Balance();
$balance->user=$name;
$balance->earning=0.0001;
if ($balance->save()) {

       return view('user.myview',$message);

} else{
       return view('user.myview',$balanc);

   }

   }
   else{
       return view('user.myview',$fmsg);
   }
    }

}
