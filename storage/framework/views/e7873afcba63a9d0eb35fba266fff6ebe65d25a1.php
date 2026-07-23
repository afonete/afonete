
<?php $__env->startSection('contents'); ?>
<div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8 max-w-5xl">

    
    <?php if(session('message')): ?>
        <div class="flex items-center p-4 mb-6 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm" role="alert">
            <i class="fas fa-check-circle text-lg mr-3 text-green-600"></i>
            <span class="font-medium flex-1"><?php echo e(session('message')); ?></span>
            <button type="button" class="ml-auto bg-green-50 text-green-500 rounded-lg p-1.5 hover:bg-green-100 inline-flex items-center justify-center h-8 w-8" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    <?php endif; ?>

    
    <a href="<?php echo e(route('admin.team-leaders.index')); ?>" class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 font-semibold mb-6 transition duration-150">
        <i class="fas fa-arrow-left"></i> Back to Team Leaders List
    </a>

    
    <header class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 py-6 px-6 rounded-2xl mb-8 shadow-sm">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <span class="p-3 bg-indigo-600 text-white rounded-xl shadow-md inline-flex items-center justify-center">
                    <i class="fas fa-user-shield text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-2xl sm:text-3xl text-slate-800 font-extrabold tracking-tight">
                        Application Review
                    </h1>
                    <p class="text-slate-500 text-sm mt-1 font-medium">
                        Team Leader ID #<?php echo e($leader->id); ?> &middot; Applied <?php echo e($leader->created_at->format('d M Y, H:i')); ?>

                    </p>
                </div>
            </div>
            <div>
                <?php if($leader->status === 'pending'): ?>
                    <span class="inline-flex items-center gap-1.5 bg-amber-100 text-amber-800 font-bold text-sm px-4 py-2 rounded-full border border-amber-200">
                        <i class="fas fa-clock"></i> Pending Review
                    </span>
                <?php elseif($leader->status === 'confirmed'): ?>
                    <span class="inline-flex items-center gap-1.5 bg-green-100 text-green-800 font-bold text-sm px-4 py-2 rounded-full border border-green-200">
                        <i class="fas fa-check-circle"></i> Confirmed
                    </span>
                <?php elseif($leader->status === 'rejected'): ?>
                    <span class="inline-flex items-center gap-1.5 bg-red-100 text-red-800 font-bold text-sm px-4 py-2 rounded-full border border-red-200">
                        <i class="fas fa-times-circle"></i> Rejected
                    </span>
                <?php elseif($leader->status === 'suspended'): ?>
                    <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-800 font-bold text-sm px-4 py-2 rounded-full border border-gray-200">
                        <i class="fas fa-ban"></i> Suspended
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        
        <div class="lg:col-span-1 space-y-6">

            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-8 text-center text-white">
                    <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-user text-3xl"></i>
                    </div>
                    <h2 class="text-xl font-extrabold"><?php echo e($leader->Names); ?></h2>
                    <p class="text-blue-100 text-sm font-mono mt-1">{{ $leader->User_name }}</p>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-envelope text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Email</p>
                            <p class="text-sm font-semibold text-gray-800 break-all"><?php echo e($leader->Email); ?></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-phone text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Phone</p>
                            <p class="text-sm font-semibold text-gray-800"><?php echo e($leader->Phone); ?></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-globe text-purple-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Country</p>
                            <p class="text-sm font-semibold text-gray-800"><?php echo e($leader->Country); ?></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-calendar text-amber-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Applied</p>
                            <p class="text-sm font-semibold text-gray-800"><?php echo e($leader->created_at->format('d M Y, H:i')); ?></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 <?php echo e(($leader->leadership_level ?? 'TEAM_LEADER') === 'SUPER_LEADER' ? 'bg-yellow-50' : 'bg-blue-50'); ?> rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-crown <?php echo e(($leader->leadership_level ?? 'TEAM_LEADER') === 'SUPER_LEADER' ? 'text-yellow-600' : 'text-blue-600'); ?> text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Leadership Level</p>
                            <p class="text-sm font-semibold <?php echo e(($leader->leadership_level ?? 'TEAM_LEADER') === 'SUPER_LEADER' ? 'text-yellow-700' : 'text-blue-700'); ?>">
                                <?php echo e($leader->leadership_level ?? 'TEAM_LEADER'); ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="lg:col-span-2 space-y-6">

            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-link text-indigo-600"></i>
                        Social Group Links — Verify Before Approval
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">Click the links to open in a new tab and verify the groups are active. Use the copy button to save the URL.</p>
                </div>
                <div class="p-6 space-y-5">

                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                            <i class="fab fa-whatsapp text-green-600 mr-1"></i> WhatsApp Group Link
                        </label>
                        <?php if($leader->whatsapp): ?>
                            <div class="flex items-stretch gap-2">
                                <div class="flex-1 bg-green-50 border border-green-200 rounded-xl px-4 py-3 flex items-center">
                                    <a href="<?php echo e($leader->whatsapp); ?>" target="_blank" rel="noopener"
                                       class="text-sm text-green-800 font-semibold hover:text-green-600 transition break-all flex-1"
                                       id="whatsapp-link">
                                        <?php echo e($leader->whatsapp); ?>

                                    </a>
                                </div>
                                <a href="<?php echo e($leader->whatsapp); ?>" target="_blank" rel="noopener"
                                   class="inline-flex items-center justify-center px-4 bg-green-600 hover:bg-green-700 text-white rounded-xl transition duration-150 font-bold text-sm gap-1.5"
                                   title="Open WhatsApp group">
                                    <i class="fas fa-external-link-alt"></i> Open
                                </a>
                                <button type="button"
                                        onclick="copyToClipboard('<?php echo e($leader->whatsapp); ?>', this)"
                                        class="inline-flex items-center justify-center px-4 bg-white hover:bg-gray-50 text-gray-700 rounded-xl border border-gray-300 transition duration-150 font-bold text-sm gap-1.5"
                                        title="Copy link">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 flex items-center gap-2">
                                <i class="fas fa-exclamation-triangle text-red-500"></i>
                                <span class="text-sm text-red-700 font-semibold">Not submitted yet</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                            <i class="fab fa-telegram text-blue-600 mr-1"></i> Telegram Group Link
                        </label>
                        <?php if($leader->instagram): ?>
                            <div class="flex items-stretch gap-2">
                                <div class="flex-1 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 flex items-center">
                                    <a href="<?php echo e($leader->instagram); ?>" target="_blank" rel="noopener"
                                       class="text-sm text-blue-800 font-semibold hover:text-blue-600 transition break-all flex-1"
                                       id="telegram-link">
                                        <?php echo e($leader->instagram); ?>

                                    </a>
                                </div>
                                <a href="<?php echo e($leader->instagram); ?>" target="_blank" rel="noopener"
                                   class="inline-flex items-center justify-center px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition duration-150 font-bold text-sm gap-1.5"
                                   title="Open Telegram group">
                                    <i class="fas fa-external-link-alt"></i> Open
                                </a>
                                <button type="button"
                                        onclick="copyToClipboard('<?php echo e($leader->instagram); ?>', this)"
                                        class="inline-flex items-center justify-center px-4 bg-white hover:bg-gray-50 text-gray-700 rounded-xl border border-gray-300 transition duration-150 font-bold text-sm gap-1.5"
                                        title="Copy link">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 flex items-center gap-2">
                                <i class="fas fa-exclamation-triangle text-red-500"></i>
                                <span class="text-sm text-red-700 font-semibold">Not submitted yet</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    
                    <div class="pt-3 border-t border-gray-100">
                        <?php if($leader->whatsapp && $leader->instagram): ?>
                            <div class="flex items-center gap-2 text-sm text-green-700 font-bold bg-green-50 border border-green-200 rounded-xl px-4 py-3">
                                <i class="fas fa-check-circle text-green-600"></i>
                                Application Complete — Both group links submitted. Ready for review.
                            </div>
                        <?php else: ?>
                            <div class="flex items-center gap-2 text-sm text-red-700 font-bold bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                                Application Incomplete — Missing:
                                <?php if(!$leader->whatsapp): ?> <span class="ml-1">WhatsApp</span><?php endif; ?>
                                <?php if(!$leader->whatsapp && !$leader->instagram): ?> <span>&amp;</span> <?php endif; ?>
                                <?php if(!$leader->instagram): ?> <span>Telegram</span><?php endif; ?>
                                <span class="ml-1">link(s).</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <?php if($leader->status === 'confirmed' && isset($performance)): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-chart-line text-indigo-600"></i>
                        Performance Monitoring
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">Track referrals, duration, and task compliance for this leader.</p>
                </div>
                <div class="p-6 space-y-5">

                    
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                            <i class="fas fa-hourglass-half text-indigo-500 mr-1"></i> Activation Duration
                        </h4>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div class="bg-gray-50 rounded-xl p-3 text-center border">
                                <p class="text-[10px] text-gray-400 font-bold uppercase">Activated</p>
                                <p class="text-sm font-bold text-slate-800"><?php echo e($performance['activation_date'] ? $performance['activation_date']->format('d M Y') : 'N/A'); ?></p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-3 text-center border">
                                <p class="text-[10px] text-gray-400 font-bold uppercase">Expires</p>
                                <p class="text-sm font-bold text-slate-800"><?php echo e($performance['expiry_date'] ? $performance['expiry_date']->format('d M Y') : 'N/A'); ?></p>
                            </div>
                            <div class="bg-blue-50 rounded-xl p-3 text-center border border-blue-200">
                                <p class="text-[10px] text-blue-400 font-bold uppercase">Days Elapsed</p>
                                <p class="text-sm font-extrabold text-blue-700"><?php echo e($performance['days_elapsed']); ?> / <?php echo e($performance['duration_days']); ?></p>
                            </div>
                            <div class="rounded-xl p-3 text-center border <?php echo e($performance['is_expired'] ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200'); ?>">
                                <p class="text-[10px] font-bold uppercase <?php echo e($performance['is_expired'] ? 'text-red-400' : 'text-green-400'); ?>">Remaining</p>
                                <p class="text-sm font-extrabold <?php echo e($performance['is_expired'] ? 'text-red-700' : 'text-green-700'); ?>">
                                    <?php echo e($performance['is_expired'] ? 'EXPIRED' : $performance['days_remaining'] . ' days'); ?>

                                </p>
                            </div>
                        </div>
                        
                        <?php if($performance['duration_days'] > 0): ?>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-300 <?php echo e($performance['is_expired'] ? 'bg-red-500' : 'bg-blue-500'); ?>"
                                 style="width: <?php echo e(min(100, ($performance['days_elapsed'] / $performance['duration_days']) * 100)); ?>%"></div>
                        </div>
                        <?php endif; ?>
                    </div>

                    
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                            <i class="fas fa-users text-green-500 mr-1"></i> Referral Performance (All Levels)
                        </h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                            <div class="bg-green-50 rounded-xl p-3 text-center border border-green-200">
                                <p class="text-[10px] text-green-400 font-bold uppercase">Direct</p>
                                <p class="text-xl font-extrabold text-green-700"><?php echo e($performance['direct_referrals']); ?></p>
                            </div>
                            <div class="bg-blue-50 rounded-xl p-3 text-center border border-blue-200">
                                <p class="text-[10px] text-blue-400 font-bold uppercase">Indirect</p>
                                <p class="text-xl font-extrabold text-blue-700"><?php echo e($performance['indirect_referrals']); ?></p>
                            </div>
                            <div class="bg-indigo-50 rounded-xl p-3 text-center border border-indigo-200">
                                <p class="text-[10px] text-indigo-400 font-bold uppercase">Total Network</p>
                                <p class="text-xl font-extrabold text-indigo-700"><?php echo e($performance['total_referrals']); ?></p>
                            </div>
                            <div class="bg-purple-50 rounded-xl p-3 text-center border border-purple-200">
                                <p class="text-[10px] text-purple-400 font-bold uppercase">Active (w/ Pkg)</p>
                                <p class="text-xl font-extrabold text-purple-700"><?php echo e($performance['total_active_referrals']); ?></p>
                                <p class="text-[9px] text-purple-400 mt-0.5"><?php echo e($performance['active_referrals']); ?> direct + <?php echo e($performance['active_indirect_referrals']); ?> indirect</p>
                            </div>
                            <div class="bg-amber-50 rounded-xl p-3 text-center border border-amber-200">
                                <p class="text-[10px] text-amber-400 font-bold uppercase">Bonuses Earned</p>
                                <p class="text-xl font-extrabold text-amber-700">$<?php echo e(number_format($performance['referral_bonuses'], 2)); ?></p>
                            </div>
                        </div>
                    </div>

                    
                    <?php if(($leader->leadership_level ?? 'TEAM_LEADER') === 'SUPER_LEADER' && $credit): ?>
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                            <i class="fas fa-credit-card text-yellow-500 mr-1"></i> SUPER LEADER Credit &amp; Turnover
                        </h4>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div class="bg-yellow-50 rounded-xl p-3 text-center border border-yellow-200">
                                <p class="text-[10px] text-yellow-500 font-bold uppercase">Credit Total</p>
                                <p class="text-sm font-extrabold text-yellow-700">$<?php echo e(number_format($credit->credit_amount, 2)); ?></p>
                            </div>
                            <div class="bg-green-50 rounded-xl p-3 text-center border border-green-200">
                                <p class="text-[10px] text-green-500 font-bold uppercase">Remaining</p>
                                <p class="text-sm font-extrabold text-green-700">$<?php echo e(number_format($credit->remaining_credit, 2)); ?></p>
                            </div>
                            <div class="bg-blue-50 rounded-xl p-3 text-center border border-blue-200">
                                <p class="text-[10px] text-blue-500 font-bold uppercase">Turnover Target</p>
                                <p class="text-sm font-extrabold text-blue-700">$<?php echo e(number_format($credit->sales_turnover_target, 2)); ?></p>
                            </div>
                            <div class="rounded-xl p-3 text-center border <?php echo e($credit->status === 'active' ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200'); ?>">
                                <p class="text-[10px] font-bold uppercase <?php echo e($credit->status === 'active' ? 'text-green-500' : 'text-gray-400'); ?>">Credit Status</p>
                                <p class="text-sm font-extrabold <?php echo e($credit->status === 'active' ? 'text-green-700' : 'text-gray-600'); ?>"><?php echo e(strtoupper($credit->status)); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    
                    <?php if($performance['tasks']): ?>
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                            <i class="fas fa-list-check text-slate-500 mr-1"></i> Assigned Tasks
                        </h4>
                        <div class="bg-gray-50 rounded-xl p-3 border text-sm text-slate-700" style="white-space: pre-line;"><?php echo e($performance['tasks']); ?></div>
                    </div>
                    <?php endif; ?>

                    
                    <div class="pt-3 border-t">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Performance Actions</h4>
                        <div class="flex flex-wrap gap-3">
                            <?php if($performance['is_expired'] && $performance['active_referrals'] > 0): ?>
                                
                                <div class="flex-1 bg-green-50 border border-green-200 rounded-xl p-3 text-center">
                                    <p class="text-xs text-green-700 font-bold"><i class="fas fa-check-circle mr-1"></i> Conditions Met</p>
                                    <p class="text-[10px] text-green-600 mt-1">Leader has active referrals. Referral rewards continue beyond duration.</p>
                                </div>
                            <?php elseif($performance['is_expired']): ?>
                                
                                <div class="flex-1 bg-red-50 border border-red-200 rounded-xl p-3 text-center">
                                    <p class="text-xs text-red-700 font-bold"><i class="fas fa-exclamation-triangle mr-1"></i> Duration Expired — No Active Referrals</p>
                                    <p class="text-[10px] text-red-600 mt-1">Consider suspending or reviewing this leader.</p>
                                </div>
                            <?php endif; ?>

                            
                            <form action="<?php echo e(route('admin.team-leaders.suspend', $leader->id)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-4 rounded-xl text-xs transition duration-150"
                                        onclick="return confirm('Suspend this team leader? They will lose dashboard access.')">
                                    <i class="fas fa-pause mr-1"></i> Suspend Account
                                </button>
                            </form>

                            
                            <?php if(($leader->leadership_level ?? 'TEAM_LEADER') === 'SUPER_LEADER' && $credit && $credit->status === 'active'): ?>
                            <form action="<?php echo e(route('admin.team-leaders.credit.update', $credit->id)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="sales_turnover_target" value="<?php echo e($credit->sales_turnover_target); ?>">
                                <input type="hidden" name="turnover_target_percent" value="<?php echo e($credit->turnover_target_percent); ?>">
                                <input type="hidden" name="turnover_reward_percent" value="<?php echo e($credit->turnover_reward_percent); ?>">
                                <input type="hidden" name="auto_withdrawal_percent" value="<?php echo e($credit->auto_withdrawal_percent); ?>">
                                <input type="hidden" name="credit_status" value="pending">
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl text-xs transition duration-150"
                                        onclick="return confirm('Stop credit bonus flow for this SUPER LEADER?')">
                                    <i class="fas fa-stop-circle mr-1"></i> Stop Credit Bonus
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
            <?php endif; ?>

            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-gavel text-indigo-600"></i>
                        Admin Decision
                    </h3>
                </div>
                <div class="p-6">

                    <?php if($leader->status === 'pending' || $leader->status === 'rejected'): ?>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            
                            <?php if($leader->whatsapp && $leader->instagram): ?>
                                <button type="button" onclick="document.getElementById('approveModal').classList.remove('hidden')"
                                        class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl shadow-sm transition duration-150 text-sm">
                                    <i class="fas fa-check-circle"></i> Approve Application
                                </button>
                            <?php else: ?>
                                <button type="button" disabled
                                        class="flex items-center justify-center gap-2 bg-gray-200 text-gray-400 font-bold py-3 px-6 rounded-xl cursor-not-allowed text-sm"
                                        title="Cannot approve until both WhatsApp & Telegram links are submitted">
                                    <i class="fas fa-lock"></i> Approve (Links Missing)
                                </button>
                            <?php endif; ?>

                            
                            <form action="<?php echo e(route('admin.team-leaders.reject', $leader->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit"
                                        class="w-full flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl shadow-sm transition duration-150 text-sm"
                                        onclick="return confirm('Are you sure you want to REJECT this team leader application?')">
                                    <i class="fas fa-times-circle"></i> Reject Application
                                </button>
                            </form>
                        </div>
                    <?php elseif($leader->status === 'confirmed'): ?>
                        <div class="flex items-center gap-2 text-sm text-green-700 font-bold bg-green-50 border border-green-200 rounded-xl px-4 py-3 mb-4">
                            <i class="fas fa-check-circle text-green-600"></i>
                            This application has already been approved.
                        </div>
                        <form action="<?php echo e(route('admin.team-leaders.suspend', $leader->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit"
                                    class="flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-6 rounded-xl shadow-sm transition duration-150 text-sm"
                                    onclick="return confirm('Suspend this team leader?')">
                                <i class="fas fa-pause"></i> Suspend Leader
                            </button>
                        </form>
                    <?php elseif($leader->status === 'suspended'): ?>
                        <div class="flex items-center gap-2 text-sm text-gray-700 font-bold bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 mb-4">
                            <i class="fas fa-ban text-gray-500"></i>
                            This leader is currently suspended.
                        </div>
                        <form action="<?php echo e(route('admin.team-leaders.reactivate', $leader->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit"
                                    class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl shadow-sm transition duration-150 text-sm">
                                <i class="fas fa-play"></i> Reactivate Leader
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    
    <?php if($leader->status === 'pending' || $leader->status === 'rejected'): ?>
    <div id="approveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white p-6 rounded-2xl max-w-lg w-full shadow-2xl border border-gray-100 text-left max-h-[90vh] overflow-y-auto">
            <h4 class="font-bold text-lg text-slate-800 mb-4 border-b pb-2 flex items-center gap-2">
                <i class="fas fa-user-check text-green-600"></i> Approve: <?php echo e($leader->Names); ?>

                <span class="ml-auto text-xs font-bold px-2.5 py-1 rounded-full <?php echo e(($leader->leadership_level ?? 'TEAM_LEADER') === 'SUPER_LEADER' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800'); ?>">
                    <?php echo e($leader->leadership_level ?? 'TEAM_LEADER'); ?>

                </span>
            </h4>
            <form action="<?php echo e(route('admin.team-leaders.approve', $leader->id)); ?>" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="leadership_level" value="<?php echo e($leader->leadership_level ?? 'TEAM_LEADER'); ?>">

                
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Unique Activation Code <span class="text-red-500">*</span></label>
                    <?php $randomCode = 'TL-' . strtoupper(\Illuminate\Support\Str::random(8)); ?>
                    <input type="text" name="activation_code" required value="<?php echo e($randomCode); ?>" class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-blue-500 focus:border-blue-500 font-mono font-bold uppercase tracking-wider">
                    <small class="text-gray-400 text-xs mt-1 block">Default code generated. You can customize.</small>
                </div>

                
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Price ($) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" min="0" step="0.01" required value="0" class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-blue-500 focus:border-blue-500 font-bold">
                    <small class="text-gray-400 text-xs mt-1 block">Correspondence price for this activation code.</small>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Duration (Days) <span class="text-red-500">*</span></label>
                        <input type="number" name="duration" min="1" required value="60" class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-blue-500 focus:border-blue-500 font-bold">
                        <small class="text-gray-400 text-xs mt-1 block">Default 60 days.</small>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Token Reward <span class="text-red-500">*</span></label>
                        <input type="number" name="tokens" min="0" required value="1000" class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-blue-500 focus:border-blue-500 font-bold">
                        <small class="text-gray-400 text-xs mt-1 block">Tokens on activation.</small>
                    </div>
                </div>

                
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Assigned Tasks <span class="text-red-500">*</span></label>
                    <textarea name="tasks" rows="3" required class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Describe the specific tasks..."></textarea>
                </div>

                
                <?php if(($leader->leadership_level ?? 'TEAM_LEADER') === 'SUPER_LEADER'): ?>
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 space-y-3">
                    <h5 class="font-bold text-yellow-800 text-sm flex items-center gap-2">
                        <i class="fas fa-credit-card"></i> SUPER LEADER Credits
                    </h5>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Credit Amount ($) <span class="text-red-500">*</span></label>
                        <input type="number" name="credit_amount" min="0" step="0.01" value="0" class="w-full bg-white border border-yellow-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-yellow-500 focus:border-yellow-500 font-bold">
                        <small class="text-gray-500 text-xs mt-1 block">Amount recorded in the SUPER LEADER's credit wallet (shows as pending until configured).</small>
                    </div>
                </div>
                <?php endif; ?>

                <div class="flex justify-end gap-3 pt-3 border-t">
                    <button type="button" onclick="document.getElementById('approveModal').classList.add('hidden')" class="bg-gray-100 hover:bg-gray-200 text-slate-700 font-bold py-2 px-4 rounded-xl text-xs transition duration-150">
                        Cancel
                    </button>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-5 rounded-xl text-xs transition duration-150 shadow-md">
                        Confirm Approve &amp; Issue Code
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if(($leader->leadership_level ?? 'TEAM_LEADER') === 'SUPER_LEADER' && $leader->status === 'confirmed'): ?>
        <?php $credit = $leader->superLeaderCredit; ?>
        <div id="credit-panel" class="bg-white rounded-2xl shadow-sm border border-yellow-200 overflow-hidden mt-6">
            <div class="px-6 py-4 border-b border-yellow-100 bg-yellow-50">
                <h3 class="font-bold text-yellow-800 flex items-center gap-2">
                    <i class="fas fa-credit-card text-yellow-600"></i>
                    SUPER LEADER Credit Management
                </h3>
                <p class="text-xs text-yellow-600 mt-1">Configure credit conditions for <?php echo e($leader->Names); ?>. Changes take effect immediately.</p>
            </div>
            <div class="p-6">
                <?php if($credit): ?>
                <form action="<?php echo e(route('admin.team-leaders.credit.update', $credit->id)); ?>" method="POST" class="space-y-5">
                    <?php echo csrf_field(); ?>

                    
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="bg-gray-50 rounded-xl p-3 text-center border">
                            <p class="text-xs text-gray-400 font-bold uppercase">Total Credit</p>
                            <p class="text-lg font-extrabold text-slate-800">$<?php echo e(number_format($credit->credit_amount, 2)); ?></p>
                        </div>
                        <div class="bg-green-50 rounded-xl p-3 text-center border border-green-200">
                            <p class="text-xs text-green-500 font-bold uppercase">Remaining</p>
                            <p class="text-lg font-extrabold text-green-700">$<?php echo e(number_format($credit->remaining_credit, 2)); ?></p>
                        </div>
                        <div class="bg-blue-50 rounded-xl p-3 text-center border border-blue-200">
                            <p class="text-xs text-blue-500 font-bold uppercase">Cashout</p>
                            <p class="text-lg font-extrabold text-blue-700">$<?php echo e(number_format($credit->cashout_amount, 2)); ?></p>
                        </div>
                    </div>

                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Sales Turnover Target ($)</label>
                        <input type="number" name="sales_turnover_target" min="0" step="0.01" value="<?php echo e($credit->sales_turnover_target); ?>" class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-yellow-500 focus:border-yellow-500 font-bold">
                        <small class="text-gray-400 text-xs mt-1 block">Default $10,000. The total sales turnover the SUPER LEADER must achieve.</small>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Turnover Target (%)</label>
                            <input type="number" name="turnover_target_percent" min="0" max="100" step="0.01" value="<?php echo e($credit->turnover_target_percent); ?>" class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-yellow-500 focus:border-yellow-500 font-bold">
                            <small class="text-gray-400 text-xs mt-1 block">e.g. 1 = when 1% of turnover target is reached in referrals.</small>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Reward (%) of Credit</label>
                            <input type="number" name="turnover_reward_percent" min="0" max="100" step="0.01" value="<?php echo e($credit->turnover_reward_percent); ?>" class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-yellow-500 focus:border-yellow-500 font-bold">
                            <small class="text-gray-400 text-xs mt-1 block">e.g. 0.5 = 0.5% of credit released per milestone → cashout after 5 min.</small>
                        </div>
                    </div>

                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Automatic Credit Withdrawal (%)</label>
                        <input type="number" name="auto_withdrawal_percent" min="0" max="100" step="0.01" value="<?php echo e($credit->auto_withdrawal_percent); ?>" class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-yellow-500 focus:border-yellow-500 font-bold">
                        <small class="text-gray-400 text-xs mt-1 block">% of credit auto-withdrawn 1 hour after credit activation.</small>
                    </div>

                    
                    <div class="flex items-center gap-4 pt-3 border-t">
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 font-bold uppercase mb-2">Credit Status</p>
                            <div class="flex gap-3">
                                <button type="submit" name="credit_status" value="active"
                                        class="flex-1 flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-4 rounded-xl text-sm transition duration-150 <?php echo e($credit->status === 'active' ? 'ring-2 ring-green-400 ring-offset-2' : ''); ?>">
                                    <i class="fas fa-check-circle"></i> Active
                                </button>
                                <button type="submit" name="credit_status" value="pending"
                                        class="flex-1 flex items-center justify-center gap-2 bg-gray-400 hover:bg-gray-500 text-white font-bold py-2.5 px-4 rounded-xl text-sm transition duration-150 <?php echo e($credit->status === 'pending' ? 'ring-2 ring-gray-400 ring-offset-2' : ''); ?>">
                                    <i class="fas fa-pause"></i> Pending (Disabled)
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                <?php else: ?>
                    
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-4">
                        <p class="text-sm text-yellow-800 font-semibold flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle"></i>
                            No credit record found. Configure and create one below.
                        </p>
                    </div>
                    <form action="<?php echo e(route('admin.team-leaders.credit.create', $leader->id)); ?>" method="POST" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Credit Amount ($) <span class="text-red-500">*</span></label>
                            <input type="number" name="credit_amount" min="0" step="0.01" required value="0" class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-yellow-500 focus:border-yellow-500 font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Sales Turnover Target ($)</label>
                            <input type="number" name="sales_turnover_target" min="0" step="0.01" value="10000" class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-yellow-500 focus:border-yellow-500 font-bold">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Turnover Target (%)</label>
                                <input type="number" name="turnover_target_percent" min="0" max="100" step="0.01" value="0" class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm font-bold">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Reward (%)</label>
                                <input type="number" name="turnover_reward_percent" min="0" max="100" step="0.01" value="0" class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm font-bold">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Auto Withdrawal (%)</label>
                            <input type="number" name="auto_withdrawal_percent" min="0" max="100" step="0.01" value="0" class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm font-bold">
                        </div>
                        <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2.5 px-4 rounded-xl text-sm transition duration-150">
                            <i class="fas fa-plus mr-1"></i> Create Credit Record
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

</div>


<script>
function copyToClipboard(text, btn) {
    navigator.clipboard.writeText(text).then(function() {
        var originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
        btn.classList.add('bg-green-50', 'text-green-700', 'border-green-300');
        setTimeout(function() {
            btn.innerHTML = originalHTML;
            btn.classList.remove('bg-green-50', 'text-green-700', 'border-green-300');
        }, 2000);
    }).catch(function() {
        // Fallback for older browsers
        var textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        var originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
        setTimeout(function() { btn.innerHTML = originalHTML; }, 2000);
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/team-leader-detail.blade.php ENDPATH**/ ?>