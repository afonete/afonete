@include('user.user-dashboard-base')
<div class="content-wrapper">
<div class="container-fluid py-4">

    @include('user.token._nav', ['active' => 'locked'])

    <div class="row mt-3 justify-content-center">
        <div class="col-md-7">

            {{-- Balance card --}}
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-secondary text-white font-weight-bold">
                    <i class="fas fa-lock mr-1"></i> Locked Token Wallet
                </div>
                <div class="card-body text-center py-4">
                    <div class="mb-2" style="font-size:2.5rem; font-weight:bold; color:#495057;">
                        {{ number_format($lockedBal, 0) }}
                    </div>
                    <div class="text-muted">{{ $symbol }} locked</div>

                    @if($package)
                    <div class="mt-3 p-3 rounded" style="background:#f8f9fa; border:1px solid #dee2e6;">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="text-muted small">Package started</div>
                                <div class="font-weight-bold">{{ \Carbon\Carbon::parse($package->created_at)->format('d M Y') }}</div>
                            </div>
                            <div class="col-4">
                                <div class="text-muted small">Releases on</div>
                                <div class="font-weight-bold text-success">{{ \Carbon\Carbon::parse($package->expiration_date)->format('d M Y') }}</div>
                            </div>
                            <div class="col-4">
                                <div class="text-muted small">Days remaining</div>
                                <div class="font-weight-bold text-warning">
                                    {{ max(0, \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($package->expiration_date), false)) }}
                                </div>
                            </div>
                        </div>
                        {{-- Progress bar --}}
                        @php
                            $start   = \Carbon\Carbon::parse($package->created_at);
                            $end     = \Carbon\Carbon::parse($package->expiration_date);
                            $elapsed = $start->diffInDays(\Carbon\Carbon::now());
                            $total   = $start->diffInDays($end);
                            $pct     = $total > 0 ? min(100, round($elapsed / $total * 100)) : 0;
                        @endphp
                        <div class="progress mt-3" style="height:10px;">
                            <div class="progress-bar bg-success" style="width:{{ $pct }}%"></div>
                        </div>
                        <small class="text-muted">{{ $pct }}% of package duration complete</small>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Explanation --}}
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="font-weight-bold mb-3"><i class="fas fa-info-circle text-info mr-2"></i>How Locked Token works</h5>

                    <div class="d-flex mb-3">
                        <div class="mr-3 text-center" style="min-width:40px;">
                            <span class="badge badge-secondary rounded-circle p-2">1</span>
                        </div>
                        <div>
                            <strong>On package purchase</strong>
                            <p class="text-muted mb-0">Your investment amount is converted to tokens at the UVP price and placed in your Locked Token wallet.<br>
                            <small>Example: $1,000 ÷ $0.0025 = <strong>400,000 {{ $symbol }}</strong> locked</small></p>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="mr-3 text-center" style="min-width:40px;">
                            <span class="badge badge-secondary rounded-circle p-2">2</span>
                        </div>
                        <div>
                            <strong>Locked during package duration</strong>
                            <p class="text-muted mb-0">These tokens cannot be withdrawn or moved during the active package period. They are locked until the package expires.</p>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="mr-3 text-center" style="min-width:40px;">
                            <span class="badge badge-success rounded-circle p-2">3</span>
                        </div>
                        <div>
                            <strong>Released automatically on expiry</strong>
                            <p class="text-muted mb-0">When your package duration ends, the system automatically transfers all locked tokens to your <strong>Available Token</strong> wallet.</p>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="mr-3 text-center" style="min-width:40px;">
                            <span class="badge badge-primary rounded-circle p-2">4</span>
                        </div>
                        <div>
                            <strong>Then move to Free Token to use them</strong>
                            <p class="text-muted mb-0">From Available Token, transfer to Free Token wallet. From Free Token you can: transfer to another user, swap for cashout, or withdraw to your FONE wallet.</p>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('user.token.available') }}" class="btn btn-success btn-lg font-weight-bold">
                            <i class="fas fa-check-circle mr-1"></i> Go to Available Token Wallet
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
