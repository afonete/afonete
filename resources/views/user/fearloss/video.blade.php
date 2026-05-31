
    <?php

    use Illuminate\Support\Facades\Auth;
   use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

    use App\Models\User;
    $user = Auth::user();

    $name = $user->user;
     if (!$id) {
        header("location:user.fearloss.videos");    }
    ?>

     @include('user.user-dashboard-base')
     <style type="text/css">
       #form{
        display: none;
       }
       .show{
        display: block;
    }

   .popup-quest{
        background-color: white;
        border: 1px solid black;
        border-radius: 2px;
        padding: 10px;
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
    }

     </style>
     <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper" style="width: 80%;">
    <!-- Content Header (Page header) -->
    <br><br>
    <div class="content-header">
      <div class="container-fluid">
        <ul class="nav nav-tabs">
          <li class="active">
<span id="msg" class="bg-danger py-2 px-2 rounded">
  <?php
$mine=db::SELECT("SELECT * from videos where id='$id' and user='$name'");

  ?>
  @if(count($mine))
  My Video
@else
            {{$already??'Earn money by watching this video and answer question '}}<br>
            {{$update??''}}

  {{$errora??''}}
{{$errorb??''}}
</span>
@if(!$already )
<?php
$results=db::SELECT("SELECT question from ads where id='$id'");
  foreach ($results as $row) {


?>
             <form method="post" id="form" action="{{route('user.watched')}}">
              @csrf
              <span> {{$row->question}}</span>

              <input type="text" name="answer" placeholder="Answer question">
              <input type="hidden" name="id" value="{{$id}}">
              <input type="submit" name="sub" value="submit">

            </form>
            <?php
             }

?>
@endif
@if(!$fail )
<span id="countdown-time" class="alert-success">
</span>
@endif
<span class="alert-success">
{{$success??''}}

{{$fail??''}}
</span>
<span id="done"></span>
@endif
         </li>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content" >
      <div class="container-fluid">

        <div class="row">
                    <?php
$results=db::SELECT("SELECT * from videos where id='$id' order by id desc");
  foreach ($results as $row) {


        ?>
            <div class="col-md-8">
            <div class="card card-primary card-outline">
            <center>
       <h4>   {{$row->tittle}}
        <br></h4>

       <div id="video-container">
<video  src="{{asset('video/'.$row->video)}}" id="video" width="100%" style="height: 300px;width: 100%;"
     class="img click-img" controls alt="click addvs" type="video/mp4" controlsList="nodownload">

       </div>


       <p>{{$row->description}}

       </p>

           </center>
        </div>
 <script>
        const video = document.getElementById("video");
        const popup_quest = document.getElementById("quest");
        let isTenSecondLeft = false;
        let startDate;

        video.onplay = ()=>{
            startDate = new Date();
var time="<?php echo $row->time ?>"
            // when video is playing
            video.ontimeupdate = ()=>{
                if(!isTenSecondLeft){
                    // calculate 10 second
                    let diff = new Date() - startDate;
                    let second = Math.floor(diff / 1000);
                      document.getElementById('countdown-time').innerHTML=second+" second";

                    // when ten second left popup question form
                    if(second == time){
                        isTenSecondLeft = true;
                        // popup_quest.classList.add("show")
                        document.getElementById('form').style.display='block';
                      document.getElementById('countdown-time').innerHTML=" ";


                    }
                }
            }
        }


    </script>
      </div>
 <?php }?>

 <div class="col-md-4">
    <h3 class="text-warning font-weight-bold mb-4 text-lg">Trendings</h3>
    <?php
      $results = db::SELECT("SELECT * from videos order by id desc");
      foreach ($results as $row) {

        if($row->id == $id){
            continue;
        }
    ?>
    <div class="card mb-4 border border-warning shadow-sm">
      <div class="card-body ">
        <h4 class="card-title text-warning">
          <a href="{{route('user.watch')}}?video={{$row->id}}" target="_blank" class="text-warning font-weight-bold">
            {{$row->tittle}}
          </a>
        </h4>
        <div class="text-left">
          <video src="{{asset('video/'.$row->video)}}"
             class="rounded w-100" style="height: 115px;" alt="Video thumbnail"></video>
        </div>
        <p class="card-text">
          <a href="{{route('user.watch')}}?video={{$row->id}}" target="_blank" class="text-warning">
            View for {{$row->time}} sec and Earn {{$row->user_earns}}$
          </a>
        </p>
      </div>
    </div>
    <?php } ?>
  </div>




      </div>
       <!-- END OF click column -->
    </div>
    <!-- /.content -->
    <!-- /.row -->

  </div>
</div>







