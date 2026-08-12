<div class="wrapper">
    @include('user.user-dashboard-base')
    <title>FOM Licence Miner</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

   <div class="content-wrapper">

<body>

   <div class="flex-1 p-4 ">
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>FOM Licence Miner Packages</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-color: #111827;
                color: #ffffff;
            }
            .card {
                background-color: #1F2937;
                border: 1px solid #374151;
                border-radius: 0.75rem;
            }
            .card-header {
                font-size: 1.25rem;
                font-weight: bold;
                color: #ffffff;
                text-align: center;
                background-color: #111827;
                border-bottom: 1px solid #374151;
            }
            .price {
                font-size: 1.5rem;
                font-weight: bold;
                color: #10B981;
                text-align: center;
            }
            .details {
                font-size: 0.9rem;
                color: #9CA3AF;
                text-align: center;
            }
            .card-body ul {
                list-style: none;
                padding-left: 0;
            }
            .card-body {
                padding: 12px !important;
            }
            .card-body li {
                color: #10B981;
                font-size: 13px;
                display: flex;
                align-items: center;
                margin-bottom: 4px;
            }
            .btn-primary {
                background-color: #3B82F6;
                border: none;
                font-weight: bold;
                width: 100%;
            }
            .btn-primary:hover {
                background-color: #2563EB;
            }
            .card-body li::before {
                content: "✔️";
                margin-right: 6px;
                color: #ff5733;
            }
            .deposit-badge {
                background-color: #1F2937;
                border: 1px solid #374151;
                border-radius: 0.5rem;
                padding: 10px 16px;
            }

            /* Premium Code Listing Card Styling */
            .success-code-card {
                background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%);
                border: 1px solid #10b981 !important;
                border-radius: 1rem !important;
                box-shadow: 0 10px 30px rgba(16, 185, 129, 0.15) !important;
            }
            .code-row-box {
                background-color: #0b0f19;
                border: 1px solid #374151;
                border-radius: 0.5rem;
                transition: all 0.2s ease-in-out;
            }
            .code-row-box:hover {
                border-color: #f59e0b;
                box-shadow: 0 0 12px rgba(245, 158, 11, 0.2);
            }
            .code-badge-num {
                background-color: #1f2937;
                color: #9ca3af;
                font-weight: 700;
                font-size: 0.75rem;
                padding: 4px 8px;
                border-radius: 0.375rem;
            }
            .code-text-val {
                font-family: 'Courier New', Courier, monospace;
                letter-spacing: 1.5px;
                font-size: 1.15rem;
                color: #fbbf24;
                font-weight: 800;
            }
            .btn-copy-individual {
                background-color: #d97706;
                color: #ffffff;
                font-weight: 700;
                border: none;
                transition: all 0.2s ease;
            }
            .btn-copy-individual:hover {
                background-color: #b45309;
                color: #ffffff;
            }
            .btn-copy-individual.copied {
                background-color: #10b981 !important;
                color: #ffffff !important;
            }
        </style>
    </head>
    <body>

    <div class="container-fluid my-4">
        
        {{-- Balance & Header Notice --}}
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between deposit-badge mb-4 gap-3">
            <div>
                <h3 class="text-white mb-1 font-weight-bold"><i class="fa fa-microchip text-warning mr-2"></i>FOM LICENCE MINER PACKAGES</h3>
                <p class="text-muted mb-0 small">Purchase activation codes using your <strong>Deposit Wallet</strong>. Activate 1 code on your account per package name, and share extra codes with other users!</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end">
                    <span class="text-muted d-block small">Deposit Wallet Balance</span>
                    <span class="text-success font-weight-bold fs-5">${{ number_format($depositWalletBalance, 2) }}</span>
                </div>
                <a href="{{ route('user.dashboard.deposit') }}" class="btn btn-outline-success btn-sm font-weight-bold ms-2">
                    <i class="fa fa-plus-circle me-1"></i>Deposit Funds
                </a>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Purchase Success & Impressive Activation Codes Showcase --}}
        @if(session('purchase_success'))
            @php $createdCodes = session('created_codes') ?: [['code' => session('activation_code'), 'id' => session('activation_id')]]; @endphp
            
            <div class="card success-code-card mb-4">
                <div class="card-header bg-success text-white text-center py-2.5 font-weight-bold border-0">
                    <i class="fa fa-check-circle me-2 fs-5"></i>Package Purchase Successful!
                </div>
                <div class="card-body p-4 text-center">
                    
                    <div class="mb-3">
                        <span class="badge bg-emerald-900 text-emerald-300 border border-emerald-500 px-3 py-1.5 rounded-pill uppercase tracking-wider font-bold mb-2 inline-block">
                            <i class="fa fa-shopping-bag me-1"></i> {{ session('quantity') }}x {{ session('package_name') }} Package(s)
                        </span>
                        <h4 class="text-white font-weight-bold mb-1">Total Paid: <span class="text-success">${{ number_format(session('total_cost'), 2) }}</span></h4>
                    </div>

                    <div class="alert alert-dark border border-secondary text-light small max-w-2xl mx-auto mb-4 text-start">
                        <div class="d-flex align-items-start gap-2">
                            <i class="fa fa-info-circle text-warning fs-5 mt-0.5 flex-shrink-0"></i>
                            <div>
                                <strong>Activation Rule:</strong> You can activate <strong>1 code</strong> for <strong>{{ session('package_name') }}</strong> on your account. Extra codes can be copied and given to other users to activate on their accounts!
                            </div>
                        </div>
                    </div>

                    {{-- Generated Activation Codes Container --}}
                    <div class="p-3.5 bg-dark bg-opacity-80 border border-secondary rounded-3 max-w-2xl mx-auto mb-4 text-start">
                        <div class="d-flex align-items-center justify-content-between pb-2 mb-3 border-bottom border-secondary">
                            <span class="text-light font-weight-bold small uppercase tracking-wider">
                                <i class="fa fa-ticket-alt text-warning me-1.5"></i> Generated Activation Code(s) ({{ count($createdCodes) }})
                            </span>
                            @if(count($createdCodes) > 1)
                                <button type="button" class="btn btn-outline-warning btn-sm font-weight-bold px-2.5 py-1" id="btnCopyAll" onclick="copyAllCodes()">
                                    <i class="fa fa-copy me-1"></i>Copy All {{ count($createdCodes) }} Codes
                                </button>
                            @endif
                        </div>

                        <div class="d-flex flex-column gap-2.5">
                            @foreach($createdCodes as $idx => $codeItem)
                                <div class="code-row-box p-2.5 d-flex align-items-center justify-content-between gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="code-badge-num">#{{ $idx + 1 }}</span>
                                        <code class="code-text-val px-2 py-1 rounded bg-black code-item-val" id="code-item-{{ $idx }}">{{ $codeItem['code'] }}</code>
                                    </div>
                                    <button type="button" class="btn btn-copy-individual btn-sm px-3 py-1.5 rounded-2" id="btn-copy-{{ $idx }}" onclick="copyIndividualCode('code-item-{{ $idx }}', 'btn-copy-{{ $idx }}')">
                                        <i class="fa fa-copy me-1"></i>Copy Code
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Direct Activate First Code Action --}}
                    <div class="pt-2">
                        <form action="{{ route('user.investment-package.activate-code') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="activation_id" value="{{ $createdCodes[0]['id'] ?? session('activation_id') }}">
                            <input type="hidden" name="code" value="{{ $createdCodes[0]['code'] ?? session('activation_code') }}">
                            <button type="submit" class="btn btn-success btn-lg font-weight-bold px-5 py-2.5 shadow-lg">
                                <i class="fa fa-bolt me-2"></i>Activate Code #1 Direct
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        @endif

        {{-- Packages Grid --}}
        <div class="row">
            @forelse($packages as $pkg)
                @php
                    $numPrice       = \App\Models\FomLicenceMiner::cleanNum($pkg->price);
                    $numTokens      = \App\Models\FomLicenceMiner::cleanNum($pkg->tokens);
                    $numTokenBonus  = \App\Models\FomLicenceMiner::cleanNum($pkg->token_bonus);
                    $numSponsors    = \App\Models\FomLicenceMiner::cleanNum($pkg->direct_sponsors);
                    $numAffiliate   = \App\Models\FomLicenceMiner::cleanNum($pkg->affiliate_vbonus);
                    $numVolumePoint = \App\Models\FomLicenceMiner::cleanNum($pkg->volume_point);
                    $numTotalReturn = \App\Models\FomLicenceMiner::cleanNum($pkg->total_return);
                @endphp
                <div class="col-md-3">
                    <div class="card mb-4 shadow h-100 d-flex flex-column justify-between">
                        <div>
                            <div class="card-header">{{ strtoupper($pkg->name) }}</div>
                            <div class="price mt-2">{{ $pkg->display_price ?: ('$' . number_format($numPrice, 0) . ' USDT') }}</div>
                            <div class="details mb-2">From Licence Miner. <br> TOKEN | {{ number_format($numTokens) }} {{ $tokenSymbol }}</div>
                            <div class="card-body">
                                <ul>
                                    <li>Duration {{ $pkg->duration_days }} Days</li>
                                    @if($numTokenBonus > 0)
                                        <li>Token Bonus: X{{ $numTokenBonus }}%</li>
                                    @endif
                                    @if($numSponsors > 0)
                                        <li>Direct Sponsors: {{ $numSponsors }}%</li>
                                    @endif
                                    @if($numAffiliate > 0)
                                        <li>Affiliate V.bonus: {{ $numAffiliate }}%</li>
                                    @endif
                                    @if(!empty($pkg->space_shop_limit))
                                        <li>{{ $pkg->space_shop_limit }}</li>
                                    @endif
                                    @if($numVolumePoint > 0)
                                        @if($numVolumePoint > 10)
                                            <li>Volume Bonus: {{ number_format($numVolumePoint) }}</li>
                                        @else
                                            <li>Volume Point: {{ $numVolumePoint }} Point</li>
                                        @endif
                                    @endif
                                    @if(!empty($pkg->unlocked_per_week))
                                        <li>Unlocked Per Week: {{ $pkg->unlocked_per_week }}</li>
                                    @endif
                                    @if(!empty($pkg->allowed_loan))
                                        <li>{{ $pkg->allowed_loan }}</li>
                                    @endif
                                    @if(!empty($pkg->investment_option))
                                        <li>{{ $pkg->investment_option }}</li>
                                    @endif
                                    @if($numTotalReturn > 0)
                                        <li><strong>TOTAL RETURN: {{ number_format($numTotalReturn) }} {{ $tokenSymbol }}</strong></li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        {{-- Purchase Form with Quantity Selector --}}
                        <div class="px-3 pb-3 pt-0">
                            <form action="{{ route('user.investment-package.buy') }}" method="POST" onsubmit="return confirm('Confirm purchasing package activation codes with Deposit Wallet balance?');">
                                @csrf
                                <input type="hidden" name="package_id" value="{{ $pkg->id }}">
                                
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <label class="text-white-50 small mb-0 font-weight-bold">Qty:</label>
                                    <input type="number" name="quantity" min="1" value="1" required
                                           class="form-control form-control-sm text-center bg-dark text-white border-secondary qty-input"
                                           data-unit-price="{{ $numPrice }}"
                                           data-button-id="buy-btn-{{ $pkg->id }}">
                                </div>

                                <button type="submit" class="btn btn-primary font-weight-bold shadow-sm" id="buy-btn-{{ $pkg->id }}">
                                    Buy Now (${{ number_format($numPrice, 0) }})
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <h4>No active Licence Miner packages available at the moment.</h4>
                </div>
            @endforelse
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyIndividualCode(elementId, btnId) {
            var codeElement = document.getElementById(elementId);
            var btnElement  = document.getElementById(btnId);
            if (!codeElement) return;

            var codeText = codeElement.innerText.trim();
            navigator.clipboard.writeText(codeText).then(function() {
                if (btnElement) {
                    var origHtml = btnElement.innerHTML;
                    btnElement.innerHTML = '<i class="fa fa-check text-white me-1"></i>Copied!';
                    btnElement.classList.add("copied");
                    setTimeout(function() {
                        btnElement.innerHTML = origHtml;
                        btnElement.classList.remove("copied");
                    }, 2000);
                }
            }).catch(function(err) {
                var tempInput = document.createElement("input");
                tempInput.value = codeText;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand("copy");
                document.body.removeChild(tempInput);
                if (btnElement) {
                    var origHtml = btnElement.innerHTML;
                    btnElement.innerHTML = '<i class="fa fa-check text-white me-1"></i>Copied!';
                    btnElement.classList.add("copied");
                    setTimeout(function() {
                        btnElement.innerHTML = origHtml;
                        btnElement.classList.remove("copied");
                    }, 2000);
                }
            });
        }

        function copyAllCodes() {
            var codeElements = document.querySelectorAll(".code-item-val");
            var btnAll = document.getElementById("btnCopyAll");
            var allCodes = [];

            codeElements.forEach(function(el) {
                if (el.innerText.trim()) {
                    allCodes.push(el.innerText.trim());
                }
            });
            if (allCodes.length === 0) return;

            var combinedText = allCodes.join("\n");
            navigator.clipboard.writeText(combinedText).then(function() {
                if (btnAll) {
                    var origText = btnAll.innerHTML;
                    btnAll.innerHTML = '<i class="fa fa-check text-success me-1"></i>All Copied!';
                    setTimeout(function() {
                        btnAll.innerHTML = origText;
                    }, 2000);
                }
            }).catch(function(err) {
                var tempInput = document.createElement("textarea");
                tempInput.value = combinedText;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand("copy");
                document.body.removeChild(tempInput);
                if (btnAll) {
                    var origText = btnAll.innerHTML;
                    btnAll.innerHTML = '<i class="fa fa-check text-success me-1"></i>All Copied!';
                    setTimeout(function() {
                        btnAll.innerHTML = origText;
                    }, 2000);
                }
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            var qtyInputs = document.querySelectorAll(".qty-input");
            qtyInputs.forEach(function(input) {
                input.addEventListener("input", function() {
                    var qty = parseInt(this.value) || 1;
                    if (qty < 1) qty = 1;
                    var unitPrice = parseFloat(this.getAttribute("data-unit-price")) || 0;
                    var totalPrice = (unitPrice * qty).toLocaleString('en-US', {minimumFractionDigits: 0, maximumFractionDigits: 2});
                    var buttonId = this.getAttribute("data-button-id");
                    var btn = document.getElementById(buttonId);
                    if (btn) {
                        btn.innerText = "Buy Now ($" + totalPrice + ")";
                    }
                });
            });
        });
    </script>
</body>
</div>
</div>
