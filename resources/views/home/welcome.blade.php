<?php

// use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;


$totalUser=User::count();
$totalUser=$totalUser+125;
//7day ago from now
    $startDate = Carbon::now()->subDays(7)->startOfDay();
// Count new users registered in the last 7 days
    $newUsersCount = User::where('created_at', '>=', $startDate)->count();
    $newUsersCount=$newUsersCount+78;
 // Get today's date
    $today = Carbon::now()->startOfDay();
// Count users registered today
    $usersRegisteredTodayCount = User::whereDate('created_at', $today)->count();
     $usersRegisteredTodayCount=$usersRegisteredTodayCount+25;
?>



<div style="width:100%">
<div class="">

    @include('head')
</div>
    
    <!-- About Us Page Section End -->
    
    <!-- Video Section Begin -->
    <section class="hero-section">

        <div class="hero-slider owl-carousel ">
            <div class="hs-item set-bg" data-setbg="{{asset('assets/front/img/hero/big-banner2.jpg')}}">
                <div class="col hero-text hello">
                <h2>Join {{ env('APP_NAME') }}<br> today</h2>

                    <p> ALL IN ONE INNOVATION, AI, WEB3 & BLOCKCHAIN TECHNOLOGY
                    </br> The Platform for Success-Minded People
                    </p>
            </div>
                <div class="hero-btns">
                    <button class="hero-btn registerHome"><a href="{{route('register')}}">JOIN NOW</a></button>
                    <button class="hero-btn login"><a href="{{route('login')}}">Login</a></button>
                </div>
            </div>
            <div class="bg-2">
                <div class="col hero-text">

<style>
 #afo1{
            display:none;
           
        }
    
    @media screen and (max-width:867px){
        #afo1{
            display:block;
            
        }
        
        /* .hero-section{
            height:400px;
            width:100%;
        } */
        
         #afo{
            display:none;
        }
    } 
  @media (max-width: 868px) and (max-height: 851px) {
      .hero-section {
       
        height: 480px;
        /*max-height: 330px;*/
        
      
      }
    }
      @media (max-width: 868px) and (max-height: 700px) {
      .hero-section {
       
        height: 530px;
        /*max-height: 330px;*/
        
      
      }
      .img,#rep,#rep1,.rep,.rep1{
          width:100%;
      }
      .img{
          width:500px;
      }
      
    }
 
</style>
         <div class="hello-content">
            
                   <h3> {{ env('APP_NAME') }} Bifonex has created a unique 
                    and innovative affiliate platform for a global community passionate 
                    about emerging technologies, AI, Web3, and blockchain. 
                    Our ecosystem gives everyone access to innovative solutions and
                     opportunities designed to bring the future of digital technology closer today.</h3>
                   
            </div>
  
                </div>
            </div>



    </section>
    <!-- Hero Section End -->
<div>
        <!-- START OF VIDEO SLIDER -->

        <!-- row -->
        <div class="slide-row ">

            <!-- section title -->

            <div class="main_container" style="width=100%">
                <div class="sections-tittle">
                    <div class="sect2-title head">
                        <h3 class="common-header font-weight-bold">LET'S PUT OUR FAITH INTO ACTION, TAKE RISKS, AND START SOMETHING.</h3>
                        <p class="par mt-3">We all spend a lot of time online, but many people spend it without creating value or earning even $0.10. 
                            It’s time to change the way you think about your time online.
                            Turn your everyday activities into opportunities to earn—whether in dollars, digital coins, or USDT.Turn Your Time Into Money.
                            
                        </p>
                    </div>
                  
                    
                    <div class="benef-part marg row">
                        <div class="semi_benef_part">   
                        <div class="benef-img col-lg-6 pr-4" >
                            <img src="{{asset('assets/front/img/aff2-small.jpg')}}" alt="" style="">
                        </div>

                        <div class="benef-list list-one col-lg-5 pl-5">
                            <div>
                                <ul >
                                    <li>
                                        <p class="par">Earn rewards by watching videos, 
                                            shopping online,
                                             or signing up for exciting services.</p>
                                    </li>

                                    <li>
                                        <p class="par">Earn from anywhere, even from the comfort of your home.</p>
                                    </li>
                                     <li>
                                        <p class="par">24/7 Customer Support to help with your questions and issues.
