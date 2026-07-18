<?php if (isset($component)) { $__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\GuestLayout::class, []); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
    <div class="">
        <div class="register shadow-lg rounded">
            <form class="validate-form" style="margin-top: 0;" action="<?php echo e(route('register')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <h2 class="form_header">Register Form</h2>
                <p class="bg-secondary">
                    <?php echo e($exist ?? ''); ?>

                    <?php echo e($ref ?? ''); ?>

                </p>

                <!-- Error Messages -->
                <?php $__currentLoopData = ['danger', 'warning', 'success', 'info', 'exist']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(Session::has('alert-'.$msg)): ?>
                        <div class="alert alert-<?php echo e($msg); ?>" role="alert">
                            <?php echo e(Session::get('alert-'.$msg)); ?>

                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if(!empty($request)): ?>
                    <div class="alert alert-success">
                        <ul>
                            <?php echo e($request); ?>

                        </ul>
                    </div>
                <?php endif; ?>

                <style>
                    .askHelp {
                        display: none;
                    }
                    #referee_input {
                        display: none;
                    }
                    /* Styles for Password Validation and Confirmation Messages */
                    .password-validation-msg,
                    .password-confirmation-msg {
                        font-size: 18px;
                        color: #e74c3c;
                        margin-top: 5px;
                    }
                    .password-validation-msg.valid,
                    .password-confirmation-msg.valid {
                        color: #2ecc71;
                    }
                    .form-check-label a, .already a {
                        text-decoration: none;
                        color: red;
                    }
                    .already a {
                        text-decoration: none;
                        color: #0000ff;
                    }
                </style>

                <div class="registerForm registerDivs" id="regInputs">
                    <div class="leftSide">
                        <!-- Country -->
                        <div class="validate-input">
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.input-label','data' => ['for' => 'country']]); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['for' => 'country']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <i class="las la-globe-americas input_icon"></i>
                                    </button>
                                </div>
                                <select id="countrySelect" name="country" class="input100" required>
                                    <option value="">Select a country</option>
                                </select>
                            </div>
                        </div>

                        <!-- Full Name -->
                        <div class="validate-input hide_input">
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.input-label','data' => ['for' => 'name']]); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['for' => 'name']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <i class="las la-user input_icon"></i>
                                    </button>
                                </div>
                                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.text-input','data' => ['id' => 'name','class' => 'p-4 input100 formInputs','type' => 'text','name' => 'name','value' => old('name'),'required' => true,'placeholder' => 'Enter Your Full Name']]); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['id' => 'name','class' => 'p-4 input100 formInputs','type' => 'text','name' => 'name','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('name')),'required' => true,'placeholder' => 'Enter Your Full Name']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                            </div>
                            <span class="focus-input100"></span>
                        </div>

                        <!-- Phone -->
                        <div class="validate-input hide_input">
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.input-label','data' => ['for' => 'phone']]); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['for' => 'phone']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <i class="las la-phone-volume input_icon"></i>
                                    </button>
                                </div>
                                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.text-input','data' => ['id' => 'phone','class' => 'p-4 input100 formInputs','type' => 'text','name' => 'phone','value' => old('phone'),'required' => true,'placeholder' => 'Enter Your Phone']]); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['id' => 'phone','class' => 'p-4 input100 formInputs','type' => 'text','name' => 'phone','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('phone')),'required' => true,'placeholder' => 'Enter Your Phone']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                            </div>
                            <span class="focus-input100"></span>
                        </div>

                        <!-- Email -->
                        <div class="validate-input hide_input">
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.input-label','data' => ['for' => 'email']]); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['for' => 'email']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <i class="las la-envelope input_icon"></i>
                                    </button>
                                </div>
                                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.text-input','data' => ['id' => 'email','class' => 'p-4 input100 formInputs','type' => 'text','name' => 'email','value' => old('email'),'required' => true,'placeholder' => 'Enter Your Email']]); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['id' => 'email','class' => 'p-4 input100 formInputs','type' => 'text','name' => 'email','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('email')),'required' => true,'placeholder' => 'Enter Your Email']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                            </div>
                            <span class="focus-input100"></span>
                        </div>
                    </div>

                    <div class="rightSide mt-5">
                        <!-- Referee Number -->
                        <span class="checkbox_ref hide_input" style="margin-top:-30px">
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.input-label','data' => ['for' => 'has_referee_number','class' => 'input-label','id' => 'referre']]); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['for' => 'has_referee_number','class' => 'input-label','id' => 'referre']); ?>Do you have a referee number?&nbsp;&nbsp; <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                            <input type="checkbox" id="has_referee_number" name="has_referee_number" <?php echo e((request()->query('referral') != '' || (request()->query('side') == 'LEFT' || request()->query('side') == 'RIGHT')) ? 'checked':''); ?>>
                            <input type="hidden" name="side" value="<?php echo e(request()->query('side')); ?>">
                        </span>

                        <!-- Referee Number Input (hidden by default) -->
                        <div class="validate-input hide_input" id="referee_input">
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.input-label','data' => ['for' => 'referee']]); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['for' => 'referee']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <i class="lab la-connectdevelop input_icon"></i>
                                    </button>
                                </div>
                                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.text-input','data' => ['id' => 'referee','class' => 'p-4 input100 formInputs','type' => 'text','name' => 'referee_id','value' => ''.e(request()->query('referral') ?? '').'','placeholder' => 'Enter your referee number']]); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['id' => 'referee','class' => 'p-4 input100 formInputs','type' => 'text','name' => 'referee_id','value' => ''.e(request()->query('referral') ?? '').'','placeholder' => 'Enter your referee number']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                            </div>
                            <span class="focus-input100"></span>
                        </div>

                        <!-- Username -->
                        <div class="validate-input hide_input">
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.input-label','data' => ['for' => 'Username']]); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['for' => 'Username']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <i class="las la-user input_icon"></i>
                                    </button>
                                </div>
                                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.text-input','data' => ['id' => 'user','class' => 'p-4 input100 formInputs','type' => 'text','name' => 'user','value' => old('user'),'required' => true,'placeholder' => 'Enter Your Username']]); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['id' => 'user','class' => 'p-4 input100 formInputs','type' => 'text','name' => 'user','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('user')),'required' => true,'placeholder' => 'Enter Your Username']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                            </div>
                            <span class="focus-input100"></span>
                        </div>

                        <!-- Password -->
                        <div class="validate-input hide_input">
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.input-label','data' => ['for' => 'password']]); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['for' => 'password']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <i class="las la-lock input_icon"></i>
                                    </button>
                                </div>
                                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.text-input','data' => ['id' => 'password','class' => 'input100 formInputs','type' => 'password','name' => 'password','required' => true,'placeholder' => 'Enter Your Password']]); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['id' => 'password','class' => 'input100 formInputs','type' => 'password','name' => 'password','required' => true,'placeholder' => 'Enter Your Password']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                                <span class="focus-input100"></span>
                            </div>
                            <div id="password-validation-msg" class="password-validation-msg"></div>
                        </div>

                        <!-- Password Confirmation -->
                        <div class="validate-input hide_input">
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
<?php endif; ?>
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <i class="las la-lock input_icon"></i>
                                    </button>
                                </div>
                                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.text-input','data' => ['id' => 'password_confirmation','class' => 'p-4 input100 formInputs','type' => 'password','name' => 'password_confirmation','required' => true,'placeholder' => 'Re-enter Password']]); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['id' => 'password_confirmation','class' => 'p-4 input100 formInputs','type' => 'password','name' => 'password_confirmation','required' => true,'placeholder' => 'Re-enter Password']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                                <span class="focus-input100"></span>
                            </div>
                            <div id="password-confirmation-msg" class="password-confirmation-msg"></div>
                        </div>
                    </div>

                    <div class="column_reg pinRequired hide_input">
                        <div class="checkList checkValid">
                            <div>
                                <h3 class="checkHead">Password Must Have:</h3>
                                <div class="check" id="check1">
                                    <i class="las la-check-circle"></i><span>One uppercase character.</span>
                                </div>
                                <div class="check" id="check2">
                                    <i class="las la-check-circle"></i><span>One lowercase character.</span>
                                </div>
                                <div class="check" id="check3">
                                    <i class="las la-check-circle"></i><span>Contains numeric character</span>
                                </div>
                                <div class="check" id="check4">
                                    <i class="las la-check-circle"></i><span>Contains specific character</span>
                                </div>
                                <div class="check" id="check5">
                                    <i class="las la-check-circle"></i><span>8 characters minimum</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hide_input">
                    <!-- Terms and Conditions Checkbox -->
                    <div style="margin-top: 30px;">
                        <input type="checkbox" id="tos_field" class="form-check-input" required>
                        <label for="tos_field" class="form-check-label" style="display: inline !important;">
                            <span class="accept">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                I have read and agree to the <a href="<?php echo e(route('tam')); ?>" target="_blank">Terms and Conditions</a> and
                                <a href="<?php echo e(route('policy')); ?>" target="_blank">Privacy Policy</a>
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button class="login100-form-btn" id="logbutton" type="submit">
                                <span class="subtBtn">Register</span>
                                <div class="spinner-border m-5" style="width: 3rem; height: 3rem; display: none;"
                                     role="status" id="loader"></div>
                            </button>
                        </div>
                    </div>

                    <!-- Already Registered Link -->
                    <div class="flex items-center justify-end mt-4 already">
                        <a class="alreadyReg" href="<?php echo e(route('login')); ?>">
                            <?php echo e(__('Already registered? Login')); ?>

                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            // --- 1. Loader Spinner ---
            const logbutton = document.getElementById("logbutton");
            const loader = document.getElementById("loader");
            if (logbutton && loader) {
                logbutton.addEventListener("click", function () {
                    loader.style.display = "block";
                });
            }

            // --- 2. Reliable Embedded Country Dropdown & Phone Prefix ---
            const countrySelectElement = document.getElementById('countrySelect');
            const limitedCountries = ['Chile', 'Israel'];

            // Static embedded list of 250 countries [CountryName, DialCode]
            // Immune to API deprecations, CORS issues, or server downtimes!
            const allCountries = [
                ["Afghanistan", "+93"], ["Albania", "+355"], ["Algeria", "+213"], ["American Samoa", "+1684"],
                ["Andorra", "+376"], ["Angola", "+244"], ["Anguilla", "+1264"], ["Antarctica", ""],
                ["Antigua and Barbuda", "+1268"], ["Argentina", "+54"], ["Armenia", "+374"], ["Aruba", "+297"],
                ["Australia", "+61"], ["Austria", "+43"], ["Azerbaijan", "+994"], ["Bahamas", "+1242"],
                ["Bahrain", "+973"], ["Bangladesh", "+880"], ["Barbados", "+1246"], ["Belarus", "+375"],
                ["Belgium", "+32"], ["Belize", "+501"], ["Benin", "+229"], ["Bermuda", "+1441"],
                ["Bhutan", "+975"], ["Bolivia", "+591"], ["Bosnia and Herzegovina", "+387"], ["Botswana", "+267"],
                ["Bouvet Island", "+47"], ["Brazil", "+55"], ["British Indian Ocean Territory", "+246"], ["British Virgin Islands", "+1284"],
                ["Brunei", "+673"], ["Bulgaria", "+359"], ["Burkina Faso", "+226"], ["Burundi", "+257"],
                ["Cambodia", "+855"], ["Cameroon", "+237"], ["Canada", "+1204"], ["Cape Verde", "+238"],
                ["Caribbean Netherlands", "+599"], ["Cayman Islands", "+1345"], ["Central African Republic", "+236"], ["Chad", "+235"],
                ["Chile", "+56"], ["China", "+86"], ["Christmas Island", "+61"], ["Cocos (Keeling) Islands", "+61"],
                ["Colombia", "+57"], ["Comoros", "+269"], ["Congo", "+242"], ["Cook Islands", "+682"],
                ["Costa Rica", "+506"], ["Croatia", "+385"], ["Cuba", "+53"], ["Curaçao", "+599"],
                ["Cyprus", "+357"], ["Czechia", "+420"], ["DR Congo", "+243"], ["Denmark", "+45"],
                ["Djibouti", "+253"], ["Dominica", "+1767"], ["Dominican Republic", "+1809"], ["Ecuador", "+593"],
                ["Egypt", "+20"], ["El Salvador", "+503"], ["Equatorial Guinea", "+240"], ["Eritrea", "+291"],
                ["Estonia", "+372"], ["Eswatini", "+268"], ["Ethiopia", "+251"], ["Falkland Islands", "+500"],
                ["Faroe Islands", "+298"], ["Fiji", "+679"], ["Finland", "+358"], ["France", "+33"],
                ["French Guiana", "+594"], ["French Polynesia", "+689"], ["French Southern and Antarctic Lands", "+262"], ["Gabon", "+241"],
                ["Gambia", "+220"], ["Georgia", "+995"], ["Germany", "+49"], ["Ghana", "+233"],
                ["Gibraltar", "+350"], ["Greece", "+30"], ["Greenland", "+299"], ["Grenada", "+1473"],
                ["Guadeloupe", "+590"], ["Guam", "+1671"], ["Guatemala", "+502"], ["Guernsey", "+44"],
                ["Guinea", "+224"], ["Guinea-Bissau", "+245"], ["Guyana", "+592"], ["Haiti", "+509"],
                ["Heard Island and McDonald Islands", ""], ["Honduras", "+504"], ["Hong Kong", "+852"], ["Hungary", "+36"],
                ["Iceland", "+354"], ["India", "+91"], ["Indonesia", "+62"], ["Iran", "+98"],
                ["Iraq", "+964"], ["Ireland", "+353"], ["Isle of Man", "+44"], ["Israel", "+972"],
                ["Italy", "+39"], ["Ivory Coast", "+225"], ["Jamaica", "+1876"], ["Japan", "+81"],
                ["Jersey", "+44"], ["Jordan", "+962"], ["Kazakhstan", "+76"], ["Kenya", "+254"],
                ["Kiribati", "+686"], ["Kosovo", "+383"], ["Kuwait", "+965"], ["Kyrgyzstan", "+996"],
                ["Laos", "+856"], ["Latvia", "+371"], ["Lebanon", "+961"], ["Lesotho", "+266"],
                ["Liberia", "+231"], ["Libya", "+218"], ["Liechtenstein", "+423"], ["Lithuania", "+370"],
                ["Luxembourg", "+352"], ["Macau", "+853"], ["Madagascar", "+261"], ["Malawi", "+265"],
                ["Malaysia", "+60"], ["Maldives", "+960"], ["Mali", "+223"], ["Malta", "+356"],
                ["Marshall Islands", "+692"], ["Martinique", "+596"], ["Mauritania", "+222"], ["Mauritius", "+230"],
                ["Mayotte", "+262"], ["Mexico", "+52"], ["Micronesia", "+691"], ["Moldova", "+373"],
                ["Monaco", "+377"], ["Mongolia", "+976"], ["Montenegro", "+382"], ["Montserrat", "+1664"],
                ["Morocco", "+212"], ["Mozambique", "+258"], ["Myanmar", "+95"], ["Namibia", "+264"],
                ["Nauru", "+674"], ["Nepal", "+977"], ["Netherlands", "+31"], ["New Caledonia", "+687"],
                ["New Zealand", "+64"], ["Nicaragua", "+505"], ["Niger", "+227"], ["Nigeria", "+234"],
                ["Niue", "+683"], ["Norfolk Island", "+672"], ["North Korea", "+850"], ["North Macedonia", "+389"],
                ["Northern Mariana Islands", "+1670"], ["Norway", "+47"], ["Oman", "+968"], ["Pakistan", "+92"],
                ["Palau", "+680"], ["Palestine", "+970"], ["Panama", "+507"], ["Papua New Guinea", "+675"],
                ["Paraguay", "+595"], ["Peru", "+51"], ["Philippines", "+63"], ["Pitcairn Islands", "+64"],
                ["Poland", "+48"], ["Portugal", "+351"], ["Puerto Rico", "+1787"], ["Qatar", "+974"],
                ["Romania", "+40"], ["Russia", "+73"], ["Rwanda", "+250"], ["Réunion", "+262"],
                ["Saint Barthélemy", "+590"], ["Saint Helena, Ascension and Tristan da Cunha", "+290"], ["Saint Kitts and Nevis", "+1869"], ["Saint Lucia", "+1758"],
                ["Saint Martin", "+590"], ["Saint Pierre and Miquelon", "+508"], ["Saint Vincent and the Grenadines", "+1784"], ["Samoa", "+685"],
                ["San Marino", "+378"], ["Saudi Arabia", "+966"], ["Senegal", "+221"], ["Serbia", "+381"],
                ["Seychelles", "+248"], ["Sierra Leone", "+232"], ["Singapore", "+65"], ["Sint Maarten", "+1721"],
                ["Slovakia", "+421"], ["Slovenia", "+386"], ["Solomon Islands", "+677"], ["Somalia", "+252"],
                ["South Africa", "+27"], ["South Georgia", "+500"], ["South Korea", "+82"], ["South Sudan", "+211"],
                ["Spain", "+34"], ["Sri Lanka", "+94"], ["Sudan", "+249"], ["Suriname", "+597"],
                ["Svalbard and Jan Mayen", "+4779"], ["Sweden", "+46"], ["Switzerland", "+41"], ["Syria", "+963"],
                ["São Tomé and Príncipe", "+239"], ["Taiwan", "+886"], ["Tajikistan", "+992"], ["Tanzania", "+255"],
                ["Thailand", "+66"], ["Timor-Leste", "+670"], ["Togo", "+228"], ["Tokelau", "+690"],
                ["Tonga", "+676"], ["Trinidad and Tobago", "+1868"], ["Tunisia", "+216"], ["Turkmenistan", "+993"],
                ["Turks and Caicos Islands", "+1649"], ["Tuvalu", "+688"], ["Türkiye", "+90"], ["Uganda", "+256"],
                ["Ukraine", "+380"], ["United Arab Emirates", "+971"], ["United Kingdom", "+44"], ["United States", "+1201"],
                ["United States Minor Outlying Islands", "+268"], ["United States Virgin Islands", "+1340"], ["Uruguay", "+598"], ["Uzbekistan", "+998"],
                ["Vanuatu", "+678"], ["Vatican City", "+3906698"], ["Venezuela", "+58"], ["Vietnam", "+84"],
                ["Wallis and Futuna", "+681"], ["Western Sahara", "+2125288"], ["Yemen", "+967"], ["Zambia", "+260"],
                ["Zimbabwe", "+263"], ["Åland Islands", "+35818"]
            ];

            if (countrySelectElement) {
                const filteredCountries = allCountries.filter(item => !limitedCountries.includes(item[0]));

                let optionsHtml = '<option value="">Select a country</option>';
                filteredCountries.forEach(item => {
                    optionsHtml += `<option value="${item[0]}">${item[0]}</option>`;
                });
                countrySelectElement.innerHTML = optionsHtml;

                countrySelectElement.addEventListener('change', function () {
                    const selected = allCountries.find(item => item[0] === this.value);
                    const countryCodeDisplay = document.getElementById('phone');
                    if (countryCodeDisplay && selected) {
                        countryCodeDisplay.value = selected[1] || '';
                    }
                });
            }

            // --- 3. Referee Number Toggle ---
            const hasRefereeNumberCheckbox = document.getElementById("has_referee_number");
            const refereeInput = document.getElementById("referee_input");

            if (hasRefereeNumberCheckbox && refereeInput) {
                const toggleRefereeInput = () => {
                    refereeInput.style.display = hasRefereeNumberCheckbox.checked ? "block" : "none";
                };
                toggleRefereeInput();
                hasRefereeNumberCheckbox.addEventListener("change", toggleRefereeInput);
            }

            // --- 4. Password Validation ---
            const passwordInput = document.getElementById("password");
            const passwordConfirmationInput = document.getElementById("password_confirmation");
            const passwordValidationMsg = document.getElementById("password-validation-msg");
            const passwordConfirmationMsg = document.getElementById("password-confirmation-msg");
            const checkList = document.querySelector('.checkList');
            const regInputs = document.querySelector('#regInputs');
            const pinRequired = document.querySelector('.pinRequired');

            if (passwordInput && passwordConfirmationInput) {
                const validatePasswords = () => {
                    const password = passwordInput.value;
                    const passwordConfirmation = passwordConfirmationInput.value;

                    const uppercaseRegex = /[A-Z]/;
                    const lowercaseRegex = /[a-z]/;
                    const numberRegex = /[0-9]/;
                    const specialCharacterRegex = /[!@#$%^&*()_+{}\[\]:;<>,.?~\-=/\|]/;
                    const minLength = 8;

                    let isValidPassword = true;

                    if (!uppercaseRegex.test(password)) {
                        if (document.getElementById("check1")) document.getElementById("check1").style.color = "red";
                        isValidPassword = false;
                    } else {
                        if (document.getElementById("check1")) document.getElementById("check1").style.color = "green";
                    }

                    if (!lowercaseRegex.test(password)) {
                        if (document.getElementById("check2")) document.getElementById("check2").style.color = "red";
                        isValidPassword = false;
                    } else {
                        if (document.getElementById("check2")) document.getElementById("check2").style.color = "green";
                    }

                    if (!numberRegex.test(password)) {
                        if (document.getElementById("check3")) document.getElementById("check3").style.color = "red";
                        isValidPassword = false;
                    } else {
                        if (document.getElementById("check3")) document.getElementById("check3").style.color = "green";
                    }

                    if (!specialCharacterRegex.test(password)) {
                        if (document.getElementById("check4")) document.getElementById("check4").style.color = "red";
                        isValidPassword = false;
                    } else {
                        if (document.getElementById("check4")) document.getElementById("check4").style.color = "green";
                    }

                    if (password.length < minLength) {
                        if (document.getElementById("check5")) document.getElementById("check5").style.color = "red";
                        isValidPassword = false;
                    } else {
                        if (document.getElementById("check5")) document.getElementById("check5").style.color = "green";
                    }

                    if (isValidPassword) {
                        if (regInputs) regInputs.classList.remove('onOpen');
                        if (checkList) checkList.classList.add("checkValid");
                        if (pinRequired) pinRequired.style.display = "none";
                        if (passwordValidationMsg) {
                            passwordValidationMsg.innerHTML = "Password is valid.";
                            passwordValidationMsg.classList.add("valid");
                        }
                    } else {
                        if (regInputs) regInputs.classList.add('onOpen');
                        if (checkList) checkList.classList.remove("checkValid");
                        if (pinRequired) pinRequired.style.display = "block";
                        if (passwordValidationMsg) {
                            passwordValidationMsg.innerHTML = "";
                            passwordValidationMsg.classList.remove("valid");
                        }
                    }

                    if (password && password === passwordConfirmation) {
                        if (passwordConfirmationMsg) {
                            passwordConfirmationMsg.innerHTML = "Passwords match.";
                            passwordConfirmationMsg.classList.add("valid");
                        }
                    } else if (passwordConfirmation) {
                        if (passwordConfirmationMsg) {
                            passwordConfirmationMsg.innerHTML = "Passwords do not match.";
                            passwordConfirmationMsg.classList.remove("valid");
                        }
                    } else {
                        if (passwordConfirmationMsg) {
                            passwordConfirmationMsg.innerHTML = "";
                            passwordConfirmationMsg.classList.remove("valid");
                        }
                    }
                };

                passwordInput.addEventListener("input", validatePasswords);
                passwordConfirmationInput.addEventListener("input", validatePasswords);
            }
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015)): ?>
<?php $component = $__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015; ?>
<?php unset($__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015); ?>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/auth/register.blade.php ENDPATH**/ ?>