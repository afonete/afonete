<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<?php echo $__env->make('home.include.user-auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Fonepo')); ?></title>

        


</head>

<body class="font-sans text-gray-900 antialiased">

    <center>
        <div
            class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">

            <div
                class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                <?php echo e($slot); ?>

            </div>
        </div>

    </center>
</body>
<?php echo $__env->make('home.include.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</html>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\mcu.focoin.eu\resources\views/layouts/guest.blade.php ENDPATH**/ ?>