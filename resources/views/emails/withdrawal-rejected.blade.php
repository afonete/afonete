@component('mail::message')
# ❌ Withdrawal Rejected

Hello {{ $user->name }},

Unfortunately, your withdrawal request has been rejected.

| | |
|--|--|
| **Amount** | ${{ number_format($amount, 2) }} USDT |
| **Reference** | {{ $transactionNo }} |
| **Reason** | {{ $reason }} |

The requested amount has been **refunded** back to your CASHOUT wallet balance. You can submit a new request with corrected details.

If you have questions, please contact support.

Thanks,
{{ config('app.name') }} Team
@endcomponent
