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
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('users')) {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'city')) {
                try { \Illuminate\Support\Facades\Schema::table('users', function ($table) { $table->string('city', 100)->nullable(); }); } catch (\Throwable $e) {}
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'address')) {
                try { \Illuminate\Support\Facades\Schema::table('users', function ($table) { $table->text('address')->nullable(); }); } catch (\Throwable $e) {}
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'dob')) {
                try { \Illuminate\Support\Facades\Schema::table('users', function ($table) { $table->date('dob')->nullable(); }); } catch (\Throwable $e) {}
            }
        }

        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'city'    => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'dob'     => 'nullable|date',
        ]);

        $user->name    = $request->input('name');
        $user->phone   = $request->input('phone');
        if ($request->has('country')) $user->country = $request->input('country');
        if ($request->has('city'))    $user->city    = $request->input('city');
        if ($request->has('address')) $user->address = $request->input('address');
        if ($request->has('dob'))     $user->dob     = $request->input('dob');

        if ($user->save()) {
            return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
        } else {
            return redirect()->route('profile.edit')->with('fail', 'Profile update failed.');
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