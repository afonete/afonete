<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            // 'referee_id' => 'required',
            'country' => 'required',
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();
  
        $settings = \App\Models\WithdrawalSetting::current();
        $has_free = ($settings && $settings->allow_free_dashboard_access) ? 'yes' : 'no';
        $paid_pkg = ($settings && $settings->allow_free_dashboard_access) ? 'standard' : 'no';
        $contract_signed = ($settings && $settings->allow_free_dashboard_access) ? 'Signed' : '';

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'referee_id' => $input['referee_id'],
            'country' => $input['country'],
            'password' => Hash::make($input['password']),
            'has_free_package' => $has_free,
            'has_paid_package' => $paid_pkg,
            'contract' => $contract_signed,
        ]);
    }
}