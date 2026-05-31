<div class="grid grid-cols-2 bg-white p-4 rounded-lg mb-6 space-x-2 md:space-x-4">

    <div class="flex flex-col sm:flex-row justify-around gap-2">
        <a href="<?php echo e(route('teambuilding')); ?>" class="<?php echo e(request()->routeIs('teambuilding') ? 'bg-purple-500 px-2 rounded py-1
         text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800'); ?> self-start" style="color:white">
            <i class="fas fa-home text-white" ></i>
        </a>

        <a href="<?php echo e(route('team.structure')); ?>" class="<?php echo e(request()->routeIs('team.structure') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800'); ?>">Team Structure</a>
        <a href="<?php echo e(route('team.genealogy')); ?>" class="<?php echo e(request()->routeIs('team.genealogy') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800'); ?>">Team Genealogy</a>
        <a href="<?php echo e(route('downline')); ?>" class="<?php echo e(request()->routeIs('downline') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800'); ?>">Downline</a>
        <a href="<?php echo e(route('volume.points')); ?>" class="<?php echo e(request()->routeIs('volume.points') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800'); ?>">Volume Points</a>
        <a href="<?php echo e(route('team.ranking')); ?>" class="<?php echo e(request()->routeIs('team.ranking') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800'); ?>">Team Ranking</a>

    </div>
    <div  class="flex flex-col sm:flex-row justify-around gap-2">
    <a href="<?php echo e(route('teams.groups')); ?>" class="<?php echo e(request()->routeIs('teams.groups') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800'); ?>">Teams & Groups</a>
    <a href="<?php echo e(route('my.awards')); ?>" class="<?php echo e(request()->routeIs('my.awards') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800'); ?>">My Awards</a>
    <a href="<?php echo e(route('commission')); ?>" class="<?php echo e(request()->routeIs('commission') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800'); ?>">Commission</a>
    <a href="<?php echo e(route('focoin.point')); ?>" class="<?php echo e(request()->routeIs('focoin.point') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800'); ?>">Focoin Point</a>
    <a href="<?php echo e(route('fone.commission')); ?>" class="<?php echo e(request()->routeIs('fone.commission') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800'); ?>">Fone Commission</a>
    <a href="<?php echo e(route('fomo.commission')); ?>" class="<?php echo e(request()->routeIs('fomo.commission') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800'); ?>">FOMO Commission</a>
    <a href="<?php echo e(route('merchant')); ?>" class="<?php echo e(request()->routeIs('merchant') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800'); ?>">Merchant</a>

    </div>

</div>
<?php /**PATH /home/cwkwkueb/test.focoin.eu/resources/views/components/navbar.blade.php ENDPATH**/ ?>