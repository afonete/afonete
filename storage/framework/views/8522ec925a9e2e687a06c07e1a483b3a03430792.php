<?php

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Wallet;
$user = Auth::user();
$name = $user->name;
$uname = $user->user;
$email=$user->email;
$phone=$user->phone;
$gender=$user->gender;
$country=$user->country;
$package=$user->has_paid_package;
$wallet=Wallet::where('user',$uname)->first();
$address=$wallet->wallet?$wallet->wallet:'';
$addressi='Enter wallet address. ex: 2N3cD7vQxBqmHFVFrgK2o7HonHnVoFxxDVB';
?>

<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="content-wrapper py-5">

   <div class="tabs tab_links" >
        <span class="links_tabs d-flex ">
          <a href="<?php echo e(route('profile.edit')); ?>" class="fomoLink" id="tabs"> <i class="fa-regular fa-address-card"></i> My profile</a>
          <a href="<?php echo e(route('password.show')); ?>" class="fomoLink"><i class="fa-solid fa-lock"></i> Password</a>
          <a href="<?php echo e(route('wallet')); ?>" class="fomoLink"><i class="fa-solid fa-wallet"></i> wallet address</a>
        </span>
      </div>
    <br>
    <div class="prof-update pb-4">
        <h2 class="upd-title">Wallet Address
            <span class="bg-success"> 
                <?php echo e($success??''); ?>

            </span>
             <span class="bg-danger"> 
               <?php echo e($failed??''); ?></span>

            </h2>
            <div class="row upd-row d-flex justify-content-center">  
                <div class="col-lg-8">
                        <div class="container-fluid">
                            <?php if(!$show): ?>
	                            <form action="<?php echo e(route('user.wallet')); ?>" method="post" class="shadow rounded">
	                                <?php echo csrf_field(); ?>
	                            	<div class="p-4">
	                            		<label>Edit Wallet Address <i>N.B: Make sure the address is correct</i></label>
	                            		<input type="text" name="wallet" class="form-control my-2" value="<?php echo e($address); ?>" placeholder="<?php echo e($addressi); ?>">

	                            		<button type="submit" class="btn btn-success w-100 my-2" >save addrees</button>
	                            	</div>
	                            </form>
	                         <center>  

                    
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
</div><?php /**PATH /home/blackjay/Downloads/test.focoin.eu/resources/views/user/wallet.blade.php ENDPATH**/ ?>