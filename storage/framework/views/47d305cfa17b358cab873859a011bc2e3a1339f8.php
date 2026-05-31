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
  $baseUrl = url('/');
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

    <script>
        window.Promise ||
          document.write(
            '<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"><\/script>'
          )
        window.Promise ||
          document.write(
            '<script src="https://cdn.jsdelivr.net/npm/eligrey-classlist-js-polyfill@1.2.20171210/classList.min.js"><\/script>'
          )
        window.Promise ||
          document.write(
            '<script src="https://cdn.jsdelivr.net/npm/findindex_polyfill_mdn"><\/script>'
          )
      </script>


      <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

      <script>
        // Replace Math.random() with a pseudo-random number generator to get reproducible results in e2e tests
        // Based on https://gist.github.com/blixt/f17b47c62508be59987b
        var _seed = 42;
        Math.random = function() {
          _seed = _seed * 16807 % 2147483647;
          return (_seed - 1) / 2147483646;
        };


 function startCountdown(targetDate) {
    const countdownElement = document.getElementById('countdown');

    function updateCountdown() {
        const now = new Date().getTime();
        const distance = new Date(targetDate).getTime() - now;

        if (distance < 0) {
            countdownElement.innerHTML = "Package expired!";
            clearInterval(interval);
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        countdownElement.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
    }

    const interval = setInterval(updateCountdown, 1000);
}
</script>
    <div class="content-wrapper">
        <div class="container-fluid">
     <div class="row mb-2">
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
     </style>
     <script>

    const handleclose = ()=>{
      const chat = document.querySelector("#chat")

        if(chat.classList.contains("hide")){
            chat.classList.remove("hide")
        }
        else{
            chat.classList.add("hide")
        }
        console.log("oko")
    }

        const handleOpen = ()=>{
  const chat = document.querySelector("#chat")
    if(chat.classList.contains("hide")){
        chat.classList.remove("hide")
        window.location.href ="#header-box"
    }
    else{
        chat.classList.add("hide")
    }

}
     </script>
     <!-- Content Wrapper. Contains page content -->




        <marquee behavior="" direction="">
            <i style="color: brown">
          Welcome To Millionaire Site, We're Here for you , Money is always eager ,
          now you can make money online , Better choice  solutions for your future finance And don't Hesitate to Contact us

                 </i>
                 </marquee>



        <div>
            
            <?php

                $amount = $mypackage->amount;

                // $fcoin = $amount * 0.0025; // fcoin
                // $percent20 = ($amount * 20/100);
                // $percent80 = ($amount * 80/100);
                // $gasFees = $percent20; // gas fees
                // $poolCapital =  $percent80; // poolcapital
                // $twoPercentageOfPoolCapital = $poolCapital * 2/100;
                // $dailyIncome =  $twoPercentageOfPoolCapital;//dairy income
                // // 25% of 2% of poolcapital

                // $t5percentageOfDailyIncome = $dailyIncome * 25/100;
                // $cashout = $t5percentageOfDailyIncome;//cashout
                // $t75PercentOfDailyIncome = $dailyIncome * 75/100;
                // $shooping =  $t75PercentOfDailyIncome;//shooping
                // $createdDate = $mypackage->created_at;
                // $today = Carbon::now();
                // // Calculate the difference in days
                // $daysGone = isset($createdDate) ? $createdDate->diffInDays($today) : 0;
                $earnings = $user->earnings->sum("amount");
                // dd($ranks);
// dd($user);
                //   dd($user->earnings->sum("amount"));

        //         $data = [
        //             "amount"=>$amount,
        //             "fcoin"=>$fcoin,
        //             "gasfees"=>$gasFees,
        //             "pool"=>$poolCapital,
        //             "dailyIncome"=>$dailyIncome,
        //             "cashout"=>$cashout,
        //             "shooping"=>$shooping,
        //             "daysgone"=>$daysGone
        // ];
        // print_r($data);
// dd($user->investments)
    //  dd($user);

            ?>

                <!-- <div x-data="{ open: false }">
            <button @click="open = true" class="btn btn-primary">Open Modal</button>

            <div x-show="open" class="modal fade show d-block" style="background: rgba(0, 0, 0, 0.5);" x-transition>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Modal Title</h5>
                            <button type="button" class="btn-close" @click="open = false"></button>
                        </div>
                        <div class="modal-body">
                            <p>This is a simple modal using Alpine.js and Bootstrap.</p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" @click="open = false">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
       
                     <div class="ad-main-box mt-3 mb-3">
                     
                 <div class="ad-sub-box1 ">

                    <div class="sub-box-1">
                        <div class="ad-box bg-info mx-1" id="not">
                            <a class="nav-icon fas fa-window-restore" href="#"><br>Account status<br/>
                                <b>
                                    

                                    <?php if($user->has_free_package == 'no' && $user->has_paid_package == 'ft'): ?>
                                        FT
                                    <?php elseif($user->has_free_package == 'yes'): ?>
                                         FREE
                                    <?php else: ?>

                                    <?php echo e($user->has_paid_package); ?>

                                    <?php endif; ?>

                                </b>
                            </a>
                        </div>



                            <div class="ad-box bg-info" id="not"> <a class="nav-icon fas fa-window-restore" href="#">
                                <br>FOCOIN<br />
                                <b>
                                    <?php echo e($fcoin); ?>

                                </b>
                            </a>
                        </div>



                    </div>


                 </div>

                 <div class="sub-box-1">

                        <div class="ad-box bg-info mx-2" id="not">
                            <a class="nav-icon fas fa-window-restore" href="#">
                                <br>FOMO EARN<br />
                                <b><?php echo e($gasFees); ?></b>

                           </a>
                        </div>
                        <?php if($user->has_free_package == 'no'): ?>
                        <div class="ad-box bg-dark mx-2" id="not">
                            <a class="nav-icon fas fa-window-restore" href="#">
                                <br>Portfolio<br />
                                <b><?php echo e($portfolio); ?></b>

                           </a>
                        </div>
                        <?php endif; ?>


                        <?php if($user->has_free_package == 'yes'): ?>
                        <div class="ad-box bg-success">
                            <a href="<?php echo e(route('user.dashboard.activate')); ?>" class="nav-icon fas fa-window-restore" >
                                <br>Activate <br> package</a>
                            </div> <?php endif; ?>
                        <?php if($package1=='fc1' || $package1=='fc2'): ?>
                        <div class="ad-box bg-success">
                            <a href="#" class="nav-icon fas fa-window-restore" >
                            <br>Package  <br> Activated</a></div>
                        <?php endif; ?>


                 </div>




                <div class="sub-box-1 ">
                    <div class="ad-box bg-primary mx-3">
                        <a class="nav-icon fas fa-wallet" href="<?php echo e(route('mypayments')); ?>">
                            <p class="py-1 text-white">Cash&deposits </p>
                            <?php echo e($deposits); ?>

                     </a>
                    </div>

                        <div class="ad-box bg-danger ">
                            <div class="d-block">
                                    <i class="fas fa-bookmark"></i>
                                    <a class="nav-icon  fw-bold text-white d-block" href="#">
                                        Cash out
                                    </a>
                                    <p class="text-white "> <?php echo e($cashout); ?> </p>

                            </div>
                        </div>

                </div>

                <?php if($user->has_free_package == 'no'): ?>
                    <div  class="sub-box-1 px-2">
                        <div class="ad-box bg-warning mr-2 ml-1"><a class="nav-icon fas fa-money" href="#"> <br>Daily Income <br /> <?php echo e($dailyIncome); ?> </a></div>
                        <div class="ad-box bg-info mr-2 ml-1"><a class="nav-icon fas fa-gift" href='#'>
                            <span>Trading Voucher </span>
                            <span class="py-2"><?php echo e($shooping); ?></span>
                                <?php if($show_timer): ?>
                                <span id="countdown" class="bg-dark badge badge-dark"></span>
                                <?php endif; ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <script>
                    const targetDate = "<?php echo e($expirationDate); ?>";
                    startCountdown(targetDate);
                </script>

                <div class="ad-sub-box2">
              <?php if($user->has_free_package != 'yes'): ?>
                <div class="sub-box-1">
                    <div class="ad-box bg-success col"><a class="nav-icon fas fa-gift" href='#'>
                        <br>Reserved_ads<br>100000</a>
                    </div>
                    <br>
                </div>
              <?php endif; ?>


             </div>


         </div>
<?php

    $reserved = db::SELECT("SELECT user, SUM(reserved_token) as token FROM balances WHERE user = :user GROUP BY user", ['user' => $user->id]);

?>




        <div class="ad-main-box mt-3 mb-3">
             <!-- <div class="" style="background-color: white;" id="hide"></div> -->
             <div class="ad-sub-box1 ">

                    <div class="sub-box-1">
                         <div class=" ad-box bg-success "><a class="nav-icon fas fa-gear" href="<?php echo e(route('user.package.history')); ?>"><br>My package</a></div>
                        <div class="ad-box bg-primary"><a class="nav-icon fas fa-user" href="#"><br>My invetees</a></div>
                    </div>
                    <div class="sub-box-1">
                        <div class="ad-box bg-danger"><a class="nav-icon fas fa-users" href="#"><br>My Team</a></div>
                        <div class="ad-box bg-warning "><a class="nav-icon fas fa-gift" href="#"><br>Coin <br> coming soon</a></div>
                    </div>

             </div>

             <div class="ad-sub-box2">
                <div  class="sub-box-1">
                    <div class="ad-box bg-info mr-2 ml-1"><a class="nav-icon fas fa-gift" href="#"><br>Total <br> Balance</a></div>
                    <?php if($user->has_free_package == 'no'): ?>
                    <div class="ad-box bg-success mr-2 ml-1"><a class="nav-icon fas fa-gift" href="#">
                        <br> Locked Token <br><?php echo e($locked); ?> </a>
                    </div>
                    <?php endif; ?>
                </div>
                <!--<div class="sub-box-1">
                    <div class="ad-box bg-info col"><a class="nav-icon fas fa-gift" href="#"><br>Wallet  Balance <br> coming soon</a></div>
                </div>-->

                <div class="sub-box-1">
                         <div class=" ad-box bg-success ">
                         <a class="nav-icon fas fa-wallet" href="/user/user-wallet">
                         <br>Wallet  Balance</a>
                         </div>
                        <?php if($user->has_free_package == 'no'): ?>
                            <div class="ad-box bg-primary ml-1">
                                <a class="nav-icon fas fa-gift" href="#"><br>Cashout Credit <br> $<?php echo e($credit); ?>

                                    <br>
                                    <?php
                                                    $status = $credit_status;
                                                    $badgeClass = match($status) {
                                                        'approved' => 'badge-dark',
                                                        'rejected' => 'badge-danger',
                                                        default => 'badge-warning',
                                                    };
                                            ?>

                                                <span class="badge <?php echo e($badgeClass); ?>">
                                                    <?php echo e($status); ?>

                                                </span>


                                </a>
                            </div>

                        <?php endif; ?>
                    </div>


             </div>


         </div>
        </div>

         <!-- Content Header (Page header) -->
         <div class="content-header">
             <div class="container-fluid">
                 <!-- /.row -->

         <!-- /.content-header -->
         <div class="ref row">
            <div class="col-md-9 mx-div">
                <div class="row row1">
                 <div class="col-12 col-sm-6 col-md-3">
                     <a href="">
                         <div class="info-box">
                             <span class="info-box-icon bg-info elevation-1"><i class="fas fa-list"></i></span>

                             <div class="info-box-content">
                                  <span class="info-box-text">Clicks</span>
                                 <span class="info-box-number">

                                 </span>
                             </div>

                         </div>
                     </a>

                 </div>
                 <!-- /.col -->
                 <div class="col-12 col-sm-6 col-md-3">
                     <a href="<?php echo e(route('user.referral.show')); ?>">
                         <div class="info-box mb-3">
                             <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>
                                 <?php
                                        $referrals=db::SELECT("SELECT * from users where referee_id= :ref",['ref'=>$user->id]);

                                        // $earning = db::SELECT("SELECT user, SUM(earning) as earn FROM balances WHERE user = :user GROUP BY user", ['user' => $userr]);




                                $task=db::SELECT("SELECT * FROM users join activations on(users.email=activations.email) join tasks ON(activations.code=tasks.code)  WHERE users.has_paid_package='ft' and  activations.package='ft' and users.email='$email'");
                                 ?>
                             <div class="info-box-content">
                                 <span class="info-box-text">Referrals</span>
                                 <span class="info-box-number"><?php echo e($referals); ?></span>
                             </div>

                         </div>
                     </a>

                 </div>
                 <!-- /.col -->

                 <!-- fix for small devices only -->
                 <div class="clearfix hidden-md-up"></div>

                 <div class="col-12 col-sm-6 col-md-3">
                    <a href="<?php echo e(route('user.task.show')); ?>">
                     <div class="info-box mb-3">
                         <span class="info-box-icon bg-success elevation-1"><i class="fas fa-list"></i></span>

                         <div class="info-box-content">
                             <span class="info-box-text">Tasks</span>
                             <span class="info-box-number"><?php echo e(count($task)); ?></span>
                         </div>

                     </div>
                    </a>
                 </div>

                 <!-- /.col -->
                 <div class="col-12 col-sm-6 col-md-3">
                     <div class="info-box mb-3">
                         <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

                         <div class="info-box-content">
                             <span class="info-box-text">Earnings</span>
                             <span class="info-box-number text-sm"><?php echo e(number_format($earnings)); ?></span>
                         </div>

                     </div>

                 </div>
                 <!-- /.col -->
             </div>

            </div>

            <div class="row2 col-md-3 mx-div zoom">
                 <span style="color:#dc3545; font-size: 15px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;coming soon</span>
                 <div class="subZoom row">
                    <div class="img col-2">
                        <div class="zoomImg">
                            <img src="<?php echo e(asset('assets/a/img/zoom.png')); ?>" alt="">
                        </div>
                    </div>
                    <div class="col-10 zoom-content">

                        <div>
                            <span class="text-warning">  we are on zoom</span><br>
                        <span class="font-weight-bold"><a href="#">CLICK  HERE  TO JOIN US</a></span>
                        </div>

                    </div>
                </div>
         </div>
          </div><!-- /.container-fluid -->
         </div>
         <div>
            <?php echo $__env->make('user.chatonline', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
         <!--   <div class="d-flex flex-column justify-content-between  mt-2 position-relative containers w-25 ">
                <button class="btn btn-warning  btn-sm text-sm
                 d-flex align-items-center justify-content-center gap-2 font-weight-bold p-0" style="padding:0,margin:0" onclick="handleOpen()" >
                    <i class="fa fa-comments"></i>
                    <span>Chat Online</span>
                </button>
                <a href="https://t.me/+dDtOAg1RwTg5NDFk"
                target="_blank" style="padding:0,margin:0" class="btn btn-warning btn-sm  my-2 d-flex align-items-center
                 justify-content-center gap-2 font-weight-bold">
                    <i class="fa fa-paper-plane"></i>
                    <span>Chat On Telegram</span>
                </a>
            </div>
            -->
         </div>
         <!-- /.row ENDING RIGHT HERE AND BE READY TO START -->
         <!-- Main content -->
         <div class="content">
             <div class="container-fluid ">

             <?php if($have_pending_deposits): ?>
<div id="myModal" class="modal fade show d-block" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-top warning " role="document">
        <div class="modal-content p-4 shadow border rounded " style="background:#27445D;">
            <?php if(session('success')): ?>
                <div class="text-success">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="text-danger">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <div class="text-center mb-3">
                <h2 class="text-uppercase fw-bold text-white" >YOU HAVE PENDING DEPOSIT</h2>
            </div>
            
            <p class="text-light small">You still have a pending order.</p>
            <p class="text-light small py-1">If your transaction is not approved, please submit your transaction ID below:</p>
            
            <form class="d-flex justify-content-center align-items-center gap-2" method="POST" action="<?php echo e(route('user.claim')); ?>">
                <?php echo csrf_field(); ?>
                <input type="text" 
                  class="form-control me-2" 
                  placeholder="Transaction ID" name="transactionId" 
                  value="<?php echo e($have_pending_deposits->transaction_id ?? ''); ?>" readonly>
                <button type="submit" class="btn btn-success text-white mx-2">Send</button>
            </form>
            <p class="text-sm">Please wait admin to approve your deposit</p>
            <a class="btn btn-danger btn-sm" href="#" 
                onclick="event.preventDefault(); document.getElementById('logout-form').submit()">Logout</a>
 

          
        </div>
    </div>
</div>
<?php endif; ?>

                 <div class="row ">
                             <div class="card card-primary card-outline col-lg-4">

                                 <h5 class="card-title">
                                    Refferral id:
                                    <button class="btn btn-default font-weight-bold" type="submit" id="btn2" disabled>
                                         <i class="las la-link" style="font-size: 20px;"></i>
                                          <input type="hidden" value="<?php echo e($ref_code); ?>" id="link1">
<a href="#" style="color:red;text-decoration:none;">
    <?php if($package!='standard'): ?>
  <span style="visibility: visible;"><?php echo e($ref_code); ?></span>
  <?php else: ?>
  <span style="visibility: visible;">*********</span>
  <?php endif; ?>
</a></h5><br>
                                    </button>

                                            <div class="input-group">
                                            <?php if($package!='standard'): ?>
                                            <!-- value="http://bifonex.com/register?referral=<?php echo e($ref_code); ?>" -->
                                                <input type="text" id="link" 
                                                value="<?php echo e($baseUrl); ?>/register?referral=<?php echo e($ref_code); ?>"
                                              class="form-control" readonly class="form-control">

                                                <button class="btn btn-default" type="submit" id="btn">
                                                    <i class="las la-link" style="font-size: 20px;"></i>
                                                </button>
                                              <?php else: ?>
                                                <input type="text" id="link"value="http://bifonex.com/register?referral=*******"
                                              class="form-control" readonly class="form-control"><?php endif; ?>

                                            </div>

                                            <p class="card-text text-center py-1">
                                            <i class="fas fa-arrow-circle-right"></i> Share this link and get 500 coin when they activate account!
                                            </p>


                                            <div class="input-group">
                                            <?php if($package!='standard'): ?>
                                               <button class="btn btn-info  "  onclick="copyToClipboard('link-right')">
                                                    Right
                                                </button>
                                                
                                                <input type="text" id="link-right"
                                                value="<?php echo e($baseUrl); ?>/register?referral=<?php echo e($ref_code); ?>&side=RIGHT"
                                              class="form-control" readonly class="form-control">

                                                <button class="btn btn-default"  onclick="copyToClipboard('link-right')">
                                                    <i class="las la-link" style="font-size: 20px;"></i>
                                                </button>
                                              <?php else: ?>
                                                <input type="text" id="link-right"value="http://bifonex.com/register?referral=*******"
                                              class="form-control" readonly class="form-control"><?php endif; ?>

                                            </div>


                                            <div class="input-group my-1">
                                            <?php if($package!='standard'): ?>
                                              <button class="btn btn-primary"  onclick="copyToClipboard('link-right')">
                                                    Left
                                                </button>
                                                <input type="text" id="link-left" value="<?php echo e($baseUrl); ?>/register?referral=<?php echo e($ref_code); ?>&side=LEFT"
                                              class="form-control" readonly class="form-control">

                                                <button class="btn btn-default" onclick="copyToClipboard('link-left')">
                                                    <i class="las la-link" style="font-size: 20px;"></i>
                                                </button>
                                              <?php else: ?>
                                                <input type="text" id="link-left"value="http://bifonex.com/register?referral=*******"
                                              class="form-control" readonly class="form-control"><?php endif; ?>

                                            </div>

                                     <script type="text/javascript">
                                         function getreferral() {
                                             var package="<?php echo $package ?>";
                                             if(package=="standard"){
                                                 alert('Activate package to be allowed for this feature')
                                             }
                                             else{
                                            var codeinput = document.getElementById('link1');
                                            var newcode=codeinput.value;
                                            var tempInput = document.createElement("input");
                                            tempInput.value = newcode;
                                            document.body.appendChild(tempInput);
                                            tempInput.select();
                                            document.execCommand("copy");
                                            document.body.removeChild(tempInput);
                                            alert('Referral code copied to clickboard ');
                                        }}
                                        var copybtn = document.getElementById('btn2');
                                        copybtn.addEventListener("click", getreferral);

                                     </script>

                                        <br>

                                            <!-- <a href="#" id="btn">  <img src="https://cdn-icons-png.flaticon.com/128/455/455691.png" style="width:20px;height: 20px;float: left;">
                                        &nbsp;&nbsp;Copy Link</a></i><br> -->

                             </div>
                             <div class="col-lg-5">

                                 <div class="col-sm-md-4 bg-white">
                                     <!-- Map card -->
                                     <div class="card bg-gradient-primary">
                                         <div class="card-header border-0 " style="position: relative;">
                                             <h3 class="card-title">
                                                 <i class="fas fa-edit mr-1"></i>
                                                 Survey <i style="color:#dc3545; font-size: 15px; position: absolute; right:20px;">comming soon</i>
                                             </h3>

                                             <!-- /.card-tools -->
                                         </div>
                                         <div class="card-body"
                                             style="background-color:white; width: 100%; ">
                                             <center><a href="#">
                                                     <h4>Earn $30 By SURVEYS & APPS</h4>
                                                     <i class="fas fa-share "></i>
                                                 </a>
                                             </center>

                                         </div>

                                     </div>
                                 </div>
                             </div>
                                <?php
        $fcuser=db::SELECT("SELECT * from users where has_paid_package= 'fc1' or has_paid_package= 'fc2'");
$user=db::SELECT("SELECT * from users");




                                ?>
                                <div class="col-lg-3" style="margin-right:0px;">
                                    <div class="">
                                        <div class="info-box mb-3 w-100" style="width: 250px;height: 70px;">
                                             <span class="info-box-icon  elevation-1"><i class="fas fa-users text-primary"></i></span>

                                             <div class="info-box-content">
                                                 <span class="info-box-text">Total Founders</span>
                                                 <span class="info-box-number">******</span>
                                             </div>

                                         </div>
                                         <div class="info-box mb-3 w-100 top-users" style="width: 250px;height: 130px;">
                                                <div class="topUser_title">Top Users</div>
                                                <div class="soon">comming soon</div>
                                                <div class="topUser_img">
                                                    <img src="<?php echo e(asset('assets/a/img/team.png')); ?>" alt="">
                                                </div>
                                                <div class="Usersmsg">
                                                    <span class=""> No users joined yet</span>


                                         </div>

                                    </div>

                            </div>
                         <script type="text/javascript">
                         function copylink() {
                              var package="<?php echo $package ?>";
                                             if(package=="standard"){
                                                 alert('Activate package to be allowed for this feature')
                                             }
                                             else{
                             var linkinput = document.getElementById('btn2');
                             linkinput.select();
                             document.execCommand("copy");
                             alert("Link copied to clipbord");
                         }}

                         var copybtn = document.getElementById('btn');
                         copybtn.addEventListener("click", copylink());



        function copyToClipboard(elementId) {
            const input = document.getElementById(elementId);
            input.select();
            input.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand("copy");
            // input.value
            alert("Link Copied to clickboard  " );
        }


                         </script>



                     
                 </div>
                 <span style="color: blue;font-size: 20px;"><b>Upcoming Projects</b> </span>

                                 <div class="row">
                     <div class="comingSoon col-md-5">
                          <div class="row my-2">

                                 <div class="col-12 col-sm-6 col-md-6">
                                     <div class="info-box main-upcoming">
                                        <div class="upcoming ">
                                            <div>
                                                <img src="<?php echo e(asset('assets/a/img/upcoming.png')); ?>" alt="">
                                            </div>
                                            <div class="info-box-content text-center">
                                                <b></b>Future shoop
                                            </div>
                                        </div>

                                     </div>
                                 </div>
                                 <!-- /.col -->
                                 <div class="col-12 col-sm-6 col-md-6">

                                     <div class="info-box mb-3  main-upcoming">
                                        <div class="upcoming">
                                            <div>
                                                <img src="<?php echo e(asset('assets/a/img/booking.png')); ?>" alt="">
                                            </div>
                                            <div class="info-box-content text-center">
                                            <b>booking system</b>
                                            <!--Your fovorite booking site-->
                                            </div>
                                        </div>

                                     </div>

                                 </div>
                                 <!-- /.col -->

                                 <!-- fix for small devices only -->
                                 <div class="clearfix hidden-md-up"></div>

                                 <div class="col-12 col-sm-6 col-md-6">

                                     <div class="info-box mb-3  main-upcoming">
                                        <div class="upcoming">
                                            <div>
                                                <img src="<?php echo e(asset('assets/a/img/shopping.png')); ?>" alt="">
                                            </div>
                                            <div class="info-box-content text-center">
                                             <b>Market Place</b>
                                            </div>
                                        </div>

                                     </div>

                                 </div>
                                     <!-- /.col -->
                                 <div class="col-12 col-sm-6 col-md-6">

                                     <div class="info-box mb-3  main-upcoming">
                                        <div class="upcoming">
                                            <div>
                                                <img src="<?php echo e(asset('assets/a/img/crypto.png')); ?>" alt="">
                                            </div>
                                            <div class="info-box-content text-center">
                                            <b>crypto loans </b>
                                            </div>
                                        </div>

                                     </div>

                                 </div>

                             </div>
                        </div>


                     <div class="clickEvents col-md-7 py-2">
                        <div class="container-fluid">
                            <span style="color:#dc3545; font-size: 15px;">comming soon</span>
                            <div class="main_comingBox row d-flex justify-content-between ">
                                <div class="comingBox col-12 col-sm-6 col-md-5">

                                    <div class="row">
                                        <div class="col-8 zoom-content">
                                            <div>
                                                <span><a href="#">CLICK  HERE  TO </a></span><br>
                                                <span>  STAKE YOUR TOKENS</span><br>
                                            </div>

                                        </div>

                                        <div class=" col-3 d-flex justify-content-end align-items-center">
                                            <div class="icon">
                                                <i class="las la-external-link-alt"></i>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                               <div class="comingBox col-12 col-sm-6 col-md-5">
                                    <div class="row">
                                        <div class="col-8 zoom-content">
                                            <div>
                                                <span><a href="#">CLICK  HERE  TO </a></span><br>
                                                <span> UPGRADE MEMBERSHIP</span><br>
                                            </div>

                                        </div>

                                        <div class=" col-3 d-flex justify-content-end align-items-center"  >
                                            <div class="icon">
                                                <i class="las la-external-link-alt"></i>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                             </div>

                             <div class="main_comingBox row d-flex justify-content-between ">
                                <div class="comingBox col-12 col-sm-6 col-md-5">
                                    <div class="row">
                                        <div class="col-8 zoom-content">
                                            <div>
                                                <span><a href="#">CLICK  HERE  TO </a></span><br>
                                                <span> UNSTAKE </span><br>
                                            </div>

                                        </div>

                                        <div class=" col-3 d-flex justify-content-end align-items-center" >
                                            <div class="icon">
                                                <i class="las la-external-link-alt text-black "></i>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                               <div class="comingBox col-12 col-sm-6 col-md-5">
                                    <div class="row">
                                        <div class="col-8 zoom-content">
                                            <div>
                                                <span><a href="#">CLICK  HERE  TO </a></span><br>
                                                <span>TOKEN EARNINGS </span><br>
                                            </div>

                                        </div>

                                        <div class=" col-3 d-flex justify-content-end align-items-center">
                                            <div class="icon">
                                                <i class="las la-external-link-alt text-white "></i>

                                            </div>

                                        </div>
                                    </div>

                                </div>
                             </div>

                             <div class="findUs p-3">
                                 <div class="text-white text-center">Add Your self to our <a href="#"> <i class="lab la-facebook-f"></i>FaceBook page</a>   <a href="#"><i class="lab la-telegram-plane"></i>Telegram</a> </div>
                             </div>
                        </div>

                     </div>
                 </div>

                    <div class="row w-100" style="margin-top:15px">
                     <div class="col-md-6">
                         <div class="card direct-chat direct-chat-primary">
                             <div class="card-header">
                                 <h3 class="card-title">LEADERBOARD</h3>

                                 <div class="card-tools">
                                     <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                         <i class="fas fa-minus"></i>
                                     </button>
                                     <button type="button" class="btn btn-tool" data-toggle="tooltip" title="Contacts"
                                         data-widget="chat-pane-toggle">
                                         <i class="fas fa-comments"></i>
                                     </button>
                                     <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                             class="fas fa-times"></i>
                                     </button>
                                 </div>
                             </div>
                             <!-- /.card-header -->
                             <div class="card-body">
                                 <div class="direct-chat-messages">

                                     <table class="table">
                                         <thead class="bg-gradient-primary">
                                             <tr>
                                                 <th>Username</th>
                                                 <th></th>
                                                 <th>Total Earned</th>
                                             </tr>
                                         </thead>
                                         <tbody>
                                             <tr>
                                         <!--         <td>1. John</td>
                                                 <td></td>
                                                 <td>$0</td>
                                             </tr>
                                             <tr>
                                                 <td>2. Mary</td>
                                                 <td></td>
                                                 <td>$0</td>
                                             </tr>
                                             <tr>
                                                 <td>3. July</td>
                                                 <td></td>
                                                 <td>$0</td>
                                             </tr>
                                             <tr>
                                                 <td>4. July</td>
                                                 <td></td>
                                                 <td>$0</td>
                                             </tr>
                                             <tr>
                                                 <td>5. JackMan
                                                 </td>
                                                 <td></td>
                                                 <td>$0</td>
                                             </tr> -->
                                         </tbody>
                                     </table>




                                 </div>


                             </div>

                         </div>

                         



                     </div>
                     <div class="col-md-6">

                         <div class="col-md-12">
                             <div class="card direct-chat direct-chat-primary">
                                 <div class="card-header">
                                     <h3 class="card-title">Social Media Share</h3>

                                     <div class="card-tools">
                                         <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                             <i class="fas fa-minus"></i>
                                         </button>

                                         <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                                 class="fas fa-times"></i>
                                         </button>
                                     </div>
                                 </div>
                                 <!-- /.card-header -->
                                 <div class="card-body">
                                     <div class="direct-chat-messages">
                                         <!-- Add font awesome icons -->
                                         <div class="px-3 d-flex justify-content-between align-items-center ">
                                            <div class="link-icon">
                                                <div><a href="#" onclick="shareOnFacebook()" class="links media_fb"><i class=" lab la-facebook-f "></i></a></div>
                                                <div class="">Facebook</div>
                                            </div>
                                            <div>
                                             <button class=" btn btn-success" onclick="shareOnFacebook()">share link</button>
                                            </div>
                                         </div>
                                         <hr>

                                         <div class="px-3 d-flex justify-content-between align-items-center">
                                             <div class="link-icon">
                                                <div><a href="#" class="links media_tw"><i class=" lab la-twitter"></i></a></div>
                                                <div class="">Twitter</div>
                                            </div>
                                            <div>
                                                <button class=" btn btn-success" onclick="shareOnTwitter()">share link</button>
                                            </div>
                                         </div>
                                         <hr>

                                          <div class="px-3 d-flex justify-content-between align-items-center">
                                                <div class="link-icon">
                                                    <div><a href="#" class="links media_wt"><i class=" lab la-whatsapp"></i></a></div>
                                                    <div class="">Whatsapp</div>
                                                </div>
                                            <div>
                                                <button class=" btn btn-success" onclick="shareOnWhatsApp()">share link</button>
                                            </div>
                                         </div>
                                         <!--<hr>-->

                                         <!--<div class="px-3 d-flex justify-content-between align-items-center">  -->
                                         <!--       <div class="link-icon">  -->
                                         <!--           <div><a href="#" class="links media_in"><i class=" lab la-instagram"></i></a></div> -->
                                         <!--           <div class="">Instagram</div>-->
                                         <!--       </div> -->
                                         <!--   <div>-->
                                         <!--       <button class=" btn btn-success" onclick="shareOnInstagram()">share link</button>-->
                                         <!--   </div>-->
                                         <!--</div>-->
                                         <!--<hr>-->
                                         <!--<div class="px-3 d-flex justify-content-between align-items-center">  -->
                                         <!--       <div class="link-icon">  -->
                                         <!--           <div><a href="#" class="links media_pi"><i class=" lab la-pinterest"></i></a></div> -->
                                         <!--           <div class="">Pinterest</div>-->
                                         <!--       </div> -->
                                         <!--   <div>-->
                                         <!--       <button class=" btn btn-success" onclick="shareOnInstagram()">share link</button>-->
                                         <!--   </div>-->
                                         <!--</div>-->
                                         <hr>

                                     </div>


                                 </div>
                             </div>

                         </div>



                     </div>








<!-- js share logic -->
 <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>

 <script src="https://cdn.jsdelivr.net/npm/whatsapp-web@1.9.0/dist/browser.js"></script>
  <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>




  <!-- Add your own JavaScript code -->
  <script>

    // document.getElementById('facebook-share-button').addEventListener('click', shareOnFacebook);
    document.getElementById('whatsapp-share-button').addEventListener('click', shareOnWhatsApp);
    document.getElementById('twitter-share-button').addEventListener('click', shareOnTwitter);



    // Function to handle the Facebook sharing
   function shareOnFacebook() {
    // Replace "YOUR_SHARE_URL" with the URL you want to share
    var shareUrl = 'https://www.bifonex.com/register?referral=<?php echo e($ref_code); ?>';

    // Open the Facebook share dialog
    window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(shareUrl), '_blank');
  }

  // Function to handle the WhatsApp sharing
    function shareOnWhatsApp() {
      // Replace 'YOUR_SHARE_TEXT' with the desired text to share
      var shareText = encodeURIComponent('Infinite earning: https:www.bifonex.com/register?referral=<?php echo e($ref_code); ?>');
      var whatsappURL = 'https://api.whatsapp.com/send?text=' + shareText;
      window.open(whatsappURL, '_blank');
    }

   // Function to handle the Twitter sharing
    function shareOnTwitter() {
      // Replace 'YOUR_SHARE_TEXT' with the desired text to share
      var shareText = 'Infinite earning: https://www.bifonex.com/register?referral=<?php echo e($ref_code); ?>';

      // Open the Twitter share popup
      window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent(shareText), '_blank');
    }

    // Add an event listener to the button

  </script>









                     <!-- /.row -->

                 </div>



                 <div class="col-lg-12">
                     <!-- Map card -->
                     <div class="card " style="background-color:#000050; width: 100%; ">
                         <div class="card-header " style="background-color:gray;">
                             <h3 class="card-title" style="color: white">
                                 Earn Type
                             </h3>


                             <!-- /.card-tools -->
                         </div>
                         <div class="card-body">
                             <div class="row">
                                 <div class="col-md-9">
                                     <h3 style="color: white">Tasks</h3>
                                 </div>
                                 <div class="col-md-3">

                                     <h3 style="color: white">Earn</h3>
                                 </div>
                             </div>
                             <!-- /.card -->
                             <div class="row" style="margin-top:10px;">
                                  <div class="col-md-5">
                                     <li style="color: white">Create YouTube videos</li>
                                 </div>
                                  <div class="col-md-4 pt-2">
                                     <i style="color:#dc3545; font-size: 15px;">comming soon</i>
                                 </div>
                                 <div class="col-md-3 btn btn-success">
                                     50$
                                 </div>
                             </div>
                             <!-- /.card -->
                             <div class="row" style="margin-top:10px;">
                                  <div class="col-md-5">
                                     <li style="color: white">Complete surveys</li>
                                 </div>
                                  <div class="col-md-4 pt-2">
                                     <i style="color:#dc3545; font-size: 15px;">comming soon</i>
                                 </div>
                                 <div class="col-md-3 btn btn-success">
                                     30$
                                 </div>
                             </div>
                             <!-- /.card -->
                             <div class="row" style="margin-top:10px;">
                                  <div class="col-md-5">
                                     <li style="color: white">Download apps</li>
                                 </div>
                                  <div class="col-md-4 pt-2">
                                     <i style="color:#dc3545; font-size: 15px;">comming soon</i>
                                 </div>
                                 <div class="col-md-3 btn btn-success">
                                     20$
                                 </div>
                             </div>
                             <!-- /.card -->
                             <div class="row" style="margin-top:10px;">
                                  <div class="col-md-5">
                                     <li style="color: white">Refer friends</li>
                                 </div>
                                  <div class="col-md-4 pt-2">
                                     <i style="color:#dc3545; font-size: 15px;">comming soon</i>
                                 </div>
                                 <div class="col-md-3 btn btn-success">
                                     10$
                                 </div>
                             </div>
                             <!-- /.card -->
                             <div class="row" style="margin-top:10px;">
                                 <div class="col-md-5">
                                     <li style="color: white">Get clicks</li>
                                 </div>
                                  <div class="col-md-4 pt-2">
                                     <i style="color:#dc3545; font-size: 15px;">comming soon</i>
                                 </div>
                                 <div class="col-md-3 btn btn-success">
                                     2$
                                 </div>
                             </div>
                             <!-- /.card -->
                         </div>

                     </div>
                     <!-- /.card -->

                     <!-- solid sales graph -->

                 </div>
             </div>
             <div class="row">
                 <div class="col-md-12 ">
                     <div class="card card-secondary card-outline">

                         <div class="card-body card-warning">
                             <div class="row">
                                 <div class="col-md-4">
                                     <h1 class="py-2 text-center">TOTAL VOLUME</h1>
                                     <div class="row justify-content-between">
                                         <div class="col">
                                             <!-- Map card -->
                                             <div class="card bg-gradient-primary">

                                                 <div class="card-body"
                                                     style="background-color:white;color:black;height: 180px; width: 100%; ">
                                                     <h6>Right Team</h6>
                                                     <h5><b class="text-warning">Members:</b> <?php echo e($right); ?></h5>

                                                     <p>
                                                     <h4><?php echo e($right_amount); ?> <small>Vp</small></h4>
                                                     </p>


                                                     <h3 class="text-success">
                                                         <i class="fas fa-users mr-1"></i>
                                                         Earn Bonus
                                                     </h3>
                                                     <hr>
                                                 </div>
                                             </div>
                                         </div>




                                        <div class="col ">
                                            <div class="card  bg-white  rounded  d-flex justify-content-center align-items-center py-2">
                                                <i class=" fas fa-wallet text-white px-3 rounded-lg text-lg text-center py-3" style="background-color:rgb(238, 193, 71);font-size:2em !important"></i>
                                                <h2 class="text-lg text-dark fw-bold py-2"><?php echo e($commission); ?> $</h2>
                                                <p class="py-2 px-2 text-center">Total Commission Earn</p>
                                            </div>
                                        </div>

                                        </div>


                                     <!-- solid sales graph -->
                                     <div class="row justify-content-between">

                                         <div class="col">
                                             <!-- Map card -->
                                             <div class="card bg-gradient-primary">

                                                 <div class="card-body"
                                                     style="background-color:white;color:black;height: 180px; width: 100%; ">
                                                     <h6>Left Team</h6>
                                                     <h5><b class="text-warning">Members:</b> <?php echo e($lift); ?> </h5>

                                                     <p>
                                                     <h4>0 <small>Vp</small></h4>
                                                     </p>

                                                     <h3 class="text-success">
                                                         <i class="fas fa-users mr-1"></i>
                                                         Earn Bonus
                                                     </h3>
                                                     <hr>
                                                 </div>
                                             </div>
                                         </div>
                                         <div class="col">
                                         </div>

                                         <!-- solid sales graph -->
                                     </div>
                                 </div>
                                 
                                 <div class="col-md-8">
                                     <h1 class="py-2 text-center">COMMISSIONS</h1>
                                     <div class="card">

                                         <div class="card-body">
                                             <div class="row">
                                                 <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-6">
                                                           <div class="py-2">
                                                                <h4 class="text-left p-0">LEFT TEAM</h4>
                                                                <div>
                                                                <p> <strong>Direct UVP: </strong><?php echo e($left_direct_uvp); ?></p>
                                                                <p> <strong>Indirect UVP: </strong><?php echo e($left_indirect_uvp); ?></p>
                                                                <p> <strong>Total Person Team: </strong>0</p>
                                                                <div>
                                                                        <strong>Total Team Ref</strong>
                                                                        <div>
                                                                            <p>VP: <strong><?php echo e($left_direct_uvp+$left_indirect_uvp); ?></strong></p>
                                                                            <p>Subscriptions: <strong>0</strong></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                           <div class="py-2">
                                                                <h4 class="text-left p-0">RIGHT TEAM</h4>
                                                                <div>
                                                                    <p> <strong>Direct UVP: </strong><?php echo e($right_direct_uvp); ?> </p>
                                                                    <p> <strong>Indirect UVP: </strong><?php echo e($right_indirect_uvp); ?> </p>
                                                                    <p> <strong>Total Person Team: </strong>
                                                                        0
                                                                        </p>
                                                                    <div>
                                                                        <strong>Total Team Ref</strong>
                                                                        <div>
                                                                            <p>VP: <strong><?php echo e($right_direct_uvp+$right_indirect_uvp); ?></strong></p>
                                                                            <p>Subscriptions: <strong>soon</strong></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                 </div>
                                                 <div class="col-md-6">
                                                     <select name="" id="earnings-filter"
                                                         class="form-control float-sm-right float-md-right">
                                                         <option value="this_week">This week</option>
                                                        <option value="last_week">Last week</option>
                                                        <option value="this_month">This month</option>
                                                        <option value="last_month">Last month</option>
                                                        <option value="this_year">This year</option>
                                                        <option value="last_year">Last year</option>
                                                        <option value="all_time" selected>All time</option>

                                                     </select>
                                                    <div class="row py-2">
                                                        <div class="col-6">
                                                            <div class="card  bg-white rounded  d-flex justify-content-center align-items-center py-2">
                                                                <i class="fa fa-bar-chart text-yellow-500 text-lg text-center" style="color:rgb(238, 193, 71);font-size:2em !important"></i>
                                                                <h2 class="text-xl text-info" id='left-amount'>0.00 $</h2>
                                                                <p class="py-2 text-xs" id='left-for'>Left Earning  <span></span></p>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="card  bg-white rounded  d-flex justify-content-center align-items-center py-2">
                                                                <i class="fa fa-bar-chart text-yellow-500 text-lg text-center" style="color:rgb(238, 193, 71);font-size:2em !important"></i>
                                                                <div>
                                                                    <h2 class="text-xl text-info" id="right-amount">0.00 $</h2>
                                                                    <p class="py-2 text-xs" id="right-for">Right Earning  <span></span></p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>



                                                 </div>


                                             </div>

                <script>
                document.getElementById('earnings-filter').addEventListener('change', function() {
                    const selectedOption = this.value;

                    fetch(`/fetch-earnings?period=${selectedOption}`)
                        .then(response => response.json())
                        .then(data => {
                            // Do something with the fetched earnings data
                            console.log(data);
                            document.querySelector("#right-amount").innerHTML = data.right+".0 $"
                            // document.querySelector("#right-for").innerHTML = data.right
                            document.querySelector("#left-amount").innerHTML = data.left+".0 $"
                            // document.querySelector("#right-amount").innerHTML = data.right

                            // You can also update the UI with the earnings data here
                        })
                        .catch(error => console.error('Error:', error));
                });
                </script>

                                             <div class="row">
                                                 <div class="col-md-4"></div>

                                                 <div class="col-md-6">
                                                     <div class="col-6 text-center">
                                                         <input type="text" class="knob" readonly value="0"
                                                             data-width="90" data-height="90" data-fgColor="#39CCCC">
                                                     </div>
                                                 </div>

                                             </div>


                                             <div class="row">
                                                 <div class="col-sm-3 col-6">
                                                     <div class="description-block border-right">

                                                        <div class="px-2">
                                                            <span class="description-text"><b>ZONE A Earn vp</b></span><br>
                                                            <h5 class="description-header">
                                                                Vp
                                                                <?php echo e($zoneAearning); ?>

                                                            </h5>
                                                        </div>

                                                     </div>
                                                     <!-- /.description-block -->
                                                 </div>
                                                 <div class="col-sm-3 col-6">
                                                     <div class="description-block border-right">

                                                        <div class="px-2">
                                                            <span class="description-text"><b>ZONE B Earn vp</b></span><br>
                                                            <h5 class="description-header">
                                                                <?php echo e($zoneBearning); ?> Vp
                                                            </h5>
                                                        </div>

                                                     </div>
                                                     <!-- /.description-block -->
                                                 </div>
                                                 <!-- /.col -->
                                                 <div class="col-sm-3 col-6">
                                                     <div class="description-block border-right">
                                                         <p class="description-text"> <b>Volume Bonus</b></p>
                                                         <div class=" d-flex justify-content-center align-items-center" style="color:rgb(238, 193, 71);">
                                                            <i class="fa fa-arrow-up text-success"></i>
                                                            <span>0 EUR</span>
                                                        </div>
                                                        <p class="text-center p-0 m-0">Week 09 March - 15 March</p>
                                                        <p class="text-center p-0 m-0">Volume Bonus</p>
                                                        <h5 class="description-header" style="color:rgb(238, 193, 71);">0 EUR</h5>


                                                     </div>
                                                     <!-- /.description-block -->
                                                 </div>
                                                 <!-- /.col -->
                                                 <div class="col-sm-3 col-6">
                                                     <div class="description-block">
                                                         <span class="description-text"><b>Earn vp</b></span><br>
                                                         <h5 class="description-header">0 Vp</h5>
                                                     </div>
                                                 </div>


                                             </div>


                                         </div>
                                     </div>
                                     <!-- END OF CARD -->

                                 </div>

                             </div>

                         </div>
                     <!-- END OF CARD -->
                 </div>
                 <!-- /.col-md-6 -->
             </div>




     </div>


 </div>
 </div>
 <?php if($package!='FT'): ?>


 <div class="row">
    <div class="col-md-4">
        <div class="mytask w-100">
            <div>
                <h1 class="task-head py-2">Task progress</h1>