</p>
                                    </li>
                                    <li>
                                        <p class="par">Earn money or coins by playing fun online games.</p>
                                    </li>
                                    <li>
                                        <p class="par">Get rewarded for using social media! </p>
                                    </li>
                                    <li>
                                        <p class="par">Earn by simply viewing and engaging with ads.</p>
                                    </li>
                                    <li>

                                        <p class="par">Earn rewards by referring friends and building your network</p>
                                    </li>
                                    <li>

                                        <p class="par">Connect with success-minded users in our community chat.</p>
                                    </li>
                                    <a href="{{route('register')}}" class="info-btn  signup">Sign up & start earning</a>
                                </ul>
                            </div>
        
                        </div>
                </div>
                </div>
                </div>
                <!-- place benefit section -->
            </div>
        </div>

    </div>
    <!-- /section title -->


    <!-- sliddings tabs & slick -->
</div>
<br>
<!-- /row -->

<!-- END OF VIDEO SLIDER -->
<!-- Hero Section Begin -->

<!-- About Us Page Section Begin -->
<section class=" marg">
    <div class="main_container">
        <div class="about-page-text ">
            <div class="head">
                <h3  class="common-header font-weight-bold">DO YOU WANT TO LIVE YOUR DREAMS?</h3>
                <p class="par">
                    Our story began with an inner desire to change the world while 
                    creating fair opportunities for all individuals involved in the right way 
                </p>
               
            </div>
             <div class="benef-part row mt-5 d-flex justify-content-center align-items-center">
                <div class="col-lg-5" id="">    <br>
                    <ul class="">
                        <li>
                            <p class="par">Earn from your internet usage — earn up to $5 for eligible tasks you complete.</p>
                           
                        </li>
                        <li>
                            <p class="par">Earn rewards from AI-powered advertisements uploaded by clients.</p>
                        </li>
                        <li>
                            <p class="par">Build passive income opportunities through AI Bots and advertising. </p>
                        </li>
                        <li>
                            <p class="par">Get free space for your online shop and showcase your products or services.</p>
                        </li>
                        <li>
                            <p class="par">Earn up to 15% from direct projects you refer or participate in.</p>
                        </li>
                        <li>
                            <p class="par">Buy and sell products and services online through the platform.</p>
                        </li>
                        <li>
                            <p class="par">Earn retail sales bonuses from eligible transactions.</p>
                        </li>
                        <li>
                            <p class="par">Earn travel points and access hotel benefits.
.</p>
                        </li>
                        <li>
                            <p class="par">Earn potential passive income through staking.
.</p>
                        </li>
                        <br>
                    </ul>
                      
                </div>
              <div class="benef-img mt-0 bo col-lg-6" id="">
                    <img src="{{asset('assets/front/img/dream.jpg')}}" alt="">
               </div> 
            </div>
        </div>
    </div>
</section>


<div class="getBonus">
     <div class="regAds">
        <div class="timeCountDown">   
           <div class="remainTime">
                <div id="days"></div>
                <div id="hours"></div>
                <div id="minutes"></div>
                <div id="seconds"></div>
           </div>
        </div>
        <div class="regBtn">
            <button class="getMore shadow"><a href="{{route('register')}}"><h2>Register <br> now</h2></a></button>
            <a href="{{ route('info')}}" class="more">More Info</a>
        </div>
    </div>
</div>

