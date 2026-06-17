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

        <?php if(session('success')): ?>
            <div class="alert alert-success mx-3"><?php echo e(session('success')); ?></div>
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
                        <h4 class="text-white mb-0">
                            <i class="fas fa-hand-holding-usd mr-2 text-warning"></i>Manual Withdrawal Request
                        </h4>
                        <small class="text-muted">Request reviewed and processed by admin within 24–48 hrs.</small>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 p-2" style="background:#0d0d0d; border-radius:4px;">
                            <span class="text-muted">Available Balance (CASHOUT):</span>
                            <span class="font-weight-bold text-success">$<?php echo e(number_format($availlableBalance, 2)); ?></span>
                        </div>

                        <form method="POST" action="<?php echo e(route('user.withdraw.manual')); ?>">
                            <?php echo csrf_field(); ?>

                            <div class="form-group">
                                <label class="text-white">Wallet Address (USDT TRC-20)
                                    <?php if(!$wallet): ?>
                                        — <a href="<?php echo e(route('profile.edit')); ?>" style="color:#3490dc">set in Profile</a>
                                    <?php else: ?>
                                        <small class="text-muted">(from profile — <a href="<?php echo e(route('profile.edit')); ?>" style="color:#3490dc">change</a>)</small>
                                    <?php endif; ?>
                                </label>
                                <input type="text" name="address"
                                       value="<?php echo e($wallet->wallet ?? ''); ?>"
                                       class="form-control" style="background:#222; color:white; border-color:#555;"
                                       placeholder="Your USDT wallet address" required>
                            </div>

                            <div class="form-group">
                                <label class="text-white">Amount <small class="text-muted">(min $<?php echo e(\App\Models\WithdrawalSetting::current()->min_amount); ?>)</small></label>
                                <input type="number" name="amount" min="<?php echo e(\App\Models\WithdrawalSetting::current()->min_amount); ?>" step="0.01" required
                                       max="<?php echo e($availlableBalance); ?>"
                                       class="form-control" style="background:#222; color:white; border-color:#555;"
                                       placeholder="Amount to withdraw">
                            </div>

                            <button type="submit" class="btn btn-warning btn-block font-weight-bold mt-2"
                                <?php echo e($availlableBalance < \App\Models\WithdrawalSetting::current()->min_amount ? 'disabled' : ''); ?>>
                                <i class="fas fa-paper-plane mr-1"></i> Submit Withdrawal Request
                            </button>
                            <?php if($availlableBalance < \App\Models\WithdrawalSetting::current()->min_amount): ?>
                                <small class="text-danger d-block mt-1">Minimum withdrawal is $<?php echo e(\App\Models\WithdrawalSetting::current()->min_amount); ?>.</small>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>

            
            <div class="col-md-6 mt-3 mt-md-0">
                <div class="card" style="background:#111; border:1px solid #333; border-radius:8px;">
                    <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                        <h4 class="text-white mb-0">
                            <i class="fas fa-bolt mr-2 text-info"></i>Instant Withdrawal (Plisio)
                        </h4>
                        <small class="text-muted">Sent directly via Plisio API — no admin review needed.</small>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 p-2" style="background:#0d0d0d; border-radius:4px;">
                            <span class="text-muted">Available Balance (CASHOUT):</span>
                            <span class="font-weight-bold text-success">$<?php echo e(number_format($availlableBalance, 2)); ?></span>
                        </div>

                        <form method="POST" action="<?php echo e(route('user.withdraw')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label class="text-white">Wallet Address
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
                                <label class="text-white">Amount <small class="text-muted">(min $<?php echo e(\App\Models\WithdrawalSetting::current()->min_amount); ?>)</small></label>
                                <input type="number" name="amount" min="<?php echo e(\App\Models\WithdrawalSetting::current()->min_amount); ?>" step="0.01" required
                                       max="<?php echo e($availlableBalance); ?>"
                                       class="form-control" style="background:#222; color:white; border-color:#555;"
                                       placeholder="Amount to withdraw">
                            </div>
                            <button type="submit" class="btn btn-info btn-block font-weight-bold mt-2"
                                <?php echo e($availlableBalance < \App\Models\WithdrawalSetting::current()->min_amount ? 'disabled' : ''); ?>>
                                <i class="fas fa-bolt mr-1"></i> Withdraw Now
                            </button>
                        </form>
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
                            <th>Amount</th>
                            <th>Wallet</th>
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
                            <td>$<?php echo e(number_format($w->amount, 2)); ?></td>
                            <td><small><?php echo e(Str::limit($w->wallet_address, 20)); ?></small></td>
                            <td><small><?php echo e($w->plisio_txn_id ? 'Instant' : 'Manual'); ?></small></td>
                            <td>
                                <?php
                                    $sc = ['pending'=>'warning','processing'=>'info','completed'=>'success','failed'=>'danger'];
                                    $badge = $sc[$w->status] ?? 'secondary';
                                ?>
                                <span class="badge badge-<?php echo e($badge); ?>"><?php echo e(ucfirst($w->status)); ?></span>
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

<?php echo $__env->make('user.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/balance/withdraw.blade.php ENDPATH**/ ?>