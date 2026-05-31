    
    <?php

    use Illuminate\Support\Facades\Auth;
   use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

    use App\Models\User;
    $user = Auth::user();

    $name = $user->user;
     if (!$id) {
        header("location:user.fearloss.payclick");    }
    ?>

     @include('user.user-dashboard-base')
     <style type="text/css">
       /*#form{*/
       /* display: none;*/
       /*}*/
        .waiting{
            position:relative;
            width: 30%;
            margin: 5px auto;
            padding: 20px;
            height: 150px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            text-align: center;
        }
        #leftSec{
            padding-top: 20px;
            text-align:center;
            font-size: 30px;
            font-weight: bold;
            color: #1565C0;
        }
        @media screen and (max-width: 750px){
            .waiting{
                width: 70%;
            }
            #leftSec{
                font-size:20px;  
                 padding-top: 10px;
            }
        }
        #powed{
            position: absolute;
            bottom:0;
            left: 50%;
            transform: translate(-50%, 0);
        }
        .answerForm{
            display: none;
            padding: 20px;
            background-color: #f7f7f9;
            width: 50%;
            margin: 10px auto;
        }
     </style>
     <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <br>
    <div class="content-header">
      <div class="container-fluid">
        <span id="msg" style="color: red;">
          <?php 
        $mine=db::SELECT("SELECT * from ads where id='$id' and user='$name'");
        
          ?>
        <!--  @if(count($mine))-->
        <!--  Viewing my ads-->
        <!--@else-->
        <!--            {{$already??'Earn money by viewing advertisments '}}-->
        <!--            {{$update??''}}-->
                    
        <!--  {{$errora??''}}-->
        <!--{{$errorb??''}}-->
        </span>
        <!--@if(!$already )-->
        <?php
        // $results=db::SELECT("SELECT question from ads where id='$id'");
        //   foreach ($results as $row) {
        
        
        ?>
                    <!-- <form method="post" id="form" action="{{route('user.ads')}}">-->
                    <!--  @csrf-->
                    <!--  <span> {{$row->question}}</span>-->
        
                    <!--  <input type="text" name="answer" placeholder="Answer question">-->
                    <!--  <input type="hidden" name="id" value="{{$id}}">-->
                    <!--  <input type="submit" name="sub" value="submit">-->
        
                    <!--</form>-->
                    <?php
                    //  }
                     
        ?>
        <!--@endif-->
        <!--@if(!$fail )-->
        <!--<span id="countdown-timer" class="alert-success">-->
        <!--</span>-->
        <!--@endif-->
        <!--<span class="alert-success">-->
        <!--{{$success??''}}-->
        
        <!--{{$fail??''}}-->
        <!--</span>-->
        <!--<span id="done"></span>-->
        <!--@endif-->
        
        <span id="msg" style="color: red;">
              <?php 
            $mine=db::SELECT("SELECT * from ads where id='$id' and user='$name'");
            
              ?>
              @if(count($mine))
              Viewing my ads
            @else
                    
            <div class="waiting" id="waitBox">
                <div class="flex-column justify-content-around">
                  <p class ="text-center">{{$already??'Earn money by viewing advertisments '}}  
                  {{$update??''}}
                  {{$errora??''}}
            {{$errorb??''}}</span><br></p>
                  <p class=" text-lg" id="leftSec" style="margin-top: -40px"></p>
                  <p class="text-center" style="font-size: 10px;" id="powed">Powered by Fonepo </p>
                </div>
            </div>
            @if(!$already)
            <?php
            $results=db::SELECT("SELECT question from ads where id='$id'");
              foreach ($results as $row) {
            
            
            ?>
                
            <div class="answerForm shadow" >
                <div id="done" class="alert alert-success text-center">answer responded</div>
                <span id="msg" style="color: red;">Answer this question related to this ads<span>
               
                 <form method="post" id="form" action="{{route('user.ads.url')}}" class="my-3">
                     @csrf
                  <span class="font-weight-bold text-dark" style="font-size: 18px;">{{$row->question}}?</span>
                    <input type="hidden" name="id" value="{{$id}}">
                     <div class="d-flex my-2">
                        <input type="text" name="answer" placeholder="Answer question" class="form-control">
                        <input type="submit" name="sub" value="submit" class="btn btn-primary">
                    </div>
            
                </form>
                
                
              
            </div>
               <?php
                         }
                         
            ?> 
            @endif
            @if(!$fail )
            <span id="countdown-timer" class="alert-success">
            </span>
            @endif
            <span class="alert-success">
            {{$success??''}}
            
            {{$fail??''}}
            </span>
            <span id="done"></span>
            @endif
        
        
        
        
        <script type="text/javascript">
        //   const countdownTimer = document.getElementById('countdown-timer');
        // let countdownSeconds =10; // Number of seconds for the countdown
        // let countdownInterval;
        
        // function startCountdown() {
        //   countdownInterval = setInterval(updateCountdown, 1200);
        // }
        
        // function updateCountdown() {
        //   countdownSeconds--;
        
        //   if (countdownSeconds >= 0) {
        //     countdownTimer.textContent = countdownSeconds+" remaining seconds";
        //   } else {
        //     clearInterval(countdownInterval);
        //     countdownTimer.textContent = '';
        //     performTask(); // Call the function to perform your desired task Thank you for watching ads you earn 0.0001$
        //   }
        // }
        
        // function performTask() {
        //   // Implement your task logic here
        //   document.getElementById('done').innerHTML='<input type="hidden" name="done" value="performed">';
        //   document.getElementById('form').style.display="block";
        //   document.getElementById('msg').innerHTML="Answer this question related to this ads";
        // }
        
        // startCountdown();
        
        const waitBox = document.getElementById('waitBox');
        const answerForm = document.querySelector('.answerForm');
        const countdownTimer = document.getElementById('leftSec');
        let countdownSeconds =10; // Number of seconds for the countdown
        let countdownInterval;
    
        function startCountdown() {
          countdownInterval = setInterval(updateCountdown, 1200);
        }
    
        function updateCountdown() {
          countdownSeconds--;
        
          if (countdownSeconds >= 0) {
            countdownTimer.textContent = countdownSeconds+" second left";
          } else {
            clearInterval(countdownInterval);
            waitBox.style.display = "none";
            performTask(); // Call the function to perform your desired task Thank you for watching ads you earn 0.0001$
          }
        }
    
        function performTask() {
            answerForm.style.display = "block"
         }
        startCountdown();
        
        </script>   

      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
      
        <div class="row">
                    <?php 