<script type="text/javascript">
    const days = document.getElementById('days');
    const hours = document.getElementById('hours');
    const minutes = document.getElementById('minutes');
    const seconds = document.getElementById('seconds');

    const  currentDate = new Date();


    function getNextMonday(currentDate){
        const dayOfWeek  = currentDate.getDay();
        const daysUntilMonday = dayOfWeek === 0 ? 1 : 8 - dayOfWeek;
        const nextMonday = new Date(currentDate);
        nextMonday.setDate(currentDate.getDate() + daysUntilMonday);
        nextMonday.setHours(0, 0, 0, 0);   
        return nextMonday;
    }

    function calculateTimeRemaining(){
        const currentDate = new Date();
        const nextMonday = getNextMonday(currentDate);
        const timeRemaining = nextMonday.getTime() - currentDate.getTime();
        // const timeRemaining = nextMonday - currentDate;

        let daysRemaining = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
        const hourRemaining = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minuteRemaining = Math.floor((timeRemaining % (1000 * 60 *60 )) / (1000 * 60));
        const secondRemaining = Math.floor((timeRemaining % (1000 * 60)) / 1000);

        if(daysRemaining  === 0){
            days.style.display = "none"
        }

        return {
            days: daysRemaining +":",
            hours: hourRemaining + ":",
            minutes: minuteRemaining < 10 ? `0${minuteRemaining}:` : minuteRemaining + ":",
            seconds: secondRemaining < 10 ? `0${secondRemaining}` : secondRemaining,
        };
    }
    const updateRemaningTime = ()=>{
        const remainingTime = calculateTimeRemaining();
        if (remainingTime <= 0) {
            clearInterval(countdownInterval);
            document.getElementById('countdown').textContent = 'Countdown expired!';
            return;
        }
        days.innerHTML = `0 : `;
        hours.innerHTML = `0 :`;
        minutes.innerHTML = `0 : `;
        seconds.innerHTML = `0 `;   
     }
    updateRemaningTime();
    setInterval(updateRemaningTime, 1000)

</script>


<!-- sergr -->
<div class="well marg border " style=""> 
    <div class="site-wrap">
        <div class="site-section first-section">
            <div class="main_container">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center box">
                            <span class="icon d-block">
                                <img src="{{asset('assets/front/img/add-user.png')}}" alt="">
                            </span>
                            <h3 class="text-uppercase h4 font-weight-bold">Sign Up</h3>
                            <p  class="par">Feel free to join us. Members from all countries are welcome.
                                 Registration will be absolutely free after the full launch.
.</p>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-center box">
                        <span class="icon d-block">
                                <img src="{{asset('assets/front/img/salary.png')}}" alt="">
                            </span>
                            <h3 class="text-uppercase h4 mb-3 font-weight-bold">Earn</h3>
                            <p class="par">At {{ env('APP_NAME') }} At Bifonex, you can explore multiple ways to earn by completing simple tasks,
                                 clicking and viewing ads, watching videos, playing games, referring new members, and completing offers..</p>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-center box">
                        <span class="icon d-block">
                                <img src="{{asset('assets/front/img/money.png')}}" alt="">
                            </span>
                            <h3 class="text-uppercase h4 mb-3 font-weight-bold">Cashout</h3>
                            <p class="par">Cash out your earnings through multiple payment options, 
                                including USDT and other cryptocurrencies, Perfect Money,, and more.
                                Deposits are currently accepted in cryptocurrency only.
Deposit crypto only</p>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-center box">
                        <span class="icon d-block">
                                <img src="{{asset('assets/front/img/online-shop.png')}}" alt="">
                            </span>
                            <h3 class="text-uppercase h4 mb-3 font-weight-bold">Service</h3>
                            <p class="par">Our Main Services & Project Areas: Blockchain Innovation, AI Ads Marketplace,
                                 Crypto Exchange, E-Commerce,
                                 Digital Marketing, Business Services, Software Solutions, Hotel & Flight Booking</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<br>

    <!-- Video Section End -->

     <!-- START OF COUNTER NUMBER -->

   
