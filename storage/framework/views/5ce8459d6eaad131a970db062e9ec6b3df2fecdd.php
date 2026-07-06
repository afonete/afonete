<div class="form-group mt-3">
    <label class="font-weight-bold">Second Transaction Password <span class="text-danger">*</span></label>
    <input type="password"
           name="transaction_password"
           class="form-control"
           required
           autocomplete="current-password"
           placeholder="Enter your second transaction password">
    <?php $__errorArgs = ['transaction_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <small class="text-danger d-block mt-1"><?php echo e($message); ?></small>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/components/transaction-password-field.blade.php ENDPATH**/ ?>