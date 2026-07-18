<?php

use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

$user = Auth::user();
$name = $user->name;
$userr=$user->user;
$email = $user->email;
$package = $user->has_paid_package;
$package1 = $user->has_paid_package;

switch ($package)
{
case 'ft':
    $package='FT';
    break;
    case 'fc1':
    $package='FC $100';
    break;
    case 'fc2':
    $package='FC $200';
    break;
}
// $ref_code = $user->activation;
function generateReferralId($userId) {
// Convert the user ID to base-36
$base36UserId = base_convert($userId, 10, 36);

// Generate a 4-character unique alphanumeric code
$uniqueCode = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4);

// Combine components to form the referral ID
$referralId = 'REF-' . $base36UserId . '-' . $uniqueCode;

return $referralId;
}



$ref_code = $user->activation;

//   generateReferralId($user->id);

$jsonString = file_get_contents(resource_path('dummydata/task.json'));
$data = json_decode($jsonString);

$totalSum = 0;
$deposit = db::SELECT("SELECT user, SUM(deposit) as deposit FROM balances WHERE user = :user GROUP BY user", ['user' => $userr]);


   $events = Event::where('user', $userr)
->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END") // Order by 'pending' first
->take(3) // Retrieve only the first 3 results
->get();

?>

<div class="wrapper">
 <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="content-wrapper">
    <div class="container-fluid">
 <div class="px-3 mb-2">
          <div style="padding: 10px 0;height:100%;">
    <div>

         <?php if(session('message')): ?>
         <p class="btn btn-success d-flex justify-content-center" >
             <?php echo e(session('message')); ?>

         </p>
         <?php endif; ?>


     </div>
 <style>
 .cc .col {
     background-color: #fff5aa;
     border-radius:12px;
     /* Add desired background color */
     border: 1px solid #ccc;
     /* Add desired border style */
     display: flex;
     width: 12px;
     border: 4px solid white;
     height: 100px;
     text-align: center;
     left: 40px;
     padding-top: 30px;
     padding-left: 25px;
 }

 #founder {
     background-color: lightgreen;
     border-raius: 1px solid #ccc;
     display: flex;
     width: 80px;
     height: 100px;
     text-align: center;
     left: 80px;
     padding-top: 30px;
     padding-left: 45px;
 }
 .coming .info-box {
     display: flex;
     width: 100%;
     height: 100%;
     justify-content: center;
     text-align: center;
     float: right;
     padding-top: 5px;
 }


 @media (max-width: 768px),
 @media  screen and (min-width: 768px) {
     .cc .col {
         /*  border: 1px solid #ccc; /* Add desired border style */
         */ background-color: red;
         width: 100%;
         flex: 0 0 60%;
         border: 4px solid white;
          height: 100px;
         text-align: center;
         left: 40px;
         padding-top: 30px;
         padding-left: 25px;
     }

     #hide {
         display: none;
     }
 }


 .coming {
     display: flex;
     width: 100%;
     height: 120px;
     justify-content: center;
 }
 .containers{max-width:40%; margin-left:auto; position: relative;}
img{ max-width:100%;}
.inbox_people {
/* background: #f8f8f8 none repeat scroll 0 0;
float: right;
overflow: hidden;*/

border-right:1px solid #c4c4c4;
}
.inbox_msg {
border: 1px solid #c4c4c4;
clear: both;
overflow: hidden;
}
.top_spac{ margin: 20px 0 0;}


