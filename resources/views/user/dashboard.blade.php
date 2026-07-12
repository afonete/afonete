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
     @include('user.user-dashboard-base')
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

             @if(session('message'))
             <p class="btn btn-success d-flex justify-content-center" >
                 {{ session('message') }}
             </p>
             @endif


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
     @media screen and (min-width: 768px) {
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
            {{-- @dump($venture->amount) --}}
            @php

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

            @endphp

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
       
                     
<style>
    /* Clean Modern Dashboard Grid Styles */
    .dashboard-grid-container {
        width: 100%;
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .dash-section-title {
        font-size: 1.15rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #1f2937;
        margin-top: 2rem;
        margin-bottom: 1rem;
        border-left: 4px solid #3b82f6;
        padding-left: 10px;
    }
    .dash-card {
        border: none !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08) !important;
        transition: all 0.25s ease !important;
        background: #ffffff;
        color: #333333;
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .dash-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
    }
    .dash-card .card-body {
        padding: 1.25rem !important;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }
    .dash-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }
    .dash-card-title {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: rgba(0,0,0,0.55);
        margin: 0;
    }
    .text-white .dash-card-title {
        color: rgba(255, 255, 255, 0.85) !important;
    }
    .dash-card-icon {
        font-size: 1.5rem;
        opacity: 0.85;
    }
    .dash-card-value {
        font-size: 1.6rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 0.25rem;
    }
    .dash-card-subtitle {
        font-size: 0.75rem;
        opacity: 0.8;
        margin: 0;
    }
    .dash-card-footer {
        border-top: 1px solid rgba(0,0,0,0.06);
        padding-top: 0.75rem;
        margin-top: 0.75rem;
        font-size: 0.8rem;
    }
    .text-white .dash-card-footer {
        border-top: 1px solid rgba(255,255,255,0.15);
    }
    
    /* Gradient Color presets */
    .bg-grad-info {
        background: linear-gradient(135deg, #0ea5e9, #0284c7) !important;
        color: #ffffff !important;
    }
    .bg-grad-secondary {
        background: linear-gradient(135deg, #64748b, #475569) !important;
        color: #ffffff !important;
    }
    .bg-grad-dark {
        background: linear-gradient(135deg, #1e293b, #0f172a) !important;
        color: #ffffff !important;
    }
    .bg-grad-success {
        background: linear-gradient(135deg, #10b981, #059669) !important;
        color: #ffffff !important;
    }
    .bg-grad-primary {
        background: linear-gradient(135deg, #6366f1, #4f46e5) !important;
        color: #ffffff !important;
    }
    .bg-grad-danger {
        background: linear-gradient(135deg, #f43f5e, #e11d48) !important;
        color: #ffffff !important;
    }
    .bg-grad-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706) !important;
        color: #ffffff !important;
    }
    
    /* Quick Action Button styling */
    .btn-quick-action {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 3px 6px rgba(0,0,0,0.05);
        border: none;
    }
    .btn-quick-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(0,0,0,0.12);
    }
</style>

@php
    $reserved = db::SELECT("SELECT user, SUM(reserved_token) as token FROM balances WHERE user = :user GROUP BY user", ['user' => $user->id]);
@endphp

<div class="dashboard-grid-container">

    {{-- Package Expired Banner --}}
    @if ($user->has_free_package == 'no' && $package_expired)
    <div class="alert alert-danger mb-4 text-center p-3" role="alert" style="border-radius:12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <i class="fas fa-times-circle mr-2" style="font-size: 1.2rem;"></i>
        <strong>Your package has expired!</strong> Daily ROI income has stopped. Purchase a new package to resume earnings.
        <div class="mt-2">
            <a href="{{ route('user.buypackage') }}" class="btn btn-danger btn-sm font-weight-bold px-3 py-2" style="border-radius: 6px;">
                <i class="fas fa-shopping-cart mr-1"></i> Buy New Package
            </a>
        </div>
    </div>
    @endif

    {{-- SECTION 1: SYSTEM & ACCOUNT STATUS --}}
    <h3 class="dash-section-title">System &amp; Account</h3>
    <div class="row">
        {{-- Card 1: Account Status – NOW SHOWS CURRENT PACKAGE --}}
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="dash-card bg-grad-info text-white">
                <div class="card-body">
                    <div class="dash-card-header">
                        <span class="dash-card-title">Account Status</span>
                        <i class="dash-card-icon fas fa-user-circle"></i>
                    </div>
                    <div class="dash-card-value" style="font-size:1.25rem;">
                        @if(isset($mypackage) && $mypackage)
                            {{ $package_name ?? 'VENTURE' }}
                            <div style="font-size:0.95rem; opacity:0.95; margin-top:4px;">
                                ${{ number_format($package_paid ?? 0, 0) }}
                            </div>
                        @elseif ($user->has_free_package == 'yes')
                            FREE
                        @else
                            {{ strtoupper($user->has_paid_package) }}
                            @if(isset($portfolio_raw) && $portfolio_raw > 0)
                                <div style="font-size:0.95rem; opacity:0.95; margin-top:4px;">${{ number_format($portfolio_raw,0) }}</div>
                            @endif
                        @endif
                    </div>
                    <div class="dash-card-footer">
                        @if(isset($mypackage) && $mypackage)
                            Portfolio: ${{ number_format($package_paid ?? 0,2) }}
                            <span class="badge badge-light text-dark ml-2" style="font-size:10px;">
                                {{ $package_expired ? 'EXPIRED' : 'ACTIVE' }}
                            </span>
                            @if(isset($daysgone))
                                <br><small style="opacity:.85;">Day {{ $daysgone }} / {{ $pkg_duration ?? 100 }}</small>
                            @endif
                        @elseif($user->has_free_package == 'no')
                            Portfolio: {{ $portfolio }}
                        @else
                            <a href="{{ route('user.dashboard.activate') }}" class="text-white" style="text-decoration:underline;">Activate Package →</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: FOMO Earn --}}
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="dash-card bg-grad-primary text-white">
                <div class="card-body">
                    <div class="dash-card-header">
                        <span class="dash-card-title">FOMO Earn</span>
                        <i class="dash-card-icon fas fa-window-restore"></i>
                    </div>
                    <div class="dash-card-value">
                        {{ $gasFees }}
                    </div>
                    <div class="dash-card-footer">
                        Completed actions tracker
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Wallet Balance --}}
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="dash-card bg-grad-success text-white">
                <div class="card-body">
                    <div class="dash-card-header">
                        <span class="dash-card-title">Wallet Balance</span>
                        <i class="dash-card-icon fas fa-wallet"></i>
                    </div>
                    <div class="dash-card-value">
                        Active
                    </div>
                    <div class="dash-card-footer">
                        <a href="/user/user-wallet" class="text-white font-weight-bold" style="text-decoration: underline;">
                            View Wallet <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Cashout Credit / Activate Package --}}
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            @if($user->has_free_package == 'yes')
                <div class="dash-card bg-grad-warning text-white">
                    <div class="card-body">
                        <div class="dash-card-header">
                            <span class="dash-card-title">Package Action</span>
                            <i class="dash-card-icon fas fa-arrow-circle-up"></i>
                        </div>
                        <div class="dash-card-value" style="font-size: 1.3rem;">
                            Inactive
                        </div>
                        <div class="dash-card-footer">
                            <a href="{{ route('user.dashboard.activate') }}" class="btn btn-sm btn-light btn-block font-weight-bold text-dark mt-2" style="border-radius: 6px;">
                                <i class="fas fa-bolt mr-1"></i> Activate Package
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="dash-card bg-grad-dark text-white">
                    <div class="card-body">
                        <div class="dash-card-header">
                            <span class="dash-card-title">Cashout Credit</span>
                            <i class="dash-card-icon fas fa-gift"></i>
                        </div>
                        <div class="dash-card-value">
                            ${{ $credit }}
                        </div>
                        <div class="dash-card-footer d-flex align-items-center justify-content-between">
                            <span>Status:</span>
                            @php
                                $status = $credit_status;
                                $badgeClass = match($status) {
                                    'approved' => 'badge-success',
                                    'rejected' => 'badge-danger',
                                    default => 'badge-warning',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} px-2 py-1 text-uppercase" style="border-radius: 4px;">
                                {{ $status ?: 'pending' }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- SECTION 2: ACTIVE PACKAGE DAILY ROI (Shown for Paid Accounts) --}}
    @if ($user->has_free_package == 'no')
    <h3 class="dash-section-title">Active Package Daily Yields (ROI)</h3>
    <div class="row">
        {{-- ROI Card 1: Daily Income – VALIDATED balances --}}
        <div class="col-12 col-md-4 mb-4">
            <div class="dash-card bg-grad-warning text-white">
                <div class="card-body">
                    <div class="dash-card-header">
                        <span class="dash-card-title">Daily Income</span>
                        <i class="dash-card-icon fas fa-money-bill-wave"></i>
                    </div>
                    <div class="dash-card-value">
                        ${{ $daily_income_per_day }}
                        <small style="font-size:0.75rem; opacity:.9;">/day</small>
                    </div>
                    <div class="dash-card-subtitle mt-1" style="font-size:0.78rem; opacity:.92;">
                        Total earned: <strong>{{ $dailyIncome }}</strong><br>
                        Cashout 25%: <strong>${{ $daily_cashout }}/day</strong> ·
                        Trading 75%: <strong>${{ $daily_trading }}/day</strong>
                    </div>
                    <div class="dash-card-footer d-flex justify-content-between align-items-center">
                        <span>Rate: {{ isset($mypackage) && $mypackage ? '2%' : '0%' }} / day</span>
                        @if($package_expired ?? true)
                            <span class="badge badge-danger">EXPIRED</span>
                        @else
                            <span class="badge badge-success">ACTIVE</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ROI Card 2: Trading Voucher (75%) --}}
        <div class="col-12 col-md-4 mb-4">
            <div class="dash-card bg-grad-info text-white">
                <div class="card-body">
                    <div class="dash-card-header">
                        <span class="dash-card-title">Trading Voucher (75%)</span>
                        <i class="dash-card-icon fas fa-shopping-bag"></i>
                    </div>
                    <div class="dash-card-value">
                        ${{ $daily_trading }} <small style="font-size: 0.8rem; opacity: 0.85;">/ day</small>
                    </div>
                    <div class="dash-card-subtitle mt-1">
                        Accumulated total: <strong>{{ $shooping }}</strong>
                    </div>
                    <div class="dash-card-footer">
                        @if($show_timer && !$package_expired)
                            <div class="d-flex align-items-center justify-content-between">
                                <span>Expiry countdown:</span>
                                <span id="countdown" class="badge badge-dark px-2 py-1 font-weight-bold"></span>
                            </div>
                        @endif
                        @if($renewal_due && !$package_expired)
                            <div class="mt-2">
                                <a href="{{ route('packageRenew') }}" class="btn btn-sm btn-block btn-warning font-weight-bold text-dark" style="border-radius:6px; font-size:11px;">
                                    <i class="fas fa-sync-alt mr-1"></i> Renew Package (#{{ $renewal_number }}/{{ $max_renewals ?? 3 }})
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ROI Card 3: Cashout (25%) – VALIDATED balances --}}
        <div class="col-12 col-md-4 mb-4">
            <div class="dash-card bg-grad-success text-white">
                <div class="card-body">
                    <div class="dash-card-header">
                        <span class="dash-card-title">Cashout Wallet (25%)</span>
                        <i class="dash-card-icon fas fa-wallet"></i>
                    </div>
                    <div class="dash-card-value">
                        ${{ $daily_cashout }} <small style="font-size: 0.8rem; opacity: 0.85;">/ day</small>
                    </div>
                    <div class="dash-card-subtitle mt-1">
                        Accumulated total: <strong>{{ $cashout }}</strong><br>
                        <small style="opacity:.85;">Available to withdraw • Min $10</small>
                    </div>
                    <div class="dash-card-footer d-flex justify-content-between align-items-center">
                        <span>Withdrawable funds</span>
                        <a href="{{ route('user.dashboard.withdraw') }}" class="badge badge-light text-success px-2 py-1 font-weight-bold" style="border-radius:4px; text-decoration:none;">
                            Withdraw →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- SECTION 3: TOKEN WALLETS (Zero Duplicates!) --}}
    <h3 class="dash-section-title">Token Wallets</h3>
    <div class="row">
        {{-- Token Card 1: Locked Token --}}
        <div class="col-12 col-sm-6 col-lg-4 mb-4">
            <div class="dash-card border-left border-secondary" style="border-left-width: 5px !important;">
                <div class="card-body">
                    <div class="dash-card-header">
                        <span class="dash-card-title text-secondary font-weight-bold">Locked Token</span>
                        <i class="dash-card-icon fas fa-lock text-secondary"></i>
                    </div>
                    <div class="dash-card-value">
                        {{ number_format($locked, 0) }}
                    </div>
                    <div class="dash-card-subtitle">
                        Investment tokens locked during package duration.
                    </div>
                    <div class="dash-card-footer text-muted" style="font-size: 0.75rem;">
                        Released to Available Token upon expiration.
                    </div>
                </div>
            </div>
        </div>

        {{-- Token Card 2: Available Token --}}
        <div class="col-12 col-sm-6 col-lg-4 mb-4">
            <div class="dash-card border-left border-success" style="border-left-width: 5px !important;">
                <div class="card-body">
                    <div class="dash-card-header">
                        <span class="dash-card-title text-success font-weight-bold">Available Token</span>
                        <i class="dash-card-icon fas fa-check-circle text-success"></i>
                    </div>
                    <div class="dash-card-value">
                        {{ number_format($available_token, 0) }}
                    </div>
                    <div class="dash-card-subtitle mb-2">
                        Released tokens and earned rewards.
                    </div>
                    <div class="dash-card-footer pt-2">
                        @if($available_token > 0)
                            <a href="{{ route('user.token.available') }}" class="btn btn-sm btn-success btn-block font-weight-bold" style="border-radius: 6px;">
                                Claim to Free Token <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        @else
                            <button class="btn btn-sm btn-block btn-light font-weight-bold text-muted" disabled style="border-radius: 6px;">
                                No Tokens Available
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Token Card 3: Free Token (FOCOIN) --}}
        <div class="col-12 col-lg-4 mb-4">
            <div class="dash-card border-left border-warning" style="border-left-width: 5px !important;">
                <div class="card-body">
                    <div class="dash-card-header">
                        <span class="dash-card-title text-warning font-weight-bold">Free Token (FOCOIN)</span>
                        <i class="dash-card-icon fas fa-coins text-warning"></i>
                    </div>
                    <div class="dash-card-value">
                        {{ number_format($free_token, 0) }}
                    </div>
                    <div class="dash-card-subtitle mb-2">
                        Fully tradeable and transferable FOCOIN tokens.
                    </div>
                    <div class="dash-card-footer pt-2">
                        @if($free_token > 0)
                            <div class="d-flex gap-2">
                                <a href="{{ route('user.token.transfer') }}" class="btn btn-xs btn-dark flex-fill py-1 font-weight-bold" style="border-radius: 4px; font-size: 11px; margin-right: 4px;">Transfer</a>
                                <a href="{{ route('user.token.swap') }}" class="btn btn-xs btn-success flex-fill py-1 font-weight-bold" style="border-radius: 4px; font-size: 11px; margin-right: 4px;">Swap</a>
                                <a href="{{ route('user.token.withdraw') }}" class="btn btn-xs btn-danger flex-fill py-1 font-weight-bold" style="border-radius: 4px; font-size: 11px;">Withdraw</a>
                            </div>
                        @else
                            <button class="btn btn-sm btn-block btn-light font-weight-bold text-muted" disabled style="border-radius: 6px;">
                                0 FOCOIN Balance
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 4: DEPOSITS & LIQUIDITY --}}
    <h3 class="dash-section-title">Deposits &amp; Liquidity</h3>
    <div class="row">
        {{-- Deposit Card 1: Cash & Deposits – Available deposit balance --}}
        <div class="col-12 col-sm-6 col-lg-4 mb-4">
            <div class="dash-card border-left border-primary" style="border-left-width:5px !important;">
                <div class="card-body">
                    <div class="dash-card-header">
                        <span class="dash-card-title text-primary font-weight-bold">Cash &amp; Deposits</span>
                        <i class="dash-card-icon fas fa-university text-primary"></i>
                    </div>
                    <div class="dash-card-value text-primary">
                        ${{ $deposits }}
                    </div>
                    <div class="dash-card-subtitle text-muted mb-2" style="font-size:0.78rem;">
                        Available deposit balance<br>
                        <span class="text-success">Approved – Used = Available</span>
                    </div>
                    <div class="dash-card-footer">
                        <a href="{{ route('mypayments') }}" class="btn btn-sm btn-outline-primary btn-block font-weight-bold" style="border-radius: 6px;">
                            <i class="fas fa-history mr-1"></i> Deposit Records
                        </a>
                        <a href="{{ route('user.dashboard.deposit') }}" class="btn btn-sm btn-primary btn-block font-weight-bold mt-1" style="border-radius: 6px;">
                            <i class="fas fa-plus mr-1"></i> Add Deposit
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Deposit Card 2: Cumulative Cashout – WITH WITHDRAWAL LINK --}}
        <div class="col-12 col-sm-6 col-lg-4 mb-4">
            <div class="dash-card border-left border-danger" style="border-left-width:5px !important;">
                <div class="card-body">
                    <div class="dash-card-header">
                        <span class="dash-card-title text-danger font-weight-bold">Cash Out</span>
                        <i class="dash-card-icon fas fa-wallet text-danger"></i>
                    </div>
                    <div class="dash-card-value text-danger">
                        {{ $cashout }}
                    </div>
                    <div class="dash-card-subtitle text-muted" style="font-size:0.78rem;">
                        Available cashout balance to be withdrawn<br>
                        <span class="text-success">Min withdrawal: $10.00</span>
                    </div>
                    <div class="dash-card-footer">
                        <a href="{{ route('user.dashboard.withdraw') }}" class="btn btn-sm btn-danger btn-block font-weight-bold" style="border-radius:6px;">
                            <i class="fas fa-arrow-circle-up mr-1"></i> Withdraw Now
                        </a>
                        <div class="d-flex justify-content-between mt-2" style="font-size:0.72rem;">
                            <a href="{{ route('user.dashboard.userwithdraw') }}" class="text-muted">Withdrawal history →</a>
                            <span class="text-muted">Fee: 0%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Deposit Card 3: Reserved Tokens --}}
        <div class="col-12 col-lg-4 mb-4">
            <div class="dash-card">
                <div class="card-body">
                    <div class="dash-card-header">
                        <span class="dash-card-title text-info">Reserved Ads</span>
                        <i class="dash-card-icon fas fa-ad text-info"></i>
                    </div>
                    <div class="dash-card-value">
                        100,000
                    </div>
                    <div class="dash-card-footer">
                        Advertising and marketing allocations
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 5: OPERATIONS & QUICK ACTIONS --}}
    <h3 class="dash-section-title">Quick Actions</h3>
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3">
            <a href="{{ route('user.investments') }}" class="btn btn-block btn-success btn-quick-action py-3 text-white">
                <i class="fas fa-cog" style="font-size: 1.1rem;"></i> My Investments
            </a>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <a href="{{ route('user.referral.downline') }}" class="btn btn-block btn-primary btn-quick-action py-3 text-white">
                <i class="fas fa-user" style="font-size: 1.1rem;"></i> My Referrals
            </a>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <a href="#" class="btn btn-block btn-danger btn-quick-action py-3 text-white">
                <i class="fas fa-users" style="font-size: 1.1rem;"></i> My Team
            </a>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <a href="#" class="btn btn-block btn-warning btn-quick-action py-3 text-dark">
                <i class="fas fa-gift" style="font-size: 1.1rem;"></i> Coin Soon
            </a>
        </div>
    </div>

