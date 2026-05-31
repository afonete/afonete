

<?php $__env->startSection('contents'); ?>
    <div class="container bg-white h-screen py-4 px-3">
        <?php if(session('status')): ?>
            <div class="alert alert-success">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

       <header class="bg-blue-50 py-[2rem] rounded ">
        <h1 class="text-2xl uppercase text-slate-700 font-bold">CLAIMS - REPORT</h1>
    </header>





<div class=" mx-auto">

	<div class="relative overflow-x-auto shadow-md sm:rounded-lg">

        <div class="flex justify-between items-center  px-3">
             <!-- <a href="<?php echo e(route("admin.adventures.create")); ?>" class="bg-blue-500 py-2 px-3 rounded text-gray-50">New <i class="fa fa-save"></i></a> -->
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
                       dark:focus:border-blue-500" placeholder="Search...">
                </div>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="bg-green-500 text-white p-4 mb-4 rounded-md">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="bg-red-500 text-white p-4 mb-4 rounded-md">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

			<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="myTable">
				<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
					
                    <tr class="text-left">
                        <th class="p-3 border">#</th>
                        <th class="p-3 border">User</th>
                        <th class="p-3 border">Transaction ID</th>
                        <th class="p-3 border">Reason</th>
                        <th class="p-3 border">Status</th>
                        <th class="p-3 border text-center">Actions</th>
                    </tr>
				</thead>
				<tbody>
                    <?php $__currentLoopData = $claims; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $claim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-2 py-4">
                                <?php echo e($loop->iteration); ?>

                            </td>
                            <td class="px-2 py-4">
                                <?php echo e($claim->user->name); ?>

                            </td>
                            <td class="px-2 py-4">
                                <?php echo e($claim->transaction_no); ?>

                            </td>

                            <td class="px-2 py-4">
                                <?php echo e($claim->reason); ?> 
                            </td>
                            <td class="px-2 py-4">
                                <?php if($claim->is_fixed == 0): ?>
                                   <span class="px-3 py-1 bg-red-500 text-white rounded-full">unfixed</span>
                                   <?php else: ?>
                                   <span class="px-3 py-1 bg-green-600 text-white rounded-full" >fixed</span>
                                <?php endif; ?>
                            </td>

                            <td class="px-2 py-4">
                                
                            <div class="flex space-x-4">

                                <?php if($claim->is_fixed == 0): ?>
                                     <!-- Fixed Form -->
                                    <form action="<?php echo e(route('admin.fixclaim')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="type" value="fixed">
                                        <input type="hidden" name="claim" value="<?php echo e($claim->id); ?>">
                                        <input type="hidden" name="transcation" value="<?php echo e($claim->transaction_no); ?>"/>

                                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                        accept&fix
                                        </button>
                                    </form>
                                   <?php else: ?>
                                    <!-- Unfixed Form -->
                                    <!-- <form action="<?php echo e(route('admin.fixclaim')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="claim" value="<?php echo e($claim->id); ?>">
                                        <input type="hidden" name="type" value="unfixed">
                                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                            Unfixed
                                        </button>
                                    </form> -->
                                <?php endif; ?>

                               

                                
                            </div>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                
                <tr>
                    <td>
                        <div>
                            <?php

                                $claims->links()
                            ?>
                        </div>
                    </td>
                </tr>

                </tbody>
			</table>
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

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/cwkwkueb/mcu.focoin.eu/resources/views/admin/claims/ClaimList.blade.php ENDPATH**/ ?>