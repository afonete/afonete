
<div class="wrapper">
<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php
use App\Models\Position;
use App\Models\User;
$user = Auth::user();
$name = $user->user;
$position=Position::where('user',$name)->first();
 ?>

 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

 <!-- Content Wrapper. Contains page content -->

 <div class="content-wrapper" id="center-side">

        <div class="content">
            <div class="container-fluid">
                <div class="bal-box-container">
                    <div class="bal-box">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                           CREDIT REWARD
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                           $ <?php echo e($credit->amount); ?>

                        </div>
                    </div>
                    <div class="bal-box">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                           EARN FOMO
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                           $ <?php echo e($fomo); ?>

                        </div>
                    </div>
                    <div class="bal-box">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                           DAILY INCOME VENTURE
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                           $ <?php echo e($incomeventure); ?>

                        </div>
                    </div>
                    <div class="bal-box">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                           FREE COIN
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                           $ <?php echo e($freecoin); ?>

                        </div>
                    </div>

                    <div class="bal-box">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                           CASH OUT
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                           $ <?php echo e($cashout); ?>

                        </div>
                    </div>
                    <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                            TRADING VOUCHER
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                           $ <?php echo e($trading); ?>

                        </div>
                    </div>
                    <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                           TOKEN
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                           $ 0.0
                        </div>
                    </div>
                    <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                           DEPOSIT
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                           $ <?php echo e($deposit); ?>

                        </div>
                    </div>

                     <div class="bal-box">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                            Escrow TOKEN
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                           $ 0.0
                        </div>
                    </div>
                    <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                            HOLDING
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                           $ 0.0
                        </div>
                    </div>

                    <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content">
                            <p>TOTAL EARNING</p>
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                          $ <?php echo e($totalEarning); ?>

                        </div>
                    </div>

                    <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content">
                            <p>TODAY EARNING</p>
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                           $ <?php echo e($todayEarning); ?>

                        </div>
                    </div>
                    <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content">
                            <p>AFFILIATE E-SHOP COMMISSIONS</p>
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                           $ 0.0
                        </div>
                    </div>

                     <div class="bal-box box-blue">
                        <div class="point">

                        </div>
                        <div class="bal-content">
                            RETAIL SALE BONUS
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                            <p class="fw-bold text-danger">0:0</p>
                        </div>
                    </div>
                    <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content">
                            <p>DIRECT UPGRADE BONUS</p>
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                          <i>0:0</i>
                        </div>
                    </div>

                    <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content">
                            <p>REQUIRED RE-ORDER PV</p>
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                            1
                        </div>
                    </div>
                    <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content">
                            <p>STREAMLINE BONUS</p>
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                            $ 0.0000
                        </div>
                    </div>

                     <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content">
                            <p>DIRECT SPONSORSHIP BONUS</p>
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                            $ 0.0000
                        </div>
                    </div>
                    <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content">
                            <p>DOWNLINE MATCHING BONUS OR </p>
                            <p>AFFILIATE BONUS</p>
                        </div>
                       <div class="bal-num font-weight-bold text-lg">
                            0.0
                        </div>
                    </div>

                    <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content">
                            <p>SHOP BONUS</p>
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                           $ 0.0000
                        </div>
                    </div>
                    <div class="bal-box bg-danger">
                        <div class="point">

                        </div>
                        <div class="bal-content text-white">
                           SHOP MATCHING BONUS
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                            $ 0.0000
                        </div>
                    </div>

                     <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content">
                            <p>TOTAL MONTHLY PURCHASE PV</p>
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                           $ 0.0000
                        </div>
                    </div>
                    <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content">
                            <p>INCOME WALLET</p>
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                            $ 0.00
                        </div>
                    </div>

                    <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content">
                            <p>SHOPPING WALLET</p>
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                            soon
                        </div>
                    </div>
                    <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content">
                            <p>REWARD COMMISSION & RANK</p>
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                           $ 0.00
                        </div>
                    </div>

                     <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content">
                            <p>ORDER WALLET</p>
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                            $ 0.00
                        </div>
                    </div>
                       <div class="bal-box box">
                        <div class="point">

                        </div>
                        <div class="bal-content">
                            <p>MERCHANT & BUY</p>
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                             0:0
                        </div>
                    </div>
                    <div class="bal-box ">

                        <div class="bal-content">
                            DIRECT SPONSORED LEFT & RIGHT
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                            0:0
                        </div>
                    </div>
                    <div class="bal-box ">
                       <br>
                        <div class="bal-content">
                            ACCUMULATED TOTAL LEFT PV'S & RIGHT
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                           0:0
                        </div>
                    </div>

                     <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content">
                            REWARD
                            <!-- <p>SPLIT BAROMETER 25% DIFFICULTY INCREASEBAROMETER 98%</p> -->
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                           0.00$
                        </div>
                    </div>
                       <div class="bal-box box-blue">
                        <div class="point">

                        </div>
                        <div class="bal-content">
                            <p>ROYAL LEADER BONUS</p>
                        </div>

                        <div class="bal-num font-weight-bold text-lg">
                            0.00$
                         </div>

                    </div>
                     <div class="bal-box box-lightBlue">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                            TRAVELLING POINT
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                            0:0
                        </div>
                    </div>




                    <div class="bal-box">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                          SAVING
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                          <p>0:0</p>
                        </div>
                    </div>


                    <div class="bal-box">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                          DIRECT ADS COMMISSION
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                          <p>0:0</p>
                        </div>
                    </div>


                    <div class="bal-box">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                          INCENTIVE BONUS
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                          <p>0:0</p>
                        </div>
                    </div>

                    <div class="bal-box">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                          TEAM BUILDING
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                          <p>0:0</p>
                        </div>
                    </div>
                    <div class="bal-box">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                          TEAM MAGIC BONUS
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                          <p>0:0</p>
                        </div>
                    </div>

                    <div class="bal-box">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                          P.POOL REFFERAL REWARDS
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                          <p>0:0</p>
                        </div>
                    </div>




                    <div class="bal-box">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                          SAVING
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                          <p>0:0</p>
                        </div>
                    </div>
                    <div class="bal-box box-blue">
                        <div class="point">

                        </div>
                        <div class="bal-content ">

                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                          <p></p>
                        </div>
                    </div>





                </div>

        </div>
 </div>

</div>

<script>
document.addEventListener('livewire:load', function() {

// $('#exampleModal').modal('show');

})
</script>


<?php /**PATH C:\xampp\htdocs\KANANI\BIFONEX\mcu.focoin.eu\resources\views/user/balance/balance.blade.php ENDPATH**/ ?>