<div class="well marg">
    <div class="site-wrap">
        <div class="site-section section-counter"></div>
        <div class="site-section first-section">
            <div class="main_container">
                <div class="row">
                    <div class="col-md-12 text-center" data-aos="fade"> <br>
                        <h2 class="site-section-heading text-uppercase text-center font-secondary">Our
                            Satisified Customers</h2>
                    </div>
                </div>
                
                <div class="row border-responsive">
                    <div class="col-md-2 col-lg-2" data-aos="fade-up" data-aos-delay=""></div>

                    <div class="col-md-3 col-lg-3 mb-4 mb-lg-0 border-right" data-aos="fade-up" data-aos-delay="">
                        <div class="text-center">
                            <span
                                class="flaticon-money-bag-with-dollar-symbol display-4 d-block mb-3 text-primary"></span>
                            <h3 class="text-uppercase h4 mb-3">New Users</h3>
                            <div class="col-lg-6">
                                <div class="counter">
                                    
                                    <span class="number" data-number="{{$newUsersCount}}">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 mb-4 mb-lg-0 border-right" data-aos="fade-up" data-aos-delay="">
                        <div class="text-center">
                            <span
                                class="flaticon-money-bag-with-dollar-symbol display-4 d-block mb-3 text-primary"></span>
                            <h3 class="text-uppercase h4 mb-3">Added Today</h3>
                            <div class="col-lg-6">
                                <div class="counter">
                                    <span class="number" data-number="{{$usersRegisteredTodayCount}}">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="">
                        <div class="text-center">
                            <span
                                class="flaticon-money-bag-with-dollar-symbol display-4 d-block mb-3 text-primary"></span>
                            <h3 class="text-uppercase h4 mb-3">Total Users</h3>
                            <div class="col-lg-6">
                                <div class="counter">
                                    <span class="number" data-number="{{$totalUser}}">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br>
    <!-- END OF COUNTER NUMBER -->
    <!-- start Our mission -->
   <!--<div class="well2" style="background: linear-gradient(135deg, #ff6a00, #ff004f); border-radius: 20px; overflow: hidden;">-->
   <div class="bg-dark mb-5 marg" style="background: linear-gradient(135deg, #000000, #1a1a1a); padding: 20px;">
        <div class="site-wrap">
            <div class="site-section section-counter"></div>
            <div class="site-section first-section">
                <div class="container">
                    <div class=" mb-2">
                        <div class="col-md-12 text-center" data-aos="fade">
                            <br>
                            <h3 class="site-section-heading text-uppercase text-center font-secondary text-white" style="" >
                            {{ strtoupper(env('APP_NAME')) }} platform - The Future Of Internet users

</h3>
                            <h4 style="color: white;">
                               We are an innovative technology company building a large-scale infrastructure
                                project that combines Web3 development, blockchain technology, and artificial intelligence.
                                We believe everyone deserves a fair opportunity to succeed and that there is more than one path to success in life.
<br>
Explore Bifonex for yourself, discover the products that best fit your personal goals, 
and start building your income and business today!
</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--</div>-->

<div class="main_container ">
     <div class="site-wrap">

        <div class="site-section first-section">
            <div class="main_container">
                <div class="row mb-2">
                     <div class="col-md-12 text-center feat-head" data-aos="fade"> <br>
                        <h3 class="text-center common-header font-weight-bold"> MAINLY FEATURE</h3>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Hero Section End -->
</div>

</div>
    <!-- start features section -->
 
    <!-- Hero Section End -->
