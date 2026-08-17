<div class="wrapper">
@include('user.user-dashboard-base')
<div class="content-wrapper">
<div class="container-fluid py-4">

    <h3 class="font-weight-bold mb-1"><i class="fas fa-briefcase text-primary mr-2"></i> Package Portfolio</h3>
    <p class="text-muted mb-3" style="font-size: 0.9rem;">All packages on your account — UVP investments and FOM Licence Miner packages, each shown separately.</p>

    {{-- ── Summary cards ── --}}
    <div class="row">
        <div class="col-md-3 col-6 mb-2">
            <div class="card shadow-sm border-0 text-center h-100"><div class="card-body">
                <small class="text-muted d-block"><span class="badge badge-primary" style="font-size: 0.6rem;">UVP</span> Total Packages</small>
                <div class="font-weight-bold text-primary" style="font-size:1.6rem;">{{ $totals['uvp_count'] }}</div>
                <small class="text-muted">{{ $totals['uvp_active_count'] }} active</small>
            </div></div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="card shadow-sm border-0 text-center h-100"><div class="card-body">
                <small class="text-muted d-block"><span class="badge badge-primary" style="font-size: 0.6rem;">UVP</span> Active Amount</small>
                <div class="font-weight-bold text-info" style="font-size:1.6rem;">${{ number_format($totals['uvp_active_amount'], 2) }}</div>
                <small class="text-muted">currently invested</small>
            </div></div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="card shadow-sm border-0 text-center h-100"><div class="card-body">
                <small class="text-muted d-block"><span class="badge badge-warning text-dark" style="font-size: 0.6rem;">FOM</span> Total Licences</small>
                <div class="font-weight-bold text-warning" style="font-size:1.6rem;">{{ $totals['fom_count'] }}</div>
                <small class="text-muted">{{ $totals['fom_active_count'] }} active</small>
            </div></div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="card shadow-sm border-0 text-center h-100"><div class="card-body">
                <small class="text-muted d-block"><span class="badge badge-warning text-dark" style="font-size: 0.6rem;">FOM</span> Active Amount</small>
                <div class="font-weight-bold text-success" style="font-size:1.6rem;">${{ number_format($totals['fom_active_amount'], 2) }}</div>
                <small class="text-muted">licence value</small>
            </div></div>
        </div>
    </div>

    {{-- ── Quick links ── --}}
    <div class="d-flex flex-wrap mt-2 mb-3" style="gap: 8px;">
        <a href="{{ route('user.venture') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Buy UVP Package
        </a>
        <a href="{{ route('user.buypackage') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-shopping-cart mr-1"></i> Browse Available Packages
        </a>
        <a href="{{ route('user.investments') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-box-open mr-1"></i> My Investments (UVP)
        </a>
        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
        </a>
    </div>

    {{-- ── UVP packages table ── --}}
    <div class="card shadow-sm border-0 mt-2">
        <div class="card-header bg-light font-weight-bold">
            <span class="badge badge-primary mr-1">UVP</span> My UVP / Investment Packages
        </div>
        <div class="card-body p-0">
            @if($uvpPackages->isEmpty())
                <p class="text-muted p-3 mb-0">You don't have any UVP packages yet.</p>
            @else
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Package</th>
                            <th>Amount Paid</th>
                            <th>Purchased</th>
                            <th>Expires</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($uvpPackages as $p)
                            <tr>
                                <td>{{ $p->id }}</td>
                                <td class="font-weight-bold">{{ strtoupper(trim((string) ($p->category ?: $p->package ?: 'VENTURE'))) }}</td>
                                <td>${{ number_format((float) ($p->paid ?? $p->amount ?? 0), 2) }}</td>
                                <td><small>{{ $p->created_at ? $p->created_at->format('M d, Y') : '—' }}</small></td>
                                <td><small>{{ $p->expiration_date ? \Carbon\Carbon::parse($p->expiration_date)->format('M d, Y') : '—' }}</small></td>
                                <td>
                                    @if(!$p->is_expired && (string) $p->status === '1')
                                        <span class="badge badge-success">ACTIVE</span>
                                    @elseif($p->is_expired)
                                        <span class="badge badge-secondary">EXPIRED</span>
                                    @else
                                        <span class="badge badge-warning text-dark">PENDING</span>
                                    @endif
                                </td>
                                <td class="text-right pr-3">
                                    <a href="{{ route('user.investments.show', $p->id) }}" class="btn btn-outline-primary btn-sm py-0" style="font-size: 0.75rem;">Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-3 py-2">
                {{ $uvpPackages->appends(request()->except('uvp_page'))->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- ── FOM Licence Miner packages table ── --}}
    <div class="card shadow-sm border-0 mt-3 mb-4">
        <div class="card-header bg-light font-weight-bold">
            <span class="badge badge-warning text-dark mr-1">FOM</span> My FOM Licence Miner Packages
        </div>
        <div class="card-body p-0">
            @if($fomPackages->isEmpty())
                <p class="text-muted p-3 mb-0">You don't have any FOM Licence Miner packages yet.</p>
            @else
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Package</th>
                            <th>Amount Paid</th>
                            <th>Purchased</th>
                            <th>Expires</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fomPackages as $p)
                            <tr>
                                <td>{{ $p->id }}</td>
                                <td class="font-weight-bold">{{ strtoupper(trim((string) ($p->category ?: $p->package ?: 'FOM'))) }} <small class="text-muted">Licence Miner</small></td>
                                <td>${{ number_format((float) ($p->paid ?? $p->amount ?? 0), 2) }}</td>
                                <td><small>{{ $p->created_at ? $p->created_at->format('M d, Y') : '—' }}</small></td>
                                <td><small>{{ $p->expiration_date ? \Carbon\Carbon::parse($p->expiration_date)->format('M d, Y') : '—' }}</small></td>
                                <td>
                                    @if(!$p->is_expired && (string) $p->status === '1')
                                        <span class="badge badge-success">ACTIVE</span>
                                    @elseif($p->is_expired)
                                        <span class="badge badge-secondary">EXPIRED</span>
                                    @else
                                        <span class="badge badge-warning text-dark">PENDING</span>
                                    @endif
                                </td>
                                <td class="text-right pr-3">
                                    <a href="{{ route('user.licence-miner.escrow.package', ['key' => $p->payable_id ?: ($p->category ?: $p->package ?: 'FOM')]) }}" class="btn btn-outline-primary btn-sm py-0" style="font-size: 0.75rem;">Escrow Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-3 py-2">
                {{ $fomPackages->appends(request()->except('fom_page'))->links() }}
            </div>
            @endif
        </div>
    </div>

</div>
</div>
@include('user.footer')
</div>
