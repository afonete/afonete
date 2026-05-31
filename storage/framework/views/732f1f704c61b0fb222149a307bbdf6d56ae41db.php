

    <!-- Session Status -->
    <auth-session-status class="mb-4" :status="session('status')" />

    <!-- Validation Errors -->
    <auth-validation-errors class="mb-4" :errors="$errors" />


<?php if (isset($component)) { $__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\GuestLayout::class, []); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['style' => 'height: 100%;']); ?>
    <div style="background:#f2f2f2; height: 10%; ">    <span class="login100-form-title" >
        <?php if(session('status')): ?>
        <!-- <?php echo e(session('status')??''); ?> -->
       <span style="font-size: 25px; width: 700px;color:#e91e63"> We have e-mailed you 
        a password reset link!<br>
check your email including spam  </span>  
 <br><br><br><br>
        <?php else: ?>
        <span style="font: 32px raleway,sans-serif;">
       <?php echo e(('RESET YOUR PASSWORD')); ?><br><br><br>
   </span><span style="font: 15px raleway,sans-serif;;color:#e91e63">
       <?php echo e(('A password reset link will be sent to your email.')); ?>


       <?php endif; ?>
    </span></div>

    <b>

        <div class="container-login100">
            <div class="wrap-login100 col-md-4" style="margin-top: -320px; margin-bottom: 0px;">

                <form class=" validate-form" method="POST" action="<?php echo e(route('password.email')); ?>">
                    <?php echo csrf_field(); ?>

                   
                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <span class="btn-show-pass">
                            <i class="zmdi zmdi-eye"></i>
                        </span>
                        <input class="input100" name="email" type="email" placeholder="Enter Your email"
                            required autocomplete="current-email">
                        <span class="focus-input100"></span>

                    </div>
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.input-error','data' => ['messages' => $errors->get('email'),'class' => 'mt-2 bg-danger','style' => 'width:300px']]); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('email')),'class' => 'mt-2 bg-danger','style' => 'width:300px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                 
                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button class="login100-form-btn" type="submit" id="logbutton">
                                Request password link
                                
                            </button>
                        </div>
                            <br>

                    <a style="color: #e91e63;font-size: 20" href="<?php echo e(route('login')); ?>">Back to Login</a>

                    </div>

                    <br>

                    
                    
                      
                      </div>
                      
                </form>
            </div>
        </div>
        </div>

        <script>
        document.getElementById("logbutton").addEventListener("click", function() {
            document.getElementById("loader").style.display = "block";
        });
        </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015)): ?>
<?php $component = $__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015; ?>
<?php unset($__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015); ?>
<?php endif; ?>
<?php /**PATH /home/cwkwkueb/test.focoin.eu/resources/views/auth/forgot-password.blade.php ENDPATH**/ ?>