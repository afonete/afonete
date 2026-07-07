<?php $__env->startSection('contents'); ?>
<div class="container-fluid py-4 px-4">

    <a href="<?php echo e(route('admin.referral.bonuses')); ?>" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>

    <h3 class="font-weight-bold mb-3"><i class="fas fa-money-bill-wave text-warning mr-2"></i> Withdrawal & Deposit Settings</h3>
    <p class="text-muted">Set limits and validation rules applied to all user withdrawals and deposits.</p>

    <?php if(session('success')): ?><div class="alert alert-success"><?php echo e(session('success')); ?></div><?php endif; ?>

    <form method="POST" action="<?php echo e(route('admin.settings.withdrawal-settings.update')); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label class="small text-muted">Minimum withdrawal amount ($) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" name="min_amount" class="form-control"
                               value="<?php echo e($settings->min_amount); ?>" required>
                        <small class="text-muted">Per spec: $10</small>
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted">Minimum deposit amount ($) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" name="min_deposit_amount" class="form-control"
                               value="<?php echo e($settings->min_deposit_amount ?? 10); ?>" required>
                        <small class="text-muted">Used by both manual deposit &amp; Plisio auto deposit</small>
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted">Max per transaction ($) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" name="max_per_transaction" class="form-control"
                               value="<?php echo e($settings->max_per_transaction); ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted">Daily limit ($, optional)</label>
                        <input type="number" step="0.01" min="0" name="daily_limit" class="form-control"
                               value="<?php echo e($settings->daily_limit); ?>" placeholder="leave blank for no limit">
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted">Monthly limit ($, optional)</label>
                        <input type="number" step="0.01" min="0" name="monthly_limit" class="form-control"
                               value="<?php echo e($settings->monthly_limit); ?>" placeholder="leave blank for no limit">
                    </div>

                    <div class="col-md-3 mt-3">
                        <label class="small text-muted">Default TRC-20 length</label>
                        <input type="number" min="26" max="64" name="default_trc20_min_length" class="form-control"
                               value="<?php echo e($settings->default_trc20_min_length); ?>">
                    </div>
                    <div class="col-md-3 mt-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="require_admin_approval" value="1" id="require_approval"
                                   <?php echo e($settings->require_admin_approval ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="require_approval">
                                Require admin approval for all withdrawals
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3 mt-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="auto_withdrawals_enabled" value="1" id="auto_withdrawals_enabled"
                                   <?php echo e(($settings->auto_withdrawals_enabled ?? false) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="auto_withdrawals_enabled">
                                Enable small automatic USDT TRC20 withdrawals
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3 mt-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="validate_trc20_format" value="1" id="validate_trc20"
                                   <?php echo e($settings->validate_trc20_format ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="validate_trc20">
                                Validate TRC-20 address format
                            </label>
                        </div>
                    </div>

                    <div class="col-md-3 mt-3">
                        <label class="small text-muted">Admin approval threshold ($)</label>
                        <input type="number" step="0.01" min="0" name="admin_approval_threshold" class="form-control"
                               value="<?php echo e($settings->admin_approval_threshold ?? 100); ?>">
                        <small class="text-muted">Withdrawals >= this amount go to admin review.</small>
                    </div>
                    <div class="col-md-3 mt-3">
                        <label class="small text-muted">Max automatic withdrawal ($)</label>
                        <input type="number" step="0.01" min="0" name="max_auto_withdrawal" class="form-control"
                               value="<?php echo e($settings->max_auto_withdrawal ?? 100); ?>">
                    </div>
                    <div class="col-md-3 mt-3">
                        <label class="small text-muted">Manual review risk score</label>
                        <input type="number" min="0" max="100" name="manual_review_risk_score" class="form-control"
                               value="<?php echo e($settings->manual_review_risk_score ?? 50); ?>">
                    </div>
                    <div class="col-md-3 mt-3">
                        <label class="small text-muted">Hot wallet max USDT</label>
                        <input type="number" step="0.000001" min="0" name="hot_wallet_max_balance" class="form-control"
                               value="<?php echo e($settings->hot_wallet_max_balance); ?>" placeholder="optional">
                        <small class="text-muted">Above this, scheduler sweeps to cold wallet.</small>
                    </div>
                    <div class="col-md-3 mt-3">
                        <label class="small text-muted">Hot wallet reserve USDT</label>
                        <input type="number" step="0.000001" min="0" name="hot_wallet_reserve_balance" class="form-control"
                               value="<?php echo e($settings->hot_wallet_reserve_balance ?? 100); ?>">
                    </div>
                    <div class="col-md-9 mt-3">
                        <label class="small text-muted">Cold wallet TRON address</label>
                        <input type="text" name="cold_wallet_address" class="form-control"
                               value="<?php echo e($settings->cold_wallet_address); ?>" placeholder="T...">
                    </div>

                    <div class="col-md-12 mt-3">
                        <label class="small text-muted">Notes (internal)</label>
                        <textarea name="notes" class="form-control" rows="2"><?php echo e($settings->notes); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right mt-3">
            <button type="submit" class="btn btn-success btn-lg font-weight-bold">
                <i class="fas fa-save mr-1"></i> Save Settings
            </button>
        </div>
    </form>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/settings/withdrawal-settings.blade.php ENDPATH**/ ?>