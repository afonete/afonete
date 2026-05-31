<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function passwordRules(): array
    {
        return ['required', 'string', new Password, 'confirmed',min(8)];
        // Require at least one uppercase character...

        (new Password)->requireUppercase();

        // Require at least one numeric character...
        (new Password)->requireNumeric();
    }
}