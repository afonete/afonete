
@include('user.user-dashboard-base')
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
           <div class="tabs tab_links" >
              <span class="links_tabs d-flex ">
                <a href="{{route('user.dashboard.payclick')}}" class="fomoLink" id="tabs"> <i class="fa-solid fa-hand-point-up"></i> Pay clicks</a>
                <a href="{{route('user.dashboard.payvideo')}}" class="fomoLink"><i class="fa-solid fa-video"></i> Watch Videos</a>
              </span>

              <a  href="/user/dashboard/download-app" class="fomoLink" ><i class="fa-solid fa-download"></i> Download App <i style="color: red">Coming soon</i></a>
              <a  href="{{route('user.dashboard.payvideo.invoice')}}" class="fomoLink" ><i class="fa-solid fa-book"></i> Invoices </a>
              <a  href="/user/dashboard/fixedads" class="fomoLink" > <i class="fa-solid fa-rectangle-ad"></i>
              Fixed Ads </a>
              <a  href="/user/dashboard/tasks" class="fomoLink" ><i class="fa-solid fa-tasks"></i>Tasks </a>

          </div>
      </div><!-- /.container-fluid -->
    </div>
    <div class="row" style="margin: 20px;">

<!-- card one -->
<div class="container mt-4">
  <div class="row">
    <!-- Card 1 -->
    <div class="col-md-4 mb-3">
      <div class="card shadow">
        <div class="card-header bg-danger text-white text-center">
          <h6 class="mb-0"><strong>OptimalBux</strong></h6>
        </div>
        <div class="card-body text-center">
          <p class="text-muted">Our newest site! Already 3 years old!</p>
           <div class="my-1">
            <img src="{{ asset('image/usd.jpg') }}" class="img-fluid rounded" alt="OptimalBux" style="max-width: 130px; height: auto;">
          </div>
          <p class="font-weight-bold text-success mb-1">Earn $0.0011 <i class="fa-solid fa-coins"></i></p>
          <p class="text-primary mb-0">+1 Point</p>
        </div>
      </div>
    </div>

    <!-- Card 2 -->
    <div class="col-md-4 mb-3">
      <div class="card shadow">
        <div class="card-header bg-danger text-white text-center">
          <h6 class="mb-0"><strong>GPTPlanet Elite Site</strong></h6>
        </div>
        <div class="card-body text-center">
          <p class="text-muted">Online Since 2010!</p>
          <div class="my-1">
            <img src="{{ asset('image/usd.jpg') }}" class="img-fluid rounded" alt="OptimalBux" style="max-width: 130px; height: auto;">
          </div>
          <p class="font-weight-bold text-success mb-1">Earn $0.001 <i class="fa-brands fa-bitcoin"></i></p>
          <p class="text-primary mb-0">+1 Point</p>
        </div>
      </div>
    </div>

    <!-- Card 3 -->
    <div class="col-md-4 mb-3">
      <div class="card shadow">
        <div class="card-header bg-danger text-white text-center">
          <h6 class="mb-0"><strong>Neobux</strong></h6>
        </div>
        <div class="card-body text-center">
          <p class="text-muted">Online Since 2008.</p>
          <div class="my-1">
            <img src="{{ asset('image/usd.jpg') }}" class="img-fluid rounded" alt="OptimalBux" style="max-width: 130px; height: auto;">
          </div>
          <p class="font-weight-bold text-success mb-1">Earn $0.001 <i class="fa-solid fa-dollar-sign"></i></p>
          <p class="text-primary mb-0">+1 Point</p>
        </div>
      </div>
    </div>

    <!-- Card 4 -->
    <div class="col-md-4 mb-3">
      <div class="card shadow">
        <div class="card-header bg-danger text-white text-center">
          <h6 class="mb-0"><strong>Cointiply</strong></h6>
        </div>
        <div class="card-body text-center">
          <p class="text-muted">Play and earn Coins.</p>
          <div class="my-1">
            <img src="{{ asset('image/usd.jpg') }}" class="img-fluid rounded" alt="OptimalBux" style="max-width: 130px; height: auto;">
          </div>
          <p class="font-weight-bold text-success mb-1">Earn $0.001 <i class="fa-solid fa-coins"></i></p>
          <p class="text-primary mb-0">+1 Point</p>
        </div>
      </div>
    </div>

    <!-- Card 5 -->
    <div class="col-md-4 mb-3">
      <div class="card shadow">
        <div class="card-header bg-danger text-white text-center">
          <h6 class="mb-0"><strong>FreeBitcoin</strong></h6>
        </div>
        <div class="card-body text-center">
          <p class="text-muted">Claim Free Bitcoin every hour.</p>
          <div class="my-1">
            <img src="{{ asset('image/usd.jpg') }}" class="img-fluid rounded" alt="OptimalBux" style="max-width: 130px; height: auto;">
          </div>
          <p class="font-weight-bold text-success mb-1">Earn $0.0005 <i class="fa-brands fa-bitcoin"></i></p>
          <p class="text-primary mb-0">+1 Point</p>
        </div>
      </div>
    </div>

    <!-- Card 6 -->
    <div class="col-md-4 mb-3">
      <div class="card shadow">
        <div class="card-header bg-danger text-white text-center">
          <h6 class="mb-0"><strong>FreeBitcoin Faucet</strong></h6>
        </div>
        <div class="card-body text-center">
          <p class="text-muted">Best BTC Faucet.</p>
          <div class="my-1">
            <img src="{{ asset('image/usd.jpg') }}" class="img-fluid rounded" alt="OptimalBux" style="max-width: 130px; height: auto;">
          </div>
          <p class="font-weight-bold text-success mb-1">Earn $0.0005 <i class="fa-brands fa-bitcoin"></i></p>
          <p class="text-primary mb-0">+1 Point</p>
        </div>
      </div>
    </div>

    <!-- Card 7 -->
    <div class="col-md-4 mb-3">
      <div class="card shadow">
        <div class="card-header bg-danger text-white text-center">
          <h6 class="mb-0"><strong>BuxFire Inc.</strong></h6>
        </div>
        <div class="card-body text-center">
          <p class="text-muted">Get paid every second just by viewing advertisements.</p>
          <div class="my-1">
            <img src="{{ asset('image/usd.jpg') }}" class="img-fluid rounded" alt="OptimalBux" style="max-width: 130px; height: auto;">
          </div>
          <p class="font-weight-bold text-success mb-1">Earn $0.0002 <i class="fa-solid fa-dollar-sign"></i></p>
          <p class="text-primary mb-0">+1 Point</p>
        </div>
      </div>
    </div>

    <!-- Card 8 -->
    <div class="col-md-4 mb-3">
      <div class="card shadow">
        <div class="card-header bg-danger text-white text-center">
          <h6 class="mb-0"><strong>Unlimited Money</strong></h6>
        </div>
        <div class="card-body text-center">
          <p class="text-muted">Register Now.</p>
          <div class="my-1">
            <img src="{{ asset('image/usd.jpg') }}" class="img-fluid rounded" alt="OptimalBux" style="max-width: 130px; height: auto;">
          </div>
          <p class="font-weight-bold text-success mb-1">Earn $0.0002 <i class="fa-solid fa-coins"></i></p>
          <p class="text-primary mb-0">+1 Point</p>
        </div>
      </div>
    </div>

    <!-- Card 9 -->
    <div class="col-md-4 mb-3">
      <div class="card shadow">
        <div class="card-header bg-danger text-white text-center">
          <h6 class="mb-0"><strong>Buxfire Corporate Inc.</strong></h6>
        </div>
        <div class="card-body text-center">
          <p class="text-muted">UK Registered Company. The Next Big PTC Site.</p>
          <div class="my-1">
            <img src="{{ asset('image/usd.jpg') }}" class="img-fluid rounded" alt="OptimalBux" style="max-width: 130px; height: auto;">
          </div>
          <p class="font-weight-bold text-success mb-1">Earn $0.0002 <i class="fa-solid fa-coins"></i></p>
          <p class="text-primary mb-0">+1 Point</p>
        </div>
      </div>
    </div>
  </div>
</div>


</div>

    @include('user.footer')