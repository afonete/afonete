<?php

namespace App\Http\Controllers\User;
use App\Exceptions\Handler;
use App\Models\User;
use App\Models\member as Member;
use App\Models\Club as club1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
   use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Club extends Controller
{
    protected $renderers = [
    Handler::class,
];
    public function create(Request $request)
    {
          $user = Auth::user();
    $ref = $user->activation;
    $userr=$user->user;
    $email = $user->email;
    $tittle=$request->tittle;
    $desc=$request->desc;
   
        $access=DB::SELECT("SELECT * from users where referee_id='$ref' and has_free_package ='yes'");
         $check=count($access);
    
        if ($check<5)
        {
    
  
       $rem=5-$check;
            return view('user.team.createTeam')->with('message','You need to have '.$rem.' direct referral To be allowed to create club');
        }
        
        $club=new club1();
        $club->user=$userr;
        $club->tittle=$tittle;
        $club->desc=$desc;
         $club->rank="none";
         if($club->save()){
              return view('user.team.createTeam')->with('message','Club Have been created well. you can now start inviting user to join your club');
         }
          return view('user.team.createTeam')->with('message','Club creation failed ');
    }
     public function invite()
    {
        $name=request()->query('name');
         $existInUser=User::where('user', $name)->first();
    if(!$existInUser)
    {
         return view('user.team.createTeam')->with('message','user '.$name.' Doesn`t exist in, invalid or wrong username  ');
    }
         $existInMember=member::where('member', $name)->first();
    if($existInMember)
    {
         return view('user.team.createTeam')->with('message','user '.$name.' Have joined club arleady, can not join two club');
    }
    $user = Auth::user();
    $userr=$user->user;
       $m=$name;
        $member=new Member();
        // return ($userr);
        $member->user=$userr;
        $member->member=$m;
         $member->status='0'; //0 invited, 1 joined 2 requested
         if($member->save()){
            return view('user.team.createTeam')->with('message','user '.$m.' have been invited to your club ');
        }
         return view('user.team.createTeam')->with('message','user '.$m.' invitation  to your club failed');
        
    
    }
     public function accept()
    {
      $memberId=request()->query('id');
      $member = Member::findOrFail($memberId);
      $member->update(['status' => 1]);
      if ($member)
      {
       return view('user.team.createTeam')->with('message','user '.$m.' has been accepted in your club');
      }
      else 
      {
          return view('user.team.createTeam')->with('message','user '.$m.' failed to accepted in club');
      }
    }
    
public function request(Request $request)
{
 $user = Auth::user();
    $userr=$user->user;
       $leader=$request->leader;
    $existBefore=Member::where('member', $userr)->exists();
    if($existBefore)
    {
         return redirect()->back()->with('request','You can`t join more than one club. you have joined club before ');
    }
        $member=new Member();

        $member->user=$leader;
        $member->member=$userr;
         $member->status='2'; //0 invited, 1 joined 2 requested
  
        if ($member->save()) {
    return redirect()->back()->with('request', 'Your request to join the club has been accepted. Please wait for confirmation from the club leader.');
}

return redirect()->back()->with('request', 'Your request to join the club has failed. Please try again.');

        
}
public function acceptInvite()
{

$user = Auth::user();
    $userr = $user->user; // Assuming 'user' is a property of the authenticated user

    $accepted = Member::where('member', $userr)->firstOrFail(); // Using 'where' instead of 'findOrFail'
    $invitee = request()->query('invitee');
    
    $accepted->update(['status' => 1]);

    if ($accepted) {
        // You can return a response or view here
        // For now, I'm returning a JSON response with a message
        return redirect()->back()->with('request','You accepted the invitation from ' . $invitee);
    }

}
}