.recent_heading {float: left; width:40%;}
.srch_bar {
display: inline-block;
text-align: right;
width: 60%;
}
.headind_srch{ padding:10px 29px 10px 20px; overflow:hidden; border-bottom:1px solid #c4c4c4;}

.recent_heading h4 {
color: #05728f;
font-size: 21px;
margin: auto;
}
.srch_bar input{ border:1px solid #cdcdcd; border-width:0 0 1px 0; width:80%; padding:2px 0 4px 6px; background:none;}
.srch_bar .input-group-addon button {
background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
padding: 0;
color: #707070;
font-size: 18px;
}
.srch_bar .input-group-addon { margin: 0 0 0 -27px;}

.chat_ib h5{ font-size:15px; color:#464646; margin:0 0 8px 0;}
.chat_ib h5 span{ font-size:13px; float:right;}
.chat_ib p{ font-size:14px; color:#989898; margin:auto}
.chat_img {
float: left;
width: 11%;
}
.chat_ib {
float: left;
padding: 0 0 0 15px;
width: 88%;
}

.chat_people{ overflow:hidden; clear:both;}
.chat_list {
border-bottom: 1px solid #c4c4c4;
margin: 0;
padding: 18px 16px 10px;
}
.inbox_chat {
 height: 550px;
overflow-y: scroll;
}
/* Define scrollbar styles */
.inbox_chat::-webkit-scrollbar,#chat::-webkit-scrollbar {
width: 12px; /* Width of the scrollbar */
}

/* Track */
.inbox_chat::-webkit-scrollbar-track,#chat::-webkit-scrollbar-track {
background: #f1f1f1; /* Color of the track */
border-radius: 10px;
}

/* Handle */
.inbox_chat::-webkit-scrollbar-thumb,#chat::-webkit-scrollbar-thumb {
background: #888; /* Color of the scrollbar handle */
border-radius: 10px;
}

/* Handle on hover */
.inbox_chat::-webkit-scrollbar-thumb:hover,#chat::-webkit-scrollbar-thumb:hove {
background: #555; /* Darker color when mouse hovers */
}

