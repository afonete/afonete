<?php
use App\Models\Deposits;
use Illuminate\Support\Facades\Auth;
use App\Models\DepositWallet;

$user     = Auth::user();
$deposits = $user->deposits()->latest()->take(10)->get();

// Three separate collections so each tab renders its own list.
$cryptoWallets      = DepositWallet::activeOfType('crypto');
$advcashWallets     = DepositWallet::activeOfType('advcash');
$perfectMoneyWallets = DepositWallet::activeOfType('perfect_money');

$minDeposit = $minDeposit ?? (float) (\App\Models\WithdrawalSetting::current()->min_deposit_amount ?? 10);
$directDepositAddress = $directDepositAddress ?? null;
?>
<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="content-wrapper text-white" style="background:#1c1d20;">

    <div class="tabs tab_links my-2" style="border-bottom: 1px solid white">
        <span class="links_tabs d-flex">
            <a href="<?php echo e(route('user.dashboard.deposit')); ?>" class="fomoLink text-white" id="tabs">
                <i class="fa-regular fa-address-card"></i> Deposit
            </a>
            <a href="<?php echo e(route('user.dashboard.withdraw')); ?>" class="fomoLink text-white">
                <i class="fa-solid fa-money-bill-transfer"></i> Withdraw
            </a>
        </span>
    </div>

        <div class="container-fluid py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="main_head mb-0" style="font-color: white">Deposit to Your Account</h1>
            <a href="<?php echo e(route('user.deposits.history')); ?>" class="btn btn-sm btn-outline-light">
                <i class="fas fa-list mr-1"></i> Full Deposit History
            </a>
        </div>

        <?php if(session('message')): ?><div class="alert alert-success mx-3"><?php echo e(session('message')); ?></div><?php endif; ?>
        <?php if(session('error')): ?><div class="alert alert-danger mx-3"><?php echo e(session('error')); ?></div><?php endif; ?>
        <?php if($errors->any()): ?><div class="alert alert-danger mx-3"><?php echo e($errors->first()); ?></div><?php endif; ?>

        
        <div class="px-3">
            <ul class="nav nav-pills mb-3" role="tablist" id="depositTabs">
                <li class="nav-item">
                    <a class="nav-link active text-white" data-toggle="pill" href="#tab-direct-tron">
                        <i class="fas fa-bolt mr-1"></i>Auto USDT TRC20
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" data-toggle="pill" href="#tab-crypto">
                        <i class="fab fa-bitcoin mr-1"></i>Manual Crypto <small class="text-muted">(<?php echo e($cryptoWallets->count()); ?>)</small>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" data-toggle="pill" href="#tab-advcash">
                        <i class="fas fa-money-bill-wave mr-1"></i>Advcash <small class="text-muted">(<?php echo e($advcashWallets->count()); ?>)</small>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" data-toggle="pill" href="#tab-perfect-money">
                        <i class="fas fa-coins mr-1"></i>Perfect Money <small class="text-muted">(<?php echo e($perfectMoneyWallets->count()); ?>)</small>
                    </a>
                </li>
                
            </ul>

            <div class="tab-content">
                
                <div class="tab-pane fade show active" id="tab-direct-tron">
                    <div class="card mb-3" style="background:#111; border:1px solid #333; border-radius:8px;">
                        <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                            <h5 class="text-white mb-0"><i class="fas fa-bolt text-warning mr-2"></i>Automatic USDT TRC20 Deposit</h5>
                            <small class="text-muted">Send USDT on the TRON/TRC20 network to your unique address. The system checks TronGrid and credits your DEPOSIT balance automatically.</small>
                        </div>
                        <div class="card-body">
                            <?php if($directDepositAddress): ?>
                                <?php
                                    $directPayload = $directDepositAddress->address;
                                    $directQr = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' . urlencode($directPayload) . '&margin=10';
                                ?>
                                <div class="row align-items-center">
                                    <div class="col-md-4 text-center mb-3">
                                        <img src="<?php echo e($directQr); ?>" alt="USDT TRC20 QR" style="width:180px; height:180px; background:#fff; padding:8px; border-radius:8px;">
                                        <div class="small text-muted mt-2">Scan with a TRON-compatible wallet</div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="alert alert-info">
                                            <strong>Network:</strong> TRON / TRC20<br>
                                            <strong>Currency:</strong> USDT<br>
                                            <strong>Minimum:</strong> $<?php echo e(number_format($minDeposit, 2)); ?>

                                        </div>
                                        <label class="text-white font-weight-bold">Your unique deposit address</label>
                                        <div class="d-flex align-items-center">
                                            <code id="directTronAddress" class="text-warning flex-grow-1" style="word-break:break-all; font-size:14px; background:#000; padding:8px 10px; border-radius:4px;"><?php echo e($directDepositAddress->address); ?></code>
                                            <button type="button" class="btn btn-sm btn-outline-warning ml-2" data-copy-target="#directTronAddress">
                                                <i class="fas fa-copy"></i> Copy
                                            </button>
                                        </div>
                                        <div class="small text-danger mt-3">
                                            ⚠ Only send <strong>USDT TRC20</strong>. Do not send ERC20/BEP20/other tokens to this address.
                                        </div>
                                        <div class="small text-muted mt-2">
                                            Deposits usually appear after the scheduled blockchain scanner runs. Keep your transaction hash for support.
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning mb-0">
                                    Automatic address generation is not available right now. The signer service may not be running/configured yet. You can still use manual crypto deposit below.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="tab-pane fade" id="tab-crypto">
                    <?php if($cryptoWallets->isEmpty()): ?>
                        <div class="alert alert-warning">No crypto deposit wallets configured yet. Contact admin.</div>
                    <?php else: ?>
                        <div class="row">
                            
                            <div class="col-md-5 mb-3">
                                <label class="text-white font-weight-bold">1. Pick your crypto &amp; network</label>
                                <select id="cryptoPicker" class="form-control" style="background:#222; color:white; border-color:#555;">
                                    <?php $__currentLoopData = $cryptoWallets->groupBy('currency'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency => $rows): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <optgroup label="<?php echo e($currency); ?> (<?php echo e($rows->count()); ?> network<?php echo e($rows->count() > 1 ? 's' : ''); ?>)">
                                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($w->id); ?>"
                                                        data-address="<?php echo e($w->wallet_address); ?>"
                                                        data-network="<?php echo e($w->network); ?>"
                                                        data-currency="<?php echo e($w->currency); ?>"
                                                        data-qr="<?php echo e($w->qrImageUrl()); ?>"
                                                        data-min="<?php echo e($w->min_amount); ?>">
                                                    <?php echo e($currency); ?> — <?php echo e($w->network); ?> <?php if(!$w->is_active): ?> (inactive) <?php endif; ?>
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </optgroup>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small class="text-muted d-block mt-1">
                                    <i class="fas fa-info-circle"></i>
                                    Each crypto supports one or more networks. Pick the one your wallet supports.
                                </small>
                            </div>

                            
                            <div class="col-md-7 mb-3">
                                <label class="text-white font-weight-bold">2. Send to this address</label>
                                <div class="p-3" style="background:#0d0d0d; border:1px solid #444; border-radius:8px;">
                                    <div class="d-flex flex-wrap align-items-center">
                                        
                                        <div class="mr-3 mb-2 text-center">
                                            <img id="cryptoQrImg"
                                                 src="<?php echo e($cryptoWallets->first()?->qrImageUrl()); ?>"
                                                 alt="QR code"
                                                 style="width:160px; height:160px; background:#fff; padding:6px; border-radius:6px;">
                                            <div class="small text-muted mt-1" id="cryptoQrCaption">
                                                Scan with your wallet app
                                            </div>
                                        </div>
                                        
                                        <div class="flex-grow-1" style="min-width:240px;">
                                            <div class="small text-muted" id="cryptoAddressLabel">Address (<?php echo e($cryptoWallets->first()?->currency); ?> · <?php echo e($cryptoWallets->first()?->network); ?>)</div>
                                            <div class="d-flex align-items-center mt-1">
                                                <code id="cryptoAddressText"
                                                      class="text-warning flex-grow-1"
                                                      style="word-break:break-all; font-size:13px; background:#000; padding:6px 8px; border-radius:4px;">
                                                    <?php echo e($cryptoWallets->first()?->wallet_address); ?>

                                                </code>
                                                <button type="button" id="copyCryptoAddressBtn"
                                                        class="btn btn-sm btn-outline-warning ml-2"
                                                        data-copy-target="#cryptoAddressText">
                                                    <i class="fas fa-copy"></i> Copy
                                                </button>
                                            </div>
                                            <div class="small text-muted mt-2">
                                                Min: $<span id="cryptoMinAmount"><?php echo e(number_format($cryptoWallets->first()?->min_amount ?? 10, 2)); ?></span>
                                                <span id="cryptoMaxWrap" style="display:none"> · Max: $<span id="cryptoMaxAmount"></span></span>
                                            </div>
                                            <div class="small text-danger mt-1" id="cryptoWarning">
                                                ⚠ Only send <?php echo e($cryptoWallets->first()?->currency ?? 'USDT'); ?> on
                                                <strong id="cryptoNetworkName"><?php echo e($cryptoWallets->first()?->network ?? 'TRC-20'); ?></strong>.
                                                Wrong network = lost funds.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="card" style="background:#111; border:1px solid #333; border-radius:8px;">
                            <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                                <h5 class="text-white mb-0">3. Submit your deposit request</h5>
                                <small class="text-muted">After sending, fill this form so admin can verify and credit your account.</small>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="<?php echo e(route('user.payment.savedeposits')); ?>" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="paymentMethod" value="MANUAL_CRYPTO">
                                    <input type="hidden" name="wallet_id"   id="walletIdInput"   value="<?php echo e($cryptoWallets->first()?->id); ?>">
                                    <input type="hidden" name="network"     id="networkInput"   value="<?php echo e($cryptoWallets->first()?->network); ?>">
                                    <input type="hidden" name="currency"     id="currencyInput"  value="<?php echo e($cryptoWallets->first()?->currency); ?>">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="text-white">Amount (USD) <span class="text-danger">*</span></label>
                                            <input type="number" name="amount" min="<?php echo e($minDeposit); ?>" step="0.01" required
                                                   class="form-control" style="background:#222; color:white; border-color:#555;"
                                                   placeholder="Min $<?php echo e(number_format($minDeposit, 0)); ?>">
                                        </div>
                                        <div class="col-md-8">
                                            <label class="text-white">Your Sender Wallet Address <span class="text-danger">*</span></label>
                                            <input type="text" name="paymentaccount" required
                                                   class="form-control" style="background:#222; color:white; border-color:#555;"
                                                   placeholder="The wallet address you sent FROM">
                                        </div>

                                        <div class="col-md-12 mt-2">
                                            <label class="text-white">Proof of Payment <small class="text-muted">(screenshot / PDF, max 4MB)</small></label>
                                            <input type="file" name="proof_of_payment" accept=".jpg,.jpeg,.png,.pdf"
                                                   class="form-control" style="background:#222; color:white; border-color:#555;">
                                            <small class="text-muted">Speeds up admin verification.</small>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <button type="submit" class="btn btn-warning btn-block font-weight-bold">
                                                <i class="fas fa-paper-plane mr-1"></i> Submit Deposit Request
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                
                <div class="tab-pane fade" id="tab-advcash">
                    <?php if($advcashWallets->isEmpty()): ?>
                        <div class="alert alert-warning">Advcash deposits are not configured yet.</div>
                    <?php else: ?>
                        <?php $__currentLoopData = $advcashWallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card mb-3" style="background:#111; border:1px solid #333; border-radius:8px;">
                                <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                                    <h5 class="text-white mb-0">
                                        <i class="fas fa-money-bill-wave mr-2 text-warning"></i>
                                        Advcash — <?php echo e($w->currency); ?>

                                        <?php if(!$w->is_active): ?>
                                            <span class="badge badge-secondary ml-2">Inactive</span>
                                        <?php endif; ?>
                                    </h5>
                                    <small class="text-muted"><?php echo e($w->label); ?></small>
                                </div>
                                <div class="card-body">
                                    
                                    <div class="p-3 mb-3" style="background:#0d0d0d; border:1px solid #444; border-radius:8px;">
                                        <div class="small text-muted">Advcash Account Number (<?php echo e($w->currency); ?>)</div>
                                        <div class="d-flex align-items-center mt-1">
                                            <code id="advcashAddr_<?php echo e($w->id); ?>"
                                                  class="text-warning flex-grow-1"
                                                  style="word-break:break-all; font-size:16px; background:#000; padding:8px 10px; border-radius:4px;">
                                                <?php echo e($w->wallet_address ?: '(not set yet)'); ?>

                                            </code>
                                            <button type="button" class="btn btn-sm btn-outline-warning ml-2"
                                                    data-copy-target="#advcashAddr_<?php echo e($w->id); ?>"
                                                    <?php echo e($w->wallet_address ? '' : 'disabled'); ?>>
                                                <i class="fas fa-copy"></i> Copy
                                            </button>
                                        </div>
                                    </div>

                                    
                                    <?php if($w->instructions): ?>
                                        <div class="p-3" style="background:#0d0d0d; border:1px solid #444; border-radius:8px;">
                                            <strong class="text-white">Instructions:</strong>
                                            <ol class="text-light mt-2 mb-0 pl-3" style="font-size:14px;">
                                                <?php $__currentLoopData = preg_split('/\r?\n/', $w->instructions); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if(trim($line) !== ''): ?>
                                                        <li class="mb-1"><?php echo trim($line); ?></li>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ol>
                                        </div>
                                    <?php endif; ?>

                                    <?php if($w->is_active && $w->wallet_address): ?>
                                        
                                        <form method="POST" action="<?php echo e(route('user.payment.savedeposits')); ?>"
                                              enctype="multipart/form-data" class="mt-3">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="paymentMethod" value="MANUAL_ADVCASH">
                                            <input type="hidden" name="wallet_id"   value="<?php echo e($w->id); ?>">
                                            <input type="hidden" name="currency"    value="<?php echo e($w->currency); ?>">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="text-white">Amount (USD) <span class="text-danger">*</span></label>
                                                    <input type="number" name="amount" min="<?php echo e($minDeposit); ?>" step="0.01" required
                                                           class="form-control" style="background:#222; color:white; border-color:#555;">
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="text-white">Your Advcash Account Number <span class="text-danger">*</span></label>
                                                    <input type="text" name="paymentaccount" required
                                                           class="form-control" style="background:#222; color:white; border-color:#555;"
                                                           placeholder="Your Advcash account (so admin can verify)">
                                                </div>
                                                <div class="col-md-12 mt-2">
                                                    <label class="text-white">Proof of Payment <small class="text-muted">(screenshot / PDF, max 4MB)</small></label>
                                                    <input type="file" name="proof_of_payment" accept=".jpg,.jpeg,.png,.pdf"
                                                           class="form-control" style="background:#222; color:white; border-color:#555;">
                                                </div>
                                                <div class="col-md-12 mt-3">
                                                    <button type="submit" class="btn btn-warning btn-block font-weight-bold">
                                                        <i class="fas fa-paper-plane mr-1"></i> Submit Advcash Deposit Request
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>

                
                <div class="tab-pane fade" id="tab-perfect-money">
                    <?php if($perfectMoneyWallets->isEmpty()): ?>
                        <div class="alert alert-warning">Perfect Money deposits are not configured yet.</div>
                    <?php else: ?>
                        <?php $__currentLoopData = $perfectMoneyWallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card mb-3" style="background:#111; border:1px solid #333; border-radius:8px;">
                                <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                                    <h5 class="text-white mb-0">
                                        <i class="fas fa-coins mr-2 text-warning"></i>
                                        Perfect Money — <?php echo e($w->currency); ?>

                                        <?php if(!$w->is_active): ?>
                                            <span class="badge badge-secondary ml-2">Inactive</span>
                                        <?php endif; ?>
                                    </h5>
                                    <small class="text-muted"><?php echo e($w->label); ?></small>
                                </div>
                                <div class="card-body">
                                    <div class="p-3 mb-3" style="background:#0d0d0d; border:1px solid #444; border-radius:8px;">
                                        <div class="small text-muted">Perfect Money Account Number (<?php echo e($w->currency); ?>)</div>
                                        <div class="d-flex align-items-center mt-1">
                                            <code id="pmAddr_<?php echo e($w->id); ?>"
                                                  class="text-warning flex-grow-1"
                                                  style="word-break:break-all; font-size:16px; background:#000; padding:8px 10px; border-radius:4px;">
                                                <?php echo e($w->wallet_address ?: '(not set yet)'); ?>

                                            </code>
                                            <button type="button" class="btn btn-sm btn-outline-warning ml-2"
                                                    data-copy-target="#pmAddr_<?php echo e($w->id); ?>"
                                                    <?php echo e($w->wallet_address ? '' : 'disabled'); ?>>
                                                <i class="fas fa-copy"></i> Copy
                                            </button>
                                        </div>
                                    </div>

                                    <?php if($w->instructions): ?>
                                        <div class="p-3" style="background:#0d0d0d; border:1px solid #444; border-radius:8px;">
                                            <strong class="text-white">Instructions:</strong>
                                            <ol class="text-light mt-2 mb-0 pl-3" style="font-size:14px;">
                                                <?php $__currentLoopData = preg_split('/\r?\n/', $w->instructions); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if(trim($line) !== ''): ?>
                                                        <li class="mb-1"><?php echo trim($line); ?></li>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ol>
                                        </div>
                                    <?php endif; ?>

                                    <?php if($w->is_active && $w->wallet_address): ?>
                                        <form method="POST" action="<?php echo e(route('user.payment.savedeposits')); ?>"
                                              enctype="multipart/form-data" class="mt-3">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="paymentMethod" value="MANUAL_PERFECT_MONEY">
                                            <input type="hidden" name="wallet_id"   value="<?php echo e($w->id); ?>">
                                            <input type="hidden" name="currency"    value="<?php echo e($w->currency); ?>">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="text-white">Amount (USD) <span class="text-danger">*</span></label>
                                                    <input type="number" name="amount" min="<?php echo e($minDeposit); ?>" step="0.01" required
                                                           class="form-control" style="background:#222; color:white; border-color:#555;">
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="text-white">Your Perfect Money Account <span class="text-danger">*</span></label>
                                                    <input type="text" name="paymentaccount" required
                                                           class="form-control" style="background:#222; color:white; border-color:#555;"
                                                           placeholder="Your Perfect Money account number">
                                                </div>
                                                <div class="col-md-12 mt-2">
                                                    <label class="text-white">Proof of Payment <small class="text-muted">(screenshot / PDF, max 4MB)</small></label>
                                                    <input type="file" name="proof_of_payment" accept=".jpg,.jpeg,.png,.pdf"
                                                           class="form-control" style="background:#222; color:white; border-color:#555;">
                                                </div>
                                                <div class="col-md-12 mt-3">
                                                    <button type="submit" class="btn btn-warning btn-block font-weight-bold">
                                                        <i class="fas fa-paper-plane mr-1"></i> Submit Perfect Money Deposit Request
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>

                
                
            </div>
        </div>

        
        <div class="mt-4 px-3">
            <h4 class="text-white mb-3"><i class="fas fa-bolt mr-2 text-warning"></i>Automatic USDT TRC-20 Deposit</h4>
            <div class="row">
                <?php if($directDepositAddress): ?>
                    <div class="col-md-8 mb-3">
                        <div class="card" style="background:#111; border:1px solid #1e3a8a; border-radius:8px;">
                            <div class="card-header" style="background:#0f172a; border-bottom:1px solid #1e3a8a;">
                                <h5 class="text-white mb-0">USDT TRC-20 Automatic Deposit</h5>
                                <small class="text-muted">TRON / TRC-20 only</small>
                            </div>
                            <div class="card-body">
                                <code id="tronAutoDepositAddr" class="d-block text-info" style="word-break:break-all; background:#000; padding:10px; border-radius:4px;"><?php echo e($directDepositAddress->address); ?></code>
                                <button type="button" class="btn btn-sm btn-outline-info mt-2" data-copy-target="#tronAutoDepositAddr"><i class="fas fa-copy mr-1"></i>Copy TRC-20 Address</button>
                                <div class="alert alert-warning py-2 small mt-3 mb-0"><strong>Important:</strong> Only send USDT on <strong>TRC-20 / TRON</strong>. Network fees must be paid separately by your wallet/exchange so the full deposit amount arrives.</div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="mt-4 px-3">
            <h4 class="text-white mb-3"><i class="fas fa-history mr-2"></i>Your Deposit History</h4>
            <?php if($deposits->isEmpty()): ?>
                <p class="text-muted">No deposits yet.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-dark table-bordered table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Transaction ID</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Network</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Proof</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $deposits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $dep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($i + 1); ?></td>
                            <td><small><?php echo e($dep->transaction_id ?? '—'); ?></small></td>
                            <td>$<?php echo e(number_format($dep->amount_deposited, 2)); ?></td>
                            <td><?php echo e($dep->deposit_method ?? '—'); ?></td>
                            <td><?php echo e($dep->network ?? '—'); ?></td>
                            <td>
                                <?php
                                    $sc = ['pending'=>'warning','approved'=>'success','rejected'=>'danger','under-review'=>'info','cancelled'=>'secondary'];
                                    $badge = $sc[$dep->status] ?? 'secondary';
                                ?>
                                <span class="badge badge-<?php echo e($badge); ?>"><?php echo e(ucfirst($dep->status)); ?></span>
                                <?php if($dep->comment): ?>
                                    <small class="text-muted d-block"><?php echo e($dep->comment); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><small><?php echo e($dep->created_at->format('d M Y')); ?></small></td>
                            <td>
                                <?php if($dep->proof_of_payment): ?>
                                    <a href="<?php echo e(asset('storage/'.$dep->proof_of_payment)); ?>" target="_blank" class="btn btn-xs btn-outline-light">View</a>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
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


