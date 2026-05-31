 <?php

    use Illuminate\Support\Facades\Auth;
    use App\Models\User;
use Illuminate\Support\Facades\Session;

    $user = Auth::user();
    $name = $user->name;
    $ref_code = $user->activation;


// echo $variable3;
    ?>


 <div>
     @include('admin.admin-base')
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <div>
   
         @if(session('message'))
         <p class="alert alert-success d-flex justify-content-center">
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
     </style>
     <!-- Content Wrapper. Contains page content -->
     
     <div class="content-wrapper"> 
        

    <?php $results=DB::select("SELECT * from users where utype != 'ADM' and email_verified_at IS NOT NULL");
     $verified=count($results);
      $result=DB::select("SELECT * from users where utype != 'ADM' and email_verified_at IS NULL");
     $unverified=count($result);
     
     $f2=DB::select("SELECT * from users where utype != 'ADM' and email_verified_at IS NOT NULL and has_paid_package='fc2'");
     $fc2=count($f2);
      $f1=DB::select("SELECT * from users where utype != 'ADM' and email_verified_at IS  NOT NULL and has_paid_package='fc1'");
     $fc1=count($result);
           $std=DB::select("SELECT * from users where utype != 'ADM' and email_verified_at IS NOT NULL and has_paid_package='standard'");

     $sta=count($std);
       $fc1=count($result);
           $ft=DB::select("SELECT * from users where utype != 'ADM' and email_verified_at IS NOT NULL and has_paid_package='ft'");
     $ftt=count($ft);
     ?>


         <div class="ad-main-box mt-3 mb-3">
             <!-- <div class="" style="background-color: white;" id="hide"></div> -->
             <div class="ad-sub-box1 ">
                   
                     <div class="sub-box-1">   
                        <div class="ad-box bg-info " id="not"><a class="nav-icon fas fa-window-restore" href="{{route('admin.verify')}}" ><br>Verified <br> user<br> {{$verified}}</a></div>

                        <div class="ad-box bg-success "> <a href="{{route('admin.pending')}}" class="nav-icon fas fa-window-restore" ><br>Pending <br> user<br><span class="text-white">{{$unverified}}</span></a></div>
                    </div>
                    <div class="sub-box-1">  
                        <div class="ad-box bg-primary"><a class="nav-icon fas fa-wallet" href="#"><br>Projects<br>0:0</a>  </div>
                        <div class="ad-box bg-danger"><a class="nav-icon fas fa-bookmark"  href="{{route('admin.fc1')}}"> <br>FC $100 <br>{{$fc1}}</a>  </div>   
                    </div>
             </div>

             <div class="ad-sub-box2">
                <div  class="sub-box-1">
                    <div class="ad-box bg-warning mr-2 ml-1"><a class="nav-icon fas fa-money"  href="{{route('admin.fc2')}}"> <br>FC $200<br />{{$fc2}}</a></div>
                   <div class="ad-box bg-info mr-2  ml-1"><a class="nav-icon fas fa-gift" href='#'> <br>Standard <br>{{$sta}}</a></div>
                </div>
                <div class="sub-box-1">   
                     <div class="ad-box bg-success col"><a class="nav-icon fas fa-gift" href="{{route('admin.ft')}}"> <br>FT worker <br>{{$ftt}}</a></div><br> 
                </div>
                   
               
             </div>
             
            
         </div>
    
     
         <!-- Content Header (Page header) -->
         <div class="content-header">
             <div class="container-fluid">
                 <!-- /.row -->
             </div><!-- /.container-fluid -->
         </div>
         <!-- /.content-header -->
         <div class="row">
             

             </div>
             <!-- /.col -->
             
             <!-- /.col -->

             <!-- fix for small devices only -->
             <div class="clearfix hidden-md-up"></div>
    <?php 

$results=DB::select("SELECT * from users where utype != 'ADM'");
    ?>       
        <div class="table-container rounded ">
                    <div class="pt-3 border">
                    <p class="text-center font-weight-bold table-title">REGISTERED USER  <span class="text-success">({{count($results)}})</span></p>
                  </div>

                  <div class="tb">
                        <table class="table table-striped " style="text-align: center;">    
                            <thead class="bg-gradient-primary">
                                  <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Names</th>
                                    <th> email</th>
                                    <th> phone</th>
                                    <th>Country</th>
                                    <th>package</th>
                                    <th>status</th>
                                  </tr>
                            </thead>
                             <?php
                                $n=1;
                                foreach ($results as $row):

                            ?>
                            <?php
                                $verified="<span class='bg-success p-1 rounded'>verified</span>";
                                $pending="<span class='bg-danger p-1 rounded'>pending</span>";
                            ?>

                         <tbody>
                            <tr>
                                <td>{{$n}}</td>
                                <td>{{$row->user}}</td>
                                <td>{{$row->name}}</td>
                                <td>{{$row->email}}</td>
                                <td>{{$row->phone}}</td>
                                <td>{{$row->country}}</td>

                                <td>{{$pack=$row->has_paid_package=='no'?'not activated':$row->has_paid_package}}</td>
                                <td><?php echo $stutus=$row->email_verified_at?$verified:$pending?> </td>
                            </tr>
                        </tbody>
                        <!-- {{$n++}} -->
                        <?php endforeach;  ?>
                   
                    </table>
                  </div>

            </div>


 </div>

 @include('user.footer')