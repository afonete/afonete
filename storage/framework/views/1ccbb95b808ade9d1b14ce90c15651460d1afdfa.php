



<div class="wrapper">
     <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
     <title>Overview</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <div class="content-wrapper">
<head>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/gojs/release/go.js"></script>

</head>
<body>

    <div class="flex-1 p-4">

        <div class="flex flex-col gap-6">

          <!-- Menu Section -->

           <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.navbar','data' => []]); ?>
<?php $component->withName('navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

          <!-- Stats Overview Section -->
          <div class="w-full sm:w-1/2 mx-auto flex flex-col gap-6">
            <div class="flex flex-col justify-center items-center">
                <img src="<?php echo e(asset("image/rf5.png")); ?>" class="w-20"/>
                <p class="py-2 bg-gray-700 text-white px-2 rounded-lg ">You</p>
            </div>
            <div class="grid grid-cols-3">
                <div class="flex flex-col justify-center items-center self-start">
                    <h4 class="text-sm sm:text-base md:text-lg lg:text-xl xl:text-2xl 2xl:text-3xl">Indirect</h4>

                    <div class="flex flex-col">
                        <?php $__currentLoopData = $indirect; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex flex-col justify-center items-center">
                            <img src="<?php echo e(asset('image/rf5.png')); ?>" class="w-16 sm:w-20 md:w-24 lg:w-32 xl:w-40 2xl:w-48" alt="Image description">

                            <p class="py-2 bg-gray-700 text-white px-2 rounded-lg"><?php echo e($member->name); ?></p>
                            <p><i class="fa fa-arrow-down"></i></p>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>


                </div>

                <div class="flex flex-col justify-center items-center self-start">
                    <h4 class="text-sm sm:text-base md:text-lg lg:text-xl xl:text-2xl 2xl:text-3xl">Direct</h4>


                        <?php $__currentLoopData = $direct; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex flex-col justify-center items-center">
                            <img src="<?php echo e(asset('image/rf5.png')); ?>" class="w-16 sm:w-20 md:w-24 lg:w-32 xl:w-40 2xl:w-48" alt="Image description">

                            <p class="py-2 bg-gray-700 text-white px-2 rounded-lg"><?php echo e($member->name); ?></p>
                            <p><i class="fa fa-arrow-down"></i></p>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>




                </div>


                <div class="flex flex-col justify-center items-center self-start">
                    <h4 class="text-sm sm:text-base md:text-lg lg:text-xl xl:text-2xl 2xl:text-3xl">Referall</h4>

                    <?php $__currentLoopData = $indirect; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex flex-col justify-center items-center">
                        <img src="<?php echo e(asset('image/rf5.png')); ?>" class="w-16 sm:w-20 md:w-24 lg:w-32 xl:w-40 2xl:w-48" alt="Image description">

                        <p class="py-2 bg-gray-700 text-white px-2 rounded-lg"><?php echo e($member->name); ?></p>
                        <p><i class="fa fa-arrow-down"></i></p>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                </div>

            </div>





          </div>


        </div>
      </div>

</body>
</div>
</div>
<?php /**PATH /home/cwkwkueb/test.focoin.eu/resources/views/user/teambuilding.blade.php ENDPATH**/ ?>