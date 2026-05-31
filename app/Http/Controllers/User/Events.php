<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class Events extends Controller
{
    //
    public function getEvents(){
   
        return view('user.events');
    }
     public function reportEvents(Request $request){
        // return view('user.events');
        $imgName1=rand(00000,99999).$request->img1;
        $imgName2=$request->img2;
         $path=public_path('events');
   $img_name1=$path . '/' . $imgName1;
     $desc=$request->desc;
        $user=Auth::User();
    $username=$user->user;
   $uploaded1=false;
   $uploaded2=false;
    try
    {
        file_put_contents($img_name1, file_get_contents($imgName1));
    }
    catch(Exception $e)
    {

       return view('user.events')->with('failed','img1');
    }
        if ($imgName2 !=NULL) {
        $imgName2=rand(00000,99999).$request->img2;
   $img_name2=$path . '/' . $imgName2;
 try
    {
        file_put_contents($img_name2, file_get_contents($imgName2));
    }
    catch(Exception $e)
    {

       return view('user.events')->with('failed','img2');
    }
    }
    else{
        $uploaded2=true;

    }
   
    
    try {
        Event::create([
     'user'=>$username,
     'img1'=>$request->img1,
     'img2'=>$request->img1,
     'desc'=>$desc,
     'date_on'=>$request->date,
     'status'=>'pending',
    ]);
       return view('user.events')->with('success','Events Reported well');
    } catch (Exception $e) {
       return view('user.events')->with('die','Failed to report event');
        
    }

    }
    
}
