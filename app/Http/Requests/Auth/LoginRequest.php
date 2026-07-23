<?php

namespace App\Http\Requests\Auth;

use App\Models\TeamLeader;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();
        $login_input = $this->input('login');

    // Determine the login type
        if (filter_var($login_input, FILTER_VALIDATE_EMAIL)) {
            $login_type = 'email';
        } elseif (preg_match('/^\+?[0-9]{7,15}$/', $login_input)) { // Example regex for phone numbers
            $login_type = 'phone';
        } else {
            $login_type = 'user';
        }

        $this->merge([
            $login_type => $this->input('login')
        ]);

        if (! Auth::attempt($this->only($login_type, 'password'), $this->boolean('remember'))) {
                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'login' => trans('auth.failed'),
                ]);
        }

        // Check if the user exists in the TeamLeader table
        $teamLeader = TeamLeader::where('User_name', $this->input('login'))
            ->orWhere('Email', $this->input('login'))
            ->orWhere('Phone', $this->input('login'))
            ->first();


        if ($teamLeader) {
            \Log::info($teamLeader);
            if ($teamLeader->status == 'pending') {

                \Log::info("PENDING");
                // Logout the user and redirect to the "leader pending" page
//                Auth::logout();
                return redirect()->to(url('/team-leader/pending-approval?username='.$teamLeader->User_name));
            }

            if ($teamLeader->status === 'rejected') {

                \Log::info("REJECTED");
                // Logout the user and redirect to the "leader rejected" page
//                Auth::logout();
                return redirect()->to(url('/team-leader/rejected?username='.$teamLeader->User_name));

            }
            return redirect()->to(url('team-leader/all'));
        }


        \Log::info("HEREE");
        RateLimiter::clear($this->throttleKey());

    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('email')).'|'.$this->ip();
    }
}
