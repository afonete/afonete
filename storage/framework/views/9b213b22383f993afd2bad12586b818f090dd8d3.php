<?php $__env->startSection('contents'); ?>
    <div class="container bg-white min-h-screen py-4 px-3">
        <?php if(session('status')): ?>
            <div class="alert alert-success">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

       <header class="bg-blue-50 py-[2rem] rounded ">
        <h1 class="text-2xl uppercase text-slate-700 font-bold">ADVENTURES - CREATE</h1>

    </header>





<div class=" mx-auto">

    <div class="my-3">

        <a href="<?php echo e(route('admin.adventures')); ?>" class="bg-purple-500 text-white py-2 px-3 my-2 flex gap-2 w-28 items-center rounded justify-center">
            <span>Report</span>
            <i class="fa fa-book"></i>
        </a>
    </div>

	<div class="relative overflow-x-auto sm:rounded-lg py-3">

        <?php if(session('message')): ?>
            <div class="text-green-100 mx-2 py-2 px-1 bg-green-500 rounded my-2">
                <?php echo e(session('message')); ?>

            </div>
        <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="bg-red-400 py-2 px-2 rounded shadow-sm">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="text-red-600"><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>



            <form action="<?php echo e(route("admin.adventures.store")); ?>" method="POST" class="block sm:grid grid-cols-3 gap-3">
                <?php echo csrf_field(); ?>
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700  pb-2">Names: </label>
                        <input type="text" id="name" name="name" class=" block w-full border-gray-300
                         rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                         placeholder="Ex: UVP, default:UVP"
                         value="<?php echo e(old('name')); ?>"
                         >
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700  pb-2">Plan: </label>
                        <input type="text"  name="plan" class=" block w-full border-gray-300
                         rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                         placeholder="Ex: Venture Light"
                         value="<?php echo e(old('plan')); ?>"

                         >
                    </div>


                    

                <div>
                    <label for="percentage_earning" class="block text-sm font-medium text-gray-700  pb-2">Percentage (%): </label>
                    <input type="number" step="0.01" id="percentage_earning"
                     name="percentage" placeholder="Ex:  1%"
                      class=" block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring
                      focus:ring-blue-500 focus:ring-opacity-50" required value="<?php echo e(old('percentage')); ?>">
                </div>


                <div>
                    <label for="min_amount" class="block text-sm font-medium text-gray-700  pb-2">Minimum Amount: </label>
                    <input type="number" id="min_amount" name="min_amount"
                     class=" block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring
                      focus:ring-blue-500 focus:ring-opacity-50" required placeholder="Ex: 19000$" value="<?php echo e(old('min_amount')); ?>">
                </div>
                <div>
                    <label for="max_amount" class="block text-sm font-medium text-gray-700  pb-2">Maximum Amount: </label>
                    <input type="number" id="max_amount" name="max_amount"
                    class=" block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring
                     focus:ring-blue-500 focus:ring-opacity-50" required placeholder="Ex: 400000$" value="<?php echo e(old('max_amount')); ?>">
                </div>

                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700  pb-2">Duration (Days): </label>
                    <input type="number" id="duration" name="duration"
                    class=" block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring
                    focus:ring-blue-500 focus:ring-opacity-50" required placeholder="Ex: 100 days" value="<?php echo e(old('duration')); ?>">
                </div>

                <div>
                    <label for="total_return" class="block text-sm font-medium text-gray-700  pb-2">Total Return (%): </label>
                    <input type="number" id="total_return" name="total_return"
                     class=" block w-full border-gray-300 rounded-md shadow-sm
                      focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required

                      placeholder="EX: 130%" value="<?php echo e(old('total_return')); ?>">
                </div>

                <div>
                      <label for="currency" class="block text-sm font-medium text-gray-700  pb-2">Choose a currency:</label>
                        <select name="currency" id="currency"   class=" block w-full border-gray-300 rounded-md shadow-sm
                        focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 py-2 px-2 text-gray-500">
                            <option value="EUR">Euro (€)</option>
                            <option value="USD" selected>USD ($)</option>
                        </select>
                </div>

                <div>
                    <label for="current_price" class="block text-sm font-medium text-gray-700  pb-2">Current Price : </label>
                    <input type="number" id="current_price" name="current_price"
                      class=" block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                      required
                      value="0.0025"
                      >
                </div>

                <div class=" col-span-3 my-2">
                    <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-sm
                     hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 w-full sm:w-auto ">
                    Save <i class="fa fa-save"></i>
                    </button>
                </div>
            </form>


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

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/uvp/create.blade.php ENDPATH**/ ?>