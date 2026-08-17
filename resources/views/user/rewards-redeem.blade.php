<!DOCTYPE html>
{{-- Self-healing data guard: if this view is rendered by a stale controller
     (or the ancient view-only closure) WITHOUT its variables, load them here
     so the dynamic boxes and live balance always render. --}}
@php
    if (!isset($options) || !isset($volumePoints)) {
        try {
            \App\Models\FomRedeemOption::ensureTableAndData();
            $__u = \Illuminate\Support\Facades\Auth::user();
            $volumePoints = $volumePoints ?? ($__u ? (float) \App\Models\ChartAccount::where('user_id', $__u->id)->where('acc_type', 'VOLUME_POINT')->sum('amount') : 0.0);
            $options = $options ?? \App\Models\FomRedeemOption::where('is_active', true)->orderBy('sort_order')->orderBy('points_required')->get();
            $myRedemptions = $myRedemptions ?? ($__u ? \App\Models\FomRedeemLog::where('user_id', $__u->id)->orderByDesc('redeemed_at')->limit(10)->get() : collect());
        } catch (\Throwable $e) {
            $volumePoints = $volumePoints ?? 0.0;
            $options = $options ?? collect();
            $myRedemptions = $myRedemptions ?? collect();
        }
    }
@endphp
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* General Styling */
    .content-wrapper {
      background-color: #f9f9f9;
      padding: 20px;
    }

    button {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s, transform 0.3s;
    }

    button:hover {
      background-color: #0056b3;
      transform: scale(1.05);
    }

    .card {
      border: 1px solid #e0e0e0;
      border-radius: 10px;
      transition: box-shadow 0.3s ease-in-out;
      animation: fadeInUp 0.5s ease-out;
    }

    .card:hover {
      box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 768px) {
      .col-md-4,
      .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
      }

      button {
        width: 100%;
      }
    }
    .usd {
  background-color: darkblue;
  color: white;
  border-radius: 0 0 20px 20px;
  width: 90%;
  height: 130px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.usd h2 {
  font-size: 35px;
  margin: 0;
}

.usd h3 {
  font-size: 25px;
  color: white;
  margin: 0;
}

.card {
  max-width: 250px;
  margin: 0 auto;
}

.btn {
  padding: 10px 20px;
  transition: all 0.3s ease;
}

.btn:hover {
  background-color: white;
  color: #00eaff;
  border: 2px solid #00eaff;
}


/* Additional Styling on ads banner */
.rewards-banner {
    min-height: 200px;
    margin-top: 10px;
    background-color: darkblue;
}

.rewards-content {
    flex: 1;
    min-width: 300px;
}

.rewards-image {
    flex: 1;
    min-width: 300px;
    display: flex;
    justify-content: center;
}

@media (max-width: 768px) {
    .rewards-banner {
        text-align: center;
    }
    
    .rewards-content, .rewards-image {
        flex: 100%;
    }
}


.bounce-animation {
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-20px);
    }
}


/* <!-- banner section --> */

.banner-card {
    transition: all 0.3s ease;
}

.banner-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.banner-content {
    flex: 1;
}

.banner-image {
    flex: 0 0 auto;
}

@media (max-width: 768px) {
    .banner-card {
        text-align: center;
    }
}

.bounce-animation {
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-15px);
    }
}



/* ── Ads marketplace (design repair) ─────────────────────────── */
.ads-card {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    border: 1px solid #eef0f3;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.ads-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
}