<div class="main_container">
  <div class="row feat  ">
    <div class="col-md-4 main-ft-left">
      <div class="text-center">
                <table class="table ">  
            <tr >
                <td class="tb_links_img">
                   <img src="https://cdn-icons-png.flaticon.com/128/2300/2300324.png" style="width:28px;height:28px;" alt="Image">
                </td>
                <td>
                     <span><b>Fomo </b><span>
                </td>
            </tr>
             <tr >
                <td class="tb_links_img">
                    <img src="https://cdn-icons-png.flaticon.com/128/2393/2393626.png" style="width:28px;height:28px;" alt="Image">
                </td>
                <td>
                     <span><b> Future Ai Bot</b><span>
                </td>
            </tr>
            
               <tr >
                    <td class="tb_links_img">
                       <img src="https://cdn-icons-png.flaticon.com/128/2108/2108037.png" style="width:28px;height:28px;" alt="Image">
                    </td>
                    <td>
                          <span><b>E_shop</b><span>
                    </td>
                </tr>
                 <tr >
                    <td class="tb_links_img">
                        <img src="{{asset('assets/front/img/reward.png')}}" style="width:28px;height:28px;" alt="">
                    </td>
                    <td>
                        <span><b> Reward Point</b><span>
                    </td>
                </tr>
            
                  <tr >
                    <td class="tb_links_img">
                        <img src="{{asset('assets/front/img/booking.png')}}" style="width:28px;height:28px;" alt="Image">
                    </td>
                    <td>
                          <span><b> Future Booking</b><span>
                    </td>
                </tr>
                 <tr >
                    <td class="tb_links_img">
                        <img  src="{{asset('assets/front/img/fund.png')}}" style="width:28px;height:28px;" alt="Image">
                    </td>
                    <td>
                       <span><b> Money Transfer</b><span>
                    </td>
                </tr>
        </table>
      </div>
    </div>
    <div class="col-md-4">
      <div class="text-center">
         <!--imge https://ik.imagekit.io/earnly/cointiply/how-does-it-work_UpyIk0FbCk.webp?updatedAt=1628449789394-->
        <img src="{{asset('assets/front/img/sm-banner1-feature.jpg')}}" width:"700px" style=" height:259px; border-radius:10px;" alt="Image">
      </div>
    </div>
    <div class="col-md-4  main-ft-right">
      <div class="text-center">
        <table class="table tb_links">  
            <tr >
                <td class="tb_links_img">
                    <img src=https://cdn-icons-png.flaticon.com/128/898/898006.png style="width:28px;height:28px;" alt="Image">
                </td>
                <td>
                    <span><b>Online loan</b><span>
                </td>
            </tr>
             <tr>
                <td class="tb_links_img">
                    <img src="{{asset('assets/front/img/robotic.png')}}" style="width:28px;height:28px;" alt="Image">
                </td>
                <td>
                     <span><b>future chat</b><span>
                </td>
            </tr>
            
               <tr >
                    <td class="tb_links_img">
                        <img src="https://cdn-icons-png.flaticon.com/128/2108/2108037.png" style="width:28px;height:28px;" alt="Image">
                    </td>
                    <td>
                          <span><b>Staking</b><span>
                    </td>
                </tr>
                 <tr >
                    <td class="tb_links_img">
                        <img src=https://cdn-icons-png.flaticon.com/128/898/898006.png style="width:28px;height:28px;" alt="Image">
                    </td>
                    <td>
                        <span><b>Credit/coin</b><span>
                    </td>
                </tr>
            
                  <tr >
                    <td class="tb_links_img">
                         <img src="{{asset('assets/front/img/communities.png')}}" style="width:28px;height:28px;" alt="Image">
                    </td>
                    <td>
                        <span><b>Community</b><span>
                    </td>
                </tr>
                 <tr >
                    <td class="tb_links_img">
                        <img src="{{asset('assets/front/img/exchange.png')}}" style="width:28px;height:28px;" alt="Image">
                    </td>
                    <td>
                       <span><b>Exchange</b><span>
                    </td>
                </tr>
        </table>
      </div>
    </div>
  </div>
  </div>
</div>

<!-- start features section -->
<!-- End features section -->
<style>
    
  .well4 {
    background-color: #333; 
    padding: 0px;
    width:85%;
    margin: auto;
}
/*@media (min-width:876px){*/
/*    #text{*/
/*        margin-right:450px;*/
/*    }*/
   
/*     #text1{*/
/*        margin-right:420px;*/
/*    }*/
/*}*/
.primary-btn1 {
  display: inline-block;
  padding: 10px 5px;
  background-color: yellow; /* Yellow background color */
  color: #333; /* Text color */
  border-radius: 5px; /* Small border radius */
  text-decoration: none;
}

.primary-btn1:hover {
  background-color: #ffd700; /* Hover state color */
}


</style>
      <!--End features section -->
      <br><br><br>
<div>
  <div >
    <div>
      <div class="main_container">
        <div class="row main-club">
          <div class="text-center py-4 club" data-aos="fade">
              
            <h2 class=" text-center font-secondary text-white" id="text1">Do You Want to Become a Founder Club Member?</h2><br>
            <h2 class=" text-uppercase text-center font-secondary">
               <a href="{{route('register')}}" class="club-btn" id="text">Yes, I'd like to sign up</a>
            </h2>
          </div>
        </div>
      </div>
    </div>
  </div>
