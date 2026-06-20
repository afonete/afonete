@extends('admin.sidebar')
@section('contents')
<div class="container-fluid py-4 px-4">

    <a href="{{ route('admin.referral.bonuses') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>

    <h3 class="font-weight-bold mb-2">
        <i class="fas fa-wallet text-warning mr-2"></i> Deposit Wallets &amp; Accounts
    </h3>
    <p class="text-muted">
        Configure every deposit option shown on the user deposit page.
        For crypto, set the wallet address + network. For Advcash / Perfect Money, set the account number.
    </p>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.deposit-wallets.update') }}">
        @csrf
        @method('PUT')

        {{-- ─────────────── Section: Crypto ─────────────── --}}
        <h4 class="text-uppercase text-muted mt-4 mb-2">
            <i class="fab fa-bitcoin mr-1"></i> Crypto Wallets
        </h4>
        @foreach(($wallets['crypto'] ?? collect()) as $w)
            @include('admin.settings._wallet_card', ['w' => $w, 'showNetwork' => true])
        @endforeach

        {{-- ─────────────── Section: Advcash ─────────────── --}}
        <h4 class="text-uppercase text-muted mt-4 mb-2">
            <i class="fas fa-money-bill-wave mr-1"></i> Advcash
        </h4>
        @foreach(($wallets['advcash'] ?? collect()) as $w)
            @include('admin.settings._wallet_card', ['w' => $w, 'showNetwork' => false])
        @endforeach

        {{-- ─────────────── Section: Perfect Money ─────────────── --}}
        <h4 class="text-uppercase text-muted mt-4 mb-2">
            <i class="fas fa-coins mr-1"></i> Perfect Money
        </h4>
        @foreach(($wallets['perfect_money'] ?? collect()) as $w)
            @include('admin.settings._wallet_card', ['w' => $w, 'showNetwork' => false])
        @endforeach

        <div class="text-right mt-4">
            <button type="submit" class="btn btn-success btn-lg font-weight-bold">
                <i class="fas fa-save mr-1"></i> Save All Wallets
            </button>
        </div>
    </form>

</div>
@endsection
