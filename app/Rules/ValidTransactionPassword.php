<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class ValidTransactionPassword implements Rule
{
    protected ?User $user;
    protected string $message = 'Invalid transaction password.';

    public function __construct(?User $user)
    {
        $this->user = $user;
    }

    public function passes($attribute, $value): bool
    {
        if (! $this->user) {
            $this->message = 'Please login first.';
            return false;
        }

        if (empty($this->user->transaction_password)) {
            $this->message = 'Please set your second transaction password first from /user/password.';
            return false;
        }

        if (! Hash::check((string) $value, $this->user->transaction_password)) {
            $this->message = 'Invalid second transaction password.';
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return $this->message;
    }
}
