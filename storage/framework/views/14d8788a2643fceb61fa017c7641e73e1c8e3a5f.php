<nav class="bg-white rounded ">
    <ul class="flex px-2">
        <li class="py-3 px-2"> <a href="<?php echo e(route('user.dashboard.payments')); ?>"   class="<?php echo e(request()->routeIs('user.dashboard.payments')?'text-yellow-500':'text-slate-500'); ?>  text-md font-semibold  ">Cash Account</a></li>
        <li class="py-3 px-2 h-[1px] text-gray-500">|</li>
        <li class="py-3 px-2"> <a href="<?php echo e(route('user.dashboard.coinacc')); ?>"    class="<?php echo e(request()->routeIs('user.dashboard.coinacc')?'text-yellow-500':'text-slate-500'); ?>  text-md font-semibold  ">Coin Account</a></li>
        <li class="py-3 px-2 h-[1px] text-gray-500">|</li>
        <li class="py-3 px-2"> <a href="<?php echo e(route('user.dashboard.tradingac')); ?>"  class="<?php echo e(request()->routeIs('user.dashboard.tradingac')?'text-yellow-500':'text-slate-500'); ?>  text-md font-semibold  ">Trading Account</a></li>
        <li class="py-3 px-2 h-[1px] text-gray-500">|</li>
        <li class="py-3 px-2"> <a href="<?php echo e(route('user.dashboard.myinvoices')); ?>" class="<?php echo e(request()->routeIs('user.dashboard.myinvoices')?'text-yellow-500':'text-slate-500'); ?>  text-md font-semibold  ">My Invoices</a></li>
    </ul>
</nav>
<?php /**PATH C:\xampp\htdocs\KANANI\BIFONEX\mcu.focoin.eu\resources\views/components/PaymentNav.blade.php ENDPATH**/ ?>