<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered; 
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\CreatedUser;
class UserRegistration extends Controller
{
    /**
     * Display the registration view.
     */
  

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function user_request(Request $request): RedirectResponse
    {   
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'user' => ['required', 'string', 'max:10', 'min:4', 'unique:'.User::class],
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            // 'phone' => ['required','string','max:255'],
            // 'referee_id' => ['string','max:255'],
        // 'password' => ['required', 'confirmed', Rules\Password::defaults()],

            'country' => ['required','string','max:255'],


        ]);
        $exist=User::where('user',$request->user)->exists();
if($exist)
{
    return view('auth.register')->with('exist', 'username has been taken, choose another');
}



        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'user'=>$request->user,
            'country' => $request->country,
            'activation'=>rand(1111111,9999999),
            'referee_id' => $request->referee_id,
            'password' => Hash::make($request->user),

            'has_request' => 'requested',
        ]);
 // event(new Registered($user));

        Auth::login($user);
        // return view('requested');
         return redirect()->route('request')->with('success', 'Your request was submitted successfully!');

       
    }
    public function confirm_user(Request $request)
    {
         $id = $request->query('id');
          $user = User::find($id);
          $email=$user->email;
          $password=$user->user;
          $username=$user->user;
if ($user->update(['has_request' => 'confirmed']))
 {
  


            $mail = new CreatedUser($email,$password,$username);
    Mail::to($email)->send($mail);

        return redirect()->route('admin.requested')->with('created','new User '.$username.' has confirmed and welcomed in Fonepo');

  }

  else {
    return view('user.user-request')->with('failed','failed to confirm user ');
  }
}
   
}