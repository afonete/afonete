<div class="wrapper">
    <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <title>Team Building Structure</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="content-wrapper bg-slate-50 min-h-screen py-4 px-4">
        <div class="container-fluid max-w-7xl mx-auto flex flex-col gap-6">

            
            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.navbar','data' => []]); ?>
<?php $component->withName('navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

            
            <?php if(session('success')): ?>
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm">
                    <span><i class="fas fa-check-circle mr-2 text-emerald-600"></i><?php echo e(session('success')); ?></span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700"><i class="fas fa-times"></i></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm">
                    <span><i class="fas fa-exclamation-circle mr-2 text-rose-600"></i><?php echo e(session('error')); ?></span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700"><i class="fas fa-times"></i></button>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-bold shadow-sm">
                    <i class="fas fa-exclamation-triangle mr-2 text-rose-600"></i><?php echo e($errors->first()); ?>

                </div>
            <?php endif; ?>

            
            <div class="bg-slate-900 text-white rounded-2xl p-6 shadow-md border border-slate-800 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold text-white flex items-center gap-2">
                        <i class="fas fa-sitemap text-amber-400"></i> Team Building Structure
                    </h1>
                    <p class="text-xs text-slate-400 mt-1">Register new downline members directly using funds from your Deposit Wallet.</p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="bg-slate-800 px-4 py-2.5 rounded-xl border border-slate-700 text-right">
                        <span class="text-[10px] text-amber-400 font-bold uppercase tracking-wider block">Deposit Wallet Balance</span>
                        <span class="text-xl font-extrabold text-white">$<?php echo e(number_format($depositBalance ?? 0, 2)); ?></span>
                    </div>

                    <button onclick="openModal()" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold py-3 px-5 rounded-xl text-xs uppercase tracking-wider shadow-md transition flex items-center gap-2">
                        <i class="fas fa-user-plus text-amber-200"></i> Add Team Member
                    </button>
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Left Referral Link</label>
                    <div class="flex">
                        <input type="text" id="leftLink" value="<?php echo e(url('/register?referral=' . (auth()->user()->activation ?? auth()->user()->user ?? auth()->id()) . '&side=LEFT')); ?>" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-l-xl font-mono text-slate-800" readonly>
                        <button onclick="copyToClipboard('leftLink')" class="bg-slate-800 hover:bg-slate-900 text-white px-4 rounded-r-xl text-xs font-bold transition">
                            <i class="fa fa-copy mr-1"></i> Copy
                        </button>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Right Referral Link</label>
                    <div class="flex">
                        <input type="text" id="rightLink" value="<?php echo e(url('/register?referral=' . (auth()->user()->activation ?? auth()->user()->user ?? auth()->id()) . '&side=RIGHT')); ?>" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-l-xl font-mono text-slate-800" readonly>
                        <button onclick="copyToClipboard('rightLink')" class="bg-slate-800 hover:bg-slate-900 text-white px-4 rounded-r-xl text-xs font-bold transition">
                            <i class="fa fa-copy mr-1"></i> Copy
                        </button>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200">
                <h4 class="text-slate-900 font-bold mb-4 text-base border-b pb-2 flex items-center gap-2">
                    <i class="fas fa-network-wired text-indigo-600"></i> Interactive Team Tree
                </h4>
                <div class="tree overflow-x-auto" id="tree">
                    <!-- Tree structure rendered via JS -->
                </div>
            </div>

        </div>
    </div>
</div>


<div id="userModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-6 border md:w-[50%] w-full max-w-2xl shadow-2xl rounded-2xl bg-white text-slate-800 my-8">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
            <h3 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fas fa-user-plus text-indigo-600"></i> Register &amp; Activate Team Member
            </h3>
            <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-600 text-lg">
                <i class="fas fa-times"></i>
            </button>
        </div>

        
        <div class="mt-4 p-3.5 bg-slate-900 text-white rounded-xl flex items-center justify-between flex-wrap gap-2 border border-slate-700">
            <div>
                <span class="text-xs text-amber-400 font-bold uppercase tracking-wider block"><i class="fas fa-wallet mr-1"></i> Your Deposit Wallet Balance</span>
                <span class="text-2xl font-extrabold text-white">$<?php echo e(number_format($depositBalance ?? 0, 2)); ?></span>
            </div>
            <div class="text-xs text-slate-300 max-w-xs text-right">
                Funds will be deducted directly from your Deposit Wallet to pay for and activate this new member's package.
            </div>
        </div>

        <form method="POST" action="<?php echo e(route('user.team.add-member')); ?>" class="space-y-4 mt-4 js-transaction-password-form">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Sponsor (Referrer)</label>
                    <input type="text" value="@ <?php echo e(Auth::user()->user); ?> (You)" readonly class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-100 text-xs font-bold text-slate-700">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Team Placement Side <span class="text-red-500">*</span></label>
                    <select name="side" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-800 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="LEFT" selected>LEFT Team</option>
                        <option value="RIGHT">RIGHT Team</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Select UVP Package Tier <span class="text-red-500">*</span></label>
                    <select name="package_id" id="pkgSelect" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-800 focus:ring-indigo-500 focus:border-indigo-500" onchange="updatePkgAmount()">
                        <?php $__currentLoopData = $adventures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($adv->id); ?>" data-min="<?php echo e($adv->min_amount); ?>" data-max="<?php echo e($adv->max_amount); ?>">
                                <?php echo e($adv->name ?: $adv->plan); ?> (Min $<?php echo e(number_format($adv->min_amount, 0)); ?> - Max $<?php echo e(number_format($adv->max_amount, 0)); ?>) · <?php echo e($adv->percentage); ?>% Daily ROI
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Investment Amount ($ USD) <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" id="pkgAmount" min="1" step="0.01" required placeholder="Enter amount to invest" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm font-bold text-slate-900 focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="text-[11px] text-slate-400 mt-1">Must be $\le$ your available Deposit Wallet balance ($<?php echo e(number_format($depositBalance ?? 0, 2)); ?>).</p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Member Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="Full Name" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Member Username <span class="text-red-500">*</span></label>
                    <input type="text" name="user" required placeholder="Username (4-10 chars)" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" required placeholder="Member Email" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" required placeholder="Phone Number" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Country <span class="text-red-500">*</span></label>
                    <input type="text" name="country" required placeholder="Country" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required placeholder="Min 6 characters" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-800">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required placeholder="Confirm Password" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-800">
                </div>
            </div>

            <input type="hidden" name="transaction_password" class="js-transaction-password-value">

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-xl text-xs shadow-md transition flex items-center gap-2">
                    <i class="fas fa-check"></i> Register &amp; Pay From Deposit
                </button>
            </div>
        </form>
    </div>
</div>

<?php echo $__env->make('user.components.transaction-password-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script>
function openModal() {
    document.getElementById('userModal').classList.remove('hidden');
    updatePkgAmount();
}

function closeModal() {
    document.getElementById('userModal').classList.add('hidden');
}

function updatePkgAmount() {
    const sel = document.getElementById('pkgSelect');
    if (!sel) return;
    const opt = sel.options[sel.selectedIndex];
    if (!opt) return;
    const min = opt.getAttribute('data-min') || '1';
    const input = document.getElementById('pkgAmount');
    if (input) {
        input.min = min;
        if (!input.value || parseFloat(input.value) < parseFloat(min)) {
            input.value = min;
        }
    }
}

function copyToClipboard(id) {
    const el = document.getElementById(id);
    if (!el) return;
    navigator.clipboard.writeText(el.value).then(() => {
        alert('Referral link copied to clipboard!');
    });
}
</script>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/team/team-structure.blade.php ENDPATH**/ ?>