



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
           <!-- Team Members Card -->
           <div class="bg-slate-700 shadow-md rounded-lg p-4 self-start ">

               <h3 class="text-lg font-semibold py-2 text-slate-100">Team Members</h3>
            <div class=" flex items-center justify-between">
               <div>
                   <p class="text-4xl font-bold text-slate-300"><?php echo e($team_members); ?></p>
                </div>
                <div class="mr-4">
                   <i class="fa fa-users block text-slate-300" style="font-size: 3em"></i>
                </div>
            </div>
           </div>

           <!-- Personal Members Card -->
           <div class="bg-white shadow-md rounded-lg p-4 self-start  ">

               <h3 class="text-lg font-semibold py-2 text-slate-600">Person Members</h3>
               <div class="flex items-center justify-between">
                   <div>

                       <p class="text-4xl font-bold text-slate-600"><?php echo e($person_members); ?></p>
                    </div>
                    <div class="mr-4">
                       <i class="fa fa-user  text-slate-600" style="font-size: 3em"></i>
                    </div>
               </div>
           </div>

           <!-- Referrals Section -->
           <div class="bg-blue-900 text-white shadow-md rounded-lg p-6  col-span-2 grid grid-cols-2 gap-2 items-center">

               <a href="<?php echo e(route('referrals.free')); ?>" class="flex items-center gap-2 bg-gray-300 px-2 py-2 rounded-md self-center">
                     <div class=" ">
                       <h3 class="text-lg font-semibold ">Click Here to</h3>
                       <p>Free Referrals</p>
                     </div>
                     <div>
                       <i class="fa fa-external-link text-blue-400 text-lg font-bold" aria-hidden="true" ></i>

                     </div>
               </a>

               <a href="<?php echo e(route('referrals.paid')); ?>" class="flex items-center gap-2 bg-gray-300 px-2 py-2 rounded-md self-center">
                   <div class=" ">
                     <h3 class="text-lg font-semibold ">Click Here to</h3>
                     <p>Paid Referrals</p>
                   </div>
                   <div>
                     <i class="fa fa-external-link" aria-hidden="true"></i>

                   </div>
               </a>

           </div>

           <div class="bg-white shadow-md rounded-lg p-4 self-start  ">

               <h3 class="text-lg font-semibold py-2 text-slate-600">Person Customers</h3>
               <div class="flex items-center justify-between">
                   <div>

                       <p class="text-4xl font-bold text-slate-600">soon</p>
                    </div>
                    <div class="mr-4">
                       <i class="fa fa-user  text-slate-600" style="font-size: 3em"></i>
                    </div>
               </div>
           </div>

           <div class="bg-white shadow-md rounded-lg p-4 self-start  ">

               <h3 class="text-lg font-semibold py-2 text-slate-600">Merchants</h3>
               <div class="flex items-center justify-between">
                   <div>

                       <p class="text-4xl font-bold text-slate-600">soon</p>
                    </div>
                    <div class="mr-4">
                       <i class="fa fa-user  text-slate-600" style="font-size: 3em"></i>
                    </div>
               </div>
           </div>

         </div>

         <!-- Team Information Section -->
         <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
             <div class="bg-white shadow-md rounded-lg flex items-center  py-2 px-2">
                    <div class="px-3">
                        <i class="fas fa-users relative text-yellow-500" style="font-size: 2em"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-700">TOTAL </h3>
                        <p class="text-yellow-500 flex gap-2 py-2 items-center">Members: <span class="text-gray-600 "><?php echo e($total_members); ?></span></p>
                    </div>
              </div>


              <div class="bg-white shadow-md rounded-lg flex items-center  py-2 px-2">
                <div class="px-3">
                    <i class="fas fa-users relative text-yellow-500" style="font-size: 2em"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-semibold text-gray-700">LEFT TEAM </h3>
                    <p class="text-yellow-500 flex gap-2 py-2 items-center">Members: <span class="text-gray-600 "><?php echo e($left_team); ?></span></p>
                </div>
          </div>


          <div class="bg-white shadow-md rounded-lg flex items-center  py-2 px-2">
            <div class="px-3">
                <i class="fas fa-users relative text-yellow-500" style="font-size: 2em"></i>
            </div>
            <div>
                <h3 class="text-2xl font-semibold text-gray-700">RIGHT TEAM </h3>
                <p class="text-yellow-500 flex gap-2 py-2 items-center">Members: <span class="text-gray-600 "><?php echo e($right_team); ?></span></p>
            </div>
      </div>

         </div>

         <!-- Team Members Table -->
         <div class="bg-white shadow-md rounded-lg p-6 overflow-x-auto">
            <header>
                <h4 class="text-gray-600  px-2">TEAM MEMBERS</h4>
            </header>
           <table class="w-full text-left">
             <thead>
               <tr>
                 <th class="py-2 px-4">Username</th>
                 <th class="py-2 px-4">Activation Date</th>
                 <th class="py-2 px-4">Package</th>
                 <th class="py-2 px-4">Country</th>
                 <th class="py-2 px-4">Signup date</th>
                 <th class="py-2 px-4">Leadership Rank</th>
                 <th class="py-2 px-4">Sponsored By</th>
                 <th class="py-2 px-4">Team</th>
               </tr>
             </thead>
             <tbody>
              <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

              <tr>
                <td class="py-2 px-4"> <?php echo e($member->name); ?> </td>
                <td class="py-2 px-4">
                    <?php echo e($member->has_free_package == 'yes'?'Not Acivated Yet':$member->have_activation_code->created_at); ?>


                </td>

                <td class="py-2 px-4">
                    <?php echo e($member->has_free_package == 'yes'?'Not Acivated Yet':$member->has_paid_package); ?>


                </td>
                <td class="py-2 px-4">
                    <?php echo e($member->country); ?>

                </td>
                <td class="py-2 px-4"><?php echo e($member->updated_at); ?></td>
                <td class="py-2 px-4">soon</td>
                <td class="py-2 px-4"><?php echo e($member->teamSide->name); ?></td>
                <td class="py-2 px-4"><?php echo e($member->teamSide->side); ?></td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              <?php $__currentLoopData = $personM; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

              <tr>
                <td class="py-2 px-4"> <?php echo e($member->name); ?> </td>
                <td class="py-2 px-4">
                    <?php echo e(($member->has_paid_package == 'no' || $member->has_paid_package == 'standard')?'Not Acivated Yet':$member->have_activation_code->created_at); ?>


                </td>
                <td class="py-2 px-4">
                    <?php echo e($member->has_free_package == 'yes'?'Not Acivated Yet':$member->has_paid_package); ?>


                </td>
                <td class="py-2 px-4">
                    <?php echo e($member->country); ?>

                </td>
                <td class="py-2 px-4"><?php echo e($member->updated_at); ?></td>
                <td class="py-2 px-4">soon</td>
                <td class="py-2 px-4"><?php echo e($member->referrer->name); ?></td>
                <td class="py-2 px-4"><?php echo e($member->teamSide->side); ?></td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
             </tbody>
           </table>
         </div>


       </div>
     </div>

</body>
</div>
</div>
<?php /**PATH /home/cwkwkueb/mcu.focoin.eu/resources/views/user/team/downline.blade.php ENDPATH**/ ?>