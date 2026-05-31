<?php

namespace App\Http\Controllers\fearloss;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\video;
use App\Models\Balance;
use App\Models\ChartAccount;
use App\Models\views;
use App\Models\Transaction;


class Videos extends Controller
{
   public function Showvideo()
    {
        return view('user.fearloss.videos');
    }
    public function watch(Request $request)
    {
        $id=$request->video;
         $user=Auth::user();
        $name=$user->user;

        $checked=array(
        'already'=> 'You have already watched this ads, try onather one to earn',
        'id'=> $id,

     );
     $check=db::SELECT("SELECT * from views where user='$name' and video='$id'");

     if (count($check)) {
       return view('user.fearloss.video',$checked);

     }
     return view('user.fearloss.video')->with('id',$id);
    }


    public function watched(Request $videos){
        $username = $user->user;
        $video = video::where("id",$videos->id)->first();
        $user = Auth::User();
        $view = views::where("video",$videos->id)->where("user",$username)->first();

        $userAnswer = $videos->answer;
        $videoAnswer = $video->answer;


        if($userAnswer !== $videoAnswer){
            return back()->with("error","Invalid Answer! please provide the correct answer to earn!");
        }

        if($view){
            return back()->with("error","You can not earn more than once on the same ADS!");
        }
        $video->reached_views += 1;
        $video->save();
        $trxId =Transaction::generateTransactionNo();

        views::create([
            "video"=>$video->id,
            "has_answer"=>$video->answer,
            "user"=>$user->user
        ]);

        ChartAccount::updateOrCreate(
            [
                'user_id' => $user->id,
                'acc_type' => 'PAID_ADS',
            ],
            [
                'amount' => $video->user_earns
            ]
            );


            $transaction =  Transaction::create([
                'user_id'=>$user->id,
                'transaction_no'=> $trxId,
                'transaction_type'=> 'PAID_ADS',
                'receiver_id'=>0,
                'transaction_details' => json_encode([
                    'amount' => $video->user_earns,
                    "has_answer"=>$video->answer,
                    "message"=>"You have earned $video->user_earns",
                    "ads_details"=>[
                        "video"=>$video->video,
                        "title"=>$video->tittle,
                    ],
                    "status"=>"success",
                    "type"=>"video"
                ])
            ]);



return back()->with("success","You have earned $video->user_earns ");
    }





    public function invoices(){
         $user = Auth::User();
        $transactions = $user->transactions()->where("transaction_type","PAID_ADS")->get();

        return view("user.fearloss.invoices",["transactions"=>$transactions]);
    }

     public function Paywatch(Request $request)
    {
        $answer=$request->answer;
        $id=$request->id;
        $user=Auth::user();
        $name=$user->user;

    $message=array(
    'id'=>$request->id,
    'success'=>"Thank you for watching video you earn 0.1$",
        'already'=> 'You have already watched this video, try onather one to earn',

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
        'fail'=> 'wrong answer,you failed to earn money try viewing and answer correctly with other videos',
        'id'=> $request->id,
        'already'=> 'You have already watched this video, try onather one to earn',


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
      $check=db::SELECT("SELECT * from videos where id='$id'");
        foreach ($check as $value) {
        $reached=$value->reached_views;
        $new=$reached+1;
        $update=db::UPDATE("UPDATE videos set reached_views='$new'");
        if (!$update) {
           return view('user.fearloss.video',$checke);
        }
        }
     $views=new views();
   $views->user=$name;
   $views->ads=$request->id;

   if (!$views->save())
   {
       return view('user.fearloss.video',$ads);

   }
$results=db::SELECT("SELECT question from videos where id='$id' and answer='$answer'");

   if (count($results))
   {
$balance=new Balance();
$balance->user=$name;
$balance->earning=0.1;
if ($balance->save()) {

       return view('user.fearloss.video',$message);

} else{
       return view('user.fearloss.video',$balanc);

   }

   }
   else{
       return view('user.fearloss.video',$fmsg);
   }
   }
    }

