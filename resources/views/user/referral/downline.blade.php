@include('user.user-dashboard-base')
<div class="content-wrapper">
<div class="container-fluid py-4">

    @include('user.referral._nav', ['active' => 'downline'])

    {{-- ── Metrics ── --}}
    <div class="row mt-2">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Direct Referrals</small>
                <div class="font-weight-bold" style="font-size:1.6rem;">{{ $directCount }}</div>
                <small class="text-muted">{{ $activeDirect }} active (with package)</small>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Direct Ref Investment</small>
                <div class="font-weight-bold text-primary" style="font-size:1.6rem;">${{ number_format($directInv, 2) }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Total Network Investment</small>
                <div class="font-weight-bold text-success" style="font-size:1.6rem;">${{ number_format($totalInv, 2) }}</div>
                <small class="text-muted">Direct + indirect (all levels)</small>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Your Referral Link</small>
                <input type="text" class="form-control form-control-sm mt-1" readonly
                       value="{{ url('/register?referral=' . (auth()->user()->activation ?? auth()->user()->user ?? auth()->id())) }}">
                <small class="text-muted">Share to invite referrals.</small>
            </div></div>
        </div>
    </div>

    {{-- ── Direct referrals table ── --}}
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-users mr-1"></i> Direct Referrals</div>
        <div class="card-body p-0">
            @if($directs->isEmpty())
                <p class="text-muted p-3 mb-0">No direct referrals yet.</p>
            @else
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr><th>Username</th><th>Email</th><th>Active Package</th><th>Investment</th><th>Joined</th><th>Rank</th></tr>
                    </thead>
                    <tbody>
                        @foreach($directs as $d)
                            @php
                                $active = $d->investments->first();
                                $highest = $d->userRanks->first();
                                // §79: label via packageLabel() — covers UVP,
                                // TEAM_LEADER/SUPER_LEADER (has_paid_package)
                                // and FOM licences; VENTURE-only eager load
                                // missed the last two ("No active package").
                                $pkgLabel = $d->packageLabel();
                                $hasPkg   = $d->hasAnyPackage();
                            @endphp
                            <tr>
                                <td>{{ $d->user ?? $d->name }}</td>
                                <td><small>{{ $d->email }}</small></td>
                                <td>
                                    @if($hasPkg)
                                        <span class="badge {{ str_contains($pkgLabel, '(FOM)') ? 'badge-warning text-dark' : 'badge-success' }}">{{ $pkgLabel }}</span>
                                    @else
                                        <span class="badge badge-secondary">No active package</span>
                                    @endif
                                </td>
                                <td>${{ number_format($active->amount ?? 0, 2) }}</td>
                                <td><small>{{ $d->created_at->format('d M Y') }}</small></td>
                                <td>
                                    @if($highest)
                                        <span class="badge badge-primary">{{ $highest->rank_name }}</span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 d-flex justify-content-center border-top">
                {{ $directs->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>

</div>
</div>
