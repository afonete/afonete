
<?php
    $addrInvalid = $w->wallet_address && method_exists($w, 'addressLooksValid') && ! $w->addressLooksValid();
?>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-header bg-<?php echo e($w->is_active ? 'success' : 'secondary'); ?> text-white d-flex justify-content-between align-items-center">
        <div>
            <strong><?php echo e($w->kindLabel()); ?></strong>
            <?php if($showNetwork && $w->network): ?>
                · <span class="badge badge-light text-dark"><?php echo e($w->network); ?></span>
            <?php endif; ?>
            · <?php echo e($w->label); ?>

        </div>
        <span class="badge badge-light text-dark"><?php echo e($w->currency); ?></span>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <label class="small text-muted">
                    <?php if($w->type === 'crypto'): ?> Wallet Address
                    <?php elseif($w->type === 'advcash'): ?> Advcash Account Number
                    <?php else: ?> Perfect Money Account Number
                    <?php endif; ?>
                    <span class="text-danger">*</span>
                </label>
                <input type="text" name="wallets[<?php echo e($w->id); ?>][wallet_address]"
                       class="form-control<?php echo e($addrInvalid ? ' is-invalid' : ''); ?>"
                       value="<?php echo e($w->wallet_address); ?>"
                       placeholder="<?php echo e($w->type === 'crypto' ? $w->network . ' address' : 'Account number'); ?>">
                <?php if($addrInvalid): ?>
                    <small class="text-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        Address doesn't match the expected <?php echo e($w->network); ?> format.
                    </small>
                <?php endif; ?>
            </div>

            <div class="col-md-4">
                <label class="small text-muted">Label</label>
                <input type="text" name="wallets[<?php echo e($w->id); ?>][label]" class="form-control"
                       value="<?php echo e($w->label); ?>">
            </div>

            <div class="col-md-2">
                <label class="small text-muted">Active?</label>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox"
                           name="wallets[<?php echo e($w->id); ?>][is_active]" value="1"
                           id="active_<?php echo e($w->id); ?>" <?php echo e($w->is_active ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="active_<?php echo e($w->id); ?>">Yes</label>
                </div>
            </div>

            <?php if($showNetwork): ?>
            <div class="col-md-3 mt-2">
                <label class="small text-muted">Display order</label>
                <input type="number" min="0" name="wallets[<?php echo e($w->id); ?>][display_order]"
                       class="form-control" value="<?php echo e($w->display_order); ?>">
            </div>
            <div class="col-md-3 mt-2">
                <label class="small text-muted">Min amount ($)</label>
                <input type="number" step="0.01" min="0"
                       name="wallets[<?php echo e($w->id); ?>][min_amount]" class="form-control"
                       value="<?php echo e($w->min_amount); ?>">
            </div>
            <div class="col-md-3 mt-2">
                <label class="small text-muted">Max amount ($, optional)</label>
                <input type="number" step="0.01" min="0"
                       name="wallets[<?php echo e($w->id); ?>][max_amount]" class="form-control"
                       value="<?php echo e($w->max_amount); ?>">
            </div>
            <?php else: ?>
            <div class="col-md-3 mt-2">
                <label class="small text-muted">Display order</label>
                <input type="number" min="0" name="wallets[<?php echo e($w->id); ?>][display_order]"
                       class="form-control" value="<?php echo e($w->display_order); ?>">
            </div>
            <?php endif; ?>

            <div class="col-md-3 mt-2">
                <label class="small text-muted">Notes (internal)</label>
                <input type="text" name="wallets[<?php echo e($w->id); ?>][notes]" class="form-control"
                       value="<?php echo e($w->notes); ?>">
            </div>

            <?php if(!$showNetwork): ?>
            <div class="col-md-12 mt-2">
                <label class="small text-muted">
                    Step-by-step instructions shown to user
                </label>
                <textarea name="wallets[<?php echo e($w->id); ?>][instructions]" class="form-control" rows="6"
                          placeholder="Each line will be numbered for the user."><?php echo e($w->instructions); ?></textarea>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/settings/_wallet_card.blade.php ENDPATH**/ ?>