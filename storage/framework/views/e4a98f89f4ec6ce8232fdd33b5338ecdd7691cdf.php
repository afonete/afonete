<?php

use Illuminate\Support\Facades\Auth;
use App\Models\User;

$user = Auth::user();
$name = $user->name;
$uname = $user->user;
$email=$user->email;
$phone=$user->phone;
$gender=$user->gender;
$country=$user->country;
$package=$user->has_paid_package;
?>

<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="content-wrapper py-5">

   <div class="tabs tab_links" >
        <span class="links_tabs d-flex ">
          <a href="<?php echo e(route('profile.edit')); ?>" class="fomoLink" id="tabs"> <i class="fa-regular fa-address-card"></i> My profile</a>
          <a href="<?php echo e(route('password.show')); ?>" class="fomoLink"><i class="fa-solid fa-lock"></i> Password</a>
          <a href="<?php echo e(route('wallet')); ?>" class="fomoLink"><i class="fa-solid fa-wallet"></i> wallet address</a>
          <a href="/user/kyc" class="fomoLink"><i class="fa-solid fa-id-card"></i>KYC</a>
        </span>
      </div>
    <br>
    <div class="prof-update">
        <h2 class="upd-title">User Information Update
            <span class="bg-success"> 
                <?php echo e(session('success')??''); ?>

            </span>
             <span class="bg-danger"> 
               <?php echo e(session('fail')??''); ?></span>

            </h2>
            <div class="row upd-row">   
                <div class="col-lg-3 prof-img">
                     <img src="<?php echo e(asset('assets/a/img/user.png')); ?>" class="" alt="User Image">
                     <div class="UserName">
                         <?php echo e($name); ?>

                     </div>
                </div>
                <div class="col-lg-8">
                        <div class="container-fluid">
                            <?php if(!$show): ?>
                            <form action="<?php echo e(route('profile.updates')); ?>" method="post">
                                <?php echo csrf_field(); ?>
                              <!--   <div class=" form-group">
                                    <label for="profile-pic">Profile Picture:</label>
                                    <input type="file" id="profile-pic" name="pic">
                                </div> -->
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="name">Username:</label>
                                        <input type="text" id="name" name="username" value="<?php echo e($uname); ?>" required disabled>
                                    </div>
                                       <div class="form-group col">
                                        <label for="name">Name:</label>
                                        <input type="text" id="name" name="name" value="<?php echo e($name); ?>" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="member_id">Member ID:</label>
                                        <input type="text" id="member_id" name="member_id" value="" placeholder="Member ID" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="city">City:</label>
                                        <input type="text" id="city" name="city" value="" placeholder="City" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="dob">Date of Birth:</label>
                                        <input type="date" id="dob" name="dob" value="" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="joining_date">Joining Date:</label>
                                        <input type="date" id="joining_date" name="joining_date" value="" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="referral_date">User Referral Date:</label>
                                        <input type="date" id="referral_date" name="referral_date" value="" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="referral_name">Referral User Name:</label>
                                        <input type="text" id="referral_name" name="referral_name" value="" placeholder="Referral User Name" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="referral_id">Referral ID:</label>
                                        <input type="text" id="referral_id" name="referral_id" value="" placeholder="Referral ID" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="referral_email">Referral Email:</label>
                                        <input type="email" id="referral_email" name="referral_email" value="" placeholder="Referral Email" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col">
                                        <label for="referral_country">Referral Country:</label>
                                        <select id="referral_country" class="form-group " name="referral_country" required>
                                            <option value="">Select Country</option>
                                            <option value="USA">United States</option>
                                            <option value="UK">United Kingdom</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Australia">Australia</option>
                                            <option value="India">India</option>
                                            <option value="Germany">Germany</option>
                                            <option value="France">France</option>
                                            <option value="Japan">Japan</option>
                                            <option value="China">China</option>
                                            <option value="Brazil">Brazil</option>
                                        </select>
                                    </div>
                                 </div>
                                 
                              
                                </div>                                                           
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="email">Email:</label>
                                        <input type="email" id="email" name="email" value="<?php echo e($email); ?>" disabled>
                                    </div>
                                    <div class="form-group col">
                                        <label for="phone">Phone:</label>
                                        <input type="tel" id="phone" name="phone" value="<?php echo e($phone); ?>" required>
                                    </div>
                                </div>
                                  <div class="form-group">
                                    <label for="phone">Country:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo e($country); ?></label>
                                </div>
                                         <!-- <div class="form-group">
                                            <label for="phone">Gender: <?php echo e($gender); ?>

                                        <?php if($gender=='not' ): ?>       
                                    &nbsp;&nbsp;&nbsp;&nbsp;  <input type="radio" name="gender" value="not" checked> Custom &nbsp;&nbsp;&nbsp;
                                           <input type="radio" name="gender" value="f" checked> Female&nbsp;&nbsp;

                                           <input type="radio" name="gender" value="m"> Male</label>

                                            <?php endif; ?>
                                            <?php if($gender=='f'): ?> 
                                              &nbsp;&nbsp;&nbsp;&nbsp;  <input type="radio" name="gender" value="not"> Custom &nbsp;&nbsp;&nbsp;
                                                               <input type="radio" name="gender" value="f" checked> Female&nbsp;&nbsp;

                                                               <input type="radio" name="gender" value="m"> Male</label>

                                            <?php endif; ?>
                                            <?php if($gender=='m'): ?> 
                                                                &nbsp;&nbsp;&nbsp;&nbsp;  <input type="radio" name="gender" value="not" checked> Custom &nbsp;&nbsp;&nbsp;
                                                                 <input type="radio" name="gender" value="f" > Female&nbsp;&nbsp;

                                                         <input type="radio" name="gender" value="m" checked> Male</label>


                                                        <?php else: ?>

                                                        &nbsp;&nbsp;&nbsp;&nbsp;  <input type="radio" name="gender" value="not" > Custom &nbsp;&nbsp;&nbsp;
                                                                 <input type="radio" name="gender" value="f" > Female&nbsp;&nbsp;

                                                         <input type="radio" name="gender" value="m" checked> Male</label>
                        <?php endif; ?>

                                           
                                        </div> -->
                                <input type="submit" class="font-weight-bold" value="Update">
                            </form>
                         <center>  
                        <a s href="<?php echo e(route('password.show')); ?>" tyle="color: red;">change password</a></center>

                        <br>
                                <?php else: ?>
                        <!-- <h2>Change Password <br></h2> -->
                        <center> <span class="bg-success"> 
                           <?php echo e(session('status')??''); ?></span></center>
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.auth-validation-errors','data' => ['class' => 'mb-4','errors' => $errors]]); ?>
<?php $component->withName('auth-validation-errors'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'mb-4','errors' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                                
                        <form action="<?php echo e(route('password.update')); ?>" method="post" class="pb-5">
                            <?php echo csrf_field(); ?>
                          <!--   <div class=" form-group">
                                <label for="profile-pic">Profile Picture:</label>
                                <input type="file" id="profile-pic" name="pic">
                            </div> -->
                            <div class="form-group">
                                <label for="name">Current password:</label>
                                <input type="password" id="current_password" name="current_password" placeholder="current password" required>
                            </div>
                            <div class="wrap-input100 validate-input" data-validate="Enter password">
                                    <span class="btn-show-pass">
                                        <i class="zmdi zmdi-eye"></i>
                                    </span>
                                    <input class="input100" name="password" type="password" placeholder="Enter Password"
                                        name="password" required autocomplete="current-password">
                                    <span class="focus-input100"></span>

                                </div>
                                <div class="wrap-input100 validate-input">
                                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.input-label','data' => ['for' => 'password_confirmation']]); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['for' => 'password_confirmation']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?><span class="btn-show-pass"><i
                                        class="zmdi zmdi-eye"></i></span>
                                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.text-input','data' => ['id' => 'password_confirmation','class' => 'input100','type' => 'password','name' => 'password_confirmation','required' => true,'placeholder' => 'Re-enter Password']]); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['id' => 'password_confirmation','class' => 'input100','type' => 'password','name' => 'password_confirmation','required' => true,'placeholder' => 'Re-enter Password']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?><span
                                    class="focus-input100"></span>
                                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.input-error','data' => ['messages' => $errors->get('password_confirmation'),'class' => 'mt-2']]); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('password_confirmation')),'class' => 'mt-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                            </div>
                            <br>
                            <input type="submit" value="Change">
                        </form>


                    

                        <!-- second transaction -->
                        <form action="" method="post" class="pb-5">
                            <?php echo csrf_field(); ?>
                          <!--   <div class=" form-group">
                                <label for="profile-pic">Profile Picture:</label>
                                <input type="file" id="profile-pic" name="pic">
                            </div> -->
                            <div class="form-group">
                                <label for="name">Second Transaction password:</label>
                                <input type="password" id="current_password" name="current_password" placeholder="current password" required>
                            </div>
                            <div class="wrap-input100 validate-input" data-validate="Enter password">
                                    <span class="btn-show-pass">
                                        <i class="zmdi zmdi-eye"></i>
                                    </span>
                                    <input class="input100" name="password" type="password" placeholder="Enter Password"
                                        name="password" required autocomplete="current-password">
                                    <span class="focus-input100"></span>

                                </div>
                                <div class="wrap-input100 validate-input">
                                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.input-label','data' => ['for' => 'password_confirmation']]); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['for' => 'password_confirmation']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?><span class="btn-show-pass"><i
                                        class="zmdi zmdi-eye"></i></span>
                                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.text-input','data' => ['id' => 'password_confirmation','class' => 'input100','type' => 'password','name' => 'password_confirmation','required' => true,'placeholder' => 'Re-enter Password']]); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['id' => 'password_confirmation','class' => 'input100','type' => 'password','name' => 'password_confirmation','required' => true,'placeholder' => 'Re-enter Password']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?><span
                                    class="focus-input100"></span>
                                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.input-error','data' => ['messages' => $errors->get('password_confirmation'),'class' => 'mt-2']]); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('password_confirmation')),'class' => 'mt-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                            </div>
                            <br>
                            <input type="submit" value="Change">
                        </form>




                        <?php endif; ?>
                        <style>
                            .container{
                                width: 100%;
                                max-width: 500px;
                                  margin-left: 480px;
                                padding-top: 40px;
                                padding-right: 0px;
                                background-color: #f2f2f2;
                                border: 1px solid #ccc;
                                border-radius: 5px;
                            }
                            .row{
                              
                            }
                            h2 {
                                text-align: center;
                            }

                            .form-group {
                                margin-bottom: 15px;
                            }

                            label {
                                display: block;
                                font-weight: bold;
                            }

                            input[type="text"],
                            input[type="email"],
                            input[type="tel"],
                            input[type="file"],input[type="password"] {
                                width: 100%;
                                padding: 8px;
                                border: 1px solid #ccc;
                                border-radius: 4px;
                            }

                            input[type="submit"] {
                                width: 100%;
                                padding: 10px;
                                background-color: #4CAF50;
                                color: #fff;
                                border: none;
                                border-radius: 4px;
                                cursor: pointer;
                            }

                            input[type="submit"]:hover {
                                background-color: #45a049;
                            }

                            /* Responsive Styles */
                            @media  screen and (max-width: 480px) {
                                .container {
                        /*            padding: 10px;*/
                                    margin-left: 40px;
                                }
                             input[type="submit"] {
                                    font-size: 14px;
                                }
                            }
                             
                                @media  screen and (max-width: 876px) {
                                .container {
                                    padding: 10px;
                                    margin-left: 80px;
                                }
                             input[type="submit"] {
                                    font-size: 14px;
                                }
                            }
                          
                            </style>
                        </div>            
                </div>
            </div>        
    </div>



<?php echo $__env->make('user.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/profile-component.blade.php ENDPATH**/ ?>