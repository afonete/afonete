<?php $__env->startSection('contents'); ?>
    <div class="container bg-white h-screen py-4 px-3">
        <?php if(session('status')): ?>
            <div class="alert alert-success">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

       <header class="bg-blue-50 py-[2rem] rounded ">
        <h1 class="text-2xl uppercase text-slate-700 font-bold">USERS - LIST</h1>

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


			<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="myTable">
				<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
					<tr>
						<th scope="col" class="p-4">
							
                            #
						</th>


						<th scope="col" class="px-6 py-3">
						Name
						</th>
                        <th scope="col" class="px-3 py-3">
                            User Id
                        </th>


						<th scope="col" class="px-3 py-3">
							Email
						</th>
						<th scope="col" class="px-3 py-3">
                            Phone
						</th>
						<th scope="col" class="px-6 py-3">
							Country
						</th>
                        <th scope="col" class="px-6 py-3">
                            Gender
						</th>

                        <th scope="col" class="px-2 py-3">
							Type
						</th>

                        <th scope="col" class="px-2 py-3">
							Rank
						</th>
                        <th scope="col" class="px-2 py-3">
							Email Verify
						</th>
                        <th scope="col" class="px-2 py-3">
							Date Joined
						</th>
                        <th scope="col" class="px-2 py-3">
							Sponsored
						</th>



					</tr>
				</thead>
				<tbody>


                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deposit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    

                    <tr
						class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
						<td class="w-4 p-4">
							
                            <?php echo e($loop->iteration); ?>

						</td>

						<th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
							<?php echo e($deposit->name); ?>

						</th>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
							<?php echo e($deposit->id); ?>

						</th>
						<td class="px-3 py-4">
                            <?php echo e($deposit->email); ?>

						</td>
						<td class="px-3 py-4 uppercase">
							<?php echo e($deposit->phone); ?>

						</td>
						<td class="px-6 py-4">
							<?php echo e($deposit->country); ?>

						</td>
                        <td class="px-6 py-4">
							<?php echo e($deposit->gender); ?>

						</td>


                        <td class="px-2 py-4">
                            <?php echo e($deposit->utype); ?>

                        </td>
                        <td class="px-2 py-4 text-center">
                            comming soon
                        </td>
                        <td class="px-2 py-4">
                            <?php if($deposit->email_verified_at ): ?>
                              <span class="bg-green-500 px-2 py-1 text-white rounded-md">VERIFIED</span>
                             <?php else: ?>
                             <span class="bg-yellow-500 px-2 py-1 text-white rounded-md">UNVERIFIED</span>
                             <?php endif; ?>
                         </td>

                         <td class="px-2 py-4">
                            <?php echo e($deposit->created_at); ?>

                        </td>

                        <td class="px-2 py-4">
                           System
                        </td>

					</tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <tr>
            <td colspan="12 " class="py-3 d-block bg-white">
              <div class="px-2 py-2 ">
                <?php echo e($users->links()); ?>

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

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/users-list.blade.php ENDPATH**/ ?>