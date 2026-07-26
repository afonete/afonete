@include('user.user-dashboard-base')
<div class="content-wrapper">
<div class="container-fluid py-4">

    <a href="{{ route('user.dashboard.deposit') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fas fa-arrow-left mr-1"></i> Back to Deposit
    </a>

    <h3 class="font-weight-bold mb-3"><i class="fas fa-history text-primary mr-2"></i> My Deposit History</h3>

    {{-- ── Totals ── --}}
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Pending</small>
                <div class="font-weight-bold text-warning" style="font-size:1.4rem;">${{ number_format($totals['pending'], 2) }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Approved (total)</small>
                <div class="font-weight-bold text-success" style="font-size:1.4rem;">${{ number_format($totals['approved'], 2) }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Available Balance</small>
                <div class="font-weight-bold text-primary" style="font-size:1.4rem;">${{ number_format($totals['available'], 2) }}</div>
                <small class="text-muted">approved − used</small>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Used for Packages</small>
                <div class="font-weight-bold text-info" style="font-size:1.4rem;">${{ number_format($totals['used'], 2) }}</div>
            </div></div>
        </div>
    </div>

    {{-- ── Filters ── --}}
    <form method="GET" class="mt-3">
        <div class="btn-group" role="group">
            <a href="{{ route('user.deposits.history') }}" class="btn btn-sm btn-{{ !request('status') ? 'primary' : 'outline-primary' }}">All</a>
            <a href="{{ route('user.deposits.history', ['status'=>'pending']) }}" class="btn btn-sm btn-{{ request('status')==='pending' ? 'warning' : 'outline-warning' }}">Pending</a>
            <a href="{{ route('user.deposits.history', ['status'=>'approved']) }}" class="btn btn-sm btn-{{ request('status')==='approved' ? 'success' : 'outline-success' }}">Approved</a>
            <a href="{{ route('user.deposits.history', ['status'=>'rejected']) }}" class="btn btn-sm btn-{{ request('status')==='rejected' ? 'danger' : 'outline-danger' }}">Rejected</a>
            <a href="{{ route('user.deposits.history', ['status'=>'used']) }}" class="btn btn-sm btn-{{ request('status')==='used' ? 'info' : 'outline-info' }}">Used</a>
        </div>
    </form>

    {{-- ── Table ── --}}
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-body p-0">
            @if($deposits->isEmpty())
                <p class="text-muted p-3 mb-0">No deposits yet.</p>
            @else
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Ref</th><th>Date</th><th>Method</th><th>Network</th>
                            <th>Amount</th><th>Used</th><th>Status</th><th>Your Wallet</th>
                            <th>Proof</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deposits as $d)
                            @php
                                $statusBadge = match($d->status) {
                                    'approved'  => 'success',
                                    'pending'   => 'warning',
                                    'rejected'  => 'danger',
                                    'cancelled' => 'secondary',
                                    'used'      => 'info',
                                    'under-review' => 'secondary',
                                    default     => 'secondary',
                                };
                            @endphp
                            <tr>
                                <td><small>{{ $d->transaction_id }}</small></td>
                                <td><small>{{ $d->created_at->format('d M Y H:i') }}</small></td>
                                <td>{{ $d->deposit_method }}</td>
                                <td>{{ $d->network ?? '—' }}</td>
                                <td class="font-weight-bold">${{ number_format($d->amount_deposited, 2) }}</td>
                                <td>${{ number_format($d->amount_removed, 2) }}</td>
                                <td><span class="badge badge-{{ $statusBadge }}">{{ ucfirst($d->status) }}</span></td>
                                <td><small class="text-muted">{{ Str::limit($d->user_wallet_address ?? '—', 14) }}</small></td>
                                <td>
                                    @if($d->proof_of_payment)
                                        <a href="{{ asset('storage/' . $d->proof_of_payment) }}" target="_blank"
                                           class="btn btn-sm btn-outline-info">View</a>
                                    @else — @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 d-flex justify-content-center border-top">
                {{ $deposits->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>

</div>
</div>