</div>

<script>
    const targetDate = "{{ $expirationDate }}";
    if (typeof startCountdown === 'function') {
        startCountdown(targetDate);
    }
</script>
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
                     <a href="{{route('user.referral.show')}}">
                         <div class="info-box mb-3">
                             <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>
                                 <?php
                                        $referrals=db::SELECT("SELECT * from users where referee_id= :ref",['ref'=>$user->id]);

                                        // $earning = db::SELECT("SELECT user, SUM(earning) as earn FROM balances WHERE user = :user GROUP BY user", ['user' => $userr]);




                                $task=db::SELECT("SELECT * FROM users join activations on(users.email=activations.email) join tasks ON(activations.code=tasks.code)  WHERE users.has_paid_package='ft' and  activations.package='ft' and users.email='$email'");
                                 ?>
                             <div class="info-box-content">
                                 <span class="info-box-text">Referrals</span>
                                 <span class="info-box-number">{{$referals}}</span>
                             </div>

                         </div>
                     </a>

                 </div>
                 <!-- /.col -->

                 <!-- fix for small devices only -->
                 <div class="clearfix hidden-md-up"></div>

                 <div class="col-12 col-sm-6 col-md-3">
                    <a href="{{route('user.task.show')}}">
                     <div class="info-box mb-3">
                         <span class="info-box-icon bg-success elevation-1"><i class="fas fa-list"></i></span>

                         <div class="info-box-content">
                             <span class="info-box-text">Tasks</span>
                             <span class="info-box-number">{{count($task)}}</span>
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
                             <span class="info-box-number text-sm">{{number_format($earnings)}}</span>
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
                            <img src="{{asset('assets/a/img/zoom.png')}}" alt="">
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
            @include('user.chatonline')
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

             @if($have_pending_deposits)
