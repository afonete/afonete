

@include('user.user-dashboard-base')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="content-wrapper">
<!-- Content Header (Page header) -->
<div class="content-header">
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
 <!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
 <div class="container-fluid">

   <div class="row">
    <div class="card p-4 mx-auto my-5 shadow-lg" style="min-width: 600px;">
        <small class="fw-bold ">Answer this question related to this ads</small>
        <h1 class="h4 font-weight-bold mb-4 text-left"> {{$question}}?</h1>



            @if(session('error'))
                <div style="background: brown;padding:5px;border-radius:5px; color:aliceblue">
                    <strong>{{ session('error') }}</strong>
                </div>
             @endif
        @if(session('success'))
            <div class="alert alert-success">
                <strong>{{ session('success') }}</strong>
            </div>
         @endif
        <form method="post" id="form" action="{{route('user.claim.reward')}}">
            @csrf




            <div class="form-group">
                <label for="answer">Your Answerd:</label>
                <input type="hidden" name="id" value="{{$id}}">
                <input type="text" id="answer" name="answer" placeholder="Enter your answer" class="form-control" required>
            </div>

            <div class="d-flex flex-column justify-content-between">
                <button type="submit" class="btn btn-primary w-100">Submit Answer</button>
                <small class="text-muted mt-2">Earn money by viewing advertisements</small>
            </div>
        </form>

    </div>


   </div>
   <!-- /.row -->

     <!-- Main row -->
 </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
<!-- /.row -->

</div>
</div>


@include('user.footer')