</div><br>



   <style type="text/css">
        .other-box{
            display: flex;
            justify-content: space-around;
            align-items: center;
            width: 220px;
            height: 50px;
        }
        .other-box i{
            font-size: 25px;
            color: blue;
        }
        .other-box .img_container{
            padding: 3px;
            background-color: #dff9fb;
        }
        .other-text p{
            font-size: 18px;
            text-transform: uppercase;
        }
        @media screen and (max-width: 600px){
            .oneImg{
                margin-left: 20px;
            }
        }

    </style>

<!-- services section -->
<div class="main_container d-flex justify-content-center">
<div class="my-4 bg-white p-3  w-100 w-lg-70 shadow-lg rounded-5">

      

            <div class="d-flex justify-content-center flex-wrap my-2">
                <div class="d-flex">
                     <div class="mx-2 other-box bg-info rounded-3">
                        <div class="oneImg img_container d-flex justify-content-center align-items-center" style="width:25px; height:25px">
                              <img src="{{asset('assets/front/img/booking.png')}}" alt="" style="width: 100%; height: 100%;">
                        </div>
                        <div class="other-text">
                            <p class="text-white" style="font-size: 16px;">Booking system </p>
                        </div>
                    </div>
                    <div class="mx-2 other-box bg-primary rounded-3">
                        <div class="img_container d-flex justify-content-center align-items-center">
                           <i class="las la-utensils"></i>
                        </div>
                        <div class="other-text">
                            <p class="text-white">Restaurant </p>
                        </div>
                    </div>
                </div>

                <div class="d-flex">
                    <div class="mx-2 other-box bg-danger rounded-3">
                        <div class="img_container d-flex justify-content-center align-items-center">
                             <i class="las la-user-astronaut"></i>
                        </div>
                        <div class="other-text">
                            <p class="text-white">Service</p>
                        </div>
                    </div>
                    <div class="mx-2 other-box bg-warning rounded-3">
                        <div class="img_container d-flex justify-content-center align-items-center">
                           <i class="las la-microphone-alt"></i>
                        </div>
                        <div class="other-text">
                            <p>Advertising </p>
                        </div>
                    </div>
                </div>
               
               
            </div>

            <div class="d-flex justify-content-center flex-wrap my-2 gap-5">
            

                <div class="d-flex">
                     <div class="mx-2 other-box bg-success rounded-3">
                        <div class="img_container d-flex justify-content-center align-items-center">
                            <i class="las la-exchange-alt"></i>
                        </div>
                        <div class="other-text">
                            <p class="text-white" style="">Exchange </p>
                        </div>
                    </div>
                    <div class="mx-2 other-box rounded-3" style="background-color: darkblue;">
                        <div class="img_container d-flex justify-content-center align-items-center">
                          <i class="las la-building"></i>
                        </div>
                        <div class="other-text">
                            <p class="text-white">Real estate</p>
                        </div>
                    </div>
                </div>

                <div class="d-flex">
                <div class="mx-2 other-box bg-dark rounded-3">
                        <div class="img_container d-flex justify-content-center align-items-center">
                           <i class="las la-funnel-dollar"></i>
                        </div>
                        <div class="other-text">
                            <p class="text-white">Online loans</p>
                        </div>
                    </div>
                    
                </div>

                <div class="d-flex">
                <div class="mx-2 other-box rounded-3" style="background-color: #0084D1;">
                        <div class="img_container d-flex justify-content-center align-items-center">
                           <i class="las la-funnel-dollar"></i>
                        </div>
                        <div class="other-text">
                            <p class="text-white"> P2P system</p>
                        </div>
                    </div>
                    
                </div>
               
               
            </div>
        </div>
    </div>


    <!-- Hero Section End -->
    </div>

 
@include('home.include.footer')

</div>

<style>
    @media (min-width: 992px) {
  .w-lg-70 {
    width: 70% !important;
  }
}

</style>