$results=db::SELECT("SELECT * from ads where id='$id' order by id desc");
  foreach ($results as $row) {
   
  
        ?>
        <div class="col-md-8">
        <div>
        <h3 style="text-transform: capitalize;padding: 15px 0px; border-bottom: 1px solid lightgray; margin-bottom: 20px;">   {{$row->tittle}}  </h3>
        <style type="text/css">
               /* #mmm{
                    background: url("{{asset('ads/'.$row->banner)}}");
                    height: 165px;
                    background-repeat: no-repeat;
            width: 800px;
            
                }*/
        </style>
       <div  style="width: 85%; height: 500px;">
            <img src="{{asset('ads/'.$row->banner)}}"style="height:100%;width: 100%;" class="img click-img" alt="click adds">
       </div>
       <p style="margin: 10px 0; font-size: 18px; word-wrap: break-word;">{{$row->description}}  </p>
         
        </div>
       

      </div> 
 <?php }?>
 <div class="col-md-4 trend">
  <h class="title">
    Trendings
  </h>
                  <?php 
$results=db::SELECT("SELECT * from ads order by id desc");
  foreach ($results as $row) {
   
  
        ?>
            <div class="card card-primary card-outline">
            <center>
       <h4> 
    @if($row->url!=NULL)
                 <a style="color: #007bff" target="__blank"  href="{{route('user.url')}}?url={{$row->url}}">{{$row->tittle}}

</a>
  @endif 
  <!--  -->
@if($row->url==NULL)
<a style="color: #007bff" target="__blank" href="{{route('user.click')}}?ads={{$row->id}}">{{$row->tittle}}</a>

                  @endif    
   <br></h4>
       <div class="">

              @if($row->banner!=NULL )
    <span><center>
<img src="{{asset('ads/'.$row->banner)}}" style="margin-left: -18px ;height: 115px;width: 200px;" class="img click-img" alt="click adds">
                     </center>@endif       
                         
             @if($row->banner==NULL )  
             <div style="height: 100%;width:100%">{{$row->description}}</div>
    
@endif
     </span>
                          
                    </div>   
       <p>
@if($row->url!=NULL)
                 <a style="color: #007bff" target="__blank" href="{{$row->url}}">View for 10 sec and Earn free $

</a>
  @endif 
@if($row->url==NULL)
  <a style="color: #007bff" target="__blank" href="{{route('user.click')}}?ads={{$row->id}}">
    View for 10 sec and Earn free $</a>
@endif

       </p>
         
           </center>
        </div>
     
 <?php }?>
        
      </div> 
       <!-- END OF click column -->
    </div>
    <!-- /.content -->
    <!-- /.row -->

  </div>
</div>