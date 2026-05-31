




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>

        .scrolling {
            overflow-x: auto;
        }
    </style>
</head>
<body class="bg-gray-100">

<div class="wrapper">
    <!-- Include the user-dashboard-base content here -->
    <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="content-wrapper">
        <div class="w-full p-4">
            <div class="mb-2 mx-2">
                <ul class="flex flex-col md:flex-row md:space-x-5">
                    <li><a href="<?php echo e(route('overview')); ?>" class="font-medium text-lg hover:border-b-2 py-1 hover:text-orange-600 hover:border-orange-600">Overview</a></li>
                    <li><a href="" class="font-medium text-lg hover:border-b-2 py-1 hover:text-orange-600 hover:border-orange-600">Focoin</a></li>
                    <li><a href="<?php echo e(route('commission')); ?>" class="font-medium text-lg hover:border-b-2 py-1 hover:text-orange-600 hover:border-orange-600">Commissions</a></li>
                    <li><a href="" class="font-medium text-lg hover:border-b-2 py-1 hover:text-orange-600 hover:border-orange-600">Purchase code</a></li>
                    <li><a href="<?php echo e(route('transaction')); ?>" class="font-medium text-lg hover:border-b-2 py-1 hover:text-orange-600 hover:border-orange-600">Transaction</a></li>
                    <li><a href="" class="font-medium text-lg text-blue-900 hover:border-b-2 py-1 hover:text-orange-600 hover:border-orange-600">Mysubscriptions</a></li>
                </ul>
            </div>

            <div class="w-full bg-slate-100 rounded-xl p-5 my-5 shadow">
            <div>
                    <h1 class="flex items-baseline space-x-1"><span class="text-lg"><?php echo e($balance); ?></span>
                         <span class="text-xs font-bold text-orange-600">$</span></h1>
                    <p class="text-gray-700 text-md">Total</p>
                </div>

                <div>
                    <h2 class="text-orange-600 font-medium text-sm my-2">Purchase History</h2>
                </div>

                    <div class="border p-3 rounded-md">
                        <p class="flex items-baseline space-x-1"><span class="text-lg font-medium"><?php echo e($balance); ?></span><span class="text-xs font-medium text-orange-600">$</span></p>
                        <p class="text-gray-500 text-sm font-medium">Total</p>
                    </div>

            </div>

            <div class="bg-white p-3 my-5 rounded shadow-md">
                <h2 class="text-lg font-bold">History</h2>
                <div class="overflow-x-auto p-3 scrolling">
                    <table class="min-w-full bg-white">
                     <thead>
                            <tr>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Product</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Plan</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Starting Date</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">End Date</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">User Subscribed</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Activation Code</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Package</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Token</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Current Token</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Price</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Current Price</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Pool Capital</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Current Pool Capital</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Period</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Purchase Date</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Username</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Revenue Earned</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Revenue Type </th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                use Carbon\Carbon;

                                    function formatIsoDate($isoDate, $format = 'Y-m-d H:i:s')
                                    {
                                        $date = Carbon::parse($isoDate);
                                        // $date->format('F j, Y g:i A')
                                        return $date->format('F j, Y g:i A');
                                    }
                            ?>
                            <?php $__currentLoopData = $transaction; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    // dd($trx);
                                    $data = json_decode($trx->transaction_details);

                                    //                                     +"plan": "VENTURE PRO"
                                    //   +"start_date": "2024-08-22T12:22:31.709312Z"
                                    //   +"end_date": "2024-11-30T12:22:31.709312Z"
                                    //   +"package": "VENTURE PRO"
                                    //   +"price": 700
                                    //   +"period": "100 days"
                                    //   +"revenue_earned": 0
                                    //   +"revenue_type": "soon"
                                    //   +"status": "success"
                                    //   +"purchase_date": "2024-08-22T12:22:31.709312Z"
                                    //   +"username": "AC4b"
                                    //
                                    // $start_date = Carbon::parse($isoDate);
                                    // $formattedDate = $date->format('Y-m-d H:i:s');

                                    // Example: format as 'November 30, 2024 12:22 PM'
                                    // $customFormattedDate = $date->format('F j, Y g:i A');
                                    // dd($data);

                                ?>
                            <tr>
                                <th class="py-2 px-4  border-b border-gray-200 text-xs"><?php echo e($data->product); ?></th>
                                <th class="py-2 px-4  border-b border-gray-200 text-xs"><?php echo e($data->plan); ?></th>
                                <th class="py-2 px-4  border-b border-gray-200 text-xs"><?php echo e(formatIsoDate($data->start_date)); ?></th>
                                <th class="py-2 px-4  border-b border-gray-200 text-xs"><?php echo e(formatIsoDate($data->end_date)); ?></th>
                                <th class="py-2 px-4  border-b border-gray-200 text-xs"><?php echo e($data->user); ?></th>
                                <th class="py-2 px-4  border-b border-gray-200 text-xs"><?php echo e($data->code); ?></th>
                                <th class="py-2 px-4  border-b border-gray-200 text-xs"><?php echo e($data->package); ?></th>
                                <th class="py-2 px-4  border-b border-gray-200 text-xs"><?php echo e($data->token); ?>$</th>
                                <th class="py-2 px-4  border-b border-gray-200 text-xs"><?php echo e($data->current_token); ?>$</th>
                                <th class="py-2 px-4  border-b border-gray-200 text-xs"><?php echo e($data->price); ?>$</th>
                                <th class="py-2 px-4  border-b border-gray-200 text-xs"><?php echo e($data->current_price); ?>$</th>
                                <th class="py-2 px-4  border-b border-gray-200 text-xs"><?php echo e($data->poolcapital); ?>$</th>
                                <th class="py-2 px-4  border-b border-gray-200 text-xs"><?php echo e($data->current_poolcapital); ?>$</th>
                                <th class="py-2 px-4  border-b border-gray-200 text-xs"><?php echo e($data->period); ?></th>
                                <th class="py-2 px-4  border-b border-gray-200 text-xs"><?php echo e(formatIsoDate($data->purchase_date)); ?></th>
                                <th class="py-2 px-4  border-b border-gray-200 text-xs"><?php echo e($data->user); ?></th>
                                <th class="py-2 px-4  border-b border-gray-200 text-xs"><?php echo e($data->revenue_earned); ?></th>
                                <th class="py-2 px-4  border-b border-gray-200 text-xs"><?php echo e($data->revenue_type); ?></th>
                                <th class="py-2 px-4  border-b border-gray-200 text-xs"><?php echo e($data->status); ?></th>

                            </tr>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>


                </div>
                <!-- image -->
                      <?php if(!$transaction): ?>

                      <div class="text-center mt-4">

                        <h5 class="text-black text-lg">You have not subscribed to any product</h5>
                        <a href="<?php echo e(route('user.buypackage')); ?>" class="bg-blue-700 focus:outline-none hover:bg-blue-700 py-1"> <i class="fa-solid fa-plus"></i> Subscribe Product</a>

                    </div>
                    <?php endif; ?>

                    </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php /**PATH /home/cwkwkueb/mcu.focoin.eu/resources/views/user/subscription.blade.php ENDPATH**/ ?>