<br>

                <div class="clock py-2">
                    <div class="task-content">
                        <div class="perc"></div>
                        <div class="taskText"> Task Progress</div>
                    </div>
                    <!-- this one is that yellow background indicating progress -->
                    <div class="indicator">
                        <div class="bcindicator indicatorTask"></div>
                    </div>
                    <!-- this below div contain ticks of task don't delete it -->
                    <div class="task-ticks">
                    </div>
                    <div class="task-ticks-miror-one"></div>
                    <div class="task-ticks-miror"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">

        <div class="row">
            <div class="col-md-6 col-sm-12 bg-dark shadow rounded p-3 d-flex justify-content-center align-items-center m-1 ">
                <div class="col" id="chartsd"></div>

            </div>


            <div class="col bg-dark shadow rounded p-3 d-flex justify-content-center align-items-center m-1 ">
                <div class="col" id="chart2"></div>

            </div>



           <!-- <div class="col bg-white shadow rounded p-3 d-flex justify-content-center align-items-center ml-2 my-2">
                <div >
                    <i class="fa fa-users text-yellow-500 text-lg" style="color:rgb(238, 193, 71);font-size:2em !important"></i>
                    <h2 class="text-xl text-info">0.00 $</h2>
                    <p>some text</p>
                </div>

            </div>

            <div class="col bg-white shadow rounded p-3 d-flex justify-content-center align-items-center mx-2 my-2">
                <div >
                    <i class="fa fa-users text-yellow-500 text-lg" style="color:rgb(238, 193, 71);font-size:2em !important"></i>
                    <h2 class="text-xl text-info">0.00 $</h2>
                    <p>some text</p>
                </div>

            </div>


            <div class="col bg-white shadow rounded p-3 d-flex justify-content-center align-items-center my-2 ">
                <div >
                    <i class="fa fa-users text-yellow-500 text-lg" style="color:rgb(238, 193, 71);font-size:2em !important"></i>
                    <h2 class="text-xl text-info">0.00 $</h2>
                    <p>some text</p>
                </div>

            </div>-->



        </div>
    </div>
