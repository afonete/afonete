@php
    $isCash    = request()->routeIs('user.internal-exchange');
    $isCoin    = request()->routeIs('user.dashboard.payments') && request()->query('tab') !== 'trading';
    $isTrading = request()->routeIs('user.dashboard.payments') && request()->query('tab') === 'trading';
@endphp

<style>
    .payment-nav .nav-link {
        color: #64748b;
        font-weight: 600;
        font-size: 0.95rem;
        padding: 10px 18px;
        border-radius: 8px;
        background: transparent;
        transition: all 0.15s ease;
    }
    .payment-nav .nav-link:hover {
        color: #1e293b;
        background: #f1f5f9;
        text-decoration: none;
    }
    .payment-nav .nav-link.active {
        color: #b45309;
        background: #fef3c7;
    }
    .payment-nav .nav-link.disabled-lnk {
        color: #cbd5e1;
        cursor: not-allowed;
        pointer-events: none;
    }
    .payment-nav .nav-sep {
        color: #cbd5e1;
        padding: 10px 4px;
        user-select: none;
    }
</style>

<ul class="nav payment-nav mb-0 d-flex align-items-center bg-white px-3 py-2 shadow-sm" style="border-radius: 12px; border: 1px solid #e2e8f0;">
    <li class="nav-item">
        <a class="nav-link {{ $isCash ? 'active' : '' }}" href="{{ route('user.internal-exchange') }}">
            Cash Account
        </a>
    </li>
    <li class="nav-sep">|</li>
    <li class="nav-item">
        <a class="nav-link {{ $isCoin ? 'active' : '' }}" href="{{ route('user.dashboard.payments') }}">
            Coin Account
        </a>
    </li>
    <li class="nav-sep">|</li>
    <li class="nav-item">
        <a class="nav-link {{ $isTrading ? 'active' : '' }}" href="{{ route('user.dashboard.payments') }}?tab=trading">
            Trading Account
        </a>
    </li>
    <li class="nav-sep">|</li>
    <li class="nav-item">
        <span class="nav-link disabled-lnk" title="Coming soon">My Invoices</span>
    </li>
</ul>
