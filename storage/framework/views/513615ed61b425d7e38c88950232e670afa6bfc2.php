
<?php $__env->startSection('contents'); ?>
<div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8 max-w-7xl">

    
    <?php if(session('message')): ?>
        <div class="flex items-center p-4 mb-6 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm">
            <i class="fas fa-check-circle text-lg mr-3 text-green-600"></i>
            <span class="font-medium flex-1"><?php echo e(session('message')); ?></span>
            <button type="button" class="ml-auto bg-green-50 text-green-500 rounded-lg p-1.5 hover:bg-green-100" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="flex items-center p-4 mb-6 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200 shadow-sm">
            <i class="fas fa-exclamation-circle text-lg mr-3 text-red-600"></i>
            <span class="font-medium flex-1"><?php echo e(session('error')); ?></span>
            <button type="button" class="ml-auto bg-red-50 text-red-500 rounded-lg p-1.5 hover:bg-red-100" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
    <?php endif; ?>

    
    <header class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 py-6 px-6 rounded-2xl mb-8 shadow-sm">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl text-slate-800 font-extrabold tracking-tight flex items-center">
                    <span class="p-2.5 bg-indigo-600 text-white rounded-xl shadow-md mr-3 inline-flex items-center justify-center">
                        <i class="fas fa-video text-xl"></i>
                    </span>
                    Official Video Promotions & Tutorials
                </h1>
                <p class="text-slate-600 text-sm mt-2 font-medium">Upload and manage video content for Team Leaders & Super Leaders to promote and learn.</p>
            </div>
            <a href="<?php echo e(route('admin.leader-videos.upload')); ?>" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-sm transition duration-150 text-sm">
                <i class="fas fa-plus"></i> Upload New Video
            </a>
        </div>
    </header>

    
    <?php
        $allVideos = \App\Models\TeamLeaderVideo::latest()->get();
        $pendingCount = $allVideos->where('status', 'pending')->count();
        $approvedCount = $allVideos->where('status', 'approved')->count();
        $rejectedCount = $allVideos->where('status', 'rejected')->count();
    ?>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl p-4 border shadow-sm text-center">
            <p class="text-xs text-gray-400 font-bold uppercase">Total</p>
            <p class="text-2xl font-extrabold text-slate-800"><?php echo e($allVideos->count()); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-amber-200 shadow-sm text-center">
            <p class="text-xs text-amber-400 font-bold uppercase">Pending</p>
            <p class="text-2xl font-extrabold text-amber-600"><?php echo e($pendingCount); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-green-200 shadow-sm text-center">
            <p class="text-xs text-green-400 font-bold uppercase">Approved</p>
            <p class="text-2xl font-extrabold text-green-600"><?php echo e($approvedCount); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-red-200 shadow-sm text-center">
            <p class="text-xs text-red-400 font-bold uppercase">Rejected</p>
            <p class="text-2xl font-extrabold text-red-600"><?php echo e($rejectedCount); ?></p>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <?php if($allVideos->isEmpty()): ?>
            <p class="text-gray-500 text-sm py-12 text-center"><i class="fas fa-film text-3xl text-gray-300 mb-3 block"></i>No videos uploaded yet.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-[11px] tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left">Video</th>
                            <th class="px-6 py-3 text-left">Title</th>
                            <th class="px-6 py-3 text-left">Views Target</th>
                            <th class="px-6 py-3 text-left">Duration</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Date</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <?php $__currentLoopData = $allVideos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3">
                                    <?php if($v->video_url && ($v->video_type === 'youtube')): ?>
                                        <span class="inline-flex items-center gap-1 text-xs bg-red-50 text-red-700 font-bold px-2 py-1 rounded-lg"><i class="fab fa-youtube"></i> YouTube</span>
                                    <?php elseif($v->video_url): ?>
                                        <span class="inline-flex items-center gap-1 text-xs bg-blue-50 text-blue-700 font-bold px-2 py-1 rounded-lg"><i class="fas fa-file-video"></i> MP4</span>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-3 font-semibold text-gray-800 max-w-[200px] truncate"><?php echo e($v->title); ?></td>
                                <td class="px-6 py-3 text-gray-600"><?php echo e(number_format($v->views_count ?? 0)); ?></td>
                                <td class="px-6 py-3 text-gray-600"><?php echo e($v->duration_seconds ?? $v->targeted_duration ?? '—'); ?>s</td>
                                <td class="px-6 py-3">
                                    <?php if($v->status === 'approved' || $v->status === 'active'): ?>
                                        <span class="bg-green-100 text-green-700 font-bold text-xs px-2.5 py-1 rounded-full">Approved</span>
                                    <?php elseif($v->status === 'rejected'): ?>
                                        <span class="bg-red-100 text-red-700 font-bold text-xs px-2.5 py-1 rounded-full">Rejected</span>
                                    <?php else: ?>
                                        <span class="bg-amber-100 text-amber-700 font-bold text-xs px-2.5 py-1 rounded-full">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-3 text-gray-500 text-xs"><?php echo e($v->created_at->format('d M Y')); ?></td>
                                <td class="px-6 py-3 text-right space-x-2">
                                    <?php if($v->status !== 'approved' && $v->status !== 'active'): ?>
                                        <a href="<?php echo e(route('admin.leader-videos.approve', $v->id)); ?>" class="inline-flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg text-xs transition">
                                            <i class="fas fa-check"></i> Approve
                                        </a>
                                    <?php endif; ?>
                                    <?php if($v->status !== 'rejected'): ?>
                                        <button type="button" onclick="document.getElementById('reject-<?php echo e($v->id); ?>').classList.remove('hidden')" class="inline-flex items-center gap-1 bg-red-600 hover:bg-red-700 text-white font-bold py-1.5 px-3 rounded-lg text-xs transition">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    <?php endif; ?>
                                    <a href="<?php echo e(route('admin.leader-videos.delete', $v->id)); ?>" onclick="return confirm('Delete this video permanently?')" class="inline-flex items-center gap-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-1.5 px-3 rounded-lg text-xs transition">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            
                            <tr id="reject-<?php echo e($v->id); ?>" class="hidden bg-red-50">
                                <td colspan="7" class="px-6 py-4">
                                    <form action="<?php echo e(route('admin.leader-videos.reject', $v->id)); ?>" method="POST" class="flex items-center gap-3">
                                        <?php echo csrf_field(); ?>
                                        <input type="text" name="reason" required placeholder="Reason for rejection..." class="flex-1 bg-white border border-red-300 rounded-xl px-4 py-2 text-sm">
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl text-xs">Confirm Reject</button>
                                        <button type="button" onclick="document.getElementById('reject-<?php echo e($v->id); ?>').classList.add('hidden')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-xl text-xs">Cancel</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/leader-videos.blade.php ENDPATH**/ ?>