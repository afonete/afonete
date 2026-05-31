<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{


public function show()
{
    return view("user.profile-component")->with('show','show');
}
public function ad_show()
{
    return view("admin.profile-component")->with('show','show');
}

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'current_password' => ['required', 'password'],
        'password' => ['required', 'confirmed',Password::defaults()],
    ]);

    $request->user()->update([
        'password' => Hash::make($validated['password']),
    ]);

    return redirect()->route('password.show')->with('status', 'password-updated, Changed well');

    // $user=Auth::user();
        // $userId = $user->id;
        // $user = User::find($userId);
        
        // $user->password=Hash::make($validated['password']);
        // if ($user->save()) {
        //     return back()->with('status', 'password changed');

        // } else {
        //         return back()->with('status', 'failed-updated');

        // }
        
}
    public function ad_update(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'current_password' => ['required', 'password'],
        'password' => ['required', 'confirmed',Password::defaults()],
    ]);

    $request->user()->update([
        'password' => Hash::make($validated['password']),
    ]);

    return redirect()->route('admin.password.show')->with('status', 'password-updated, Changed well');

    // $user=Auth::user();
        // $userId = $user->id;
        // $user = User::find($userId);
        
        // $user->password=Hash::make($validated['password']);
        // if ($user->save()) {
        //     return back()->with('status', 'password changed');

        // } else {
        //         return back()->with('status', 'failed-updated');

        // }
        
}

}
