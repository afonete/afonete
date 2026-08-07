<div class="wrapper">
<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="content-wrapper">
    <div class="container-fluid py-4">

        
        <div class="alert alert-dark d-flex align-items-center justify-content-between flex-wrap gap-2 p-3.5 mb-4 text-white shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border: 1px solid rgba(245, 158, 11, 0.4);">
            <div>
                <h3 class="font-weight-bold text-white mb-1">
                    <i class="fas fa-right-left text-warning mr-2"></i> Internal Exchange &amp; Wallet Center
                </h3>
                <small class="text-light opacity-90">Manage, swap, and transfer funds securely across all 6 internal user wallets and member accounts.</small>
            </div>
            <div class="bg-dark px-3 py-2 rounded text-right border border-warning" style="border-radius: 8px !important;">
                <small class="text-warning font-weight-bold d-block text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.8px;">Your 7-Digit Transfer Code</small>
                <strong class="text-white font-mono" style="font-size: 1.3rem; letter-spacing: 1.5px;"><?php echo e(Auth::user()->transfer_code ?? Auth::user()->getTransferCode()); ?></strong>
            </div>
        </div>

        <?php if(session('success')): ?><div class="alert alert-success mt-2" style="border-radius: 8px;"><?php echo e(session('success')); ?></div><?php endif; ?>
        <?php if(session('error')): ?><div class="alert alert-danger mt-2" style="border-radius: 8px;"><?php echo e(session('error')); ?></div><?php endif; ?>
        <?php if($errors->any()): ?><div class="alert alert-danger mt-2" style="border-radius: 8px;"><?php echo e($errors->first()); ?></div><?php endif; ?>

        
        <h5 class="font-weight-bold text-dark mb-3"><i class="fas fa-wallet text-primary mr-1"></i> Your 6 Internal Wallets</h5>
        <div class="row mb-4">
            
            <div class="col-12 col-sm-6 col-md-4 mb-3">
                <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; border-left: 4px solid #10b981 !important;">
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="font-weight-bold text-dark small"><i class="fas fa-money-bill-wave text-success mr-1"></i> 1. Cashout Wallet</span>
                                <span class="badge badge-success px-2 py-1" style="font-size: 10px;">WITHDRAWABLE</span>
                            </div>
                            <h3 class="font-weight-bold text-dark mb-1">$<?php echo e(number_format($cashoutBal, 2)); ?></h3>
                            <p class="text-muted small mb-2" style="font-size: 0.8rem;">Direct external withdrawals + User-to-User transfers + transfers to all internal wallets.</p>
                        </div>
                        <div class="pt-2 border-top text-muted" style="font-size: 0.72rem;">
                            <strong>Transfers To:</strong> Reward, Deposit, Fomo, Trading, Purchase, User-to-User.
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-12 col-sm-6 col-md-4 mb-3">
                <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; border-left: 4px solid #6366f1 !important;">
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="font-weight-bold text-dark small"><i class="fas fa-gift text-primary mr-1"></i> 2. Reward Wallet</span>
                                <span class="badge badge-primary px-2 py-1" style="font-size: 10px;">COMMISSION</span>
                            </div>
                            <h3 class="font-weight-bold text-dark mb-1">$<?php echo e(number_format($rewardBal, 2)); ?></h3>
                            <p class="text-muted small mb-2" style="font-size: 0.8rem;">Earned referral commissions and leadership rank bonuses.</p>
                        </div>
                        <div class="pt-2 border-top text-muted" style="font-size: 0.72rem;">
                            <strong>Transfers To:</strong> Deposit, Fomo, Trading, Purchase.
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-12 col-sm-6 col-md-4 mb-3">
                <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="font-weight-bold text-dark small"><i class="fas fa-wallet text-warning mr-1"></i> 3. Deposit Wallet</span>
                                <span class="badge badge-warning text-dark px-2 py-1" style="font-size: 10px;">PACKAGE SPENDABLE</span>
                            </div>
                            <h3 class="font-weight-bold text-dark mb-1">$<?php echo e(number_format($depositBal, 2)); ?></h3>
                            <p class="text-muted small mb-2" style="font-size: 0.8rem;">Package activations &amp; team member registration. Not directly withdrawable.</p>
                        </div>
                        <div class="pt-2 border-top text-muted" style="font-size: 0.72rem;">
                            <strong>Transfers To:</strong> Trading, Purchase.
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-12 col-sm-6 col-md-4 mb-3">
                <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="font-weight-bold text-dark small"><i class="fas fa-window-restore text-info mr-1"></i> 4. Fomo Wallet</span>
                                <span class="badge badge-info px-2 py-1" style="font-size: 10px;">FOMO EARN</span>
                            </div>
                            <h3 class="font-weight-bold text-dark mb-1">$<?php echo e(number_format($fomoBal, 2)); ?></h3>
                            <p class="text-muted small mb-2" style="font-size: 0.8rem;">Task earnings and ad campaign rewards.</p>
                        </div>
                        <div class="pt-2 border-top text-muted" style="font-size: 0.72rem;">
                            <strong>Transfers To:</strong> Deposit, Trading, Purchase.
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-12 col-sm-6 col-md-4 mb-3">
                <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; border-left: 4px solid #8b5cf6 !important;">
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="font-weight-bold text-dark small"><i class="fas fa-chart-line text-purple mr-1"></i> 5. Trading Wallet</span>
                                <span class="badge badge-secondary px-2 py-1" style="font-size: 10px;">TOKEN SWAP / BUY</span>
                            </div>
                            <h3 class="font-weight-bold text-dark mb-1">$<?php echo e(number_format($tradingBal, 2)); ?></h3>
                            <p class="text-muted small mb-2" style="font-size: 0.8rem;">Used to Swap Token &amp; Buy Tokens at $<?php echo e(number_format($swapPrice, 4)); ?>/token.</p>
                        </div>
                        <div class="pt-2 border-top text-muted" style="font-size: 0.72rem;">
                            <strong>Trading Actions:</strong> Swap Token, Buy Token → Available Tokens.
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-12 col-sm-6 col-md-4 mb-3">
                <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; border-left: 4px solid #f43f5e !important;">
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="font-weight-bold text-dark small"><i class="fas fa-shopping-cart text-danger mr-1"></i> 6. Purchase Wallet</span>
                                <span class="badge badge-dark px-2 py-1" style="font-size: 10px;">CHECKING</span>
                            </div>
                            <h3 class="font-weight-bold text-dark mb-1">$<?php echo e(number_format($purchaseBal, 2)); ?></h3>
                            <p class="text-muted small mb-2" style="font-size: 0.8rem;">Package purchasing and checking account balance.</p>
                        </div>
                        <div class="pt-2 border-top text-muted" style="font-size: 0.72rem;">
                            <strong>Transfers To:</strong> Fomo, Trading.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-light p-3">
                <ul class="nav nav-pills" id="exchangeTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold py-2 px-3" id="tab-internal-link" data-toggle="pill" href="#tab-internal" role="tab" style="border-radius: 8px;">
                            <i class="fas fa-exchange-alt mr-1"></i> Internal Wallet Transfer
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold py-2 px-3" id="tab-user-link" data-toggle="pill" href="#tab-user" role="tab" style="border-radius: 8px;">
                            <i class="fas fa-user-friends mr-1"></i> User-to-User Transfer
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold py-2 px-3" id="tab-trading-link" data-toggle="pill" href="#tab-trading" role="tab" style="border-radius: 8px;">
                            <i class="fas fa-coins mr-1"></i> Trading Wallet (Swap &amp; Buy Tokens)
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body p-4">
                <div class="tab-content" id="exchangeTabsContent">

                    
                    <div class="tab-pane fade show active" id="tab-internal" role="tabpanel">
                        <div class="row">
                            <div class="col-md-7">
                                <h5 class="font-weight-bold text-dark mb-1">Transfer Between Your Internal Wallets</h5>
                                <p class="text-muted small mb-3">Select source and destination wallets according to internal exchange rules.</p>

                                <form method="POST" action="<?php echo e(route('user.internal-exchange.transfer')); ?>" class="js-transaction-password-form">
                                    <?php echo csrf_field(); ?>

                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold text-dark">From Wallet (Source) <span class="text-danger">*</span></label>
                                        <select name="from_wallet" id="fromWalletSelect" required onchange="updateDestOptions()" class="form-control font-weight-bold" style="border-radius: 8px;">
                                            <option value="">-- Select Source Wallet --</option>
                                            <option value="CASHOUT">Cashout Wallet ($<?php echo e(number_format($cashoutBal, 2)); ?>)</option>
                                            <option value="REWARD">Reward Wallet ($<?php echo e(number_format($rewardBal, 2)); ?>)</option>
                                            <option value="DEPOSIT">Deposit Wallet ($<?php echo e(number_format($depositBal, 2)); ?>)</option>
                                            <option value="FOMO">Fomo Wallet ($<?php echo e(number_format($fomoBal, 2)); ?>)</option>
                                            <option value="PURCHASE">Purchase Wallet ($<?php echo e(number_format($purchaseBal, 2)); ?>)</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold text-dark">To Wallet (Destination) <span class="text-danger">*</span></label>
                                        <select name="to_wallet" id="toWalletSelect" required class="form-control font-weight-bold" style="border-radius: 8px;">
                                            <option value="">-- Select Destination Wallet --</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold text-dark">Transfer Amount ($ USD) <span class="text-danger">*</span></label>
                                        <input type="number" name="amount" min="0.01" step="0.01" required placeholder="0.00" class="form-control font-weight-bold" style="border-radius: 8px;">
                                    </div>

                                    <input type="hidden" name="transaction_password" class="js-transaction-password-value">

                                    <button type="submit" class="btn btn-primary font-weight-bold px-4 py-2" style="border-radius: 8px;">
                                        <i class="fas fa-paper-plane mr-1"></i> Execute Wallet Transfer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    
                    <div class="tab-pane fade" id="tab-user" role="tabpanel">
                        <div class="row">
                            <div class="col-md-7">
                                <h5 class="font-weight-bold text-dark mb-1">User-to-User Cashout Transfer</h5>
                                <p class="text-muted small mb-3">Transfer Cashout USD to another member using their Username or 7-Digit Transfer Code.</p>

                                <form method="POST" action="<?php echo e(route('user.internal-exchange.user-transfer')); ?>" class="js-transaction-password-form">
                                    <?php echo csrf_field(); ?>

                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold text-dark">Recipient Username OR 7-Digit Transfer Code <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" name="recipient" id="recipientInput" required placeholder="e.g. johndoe or 7000123" class="form-control font-mono font-weight-bold" style="border-radius: 8px 0 0 8px;">
                                            <div class="input-group-append">
                                                <button type="button" onclick="lookupUser()" class="btn btn-dark font-weight-bold" style="border-radius: 0 8px 8px 0;">
                                                    <i class="fas fa-search mr-1"></i> Verify
                                                </button>
                                            </div>
                                        </div>
                                        <div id="lookupPreview" class="small font-weight-bold mt-1.5 text-success d-none"></div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold text-dark">Transfer Amount ($ USD) <span class="text-danger">*</span></label>
                                        <input type="number" name="amount" min="0.01" step="0.01" max="<?php echo e($cashoutBal); ?>" required placeholder="Available Cashout: $<?php echo e(number_format($cashoutBal, 2)); ?>" class="form-control font-weight-bold" style="border-radius: 8px;">
                                    </div>

                                    <input type="hidden" name="transaction_password" class="js-transaction-password-value">

                                    <button type="submit" class="btn btn-success font-weight-bold px-4 py-2" style="border-radius: 8px;">
                                        <i class="fas fa-share mr-1"></i> Send Cashout Funds
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    
                    <div class="tab-pane fade" id="tab-trading" role="tabpanel">
                        <div class="row">

                            
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="p-3.5 bg-light rounded" style="border-radius: 10px; border: 1px solid #e2e8f0;">
                                    <h5 class="font-weight-bold text-dark mb-1"><i class="fas fa-sync text-primary mr-1"></i> Swap Token</h5>
                                    <p class="text-muted small mb-3">Convert Trading USD <i class="fas fa-arrow-right text-muted mx-1"></i> Available Tokens at <strong>$<?php echo e(number_format($swapPrice, 4)); ?>/token</strong>.</p>

                                    <form method="POST" action="<?php echo e(route('user.internal-exchange.trading-action')); ?>" class="js-transaction-password-form">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="action_type" value="swap_token">

                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold text-dark small">Trading USD Amount ($)</label>
                                            <input type="number" name="amount" id="swapAmountInput" onkeyup="calcTokens('swap')" min="0.01" step="0.01" max="<?php echo e($tradingBal); ?>" required placeholder="Available Trading: $<?php echo e(number_format($tradingBal, 2)); ?>" class="form-control font-weight-bold" style="border-radius: 8px;">
                                        </div>

                                        <div class="p-2.5 bg-white rounded border mb-3 small">
                                            <span class="text-muted d-block">Tokens Credited:</span>
                                            <strong id="swapTokenResult" class="text-primary font-weight-bold" style="font-size: 1.05rem;">0.00 Tokens</strong>
                                        </div>

                                        <input type="hidden" name="transaction_password" class="js-transaction-password-value">

                                        <button type="submit" class="btn btn-primary btn-block font-weight-bold py-2" style="border-radius: 8px;">
                                            <i class="fas fa-exchange-alt mr-1"></i> Execute Token Swap
                                        </button>
                                    </form>
                                </div>
                            </div>

                            
                            <div class="col-md-6">
                                <div class="p-3.5 bg-light rounded" style="border-radius: 10px; border: 1px solid #e2e8f0;">
                                    <h5 class="font-weight-bold text-dark mb-1"><i class="fas fa-shopping-bag text-success mr-1"></i> Buy Token</h5>
                                    <p class="text-muted small mb-3">Buy Available Tokens with Trading USD at <strong>$<?php echo e(number_format($tradingPrice, 4)); ?>/token</strong>.</p>

                                    <form method="POST" action="<?php echo e(route('user.internal-exchange.trading-action')); ?>" class="js-transaction-password-form">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="action_type" value="buy_token">

                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold text-dark small">Trading USD Amount ($)</label>
                                            <input type="number" name="amount" id="buyAmountInput" onkeyup="calcTokens('buy')" min="0.01" step="0.01" max="<?php echo e($tradingBal); ?>" required placeholder="Available Trading: $<?php echo e(number_format($tradingBal, 2)); ?>" class="form-control font-weight-bold" style="border-radius: 8px;">
                                        </div>

                                        <div class="p-2.5 bg-white rounded border mb-3 small">
                                            <span class="text-muted d-block">Tokens Credited:</span>
                                            <strong id="buyTokenResult" class="text-success font-weight-bold" style="font-size: 1.05rem;">0.00 Tokens</strong>
                                        </div>

                                        <input type="hidden" name="transaction_password" class="js-transaction-password-value">

                                        <button type="submit" class="btn btn-success btn-block font-weight-bold py-2" style="border-radius: 8px;">
                                            <i class="fas fa-coins mr-1"></i> Buy Tokens Now
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        
        <div class="card shadow-sm border-0 mt-4" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-light font-weight-bold py-3 text-dark">
                <i class="fas fa-history mr-1"></i> Internal Exchange Transaction History
            </div>
            <div class="card-body p-0">
                <?php if($transactions->isEmpty()): ?>
                    <p class="text-muted p-3 mb-0">No internal exchange transactions recorded yet.</p>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Transaction No</th>
                                <th>Type</th>
                                <th>Details</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $d = json_decode($t->transaction_details, true) ?: [];
                                ?>
                                <tr>
                                    <td><strong class="font-mono"><?php echo e($t->transaction_no); ?></strong></td>
                                    <td><span class="badge badge-dark px-2 py-1"><?php echo e($t->transaction_type); ?></span></td>
                                    <td>
                                        <?php if(isset($d['from_wallet']) && isset($d['to_wallet'])): ?>
                                            Transferred <strong>$<?php echo e(number_format($d['amount'] ?? 0, 2)); ?></strong> from <?php echo e($d['from_wallet']); ?> to <?php echo e($d['to_wallet']); ?>

                                        <?php elseif(isset($d['to_user'])): ?>
                                            Sent <strong>$<?php echo e(number_format($d['amount'] ?? 0, 2)); ?></strong> to @ <?php echo e($d['to_user']); ?> (Code: <?php echo e($d['to_code'] ?? '—'); ?>)
                                        <?php elseif(isset($d['from_user'])): ?>
                                            Received <strong>$<?php echo e(number_format($d['amount'] ?? 0, 2)); ?></strong> from @ <?php echo e($d['from_user']); ?> (Code: <?php echo e($d['from_code'] ?? '—'); ?>)
                                        <?php elseif(isset($d['tokens'])): ?>
                                            Converted <strong>$<?php echo e(number_format($d['usd_amount'] ?? 0, 2)); ?></strong> to <strong><?php echo e(number_format($d['tokens'], 2)); ?> Tokens</strong>
                                        <?php else: ?>
                                            <?php echo e($t->transaction_details); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td><small class="text-muted"><?php echo e($t->created_at ? $t->created_at->format('M d, Y H:i') : ''); ?></small></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-3 d-flex justify-content-center border-top">
                    <?php echo e($transactions->links('pagination::bootstrap-4')); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
