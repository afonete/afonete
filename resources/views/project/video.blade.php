 <?php
    use Illuminate\Support\Facades\DB;
    use App\Models\video;
$ads=video::find($id);
 ?>
 <div>
     @include('user.user-dashboard-base')


       
<div class="content-wrapper" style="background: #1c1d20; color: #888b95;">
    <br><br><br>

    <!-- Content Header (Page header) -->

        <ul class="nav nav-tabs">
            <li class="active"><a  href="{{route('user.dashboard.video')}}">Create new Video ads||</a></li>
            <li ><a  href="{{route('user.dashboard.myvideo')}}">My Video ads||</a></li>
            <li ><a  href="{{route('user.dashboard.deposit')}}">Deposit</a></li>
          
          </ul>
     


    <!-- Main content -->
   <div class="content">
      <div class="container">
      <div class="row">
        <div class="col-md-6">
          
            <div class="card-body ">
              <div class="info">
  
                  <center>
                    <div class="text-center "><h3 class="font-size-20 "> Create a New Video <br>Advertisement</h3> 
                    <hr>
                 

                   </div>
                 
                <!-- /.info-box-content -->

       <div class="add_ads" style="background:#1c1d20">
  <form id="adForm" method="post" action="{{route('user.video')}}" enctype="multipart/form-data">
    @csrf
  <div>
      @if($message=='success')

    <span class="btn btn-success">
      Video ads has been created well
    </span>
    @endif
     @if($error=='video')

    <span class="btn btn-danger">
     failed to upload Video file 
    </span>
    @endif
    @if(isset($fail))

    <span class="btn btn-danger">
    Error during file upload:   {{$fail}}
    </span>
    @endif
     @if($error=='size')

    <span class="btn btn-danger">
     File size exceeds the maximum limit, (allowed size 60mb).
    </span>
    @endif
    @if($message=='error')
    <span class="btn btn-danger">
      Failed to create Video ads
    </span>
    @endif
   
    
    @if($error=='type')
      <span class="btn btn-danger">
      Invalid file type. Only video files are allowed<br><br>
      [video/mp4]
    </span>
    @endif
      @if($less)
      <span class="btn btn-danger">
      {{$less}}
    </span>
    @endif
  </div>
    <div class="my-3">
   <table class="ads_table" cellspacing="10">
    <tr class="my-3">
     
   <div class="">
      <tr class="user-box-add">
        <td>
          <input type="hidden" name="MAX_FILE_SIZE" value="62914560">
   <!-- 60MB in bytes -->
         <input type="file" name="video" style="color: black;" class="form-control" accept="video/*">
         <br>
        </td>
        <td>
         
          <label><span class="clear-icon" id="sel"  onclick="clearInput('file')">
          Ad video (i.e; alowed format: [mp4]</span></label>
        </td>
      </tr>
    </div>  

        
  

    <div class="">
      <tr class="user-box-add">
        <td>
          <input type="text" id="titt" oninput="changetittle(this.value)"  name="tittle" maxlength="25" value="{{$ads->tittle??''}}" placeholder="ex: watch and earn" required="" class="form-control"><br>
        </td>
        <td>
          <span class="" id="sel" onclick="clearInput('titt')"></span>
          <label>Ad tittle</label>
          <br>
        </td>
      </tr>
    </div>  
         <div class="mt-5 d-block"></div>
     <div class="user-box ">
  
        

    </div>
   
    
   <div class="user-box">
      <tr>
        <td>
          <select name="views" id="views" onchange="calculate()" class="form-control">
            <option value="{{$ads->targeted_views??'1000'}}">{{$ads->targeted_views??'1000 views'}}</option>
            <option value="5000">5000 Views</option>
            <option value="10000">10000 views</option> 
            <option value="500000">500000 Views</option>
            <option value="250000">250000 views</option>   
            <option value="500000">50000 Views</option>
          </select>
          <br>
        </td>
        <td>
          <span class="clear-icon" id="sel" onclick="clearInput('views')"></span>
            <label class="label text-sm">Total Views / Clicks</label><br>
            
        </td>
      </tr>
        


    </div>

     <div class="user-box">
        <tr>
          <td>
             <select name="duration" id="duration" onchange="calculate()" class="form-control">
                <option value="{{$ads->targeted_duration??'1'}}">{{$ads->targeted_duration??'1 day ($0.004 per views)'}}</option>
                <option value="3">3day ($0.012 per views)</option>
                <option value="7">7day ($0.028 per views)</option>   
                <option value="15">15day ($0.06 per views)</option>

                <option value="30">30day ($0.12 per views)</option>   
                <option value="60">60day ($0.24 per views)</option>   

              </select><br>
          </td>
          <td>
             <span class="clear-icon" id="sel" onclick="clearInput('duration')"></span>
              <label class="label">View Duration/Time:</label><br>
          </td>
        </tr>
    </div>

    <div class="user-box">
      <tr>
        <td>
          <select name="geo" id="geo" class="form-control">
          <option >{{$ads->location??'North America'}}</option>
          <option>United states</option>
          <option>Europe</option>   
          <option>Africa</option>  
          <option>Asia</option>
          <option>United kingdom</option>   
          <option>ociania</option>   
        </select><br>
        </td>
        <td>
          <span class="clear-icon" id="sel" onclick="clearInput('geo')"></span>
           <label class="label">Geo Targeting:</label><br>
            
        </td>
      </tr>

    </div>
    <div class="user-box">
      <tr>
        <td>
          <input type="text" id="question" value="{{$ads->question??''}}" placeholder="ex: what color on my banner" name="question"  maxlength="60" required="" class="form-control"><br>
        </td>
        <td>
          <span class="clear-icon" id="sel"  onclick="clearInput('question')"></span>
           <label>Set question related to your video</label><br>
        </td>
      </tr>
     

    </div>

     <div class="user-box">
      <tr>
        <td>
          <input type="text" id="answer"  name="answer" value="{{$ads->answer??''}}" placeholder="ex: yellow" maxlength="60" required="" class="form-control">
        <br></td>
        <td>
            <span class="clear-icon" id="sel"   onclick="clearInput('answer')"></span>
           <label>Set answer to your question</label>
        </td></tr></div>
        <div class="user-box">
         <tr>
             
        <td>
          <input type="number" id="time"  name="time" value="{{$ads->time??''}}" placeholder="time in seconds ex: 10" maxlength="60" required="" class="form-control"><br>
        </td>
         <td>
            <span class="clear-icon" id="sel"  onclick="clearInput('time')">


           <label>Set your desired minimum  time of watching time in sec</label>  </span><br>
        </td>
      </tr>
    </div>

    <tr>
        <td>
            <div style="float: right;">
                   <p ><span  id="all" class="text-white">  </span><span id="single" class="text-white">0.004$ per views</span></p>
          </div>
        </td>
      </tr>
   
      </table>
   <button class="btn btn-success" type="submit">Submit For Approval</button>
    
   </div>
  </form>
</div>
 
              </div>
            </div>
 
  <script>
   
    var all=document.getElementById('views').value;
    var single=document.getElementById('duration').value;


      var views=all*single*0.004;
      document.getElementById('all').innerHTML=views+"$ to be paid";
      document.getElementById('single').innerHTML=" 0.004$ per views";
function calculate(){
    
  var all=document.getElementById('views').value;
    var single=document.getElementById('duration').value;

      var views=all*single*0.004;
      document.getElementById('all').innerHTML=views+"$ to be paid";
      document.getElementById('single').innerHTML="0.004$ per views";

}
 function choose(id){
if (id=='link')
{
            document.getElementById('urli').style.display="block";
            document.getElementById('file').style.display="none";

}
 
 if (id=='banner')
{
            document.getElementById('file').style.display="block";
            document.getElementById('urli').style.display="none";

} 
 if (id=='both')
{
            document.getElementById('file').style.display="block";
            document.getElementById('urli').style.display="block";

}   
}
    function clearInput(inputId) {
      document.getElementById(inputId).value = '';
    }
      function changedesc(desc) {
            document.getElementById('show').style.display="block";
            document.getElementById('descriptions').innerHTML=desc;
           }

    function changetittle(tittle) {
            document.getElementById('show').style.display="block";
            document.getElementById('titts').innerHTML=tittle;
           }

    
  </script>


    
        </div>
         <!-- END OF cl -->
         <div class="col-md-6" >
          
          <div class="card-body">
            <div class="info">

                <center> <div class="text-center">
  <h4 class="font-size-20 " style="color: #888b95;">
                  How does this work?
              <hr>  </h4> 
                <div style="width: 100%;background: #2e2e32;overflow-wrap: break-word; " id="show">
                 <h3 id="titts"></h3>
                 <hr>
                 <p id="descriptions"></p>
                </div>
                <div class="font-size-14 " style="color:#e7e7e7">
<p >You can create a video advertisement and purchase traffic for any website that does not break our rules. After your purchase is complete and your video is approved, Fonepo users will view your ads for the amount of time you have chosen. Every time a user views your ad they will receive a small number of money.</p>

<p>You can pay VIDEO ads with your ammount on balance, or you can <a href="{{route('user.dashboard.deposit')}}" style="color:red">Deposit</a> to add ammount to your balance.</p>
                </div>

  <h4 class="font-size-20" style="color: #888b95;">
                <br>  Video Guidelines
   
   <style type="text/css">
     li{
      color: #e7e7e7;
     }
   </style>           <hr>  </h4> </center>
<ul>
 
<li>No illegal products or services (weapons, drugs, escorts etc.)</li>
<li>No malware, viruses, spyware or ransomware.</li>
<li>No infinite redirect loops.</li>
<li>No copyright infringing video.</li>
<li>No x-rated adult content.</li></ul>
              </div>
            </div>
          </div>
       
       
      </div>
       <!-- END OF cl -->
    
 </div>