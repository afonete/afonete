<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit Cancellation Notification</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-purple-500 rounded shadow-sm">
            <div>
                
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-100">
                    Deposit Cancellation Notification
                </h2>
            </div>
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <p class="mt-2 text-gray-600">
                    Dear <?php echo e($user->name); ?>,
                </p>
                <p class="mt-2 text-gray-600">
                    We regret to inform you that your recent deposit of <strong><?php echo e($deposit); ?></strong> has been cancelled due to <strong><?php echo e($reason); ?></strong>.
                </p>
                <p class="mt-2 text-gray-600">
                    If you have any questions or need further assistance, please contact our support team.
                </p>
                <p class="mt-4 text-gray-600">
                    Best regards,<br>
                    <strong><?php echo e(config('app.name')); ?></strong>
                </p>
                <p class="mt-2 text-gray-600">
                    <a href="mailto:support@yourcompany.com" class="text-blue-500 hover:underline">support@yourcompany.com</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/emails/cancel-deposit.blade.php ENDPATH**/ ?>