</div>

<?php echo $__env->make('user.components.transaction-password-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script>
const walletMatrix = {
    'CASHOUT':    [ { code: 'REWARD', name: 'Reward Wallet' }, { code: 'DEPOSIT', name: 'Deposit Wallet' }, { code: 'FOMO', name: 'Fomo Wallet' }, { code: 'TRADING_WALLET', name: 'Trading Wallet' }, { code: 'PURCHASE', name: 'Purchase Wallet' } ],
    'REWARD':     [ { code: 'DEPOSIT', name: 'Deposit Wallet' }, { code: 'FOMO', name: 'Fomo Wallet' }, { code: 'TRADING_WALLET', name: 'Trading Wallet' }, { code: 'PURCHASE', name: 'Purchase Wallet' } ],
    'DEPOSIT':    [ { code: 'TRADING_WALLET', name: 'Trading Wallet' }, { code: 'PURCHASE', name: 'Purchase Wallet' } ],
    'FOMO':       [ { code: 'DEPOSIT', name: 'Deposit Wallet' }, { code: 'TRADING_WALLET', name: 'Trading Wallet' }, { code: 'PURCHASE', name: 'Purchase Wallet' } ],
    'PURCHASE':   [ { code: 'FOMO', name: 'Fomo Wallet' }, { code: 'TRADING_WALLET', name: 'Trading Wallet' } ]
};

function updateDestOptions() {
    const fromSelect = document.getElementById('fromWalletSelect');
    const toSelect   = document.getElementById('toWalletSelect');
    if (!fromSelect || !toSelect) return;

    const fromCode = fromSelect.value;
    toSelect.innerHTML = '<option value="">-- Select Destination Wallet --</option>';

    if (!fromCode || !walletMatrix[fromCode]) return;

    walletMatrix[fromCode].forEach(function(item) {
        const opt = document.createElement('option');
        opt.value = item.code;
        opt.textContent = item.name + ' (' + item.code + ')';
        toSelect.appendChild(opt);
    });
}

function calcTokens(type) {
    const swapPrice    = <?php echo e($swapPrice); ?>;
    const tradingPrice = <?php echo e($tradingPrice); ?>;
    if (type === 'swap') {
        const amt = parseFloat(document.getElementById('swapAmountInput').value) || 0;
        const tokens = amt / swapPrice;
        document.getElementById('swapTokenResult').textContent = tokens.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 4 }) + ' Tokens';
    } else if (type === 'buy') {
        const amt = parseFloat(document.getElementById('buyAmountInput').value) || 0;
        const tokens = amt / tradingPrice;
        document.getElementById('buyTokenResult').textContent = tokens.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 4 }) + ' Tokens';
    }
}