</div>






<!-- <a href="<?php echo e(route('user.dashboard.events')); ?>" class="btn btn-primary">all events</a> -->

          <div class="recentEvent col-12 col-sm-6 shadow col-md mx-md-2 my-2 py-3 px-4">

            <div class="recbox">
                <a href="<?php echo e(route('user.dashboard.events')); ?>" class="box  d-flex justify-content-between align-items-center py-2  px-5">
                    <div class="font-weight-bold"> <h4 class="my-3 mx-2 font-weight-bold">Recent Events </h4></div>
                    <div>

                        <!-- <div class="date">12/05/2023</div> -->
                        <div class="allevents">all events</div>
                    </div>
                </a>
                <?php if($events): ?>
               <span class="ml-2 bg bg-primary"> No events reported yet</span>

               <?php else: ?>
           <?php foreach ($events as $event) { ?>
                <div class="recbox">
                <a href="#" class="box  d-flex justify-content-between align-items-center py-2  px-5">
                    <div class="font-weight-bold"><?php echo e($event->desc); ?></div>
                    <div>
                        <div class="date"><?php echo e($event->date_on); ?></div>
                         <?php if($event->status=='pending'): ?>
                              <div class="status status-pending"><?php echo e($event->status); ?></div>
                              <?php else: ?>
                              <div class="status status-verified"><?php echo e($event->status); ?></div>
                              <?php endif; ?>
                    </div>
                </a>
            </div>
            <?php } ?><?php endif; ?>
        </div>


        <div id="jsonDataContainer">

        </div>
        <script type="text/javascript" >
            //  all variables
            let perc = document.querySelector('.perc');
            let taskIndic = document.querySelector(".indicator");
            let taskIndicLine = document.querySelector(".indicatorTask");
            let task_content = document.querySelector(".task-content");
            let task_ticks = document.querySelector(".task-ticks");
            let sec, totalTask, completedTask = <?php echo count($referrals) ?>;

            let taskData = <?php echo json_encode($data); ?>;
            totalTask =30;

            // append task tiks to html element
             for(let i = 1; i<=30; i++){
                task_ticks.innerHTML += `
                    <span style="--i:${i}; --total: ${totalTask}"></span>
                `;
            }

            /*taskData.forEach((data, index )=> {
                task_ticks.innerHTML += `
                    <span style="--i:${index + 1}; --total: ${totalTask}"></span>
                `;

                if(data.completed == "true"){
                    completedTask++
                }
            });*/

            setInterval(myFunct, 1000);

            function myFunct(){
                let percentage = completedTask / totalTask * 100;
                perc.innerHTML = Math.round(percentage) + "%";
                indicator(Math.round(percentage));
                function indicator(sec){

                    let progress = 180 / totalTask * completedTask;
                    taskIndic.style.background =  `conic-gradient(var(--yellow) ${progress}deg, #000 0deg)`
                    taskIndicLine.style.transform = `rotate(${progress}deg)`;

                    if (totalTask ==  completedTask) {
                        taskIndic.style.background =  `conic-gradient(var(--green) ${progress}deg, #000 0deg)`
                        task_content.style.color = `var(--green)`;
                        taskIndicLine.style.setProperty('--yellow', 'var(--green)');
                    }
                }

            }


        </script>
