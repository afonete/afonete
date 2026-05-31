<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

    use Illuminate\Support\Facades\Auth;

    use App\Models\Position as positions;
    use App\Models\User;

    use Illuminate\Http\Request;

class Position extends Controller
{
    public function apply(Request $request)
    {
        $user=Auth::User();
    $username=$user->user;
   
        $position=$request->position;
        $award=$request->award; 
// return($position.' '.$award);
        try{
        positions::create([
            'user'=>$username,
            'position'=>$position,
            'award'=>$award,
            'status'=>'waiting',
        ]);
        return redirect()->back()->with('message','You have applied to be '.$position.', you will be rewarded '.$award.'$');
    }
  catch (Exception $e) {
    
      return redirect()->back()->with('message','failed to apply to be '.$position.' Try again');
  }
    }
}