function lookupUser() {
    const input = document.getElementById('recipientInput');
    const preview = document.getElementById('lookupPreview');
    if (!input || !preview) return;

    const val = input.value.trim();
    if (!val) return;

    preview.textContent = 'Searching user...';
    preview.className = 'small font-weight-bold mt-1.5 text-muted d-block';

    fetch('<?php echo e(route("user.token.transfer.lookup")); ?>?identifier=' + encodeURIComponent(val), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.found) {
            preview.innerHTML = '<i class="fas fa-check-circle text-success mr-1"></i> Verified Recipient: <strong>@' + data.username + '</strong> (' + data.name + ') · Code: <strong>' + (data.transfer_code || 'N/A') + '</strong>';
            preview.className = 'small font-weight-bold mt-1.5 text-success d-block';
        } else {
            preview.innerHTML = '<i class="fas fa-times-circle text-danger mr-1"></i> User not found. Verify username or 7-digit Transfer Code.';
            preview.className = 'small font-weight-bold mt-1.5 text-danger d-block';
        }
    })
    .catch(() => {
        preview.innerHTML = '<i class="fas fa-exclamation-triangle text-danger mr-1"></i> Lookup failed. Please try again.';
        preview.className = 'small font-weight-bold mt-1.5 text-danger d-block';
    });
}
</script><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/internal-exchange.blade.php ENDPATH**/ ?>