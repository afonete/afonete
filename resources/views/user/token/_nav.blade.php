@php $active = $active ?? ''; @endphp
<div class="mb-3">
    <h2 class="mb-1"><i class="fas fa-coins text-warning mr-2"></i>Token Wallets</h2>
    <div class="d-flex flex-wrap mt-2" style="gap:6px;">
        {{-- 1. Locked Token — info only, no withdrawal --}}
        <a href="{{ route('user.token.locked') }}"
           class="btn btn-sm {{ $active=='locked' ? 'btn-secondary' : 'btn-outline-secondary' }}">
            <i class="fas fa-lock mr-1"></i> Locked Token
        </a>
        {{-- 2. Available Token — transfer to Free Token --}}
        <a href="{{ route('user.token.available') }}"
           class="btn btn-sm {{ $active=='available' ? 'btn-success' : 'btn-outline-success' }}">
            <i class="fas fa-check-circle mr-1"></i> Available Token
        </a>
        {{-- 3. Free Token actions --}}
        <a href="{{ route('user.token.transfer') }}"
           class="btn btn-sm {{ $active=='transfer' ? 'btn-primary' : 'btn-outline-primary' }}">
            <i class="fas fa-paper-plane mr-1"></i> Transfer
        </a>
        <a href="{{ route('user.token.swap') }}"
           class="btn btn-sm {{ $active=='swap' ? 'btn-warning' : 'btn-outline-warning' }}">
            <i class="fas fa-sync-alt mr-1"></i> Swap
        </a>
        <a href="{{ route('user.token.withdraw') }}"
           class="btn btn-sm {{ $active=='withdraw' ? 'btn-danger' : 'btn-outline-danger' }}">
            <i class="fas fa-arrow-up mr-1"></i> Withdraw
        </a>
    </div>
    <small class="text-muted d-block mt-1">
        Locked → (auto on expiry) → Available → (manual transfer) → Free Token → Transfer / Swap / Withdraw
    </small>
</div>
