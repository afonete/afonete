<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        // dd($request);
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }

    // public function send(Request $request)
    // {
    //     if (Auth::user()->hasVerifiedEmail()) {
    //         return response()->json(['message' => 'Email already verified.'], 400);
    //     }

    //     Auth::user()->sendEmailVerificationNotification();

    //     return response()->json(['message' => 'Verification link sent!']);
    // }

}