.active_chat{ background:#ebebeb;}
.messaging{
position: absolute;
top: -200px;
z-index: 2000;
background-color: #fff;
}
.incoming_msg_img {
display: inline-block;
width: 6%;
}
.received_msg {
display: inline-block;
padding: 0 0 0 10px;
vertical-align: top;
width: 92%;
}
.received_withd_msg p {
background: #ebebeb none repeat scroll 0 0;
border-radius: 3px;
color: #646464;
font-size: 14px;
margin: 0;
padding: 5px 10px 5px 12px;
width: 100%;
}
.time_date {
color: #747474;
display: block;
font-size: 12px;
margin: 8px 0 0;
}
.received_withd_msg { width: 57%;}
.mesgs {
float: left;
padding: 30px 15px 0 25px;
width: 60%;
}

.sent_msg p {
background: #05728f none repeat scroll 0 0;
border-radius: 3px;
font-size: 14px;
margin: 0; color:#fff;
padding: 5px 10px 5px 12px;
width:100%;
}
.outgoing_msg{ overflow:hidden; margin:26px 0 26px;}
.sent_msg {
float: right;
width: 46%;
}
.input_msg_write input {
background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
border: medium none;
color: #4c4c4c;
font-size: 15px;
min-height: 48px;
width: 100%;
}

.type_msg {border-top: 1px solid #c4c4c4;position: relative;}
.msg_send_btn {
background: #05728f none repeat scroll 0 0;
border: medium none;
border-radius: 50%;
color: #fff;
cursor: pointer;
font-size: 17px;
height: 33px;
position: absolute;
right: 0;
top: 11px;
width: 33px;
}
/* .messaging { padding: 0 0 50px 0;} */
.msg_history {
height: 516px;
overflow-y: auto;
}
.hide{
display: none
}

.mycard{
background-color:white;
/* width:200px; */
}
.mycard2{
background-color:yellow;
/* width:200px; */
}
@media (max-width: 767.98px) {
        .modal-dialog {
            max-width: 99%; /* Adjust this value as needed */
        }
    }
    .scrollable-text {
             /* Set the desired height */
            overflow-y: scroll;
            padding: 10px;
        }
 </style>


    






                <div class="">
                    <?php
                        function abbreviateNumber($number) {
        if ($number < 1000) {
            return $number;
        } elseif ($number < 1000000) {
            return round($number / 1000, 1) . 'k';
        } elseif ($number < 1000000000) {
            return round($number / 1000000, 1) . 'M';
        } elseif ($number < 1000000000000) {
            return round($number / 1000000000, 1) . 'B';
        } else {
            return round($number / 1000000000000, 1) . 'T';
        }
    }


    ?>

   <div class="container">
    <div class="row  pb-3  " style="margin:-10px">
        <div class="col-1"></div>
        <div class="col-10">

          <div class="container">

            <div class="d-flex justify-content-between">
                <div class="py-2 my-2">
                    <h4>VENTURE PACKAGES</h4>
                  </div>
                  <div class="py-2 my-2 bg-white px-3 rounded">
                    <h4>BALANCE: <strong class="px-2">$ <?php echo e($balance); ?></strong></h4>

                  </div>
            </div>

            <div class="mb-2">

                <div class="scrollable-text bg-white p-3 rounded">
                   <h5 class="text-center">UNIQUE VENTURE PORTFOLIO</h5>
                    <!--<span style="font-size: 13px; color: black;" >
                        You can become a Royal investor (UVP) in our company and earn up to 300% daily for 600 days and your share will be converted to liquid cash for
                        withdrawal. Also, through our affiliate program our members can earn with COMPANY. Only Royal investors (UVP) get paid daily venture pool of
                        whatever you invested for 600 working days (Sat & Sun not inclusive) of which daily income of pool capital 80% and 20% gas fees and in pool
                        capital 20% in cash to an Open Account which you can withdraw from every week on Mondays and 80% in Escrow to trading their buy token and 80%
                        Trade Account and used to buy more token.
                        During the pre-launch, you will get the unique opportunity to build a global business in a way that has never been possible before!! As a Royal
                        investor or a pioneer, you’ll be able to take part of the company’s success and a greater part of all future income. A Royal investor is who
                        wants to put money in this stage and you don't need to refer anybody for earn. In during this phase prelaunch we are targeting specification
                        number of people needed on this first phase. Contract 200% If you don’t do anything and recruit anybody you double your money and get more
                        through every total company’s revenue. This package available only pro launch but you can buy one or more.
                    </span>-->

                    <span style="font-size: 13px; color: black;">
                        You can  become a Royal investor  if you buy (UVP license ) in our company and earn up to 300% daily
                        income and your share will be converted to liquid cash for withdrawal Also, through our affiliate
                        program our members can earn with COMPANY . only  Royal investor has (UVP License )  You get
                        paid daily venture pool  of whatever you invested any duration working days (Sat & Sun not inclusive)
                         of which daily income based of pool capital  80%  and charges liquidity pool fees   20% on license and In
                          pool capital get   25%  in cashout
                        which you can withdraw from every week on Mondays and 75%  going a trading account their used
                        to buy more token to  Escrow.
                        During the pre-launch, you will get the unique opportunity to build a global business in
                        a way that never been possible before!! As
                        Royal investor or  a pioneer, you’ll be able to take part of the company’s success and a
                        greater part of all future income. A  Royal investor  you don't need to refer anybody for earn In during
                        this phase prelaunch and  we are targeting specification number of people needed  on this first phase.
                        If you don’t do anything and recruit any body and  double  your money  join now get more through every total
                        company’s revenue . This
                         package available only pro launch but you can buy one or more
                    </span>
                </div>
        </div>

             <div class="row">
                <?php $__currentLoopData = $Adventures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venture): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-3 my-2">
                            <div class="mycard rounded border border-dark">
                                <div class="m-1">
                                    <p class="text-danger text-center" style="fon-size:10px;"><?php echo e($venture->name); ?></p>
                                    <div class="d-flex justify-content-center">
                                    <img src="<?php echo e(asset('image/ai-package.png')); ?>" style="width:40px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.15);">
                                    </div>
                                </div>
                                <div class="text-center" style="background-color:black;">
                                    <span style="font-size: 11px;  margin-bottom: -15%; " class="text-danger" ><?php echo e($venture->plan); ?> <?php echo e($venture->duration); ?> days</span> <br>
                                    <span style="font-size: 11px;" class="text-white">
                                        <?php if(!empty($venture->percentage_range)): ?>
                                            <?php echo e($venture->percentage_range); ?>

                                        <?php else: ?>
                                            <?php echo e($venture->percentage); ?> %
                                        <?php endif; ?>
                                    </span>
                                </div>


                                    <div class="mt-1 px-1 text-center ">

                            <span  style="font-size: 10px; margin-bottom:-22px; font-weight:bold;"
                            class=" border-bottom border-2 border-bottom-dashed">Min $<?php echo e(abbreviateNumber($venture->min_amount)); ?>

                            -Max$<?php echo e(abbreviateNumber($venture->max_amount)); ?></span> <br>
                            <span  style="font-size: 10px; margin-to:-22px; font-weight:bold;"
                            class=" border-bottom border-2 border-bottom-dashed">Total return:<?php echo e($venture->total_return); ?>%</span>

                            <div class="my-1 text-center">
                                <form action="<?php echo e(route('ventures')); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('POST'); ?>
                                    <input type="hidden" name="venture" value="<?php echo e($venture->id); ?>"/>
                                    <input type="hidden" name="payment_method" value="FROM_DEPOSITS">
                            <input type="number" name="amount_invest"
                            placeholder=" Enter Amount ($<?php echo e(abbreviateNumber($venture->min_amount)); ?> - $<?php echo e(abbreviateNumber($venture->max_amount)); ?>)"

                                    class="form-control-smaller text-sm  my-1 border p-1  w-100 mx-auto rounded"
                                    min="<?php echo e($venture->min_amount); ?>"
                                    max="<?php echo e($venture->max_amount); ?>"
                                    required/>
                                    <button class="btn btn-dark  px-2"
                                    style="font-size: 10px; background-color:black;" type="submit">
                                        INVEST NOW
                                    </button>
                                </form>
                            </div>

                            </div>

                            </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div class="col-12">
                      <div class="py-2 my-2">
                        <h4>FC PACKAGES</h4>
                      </div>
                    <div class="row">
                        <?php $__currentLoopData = $fc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <form action="<?php echo e(route('ventures')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <center class="bg-danger">
                                            <h3><?php echo e($f->name); ?></h3>
                                        </center>
                                        <center>
                                            FC VIP <?php echo e($f->price); ?>$
                                        </center>
                                        <input type="hidden" name="venture"  value="FC"/>
                                        <input type="hidden" name="package"  value="<?php echo e($f->id); ?>"/>
                                        <input type="hidden" name="routes"   value="<?php echo e($f->name); ?>"/>
                                        <input type="hidden" name="payment_method" value="FROM_DEPOSITS">
                                        <input type="hidden" name="amount_invest" value="<?php echo e($f->price); ?>"/>
                                        <div class="text-center ">
                                            <button class="btn btn-primary" value="100">
                                                BUY NOW
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                        <!-- <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <center class="bg-danger">
                                        <h3>FC </h3>
                                    </center>
                                    <center>
                                        FC VIP 200$

                                    </center>
                                    <div class="text-center mt-3">
                                        <a href="<?php echo e(route('fc2')); ?>"><button class="btn btn-primary"  value="200" > BUY NOW</button></a>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
             </div>
          </div>
        </div>
        <div class="col-1"></div>


   </div>


    </div>
</div>











 </div>


</div>

</div>

<script src="<?php echo e(asset('assets/a/plugins/jquery/jquery.min.js')); ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo e(asset('assets/a/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/plugins/sparklines/sparkline.js')); ?>"></script>

<!-- AdminLTE App -->

<script src="<?php echo e(asset('assets/a/plugins/jquery-ui/jquery-ui.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/dist/js/adminlte.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/dist/js/adminlte.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/plugins/chart.js/Chart.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/dist/js/pages/dashboard2.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/dist/js/tree.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/plugins/chart.js/Chart.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/plugins/jquery-knob/jquery.knob.min.js')); ?>"></script>

<script src="<?php echo e(asset('assets/a/dist/js/pages/dashboard.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/plugins/summernote/summernote-bs4.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/plugins/daterangepicker/daterangepicker.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/plugins/moment/moment.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/dist/js/pages/dashboard3.js')); ?>"></script>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/buypackages.blade.php ENDPATH**/ ?>