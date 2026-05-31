<form action="<?php echo e(route('transfer')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <div class="flex flex-col md:flex-row justify-between space-y-4 md:space-y-0">
        <div>
            <label for="" class="text-xs font-semibold text-gray-700">AMOUNT</label>
            <div>
                <input type="number" placeholder="$"
                name="amount"
                 class="w-full md:w-32 border-gray-700 border-b-2 focus:outline-none text-end text-gray-700"
                 min="50"
                 max="<?php echo e($balance); ?>"
                 step="0.01"
                 >
                <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-red-600"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div>
            <label for="" class="text-xs font-semibold text-gray-700">USERNAME</label>
            <div>
                <input type="text" placeholder="Username..."
                name="name"
                 class="w-full md:w-32 border-b-2 focus:outline-none  border-gray-700"

                 required
                 >
            </div>
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="text-red-600 font-semibold"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <button class="bg-orange-600 px-8 py-0.5 mt-4 md:mt-6 focus:outline-none text-white rounded-xl">Send</button>
        </div>
    </div>
</form>
<?php /**PATH C:\xampp\htdocs\KANANI\BIFONEX\mcu.focoin.eu\resources\views/components/transfer.blade.php ENDPATH**/ ?>