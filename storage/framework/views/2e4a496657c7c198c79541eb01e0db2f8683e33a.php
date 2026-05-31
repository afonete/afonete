



<div class="wrapper">
    <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="content-wrapper">
        <title>Commissions</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>

    </head>
    <body class="bg-gray-100">
        <div class="w-full p-4">

            <div class="mb-2 mx-2">
                <ul class="flex flex-col md:flex-row md:space-x-5">
                    <li>
                        <a href="<?php echo e(route('overview')); ?>" class="font-medium text-lg hover:border-b-2 py-1 hover:text-orange-600 hover:border-orange-600">Overview</a>
                    </li>
                    <li>
                        <a href="" class="font-medium text-lg hover:border-b-2 py-1 hover:text-orange-600 hover:border-orange-600">Focoin</a>
                    </li>
                    <li>
                        <a href="" class="font-medium text-lg hover:border-b-2 py-1 hover:text-orange-600 hover:border-orange-600">Commissions</a>
                    </li>
                    <li>
                        <a href="" class="font-medium text-lg hover:border-b-2 py-1 hover:text-orange-600 hover:border-orange-600">Purchase code</a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('transaction')); ?>" class="font-medium text-lg hover:border-b-2 py-1 hover:text-orange-600 hover:border-orange-600"><i class="fa-solid fa-right-left"></i> Transaction</a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('subscription')); ?>" class="font-medium text-lg text-blue-900 hover:border-b-2 py-1 hover:text-orange-600 hover:border-orange-600">Mysubscriptions</a>
                    </li>
                </ul>
            </div>

            <div class="w-full bg-slate-100 rounded-xl p-4 my-4 shadow">
                <div>
                    <h2 class="font-bold text-md">Commissions</h2>
                    <h2 class="text-orange-600 font-medium text-sm">Commissions History</h2>
                </div>
                <div class="border flex justify-evenly p-3 mt-2 rounded-xl">
                    <div>
                        <p class="flex items-baseline space-x-1"> <span class="text-lg font-medium"><?php echo e($previousWeekTotal); ?></span> <span class="text-xs font-medium text-orange-600">EUR</span> </p>
                        <p class="text-gray-500 text-sm font-medium">Previous Week</p>
                    </div>
                    <div>
                        <p class="flex items-baseline space-x-1"> <span class="text-lg font-medium"><?php echo e($balance); ?></span>
                            <span class="text-xs font-medium text-orange-600">$</span> </p>
                        <p class="text-gray-500 text-sm font-medium">Total Earnings</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-3 my-5 rounded shadow-md">
                <h2 class="text-lg font-medium">History</h2>
                <div class="overflow-x-auto ">

                <table class=" bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 bg-gray-50 border-b border-gray-200  text-blue-900 font-medium">Week</th>
                                <th class="py-2 px-4 bg-gray-50 border-b border-gray-200  text-blue-900 font-medium">Daily Vup</th>
                                <th class="py-2 px-4 bg-gray-50 border-b border-gray-200  text-blue-900 font-medium">FOMO</th>
                                <th class="py-2 px-4 bg-gray-50 border-b border-gray-200  text-blue-900 font-medium">Staking Income</th>
                                <th class="py-2 px-4 bg-gray-50 border-b border-gray-200  text-blue-900 font-medium">Direct Ads</th>
                                <th class="py-2 px-4 bg-gray-50 border-b border-gray-200  text-blue-900 font-medium">Volume Bonus</th>
                                <th class="py-2 px-4 bg-gray-50 border-b border-gray-200  text-blue-900 font-medium">Retail Sale Bonus</th>
                                <th class="py-2 px-4 bg-gray-50 border-b border-gray-200  text-blue-900 font-medium">Incentives Bonus</th>
                                <th class="py-2 px-4 bg-gray-50 border-b border-gray-200  text-blue-900 font-medium">Leadership Bonus</th>
                                <th class="py-2 px-4 bg-gray-50 border-b border-gray-200  text-blue-900 font-medium">Royal FC Leader</th>
                                <th class="py-2 px-4 bg-gray-50 border-b border-gray-200  text-blue-900 font-medium">Streamline Bonus</th>
                                <th class="py-2 px-4 bg-gray-50 border-b border-gray-200  text-blue-900 font-medium">Direct Upgrade</th>
                                <th class="py-2 px-4 bg-gray-50 border-b border-gray-200  text-blue-900 font-medium">Fomo Bonus</th>
                                <th class="py-2 px-4 bg-gray-50 border-b border-gray-200  text-blue-900 font-medium">Residual Income</th>
                                <th class="py-2 px-4 bg-gray-50 border-b border-gray-200  text-blue-900 font-medium">Team Building</th>
                                <th class="py-2 px-4 bg-gray-50 border-b border-gray-200  text-blue-900 font-medium">Travels Residual Opportunity</th>
                                <th class="py-2 px-4 bg-gray-50 border-b border-gray-200  text-blue-900 font-medium">Affiliate E-Shop Commissions</th>
                                <th class="py-2 px-4 bg-gray-50 border-b border-gray-200  text-blue-900 font-medium">Total</th>
                                <th class="py-2 px-4 bg-gray-50 border-b border-gray-200  text-blue-900 font-medium">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                                <?php
                                  use Carbon\Carbon;
                                ?>
                            <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $weekStart => $transactions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $weekEnd = Carbon::parse($weekStart)->endOfWeek()->format('Y-m-d');
                                $amountTotal = $transactions->sum('amount');

                                // echo $weekStart . " - " . $weekEnd . " | " . $amountTotal . "\n";

                                ?>

                            <tr class="hover:bg-gray-100  hover:cursor-pointer">
                                <td class="p-1 border-b border-gray-200">
                                    <p class="badge badge-light"><?php echo e($weekStart); ?> - <?php echo e($weekEnd); ?></p>
                                </td>
                                <td class="p-1 border-b border-gray-200">0.00$</td>
                                <td class="p-1 border-b border-gray-200">0.00$</td>
                                <td class="p-1 border-b border-gray-200">0.00$</td>
                                <td class="p-1 border-b border-gray-200">0.00$</td>
                                <td class="p-1 border-b border-gray-200">0.00$</td>
                                <td class="p-1 border-b border-gray-200">0.00$</td>
                                <td class="p-1 border-b border-gray-200">0.00$</td>
                                <td class="p-1 border-b border-gray-200">0.00$</td>
                                <td class="p-1 border-b border-gray-200">0.00$</td>
                                <td class="p-1 border-b border-gray-200">0.00$</td>
                                <td class="p-1 border-b border-gray-200">0.00$</td>
                                <td class="p-1 border-b border-gray-200">0.00$</td>

                                <td class="p-1 border-b border-gray-200">0.00$</td>
                                <td class="p-1 border-b border-gray-200"><?php echo e($amountTotal); ?> $</td>
                                <td class="p-1 border-b border-gray-200">0.00$</td>
                                <td class="p-1 border-b border-gray-200">0.00$</td>
                                <td class="p-1 border-b border-gray-200">0.00$</td>
                                <td class="p-1 border-b border-gray-200">
                                    <form action="<?php echo e(route('commission.details')); ?>" method="GET">
                                        <?php echo method_field("GET"); ?>
                                        <input type="hidden" name="startDate" value="<?php echo e($weekStart); ?>"/>
                                        <input type="hidden" name="endDate" value="<?php echo e($weekEnd); ?>"/>

                                        <button class="bg-orange-600 text-white px-3 py-2 rounded" type="submit">Details</button>
                                    </form>
                                </td>
                            </tr>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                            <!-- Additional rows would go here -->
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </body>
</html>
<?php /**PATH /home/cwkwkueb/test.focoin.eu/resources/views/user/commission.blade.php ENDPATH**/ ?>