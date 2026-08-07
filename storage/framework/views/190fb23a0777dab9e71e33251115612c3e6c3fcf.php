

<?php $__env->startSection('contents'); ?>
<div class="container bg-white min-h-screen py-4 px-3">
    <div class="mb-3">
        <a href="<?php echo e(route('admin.contracts.index')); ?>" class="text-blue-600 hover:text-blue-800 text-sm">← Back to Client Contracts</a>
    </div>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <div>
            <h1 class="text-2xl uppercase text-slate-700 font-bold">Client Contract Details</h1>
            <p class="text-gray-500 text-sm">Bifonex Contract #<?php echo e($contract->id); ?></p>
        </div>
        <a href="<?php echo e(route('admin.contracts.download', $contract->id)); ?>"
           class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded text-sm font-semibold shadow-sm">
            <i class="fas fa-download mr-1"></i> Download PDF
        </a>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
        <div class="border border-gray-200 rounded-xl shadow-sm p-4 bg-slate-50">
            <div class="text-xs uppercase text-gray-500 font-semibold mb-1">Client</div>
            <div class="text-lg font-bold text-gray-800"><?php echo e($contract->user->name ?? $contract->name); ?></div>
            <div class="text-sm text-gray-500"><?php echo e($contract->user->email ?? '—'); ?></div>
        </div>
        <div class="border border-gray-200 rounded-xl shadow-sm p-4 bg-slate-50">
            <div class="text-xs uppercase text-gray-500 font-semibold mb-1">User Info</div>
            <div class="text-sm text-gray-800">Username: <strong>@ <?php echo e($contract->user->user ?? '—'); ?></strong></div>
            <div class="text-sm text-gray-800">User ID: <strong>#<?php echo e($contract->user_id); ?></strong></div>
            <div class="text-sm text-gray-800">Country: <strong><?php echo e($contract->user->country ?? '—'); ?></strong></div>
        </div>
        <div class="border border-gray-200 rounded-xl shadow-sm p-4 bg-slate-50">
            <div class="text-xs uppercase text-gray-500 font-semibold mb-1">Contract Status</div>
            <div class="text-lg font-bold text-emerald-600 mb-1">SIGNED &amp; VERIFIED</div>
            <div class="text-xs text-gray-500">Signed: <?php echo e($contract->created_at ? $contract->created_at->format('Y-m-d H:i:s') : '—'); ?></div>
            <div class="text-xs text-gray-400 font-mono mt-0.5">Ref: <?php echo e($contract->contract); ?></div>
        </div>
    </div>

    
    <div class="border border-gray-200 rounded-xl shadow-md overflow-hidden bg-slate-900">
        <div class="px-5 py-3.5 bg-slate-900 text-white font-bold text-sm flex items-center justify-between border-b border-slate-800">
            <span><i class="fas fa-file-pdf text-amber-400 mr-2"></i> Client Signed Contract PDF Preview</span>
            <span class="text-xs text-slate-400 font-mono">System Ref #<?php echo e($contract->id); ?></span>
        </div>
        <iframe
            title="Bifonex Client Contract PDF"
            src="data:application/pdf;base64,<?php echo e($pdfBase64); ?>#toolbar=0&navpanes=0&scrollbar=1"
            style="width:100%; height:85vh; border:0; background:#ffffff;">
        </iframe>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/contracts/show.blade.php ENDPATH**/ ?>