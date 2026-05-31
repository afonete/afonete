<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transaction History</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<style>
    body{
        font-family: "Poppins", serif;
        font-weight: 400;
        font-style: normal;
    }
</style>
</head>
<body class="bg-gray-100">

  <div class="min-h-screen p-8">

    <ul class="flex gap-4 bg-white py-3 px-2 rounded items-center">
        <li>
            <a href="<?php echo e(route('user.dashboard.payclick')); ?>" class="text-blue-600 hover:text-blue-800 flex items-center gap-3">
                <i class="fa-solid fa-hand-point-up"></i>
                <span>Pay clicks</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('user.dashboard.payvideo')); ?>" class="text-blue-600 hover:text-blue-800 flex items-center gap-3">
                <i class="fa-solid fa-video"></i>
                <span>Watch Videos</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('user.dashboard.payvideo')); ?>" class="text-blue-600 hover:text-blue-800 flex items-center gap-3">
                <i class="fa-solid fa-download"></i>
                <span>Download App <span class="text-red-600">Coming soon</span></span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('user.dashboard.payvideo.invoice')); ?>" class="text-blue-600 hover:text-blue-800 flex items-center gap-3">
                <i class="fa-solid fa-book"></i>
                <span>Invoices</span>
            </a>
        </li>
    </ul>


    <h2 class="text-3xl font-bold text-center text-yellow-600 my-4">Transaction History</h2>

    <div class="overflow-x-auto">
      <table class="min-w-full bg-white shadow-md rounded-lg">
        <thead class="bg-yellow-500 text-white">
          <tr>
            <th class="py-2 px-4 text-left">Transaction No</th>
            <th class="py-2 px-4 text-left">User ID</th>
            <th class="py-2 px-4 text-left">Transaction Type</th>
            <th class="py-2 px-4 text-left">Status</th>
            <th class="py-2 px-4 text-left">Amount Earned</th>
            <th class="py-2 px-4 text-left">Date</th>
            <th class="py-2 px-4 text-left">Ads Type</th>
            <th class="py-2 px-4 text-left">Details</th>
            

          </tr>
        </thead>
        <tbody>
          <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <tr class="border-t">
            <td class="py-2 px-4"><?php echo e($transaction->transaction_no); ?></td>
            <td class="py-2 px-4"><?php echo e($transaction->user_id); ?></td>
            <td class="py-2 px-4"><?php echo e($transaction->transaction_type); ?></td>
            <td class="py-2 px-4"><?php echo e(json_decode($transaction->transaction_details)->status); ?></td>

            <td class="py-2 px-4 text-green-500">$<?php echo e(json_decode($transaction->transaction_details)->amount); ?></td>

            <td class="py-2 px-4"><?php echo e($transaction->created_at); ?></td>
            <td class="py-2 px-4 text-green-500"><?php echo e(json_decode($transaction->transaction_details)->type); ?></td>

            
            
            
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
<?php /**PATH /home/cwkwkueb/test.focoin.eu/resources/views/user/fearloss/invoices.blade.php ENDPATH**/ ?>