<?php endif; ?>



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
    <script>

      var options = {
        series: [{
          name: "TM",
          data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
        }],
        chart: {
        height: 200,
        type: 'line',
        foreColor:'#ccc',
        zoom: {
          enabled: true
            }
        },
      dataLabels: {
        enabled: false
      },
      tooltip: {
        theme: 'dark'
    },
      stroke: {
        curve: 'straight'
      },
      title: {
        text: 'TITLE CHART ',
        align: 'left'
      },
      grid: {
        borderColor: "#535A6C",
        row: {
          colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
          opacity: 0.5
        },
      },
      xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
      }
      };

      var chart = new ApexCharts(document.querySelector("#chartsd"), options);
      chart.render();



        var options2 = {
             series: [25,98],
             labels: ['SPLIT ','DIFFICULT INCREASE'],
            chart: {
                type: 'donut',
                height: 200,
                borderColor: "#535A6C",
                foreColor:'#ccc',
            },
            plotOptions: {
                pie: {
                startAngle: -90,
                endAngle: 90,
                offsetY: 10,
                },
            },
            title: {
                text: 'BAROMETER',
                align: 'left'
            },
            dataLabels: {
                enabled: true,
                // formatter: function(val, opts) {
                // // Custom text labels
                // const customLabels = ["Label 1", "Label 2", "Label 3", "Label 4", "Label 5"];
                // return customLabels[opts.seriesIndex];
                // },
                style: {
                fontSize: '14px',
                colors: ['#fff']

                }
            },
            tooltip: {
                theme: 'dark'
            },
            grid: {
                padding: {
                // bottom: -80
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                chart: {
                    width:150
                },
                legend: {
                    position: 'bottom'
                }
                }
            }]
};

var chart2 = new ApexCharts(document.querySelector("#chart2"), options2);
chart2.render();


  </script>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\mcu.focoin.eu\resources\views/user/dashboard.blade.php ENDPATH**/ ?>