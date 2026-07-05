<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FONEPO - Payment Confirmation</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <link rel="icon" href="<?php echo e(asset('assets/a/img/big-logo.png')); ?>">
    <style>
 body {
  font-family: "Poppins", sans-serif;
  font-weight: 400;
  font-style: normal;
  }

    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Graphic Image -->
        <div class="relative">
            <img class="w-[4rem]  object-cover " src="<?php echo e(asset('image/deposits.png')); ?>" alt="Payment Graphic">

        </div>

        <div class="p-6">
            <h2 class="text-2xl font-bold text-center mb-4 uppercase text-red-400">Payment Confirmation Failed</h2>

            <p class="text-center font-bold text-red-500">
                Your new investment of


                <span class="text-black">$<?php echo e(number_format($amount, 2)); ?></span>,
                is less than your previous investment of
                 <span class="text-black">$<?php echo e(number_format($recent->paid, 2)); ?></span>.
                 <br/>
                 Please invest an amount greater than <span class="text-black">$<?php echo e(number_format($recent->paid, 2)); ?></span>
                  to continue.
            </p>

            <div class="flex justify-center items-center py-2">



                
            </div>

            <button onclick="window.history.back()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition  w-full">
                Back
            </button>



        </div>


    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/confirm-package-payments-error.blade.php ENDPATH**/ ?>