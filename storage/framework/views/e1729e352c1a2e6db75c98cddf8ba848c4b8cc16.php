<div class="wrapper">
    <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <title>FOM Licence Miner</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

   <div class="content-wrapper">

<body>

   <div class="flex-1 p-3">
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
                border-radius: 0.6rem;
            }
            .card-header {
                font-size: 1.1rem;
                font-weight: bold;
                color: #ffffff;
                text-align: center;
                background-color: #111827;
                border-bottom: 1px solid #374151;
                padding: 8px 12px;
            }
            .price {
                font-size: 1.35rem;
                font-weight: bold;
                color: #10B981;
                text-align: center;
            }
            .details {
                font-size: 0.82rem;
                color: #9CA3AF;
                text-align: center;
            }
            .card-body ul {
                list-style: none;
                padding-left: 0;
            }
            .card-body {
                padding: 10px !important;
            }
            .card-body li {
                color: #10B981;
                font-size: 12.5px;
                display: flex;
                align-items: center;
                margin-bottom: 3px;
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
                margin-right: 5px;
                color: #ff5733;
            }
            
            /* Compact Top Banner */
            .deposit-badge-compact {
                background-color: #1F2937;
                border: 1px solid #374151;
                border-radius: 0.5rem;
                padding: 10px 16px;
            }

            /* Compact Code Listing Card */
            .success-code-card-compact {
                background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%);
                border: 1px solid #10b981 !important;
                border-radius: 0.6rem !important;
            }
            .code-row-box-compact {
                background-color: #0b0f19;
                border: 1px solid #374151;
                border-radius: 0.4rem;
                padding: 8px 12px;
                transition: all 0.2s ease-in-out;
            }
            .code-row-box-compact:hover {
                border-color: #f59e0b;
            }
            .code-badge-num-compact {
                background-color: #1f2937;
                color: #9ca3af;
                font-weight: 700;
                font-size: 0.75rem;
                padding: 2px 6px;
                border-radius: 0.25rem;
            }
            .code-text-val-compact {
                font-family: 'Courier New', Courier, monospace;
                letter-spacing: 1px;
                font-size: 0.95rem;
                color: #fbbf24;
                font-weight: 700;
            }
            .btn-copy-individual-compact {
                background-color: #d97706;
                color: #ffffff;
                font-weight: 700;
                border: none;
                font-size: 0.8rem;
                padding: 5px 12px;
                border-radius: 0.35rem;
                transition: all 0.2s ease;
            }
            .btn-copy-individual-compact:hover {
                background-color: #b45309;
                color: #ffffff;
            }
            .btn-copy-individual-compact.copied {
                background-color: #10b981 !important;
                color: #ffffff !important;
            }

            /* High Contrast Clear Notifications */
            .alert-clear-danger {
                background-color: #dc2626 !important;
                color: #ffffff !important;
                border: 1px solid #ef4444 !important;
                border-radius: 8px !important;
                font-size: 0.95rem !important;
                font-weight: 600 !important;
            }
            .alert-clear-success {
                background-color: #059669 !important;
                color: #ffffff !important;
                border: 1px solid #10b981 !important;
                border-radius: 8px !important;
                font-size: 0.95rem !important;
                font-weight: 600 !important;
            }
        </style>
    </head>
    <body>

    <div class="container-fluid my-2">
        
        
        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between deposit-badge-compact mb-3 gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="fa fa-microchip text-warning fs-5"></i>
                <div>
                    <h6 class="text-white mb-0 font-weight-bold" style="font-size: 0.95rem;">FOM LICENCE MINER PACKAGES</h6>
                    <span class="text-muted text-xs d-block" style="font-size: 0.75rem;">Purchase codes using Deposit Wallet. Activate 1 code per package name.</span>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                <div class="text-sm-end text-center">
                    <span class="text-muted d-inline d-sm-block text-xs" style="font-size: 0.72rem;">Deposit Wallet: </span>
                    <span class="text-success font-weight-bold" style="font-size: 0.95rem;">$<?php echo e(number_format($depositWalletBalance, 2)); ?></span>
                </div>
                <a href="<?php echo e(route('user.dashboard.deposit')); ?>" class="btn btn-outline-success btn-sm font-weight-bold py-1 px-2.5 text-xs">
                    <i class="fa fa-plus-circle me-1"></i>Deposit
                </a>
            </div>
        </div>

        
        <?php if(session('error')): ?>
            <div class="alert alert-clear-danger alert-dismissible fade show p-3 mb-3 shadow-sm" role="alert">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fa fa-exclamation-circle me-2 fs-5"></i><?php echo e(session('error')); ?>

                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="alert alert-clear-success alert-dismissible fade show p-3 mb-3 shadow-sm" role="alert">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fa fa-check-circle me-2 fs-5"></i><?php echo e(session('success')); ?>

                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>

        
        <?php if(session('purchase_success')): ?>
            <?php $createdCodes = session('created_codes') ?: [['code' => session('activation_code'), 'id' => session('activation_id')]]; ?>
            
            <div class="card success-code-card-compact mb-3">
                <div class="card-header bg-success text-white text-center py-2 font-weight-bold border-0 text-sm">
                    <i class="fa fa-check-circle me-1.5"></i>Package Purchase Successful!
                </div>
                <div class="card-body p-3 text-center">
                    
                    <div class="mb-2">
                        <span class="badge bg-emerald-900 text-emerald-300 border border-emerald-500 px-2.5 py-1 rounded-pill text-xs font-bold me-2 inline-block">
                            <i class="fa fa-shopping-bag me-1"></i> <?php echo e(session('quantity')); ?>x <?php echo e(session('package_name')); ?> Package(s)
                        </span>
                        <span class="text-white font-weight-bold text-sm">Total Paid: <span class="text-success">$<?php echo e(number_format(session('total_cost'), 2)); ?></span></span>
                    </div>

                    <p class="text-muted text-xs mb-2 max-w-xl mx-auto">
                        <i class="fa fa-info-circle text-warning me-1"></i>
                        You can activate <strong>1 code</strong> for <?php echo e(session('package_name')); ?> on your account. Share remaining codes with other users!
                    </p>

                    
                    <div class="p-2.5 bg-dark bg-opacity-80 border border-secondary rounded max-w-xl mx-auto mb-3 text-start">
                        <div class="d-flex align-items-center justify-content-between pb-1.5 mb-2 border-bottom border-secondary">
                            <span class="text-light font-weight-bold text-xs uppercase">
                                <i class="fa fa-ticket-alt text-warning me-1"></i> Generated Codes (<?php echo e(count($createdCodes)); ?>)
                            </span>
                            <?php if(count($createdCodes) > 1): ?>
                                <button type="button" class="btn btn-outline-warning btn-sm font-weight-bold px-2 py-0.5 text-xs" id="btnCopyAll" onclick="copyAllCodes()">
                                    <i class="fa fa-copy me-1"></i>Copy All <?php echo e(count($createdCodes)); ?> Codes
                                </button>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex flex-column gap-1.5">
                            <?php $__currentLoopData = $createdCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $codeItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="code-row-box-compact d-flex align-items-center justify-content-between gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="code-badge-num-compact">#<?php echo e($idx + 1); ?></span>
                                        <code class="code-text-val-compact px-2 py-0.5 rounded bg-black code-item-val" id="code-item-<?php echo e($idx); ?>"><?php echo e($codeItem['code']); ?></code>
                                    </div>
                                    <button type="button" class="btn btn-copy-individual-compact" id="btn-copy-<?php echo e($idx); ?>" onclick="copyIndividualCode('code-item-<?php echo e($idx); ?>', 'btn-copy-<?php echo e($idx); ?>')">
                                        <i class="fa fa-copy me-1"></i>Copy
                                    </button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    
                    <div>
                        <form action="<?php echo e(route('user.investment-package.activate-code')); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="activation_id" value="<?php echo e($createdCodes[0]['id'] ?? session('activation_id')); ?>">
                            <input type="hidden" name="code" value="<?php echo e($createdCodes[0]['code'] ?? session('activation_code')); ?>">
                            <button type="submit" class="btn btn-success btn-sm font-weight-bold px-4 py-1.5">
                                <i class="fa fa-bolt me-1"></i>Activate Code #1 Direct
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        <?php endif; ?>

        
        <div class="row">
            <?php $__empty_1 = true; $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pkg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $numPrice       = \App\Models\FomLicenceMiner::cleanNum($pkg->price);
                    $numTokens      = \App\Models\FomLicenceMiner::cleanNum($pkg->tokens);
                    $numTokenBonus  = \App\Models\FomLicenceMiner::cleanNum($pkg->token_bonus);
                    $numSponsors    = \App\Models\FomLicenceMiner::cleanNum($pkg->direct_sponsors);
                    $numAffiliate   = \App\Models\FomLicenceMiner::cleanNum($pkg->affiliate_vbonus);
                    $numVolumePoint = \App\Models\FomLicenceMiner::cleanNum($pkg->volume_point);
                    $numTotalReturn = \App\Models\FomLicenceMiner::cleanNum($pkg->total_return);
                ?>
                <div class="col-md-3">
                    <div class="card mb-3 shadow h-100 d-flex flex-column justify-between">
                        <div>
                            <div class="card-header"><?php echo e(strtoupper($pkg->name)); ?></div>
                            <div class="price mt-1.5"><?php echo e($pkg->display_price ?: ('$' . number_format($numPrice, 0) . ' USDT')); ?></div>
                            <div class="details mb-1.5">From Licence Miner. <br> TOKEN | <?php echo e(number_format($numTokens)); ?> <?php echo e($tokenSymbol); ?></div>
                            <div class="card-body">
                                <ul>
                                    <li>Duration <?php echo e($pkg->duration_days); ?> Days</li>
                                    <?php if($numTokenBonus > 0): ?>
                                        <li>Token Bonus: X<?php echo e($numTokenBonus); ?>%</li>
                                    <?php endif; ?>
                                    <?php if($numSponsors > 0): ?>
                                        <li>Direct Sponsors: <?php echo e($numSponsors); ?>%</li>
                                    <?php endif; ?>
                                    <?php if($numAffiliate > 0): ?>
                                        <li>Affiliate V.bonus: <?php echo e($numAffiliate); ?>%</li>
                                    <?php endif; ?>
                                    <?php if(!empty($pkg->space_shop_limit)): ?>
                                        <li><?php echo e($pkg->space_shop_limit); ?></li>
                                    <?php endif; ?>
                                    <?php if($numVolumePoint > 0): ?>
                                        <?php if($numVolumePoint > 10): ?>
                                            <li>Volume Bonus: <?php echo e(number_format($numVolumePoint)); ?></li>
                                        <?php else: ?>
                                            <li>Volume Point: <?php echo e($numVolumePoint); ?> Point</li>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if(!empty($pkg->unlocked_per_week)): ?>
                                        <li>Unlocked Per Month: <?php echo e($pkg->unlocked_per_week); ?></li>
                                    <?php endif; ?>
                                    <?php if(!empty($pkg->allowed_loan)): ?>
                                        <li><?php echo e($pkg->allowed_loan); ?></li>
                                    <?php endif; ?>
                                    <?php if(!empty($pkg->investment_option)): ?>
                                        <li><?php echo e($pkg->investment_option); ?></li>
                                    <?php endif; ?>
                                    <?php if($numTotalReturn > 0): ?>
                                        <li><strong>TOTAL RETURN: <?php echo e(number_format($numTotalReturn)); ?> <?php echo e($tokenSymbol); ?></strong></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>

                        
                        <div class="px-2.5 pb-2.5 pt-0">
                            <form action="<?php echo e(route('user.investment-package.buy')); ?>" method="POST" class="js-transaction-password-form">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="package_id" value="<?php echo e($pkg->id); ?>">
                                <input type="hidden" name="transaction_password" class="js-transaction-password-value">
                                
                                <div class="input-group input-group-sm mb-2">
                                    <span class="input-group-text bg-dark text-white-50 border-secondary text-xs font-weight-bold" style="padding: 4px 10px;">Qty:</span>
                                    <input type="number" name="quantity" min="1" value="1" required
                                           class="form-control text-center bg-dark text-white border-secondary qty-input font-weight-bold"
                                           style="font-size: 13px; height: 32px;"
                                           data-unit-price="<?php echo e($numPrice); ?>"
                                           data-button-id="buy-btn-<?php echo e($pkg->id); ?>">
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm font-weight-bold w-100 py-2 shadow-sm" id="buy-btn-<?php echo e($pkg->id); ?>">
                                    Buy Now ($<?php echo e(number_format($numPrice, 0)); ?>)
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12 text-center text-muted py-5">
                    <h4>No active Licence Miner packages available at the moment.</h4>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <?php echo $__env->make('user.components.transaction-password-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

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
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/investment-package.blade.php ENDPATH**/ ?>