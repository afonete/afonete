



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
         <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-5">

            <h1>MY AWARDS COMMING SOON</h1>


       </div>
     </div>

</body>
</div>
</div>
<?php /**PATH /home/cwkwkueb/mcu.focoin.eu/resources/views/user/team/myawards.blade.php ENDPATH**/ ?>