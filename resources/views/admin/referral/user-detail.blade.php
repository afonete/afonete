@include('admin.admin-base')
<div class="content-wrapper">
<div class="container-fluid py-4">

    <a href="{{ route('admin.referral.bonuses') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
    <h3 class="font-weight-bold">
        <i class="fas fa-user text-primary mr-2"></i>
        {{ $user->name }} <small class="text-muted">({{ $user->email }})</small>
    </h3>

    {{-- ── Totals ── --}}
    <div class="row mt-2">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Total Earned</small>
                <div class="font-weight-bold text-primary" style="font-size:1.5rem;">${{ number_format($totals['total'], 2) }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Pending</small>
                <div class="font-weight-bold text-warning" style="font-size:1.5rem;">${{ number_format($totals['pending'], 2) }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Withdrawable</small>
                <div class="font-weight-bold text-success" style="font-size:1.5rem;">${{ number_format($totals['withdrawable'], 2) }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Already Withdrawn</small>
                <div class="font-weight-bold text-info" style="font-size:1.5rem;">${{ number_format($totals['lifetime_withdrawn'], 2) }}</div>
            </div></div>
        </div>
    </div>

    {{-- ── Bonus rows ── --}}
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-list mr-1"></i> Bonus Rows</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Date</th><th>Source</th><th>From User</th><th>Level</th>
                            <th>Pkg Amt</th><th>Bonus</th><th>Withdrawable On</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $r)
                            <tr>
                                <td><small>{{ $r->created_at->format('d M Y H:i') }}</small></td>
                                <td>{!! $r->sourceLabel() !!}</td>
                                <td>{{ $r->sourceUser->name ?? '—' }} <small class="text-muted">({{ $r->sourceUser->email ?? '—' }})</small></td>
                                <td>
                                    @if($r->level > 0)
                                        <span class="badge badge-primary">L{{ $r->level }} · {{ rtrim(rtrim(number_format($r->percentage, 2), '0'), '.') }}%</span>
                                    @else — @endif
                                </td>
                                <td>${{ number_format($r->source_amount, 2) }}</td>
                                <td class="font-weight-bold text-success">${{ number_format($r->bonus_amount, 4) }}</td>
                                <td><small>{{ $r->week_start->format('d M Y') }}</small></td>
                                <td><span class="badge badge-{{ match($r->status){'withdrawn'=>'success','withdrawable'=>'info','pending'=>'warning','reversed'=>'danger',default=>'secondary'} }}">{{ ucfirst($r->status) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted p-3">No bonus rows.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $rows->links() }}</div>
        </div>
    </div>

    {{-- ── Investments ── --}}
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold">
            <i class="fas fa-box-open mr-1"></i> All Investments ({{ $investments->count() }})
        </div>
        <div class="card-body p-0">
            @if($investments->isEmpty())
                <p class="text-muted p-3 mb-0">No investments yet.</p>
            @else
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th><th>Package</th><th>Category</th><th>Amount</th>
                            <th>Paid</th><th>Status</th><th>Expires</th><th>Bought On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($investments as $inv)
                            @php
                                $expired = $inv->is_expired;
                                $paidOk  = (int) $inv->status === 1;
                                $badgeClass = $expired ? 'secondary' : ($paidOk ? 'success' : 'warning');
                                $badgeText  = $expired ? 'Expired' : ($paidOk ? 'Active' : 'Pending');
                            @endphp
                            <tr>
                                <td>{{ $inv->id }}</td>
                                <td><strong>{{ $inv->package }}</strong></td>
                                <td><span class="badge badge-{{ strtoupper($inv->category) === 'VENTURE' ? 'primary' : 'info' }}">{{ strtoupper($inv->category ?? '—') }}</span></td>
                                <td>${{ number_format($inv->amount, 2) }}</td>
                                <td>${{ number_format($inv->paid ?? 0, 2) }}</td>
                                <td><span class="badge badge-{{ $badgeClass }}">{{ $badgeText }}</span></td>
                                <td><small>{{ $inv->expiration_date ? \Carbon\Carbon::parse($inv->expiration_date)->format('d M Y') : '—' }}</small></td>
                                <td><small>{{ \Carbon\Carbon::parse($inv->created_at)->format('d M Y') }}</small></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- ── Weekly withdrawals ── --}}
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-money-bill-wave mr-1"></i> Weekly Withdrawals</div>
        <div class="card-body p-0">
            @if($weekly->isEmpty())
                <p class="text-muted p-3 mb-0">No weekly withdrawals.</p>
            @else
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr><th>Week</th><th>Amount</th><th>Ref</th><th>Status</th><th>Processed</th><th>Notes</th></tr>
                    </thead>
                    <tbody>
                        @foreach($weekly as $w)
                            <tr>
                                <td>{{ $w->week_start->format('d M Y') }}</td>
                                <td class="font-weight-bold">${{ number_format($w->amount, 2) }}</td>
                                <td><small>{{ $w->transaction_no }}</small></td>
                                <td>{!! $w->statusBadge() !!}</td>
                                <td><small>{{ optional($w->processed_at)->format('d M Y H:i') ?? '—' }}</small></td>
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
