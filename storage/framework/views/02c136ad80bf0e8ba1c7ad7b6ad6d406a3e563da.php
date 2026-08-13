<div class="wrapper">
    <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <title>Escrow &amp; Staking Vault - Licence Miner</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        .escrow-hub-container {
            background-color: #0b0f19;
            min-height: 100vh;
            color: #f8fafc;
        }
        .vault-card {
            background: linear-gradient(145deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid #334155;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        .nav-tabs-custom {
            border-bottom: 2px solid #334155;
        }
        .nav-tabs-custom .nav-link {
            color: #94a3b8;
            font-weight: 700;
            border: none;
            border-bottom: 3px solid transparent;
            padding: 12px 20px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background: transparent;
        }
        .nav-tabs-custom .nav-link:hover {
            color: #f59e0b;
            border-bottom-color: rgba(245, 158, 11, 0.4);
        }
        .nav-tabs-custom .nav-link.active {
            color: #f59e0b;
            border-bottom-color: #f59e0b;
            background: rgba(245, 158, 11, 0.05);
        }
        .year-card {
            background: #0f172a;
            border: 2px solid #334155;
            border-radius: 12px;
            padding: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
        }
        .year-card:hover, .year-card.selected {
            border-color: #f59e0b;
            background: rgba(245, 158, 11, 0.1);
            transform: translateY(-2px);
        }
        .year-card.selected {
            box-shadow: 0 0 15px rgba(245, 158, 11, 0.3);
        }
        .year-yield-badge {
            background-color: #10b981;
            color: #064e3b;
            font-weight: 800;
            font-size: 0.8rem;
            padding: 2px 8px;
            border-radius: 9999px;
        }
    </style>

    <div class="content-wrapper escrow-hub-container">
        <div class="container-fluid py-4 px-4 max-w-7xl mx-auto">

            
            <div class="p-4 mb-4 shadow-lg d-flex align-items-center justify-content-between flex-wrap gap-3 vault-card">
                <div class="d-flex align-items-center gap-3">
                    <a href="<?php echo e(route('investment-package')); ?>" class="btn btn-outline-light font-weight-bold px-3 py-2" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left me-1"></i> Licence Miner
                    </a>
                    <div>
                        <h4 class="font-weight-bold text-white mb-0" style="font-size: 1.35rem;">
                            <i class="fas fa-shield-alt text-warning me-2"></i> Escrow Wallet &amp; Staking Vault Hub
                        </h4>
                        <small class="text-light opacity-90">Manage Total Return Escrow tokens, 12-month package releases, and 1–5 Year compounding vaults.</small>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="<?php echo e(route('user.dashboard.activate')); ?>" class="btn btn-warning text-dark font-weight-bold px-3 py-2" style="border-radius: 8px;">
                        <i class="fas fa-key me-1"></i> Activate Code
                    </a>
                </div>
            </div>

            
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show font-weight-bold shadow-sm mb-4" role="alert" style="border-radius: 10px;">
                    <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show font-weight-bold shadow-sm mb-4" role="alert" style="border-radius: 10px;">
                    <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            
            <div class="row mb-4">
                
                <div class="col-12 col-md-4 mb-3">
                    <div class="card p-3 shadow-sm h-100 border-0" style="background: #1e293b; border-left: 4px solid #f59e0b !important; border-radius: 14px;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted d-block small font-weight-bold text-uppercase">Escrow Wallet (Locked Tokens)</span>
                                <h3 class="font-weight-bold text-warning mb-0 mt-1"><?php echo e(number_format($escrowBal, 0)); ?></h3>
                                <small class="text-muted"><?php echo e($tokenSymbol); ?></small>
                            </div>
                            <div class="p-3 bg-dark rounded-circle text-warning fs-3">
                                <i class="fas fa-lock"></i>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="col-12 col-md-4 mb-3">
                    <div class="card p-3 shadow-sm h-100 border-0" style="background: #1e293b; border-left: 4px solid #10b981 !important; border-radius: 14px;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted d-block small font-weight-bold text-uppercase">Available Tokens Balance</span>
                                <h3 class="font-weight-bold text-success mb-0 mt-1"><?php echo e(number_format($availableBal, 0)); ?></h3>
                                <small class="text-muted"><?php echo e($tokenSymbol); ?></small>
                            </div>
                            <div class="p-3 bg-dark rounded-circle text-success fs-3">
                                <i class="fas fa-coins"></i>
                            </div>
                        </div>
                    </div>
                </div>

                
                <?php
                    $releasedCount = $installments ? $installments->where('status', 'completed')->count() : 0;
                    $releasedTokens = $installments ? $installments->where('status', 'completed')->sum('amount') : 0;
                ?>
                <div class="col-12 col-md-4 mb-3">
                    <div class="card p-3 shadow-sm h-100 border-0" style="background: #1e293b; border-left: 4px solid #3b82f6 !important; border-radius: 14px;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted d-block small font-weight-bold text-uppercase">Released to Available Tokens</span>
                                <h3 class="font-weight-bold text-info mb-0 mt-1"><?php echo e(number_format($releasedTokens, 0)); ?></h3>
                                <small class="text-muted"><?php echo e($releasedCount); ?> / 12 Monthly Releases Done</small>
                            </div>
                            <div class="p-3 bg-dark rounded-circle text-info fs-3">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <ul class="nav nav-tabs nav-tabs-custom mb-4" id="escrowTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-releases-link" data-bs-toggle="tab" data-bs-target="#tab-releases" type="button" role="tab">
                        <i class="fas fa-calendar-alt me-2"></i>12 Monthly Package Releases
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-staking-link" data-bs-toggle="tab" data-bs-target="#tab-staking" type="button" role="tab">
                        <i class="fas fa-rocket me-2 text-warning"></i>1–5 Year Escrow Staking Vault
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-history-link" data-bs-toggle="tab" data-bs-target="#tab-history" type="button" role="tab">
                        <i class="fas fa-history me-2"></i>Staking Vault History
                    </button>
                </li>
            </ul>

            
            <div class="tab-content" id="escrowTabsContent">

                
                <div class="tab-pane fade show active" id="tab-releases" role="tabpanel">
                    <div class="card vault-card p-4">
                        <div class="border-bottom border-secondary pb-3 mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h5 class="font-weight-bold text-white mb-1">
                                    <i class="fas fa-calendar-alt text-info me-2"></i>12 Monthly Token Releases Schedule
                                </h5>
                                <p class="text-muted small mb-0">Total Return tokens from activated packages are locked in Escrow and released in 12 equal monthly installments into Available Tokens.</p>
                            </div>
                            <span class="badge bg-info text-dark font-weight-bold px-3 py-2 fs-6"><?php echo e(count($installments ?? [])); ?> Scheduled Releases</span>
                        </div>

                        <?php if(empty($installments) || count($installments) === 0): ?>
                            <div class="text-center p-5 text-muted">
                                <i class="fas fa-calendar-times text-secondary mb-3 d-block" style="font-size: 2.5rem;"></i>
                                <h5 class="text-light font-weight-bold">No 12-Month Installment Schedules Found</h5>
                                <p class="small mb-3">Activate a FOM Licence Miner package code to start your 12-month token release schedule into Available Tokens.</p>
                                <a href="<?php echo e(route('investment-package')); ?>" class="btn btn-warning text-dark font-weight-bold px-4">
                                    <i class="fas fa-shopping-cart me-1"></i> Buy Package Codes
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-dark table-hover mb-0 text-center align-middle" style="background:transparent;">
                                    <thead class="bg-dark text-muted uppercase text-xs">
                                        <tr>
                                            <th class="py-3">Installment #</th>
                                            <th class="py-3">Package Name</th>
                                            <th class="py-3">Tokens Amount</th>
                                            <th class="py-3">Scheduled Release Date</th>
                                            <th class="py-3">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $installments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inst): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php $isDone = ($inst->status === 'completed'); ?>
                                            <tr class="border-bottom border-secondary">
                                                <td class="align-middle font-weight-bold text-light">Installment #<?php echo e($inst->installment_number); ?> of 12</td>
                                                <td class="align-middle font-weight-bold text-warning"><?php echo e(strtoupper($inst->package_name)); ?></td>
                                                <td class="align-middle font-weight-bold text-info fs-6"><?php echo e(number_format((float)$inst->amount)); ?> <?php echo e($tokenSymbol); ?></td>
                                                <td class="align-middle text-light"><?php echo e(\Carbon\Carbon::parse($inst->release_date)->format('Y-m-d H:i')); ?></td>
                                                <td class="align-middle">
                                                    <?php if($isDone): ?>
                                                        <span class="badge bg-success text-white px-3 py-1.5"><i class="fas fa-check-circle me-1"></i>Released to Available Tokens</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning text-dark px-3 py-1.5"><i class="fas fa-lock me-1"></i>Pending in Escrow Wallet</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="tab-pane fade" id="tab-staking" role="tabpanel">
                    <div class="card vault-card p-4">
                        <div class="border-bottom border-secondary pb-3 mb-4">
                            <h5 class="font-weight-bold text-white mb-1">
                                <i class="fas fa-rocket text-warning me-2"></i>Transfer Available Tokens &rarr; Escrow Staking Vault (1–5 Years)
                            </h5>
                            <p class="text-muted small mb-0">Compound your Available Tokens back into Escrow Staking. Select a lockup duration of 1 to 5 Years to earn bonus profit %. Total tokens (Principal + Bonus Profit) are locked in Escrow and unlocked at maturity back into Available Tokens!</p>
                        </div>

                        <form method="POST" action="<?php echo e(route('user.token.available-to-escrow-staking')); ?>" class="js-transaction-password-form">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="transaction_password" class="js-transaction-password-value">
                            
                            
                            <div class="mb-4 max-w-xl">
                                <label class="form-label font-weight-bold text-light mb-2">1. Enter Amount of Available Tokens to Lock <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <input type="number" name="amount" id="tabStakeAmount" class="form-control bg-dark text-white border-secondary font-weight-bold"
                                           min="1" step="1" max="<?php echo e($availableBal); ?>" required placeholder="e.g. 10000" style="font-size: 1.25rem;">
                                    <span class="input-group-text bg-secondary border-secondary text-white font-weight-bold"><?php echo e($tokenSymbol); ?></span>
                                </div>
                                <div class="d-flex justify-content-between mt-1 text-muted small">
                                    <span>Available Tokens: <strong class="text-success"><?php echo e(number_format($availableBal, 0)); ?> <?php echo e($tokenSymbol); ?></strong></span>
                                    <button type="button" class="btn btn-link text-warning p-0 text-decoration-none small" onclick="document.getElementById('tabStakeAmount').value=<?php echo e((int)$availableBal); ?>; updateTabPreview();">Lock Max Balance</button>
                                </div>
                            </div>

                            
                            <div class="mb-4">
                                <label class="form-label font-weight-bold text-light mb-2">2. Select Lockup Duration &amp; Guaranteed Profit Yield <span class="text-danger">*</span></label>
                                
                                <input type="hidden" name="years" id="selectedYearsInput" value="1">

                                <div class="row g-3">
                                    <div class="col-6 col-md">
                                        <div class="year-card selected" onclick="selectYearCard(1, <?php echo e($y1); ?>, this)">
                                            <span class="year-yield-badge mb-2 d-inline-block">+<?php echo e($y1); ?>% Profit</span>
                                            <h4 class="font-weight-bold text-white mb-1">1 Year</h4>
                                            <span class="text-muted small d-block">Short-term Vault</span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md">
                                        <div class="year-card" onclick="selectYearCard(2, <?php echo e($y2); ?>, this)">
                                            <span class="year-yield-badge mb-2 d-inline-block">+<?php echo e($y2); ?>% Profit</span>
                                            <h4 class="font-weight-bold text-white mb-1">2 Years</h4>
                                            <span class="text-muted small d-block">Medium Vault</span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md">
                                        <div class="year-card" onclick="selectYearCard(3, <?php echo e($y3); ?>, this)">
                                            <span class="year-yield-badge mb-2 d-inline-block">+<?php echo e($y3); ?>% Profit</span>
                                            <h4 class="font-weight-bold text-white mb-1">3 Years</h4>
                                            <span class="text-muted small d-block">Growth Vault</span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md">
                                        <div class="year-card" onclick="selectYearCard(4, <?php echo e($y4); ?>, this)">
                                            <span class="year-yield-badge mb-2 d-inline-block">+<?php echo e($y4); ?>% Profit</span>
                                            <h4 class="font-weight-bold text-white mb-1">4 Years</h4>
                                            <span class="text-muted small d-block">High Yield Vault</span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md">
                                        <div class="year-card" onclick="selectYearCard(5, <?php echo e($y5); ?>, this)">
                                            <span class="year-yield-badge mb-2 d-inline-block">+<?php echo e($y5); ?>% Profit</span>
                                            <h4 class="font-weight-bold text-white mb-1">5 Years</h4>
                                            <span class="text-muted small d-block">Maximum Multiplier</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="p-4 bg-dark rounded-3 border border-secondary mb-4 max-w-2xl">
                                <h6 class="text-warning font-weight-bold mb-3 uppercase tracking-wider small"><i class="fas fa-calculator me-2"></i>Staking Projection &amp; Maturity Summary</h6>
                                
                                <div class="row text-center g-3">
                                    <div class="col-4 border-end border-secondary">
                                        <span class="text-muted d-block small">Principal Staked</span>
                                        <strong class="text-white fs-5" id="tabPrevPrincipal">0</strong>
                                        <span class="text-muted d-block text-xs"><?php echo e($tokenSymbol); ?></span>
                                    </div>
                                    <div class="col-4 border-end border-secondary">
                                        <span class="text-muted d-block small">Bonus Yield Profit</span>
                                        <strong class="text-success fs-5" id="tabPrevProfit">+0</strong>
                                        <span class="text-success d-block text-xs" id="tabPrevYieldPct">(+10%)</span>
                                    </div>
                                    <div class="col-4">
                                        <span class="text-muted d-block small">Total Unlocked at Maturity</span>
                                        <strong class="text-warning fs-5" id="tabPrevTotal">0</strong>
                                        <span class="text-muted d-block text-xs"><?php echo e($tokenSymbol); ?></span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-warning btn-lg text-dark font-weight-bold px-5 py-3 shadow-lg" <?php echo e($availableBal < 1 ? 'disabled' : ''); ?>>
                                <i class="fas fa-lock me-2"></i> Lock Tokens in Escrow Vault Now
                            </button>
                        </form>
                    </div>
                </div>

                
                <div class="tab-pane fade" id="tab-history" role="tabpanel">
                    <div class="card vault-card p-4">
                        <div class="border-bottom border-secondary pb-3 mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h5 class="font-weight-bold text-white mb-1">
                                    <i class="fas fa-history text-success me-2"></i>Active &amp; Completed Escrow Staking Vaults
                                </h5>
                                <p class="text-muted small mb-0">Track all 1–5 Year Escrow Staking vaults, bonus profit yields, and maturity unlock dates.</p>
                            </div>
                            <span class="badge bg-success font-weight-bold px-3 py-2 fs-6"><?php echo e(count($stakings ?? [])); ?> Staking Vaults</span>
                        </div>

                        <?php if(empty($stakings) || count($stakings) === 0): ?>
                            <div class="text-center p-5 text-muted">
                                <i class="fas fa-shield-alt text-secondary mb-3 d-block" style="font-size: 2.5rem;"></i>
                                <h5 class="text-light font-weight-bold">No Escrow Stakings Created Yet</h5>
                                <p class="small mb-3">Transfer Available Tokens to Escrow Staking above to earn up to 100% bonus profit yield!</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-dark table-hover mb-0 text-center align-middle" style="background:transparent;">
                                    <thead class="bg-dark text-muted uppercase text-xs">
                                        <tr>
                                            <th class="py-3">#</th>
                                            <th class="py-3">Principal Staked</th>
                                            <th class="py-3">Profit Yield %</th>
                                            <th class="py-3">Bonus Profit Tokens</th>
                                            <th class="py-3">Total Escrowed</th>
                                            <th class="py-3">Lock Duration</th>
                                            <th class="py-3">Maturity Date</th>
                                            <th class="py-3">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $stakings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php $isDone = ($stk->status === 'completed'); ?>
                                            <tr class="border-bottom border-secondary">
                                                <td class="align-middle text-muted"><?php echo e($loop->iteration); ?></td>
                                                <td class="align-middle font-weight-bold text-white"><?php echo e(number_format((float)$stk->principal_amount)); ?> <?php echo e($tokenSymbol); ?></td>
                                                <td class="align-middle font-weight-bold text-success">+<?php echo e((float)$stk->yield_percent); ?>%</td>
                                                <td class="align-middle font-weight-bold text-success">+<?php echo e(number_format((float)$stk->profit_amount)); ?> <?php echo e($tokenSymbol); ?></td>
                                                <td class="align-middle font-weight-bold text-warning fs-6"><?php echo e(number_format((float)$stk->total_staked)); ?> <?php echo e($tokenSymbol); ?></td>
                                                <td class="align-middle"><span class="badge bg-primary px-2.5 py-1.5"><?php echo e($stk->lock_years); ?> Year(s)</span></td>
                                                <td class="align-middle text-light"><?php echo e(\Carbon\Carbon::parse($stk->release_date)->format('Y-m-d')); ?></td>
                                                <td class="align-middle">
                                                    <?php if($isDone): ?>
                                                        <span class="badge bg-success text-white px-3 py-1.5"><i class="fas fa-check-circle me-1"></i>Released to Available Tokens</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning text-dark px-3 py-1.5"><i class="fas fa-lock me-1"></i>Active in Escrow Vault</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<?php echo $__env->make('user.components.transaction-password-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var currentYieldPct = <?php echo e($y1); ?>;

    function selectYearCard(years, yieldPct, cardEl) {
        document.querySelectorAll(".year-card").forEach(function(c) {
            c.classList.remove("selected");
        });
        cardEl.classList.add("selected");
        document.getElementById("selectedYearsInput").value = years;
        currentYieldPct = yieldPct;
        updateTabPreview();
    }

    function updateTabPreview() {
        var inputEl = document.getElementById("tabStakeAmount");
        if (!inputEl) return;

        var amount = parseFloat(inputEl.value) || 0;
        var profit = amount * (currentYieldPct / 100.0);
        var total  = amount + profit;
        var symbol = "<?php echo e($tokenSymbol); ?>";

        document.getElementById("tabPrevPrincipal").innerText = amount.toLocaleString('en-US');
        document.getElementById("tabPrevProfit").innerText    = "+" + profit.toLocaleString('en-US');
        document.getElementById("tabPrevYieldPct").innerText  = "(+" + currentYieldPct + "%)";
        document.getElementById("tabPrevTotal").innerText     = total.toLocaleString('en-US');
    }

    document.addEventListener("DOMContentLoaded", function() {
        var inputEl = document.getElementById("tabStakeAmount");
        if (inputEl) {
            inputEl.addEventListener("input", updateTabPreview);
        }
    });
</script>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/escrow-wallet-details.blade.php ENDPATH**/ ?>