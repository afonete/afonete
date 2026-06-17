@include('admin.admin-base')
<div class="content-wrapper">
<div class="container-fluid py-4">

    <a href="{{ route('admin.referral.bonuses') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
    <h3 class="font-weight-bold"><i class="fas fa-money-bill-wave text-warning mr-2"></i> Weekly Withdrawal Queue</h3>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    {{-- ── PENDING ── --}}
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-warning text-dark font-weight-bold">
            <i class="fas fa-hourglass-half mr-1"></i>
            Pending ({{ $pending->count() }})
        </div>
        <div class="card-body p-0">
            @if($pending->isEmpty())
                <p class="text-muted p-3 mb-0">No pending withdrawals.</p>
            @else
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr><th>User</th><th>Email</th><th>Week</th><th>Amount</th><th>Ref</th><th>Submitted</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        @foreach($pending as $w)
                            <tr>
                                <td><strong>{{ $w->user->name }}</strong></td>
                                <td><small>{{ $w->user->email }}</small></td>
                                <td>{{ $w->week_start->format('d M Y') }}</td>
                                <td class="font-weight-bold text-success">${{ number_format($w->amount, 2) }}</td>
                                <td><small>{{ $w->transaction_no }}</small></td>
                                <td><small>{{ $w->created_at->format('d M Y H:i') }}</small></td>
                                <td>
                                    <form method="POST" action="{{ route('admin.referral.withdrawals.approve', $w->id) }}" class="d-inline">
                                        @csrf
                                        <input name="admin_notes" class="form-control form-control-sm d-inline-block" style="width:160px" placeholder="Notes (optional)">
                                        <button name="mark_paid" value="1" class="btn btn-sm btn-success"><i class="fas fa-check"></i> Pay</button>
                                        <button name="mark_paid" value="0" class="btn btn-sm btn-info"><i class="fas fa-thumbs-up"></i> Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.referral.withdrawals.reject', $w->id) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i> Reject</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- ── APPROVED/PAID ── --}}
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Approved / Paid</div>
        <div class="card-body p-0">
            @if($approved->isEmpty())
                <p class="text-muted p-3 mb-0">No history yet.</p>
            @else
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="thead-light">
                        <tr><th>User</th><th>Week</th><th>Amount</th><th>Status</th><th>Processed</th><th>Notes</th></tr>
                    </thead>
                    <tbody>
                        @foreach($approved as $w)
                            <tr>
                                <td>{{ $w->user->name }}</td>
                                <td>{{ $w->week_start->format('d M Y') }}</td>
                                <td>${{ number_format($w->amount, 2) }}</td>
                                <td>{!! $w->statusBadge() !!}</td>
                                <td><small>{{ optional($w->processed_at)->format('d M Y') }}</small></td>
                                <td><small class="text-muted">{{ $w->admin_notes ?? '—' }}</small></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

</div>
</div>
