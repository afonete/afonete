@component('mail::message')
# 🔔 New Manual Withdrawal Request

**User:** {{ $user->name }} ({{ $user->email }})  
**Amount:** ${{ number_format($amount, 2) }} USDT  
**Wallet:** {{ $address }}  
**Reference:** {{ $transactionNo }}  
**Submitted:** {{ now()->format('d M Y H:i') }}

Please review and approve/reject in the admin panel.

@component('mail::button', ['url' => url('/admin/withdrawal')])
Review Withdrawal
@endcomponent
@endcomponent