<div id="myModal" class="modal fade show d-block" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-top warning " role="document">
        <div class="modal-content p-4 shadow border rounded " style="background:#27445D; color: #fff;">
            @if (session('success'))
                <div class="text-success mb-2">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="text-danger mb-2">
                    {{ session('error') }}
                </div>
            @endif

            <div class="text-center mb-3">
                <h2 class="text-uppercase fw-bold text-white" style="font-size: 1.4rem;">YOU HAVE PENDING DEPOSIT</h2>
            </div>
            
            <p class="text-light small mb-2">You still have a pending order.</p>

            @if(!empty($have_pending_deposits->comment))
                <div class="alert alert-info text-left small mb-3 p-3" style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); color: #fff; border-radius: 8px;">
                    <strong class="text-white d-block mb-1"><i class="fas fa-comment-dots mr-1 text-warning"></i> Admin Note / Response:</strong>
                    <p class="mb-0 text-light" style="font-size: 0.85rem;">{{ $have_pending_deposits->comment }}</p>
                </div>
            @endif

            <p class="text-light small py-1">If your transaction is not approved, please submit your transaction ID below:</p>
            
            <form class="d-flex justify-content-center align-items-center gap-2 mb-3" method="POST" action="{{route('user.claim')}}">
                @csrf
                <input type="text" 
                  class="form-control me-2" 
                  placeholder="Transaction ID" name="transactionId" 
                  value="{{ $have_pending_deposits->transaction_id ?? '' }}" readonly>
                <button type="submit" class="btn btn-success text-white mx-2">Send</button>
            </form>
            <p class="text-sm mb-3">Please wait for the admin to approve your deposit.</p>
            
            <div class="d-flex justify-content-between align-items-center pt-3" style="border-top: 1px solid rgba(255, 255, 255, 0.15);">
                <a class="btn btn-info btn-sm font-weight-bold px-3 py-2" href="{{ route('mypayments') }}" style="border-radius: 6px;">
                    <i class="fas fa-history mr-1"></i> View Order Status
                </a>
                <a class="btn btn-danger btn-sm font-weight-bold px-3 py-2" href="#" style="border-radius: 6px;"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
                    <i class="fas fa-sign-out-alt mr-1"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>
