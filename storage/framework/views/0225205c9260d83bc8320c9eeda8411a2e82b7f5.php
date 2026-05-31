

<div class="wrapper">
     <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
     <title>Overview</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <div class="content-wrapper">
<head>
<script src="https://cdn.tailwindcss.com"></script>
</head>


            <div class="w-full p-4">

            <div class="my-2 mx-2">
                <ul class="flex flex-col md:flex-row md:space-x-5 md:space-y-0 space-y-4">
                    <li>
                        <a href="#" class="font-medium text-lg hover:border-b-2 py-1 hover:text-orange-600 hover:border-orange-600">Overview</a>
                    </li>
                    <li>
                        <a href="#" class="font-medium text-lg hover:border-b-2 py-1 hover:text-orange-600 hover:border-orange-600">Focoin</a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('commission')); ?>" class="font-medium text-lg hover:border-b-2 py-1 hover:text-orange-600 hover:border-orange-600">Commissions</a>
                    </li>
                    <li>
                        <a href="#" class="font-medium text-lg hover:border-b-2 py-1 hover:text-orange-600 hover:border-orange-600">Purchase code</a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('transaction')); ?>" class="font-medium text-lg hover:border-b-2 py-1 hover:text-orange-600 hover:border-orange-600"><i class="fa-solid fa-right-left"></i> Transaction</a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('subscription')); ?>" class="font-medium text-lg text-blue-900 hover:border-b-2 py-1 hover:text-orange-600 hover:border-orange-600">Mysubscriptions</a>
                    </li>
                </ul>
            </div>

            <div class="w-full bg-blue-100 rounded-xl p-3">
                <div>
                    <h1 class="flex items-baseline space-x-1"><span class="text-lg"><?php echo e($balance); ?></span> <span class="text-sm font-bold text-orange-600">$</span></h1>
                    <p class="text-gray-700 text-md">Total</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                    <div class="bg-white rounded-lg p-3">
                        <h1 class="text-sm my-2 text-gray-700">Transfer user</h1>

                       <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.transfer','data' => []]); ?>
<?php $component->withName('transfer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

                    </div>

                    <div class="bg-white rounded-lg p-3">
                        <h1 class="text-sm my-2 text-gray-700">Withdraw (Processing time is up to one week)</h1>

                        <form action="">
                            <div class="flex flex-col md:flex-row justify-between space-y-4 md:space-y-0">
                                <div>
                                    <label for="" class="text-xs font-semibold text-gray-700">AMOUNT</label>
                                    <div>
                                        <input type="text" placeholder="$" class="w-full md:w-32 border-b-2 border-gray-700 focus:outline-none text-end">
                                    </div>
                                </div>

                                <div>
                                    <label for="" class="text-xs font-semibold text-gray-700">USERNAME</label>
                                    <div>
                                        <select name="" id="" class="w-full md:w-auto text-sm border-b-2 border-gray-700 focus:outline-none">
                                            <option value="" selected disabled>Choose Account</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <button class="bg-orange-600 px-8 py-0.5 mt-4 focus:outline-none md:mt-6 text-white rounded-xl">Withdraw</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 my-10 rounded shadow-md">
                <div>
                    <h2 class="text-xl font-bold">History</h2>
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full mt-4">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4  text-blue-900 font-medium py-2">Amount</th>
                                    <th class="px-4  text-blue-900 font-medium py-2">Type Income</th>
                                    <th class="px-4  text-blue-900 font-medium py-2">VUP Income</th>
                                    <th class="px-4  text-blue-900 font-medium py-2"><i class="fa-solid fa-sort"></i> Date</th>
                                    <th class="px-4  text-blue-900 font-medium py-2">Cash 25%</th>
                                    <th class="px-4  text-blue-900 font-medium py-2">Trading Voucher 75%</th>
                                    <th class="px-4  text-blue-900 font-medium py-2">Description</th>
                                    <th class="px-4  text-blue-900 font-medium py-2"><i class="fa-solid fa-sort"></i> Transaction Type</th>
                                    <th class="px-4  text-blue-900 font-medium py-2"><i class="fa-solid fa-sort"></i> Transaction Status</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                    use Carbon\Carbon;

                                ?>
                                <?php $__currentLoopData = $transaction; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


                                <?php
                                $data = json_decode($trx->transaction_details);
                                $formattedDate = Carbon::parse($data->date)->format('F j, Y, g:i A');


                                ?>
                                <tr>
                                    <td class="border-b px-4 py-2 text-green-600"><?php echo e($data->amount); ?> $</td>
                                    <td class="border-b px-4 py-2"><?php echo e($data->type); ?></td>
                                    <td class="border-b px-4 py-2"><?php echo e($data->amount); ?> $</td>
                                    <td class="border-b px-4 py-2"><?php echo e($formattedDate); ?></td>
                                    <td class="border-b px-4 py-2"><?php echo e($data->cash_25); ?> $</td>
                                    <td class="border-b px-4 py-2"><?php echo e($data->trading_75); ?> $</td>
                                    <td class="border-b px-4 py-2"><?php echo e($data->description); ?></td>
                                    <td class="border-b px-4 py-2"><?php echo e($trx->transaction_type); ?></td>
                                    <td class="border-b px-4 py-2"><?php echo e($data->status); ?></td>
                                </tr>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
            </div>
            </div>
</body>
</html>
<?php /**PATH /home/cwkwkueb/test.focoin.eu/resources/views/user/overview.blade.php ENDPATH**/ ?>