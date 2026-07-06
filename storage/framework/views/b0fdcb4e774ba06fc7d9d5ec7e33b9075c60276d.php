<?php $__env->startSection('contents'); ?>
    <div class="container bg-white h-screen py-4 px-3">
        <?php if(session('status')): ?>
            <div class="alert alert-success">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

       <header class="bg-blue-50 py-[2rem] rounded ">
        <h1 class="text-2xl uppercase text-slate-700 font-bold">USERS - MEMBERSHIP PLANS</h1>

    </header>





<div class=" mx-auto">

	<div class="relative overflow-x-auto shadow-md sm:rounded-lg">

        <div class="flex justify-between items-center  px-3">
             
             <div class="p-4">
                <label for="table-search" class="sr-only">Search</label>
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <input type="text" id="searchInput"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                     focus:ring-blue-500 focus:border-blue-500 block w-80 pl-10 p-2.5  dark:bg-gray-700
                      dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500
                       dark:focus:border-blue-500" placeholder="Search username,names,email,date,..">
                </div>
            </div>
        </div>


        <div class="overflow-x-auto">
            <table id="myTable" class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">#</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">User Details</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Plan Type</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Amount</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Interest(%)</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Daily Bonus</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Duration</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Reward</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Plan Status</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">User Level</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Membership Details</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Role Position</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Groups</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Country</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Approved Date</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">KYC Status</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Reference</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row 1 -->
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $membership = $user->membershipSummary;
                    ?>
                    <tr class="bg-white border-b hover:bg-gray-50 align-top">
                        <td class="px-4 py-3 text-sm text-gray-700 border-b">
                            <?php echo e($users->firstItem() + $loop->index); ?>

                        </td>

                        <td class="px-4 py-3 text-sm text-gray-700 border-b min-w-[260px]">
                            <div class="flex flex-col gap-1">
                                <div class="font-semibold text-gray-900">
                                    <i class="fa fa-user text-xs pr-1 text-gray-300"></i><?php echo e($user->name); ?>

                                </div>
                                <div><span class="font-semibold">Username:</span> <?php echo e($user->user ?? 'N/A'); ?></div>
                                <div><i class="fa fa-envelope text-xs pr-1 text-gray-300"></i><?php echo e($user->email); ?></div>
                                <div><i class="fa fa-phone-square text-xs pr-1 text-gray-300"></i><?php echo e($user->phone); ?></div>
                                <div><i class="fa fa-calendar-check text-xs pr-1 text-gray-300"></i>Joined: <?php echo e($user->created_at); ?></div>
                            </div>
                        </td>

                        <td class="px-4 py-3 text-sm text-gray-700 border-b min-w-[190px]">
                            <div class="font-semibold text-gray-900"><?php echo e($membership->plan_type); ?></div>
                            <div class="text-xs text-gray-500">Category: <?php echo e($membership->category); ?></div>
                        </td>

                        <td class="px-4 py-3 text-sm text-gray-700 border-b whitespace-nowrap">
                            <?php echo e($membership->amount); ?>

                        </td>

                        <td class="px-4 py-3 text-sm text-gray-700 border-b whitespace-nowrap">
                            <?php echo e($membership->interest); ?>

                        </td>

                        <td class="px-4 py-3 text-sm text-gray-700 border-b whitespace-nowrap">
                            <?php echo e($membership->daily_bonus); ?>

                        </td>

                        <td class="px-4 py-3 text-sm text-gray-700 border-b whitespace-nowrap">
                            <?php echo e($membership->duration); ?>

                        </td>

                        <td class="px-4 py-3 text-sm text-gray-700 border-b min-w-[180px]">
                            <?php echo e($membership->reward); ?>

                        </td>

                        <td class="px-4 py-3 text-sm text-gray-700 border-b whitespace-nowrap">
                            <span class="<?php echo e($membership->status_badge_class); ?> px-2 py-1 text-white rounded-md text-xs font-semibold">
                                <?php echo e($membership->plan_status); ?>

                            </span>
                        </td>

                        <td class="px-4 py-3 text-sm text-gray-700 border-b whitespace-nowrap">
                            <?php echo e($membership->user_level); ?>

                        </td>

                        <td class="px-4 py-3 text-xs text-gray-700 border-b min-w-[230px]">
                            <div class="flex flex-col gap-1">
                                <?php $__currentLoopData = $membership->membership_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div><?php echo e($detail); ?></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </td>

                        <td class="px-4 py-3 text-xs text-gray-700 border-b min-w-[170px]">
                            <div class="flex flex-col gap-1">
                                <?php $__currentLoopData = $membership->role_position; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div><?php echo e($role); ?></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </td>

                        <td class="px-4 py-3 text-xs text-gray-700 border-b min-w-[170px]">
                            <div class="flex flex-col gap-1">
                                <?php $__currentLoopData = $membership->groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div><?php echo e($group); ?></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </td>

                        <td class="px-4 py-3 text-sm text-gray-700 border-b whitespace-nowrap">
                            <?php echo e($membership->country); ?>

                        </td>

                        <td class="px-4 py-3 text-sm text-gray-700 border-b whitespace-nowrap">
                            <?php echo e($membership->approved_date); ?>

                        </td>

                        <td class="px-4 py-3 text-xs text-gray-700 border-b min-w-[150px]">
                            <div class="flex flex-col gap-1">
                                <span class="<?php echo e($membership->email_verified ? 'bg-green-500' : 'bg-yellow-500'); ?> px-2 py-1 text-white rounded-md text-xs font-semibold text-center">
                                    <?php echo e($membership->email_verified ? 'Email verified' : 'Email unverified'); ?>

                                </span>
                                <span class="<?php echo e($membership->contract_signed ? 'bg-green-500' : 'bg-yellow-500'); ?> px-2 py-1 text-white rounded-md text-xs font-semibold text-center">
                                    <?php echo e($membership->contract_signed ? 'Contract signed' : 'Contract pending'); ?>

                                </span>
                            </div>
                        </td>

                        <td class="px-4 py-3 text-xs text-gray-700 border-b min-w-[130px]">
                            <div class="flex flex-col gap-1">
                                <?php $__currentLoopData = $membership->reference_lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reference): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div><?php echo e($reference); ?></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td colspan="17" class="py-2 px-3">
                            <?php echo e($users->links()); ?>

                        </td>
                    </tr>
                    <!-- Add more rows here -->
                </tbody>
            </table>
        </div>


		</div>


		
	</div>

    <script src="<?php echo e(asset('assets/a/plugins/jquery/jquery.min.js')); ?>"></script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo e(asset('assets/a/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
    <script>



      $(document).ready(function(){

        $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#myTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });



    $('#openModal').click(function() {
                $('#modal').removeClass('hidden');
            });

            $('#closeModal').click(function() {
                $('#modal').addClass('hidden');
                $("#action").value = ""
            });

      })
      function show(action,deposit){
        // alert(deposit)

        $('#modal').removeClass('hidden');
        // $("#action").value(action)
        // $("#deposit").value(deposit);
        $("#inputs").html(`
        <input type="hidden" name="action" value="${action}"/>
        <input type="hidden" name="deposit" value="${deposit}"/>
        `)

    }
    </script>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/admin-users-memberships.blade.php ENDPATH**/ ?>