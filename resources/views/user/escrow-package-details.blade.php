<div class="wrapper">
    @include('user.user-dashboard-base')
    <title>{{ strtoupper($summary->package_name) }} — Escrow Details - Licence Miner</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <div class="content-wrapper" style="background: #0b1220; min-height: 100vh;">
        <div class="container-fluid py-4 px-3 px-md-4">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <div>
                    <h3 class="font-weight-bold text-white mb-1">
                        <i class="fas fa-box-open text-warning me-2"></i>{{ strtoupper($summary->package_name) }} — Escrow Details
                    </h3>
                    <small class="text-muted">
                        @if($summary->activation_id) Activation #{{ $summary->activation_id }} · @endif
                        12-month token release schedule for this package
                    </small>
                </div>
                <a href="{{ route('user.licence-miner.escrow') }}" class="btn btn-outline-light font-weight-bold px-3 py-2" style="border-radius: 8px;">
                    <i class="fas fa-arrow-left me-1"></i> All Packages / Escrow Vault
                </a>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show font-weight-bold shadow-sm mb-4" role="alert" style="border-radius: 10px;">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Summary cards for THIS package --}}
            <div class="row mb-4">
                <div class="col-12 col-md-3 mb-3">
                    <div class="card p-3 shadow-sm h-100 border-0" style="background: #1e293b; border-left: 4px solid #3b82f6 !important; border-radius: 14px;">
                        <span class="text-muted d-block small font-weight-bold text-uppercase">Total Return</span>
                        <h4 class="font-weight-bold text-info mb-0 mt-1">{{ number_format($summary->total_return) }}</h4>
                        <small class="text-muted">{{ $symbol }}</small>
                    </div>
                </div>
                <div class="col-12 col-md-3 mb-3">
                    <div class="card p-3 shadow-sm h-100 border-0" style="background: #1e293b; border-left: 4px solid #10b981 !important; border-radius: 14px;">
                        <span class="text-muted d-block small font-weight-bold text-uppercase">Released to Available</span>
                        <h4 class="font-weight-bold text-success mb-0 mt-1">{{ number_format($summary->released_tokens) }}</h4>
                        <small class="text-muted">{{ $summary->released_count }} / {{ $summary->total_count }} installments</small>
                    </div>
                </div>
                <div class="col-12 col-md-3 mb-3">
                    <div class="card p-3 shadow-sm h-100 border-0" style="background: #1e293b; border-left: 4px solid #f59e0b !important; border-radius: 14px;">
                        <span class="text-muted d-block small font-weight-bold text-uppercase">Still Locked in Escrow</span>
                        <h4 class="font-weight-bold text-warning mb-0 mt-1">{{ number_format($summary->pending_tokens) }}</h4>
                        <small class="text-muted">{{ $symbol }}</small>
                    </div>
                </div>
                <div class="col-12 col-md-3 mb-3">
                    <div class="card p-3 shadow-sm h-100 border-0" style="background: #1e293b; border-left: 4px solid #8b5cf6 !important; border-radius: 14px;">
                        <span class="text-muted d-block small font-weight-bold text-uppercase">Next Release</span>
                        <h4 class="font-weight-bold text-white mb-0 mt-1">
                            @if($summary->is_complete)
                                <span class="text-success">Complete</span>
                            @elseif($summary->next_release)
                                {{ \Carbon\Carbon::parse($summary->next_release)->format('Y-m-d') }}
                            @else
                                —
                            @endif
                        </h4>
                        <small class="text-muted">
                            @if(!$summary->is_complete && $summary->next_release)
                                {{ \Carbon\Carbon::parse($summary->next_release)->diffForHumans() }}
                            @else
                                All 12 installments released
                            @endif
                        </small>
                    </div>
                </div>
            </div>

            {{-- Full 12-installment schedule for this package --}}
            <div class="card border-0 shadow-sm p-4" style="background: #0f172a; border-radius: 14px;">
                <div class="border-bottom border-secondary pb-3 mb-3">
                    <h5 class="font-weight-bold text-white mb-1"><i class="fas fa-calendar-alt text-info me-2"></i>Release Schedule</h5>
                    <p class="text-muted small mb-0">Each installment moves tokens from your Escrow Wallet into Available Tokens automatically on its release date.</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 text-center align-middle" style="background:transparent;">
                        <thead class="bg-dark text-muted uppercase text-xs">
                            <tr>
                                <th class="py-3">Installment #</th>
                                <th class="py-3">Tokens Amount</th>
                                <th class="py-3">Scheduled Release Date</th>
                                <th class="py-3">Released On</th>
                                <th class="py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($installments as $inst)
                                @php $isDone = ($inst->status === 'completed'); @endphp
                                <tr class="border-bottom border-secondary">
                                    <td class="align-middle font-weight-bold text-light">#{{ $inst->installment_number }} of {{ $summary->total_count }}</td>
                                    <td class="align-middle font-weight-bold text-info fs-6">{{ number_format((float)$inst->amount) }} {{ $symbol }}</td>
                                    <td class="align-middle text-light">{{ \Carbon\Carbon::parse($inst->release_date)->format('Y-m-d H:i') }}</td>
                                    <td class="align-middle text-light">{{ $inst->processed_at ? \Carbon\Carbon::parse($inst->processed_at)->format('Y-m-d H:i') : '—' }}</td>
                                    <td class="align-middle">
                                        @if($isDone)
                                            <span class="badge bg-success text-white px-3 py-1.5"><i class="fas fa-check-circle me-1"></i>Released to Available Tokens</span>
                                        @else
                                            <span class="badge bg-warning text-dark px-3 py-1.5"><i class="fas fa-lock me-1"></i>Pending in Escrow Wallet</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
