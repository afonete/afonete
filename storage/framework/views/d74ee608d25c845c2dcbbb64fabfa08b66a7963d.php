<?php $__env->startSection('contents'); ?>
    <div class="container bg-white h-screen py-4 px-3">
        <?php if(session('status')): ?>
            <div class="alert alert-success">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

       <header class="bg-blue-50 py-[2rem] rounded ">
        <h1 class="text-2xl uppercase text-slate-700 font-bold">Pending Deposits</h1>

    </header>

<!-- Modal container -->
<div id="modal" class="fixed top-0 left-0 w-full z-50 h-full flex
items-center justify-center bg-gray-800 bg-opacity-75 hidden">
    <!-- Modal content -->
    <div class="bg-white p-8 rounded shadow-md w-1/2">
        <!-- Modal header -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">COMMENT</h3>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <!-- Modal body -->
        <form method="POST" action="<?php echo e(route('admin.otherwise-decision-deposit')); ?>">
            <?php echo csrf_field(); ?>
        <!-- Modal footer -->
        <div id="inputs">

        </div>

        <label>Enter Comment:</label>
        <textarea rows="5" class="w-full px-2 outline-none dark:focus:ring-blue-500 rounded border" name="comment">

        </textarea>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 block w-full text-center text-white px-4 py-2 rounded focus:outline-none">
                Send
            </button>
        </div>
    </form>
    </div>
</div>

<!-- Button to open modal -->