@endif

                 <div class="row ">
                             <div class="card card-primary card-outline col-lg-4">

                                 <h5 class="card-title">
                                    Refferral id:
                                    <button class="btn btn-default font-weight-bold" type="submit" id="btn2" disabled>
                                         <i class="las la-link" style="font-size: 20px;"></i>
                                          <input type="hidden" value="{{$ref_code}}" id="link1">
<a href="#" style="color:red;text-decoration:none;">
    @if($package!='standard')
  <span style="visibility: visible;">{{$ref_code}}</span>
  @else
  <span style="visibility: visible;">*********</span>
  @endif
</a></h5><br>
                                    </button>

                                            <div class="input-group">
                                            @if($package!='standard')
                                            <!-- value="http://bifonex.com/register?referral={{$ref_code}}" -->
                                                <input type="text" id="link" 
                                                value="{{$baseUrl}}/register?referral={{$ref_code}}"
                                              class="form-control" readonly class="form-control">

                                                <button class="btn btn-default" type="submit" id="btn">
                                                    <i class="las la-link" style="font-size: 20px;"></i>
                                                </button>
                                              @else
                                                <input type="text" id="link"value="http://bifonex.com/register?referral=*******"
                                              class="form-control" readonly class="form-control">@endif

                                            </div>

                                            <p class="card-text text-center py-1">
                                            <i class="fas fa-arrow-circle-right"></i> Share this link and get 500 coin when they activate account!
                                            </p>


                                            <div class="input-group">
                                            @if($package!='standard')
                                               <button class="btn btn-info  "  onclick="copyToClipboard('link-right')">
                                                    Right
                                                </button>
                                                
                                                <input type="text" id="link-right"
                                                value="{{$baseUrl}}/register?referral={{$ref_code}}&side=RIGHT"
                                              class="form-control" readonly class="form-control">

                                                <button class="btn btn-default"  onclick="copyToClipboard('link-right')">
                                                    <i class="las la-link" style="font-size: 20px;"></i>
                                                </button>
                                              @else
                                                <input type="text" id="link-right"value="http://bifonex.com/register?referral=*******"
                                              class="form-control" readonly class="form-control">@endif

                                            </div>


                                            <div class="input-group my-1">
                                            @if($package!='standard')
                                              <button class="btn btn-primary"  onclick="copyToClipboard('link-right')">
                                                    Left
                                                </button>
                                                <input type="text" id="link-left" value="{{$baseUrl}}/register?referral={{$ref_code}}&side=LEFT"
                                              class="form-control" readonly class="form-control">

                                                <button class="btn btn-default" onclick="copyToClipboard('link-left')">
                                                    <i class="las la-link" style="font-size: 20px;"></i>
                                                </button>
                                              @else
                                                <input type="text" id="link-left"value="http://bifonex.com/register?referral=*******"
                                              class="form-control" readonly class="form-control">@endif

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
                                                    <img src="{{asset('assets/a/img/team.png')}}" alt="">
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



                     {{-- start OF LEADER --}}
                 </div>
                 <span style="color: blue;font-size: 20px;"><b>Upcoming Projects</b> </span>

                                 <div class="row">
                     <div class="comingSoon col-md-5">
                          <div class="row my-2">

                                 <div class="col-12 col-sm-6 col-md-6">
                                     <div class="info-box main-upcoming">
                                        <div class="upcoming ">
                                            <div>
                                                <img src="{{asset('assets/a/img/upcoming.png')}}" alt="">
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
                                                <img src="{{asset('assets/a/img/booking.png')}}" alt="">
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
                                                <img src="{{asset('assets/a/img/shopping.png')}}" alt="">
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
                                                <img src="{{asset('assets/a/img/crypto.png')}}" alt="">
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

                         {{-- END OF LEADER --}}



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
    var shareUrl = 'https://www.bifonex.com/register?referral={{$ref_code}}';

    // Open the Facebook share dialog
    window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(shareUrl), '_blank');
  }

  // Function to handle the WhatsApp sharing
    function shareOnWhatsApp() {
      // Replace 'YOUR_SHARE_TEXT' with the desired text to share
      var shareText = encodeURIComponent('Infinite earning: https:www.bifonex.com/register?referral={{$ref_code}}');
      var whatsappURL = 'https://api.whatsapp.com/send?text=' + shareText;
      window.open(whatsappURL, '_blank');
    }

   // Function to handle the Twitter sharing
    function shareOnTwitter() {
      // Replace 'YOUR_SHARE_TEXT' with the desired text to share
      var shareText = 'Infinite earning: https://www.bifonex.com/register?referral={{$ref_code}}';

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
                                                     <h5><b class="text-warning">Members:</b> {{$right}}</h5>

                                                     <p>
                                                     <h4>{{$right_amount}} <small>Vp</small></h4>
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
                                            <div class="card bg-white rounded d-flex justify-content-center align-items-center py-2">
                                                <i class="fas fa-wallet text-white px-3 rounded-lg text-lg text-center py-3" style="background-color:rgb(238, 193, 71);font-size:2em !important"></i>
                                                <h2 class="text-lg text-dark fw-bold py-2">${{ number_format($referral_bonus_totals['total'] ?? 0, 2) }}</h2>
                                                <p class="py-1 px-2 text-center mb-0">Total Referral Bonus</p>
                                                <small class="text-muted text-center px-2">
                                                    {{ $direct_referral_count }} referrals ({{ $active_referral_count }} active)
                                                </small>
                                                <small class="text-success font-weight-bold">
                                                    ${{ number_format($referral_bonus_totals['withdrawable'] ?? 0, 2) }} withdrawable
                                                </small>
                                                <small class="text-warning">
                                                    ${{ number_format($referral_bonus_totals['pending'] ?? 0, 2) }} pending (next Monday)
                                                </small>
                                                <div class="d-flex gap-1 mt-1">
                                                    <a href="{{ route('user.referral.bonus') }}" class="btn btn-xs btn-outline-warning" style="font-size:11px;">
                                                        Bonus & Withdraw
                                                    </a>
                                                    <a href="{{ route('user.referral.downline') }}" class="btn btn-xs btn-outline-info" style="font-size:11px;">
                                                        Downline
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- ── RANK CARD ── --}}
                                        <div class="col mt-3 mt-md-0">
                                            <div class="card bg-white rounded d-flex justify-content-center align-items-center py-2">
                                                <i class="fas fa-trophy text-white px-3 rounded-lg text-lg text-center py-3" style="background-color:rgb(167, 139, 250);font-size:2em !important"></i>
                                                @if($current_rank)
                                                    <h2 class="text-lg text-dark fw-bold py-2">🏆 {{ $current_rank->rank_name }}</h2>
                                                    <p class="py-1 px-2 text-center mb-0">Current Rank</p>
                                                    @if($current_rank->congratulation_image)
                                                        <small class="text-success">Picture available</small>
                                                    @endif
                                                @else
                                                    <h2 class="text-lg text-dark fw-bold py-2">🎯 No rank yet</h2>
                                                    <p class="py-1 px-2 text-center mb-0">Build your network</p>
                                                @endif
                                                @if($next_rank)
                                                    <small class="text-muted">Next: <strong>{{ $next_rank->name }}</strong> · {{ $next_rank->rewardLabel() }}</small>
                                                @endif
                                                <a href="{{ route('user.referral.rank') }}" class="btn btn-xs btn-outline-primary mt-1" style="font-size:11px;">
                                                    View Ranks
                                                </a>
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
                                                     <h5><b class="text-warning">Members:</b> {{$lift}} </h5>

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
                                 {{-- END OF COL-MD-6 --}}
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
                                                                <p> <strong>Direct UVP: </strong>{{$left_direct_uvp}}</p>
                                                                <p> <strong>Indirect UVP: </strong>{{$left_indirect_uvp}}</p>
                                                                <p> <strong>Total Person Team: </strong>0</p>
                                                                <div>
                                                                        <strong>Total Team Ref</strong>
                                                                        <div>
                                                                            <p>VP: <strong>{{$left_direct_uvp+$left_indirect_uvp}}</strong></p>
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
                                                                    <p> <strong>Direct UVP: </strong>{{$right_direct_uvp}} </p>
                                                                    <p> <strong>Indirect UVP: </strong>{{$right_indirect_uvp}} </p>
                                                                    <p> <strong>Total Person Team: </strong>
                                                                        0
                                                                        </p>
                                                                    <div>
                                                                        <strong>Total Team Ref</strong>
                                                                        <div>
                                                                            <p>VP: <strong>{{$right_direct_uvp+$right_indirect_uvp}}</strong></p>
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
                                                                {{$zoneAearning}}
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
                                                                {{$zoneBearning}} Vp
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
                                                            <span>0 $</span>
                                                        </div>
                                                        <p class="text-center p-0 m-0">Week 09 March - 15 March</p>
                                                        <p class="text-center p-0 m-0">Volume Bonus</p>
                                                        <h5 class="description-header" style="color:rgb(238, 193, 71);">0 $</h5>


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
 @if($package!='FT')


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






