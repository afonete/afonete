<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('user.profile-component', [
            'user' => $request->user(),
        ]);
        // return view('user.profile-component');
    }
      public function ad_edit(Request $request): View
    {
        return view('admin.profile-component', [
            'user' => $request->user(),
        ]);
        // return view('user.profile-component');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
     public function updates(Request $request)
    {
       // return view('livewire.profile.profile-component')->layout('layouts.user-dashboard-base');

        $user = Auth::user();
        $userId = $user->id;
        $user = User::find($userId);
        $name = $request->input('name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        // $user->profile_photo_url = $request->input('pic');
        //$user = User::where('email', $email)->first();
       
            $user->name = $name;
            $user->phone = $phone;

            $user->save();
        // return redirect('profile/account');
       
            if ($user->save()) {
                // return view('user.profile-component');
                return redirect()->route('profile.edit')->with('success','Profile updated successfull');
            } 
            else {
                return view('profile.edit')->with('fail','Profile not updated ');
            }
    }
    public function ad_updates(Request $request)
    {
       // return view('livewire.profile.profile-component')->layout('layouts.user-dashboard-base');

        $user = Auth::user();
        $userId = $user->id;
        $user = User::find($userId);
        $name = $request->input('name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        // $user->profile_photo_url = $request->input('pic');
        //$user = User::where('email', $email)->first();
       
            $user->name = $name;
            $user->phone = $phone;

            $user->save();
        // return redirect('profile/account');
       
            if ($user->save()) {
                // return view('user.profile-component');
                return redirect()->route('admin.profile.edit')->with('success','Profile updated successfull');
            } 
            else {
                return view('admin.profile.edit')->with('fail','Profile not updated ');
            }
    }
    
     // wallet address

    public function wallet(Request $request): View
    {
        return view('user.wallet', [
            'user' => $request->user(),
        ]);
        // return view('user.profile-component');
    }
}