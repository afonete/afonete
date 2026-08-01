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
use App\Models\Teams;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'user' => ['required', 'string', 'max:10', 'min:4', 'unique:users'],
            'phone' => [
                'required',
                'regex:/^([0-9\s\-\+\(\)]*)$/',
                function ($attribute, $value, $fail) use ($request) {
                    $phoneUtil = PhoneNumberUtil::getInstance();
                    try {
                        $phoneNumber = $phoneUtil->parse($value, $request->country);
                        if (!$phoneUtil->isValidNumber($phoneNumber)) {
                            $fail('The phone number is not valid for the selected country.');
                        }
                    } catch (NumberParseException $e) {
                        $fail('The phone number format is invalid.');
                    }
                }
            ],

            // 'country' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);


        $refferal = 0;
        $referedBy = $request->referee_id;
        global $father;

        $exist = User::where('user', $request->user)->exists();
        if ($exist) {
            return view('auth.register')->with('exist', 'username has been taken, choose another');
        }



        if (!empty($request->referee_id) && $request->referee_id != '') {
            $refereeInput = trim((string) $request->referee_id);
            $indirect = null;

            // Handle legacy REF-20-d274ac format if someone clicks an old link
            if (str_starts_with(strtoupper($refereeInput), 'REF-')) {
                $parts = explode('-', $refereeInput);
                if (count($parts) >= 2) {
                    $base36Id = $parts[1];
                    $decodedId = base_convert(strtolower($base36Id), 36, 10);
                    $indirect = User::find($decodedId);
                }
            }

            if (!$indirect) {
                $indirect = User::where('activation', $refereeInput)
                    ->orWhere('user', $refereeInput)
                    ->orWhere('id', $refereeInput)
                    ->orWhere('transfer_code', $refereeInput)
                    ->first();
            }

            if (!$indirect) {
                return Redirect::back()->withErrors(['ref' => 'Invalid referee referral code. Please check and try again.']);
            }

            // ALWAYS link referee_id to the referrer's user ID so referral bonuses generate
            $refferal = $indirect->id;

            $user = User::create([
                'name'        => $request->name,
                'email'       => $request->email,
                'password'    => Hash::make($request->password),
                'phone'       => $request->phone,
                'user'        => $request->user,
                'country'     => $request->country,
                'activation'  => rand(1111111, 9999999),
                'referee_id'  => $refferal,
                'father'      => null,
                'has_request' => 'registed',
            ]);

            // Determine team placement side
            $reqSide = strtoupper(trim((string) $request->side));
            if ($reqSide === 'LEFT' || $reqSide === 'RIGHT') {
                $placedSide = $reqSide;
            } else {
                // Main link without side parameter: auto-balance side placement
                $leftCount  = Teams::where('user_id', $indirect->id)->where('side', 'LEFT')->count();
                $rightCount = Teams::where('user_id', $indirect->id)->where('side', 'RIGHT')->count();
                $placedSide = ($leftCount <= $rightCount) ? 'LEFT' : 'RIGHT';
            }

            Teams::create([
                'user_id'      => $indirect->id,
                'team_user_id' => $user->id,
                'side'         => $placedSide,
            ]);

            event(new Registered($user));
            Auth::login($user);
            return redirect(RouteServiceProvider::HOME);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'user' => $request->user,
            'country' => $request->country,
            'activation' => rand(1111111, 9999999),
            'referee_id' => $refferal,
            'father' => null,
            'has_request' => 'registed',
        ]);



        event(new Registered($user));
        Auth::login($user);
        return redirect(RouteServiceProvider::HOME);
    }
}