<!-- <a href="{{route('user.dashboard.events')}}" class="btn btn-primary">all events</a> -->

          <div class="recentEvent col-12 col-sm-6 shadow col-md mx-md-2 my-2 py-3 px-4">

            <div class="recbox">
                <a href="{{route('user.dashboard.events')}}" class="box  d-flex justify-content-between align-items-center py-2  px-5">
                    <div class="font-weight-bold"> <h4 class="my-3 mx-2 font-weight-bold">Recent Events </h4></div>
                    <div>

                        <!-- <div class="date">12/05/2023</div> -->
                        <div class="allevents">all events</div>
                    </div>
                </a>
                @if($events)
               <span class="ml-2 bg bg-primary"> No events reported yet</span>

               @else
           <?php foreach ($events as $event) { ?>
                <div class="recbox">
                <a href="#" class="box  d-flex justify-content-between align-items-center py-2  px-5">
                    <div class="font-weight-bold">{{$event->desc}}</div>
                    <div>
                        <div class="date">{{$event->date_on}}</div>
                         @if($event->status=='pending')
                              <div class="status status-pending">{{$event->status}}</div>
                              @else
                              <div class="status status-verified">{{$event->status}}</div>
                              @endif
                    </div>
                </a>
            </div>
            <?php } ?>@endif
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
@endif



    </div>

 </div>

 </div>

  <script src="{{asset('assets/a/plugins/jquery/jquery.min.js')}}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{asset('assets/a/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/a/plugins/sparklines/sparkline.js')}}"></script>

  <!-- AdminLTE App -->

  <script src="{{asset('assets/a/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
  <script src="{{asset('assets/a/dist/js/adminlte.min.js')}}"></script>
  <script src="{{asset('assets/a/dist/js/adminlte.js')}}"></script>
  <script src="{{asset('assets/a/plugins/chart.js/Chart.min.js')}}"></script>
  <script src="{{asset('assets/a/dist/js/pages/dashboard2.js')}}"></script>
  <script src="{{asset('assets/a/dist/js/tree.js')}}"></script>
  <script src="{{asset('assets/a/plugins/chart.js/Chart.min.js')}}"></script>
  <script src="{{asset('assets/a/plugins/jquery-knob/jquery.knob.min.js')}}"></script>

  <script src="{{asset('assets/a/dist/js/pages/dashboard.js')}}"></script>
  <script src="{{asset('assets/a/plugins/summernote/summernote-bs4.min.js')}}"></script>
  <script src="{{asset('assets/a/plugins/daterangepicker/daterangepicker.js')}}"></script>
  <script src="{{asset('assets/a/plugins/moment/moment.min.js')}}"></script>
  <script src="{{asset('assets/a/dist/js/pages/dashboard3.js')}}"></script>
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