<div class=" mx-auto">

	<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
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




            <strong class="text-green-500 mx-2">
                <?php echo e(session('message')); ?>

            </strong>
			<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="myTable">
				<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
					<tr>
						<th scope="col" class="p-4">
							
                            #
						</th>

						<th scope="col" class="px-6 py-3">
							Customer Names
						</th>


						<th scope="col" class="px-6 py-3">
							Amount
						</th>
						<th scope="col" class="px-6 py-3">
							Deposit Method
						</th>
						<th scope="col" class="px-6 py-3">
							Currency
						</th>
                        <th scope="col" class="px-6 py-3">
							Date
						</th>
                        <th scope="col" class="px-6 py-3">
							Current Status
						</th>


						<th scope="col" class="px-6 py-3">
							Actions
						</th>
					</tr>
				</thead>
				<tbody>


                    <?php $__currentLoopData = $deposits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deposit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>



                    <tr
						class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
						<td class="w-4 p-4">
							
                            <?php echo e($loop->iteration); ?>

						</td>

						<th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
							<?php echo e($deposit->user->name); ?>

						</th>

						<td class="px-6 py-4">
                            $<?php echo e(number_format($deposit->amount_deposited, 2)); ?>

						</td>
						<td class="px-6 py-4 uppercase">
							<?php echo e($deposit->deposit_method); ?>

						</td>
						<td class="px-6 py-4">
							Dollar
						</td>
                        <td class="px-6 py-4">
							<?php echo e($deposit->created_at->format('Y-m-d h:i A')); ?>

						</td>
                        <td class="px-6 py-4 status">
                          <?php
                          $color = "";
                            if($deposit->status == 'pending'){$color = 'bg-yellow-500';}
                            if($deposit->status == 'cancelled'){$color = 'bg-red-500';}
                            if($deposit->status == 'rejected'){$color = 'bg-purple-700';}
                            if($deposit->status == 'approved'){$color = 'bg-green-500';}
                            if($deposit->status == 'under-review'){$color = 'bg-blue-500';}

                            ?>
                           <span class="px-2 py-1 block text-xs  text-center <?php
                            echo $color;

                           ?> rounded-2xl text-white"> <?php echo e(ucfirst($deposit->status)); ?>  </span>

                        </td>
						<td class="px-6 py-4 text-right">

                            <div class="relative inline-block ">
                                <button onclick="toggleDropdown(this)"
                                 class="z-1 justify-center w-full rounded-md border
                                  border-gray-300 px-4 py-2 bg-white text-sm
                                  font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v.01M12 12v.01M12 18v.01"></path>
                                  </svg>
                                </button>
                                <div class="dropdown-menu z-40 hidden origin-top-right absolute -left-[10.3rem] -top-4 mt-2
                                w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none ">
                                    <div class="flex flex-col uppercase text-left p-1">
                                      <?php if($deposit->status != 'approved'): ?>
                                      <form action="<?php echo e(route('admin.approve-deposit')); ?>" method="POST">
                                          <?php echo csrf_field(); ?>
                                          <input type="hidden" name="deposit" value="<?php echo e($deposit->id); ?>"/>
                                          <button type="submit"
                                          class="text-sm py-2 text-blue-500 hover:bg-indigo-100 rounded hover:shadow flex gap-2 px-2 items-center w-full">
                                          <i class="fa fa-check"></i> <span> Approve</span>
                                          </button>
                                      </form>
                                      <?php endif; ?>

                                      <?php if($deposit->status != 'under-review'): ?>
                                      <button class="uppercase py-2 text-gray-600 hover:bg-gray-100 rounded hover:shadow flex gap-2 px-2 items-center w-full" onclick="show('under-review','<?php echo e($deposit->id); ?>')">
                                        <i class="fa fa-hourglass-half"></i> <span>Under Review</span>
                                      </button>
                                      <?php endif; ?>

                                      <?php if($deposit->status != 'rejected'): ?>
                                      <button class="uppercase py-2 text-yellow-600 hover:bg-yellow-100 rounded hover:shadow flex gap-2 px-2 items-center w-full" onclick="show('rejected','<?php echo e($deposit->id); ?>')">
                                        <i class="fa fa-ban"></i>
                                        <span>Reject</span>
                                      </button>
                                      <?php endif; ?>

                                      <?php if($deposit->status != 'cancelled'): ?>
                                      <button class="uppercase py-2 text-red-500 hover:bg-red-50 flex gap-2 px-2 items-center w-full hover:shadow" onclick="show('cancelled','<?php echo e($deposit->id); ?>')">
                                         <i class="fa fa-trash"></i> <span> Cancel</span>
                                      </button>
                                      <?php endif; ?>
                                    </div>
                                  </div>

                              </div>




                            



						</td>
					</tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


				</tbody>
			</table>
		</div>


		
	</div>

    <script src="<?php echo e(asset('assets/a/plugins/jquery/jquery.min.js')); ?>"></script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo e(asset('assets/a/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
    <script>


function toggleDropdown(button) {
    // Close any currently open dropdowns
    const openDropdowns = document.querySelectorAll('.dropdown-menu:not(.hidden)');
    openDropdowns.forEach(dropdown => {
        dropdown.classList.add('hidden');
    });

    // Toggle the clicked dropdown
    const dropdown = button.nextElementSibling;
    dropdown.classList.toggle("hidden");

    // Detect clicks outside of the dropdown to close it
    document.addEventListener('click', function(event) {
        if (!dropdown.contains(event.target) && !button.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    }, { once: true }); // The event listener is removed after one execution
}


      $(document).ready(function(){

        $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#myTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
        });

        $('#pending').on('click', function() {

        sortTable('pending');
    });

    // Sort by Cancelled
    $('#cancelled').on('click', function() {
        sortTable('cancelled');
    });

    // Sort by Approved
    $('#approved').on('click', function() {
        sortTable('approved');
    });

    function sortTable(status) {
        var rows = $('#statusTable tbody tr').get();

        // Sort rows based on the given status
        rows.sort(function(a, b) {
            var statusA = $(a).find('.status span').text().toLowerCase();
            var statusB = $(b).find('.status span').text().toLowerCase();

            if (statusA === status && statusB !== status) {
                return -1;
            }
            if (statusA !== status && statusB === status) {
                return 1;
            }
            return 0;
        });

        // Append sorted rows back into the table body
        $.each(rows, function(index, row) {
            $('#statusTable tbody').append(row);
        });
    }



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

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/cwkwkueb/mcu.focoin.eu/resources/views/admin/payments.blade.php ENDPATH**/ ?>