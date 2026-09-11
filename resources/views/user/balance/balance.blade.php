
<div class="wrapper">
@include('user.user-dashboard-base')
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
                           $ {{$credit->amount}}
                        </div>
                    </div>
                    <div class="bal-box">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                           EARN FOMO
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                           $ {{$fomo}}
                        </div>
                    </div>
                    <div class="bal-box">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                           DAILY INCOME VENTURE
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                           $ {{$incomeventure}}
                        </div>
                    </div>
                    <div class="bal-box">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                           FREE COIN
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                           $ {{$freecoin}}
                        </div>
                    </div>

                    <div class="bal-box">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                           CASH OUT
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                           $ {{$cashout}}
                        </div>
                    </div>
                    <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                            TRADING VOUCHER
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                           $ {{$trading}}
                        </div>
                    </div>
                    <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                           AVAILABLE TOKEN
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                           {{ number_format($available_token ?? 0, 1) }}
                        </div>
                    </div>
                    <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                           DEPOSIT
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                           $ {{$deposit}}
                        </div>
                    </div>

                     <div class="bal-box">
                        <div class="point">

                        </div>
                        <div class="bal-content ">
                            Escrow TOKEN
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                           $ {{ number_format($escrow_token ?? 0, 1) }}
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
                          $ {{$totalEarning}}
                        </div>
                    </div>

                    <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content">
                            <p>TODAY EARNING</p>
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                           $ {{$todayEarning}}
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
                            <p>TOTAL ACCUMULATED VOLUME BONUS</p>
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                            {{ number_format(($fom_vol_left ?? 0) + ($fom_vol_right ?? 0), 2) }}
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
                            <p>TOTAL VOLUME POINT</p>
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                           {{ number_format($volume_points ?? 0, 0) }}
                        </div>
                    </div>
                    <div class="bal-box ">
                        <div class="point">

                        </div>
                        <div class="bal-content">
                            <p>INCENTIVE BONUS</p>
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                           $ {{ number_format($incentive_bonus ?? 0, 2) }}
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
                            WEEKLY RIGHT & LEFT VOLUME BONUS
                        </div>
                        <div class="bal-num font-weight-bold text-lg">
                           {{ number_format($fom_vol_right ?? 0, 0) }}:{{ number_format($fom_vol_left ?? 0, 0) }}
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
                          SAVING TOKEN
                        </div>
                        <div class="bal-num font-weight-bold text-lg ">
                          {{ number_format($saving_token ?? 0, 2) }} <small style="font-size:0.7rem;font-weight:400;">{{ $token_symbol ?? 'FOCOIN' }}</small>
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


