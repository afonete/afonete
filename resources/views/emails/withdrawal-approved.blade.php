@component('mail::message')
# ✅ Withdrawal Approved

Hello {{ $user->name }},

Your withdrawal request has been approved and processed.

| | |
|--|--|
| **Amount** | ${{ number_format($amount, 2) }} USDT |
| **Reference** | {{ $transactionNo }} |
| **Transaction Hash** | {{ $txnHash ?? 'Pending on-chain confirmation' }} |
| **Date** | {{ now()->format('d M Y H:i') }} |

@if($txnHash)
You can track the transfer on the blockchain using the hash above.
@else
You will receive a follow-up email with the on-chain hash once the transfer confirms.
@endif

Thanks,
{{ config('app.name') }} Team
@endcomponent
