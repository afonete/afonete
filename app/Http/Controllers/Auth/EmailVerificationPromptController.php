<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;
//use Illuminate\Routing\RouteServiceProvider;
error_reporting(0);
class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if ($user && !$user->hasVerifiedEmail()) {
            // Automatically generate & email the verification PIN the first time
            // the user reaches this page, so the PIN is ready immediately — without
            // them having to click "Resend". We only send when no PIN exists yet to
            // avoid spamming on every page refresh.
            if (empty($user->email_verification_pin)) {
                $pin = (string) rand(100000, 999999);
                $user->email_verification_pin = $pin;
                $user->save();

                try {
                    \Illuminate\Support\Facades\Mail::to($user->email)
                        ->send(new \App\Mail\VerificationPinEmail($user->name, $pin));
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('Could not send verification PIN email on verify-email prompt: ' . $e->getMessage());
                }
            }

            return view('auth.verify-email');
        }

        return Redirect::intended(RouteServiceProvider::HOME);
    }
    
} 