.ads-card-header {
    background: linear-gradient(45deg, #0d6efd, #0a4dad);
    color: #fff;
    padding: 12px 16px;
    font-weight: 600;
    font-size: 15px;
}

.promo-row {
    border-radius: 12px;
}

.vacation-purchase {
    padding: 14px 0;
}

  </style>
</head>

<body>
  @include('user.user-dashboard-base')

  <div class="content-wrapper py-3">
    <div class="mx-3">

    <!-- ads banner div -->
  <div class="rewards-banner d-flex flex-wrap justify-content-between align-items-center gap-4 p-4 rounded shadow-sm">
      <div class="rewards-content d-flex flex-column align-items-center text-center">
            <h2 class="fw-bold mb-2 text-white">INVITE FRIENDS TO EARN</h2>
            <h2 class="mb-4" style="color: orange;">$1,000.00 + 25% COMMISSION</h2>
            <button class="btn btn-lg mb-2" style="background: linear-gradient(45deg, #006400, #228B22); color: white; border: none;">
            Sign up & Earn
        </button>

            <div>
                <span class="text-muted">Referral Terms & Conditions</span>
            </div> 
     </div>

        <div class="rewards-image">
            <img src="{{asset('image/Ads1.png')}}" alt="Rewards Banner" class="img-fluid bounce-animation" style="max-width: 200px;">
        </div>

</div>

<!-- banner section -->
<div class="row">
    <div class="col-md-6 g-3">
    <div class="banner-card d-flex flex-column flex-md-row align-items-center justify-content-between p-4 rounded shadow-sm h-100" style="background: linear-gradient(45deg, #00008B, #000080); color: white;">

            <div class="banner-content  text-md-start mb-4 mb-md-0">
                <h2 class="fw-bold d-flex text-white">GET YOUR <span class="fw-bold ms-2" style="color:orange;">$1,000.00</span></h2>
                <h2 class="fw-bold mb-2 text-white">REFERRAL REWARDS</h2>
                <span class="d-block small mb-1 text-muted">
                    Every friends you Invite, will get your $1,000.00,
                    the more you Invite more you will get!
                </span>
                <a href="" class="btn btn-outline-primary">Please see the details</a>
            </div>
            <div class="banner-image">
                <img src="{{asset('image/ad2.png')}}" alt="Hello" class="img-fluid bounce-animation" style="max-width: 200px;">
            </div>
        </div>
    </div>

    <div class="col-md-6 g-3">
    <div class="banner-card d-flex flex-column flex-md-row align-items-center justify-content-between p-4 rounded shadow-sm h-100" style="background: linear-gradient(45deg, #00008B, #000080); color: white;">

            <div class="banner-content  text-md-start mb-4 mb-md-0">
                <h2 class="fw-bold text-white d-flex ">GET YOUR <span class="ms-2 fw-bold" style="color:orange;"> $1,000.00</span></h2>
                <h2 class="fw-bold mb-2 text-white">COMMISSION REWARDS</h2>
                <span class="d-block small mb-1 text-muted">
                    You will receive commission rewards every time your friends
                   place wager based on the games
                </span>
                <a href="" class="btn btn-outline-primary">Please see the details</a>
            </div>
            <div class="banner-image">
                <img src="{{asset('image/ads3.png')}}" alt="Hello" class="img-fluid bounce-animation" style="max-width: 200px;">
            </div>
        </div>
    </div>
</div>


<!-- second section of banner -->
<div>
    <div class="row ">
            <div class="col-md-9">
                   
                        <!-- First column content -->
                         <div class="row">
                            <div class="col-md-6 g-3 border p-5 bg-dark" style="background:; border-radius: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                <div class="d-flex flex-column">
                                    <div class="d-flex justify-content-between mb-3">
                                        <div>
                                            <h2 class="fw-bold text-white">20% Coupon</h2>
                                            <span class="text-light">Rewards Center</span>
                                            <span class="d-block text-light">Remaining: 300</span>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                        <span class="d-block fw-bold text-white">Sport</span>
                                        <span class="text-light">Validity Period: 2025/10/6</span>
                                        </div>

                                        <div class="text-end">
                                            <h2 class="text-white fw-bold">300</h2>
                                            <span class="d-block text-light">USDT</span>
                                            <span class="text-danger">Expired</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 g-3 border p-5 bg-dark" style="background:; border-radius: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                <div class="d-flex flex-column">
                                    <div class="d-flex justify-content-between mb-3">
                                        <div>
                                            <h2 class="fw-bold text-white">20% Coupon</h2>
                                            <span class="text-light">Rewards Center</span>
                                            <span class="d-block text-light">Remaining: 300</span>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                        <span class="d-block fw-bold text-white">Sport</span>
                                        <span class="text-light">Validity Period: 2025/10/6</span>
                                        </div>

                                        <div class="text-end">
                                            <h2 class="text-white fw-bold">300</h2>
                                            <span class="d-block text-light">USDT</span>
                                            <span class="text-danger">Expired</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 g-3 border p-5 bg-dark" style="background:; border-radius: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                <div class="d-flex flex-column">
                                    <div class="d-flex justify-content-between mb-3">
                                        <div>
                                            <h2 class="fw-bold text-white">20% Coupon</h2>
                                            <span class="text-light">Rewards Center</span>
                                            <span class="d-block text-light">Remaining: 300</span>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                        <span class="d-block fw-bold text-white">Sport</span>
                                        <span class="text-light">Validity Period: 2025/10/6</span>
                                        </div>

                                        <div class="text-end">
                                            <h2 class="text-white fw-bold">300</h2>
                                            <span class="d-block text-light">USDT</span>
                                            <span class="text-danger">Expired</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 g-3 border p-5 bg-dark" style="background:; border-radius: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                <div class="d-flex flex-column">
                                    <div class="d-flex justify-content-between mb-3">
                                        <div>
                                            <h2 class="fw-bold text-white">20% Coupon</h2>
                                            <span class="text-light">Rewards Center</span>
                                            <span class="d-block text-light">Remaining: 300</span>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="d-block fw-bold text-white">Sport</span>
                                            <span class="text-light">Validity Period: 2025/10/6</span>
                                        </div>

                                        <div class="text-end">
                                            <h2 class="text-white fw-bold">300</h2>
                                            <span class="d-block text-light">USDT</span>
                                            <span class="text-danger">Expired</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                         </div>
            </div>                 
            
            <div class="col-md-3 ">
            

                            <div class="col-md-12 mt-2 border p-3 rounded shadow-sm" style="background-color: rgb(109 40 217); border-radius: 15px !important;">
                                <h4 class="text-white fw-bold mb-2">Rewards Center</h4>
                                <span class="text-light d-block mb-4" style="font-size: 14px;">
                                    Earn Volume Points when your direct referrals buy FOM Licence Miner Packages — then swap them for USDT!
                                </span>
                                <h2 class="display-4 text-white fw-bold ">{{ number_format($volumePoints ?? 0) }}</h2>
                                <span class="text-light d-block mb-2">TOTAL VOLUME POINT</span>
                                <a href="{{ route('user.fom-referral') }}" class="btn btn-primary px-4 py-2 mx-auto d-block" style="border-radius: 15px; font-size: 16px; width: fit-content;">
                                    <i class="fas fa-network-wired me-2"></i> My FOM Referrals
                                </a>
                            </div>



                            
                            <div class="col-md-12 mt-2 border p-3  rounded shadow-sm" style="background-color: rgb(194 65 12); border-radius: 15px !important;">
                                <h4 class="text-white fw-bold mb-2">Refer a friend</h4>
                                <span class="text-light d-block mb-4" style="font-size: 14px;">
                                Invite friends to get up to 9,000 USDT
                                </span>
                                <!-- <h2 class="display-7 text-white fw-bold">5BFH3FH7 <i class="fas fa-copy" onclick="copyToClipboard('5BFH3FH7')" style="cursor: pointer; font-size: 0.8em;" title="Copy referral code"></i></h2> -->
                                <span class="text-light d-block mb-2">Your referral code </span>
                            </div>  
                            
                            <div class="col-md-12 mt-2 border p-3 rounded shadow-sm" style="background-color: rgb(13 148 136); border-radius: 15px !important;">
                                <h4 class="text-white fw-bold mb-2">Web3 Wallet</h4>
                                <h4 class="text-white mb-4" style="font-size: 14px;">
                                  Wallet
                                </h4>
                                <span class="text-light d-block mb-4" style="font-size: 14px;">
                                    The gateway to multi-chain ecosystem for global crypto users
                                </span>
                                <button class="btn btn-primary px-4 py-2 mx-auto d-block" style="border-radius: 15px; font-size: 16px;">
                                    <i class="fas fa-download me-2"></i> Download Now
                                </button>
                            </div>
                        
                        </div>
                    
                </div>
    
</div>


    <!-- points division -->
      <div class="row my-3">
        <!-- Card 1 -->
        <div class="col-md-3 my-2">
          <div class="card bg-white rounded p-4 shadow-sm h-100">
            <h1 class="text-primary mb-2">+10 <span class="fs-4 text-secondary">Points</span></h1>
            <h3 class="h5 mb-3">Daily Deposit</h3>
            <span class="text-muted mb-4" style="font-size:13px;" >Complete a valid deposit of any amount.</span>
            <button class="btn px-4" data-bs-toggle="tooltip" title="Deposit to earn daily points" style="border:2px solid darkblue; font-size: 16px; border-radius: 10px;" >
              <i class="fas fa-dollar-sign"></i> Deposit Now
            </button>
          </div>
        </div>
        <!-- Card 2 -->
        <div class="col-md-3 my-2">
          <div class="card bg-white rounded p-4 shadow-sm h-100">
            <h1 class="text-primary mb-2">+10 <span class="fs-4 text-secondary">Points</span></h1>
            <h3 class="h5 mb-3">Daily Trade</h3>
            <span class="text-muted mb-4" style="font-size:13px;" >Reach 500 USDT in volume</span>
            <button class="btn px-4" data-bs-toggle="tooltip" title="Trade to earn daily points" style="border:2px solid darkblue; font-size: 16px; border-radius: 10px;" >
              <i class="fas fa-chart-line"></i> Trade Now
            </button>
          </div>
        </div>
        <!-- Card 3 -->
        <div class="col-md-3 my-2">
          <div class="card bg-white rounded p-4 shadow-sm h-100">
            <h1 class="text-primary mb-2">+10 <span class="fs-4 text-secondary">Points</span></h1>
            <h3 class="h5 mb-3">Merchant Signup</h3>
            <span class="text-muted mb-4" style="font-size:13px;" >Reach 10 merchants on board</span>
            <button class="btn px-4" data-bs-toggle="tooltip" title="Invite merchants to earn points" style="border:2px solid darkblue; font-size: 16px; border-radius: 10px;" >
              <i class="fas fa-users"></i> Invite Now
            </button>
          </div>
        </div>
        <!-- Card 4 -->
        <div class="col-md-3 my-2">
          <div class="card bg-white rounded p-4 shadow-sm h-100">
            <h1 class="text-primary mb-2">+20 <span class="fs-4 text-secondary">Points</span></h1>
            <span class="text-muted mb-4" style="font-size:13px;" >Complete a minimum of 100 USDT Deposit</span>
            <button class="btn px-4" data-bs-toggle="tooltip" title="Deposit 100 USDT to earn points" style="border:2px solid darkblue; font-size: 16px; border-radius: 10px;" >
              <i class="fas fa-wallet"></i> Trade Now
            </button>
          </div>
        </div>
        <!-- Card 5 -->
        <div class="col-md-3 my-2">
          <div class="card bg-white rounded p-4 shadow-sm h-100">
            <h1 class="text-primary mb-2">+5 <span class="fs-4 text-secondary">Points</span></h1>
            <span class="text-muted mb-4" style="font-size:13px;" >Invite friends to sign up</span>
            <button class="btn px-4" data-bs-toggle="tooltip" title="Invite friends to earn points" style="border:2px solid darkblue; font-size: 16px; border-radius: 10px;" >
              <i class="fas fa-user-friends"></i> Invite Now
            </button>
          </div>
        </div>
      </div>

      <!-- Rewards Section: Volume Point → USDT (admin-configurable) · v2 dynamic -->
      @if(session('success'))
          <div class="alert alert-success font-weight-bold shadow-sm" style="border-radius:10px;">
              <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
          </div>
      @endif
      @if(session('error'))
          <div class="alert alert-danger font-weight-bold shadow-sm" style="border-radius:10px;">
              <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
          </div>
      @endif

      <div class="row my-3">
        @forelse(($options ?? collect()) as $opt)
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card bg-white" style="border-radius: 20px; overflow: hidden; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                    <div class="usd">
                        <h2 class="font-weight-bold text-center my-2 text-white">{{ number_format((float) $opt->usdt_amount, ((float) $opt->usdt_amount == (int) $opt->usdt_amount) ? 0 : 2) }}</h2>
                        <h3 class="text-center my-1">USDT</h3>
                    </div>
                    <div class="p-3 text-center">
                        <span class="text-center" style="font-size: 14px; color: black;">USDT Bonuses <i class="fas fa-question-circle"></i></span>
                        <h1 style="font-size: 20px; color: black; margin: 10px 0;">{{ number_format((float) $opt->points_required) }} Points</h1>
                        @if(($volumePoints ?? 0) >= (float) $opt->points_required)
                            <form action="{{ route('user.rewards.redeem') }}" method="POST" onsubmit="return confirm('Redeem {{ number_format((float) $opt->points_required) }} Volume Points for {{ number_format((float) $opt->usdt_amount, 2) }} USDT to your Cashout balance?');">
                                @csrf
                                <input type="hidden" name="option_id" value="{{ $opt->id }}">
                                <button type="submit" class="btn mt-1 px-5" style="background-color: #00eaff; color: white; font-size: 16px; border-radius: 10px; border: none;">Redeem</button>
                            </form>
                        @else
                            <button type="button" class="btn mt-1 px-5" disabled title="You need {{ number_format((float) $opt->points_required) }} Volume Points" style="background-color: #94a3b8; color: white; font-size: 16px; border-radius: 10px; border: none; cursor: not-allowed;">Redeem</button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-4">No redemption options are available right now.</div>
        @endforelse
      </div>

      {{-- My recent redemptions --}}
      @if(isset($myRedemptions) && $myRedemptions->isNotEmpty())
          <div class="card p-3 mb-4" style="border-radius: 14px;">
              <h5 class="font-weight-bold mb-3"><i class="fas fa-history mr-2 text-primary"></i>My Recent Redemptions</h5>
              <div class="table-responsive">
                  <table class="table table-sm mb-0 text-center">
                      <thead class="thead-light"><tr><th>Date</th><th>Points Spent</th><th>USDT Received</th></tr></thead>
                      <tbody>
                          @foreach($myRedemptions as $r)
                              <tr>
                                  <td><small>{{ \Carbon\Carbon::parse($r->redeemed_at)->format('Y-m-d H:i') }}</small></td>
                                  <td class="font-weight-bold text-danger">-{{ number_format((float) $r->points_spent) }}</td>
                                  <td class="font-weight-bold text-success">+{{ number_format((float) $r->usdt_received, 2) }} USDT</td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
              </div>
          </div>
      @endif

      {{-- ════════════ Advertising Marketplace (design repaired) ════════════ --}}
      <div class="mt-4 mb-2">
          <h4 class="fw-bold mb-1 text-primary"><i class="fas fa-bullhorn me-2"></i>Advertising Marketplace</h4>
          <span class="text-muted d-block mb-3" style="font-size: 13px;">
              Promote your business across the Bifonex platform with the advertising products below.
          </span>
      </div>

      @php
          $adProducts = [
              ['icon' => 'fa-th',            'title' => 'AdGrid Ads',               'placeholder' => '1 day (s) - $0.50'],
              ['icon' => 'fa-mouse-pointer', 'title' => 'Paid To Click Ads',        'placeholder' => '500 Credits - $0.75'],
              ['icon' => 'fa-thumbtack',     'title' => 'Fixed PTC Advertisements', 'placeholder' => '20 Seconds - $80.00'],
              ['icon' => 'fa-sign-in-alt',   'title' => 'Login Ads',                'placeholder' => '1 day (s) - $1.00'],
              ['icon' => 'fa-user-plus',     'title' => 'Paid To Signup Offers',    'placeholder' => '5 Credits - $1.00'],
              ['icon' => 'fa-font',          'title' => 'Featured Text Ads',        'placeholder' => '100000 Credits - $1.00'],
              ['icon' => 'fa-image',         'title' => 'Banner Ads',               'placeholder' => '1000 Credits - $0.02'],
              ['icon' => 'fa-link',          'title' => 'Featured Link Ads',        'placeholder' => '1 Month (s) - $3.00'],
          ];
      @endphp

      <div class="row g-3">
          @foreach($adProducts as $ad)
              <div class="col-lg-3 col-md-4 col-sm-6">
                  <div class="ads-card h-100 bg-white d-flex flex-column">
                      <div class="ads-card-header d-flex align-items-center">
                          <i class="fas {{ $ad['icon'] }} me-2"></i>
                          <span>{{ $ad['title'] }}</span>
                      </div>
                      <div class="card-body p-4 d-flex flex-column justify-content-between">
                          <form onsubmit="return false;">
                              <div class="mb-3">
                                  <input type="text"
                                         class="form-control form-control-lg"
                                         placeholder="{{ $ad['placeholder'] }}"
                                         style="border: 2px solid #dee2e6; font-size: 15px;">
                              </div>
                              <button type="button" class="btn btn-primary w-100 py-2 fw-bold d-flex align-items-center justify-content-center">
                                  <i class="fas fa-shopping-cart me-2"></i>
                                  Buy Now!
                              </button>
                          </form>
                      </div>
                  </div>
              </div>
          @endforeach

          {{-- Bundle offer (full width) --}}
          <div class="col-12">
              <div class="ads-card bg-white p-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                  <div>
                      <h5 class="fw-bold mb-2"><i class="fas fa-box-open me-2 text-primary"></i>Small Package - $5.00</h5>
                      <ul class="mb-0 text-muted" style="font-size: 14px;">
                          <li>5000 Paid To Click Credits</li>
                          <li>50000 Banner Ad Credits</li>
                      </ul>
                  </div>
                  <button type="button" class="btn btn-primary py-2 px-4 fw-bold flex-shrink-0 d-flex align-items-center justify-content-center">
                      <i class="fas fa-shopping-cart me-2"></i>
                      Buy Now!
                  </button>
              </div>
          </div>
      </div>

      {{-- ════════════ Advertising promo rows ════════════ --}}

      {{-- Vacation Days --}}
      <div class="row promo-row my-4 g-3 bg-white shadow-sm py-3 px-4 mx-0 align-items-center">
          <div class="col-md-6">
              <h4 class="text-primary mb-3"><i class="fas fa-umbrella-beach me-2"></i>Vacation Days</h4>
              <span class="text-muted small fw-bold">
                  Everyone needs a break! Purchase vacation days on demand and easily enable vacation mode in your settings.
              </span>
          </div>
          <div class="col-md-6">
              <div class="vacation-purchase rounded-3 bg-light">
                  <form onsubmit="return false;" class="mb-0">
                      <div class="input-group mb-2 w-75 mx-auto">
                          <input type="number"
                                 class="form-control form-control-lg"
                                 placeholder="15 Day (s)"
                                 min="1"
                                 max="30"
                                 style="border: 2px solid #dee2e6;">
                          <span class="input-group-text fw-bold">$12.75</span>
                      </div>
                      <button type="button" class="btn btn-primary py-2 px-4 fw-bold d-flex align-items-center justify-content-center mx-auto">
                          <i class="fas fa-shopping-cart me-2"></i>
                          Buy Now!
                      </button>
                  </form>
              </div>
          </div>
      </div>

      {{-- Paid To Click Ads tiers --}}
      <div class="row promo-row my-4 g-3 bg-white shadow-sm py-3 px-4 mx-0 align-items-center">
          <div class="col-md-6">
              <h4 class="text-primary mb-3"><i class="fas fa-mouse-pointer me-2"></i>Paid To Click Ads</h4>
              <div class="mb-2">
                  <button type="button" class="btn btn-sm btn-success rounded mb-2 active">MICRO</button>
                  <button type="button" class="btn btn-sm btn-primary rounded mb-2">MIN</button>
                  <button type="button" class="btn btn-sm btn-primary rounded mb-2">MONO</button>
                  <button type="button" class="btn btn-sm btn-primary rounded mb-2">MACRO</button>
                  <button type="button" class="btn btn-sm btn-primary rounded mb-2">STANDARD</button>
                  <button type="button" class="btn btn-sm btn-primary rounded mb-2">MEGA</button>
                  <button type="button" class="btn btn-sm btn-primary rounded mb-2">BAZAR AD</button>
              </div>
              <span class="text-muted small fw-bold d-block">10 Second Exposure, Value: $0.0002</span>
              <span class="text-muted small fw-bold d-block">(1000 Credits = 1000 Clicks)</span>
          </div>
          <div class="col-md-6">
              <div class="vacation-purchase rounded-3 bg-light">
                  <form onsubmit="return false;" class="mb-0">
                      <div class="input-group mb-2 w-75 mx-auto">
                          <input type="number"
                                 class="form-control form-control-lg"
                                 placeholder="1000 Credits"
                                 min="1"
                                 style="border: 2px solid #dee2e6;">
                          <span class="input-group-text fw-bold">$0.20</span>
                      </div>
                      <button type="button" class="btn btn-primary py-2 px-4 fw-bold d-flex align-items-center justify-content-center mx-auto">
                          <i class="fas fa-shopping-cart me-2"></i>
                          Buy Now!
                      </button>
                  </form>
              </div>
          </div>
      </div>

      {{-- Banner Ads --}}
      <div class="row promo-row my-4 g-3 bg-white shadow-sm py-3 px-4 mx-0 align-items-center">
          <div class="col-md-6">
              <h4 class="text-primary mb-3"><i class="fas fa-image me-2"></i>Banner Ad</h4>
              <div class="mb-2">
                  <span class="badge bg-success">Rotating Banner Ads</span>
              </div>
              <span class="text-muted small fw-bold">
                  Display your banner on every page of Bifonex.
              </span>
          </div>
          <div class="col-md-6">
              <div class="vacation-purchase rounded-3 bg-light">
                  <form onsubmit="return false;" class="mb-0">
                      <div class="input-group mb-2 w-75 mx-auto">
                          <input type="number"
                                 class="form-control form-control-lg"
                                 placeholder="2000 Credits"
                                 min="1"
                                 style="border: 2px solid #dee2e6;">
                          <span class="input-group-text fw-bold">$0.29</span>
                      </div>
                      <button type="button" class="btn btn-primary py-2 px-4 fw-bold d-flex align-items-center justify-content-center mx-auto">
                          <i class="fas fa-shopping-cart me-2"></i>
                          Buy Now!
                      </button>
                  </form>
              </div>
          </div>
      </div>

      {{-- Fixed PTC Advertisement --}}
      <div class="row promo-row my-4 g-3 bg-white shadow-sm py-3 px-4 mx-0 align-items-center">
          <div class="col-md-6">
              <h4 class="text-primary mb-3"><i class="fas fa-thumbtack me-2"></i>Fixed PTC Advertisement</h4>
              <span class="text-muted small fw-bold">
                  Display your advertisement for a full 24 hours and get clicks. A great choice to receive huge traffic.
              </span>
          </div>
          <div class="col-md-6">
              <div class="vacation-purchase rounded-3 bg-light">
                  <form onsubmit="return false;" class="mb-0">
                      <div class="input-group mb-2 w-75 mx-auto">
                          <input type="number"
                                 class="form-control form-control-lg"
                                 placeholder="15 Seconds"
                                 min="1"
                                 style="border: 2px solid #dee2e6;">
                          <span class="input-group-text fw-bold">$1.09</span>
                      </div>
                      <button type="button" class="btn btn-primary py-2 px-4 fw-bold d-flex align-items-center justify-content-center mx-auto">
                          <i class="fas fa-shopping-cart me-2"></i>
                          Buy Now!
                      </button>
                  </form>
              </div>
          </div>
      </div>

      {{-- AdGrid Advertising --}}
      <div class="row promo-row my-4 g-3 bg-white shadow-sm py-3 px-4 mx-0 align-items-center">
          <div class="col-md-6">
              <h4 class="text-primary mb-3"><i class="fas fa-th me-2"></i>AdGrid Advertising</h4>
              <span class="text-muted small fw-bold">
                  Advertise and display your ad for a very sharp price!
              </span>
          </div>
          <div class="col-md-6">
              <div class="vacation-purchase rounded-3 bg-light">
                  <form onsubmit="return false;" class="mb-0">
                      <div class="input-group mb-2 w-75 mx-auto">
                          <input type="number"
                                 class="form-control form-control-lg"
                                 placeholder="1000 Credits"
                                 min="1"
                                 style="border: 2px solid #dee2e6;">
                          <span class="input-group-text fw-bold">$0.15</span>
                      </div>
                      <button type="button" class="btn btn-primary py-2 px-4 fw-bold d-flex align-items-center justify-content-center mx-auto">
                          <i class="fas fa-shopping-cart me-2"></i>
                          Buy Now!
                      </button>
                  </form>
              </div>
          </div>
      </div>

    </div>
  </div>

  @include('user.footer')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Initialize tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));


                                                            function copyToClipboard(text) {
                                                                navigator.clipboard.writeText(text);
                                                                alert('Referral code copied!');
                                                            }
                                                          
  </script>
</body>

</html>
