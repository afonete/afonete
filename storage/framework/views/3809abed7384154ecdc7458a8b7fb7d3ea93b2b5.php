<?php $__env->startSection('contents'); ?>
<div class="container-fluid py-4 px-4">

    <?php if(session('message')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-1"></i> <?php echo e(session('message')); ?>

            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger"><?php echo e($errors->first()); ?></div>
    <?php endif; ?>

    <header class="bg-blue-50 py-8 rounded px-4 mb-4">
        <h1 class="text-2xl uppercase text-slate-700 font-bold">
            <i class="fas fa-coins mr-2"></i> Token Price Settings
        </h1>
        <p class="text-gray-500 mt-1 text-sm">
            This price is used system-wide for all token calculations:
            FREE TOKEN on purchase, LOCKED TOKEN (charges), and AVAILABLE TOKEN on renewal.
        </p>
    </header>

    <div class="row">

        
        <div class="col-md-5">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-warning text-dark font-weight-bold">
                    <i class="fas fa-tag mr-1"></i> Current Token Price
                </div>
                <div class="card-body">
                    <?php if($setting): ?>
                    <div class="text-center py-3">
                        <div style="font-size: 2.5rem; font-weight: bold; color: #1a56db;">
                            $<?php echo e(number_format($setting->token_price, 6)); ?>

                        </div>
                        <div class="text-muted mt-1">per <?php echo e($setting->token_symbol); ?> token</div>
                        <div class="mt-2">
                            <span class="badge badge-info px-3 py-2"><?php echo e($setting->token_symbol); ?></span>
                        </div>
                        <div class="text-muted small mt-2">Last updated: <?php echo e($setting->updated_at->format('d M Y H:i')); ?></div>
                    </div>

                    <hr>

                    
                    <h6 class="font-weight-bold mb-2">Example with $1,000 investment:</h6>
                    <?php
                        $tp = (float) $setting->token_price;
                        $inv = 1000;
                        $free = $tp > 0 ? number_format($inv / $tp, 0) : '—';
                        $locked = $tp > 0 ? number_format(($inv * 0.20) / $tp, 0) : '—';
                    ?>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-muted">Free Token:</td>
                            <td class="font-weight-bold"><?php echo e($free); ?> <?php echo e($setting->token_symbol); ?></td>
                            <td class="text-muted small">$1,000 ÷ $<?php echo e($setting->token_price); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Locked Token (20%):</td>
                            <td class="font-weight-bold"><?php echo e($locked); ?> <?php echo e($setting->token_symbol); ?></td>
                            <td class="text-muted small">$200 ÷ $<?php echo e($setting->token_price); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Available Token (renewal $600):</td>
                            <td class="font-weight-bold"><?php echo e($tp > 0 ? number_format(600 / $tp, 0) : '—'); ?> <?php echo e($setting->token_symbol); ?></td>
                            <td class="text-muted small">$600 ÷ $<?php echo e($setting->token_price); ?></td>
                        </tr>
                    </table>
                    <?php else: ?>
                        <p class="text-danger">No token setting found. Please create one below.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="col-md-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white font-weight-bold">
                    <i class="fas fa-edit mr-1"></i> Update Token Price
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('admin.token-settings.update')); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="form-group">
                            <label class="font-weight-bold">Token Price (USD) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number"
                                       name="token_price"
                                       step="0.000001"
                                       min="0.000001"
                                       class="form-control form-control-lg"
                                       value="<?php echo e(old('token_price', $setting->token_price ?? '0.002500')); ?>"
                                       placeholder="e.g. 0.002500"
                                       required>
                            </div>
                            <small class="text-muted">
                                Use up to 6 decimal places. Example: 0.002500 means 1 token = $0.0025.
                                At this price, $1,000 investment = 400,000 tokens.
                            </small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Token Symbol <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="token_symbol"
                                   class="form-control"
                                   value="<?php echo e(old('token_symbol', $setting->token_symbol ?? 'FONE')); ?>"
                                   placeholder="e.g. FONE"
                                   maxlength="20"
                                   required>
                            <small class="text-muted">Shown next to token amounts on the user dashboard.</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Notes <small class="text-muted font-weight-normal">(optional)</small></label>
                            <textarea name="notes" rows="2" class="form-control"
                                      placeholder="Reason for price change e.g. 'Q2 2025 price adjustment'"><?php echo e(old('notes')); ?></textarea>
                        </div>

                        <div class="alert alert-warning py-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            <strong>Important:</strong> Changing the token price affects all <em>future</em> purchases and renewals.
                            Existing token balances are not recalculated.
                        </div>

                        <button type="submit" class="btn btn-primary btn-block py-2 font-weight-bold">
                            <i class="fas fa-save mr-1"></i> Save Token Price
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/token-settings.blade.php ENDPATH**/ ?>