



<div class="wrapper">
    <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <title>Withdrawal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/charts.css/dist/charts.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.3/dist/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
   <div class="content-wrapper">
<head>
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    [x-cloak]{display: none !important;}
</style>
</head>
<body>

    <div x-data="walletApp()" class="min-h-screen bg-gray-900  text-white p-8">
        <!-- Wallet Balance and Actions -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-semibold text-gray-200">Wallet Balance</h2>
            <div class="text-yellow-400 text-4xl font-bold mt-2"><?php echo e($cashout); ?> USDT</div>

            <div class="flex justify-center space-x-8 mt-6">
                <!-- Send Button -->
                <div class="text-center">
                    <button @click="showSendModal = true" class="bg-gray-700 hover:bg-gray-600 p-4 rounded-full">
                        <i class="fas fa-arrow-up text-2xl"></i>
                    </button>
                    <p class="mt-2">Send</p>
                </div>

                <!-- Receive Button -->
                <div class="text-center">
                    <button @click="showReceiveModal = true" class="bg-gray-700 hover:bg-gray-600 p-4 rounded-full">
                        <i class="fas fa-arrow-down text-2xl"></i>
                    </button>
                    <p class="mt-2">Receive</p>
                </div>

                <!-- Swap Button -->
                <div class="text-center">
                    <button @click="showSwapModal= true" class="bg-gray-700 hover:bg-gray-600 p-4 rounded-full">
                        <i class="fas fa-sync text-2xl"></i>
                    </button>
                    <p class="mt-2">Swap</p>
                </div>




                <!-- Hold Funds Button -->
                <div class="text-center">
                    <button @click="showHoldFundsModal= true" class="bg-gray-700 hover:bg-gray-600 p-4 rounded-full">
                        <i class="fas fa-pause-circle text-2xl"></i>
                    </button>
                    <p class="mt-2">Hold Funds</p>
                </div>
            </div>
        </div>

        <!-- Assets -->
        <div class="bg-gray-800 p-4 rounded-lg">
             <h4 class="text-gray-100 py-2">Assets</h4>

          <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

          <div class="flex justify-between">
                <span><?php echo e($key); ?></span>
                <span><?php echo e($value); ?><?php echo e($value==0?".00":""); ?></span>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>

        <!-- Transaction History -->
        <div class="mt-6 bg-gray-800 p-4 rounded-lg">
            <h3 class="text-lg font-semibold">Transaction History</h3>
            <table class="w-full text-center mt-4 text-gray-200">
                <thead>
                    <tr class="bg-gray-700">
                        <th class="py-2">Date</th>
                        <th class="py-2">Type</th>
                        <th class="py-2">Amount</th>
                        <th class="py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-2">No Data</td>
                        <td class="py-2">--</td>
                        <td class="py-2">--</td>
                        <td class="py-2">--</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Send Modal -->
        <div x-cloak x-show="showSendModal" x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 p-6 rounded-lg max-w-md w-full">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold">Transfer</h3>
                    <button @click="showSendModal = false" class="text-white bg-red-500 px-3">X</button>
                </div>
                <div class="mb-4">
                    <div class="mb-4">
                        <div class="flex space-x-2">
                            <button :class="{ 'bg-blue-500 text-white': type=='USDT', 'bg-gray-500 text-white': type !=='USDT' }" @click="selectType('USDT')"  class="px-2 py-2  text-gray-200 border-none outline-none">USDT</button>
                            <button :class="{ 'bg-blue-500 text-white': type=='PERFECT_MONEY', 'bg-gray-500 text-white': type !=='PERFECT_MONEY' }" @click="selectType('PERFECT_MONEY')"  class="px-2 py-2  text-gray-200 border-none outline-none">PERFECT MONEY</button>
                            <button :class="{ 'bg-blue-500 text-white': type=='VOLE', 'bg-gray-500 text-white': type !=='VOLE' }" @click="selectType('VOLE')"  class="px-2 py-2  text-gray-200 border-none outline-none">VOLET</button>
                        </div>
                    </div>

                </div>



                <div class="mb-4">
                    <label class="block text-gray-400">Address:</label>
                    <input type="text" placeholder="Enter wallet address" class="w-full mt-1 py-2 px-2 bg-gray-900 border border-gray-700 rounded-lg">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400">Amount:</label>
                    <input type="number" placeholder="Enter amount" class="w-full mt-1 py-2 px-2 bg-gray-900 border border-gray-700 rounded-lg">

                </div>
                <div x-show="amount < 10 && dataAvailable" x-cloak class="bg-red-500 text-center py-2 text-white font-semibold rounded-lg">Not eligible: Balance below 10 USDT</div>

                <div x-show="dataAvailable" x-cloak class="mb-4 p-2 text-gray-200">
                    <p class="flex gap-2">Balance: <span x-html="amount"></span></p>
                </div>

                <div>
                     <button :disabled="amount<10?'true':'false'"
                      type="submit" class="bg-indigo-500 hover:bg-indigo-600 cursor-pointer px-3 py-2 rounded w-full">Send</button>
                </div>

            </div>
        </div>

         <!-- swap token Modal -->
        <div x-cloak x-show="showSwapModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 p-6 rounded-lg max-w-md w-full">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold">Swap Tokens</h3>
                    <button @click="showSwapModal = false" class="text-white">X</button>
                </div>


               <div>
                    <!-- From Section -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium">From:</label>
                        <span class="block text-gray-300 text-sm mb-1">Balance: <span x-text="amount"></span></span>
                        <input type="text" value="Lista" class="block w-full py-2 px-3 bg-gray-700 rounded-lg border-none text-gray-400" disabled>
                    </div>

                    <!-- Amount Input -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Amount:</label>
                        <input type="number"  class="block w-full py-2 px-3 bg-gray-700 rounded-lg border border-gray-600 text-white" placeholder="0">
                    </div>

                    <!-- Converted Display -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Converted:</label>
                        <input type="text"  class="block w-full py-2 px-3 bg-gray-700 rounded-lg border-none text-gray-400" disabled>
                    </div>

                    <!-- Max Button -->
                    <button class="block w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 rounded-lg mb-4" @click="amount = 0">Max</button>

                    <!-- To Section -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium">To:</label>
                        <input type="text" value="USDT" class="block w-full py-2 px-3 bg-gray-700 rounded-lg border-none text-gray-400" disabled>
                    </div>

                    <!-- Insufficient Balance Warning -->
                    <div x-show="amount==''" class="text-center bg-red-600 py-2 rounded-lg text-white font-semibold">
                        Insufficient balance
                    </div>


               </div>
            </div>
        </div>


         <!-- hold token Modal -->
         <div x-cloak x-show="showHoldFundsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 p-6 rounded-lg max-w-md w-full">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold">Hold Funds</h3>
                    <button @click="showHoldFundsModal= false" class="text-white">X</button>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-400">Amount:</label>
                    <input type="number" placeholder="Enter amount" class="w-full mt-1 py-2 px-2 bg-gray-900 border border-gray-700 rounded-lg">
                </div>
                <button class="w-full bg-green-500
                 hover:bg-green-600 py-2 rounded-lg text-white">Execute</button>

            </div>
        </div>


        <!-- Receive Modal -->
        <div x-cloak x-show="showReceiveModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 p-6 rounded-lg max-w-md w-full">
                <form action="<?php echo e(route("user.receive")); ?>" method="GET">
                    <?php echo csrf_field(); ?>

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">Receive</h3>
                        <button type="button" @click="showReceiveModal = false" class="text-white">X</button>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-400">Amount:</label>
                        <input type="number" name="amount" placeholder="Enter amount" class="w-full mt-1 py-2 px-2 bg-gray-900 border border-gray-700 rounded-lg">
                    </div>
                    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 py-2 rounded-lg text-white">Proceed</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        function walletApp() {
            return {
                showSendModal: false,
                showReceiveModal: false,
                showSwapModal: false,
                showHoldFundsModal: false,
                fetchData: '',
                dataAvailable:false,
                amount:'',
                type:'',

                async selectType(type) {
                    try {
                        const apiUrl = `http://127.0.0.1:8000/user/dashboard/finance/account?ac=${type}`;
                        const response = await fetch(apiUrl);
                        this.type = type

                        if (!response.ok) {
                            throw new Error(`Error fetching data: ${response.statusText}`);
                        }
                        this.dataAvailable=true
                        const data = await response.json();
                        this.amount = data?.balance
                        return `  ${data.balance}`;
                    } catch (error) {
                        console.error("Error fetching payment data:", error);
                        return "Error fetching data. Please try again later.";
                    }
                }
            }
        }
    </script>

    <!-- Include Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>


</body>
</div>
</div>
<?php /**PATH /home/blackjay/Downloads/test.focoin.eu/resources/views/user/UserWithdrawal.blade.php ENDPATH**/ ?>