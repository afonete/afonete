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
                    
                    
        if ($request->user() && !$request->user()->hasVerifiedEmail()) {
            return view('auth.verify-email');
        }
        return Redirect::intended(RouteServiceProvider::HOME);
    }
    
} 