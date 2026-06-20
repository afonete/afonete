<?php
use App\Models\Wallet;
use App\Models\withdrawals as WithdrawalModel;
use Illuminate\Support\Facades\Auth;
$user    = Auth::user();
$wallet  = Wallet::where('user', $user->user)->first();
$history = WithdrawalModel::where('user_id', $user->id)->latest()->take(15)->get();
?>
<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="content-wrapper text-white" style="background:#1c1d20;">

    <div class="tabs tab_links my-2" style="border-bottom:1px solid white">
        <span class="links_tabs d-flex">
            <a href="<?php echo e(route('user.dashboard.deposit')); ?>" class="fomoLink text-white">
                <i class="fa-regular fa-address-card"></i> Deposit
            </a>
            <a href="<?php echo e(route('user.dashboard.withdraw')); ?>" class="fomoLink text-white" id="tabs">
                <i class="fa-solid fa-money-bill-transfer"></i> Withdraw
            </a>
        </span>
    </div>

    <div class="container-fluid py-3">
        <h1 class="main_head">Withdraw from Your Account</h1>

        <?php if(session('success')): ?><div class="alert alert-success mx-3"><?php echo e(session('success')); ?></div><?php endif; ?>
        <?php if(session('error')): ?><div class="alert alert-danger mx-3"><?php echo e(session('error')); ?></div><?php endif; ?>
        <?php if($errors->any()): ?><div class="alert alert-danger mx-3"><?php echo e($errors->first()); ?></div><?php endif; ?>

        
        <div class="d-flex justify-content-between mb-3 p-3" style="background:#0d0d0d; border-radius:8px;">
            <span class="text-muted">Available Balance (CASHOUT):</span>
            <span class="font-weight-bold text-success" style="font-size:1.2rem;">$<?php echo e(number_format($availlableBalance, 2)); ?></span>
        </div>

        <?php if($availlableBalance < $settings->min_amount): ?>
            <div class="alert alert-warning mx-3">
                <i class="fas fa-info-circle"></i>
                Minimum withdrawal is $<?php echo e(number_format($settings->min_amount, 2)); ?>.
                Per-transaction max $<?php echo e(number_format($settings->max_per_transaction, 2)); ?>.
                <?php if($settings->daily_limit): ?>   · Daily limit $<?php echo e(number_format($settings->daily_limit, 2)); ?> <?php endif; ?>
                <?php if($settings->monthly_limit): ?> · Monthly limit $<?php echo e(number_format($settings->monthly_limit, 2)); ?> <?php endif; ?>
            </div>
        <?php endif; ?>

        
        <div class="px-3">
            <ul class="nav nav-pills mb-3" role="tablist" id="withdrawTabs">
                <li class="nav-item">
                    <a class="nav-link active text-white" data-toggle="pill" href="#tab-wcrypto">
                        <i class="fab fa-bitcoin mr-1"></i>Crypto
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" data-toggle="pill" href="#tab-wadvcash">
                        <i class="fas fa-money-bill-wave mr-1"></i>Advcash
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" data-toggle="pill" href="#tab-wperfectmoney">
                        <i class="fas fa-coins mr-1"></i>Perfect Money
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" data-toggle="pill" href="#tab-winstant">
                        <i class="fas fa-bolt mr-1"></i>Instant (Plisio)
                    </a>
                </li>
            </ul>

            <div class="tab-content">

                
                <div class="tab-pane fade show active" id="tab-wcrypto">
                    <div class="card" style="background:#111; border:1px solid #333; border-radius:8px;">
                        <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                            <h4 class="text-white mb-0">
                                <i class="fab fa-bitcoin mr-2 text-warning"></i>Crypto Withdrawal
                                <small class="text-muted">— processed by admin within 24–48 hrs</small>
                            </h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?php echo e(route('user.withdraw.manual')); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="method" value="crypto">

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="text-white font-weight-bold">1. Pick your crypto &amp; network</label>
                                        <select id="wCryptoPicker" name="currency" class="form-control" required
                                                style="background:#222; color:white; border-color:#555;">
                                            <?php $__currentLoopData = $cryptoByCurrency; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency => $rows): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <optgroup label="<?php echo e($currency); ?> (<?php echo e($rows->count()); ?> network<?php echo e($rows->count() > 1 ? 's' : ''); ?>)">
                                                    <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($currency); ?>" data-network="<?php echo e($w->network); ?>">
                                                            <?php echo e($currency); ?> — <?php echo e($w->network); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </optgroup>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <input type="hidden" name="network" id="wCryptoNetworkInput" value="<?php echo e(optional($cryptoByCurrency->first())->first()?->network); ?>">
                                        <small class="text-muted d-block mt-1">
                                            <i class="fas fa-info-circle"></i>
                                            Pick the network your receiving wallet supports.
                                        </small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="text-white font-weight-bold">2. Destination wallet address</label>
                                        <input type="text" name="address" required
                                               class="form-control" style="background:#222; color:white; border-color:#555;"
                                               value="<?php echo e($wallet->wallet ?? ''); ?>"
                                               placeholder="Your wallet address on the chosen network">
                                        <small class="text-muted">Must match the network you selected above.</small>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="text-white">Amount (USD) <span class="text-danger">*</span></label>
                                        <input type="number" name="amount" required step="0.01"
                                               min="<?php echo e($settings->min_amount); ?>"
                                               max="<?php echo e($settings->max_per_transaction); ?>"
                                               class="form-control" style="background:#222; color:white; border-color:#555;"
                                               placeholder="Min $<?php echo e(number_format($settings->min_amount, 2)); ?> · Max $<?php echo e(number_format($settings->max_per_transaction, 2)); ?>">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="text-white">Notes for admin <small class="text-muted">(optional)</small></label>
                                        <input type="text" name="notes" maxlength="255"
                                               class="form-control" style="background:#222; color:white; border-color:#555;"
                                               placeholder="e.g. please send before Friday">
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <button type="submit" class="btn btn-warning btn-block font-weight-bold"
                                                <?php echo e($availlableBalance < $settings->min_amount ? 'disabled' : ''); ?>>
                                            <i class="fas fa-paper-plane mr-1"></i> Submit Crypto Withdrawal Request
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                
                <div class="tab-pane fade" id="tab-wadvcash">
                    <div class="card" style="background:#111; border:1px solid #333; border-radius:8px;">
                        <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                            <h4 class="text-white mb-0">
                                <i class="fas fa-money-bill-wave mr-2 text-warning"></i>Advcash Withdrawal
                                <small class="text-muted">— processed by admin within 24–48 hrs</small>
                            </h4>
                        </div>
                        <div class="card-body">
                            <?php if($advcashActive->isEmpty()): ?>
                                <div class="alert alert-warning">Advcash withdrawals are not currently configured.</div>
                            <?php else: ?>
                            <form method="POST" action="<?php echo e(route('user.withdraw.manual')); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="method" value="advcash">
                                <input type="hidden" name="network" value="ADVCASH">

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="text-white">Currency</label>
                                        <select name="currency" class="form-control" required style="background:#222; color:white; border-color:#555;">
                                            <?php $__currentLoopData = $advcashActive; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($w->currency); ?>"><?php echo e($w->currency); ?> — <?php echo e($w->label); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="text-white">Your Advcash Account Number <span class="text-danger">*</span></label>
                                        <input type="text" name="address" required
                                               class="form-control" style="background:#222; color:white; border-color:#555;"
                                               placeholder="Your Advcash account (so admin can send funds to you)">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i>
                                            We'll send your withdrawal to this account number. Make sure it's correct.
                                        </small>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="text-white">Amount (USD) <span class="text-danger">*</span></label>
                                        <input type="number" name="amount" required step="0.01"
                                               min="<?php echo e($settings->min_amount); ?>"
                                               max="<?php echo e($settings->max_per_transaction); ?>"
                                               class="form-control" style="background:#222; color:white; border-color:#555;">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="text-white">Notes for admin <small class="text-muted">(optional)</small></label>
                                        <input type="text" name="notes" maxlength="255"
                                               class="form-control" style="background:#222; color:white; border-color:#555;"
                                               placeholder="e.g. please use USD wallet">
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <button type="submit" class="btn btn-warning btn-block font-weight-bold"
                                                <?php echo e($availlableBalance < $settings->min_amount ? 'disabled' : ''); ?>>
                                            <i class="fas fa-paper-plane mr-1"></i> Submit Advcash Withdrawal Request
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="tab-pane fade" id="tab-wperfectmoney">
                    <div class="card" style="background:#111; border:1px solid #333; border-radius:8px;">
                        <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                            <h4 class="text-white mb-0">
                                <i class="fas fa-coins mr-2 text-warning"></i>Perfect Money Withdrawal
                                <small class="text-muted">— processed by admin within 24–48 hrs</small>
                            </h4>
                        </div>
                        <div class="card-body">
                            <?php if($perfectMoneyActive->isEmpty()): ?>
                                <div class="alert alert-warning">Perfect Money withdrawals are not currently configured.</div>
                            <?php else: ?>
                            <form method="POST" action="<?php echo e(route('user.withdraw.manual')); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="method" value="perfect_money">
                                <input type="hidden" name="network" value="PERFECT_MONEY">

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="text-white">Currency</label>
                                        <select name="currency" class="form-control" required style="background:#222; color:white; border-color:#555;">
                                            <?php $__currentLoopData = $perfectMoneyActive; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($w->currency); ?>"><?php echo e($w->currency); ?> — <?php echo e($w->label); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="text-white">Your Perfect Money Account Number <span class="text-danger">*</span></label>
                                        <input type="text" name="address" required
                                               class="form-control" style="background:#222; color:white; border-color:#555;"
                                               placeholder="Your Perfect Money account number">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i>
                                            We'll send your withdrawal to this account number. Make sure it's correct.
                                        </small>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="text-white">Amount (USD) <span class="text-danger">*</span></label>
                                        <input type="number" name="amount" required step="0.01"
                                               min="<?php echo e($settings->min_amount); ?>"
                                               max="<?php echo e($settings->max_per_transaction); ?>"
                                               class="form-control" style="background:#222; color:white; border-color:#555;">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="text-white">Notes for admin <small class="text-muted">(optional)</small></label>
                                        <input type="text" name="notes" maxlength="255"
                                               class="form-control" style="background:#222; color:white; border-color:#555;">
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <button type="submit" class="btn btn-warning btn-block font-weight-bold"
                                                <?php echo e($availlableBalance < $settings->min_amount ? 'disabled' : ''); ?>>
                                            <i class="fas fa-paper-plane mr-1"></i> Submit Perfect Money Withdrawal Request
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="tab-pane fade" id="tab-winstant">
                    <div class="card" style="background:#111; border:1px solid #333; border-radius:8px;">
                        <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                            <h4 class="text-white mb-0">
                                <i class="fas fa-bolt mr-2 text-info"></i>Instant Withdrawal (Plisio)
                                <small class="text-muted">— USDT sent directly via Plisio API, no admin review</small>
                            </h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?php echo e(route('user.withdraw')); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="method" value="crypto">
                                <input type="hidden" name="network" value="TRC-20">
                                <input type="hidden" name="currency" value="USDT">

                                <div class="form-group">
                                    <label class="text-white">Wallet Address (USDT TRC-20)
                                        <?php if(!$wallet): ?>
                                            — <a href="<?php echo e(route('profile.edit')); ?>" style="color:#3490dc">set in Profile</a>
                                        <?php else: ?>
                                            <small class="text-muted">(<a href="<?php echo e(route('profile.edit')); ?>" style="color:#3490dc">change</a>)</small>
                                        <?php endif; ?>
                                    </label>
                                    <input type="text" name="address"
                                           value="<?php echo e($wallet->wallet ?? ''); ?>"
                                           class="form-control" style="background:#222; color:white; border-color:#555;"
                                           required>
                                </div>
                                <div class="form-group">
                                    <label class="text-white">Amount (USDT)</label>
                                    <input type="number" name="amount" min="<?php echo e($settings->min_amount); ?>" step="0.01" required
                                           max="<?php echo e($availlableBalance); ?>"
                                           class="form-control" style="background:#222; color:white; border-color:#555;"
                                           placeholder="Min $<?php echo e(number_format($settings->min_amount, 2)); ?>">
                                </div>
                                <button type="submit" class="btn btn-info btn-block font-weight-bold mt-2"
                                        <?php echo e($availlableBalance < $settings->min_amount ? 'disabled' : ''); ?>>
                                    <i class="fas fa-bolt mr-1"></i> Withdraw Now (Instant)
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        
        <div class="mt-4 px-3">
            <h4 class="text-white mb-3"><i class="fas fa-history mr-2"></i>Your Withdrawal History</h4>
            <?php if($history->isEmpty()): ?>
                <p class="text-muted">No withdrawals yet.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-dark table-bordered table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Reference</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Destination</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Admin Note</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($i + 1); ?></td>
                            <td><small><?php echo e($w->transaction_no); ?></small></td>
                            <td>
                                <span class="badge badge-<?php echo e($w->method === 'crypto' ? 'primary' : ($w->method === 'advcash' ? 'warning' : 'info')); ?>">
                                    <?php echo e($w->methodLabel()); ?>

                                </span>
                            </td>
                            <td>$<?php echo e(number_format($w->amount, 2)); ?></td>
                            <td>
                                <small>
                                    <?php echo e($w->currency); ?><?php echo e($w->network ? ' · ' . $w->network : ''); ?><br>
                                    <code style="word-break:break-all;"><?php echo e(Str::limit($w->wallet_address, 22)); ?></code>
                                </small>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo e($w->plisio_txn_id ? 'success' : 'secondary'); ?>">
                                    <?php echo e($w->typeLabel()); ?>

                                </span>
                            </td>
                            <td>
                                <?php
                                    $sc = ['pending'=>'warning','processing'=>'info','completed'=>'success','failed'=>'danger'];
                                    $badge = $sc[$w->status] ?? 'secondary';
                                ?>
                                <span class="badge badge-<?php echo e($badge); ?>"><?php echo e(ucfirst($w->status)); ?></span>
                                <?php if($w->txn_hash): ?>
                                    <small class="text-muted d-block" title="<?php echo e($w->txn_hash); ?>">
                                        <i class="fas fa-link"></i> <?php echo e(Str::limit($w->txn_hash, 16)); ?>

                                    </small>
                                <?php endif; ?>
                            </td>
                            <td><small class="text-muted"><?php echo e($w->admin_note ?? '—'); ?></small></td>
                            <td><small><?php echo e($w->created_at->format('d M Y')); ?></small></td>
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
    // Crypto picker — sync the hidden network input when the user picks a different option
    const picker = document.getElementById('wCryptoPicker');
    if (picker) {
        picker.addEventListener('change', function () {
            const opt = picker.options[picker.selectedIndex];
            const network = opt ? opt.getAttribute('data-network') : '';
            const inp = document.getElementById('wCryptoNetworkInput');
            if (inp && network) inp.value = network;
        });
    }
})();
</script>

<?php echo $__env->make('user.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/balance/withdraw.blade.php ENDPATH**/ ?>