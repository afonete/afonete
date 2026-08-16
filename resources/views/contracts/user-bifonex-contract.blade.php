@include('user.user-dashboard-base')

<div class="content-wrapper" style="background:#f8fafc; min-height:100vh;">
    <section class="content-header py-3">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="font-weight-bold text-dark mb-0"><i class="fas fa-file-contract text-primary mr-2"></i>My Bifonex Contract</h1>
                    <p class="text-muted small">View your contract status and the official contract summary.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if(!$contract)
                <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
                    <div class="card-body p-4 text-center">
                        <i class="fas fa-file-signature fa-3x text-warning mb-3"></i>
                        <h4 class="font-weight-bold text-dark mb-2">No Signed Contract Found</h4>
                        <p class="text-muted small mb-3">You have not signed a Bifonex contract yet. Once you activate a package and sign the contract, it will appear here.</p>
                        <a href="{{ route('user.contract') }}" class="btn btn-primary font-weight-bold px-4 py-2" style="border-radius:8px;">
                            Sign Contract Now →
                        </a>
                    </div>
                </div>
            @else
                {{-- ── 1. CONTRACT SUMMARY CARDS ── --}}
                <div class="row mb-4">
                    <div class="col-12 col-md-4 mb-3 mb-md-0">
                        <div class="card border-0 shadow-sm h-100" style="border-radius:12px; border-left:4px solid #3b82f6 !important;">
                            <div class="card-body p-3.5">
                                <span class="text-uppercase small text-muted font-weight-bold d-block mb-1" style="font-size:0.72rem;">Client Info</span>
                                <h5 class="font-weight-bold text-dark mb-1" style="font-size:1.1rem;">{{ $contract->user->name ?? $contract->name }}</h5>
                                <div class="text-muted small"><i class="fas fa-envelope mr-1 text-primary"></i> {{ $contract->user->email ?? '—' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 mb-3 mb-md-0">
                        <div class="card border-0 shadow-sm h-100" style="border-radius:12px; border-left:4px solid #6366f1 !important;">
                            <div class="card-body p-3.5">
                                <span class="text-uppercase small text-muted font-weight-bold d-block mb-1" style="font-size:0.72rem;">Account Details</span>
                                <div class="font-weight-bold text-slate-800 mb-1" style="font-size:1rem;">@ {{ $contract->user->user ?? '—' }}</div>
                                <div class="text-muted small">User ID: <strong>{{ ($contract->user && method_exists($contract->user, 'getTransferCode')) ? $contract->user->getTransferCode() : ($contract->user->transfer_code ?? '—') }}</strong> · Country: <strong>{{ $contract->user->country ?? '—' }}</strong></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="card border-0 shadow-sm h-100" style="border-radius:12px; border-left:4px solid #10b981 !important;">
                            <div class="card-body p-3.5 d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-uppercase small text-muted font-weight-bold d-block mb-1" style="font-size:0.72rem;">Contract Status</span>
                                    <div class="font-weight-bold text-success" style="font-size:1rem;">SIGNED &amp; VERIFIED</div>
                                    <div class="text-muted small">Signed: <strong>{{ $contract->created_at ? $contract->created_at->format('M d, Y H:i') : '—' }}</strong></div>
                                </div>
                                <div>
                                    <span class="badge badge-success px-3 py-2 font-weight-bold" style="border-radius:6px; font-size:0.75rem;">
                                        <i class="fas fa-check-circle mr-1"></i> VALID
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── 2. CONTRACT SUMMARY (PDF VIEW) ── --}}
            {{-- Admin-managed: Admin → Bifonex contract → User Contract Template --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius:12px; overflow:hidden;">
                <div class="card-header bg-light font-weight-bold text-dark py-3 d-flex align-items-center justify-content-between">
                    <span><i class="fas fa-file-pdf text-danger mr-2"></i> Bifonex Contract Summary</span>
                    @if(!$contract)
                        <span class="text-muted small">Preview before signing</span>
                    @endif
                </div>
                <div class="card-body p-0">
                    @if(!empty($summaryPdfAvailable ?? null))
                        <div style="background:#1e293b;">
                            {{-- Streamed from its own route so the browser's native PDF viewer renders it --}}
                            <iframe
                                title="Bifonex Contract Summary PDF"
                                src="{{ route('user.contracts.bifonex.summary-pdf') }}#toolbar=0&navpanes=0&scrollbar=1"
                                style="width:100%; height:75vh; border:0; background:#ffffff;"
                                oncontextmenu="return false;">
                            </iframe>
                        </div>
                        <noscript>
                            <div class="p-3 text-center">
                                <a href="{{ route('user.contracts.bifonex.summary-pdf') }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm font-weight-bold">
                                    <i class="fas fa-file-pdf mr-1"></i> Open Contract Summary (PDF)
                                </a>
                            </div>
                        </noscript>
                    @elseif(!empty($contractSummary ?? null))
                        {{-- HTML fallback if PDF generation failed --}}
                        <div class="p-4">
                            <div class="text-slate-800 small trix-content" style="line-height:1.7;">
                                {!! $contractSummary !!}
                            </div>
                        </div>
                    @else
                        <div class="p-4 text-center text-muted small">
                            The contract summary is not available at the moment. Please try again later.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
