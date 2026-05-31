<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\latest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\activate;
use Illuminate\Support\Facades\Redirect;

class News extends Controller
{
    public function activate(Request $request)
    {
        // $email=$request->email;
       $email=request()->query('email')??$request->email;

        $news=new latest();
        $news->email=$email;
        $news->status='activated';
        if ($news->save()) {
            $status="activate";
            $mail = new activate($status,$email);
    Mail::to($email)->send($mail);
              $message = "You have been activated subscription for receiving updates and news of Fonepo. thank u you!";

        return view('emails.activate_complete', compact('message'));

             // return redirect()->back();
  
        }
       return ('not active');

    }
    public function deactivate()
    {
     
       $email=request()->query('email');
      $update = DB::update('UPDATE latests SET email = :em, status = :st, updated_at = now() WHERE email = :emWhere', ['st' => 'deactivate', 'em' => $email, 'emWhere' => $email]);

               if ($update) {
                $status='deactivate';
                   $mail = new activate($status,$email);
    Mail::to($email)->send($mail);
             // return redirect()->back();
              $message = "You have deactivated your subscription for receiving updates and news of Fonepo. We'll miss you!";
        
        // Redirect with a delay using JavaScript
        return view('emails.deactivate_complete', compact('message'));
        }
       
return ('notdacti');
    }
}
