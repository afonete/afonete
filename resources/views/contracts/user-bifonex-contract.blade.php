@include('user.user-dashboard-base')

<div class="content-wrapper" style="background:#f8fafc; min-height:100vh;">
    <section class="content-header py-3">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="font-weight-bold text-dark mb-0"><i class="fas fa-file-contract text-primary mr-2"></i>My Bifonex Contract</h1>
                    <p class="text-muted small">View your official signed affiliate agreement and contract summary.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if(!$contract)
                <div class="card border-0 shadow-sm" style="border-radius:12px;">
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
                                <div class="text-muted small">User ID: <strong>#{{ $contract->user_id }}</strong> · Country: <strong>{{ $contract->user->country ?? '—' }}</strong></div>
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

                {{-- ── 2. CONTRACT HIGHLIGHTS & OVERVIEW ── --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius:12px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color:#ffffff;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 border-bottom pb-2" style="border-bottom-color: rgba(255,255,255,0.15) !important;">
                            <h5 class="font-weight-bold text-white mb-0 flex-grow-1">
                                <i class="fas fa-file-alt text-warning mr-2"></i> Bifonex Contract Summary &amp; Overview
                            </h5>
                            <span class="badge badge-warning text-dark font-weight-bold px-2.5 py-1" style="border-radius:4px; font-size:0.72rem;">
                                Contract Ref #{{ $contract->id }}
                            </span>
                        </div>
                        <div class="row text-slate-200 small">
                            <div class="col-12 col-md-4 mb-2 mb-md-0">
                                <strong>Agreement Type:</strong> Independent Marketing Affiliate (IMA) &amp; Client Agreement
                            </div>
                            <div class="col-12 col-md-4 mb-2 mb-md-0">
                                <strong>Confidentiality:</strong> Protected proprietary information &amp; platform rules
                            </div>
                            <div class="col-12 col-md-4">
                                <strong>Legal Effect:</strong> Digitally signed with legally binding system verification
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── 3. FULL SIGNED DOCUMENT PREVIEW ── --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius:12px; overflow:hidden;">
                    <div class="card-header bg-light font-weight-bold text-dark py-3 d-flex align-items-center justify-content-between">
                        <span><i class="fas fa-file-pdf text-danger mr-2"></i> Full Signed Contract Document</span>
                        <span class="text-muted small">Verified Digital Copy</span>
                    </div>
                    <div class="card-body p-0">
                        <div style="background:#1e293b;">
                            <iframe
                                title="Bifonex Contract PDF"
                                src="data:application/pdf;base64,{{ $pdfBase64 }}#toolbar=0&navpanes=0&scrollbar=1"
                                style="width:100%; height:82vh; border:0; background:#ffffff;"
                                sandbox="allow-same-origin"
                                oncontextmenu="return false;">
                            </iframe>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
