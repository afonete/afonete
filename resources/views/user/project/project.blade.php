 <div>
      @include('user.user-dashboard-base')

         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <br>

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
       <!--  <ul class="nav nav-tabs">
            <li class="active"><a  href="{{route('user.dashboard')}}">Package List</a></li>
            <li ><a  href="{{route('user.dashboard')}}">Purchased Packages</a></li>
          
          </ul> --><center>
          <h3 style="border-bottom: 1px solid #000050; padding: 5px 0;"><i class="las la-cloud-upload-alt" style="font-size:35px;" ></i> Upload project : Advertise </h3></center>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container">
      <div class="row">
        <div class="col-md-4">
          
            <div class="card-body rounded">
              <div class="info shadow">
  
                    <div class="text-center text-white py-2" style="background: #000050;"><h4 class="font-size-20 font-weight-600">Paid to Click (PTC)</h4> <span class="font-size-12 ">Drive traffic to your website, get views on your service.</span></div>
                <!-- /.info-box-content -->
              <div style="height: 230px;">
                 <img style=" height: 100%; width: 100%" src="{{asset('assets/a/img/click.png')}}">
              </div>
                  <div class="container-fluid"  style="background: #000050;">
                    <div class="d-flex justify-content-center align-items-center py-2">
                      <div class="btn btn-warning">
                       <a href="{{route('user.dashboard.ptc')}}" class="text-white "> MANAGE A PTC AD</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
         
         
        </div>
         <!-- END OF cl -->
         <div class="col-md-4">
          
          <div class="card-body rounded ">
            <div class="info shadow">

              <div class="text-center text-white py-2" style="background: #000050;"><h4 class="font-size-20 font-weight-600">Paid to Watch (PTW)</h4> <span class="font-size-12 ">Drive traffic to your website, get views on your video.</span></div>
              
              <!-- /.info-box-content -->
             <div  style="height: 230px;">
                 <img style=" height: 100%; width: 100%" src="{{asset('assets/a/img/video.png')}}">
                 <!-- <span>Video watches</span> -->
             </div>

              <div class="container-fluid" style="background: #000050;">
                <div class="d-flex justify-content-center align-items-center py-2">
                  <div class="btn btn-warning" >
                    <a href="{{route('user.dashboard.video')}}" class="text-white ">
                      MANAGE VIDEO AD
                    </a>
                  </div>
                  <br>
                </div>
              </div>
            </div>
          </div>
       
       
      </div>
       <!-- END OF cl -->

         <!-- END OF cl -->
         <div class="col-md-4">
          
          <div class="card-body rounded ">
            <div class="info shadow">

              <div class="text-center text-white py-2" style="background: #000050;"><h4 class="font-size-20 font-weight-600">Paid to Downloads (PTD)</h4> <span class="font-size-12 ">Drive traffic to your website, get views on your App.</span></div>
              
              <!-- /.info-box-content -->
             <div  style="height: 230px;">
                 <img style=" height: 100%; width: 100%" src="{{asset('assets/a/img/download.png')}}">
                 <!-- <span>Video watches</span> -->
             </div>

              <div class="container-fluid" style="background: #000050;">
                <div class="d-flex justify-content-center align-items-center py-2" >
                  <div class="btn btn-warning" >
                    <a href="{{route('user.dashboard.video')}}" class="text-white ">
                      MANAGE A DOWNLOADABLE APP <span style="color: red;">coming soon</span>
                    </a>
                  </div>
                  <br>
                </div>
              </div>
            </div>
          </div>
       
       
      </div>
     <!-- END OF cl -->
        </div>
    
         <!-- END OF cl -->
      
       
       
      </div>
       <!-- END OF cl -->
     
    </div>
     <!-- END OF cl -->
        </div>
         
       
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
    <!-- /.row -->

  </div>
 </div>