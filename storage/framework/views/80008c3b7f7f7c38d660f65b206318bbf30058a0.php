<?php
use App\Models\Deposits;
use Illuminate\Support\Facades\Auth;
$user     = Auth::user();
$deposits = $user->deposits()->latest()->take(10)->get();
$wallets  = $wallets ?? \App\Models\DepositWallet::active()->get();
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
            <h1 class="main_head mb-0">Deposit to Your Account</h1>
            <a href="<?php echo e(route('user.deposits.history')); ?>" class="btn btn-sm btn-outline-light">
                <i class="fas fa-list mr-1"></i> Full Deposit History
            </a>
        </div>

        <?php if(session('message')): ?>
            <div class="alert alert-success mx-3"><?php echo e(session('message')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger mx-3"><?php echo e(session('error')); ?></div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="alert alert-danger mx-3"><?php echo e($errors->first()); ?></div>
        <?php endif; ?>

        <div class="row px-3">

            
            <div class="col-md-6">
                <div class="card" style="background:#111; border:1px solid #333; border-radius:8px;">
                    <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                        <h4 class="text-white mb-0"><i class="fas fa-hand-holding-usd mr-2 text-warning"></i>Manual Deposit (USDT)</h4>
                        <small class="text-muted">Send USDT to the address below, then submit proof here.</small>
                    </div>
                    <div class="card-body">

                        
                        <?php if($wallets->isEmpty()): ?>
                            <div class="alert alert-warning">No deposit wallets configured yet. Contact admin.</div>
                        <?php else: ?>
                        <div class="mb-3">
                            <ul class="nav nav-pills mb-2" role="tablist">
                                <?php $__currentLoopData = $wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?php echo e($i === 0 ? 'active' : ''); ?> text-white"
                                           data-toggle="pill" href="#wallet-<?php echo e($w->network); ?>">
                                            <?php echo e($w->network); ?>

                                        </a>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                            <div class="tab-content">
                                <?php $__currentLoopData = $wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="tab-pane fade <?php echo e($i === 0 ? 'show active' : ''); ?>" id="wallet-<?php echo e($w->network); ?>">
                                        <div class="p-3" style="background:#0d0d0d; border:1px solid #444; border-radius:6px;">
                                            <small class="text-muted d-block mb-1"><?php echo e($w->label); ?>:</small>
                                            <code class="text-warning" style="word-break:break-all; font-size:13px;">
                                                <?php echo e($w->wallet_address); ?>

                                            </code>
                                            <small class="d-block mt-1">
                                                <span class="text-muted">Min: $<?php echo e(number_format($w->min_amount, 0)); ?></span>
                                                <?php if($w->max_amount): ?>
                                                    <span class="text-muted">· Max: $<?php echo e(number_format($w->max_amount, 0)); ?></span>
                                                <?php endif; ?>
                                            </small>
                                            <small class="text-danger d-block mt-1">⚠ Only send USDT on <?php echo e($w->network); ?>. Wrong network = lost funds.</small>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(route('user.payment.savedeposits')); ?>" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="paymentMethod" value="MANUAL_USDT">

                            <div class="form-group">
                                <label class="text-white">Amount (USD) <span class="text-danger">*</span></label>
                                <input type="number" name="amount" min="10" step="0.01" required
                                       class="form-control" style="background:#222; color:white; border-color:#555;"
                                       placeholder="Enter amount you sent (min $10)">
                            </div>

                            <div class="form-group">
                                <label class="text-white">Your Wallet Address (you sent from) <span class="text-danger">*</span></label>
                                <input type="text" name="paymentaccount" required
                                       class="form-control" style="background:#222; color:white; border-color:#555;"
                                       placeholder="Your USDT wallet address">
                            </div>

                            <div class="form-group">
                                <label class="text-white">Network</label>
                                <select name="network" class="form-control" style="background:#222; color:white; border-color:#555;">
                                    <?php $__currentLoopData = $wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($w->network); ?>"><?php echo e($w->label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="text-white">Proof of Payment <small class="text-muted">(screenshot/PDF, max 4MB)</small></label>
                                <input type="file" name="proof_of_payment" accept=".jpg,.jpeg,.png,.pdf"
                                       class="form-control" style="background:#222; color:white; border-color:#555;">
                                <small class="text-muted">Upload a screenshot of your transaction. Speeds up approval.</small>
                            </div>

                            <button type="submit" class="btn btn-warning btn-block font-weight-bold mt-2">
                                <i class="fas fa-paper-plane mr-1"></i> Submit Deposit Request
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            
            <div class="col-md-6 mt-3 mt-md-0">
                <div class="card" style="background:#111; border:1px solid #333; border-radius:8px;">
                    <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                        <h4 class="text-white mb-0"><i class="fas fa-bolt mr-2 text-info"></i>Automatic Deposit (Plisio)</h4>
                        <small class="text-muted">Generate a crypto invoice — payment confirmed automatically.</small>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Creates a Plisio USDT invoice. You pay and it confirms automatically — no admin approval needed.</p>

                        <?php if(isset($ammount)): ?>
                            <div class="alert alert-warning"><?php echo e($ammount); ?></div>
                        <?php endif; ?>
                        <?php if(isset($p_failed)): ?>
                            <div class="alert alert-danger"><?php echo e($p_failed); ?></div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(route('user.deposit')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label class="text-white">Currency: USD ($)</label>
                            </div>
                            <div class="form-group">
                                <label class="text-white">Amount <span class="text-danger">*</span></label>
                                <input type="number" name="amount" min="12" required
                                       class="form-control" style="background:#222; color:white; border-color:#555;"
                                       placeholder="Minimum $12">
                            </div>
                            <button type="submit" class="btn btn-info btn-block font-weight-bold mt-2">
                                <i class="fas fa-external-link-alt mr-1"></i> Generate Invoice
                            </button>
                        </form>

                        <div class="mt-3 p-2" style="border:1px solid #333; border-radius:4px; font-size:12px;">
                            <p class="text-warning mb-1">⚠ Rules:</p>
                            <p class="text-muted mb-1">Do not send USDT twice to the same invoice address.</p>
                            <p class="text-muted mb-1">Only USDT TRC-20 is supported.</p>
                            <p class="text-muted mb-0">Create a new invoice for each deposit.</p>
                        </div>
                    </div>
                </div>
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

<?php echo $__env->make('user.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/balance/deposit.blade.php ENDPATH**/ ?>