<script>
(function () {
    // ── Copy to clipboard ──
    document.querySelectorAll('[data-copy-target]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const sel = btn.getAttribute('data-copy-target');
            const el = document.querySelector(sel);
            if (!el) return;
            const text = el.textContent.trim();
            const fallback = function () {
                const ta = document.createElement('textarea');
                ta.value = text;
                document.body.appendChild(ta);
                ta.select();
                try { document.execCommand('copy'); } catch (e) {}
                document.body.removeChild(ta);
            };
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).catch(fallback);
            } else {
                fallback();
            }
            // Visual feedback
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i> Copied';
            btn.classList.add('btn-success');
            btn.classList.remove('btn-outline-warning');
            setTimeout(function () {
                btn.innerHTML = original;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-warning');
            }, 1400);
        });
    });

    // ── Crypto picker — switch address + QR + network ──
    const picker = document.getElementById('cryptoPicker');
    if (picker) {
        const update = function () {
            const opt = picker.options[picker.selectedIndex];
            if (!opt) return;
            const address = opt.getAttribute('data-address') || '';
            const network = opt.getAttribute('data-network') || '';
            const currency = opt.getAttribute('data-currency') || '';
            const qr      = opt.getAttribute('data-qr') || '';
            const min     = opt.getAttribute('data-min') || '10';

            document.getElementById('cryptoAddressText').textContent = address;
            document.getElementById('cryptoQrImg').src = qr;
            document.getElementById('cryptoQrImg').alt = currency + ' ' + network + ' QR';
            document.getElementById('cryptoQrCaption').textContent =
                'Scan with your ' + currency + ' wallet';
            document.getElementById('cryptoMinAmount').textContent = parseFloat(min).toFixed(2);
            document.getElementById('cryptoNetworkName').textContent = network;
            
            // FIX ADDRESS ISSUE OF NOT SHOWING ONLY : Address (USDT · BEP-20)
            document.getElementById('cryptoAddressLabel').textContent =
                `Address (${currency} · ${network})`;

            // Sync hidden inputs in the deposit form
            const wId = document.getElementById('walletIdInput'); if (wId) wId.value = opt.value;
            const nIn = document.getElementById('networkInput');  if (nIn) nIn.value = network;
            const cIn = document.getElementById('currencyInput'); if (cIn) cIn.value = currency;
        };
        picker.addEventListener('change', update);
        update();
    }
})();
</script>

<?php echo $__env->make('user.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/balance/deposit.blade.php ENDPATH**/ ?>