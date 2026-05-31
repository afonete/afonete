<?php $__env->startSection('contents'); ?>
    <div class="container bg-white h-screen py-4 px-3">
        <?php if(session('status')): ?>
            <div class="alert alert-success">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

       <header class="bg-blue-50 py-[2rem] rounded ">
        <h1 class="text-2xl uppercase text-slate-700 font-bold">FC PACKAGE - CREATE</h1>

    </header>





<div class=" mx-auto">

	<div class="relative overflow-x-auto sm:rounded-lg py-3">





        <?php if(session('message')): ?>
        <p class="text-gray-200 mx-2 py-2 px-1 bg-green-800 rounded">
            <?php echo e(session('message')); ?>


        </p>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="bg-red-600 py-2 px-2 rounded shadow-sm">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="text-red-200"><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>



            <form action="<?php echo e(route("fcpackages.store")); ?>" method="POST" class="flex flex-col w-auto  sm:w-1/2 mx-auto gap-3">
                <?php echo csrf_field(); ?>
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700  pb-2">Name: </label>
                        <input type="text" id="name" name="name" class=" block w-full border-gray-300
                         rounded-md focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                         placeholder="Ex: FC1"
                         value="<?php echo e(old('name')); ?>"
                         >
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700  pb-2">Amount: </label>
                        <input type="number" step="0.01"  name="price" class=" block w-full border-gray-300
                         rounded-md  focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                         placeholder="Ex: 200"
                        value="<?php echo e(old('price')); ?>"
                         >
                    </div>












                <div class=" ">
                    <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-sm
                     hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500
                      focus:ring-opacity-50 w-full">
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

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/cwkwkueb/mcu.focoin.eu/resources/views/admin/fc/create.blade.php ENDPATH**/ ?>