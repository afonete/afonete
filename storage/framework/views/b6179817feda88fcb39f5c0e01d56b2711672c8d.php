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
        
        
        <?php if(session('success')): ?>
            <div class="bg-emerald-950/40 border border-emerald-800/40 text-emerald-300 p-3.5 rounded-xl mb-6 flex justify-between items-center text-sm shadow-sm max-w-2xl mx-auto">
                <span><i class="fas fa-check-circle mr-2 text-emerald-400"></i><strong>Success!</strong> <?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="bg-red-950/40 border border-red-800/40 text-red-300 p-3.5 rounded-xl mb-6 flex justify-between items-center text-sm shadow-sm max-w-2xl mx-auto">
                <span><i class="fas fa-exclamation-triangle mr-2 text-red-400"></i><strong>Error:</strong> <?php echo e(session('error')); ?></span>
                <?php if(str_contains(strtolower(session('error')), 'password')): ?>
                    <a href="/user/password" class="text-xs bg-red-800 hover:bg-red-700 text-white font-bold py-1.5 px-3 rounded-lg ml-3 transition-all" style="text-decoration:none;"><i class="fas fa-key mr-1"></i> Set Password</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="bg-red-950/40 border border-red-800/40 text-red-300 p-3.5 rounded-xl mb-6 flex justify-between items-center text-sm shadow-sm max-w-2xl mx-auto">
                <span><i class="fas fa-exclamation-triangle mr-2 text-red-400"></i><strong>Error:</strong> <?php echo e($errors->first()); ?></span>
                <?php if(str_contains(strtolower($errors->first()), 'password')): ?>
                    <a href="/user/password" class="text-xs bg-red-800 hover:bg-red-700 text-white font-bold py-1.5 px-3 rounded-lg ml-3 transition-all" style="text-decoration:none;"><i class="fas fa-key mr-1"></i> Set Password</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        
        <?php if(isset($settings->auto_withdrawals_enabled) && $settings->auto_withdrawals_enabled): ?>
            <div class="bg-indigo-950/40 border border-indigo-800/40 text-indigo-300 p-3.5 rounded-xl mb-6 flex justify-between items-center text-sm shadow-sm max-w-2xl mx-auto">
                <span>
                    <i class="fas fa-info-circle mr-2 text-indigo-400"></i>
                    <strong>Alternative Methods:</strong> Want to withdraw using Advcash, Perfect Money, or manual Crypto instead of automatic TRC-20?
                </span>
                <a href="<?php echo e(route('user.dashboard.manual_withdraw')); ?>" class="text-xs bg-indigo-800 hover:bg-indigo-700 text-white font-bold py-1.5 px-3 rounded-lg ml-3 transition-all flex-shrink-0" style="text-decoration:none;">
                    <i class="fas fa-hand-holding-usd mr-1"></i> Manual Methods
                </a>
            </div>
        <?php endif; ?>

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
        <div class="mt-6 bg-gray-800 p-5 rounded-xl shadow-sm border border-gray-700">
            <h3 class="text-lg font-bold text-gray-100 flex items-center gap-2">
                <i class="fas fa-history text-yellow-500"></i> Withdrawal Transaction History
            </h3>
            
            <div class="overflow-x-auto mt-4 rounded-lg border border-gray-700">
                <table class="w-full text-left border-collapse text-sm text-gray-200">
                    <thead>
                        <tr class="bg-gray-700 text-xs font-semibold text-gray-300 uppercase tracking-wider">
                            <th class="px-4 py-3">Reference No</th>
                            <th class="px-4 py-3">Method</th>
                            <th class="px-4 py-3">Requested</th>
                            <th class="px-4 py-3">Fee Charged</th>
                            <th class="px-4 py-3">Net Received</th>
                            <th class="px-4 py-3">Destination</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700 bg-gray-800/50">
                        <?php $__empty_1 = true; $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-700/50 transition-all">
                                <td class="px-4 py-3 font-mono text-xs select-all text-gray-400">
                                    <?php echo e($w->transaction_no); ?>

                                </td>
                                <td class="px-4 py-3">
                                    <span class="bg-indigo-900/40 text-indigo-300 text-[10px] font-bold px-2 py-0.5 rounded border border-indigo-800/40 uppercase">
                                        <?php echo e($w->methodLabel()); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3 font-bold text-gray-100">
                                    $<?php echo e(number_format($w->amount, 2)); ?>

                                </td>
                                <td class="px-4 py-3 text-yellow-400 font-semibold">
                                    $<?php echo e(number_format($w->fee_amount ?? 0.00, 2)); ?>

                                </td>
                                <td class="px-4 py-3 font-extrabold text-emerald-400">
                                    $<?php echo e(number_format($w->net_amount ?? $w->amount, 2)); ?>

                                </td>
                                <td class="px-4 py-3 text-xs text-gray-400 max-w-[200px] truncate" title="<?php echo e($w->wallet_address); ?>">
                                    <?php echo e($w->wallet_address); ?>

                                </td>
                                <td class="px-4 py-3 text-center">
                                    <?php
                                        $badgeClass = match($w->status) {
                                            'completed' => 'bg-emerald-900/40 text-emerald-300 border border-emerald-800/40',
                                            'pending' => 'bg-amber-900/40 text-amber-300 border border-amber-800/40',
                                            'processing' => 'bg-sky-900/40 text-sky-300 border border-sky-800/40',
                                            default => 'bg-red-900/40 text-red-300 border border-red-800/40',
                                        };
                                    ?>
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold <?php echo e($badgeClass); ?>">
                                        <?php echo e(ucfirst($w->status)); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-400">
                                    <?php echo e($w->created_at->format('Y-m-d H:i')); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center py-6 text-gray-400">
                                    <i class="fas fa-folder-open text-xl mb-1 block text-gray-500"></i>
                                    No withdrawal transactions found yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            
            <?php if($history->hasPages()): ?>
                <div class="mt-4 flex justify-center text-sm">
                    <?php echo e($history->links()); ?>

                </div>
            <?php endif; ?>
        </div>

        <!-- Send Modal -->
        <div x-cloak x-show="showSendModal" x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 p-6 rounded-lg max-w-md w-full">
                
                
                <?php if(isset($settings->auto_withdrawals_enabled) && $settings->auto_withdrawals_enabled): ?>
                    
                    
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-xl font-bold text-gray-100 flex items-center gap-2">
                            <i class="fas fa-link text-emerald-500"></i> Instant Withdrawal (TRC-20)
                        </h3>
                        <button @click="showSendModal = false; withdrawAmount = ''" class="text-white bg-red-500 hover:bg-red-600 px-3 py-1 rounded font-bold">X</button>
                    </div>

                    
                    <?php if($cashout < $settings->min_amount): ?>
                        <div class="bg-amber-950/20 border border-amber-800/40 text-amber-300 text-xs p-3 rounded-lg mb-3">
                            <i class="fas fa-info-circle mr-1"></i> Balance is below minimum withdrawal amount of $<?php echo e(number_format($settings->min_amount, 2)); ?>.
                        </div>
                    <?php else: ?>
                        <div class="bg-slate-700/50 p-2.5 rounded-lg mb-3 text-xs text-slate-300 flex justify-between items-center">
                            <span>Min: <strong>$<?php echo e(number_format($settings->min_amount, 2)); ?></strong></span>
                            <span>Max per trx: <strong>$<?php echo e(number_format($settings->max_per_transaction, 2)); ?></strong></span>
                        </div>
                    <?php endif; ?>

                    
                    <div class="space-y-3 text-left">
                        <form method="POST" action="<?php echo e(route('user.withdraw.direct_blockchain')); ?>">
                            <?php echo csrf_field(); ?>
                            
                            <div>
                                <label class="block text-xs text-gray-400 font-bold mb-1">Destination USDT TRC-20 Address <span class="text-danger">*</span></label>
                                <input type="text" name="address" required class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg p-2.5 text-xs focus:outline-none font-mono" placeholder="T... (TRON address)">
                                <small class="text-[10px] text-gray-500 mt-1 block">Must be a valid TRON address</small>
                            </div>

                            <div>
                                <label class="block text-xs text-gray-400 font-bold mb-1 mt-2">Amount (USDT) <span class="text-danger">*</span></label>
                                <input type="number" name="amount" x-model="withdrawAmount" required step="0.01" min="<?php echo e($settings->min_amount); ?>" max="<?php echo e($settings->max_per_transaction); ?>" class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg p-2.5 text-xs focus:outline-none" placeholder="Min $<?php echo e(number_format($settings->min_amount, 2)); ?>">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-400 font-bold mb-1 mt-2">Trading (Second) Password <span class="text-danger">*</span></label>
                                <input type="password" name="transaction_password" required class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg p-2.5 text-xs focus:outline-none" placeholder="Enter transaction password">
                            </div>

                            
                            <div class="bg-gray-900 border border-gray-700 rounded-xl p-3.5 mt-3 space-y-2 text-xs" x-show="withdrawAmount > 0">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Requested Amount:</span>
                                    <span class="font-bold text-gray-200" x-text="'$' + parseFloat(withdrawAmount).toFixed(2)"></span>
                                </div>
                                <div class="flex justify-between border-b border-gray-800 pb-2">
                                    <span class="text-gray-400">Withdrawal Fee (<?php echo e(number_format($settings->withdrawal_fee_percent, 2)); ?>%):</span>
                                    <span class="font-bold text-yellow-500" x-text="'$' + (withdrawAmount * (feePercent / 100)).toFixed(2)"></span>
                                </div>
                                <div class="flex justify-between pt-1">
                                    <span class="text-gray-400 font-bold">You will Receive (Net):</span>
                                    <span class="font-extrabold text-emerald-400 text-sm" x-text="'$' + (withdrawAmount - (withdrawAmount * (feePercent / 100))).toFixed(2)"></span>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2.5 px-4 rounded-lg mt-3 transition-all text-xs"
                                    <?php echo e($cashout < $settings->min_amount ? 'disabled' : ''); ?>>
                                <i class="fas fa-paper-plane mr-1"></i> Send USDT Instantly
                            </button>
                        </form>

                        
                        <div class="mt-4 pt-3 border-t border-gray-700 text-center text-xs">
                            <span class="text-gray-400">Prefer an alternative payment method?</span>
                            <a href="<?php echo e(route('user.dashboard.manual_withdraw')); ?>" class="block text-yellow-500 hover:text-yellow-400 font-bold mt-1 underline">
                                <i class="fas fa-hand-holding-usd mr-1"></i> Switch to Manual Withdrawal Page
                            </a>
                            <small class="text-[10px] text-gray-500 d-block mt-0.5">(Supports Crypto, Advcash, and Perfect Money · Processed within 3 days)</small>
                        </div>
                    </div>

                <?php else: ?>
                    
                    
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-xl font-bold text-gray-100 flex items-center gap-2">
                            <i class="fas fa-wallet text-yellow-500"></i> Manual Withdrawal
                        </h3>
                        <button @click="showSendModal = false; withdrawAmount = ''" class="text-white bg-red-500 hover:bg-red-600 px-3 py-1 rounded font-bold">X</button>
                    </div>

                    
                    <?php if($cashout < $settings->min_amount): ?>
                        <div class="bg-amber-950/20 border border-amber-800/40 text-amber-300 text-xs p-3 rounded-lg mb-3">
                            <i class="fas fa-info-circle mr-1"></i> Balance is below minimum withdrawal amount of $<?php echo e(number_format($settings->min_amount, 2)); ?>.
                        </div>
                    <?php else: ?>
                        <div class="bg-slate-700/50 p-2.5 rounded-lg mb-3 text-xs text-slate-300 flex justify-between items-center">
                            <span>Min: <strong>$<?php echo e(number_format($settings->min_amount, 2)); ?></strong></span>
                            <span>Max per trx: <strong>$<?php echo e(number_format($settings->max_per_transaction, 2)); ?></strong></span>
                        </div>
                    <?php endif; ?>

                    
                    <div class="flex space-x-2 mb-4 border-b border-gray-700 pb-2">
                        <button :class="{ 'bg-blue-600 text-white font-bold': withdrawMethod=='crypto', 'bg-gray-700 text-gray-300': withdrawMethod !=='crypto' }" @click="withdrawMethod = 'crypto'" class="flex-1 py-1.5 text-xs rounded transition-all">Crypto</button>
                        <button :class="{ 'bg-blue-600 text-white font-bold': withdrawMethod=='advcash', 'bg-gray-700 text-gray-300': withdrawMethod !=='advcash' }" @click="withdrawMethod = 'advcash'" class="flex-1 py-1.5 text-xs rounded transition-all">Advcash</button>
                        <button :class="{ 'bg-blue-600 text-white font-bold': withdrawMethod=='perfect_money', 'bg-gray-700 text-gray-300': withdrawMethod !=='perfect_money' }" @click="withdrawMethod = 'perfect_money'" class="flex-1 py-1.5 text-xs rounded transition-all">Perfect Money</button>
                    </div>

                    
                    <div x-show="withdrawMethod == 'crypto'" class="space-y-3 text-left">
                        <form method="POST" action="<?php echo e(route('user.withdraw.manual')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="method" value="crypto">
                            
                            <div>
                                <label class="block text-xs text-gray-400 font-bold mb-1">Pick Currency &amp; Network</label>
                                <select name="currency" class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg p-2.5 text-xs font-semibold focus:outline-none" required
                                        @change="cryptoNetwork = $event.target.options[$event.target.selectedIndex].getAttribute('data-network')">
                                    <?php $__currentLoopData = $cryptoByCurrency; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency => $rows): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <optgroup label="<?php echo e($currency); ?>">
                                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($currency); ?>" data-network="<?php echo e($w->network); ?>">
                                                    <?php echo e($currency); ?> — <?php echo e($w->network); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </optgroup>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <input type="hidden" name="network" :value="cryptoNetwork">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-400 font-bold mb-1 mt-2">Destination Wallet Address</label>
                                <input type="text" name="address" required class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg p-2.5 text-xs focus:outline-none font-mono" placeholder="Your receiving wallet address">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-400 font-bold mb-1 mt-2">Amount (USD)</label>
                                <input type="number" name="amount" x-model="withdrawAmount" required step="0.01" min="<?php echo e($settings->min_amount); ?>" max="<?php echo e($settings->max_per_transaction); ?>" class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg p-2.5 text-xs focus:outline-none" placeholder="Min $<?php echo e(number_format($settings->min_amount, 2)); ?>">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-400 font-bold mb-1 mt-2">Trading (Second) Password</label>
                                <input type="password" name="transaction_password" required class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg p-2.5 text-xs focus:outline-none" placeholder="Enter transaction password">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-400 font-bold mb-1 mt-2">Notes</label>
                                <input type="text" name="notes" class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg p-2.5 text-xs focus:outline-none" placeholder="Notes for admin (optional)">
                            </div>

                            
                            <div class="bg-gray-900 border border-gray-700 rounded-xl p-3.5 mt-3 space-y-2 text-xs" x-show="withdrawAmount > 0">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Requested Amount:</span>
                                    <span class="font-bold text-gray-200" x-text="'$' + parseFloat(withdrawAmount).toFixed(2)"></span>
                                </div>
                                <div class="flex justify-between border-b border-gray-800 pb-2">
                                    <span class="text-gray-400">Withdrawal Fee (<?php echo e(number_format($settings->withdrawal_fee_percent, 2)); ?>%):</span>
                                    <span class="font-bold text-yellow-500" x-text="'$' + (withdrawAmount * (feePercent / 100)).toFixed(2)"></span>
                                </div>
                                <div class="flex justify-between pt-1">
                                    <span class="text-gray-400 font-bold">You will Receive (Net):</span>
                                    <span class="font-extrabold text-emerald-400 text-sm" x-text="'$' + (withdrawAmount - (withdrawAmount * (feePercent / 100))).toFixed(2)"></span>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-slate-900 font-bold py-2.5 px-4 rounded-lg mt-3 transition-all text-xs"
                                    <?php echo e($cashout < $settings->min_amount ? 'disabled' : ''); ?>>
                                <i class="fas fa-paper-plane mr-1"></i> Submit Crypto Withdrawal
                            </button>
                        </form>
                    </div>

                    
                    <div x-show="withdrawMethod == 'advcash'" class="space-y-3 text-left">
                        <?php if($advcashActive->isEmpty()): ?>
                            <div class="p-3 text-xs text-yellow-500 bg-yellow-950/20 border border-yellow-800/20 rounded-lg">Advcash withdrawals are not configured currently.</div>
                        <?php else: ?>
                        <form method="POST" action="<?php echo e(route('user.withdraw.manual')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="method" value="advcash">
                            <input type="hidden" name="network" value="ADVCASH">

                            <div>
                                <label class="block text-xs text-gray-400 font-bold mb-1">Select Currency</label>
                                <select name="currency" class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg p-2.5 text-xs font-semibold focus:outline-none" required>
                                    <?php $__currentLoopData = $advcashActive; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($w->currency); ?>"><?php echo e($w->currency); ?> — <?php echo e($w->label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs text-gray-400 font-bold mb-1 mt-2">Advcash Account Number</label>
                                <input type="text" name="address" required class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg p-2.5 text-xs focus:outline-none font-mono" placeholder="Your Advcash Account No">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-400 font-bold mb-1 mt-2">Amount (USD)</label>
                                <input type="number" name="amount" x-model="withdrawAmount" required step="0.01" min="<?php echo e($settings->min_amount); ?>" max="<?php echo e($settings->max_per_transaction); ?>" class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg p-2.5 text-xs focus:outline-none" placeholder="Min $<?php echo e(number_format($settings->min_amount, 2)); ?>">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-400 font-bold mb-1 mt-2">Trading (Second) Password</label>
                                <input type="password" name="transaction_password" required class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg p-2.5 text-xs focus:outline-none" placeholder="Enter transaction password">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-400 font-bold mb-1 mt-2">Notes</label>
                                <input type="text" name="notes" class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg p-2.5 text-xs focus:outline-none" placeholder="Notes for admin (optional)">
                            </div>

                            
                            <div class="bg-gray-900 border border-gray-700 rounded-xl p-3.5 mt-3 space-y-2 text-xs" x-show="withdrawAmount > 0">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Requested Amount:</span>
                                    <span class="font-bold text-gray-200" x-text="'$' + parseFloat(withdrawAmount).toFixed(2)"></span>
                                </div>
                                <div class="flex justify-between border-b border-gray-800 pb-2">
                                    <span class="text-gray-400">Withdrawal Fee (<?php echo e(number_format($settings->withdrawal_fee_percent, 2)); ?>%):</span>
                                    <span class="font-bold text-yellow-500" x-text="'$' + (withdrawAmount * (feePercent / 100)).toFixed(2)"></span>
                                </div>
                                <div class="flex justify-between pt-1">
                                    <span class="text-gray-400 font-bold">You will Receive (Net):</span>
                                    <span class="font-extrabold text-emerald-400 text-sm" x-text="'$' + (withdrawAmount - (withdrawAmount * (feePercent / 100))).toFixed(2)"></span>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-slate-900 font-bold py-2.5 px-4 rounded-lg mt-3 transition-all text-xs"
                                    <?php echo e($cashout < $settings->min_amount ? 'disabled' : ''); ?>>
                                <i class="fas fa-paper-plane mr-1"></i> Submit Advcash Withdrawal
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>

                    
                    <div x-show="withdrawMethod == 'perfect_money'" class="space-y-3 text-left">
                        <?php if($perfectMoneyActive->isEmpty()): ?>
                            <div class="p-3 text-xs text-yellow-500 bg-yellow-950/20 border border-yellow-800/20 rounded-lg">Perfect Money withdrawals are not configured currently.</div>
                        <?php else: ?>
                        <form method="POST" action="<?php echo e(route('user.withdraw.manual')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="method" value="perfect_money">
                            <input type="hidden" name="network" value="PERFECT_MONEY">

                            <div>
                                <label class="block text-xs text-gray-400 font-bold mb-1">Select Currency</label>
                                <select name="currency" class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg p-2.5 text-xs font-semibold focus:outline-none" required>
                                    <?php $__currentLoopData = $perfectMoneyActive; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($w->currency); ?>"><?php echo e($w->currency); ?> — <?php echo e($w->label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs text-gray-400 font-bold mb-1 mt-2">Perfect Money Account Number</label>
                                <input type="text" name="address" required class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg p-2.5 text-xs focus:outline-none font-mono" placeholder="Your Perfect Money Account No">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-400 font-bold mb-1 mt-2">Amount (USD)</label>
                                <input type="number" name="amount" x-model="withdrawAmount" required step="0.01" min="<?php echo e($settings->min_amount); ?>" max="<?php echo e($settings->max_per_transaction); ?>" class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg p-2.5 text-xs focus:outline-none" placeholder="Min $<?php echo e(number_format($settings->min_amount, 2)); ?>">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-400 font-bold mb-1 mt-2">Trading (Second) Password</label>
                                <input type="password" name="transaction_password" required class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg p-2.5 text-xs focus:outline-none" placeholder="Enter transaction password">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-400 font-bold mb-1 mt-2">Notes</label>
                                <input type="text" name="notes" class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg p-2.5 text-xs focus:outline-none" placeholder="Notes for admin (optional)">
                            </div>

                            
                            <div class="bg-gray-900 border border-gray-700 rounded-xl p-3.5 mt-3 space-y-2 text-xs" x-show="withdrawAmount > 0">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Requested Amount:</span>
                                    <span class="font-bold text-gray-200" x-text="'$' + parseFloat(withdrawAmount).toFixed(2)"></span>
                                </div>
                                <div class="flex justify-between border-b border-gray-800 pb-2">
                                    <span class="text-gray-400">Withdrawal Fee (<?php echo e(number_format($settings->withdrawal_fee_percent, 2)); ?>%):</span>
                                    <span class="font-bold text-yellow-500" x-text="'$' + (withdrawAmount * (feePercent / 100)).toFixed(2)"></span>
                                </div>
                                <div class="flex justify-between pt-1">
                                    <span class="text-gray-400 font-bold">You will Receive (Net):</span>
                                    <span class="font-extrabold text-emerald-400 text-sm" x-text="'$' + (withdrawAmount - (withdrawAmount * (feePercent / 100))).toFixed(2)"></span>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-slate-900 font-bold py-2.5 px-4 rounded-lg mt-3 transition-all text-xs"
                                    <?php echo e($cashout < $settings->min_amount ? 'disabled' : ''); ?>>
                                <i class="fas fa-paper-plane mr-1"></i> Submit Perfect Money Withdrawal
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

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
                withdrawMethod: 'crypto',
                cryptoNetwork: '<?php echo e(optional($cryptoByCurrency->first())->first()?->network); ?>',
                withdrawAmount: '',
                feePercent: <?php echo e((float) ($settings->withdrawal_fee_percent ?? 0.00)); ?>,

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
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/UserWithdrawal.blade.php ENDPATH**/ ?>