@include('user.user-dashboard-base')

<div class="content-wrapper p-3 p-md-4" style="background-color: #f4f6f9; min-height: 85vh;">
    <div class="container-fluid">
        <!-- Breadcrumb & Header -->
        <div class="row mb-3">
            <div class="col-12 d-flex align-items-center justify-content-between flex-wrap">
                <div>
                    <h3 class="font-weight-bold text-dark mb-1">Affiliate Partner Terms &amp; Conditions</h3>
                    <p class="text-muted mb-0">Accept terms to activate your Binary Status and start earning affiliate referral bonuses.</p>
                </div>
                <div class="mt-2 mt-sm-0">
                    @if($isAccepted)
                        <span class="badge badge-success px-3 py-2 fs-6 rounded-pill shadow-sm">
                            <i class="fas fa-check-circle mr-1"></i> Binary Status: ACTIVE
                        </span>
                    @else
                        <span class="badge badge-danger px-3 py-2 fs-6 rounded-pill shadow-sm">
                            <i class="fas fa-times-circle mr-1"></i> Binary Status: INACTIVE
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Access Check Banner -->
        @if(!$hasFomPackage)
            <div class="alert alert-warning border-0 shadow-sm rounded-lg p-4 mb-4" style="background-color: #fffbe3; border-left: 5px solid #f59e0b !important;">
                <div class="d-flex align-items-start">
                    <i class="fas fa-exclamation-triangle fa-2x text-warning mr-3 mt-1"></i>
                    <div>
                        <h5 class="font-weight-bold text-dark mb-1">FOM Licence Miner Package Required</h5>
                        <p class="text-dark mb-3">
                            Only users who possess an active <strong>FOM Licence Miner Package</strong> are allowed to accept the Affiliate Partner Terms &amp; Conditions.
                            Please purchase or redeem a package code to unlock affiliate activation.
                        </p>
                        <a href="{{ route('investment-package') }}" class="btn btn-warning font-weight-bold px-4 rounded-pill shadow-sm text-dark">
                            <i class="fas fa-microchip mr-1"></i> Go to FOM Licence Miner
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-success border-0 shadow-sm rounded-lg p-3 mb-4 d-flex align-items-center" style="background-color: #ecfdf5; border-left: 5px solid #10b981 !important;">
                <i class="fas fa-check-circle fa-2x text-success mr-3"></i>
                <div>
                    <strong class="text-success font-weight-bold">FOM Licence Miner Package Active</strong>
                    <div class="text-dark text-sm">You are eligible to review and accept the Affiliate Partner Terms &amp; Conditions below.</div>
                </div>
            </div>
        @endif

        <!-- Terms Card -->
        <div class="card border-0 shadow-sm rounded-lg overflow-hidden mb-4">
            <div class="card-header text-white" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                <h5 class="card-title font-weight-bold mb-0 d-flex align-items-center">
                    <i class="fas fa-file-contract text-warning mr-2"></i> Affiliate Partner Program Agreement
                </h5>
            </div>
            <div class="card-body p-4 bg-white">
                <div class="border rounded p-3 mb-4" style="max-height: 400px; overflow-y: auto; background-color: #f8fafc; font-size: 0.95rem; line-height: 1.7; border-color: #e2e8f0 !important;">
                    {!! $termsContent !!}
                </div>

                @if($isAccepted)
                    <div class="alert alert-success d-flex align-items-center rounded-lg border-0 shadow-sm p-3" style="background-color: #d1fae5; border-left: 5px solid #10b981 !important;">
                        <i class="fas fa-check-circle fa-2x text-success mr-3"></i>
                        <div>
                            <h6 class="font-weight-bold text-success mb-1">Affiliate Partner Terms Accepted!</h6>
                            <p class="mb-0 text-dark text-sm">
                                You accepted the Affiliate Partner Terms &amp; Conditions on 
                                <strong>{{ $user->affiliate_terms_accepted_at ? \Carbon\Carbon::parse($user->affiliate_terms_accepted_at)->format('M d, Y \a\t H:i') : 'Active' }}</strong>. 
                                Your Binary Status is <strong>ACTIVE</strong> and you are actively earning affiliate bonuses.
                            </p>
                        </div>
                    </div>
                @else
                    @if($hasFomPackage)
                        <form action="{{ route('user.affiliate.accept-terms') }}" method="POST" id="affiliateTermsPageForm">
                            @csrf
                            <div class="card border-primary-light bg-light rounded-lg shadow-sm mb-4">
                                <div class="card-body py-3 px-3">
                                    <div class="custom-control custom-checkbox d-flex align-items-center">
                                        <input type="checkbox" class="custom-control-input" id="page_agree_terms_checkbox" name="agree_terms" value="1" onchange="togglePageAffiliateSubmitBtn(this)">
                                        <label class="custom-control-label font-weight-bold text-dark mb-0 ml-1" for="page_agree_terms_checkbox" style="cursor: pointer; font-size: 0.98rem;">
                                            I have read, understood, and agree to the Affiliate Partner Terms &amp; Conditions to start earning my affiliate bonus.
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-secondary btn-lg px-4 rounded-pill font-weight-bold shadow-sm" id="btnSubmitPageAffiliateTerms" disabled>
                                <i class="fas fa-check-circle mr-1"></i> Accept &amp; Activate Affiliate Bonus
                            </button>
                        </form>
                    @else
                        <button type="button" class="btn btn-secondary btn-lg px-4 rounded-pill font-weight-bold shadow-sm" disabled style="opacity: 0.65; cursor: not-allowed;">
                            <i class="fas fa-lock mr-1"></i> Acceptance Locked (Requires FOM Licence Miner)
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function togglePageAffiliateSubmitBtn(checkbox) {
    const btn = document.getElementById('btnSubmitPageAffiliateTerms');
    if (btn) {
        btn.disabled = !checkbox.checked;
        if (checkbox.checked) {
            btn.classList.remove('btn-secondary');
            btn.classList.add('btn-success');
        } else {
            btn.classList.add('btn-secondary');
            btn.classList.remove('btn-success');
        }
    }
}
</script>

@include('user.footer')