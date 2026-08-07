<?php $__env->startSection('contents'); ?>
<div x-data="{ showRejectModal: false, rejectId: null }" class="container mx-auto py-6 px-6 bg-slate-50 min-h-screen">

    
    <?php if(session('message')): ?>
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-lg bg-emerald-50 dark:bg-gray-800 dark:text-emerald-400 font-semibold" role="alert">
            <i class="fas fa-check-circle mr-1"></i> <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 font-semibold" role="alert">
            <i class="fas fa-exclamation-triangle mr-1"></i> <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    
    <div class="bg-white border border-slate-200 rounded-xl px-5 py-4 mb-6 shadow-sm flex items-center gap-3">
        <div class="bg-blue-100 text-blue-600 p-2.5 rounded-lg text-lg">
            <i class="fas fa-coins"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Token Withdrawals</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage and review pending or processed user withdrawal claims for FONE/FOCOIN tokens.</p>
        </div>
    </div>

    
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mb-6">
        
        
        <div class="xl:col-span-4">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden h-full">
                <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                    <span class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <i class="fas fa-gas-pump text-slate-500"></i> Gas Fees (20% Charges)
                    </span>
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded">Admin Only</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">User</th>
                                <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider text-right">Gas Fee</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $gasFees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userId => $amount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php $u = \App\Models\User::find($userId); ?>
                                <tr class="hover:bg-slate-50 border-b border-slate-100 transition-all">
                                    <td class="px-4 py-3 text-sm">
                                        <span class="font-semibold text-slate-800"><?php echo e($u->name ?? '—'); ?></span>
                                        <span class="block text-xs text-slate-400 mt-0.5"><?php echo e($u->email ?? ''); ?></span>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-bold text-slate-700 text-right">
                                        <?php echo e(number_format($amount, 0)); ?> tokens
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="2" class="text-center py-5 text-slate-400 text-sm">
                                        No gas fees recorded yet.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        
        <div class="xl:col-span-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden h-full">
                <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                    <span class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <i class="fas fa-clock text-amber-500"></i> Pending Requests
                    </span>
                    <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-0.5 rounded-full"><?php echo e($pending->count()); ?> claims</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">User</th>
                                <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">Tokens</th>
                                <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">Destination Wallet</th>
                                <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $pending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50 border-b border-slate-100 transition-all">
                                    <td class="px-4 py-3 text-sm">
                                        <span class="font-bold text-slate-800"><?php echo e($tw->user->name ?? '—'); ?></span>
                                        <span class="block text-xs text-slate-400 mt-0.5"><?php echo e($tw->user->email ?? ''); ?></span>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-bold text-emerald-600">
                                        <?php echo e(number_format($tw->token_amount, 0)); ?>

                                        <span class="block text-xs text-slate-400 font-normal mt-0.5">$<?php echo e(number_format($tw->coin_value_at_request, 4)); ?> val</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <code class="text-xs text-slate-500 bg-slate-50 border border-slate-200 px-2 py-1 rounded select-all" title="<?php echo e($tw->wallet_address); ?>"><?php echo e(Str::limit($tw->wallet_address, 15)); ?></code>
                                        <span class="block text-xs text-slate-400 mt-1" title="Ref: <?php echo e($tw->transaction_no); ?>"><i class="fas fa-barcode"></i> <?php echo e(Str::limit($tw->transaction_no, 12)); ?></span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right">
                                        <div class="flex justify-end gap-1.5">
                                            <form method="POST" action="<?php echo e(route('admin.token-withdrawals.approve')); ?>" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="token_withdrawal_id" value="<?php echo e($tw->id); ?>">
                                                <button class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold p-2 rounded-lg shadow-sm shadow-emerald-500/10 transition-all" onclick="return confirm('Are you sure you want to approve this token withdrawal?')">
                                                    <i class="fas fa-check text-xs"></i>
                                                </button>
                                            </form>
                                            <button class="bg-red-500 hover:bg-red-600 text-white font-bold p-2 rounded-lg shadow-sm shadow-red-500/10 transition-all" 
                                                    @click="rejectId = <?php echo e($tw->id); ?>; showRejectModal = true">
                                                <i class="fas fa-times text-xs"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-6 text-slate-400 text-sm">
                                        <i class="fas fa-check-circle text-emerald-500 text-lg mb-1 block"></i>
                                        All pending requests cleared!
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <span class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <i class="fas fa-history text-slate-500"></i> Processed History
            </span>
            <span class="bg-slate-200 text-slate-700 text-xs font-semibold px-2 py-0.5 rounded"><?php echo e($completed->total()); ?> total</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">User</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">Token Amount</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">Status</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">Admin Response Note</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">Processed Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $completed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50 border-b border-slate-100 transition-all">
                            <td class="px-4 py-3 text-sm">
                                <span class="font-semibold text-slate-800"><?php echo e($tw->user->name ?? '—'); ?></span>
                                <span class="block text-xs text-slate-400 mt-0.5"><?php echo e($tw->user->email ?? ''); ?></span>
                            </td>
                            <td class="px-4 py-3 text-sm font-bold text-slate-700">
                                <?php echo e(number_format($tw->token_amount, 0)); ?>

                            </td>
                            <td class="px-4 py-3 text-sm">
                                <?php if($tw->status === 'approved'): ?>
                                    <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2 py-0.5 rounded"><i class="fas fa-check-circle mr-1"></i>Approved</span>
                                <?php else: ?>
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-0.5 rounded"><i class="fas fa-times-circle mr-1"></i>Rejected</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-500">
                                <?php if($tw->admin_note): ?>
                                    <span class="font-italic">"<?php echo e($tw->admin_note); ?>"</span>
                                <?php else: ?>
                                    <span class="text-slate-300">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-400">
                                <i class="far fa-calendar-alt mr-1"></i> <?php echo e($tw->created_at->format('d M Y')); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-6 text-slate-400 text-sm">
                                No processed records yet.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        
        <?php if($completed->total() > 15 || $completed->hasPages()): ?>
            <div class="px-4 py-3 bg-slate-50 border-t border-slate-100 flex justify-center">
                <?php echo e($completed->links('pagination::bootstrap-4')); ?>

            </div>
        <?php endif; ?>
    </div>

    
    <div x-show="showRejectModal" 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
         x-cloak
         x-transition>
        
        <div class="bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-md overflow-hidden" 
             @click.away="showRejectModal = false">
            
            <div class="px-5 py-4 bg-red-50 border-b border-red-100 flex justify-between items-center">
                <h5 class="font-bold text-red-800 text-base flex items-center gap-2">
                    <i class="fas fa-times-circle"></i> <span>Reject Token Withdrawal</span>
                </h5>
                <button class="text-red-500 hover:text-red-700 font-bold" @click="showRejectModal = false">&times;</button>
            </div>
            
            <form method="POST" action="<?php echo e(route('admin.token-withdrawals.reject')); ?>">
                <?php echo csrf_field(); ?>
                <div class="p-5">
                    <input type="hidden" name="token_withdrawal_id" :value="rejectId">
                    
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-slate-600 mb-2">Reason for Rejection</label>
                        <textarea name="admin_note" 
                                  class="w-full bg-slate-50 border border-slate-300 focus:ring-blue-200 focus:border-blue-500 rounded-lg text-sm px-3 py-2 transition-all" 
                                  rows="3" 
                                  placeholder="Provide reason for rejection…"></textarea>
                    </div>
                    
                    <p class="text-xs text-blue-600 bg-blue-50 p-3 rounded-lg flex items-center gap-2">
                        <i class="fas fa-info-circle text-sm"></i>
                        <span>Tokens will be refunded back to the user's available token balance.</span>
                    </p>
                </div>
                
                <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" 
                            class="px-4 py-2 bg-white border border-slate-200 text-slate-700 font-semibold text-sm rounded-lg hover:bg-slate-50 transition-all" 
                            @click="showRejectModal = false">Cancel</button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white font-semibold text-sm rounded-lg hover:bg-red-700 shadow-md shadow-red-600/10 transition-all">Confirm Reject</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="<?php echo e(asset('assets/a/plugins/jquery/jquery.min.js')); ?>"></script>
<!-- Bootstrap 4 (Kept fallback script but page uses pure Tailwind + Alpine modal) -->
<script src="<?php echo e(asset('assets/a/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/token-withdrawals.blade.php ENDPATH**/ ?>