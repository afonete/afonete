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
        $user = $request->user();

        // Validate PIN
        if (!$request->filled('pin') || $request->pin !== $user->email_verification_pin || empty($user->email_verification_pin)) {
            return back()->withInput()->withErrors(['password' => 'The email verification PIN is incorrect or expired. Please click "Send PIN" to get a new one.']);
        }

        $validated = $request->validate([
            'current_password' => ['required', 'password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
            'email_verification_pin' => null, // Clear PIN after use
        ]);

        return redirect()->route('password.show')->with('status', 'password-updated, Changed well');
    }

    public function sendPasswordPin(Request $request)
    {
        $user = Auth::user();
        
        // Generate random 6-digit PIN
        $pin = (string) rand(100000, 999999);
        $user->email_verification_pin = $pin;
        $user->save();

        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\VerificationPinEmail($user->name, $pin));
            return response()->json(['ok' => true, 'message' => 'Verification PIN has been sent to your email.']);
        } catch (\Throwable $e) {
            \Log::error('Could not send password change PIN email: ' . $e->getMessage());
            return response()->json(['ok' => false, 'message' => 'Could not send the email. Please try again later.'], 500);
        }
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

    /**
     * Update the user's second transaction password.
     * This password is separate from the normal login password and is required
     * before withdrawals/transfers/token actions can be submitted.
     */
    public function updateTransactionPassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Validate PIN
        if (!$request->filled('pin') || $request->pin !== $user->email_verification_pin || empty($user->email_verification_pin)) {
            return back()->withInput()->withErrors(['transaction_password' => 'The email verification PIN is incorrect or expired. Please click "Send PIN" to get a new one.']);
        }

        $validated = $request->validate([
            'current_password' => ['required', 'password'],
            'transaction_password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'transaction_password' => Hash::make($validated['transaction_password']),
            'transaction_password_set_at' => now(),
        ]);

        return redirect()->route('password.show')->with('status', 'Second transaction password updated successfully.');
    }

    /**
     * AJAX check used by the transaction-password popup.
     * It returns JSON instead of redirecting, so the popup can show errors immediately.
     */
    public function verifyTransactionPassword(Request $request)
    {
        $user = $request->user();
        $password = (string) $request->input('transaction_password', '');

        if (! $user) {
            return response()->json(['ok' => false, 'message' => 'Please login first.'], 401);
        }

        if (empty($user->transaction_password)) {
            return response()->json([
                'ok' => false,
                'message' => 'Please set your second transaction password first from /user/password.',
            ], 200);
        }

        if ($password === '') {
            return response()->json(['ok' => false, 'message' => 'Second transaction password is required.'], 200);
        }

        if (! Hash::check($password, $user->transaction_password)) {
            return response()->json(['ok' => false, 'message' => 'Second transaction password is wrong.'], 200);
        }

        return response()->json(['ok' => true, 'message' => 'Transaction password verified.']);
    }

}
