{{-- ─────────────────────────────────────────────────────────────────────
     Shared Finance section navbar (Bootstrap 4 — Tailwind is NOT loaded).
     Usage:  @include('user.finance-nav', ['active' => 'commission'])
     Keys:   overview | commissions | commission | transaction | subscription
     "commissions" = Commission tab on /user/overview (table on overview page)
     "commission"  = Referral Bonuses page (/user/commission)
     ───────────────────────────────────────────────────────────────────── --}}
@php($active = $active ?? '')
<div class="mb-4">
    <ul class="nav nav-pills flex-column flex-md-row" style="gap: 6px;">
        <li class="nav-item">
            <a href="{{ route('overview') }}"
               class="nav-link font-weight-bold {{ $active === 'overview' ? 'active bg-primary text-white shadow-sm' : 'text-dark bg-white border' }}"
               style="border-radius: 8px;">
                <i class="fas fa-chart-pie mr-1"></i> Overview
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('overview') }}#commissions"
               class="nav-link font-weight-bold {{ $active === 'commissions' ? 'active bg-primary text-white shadow-sm' : 'text-dark bg-white border' }}"
               style="border-radius: 8px;">
                <i class="fas fa-coins mr-1"></i> Commission
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('commission') }}"
               class="nav-link font-weight-bold {{ $active === 'commission' ? 'active bg-primary text-white shadow-sm' : 'text-dark bg-white border' }}"
               style="border-radius: 8px;">
                <i class="fas fa-hand-holding-usd mr-1"></i> Referral Bonuses
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('transaction') }}"
               class="nav-link font-weight-bold {{ $active === 'transaction' ? 'active bg-primary text-white shadow-sm' : 'text-dark bg-white border' }}"
               style="border-radius: 8px;">
                <i class="fas fa-exchange-alt mr-1"></i> Transactions
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('subscription') }}"
               class="nav-link font-weight-bold {{ $active === 'subscription' ? 'active bg-primary text-white shadow-sm' : 'text-dark bg-white border' }}"
               style="border-radius: 8px;">
                <i class="fas fa-box-open mr-1"></i> My Subscriptions
            </a>
        </li>
    </ul>
</div>
