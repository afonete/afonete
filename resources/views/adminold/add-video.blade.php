 <?php
    use Illuminate\Support\Facades\DB;
    use App\Models\Video;
$ads=Video::find($id);
 ?>
 <div>
       @include('admin.admin-base')


        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"  style="width: 82%;background: #1c1d20; color: #888b95;">
    <br><br>

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
    <ul class="nav nav-tabs">
          <li ><a  href="{{route('admin.ads')}}">Manage ads</a></li>
        <li><a  href="{{route('admin.videos')}}">Manage videos</a></li>
        <li><a  href="#">Manage download</a></li>
        <li class="active"><a  href="{{route('admin.video.create')}}">Add Videos</a></li>
          
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

    <span class="alert alert-success">
      Video ads has been created well
    </span>
    @endif
     @if($error=='video')

    <span class="alert alert-danger">
     failed to upload Video file 
    </span>
    @endif
    @if(isset($fail))

    <span class="alert alert-danger">
    Error during file upload:   {{$fail}}
    </span>
    @endif
     @if($error=='size')

    <span class="alert alert-danger">
     File size exceeds the maximum limit, (allowed size 60mb).
    </span>
    @endif
    @if($message=='error')
    <span class="alert alert-danger">
      Failed to create Video ads
    </span>
    @endif
   
    
    @if($error=='type')
      <span class="alert alert-danger">
      Invalid file type. Only video files are allowed<br><br>
      [video/mp4]
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
         
          <label><span class="clear-icon" id="sel"  onclick="clearInput('file')">&#10005;
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
          <span class="" id="sel" onclick="clearInput('titt')">&#10005;</span>
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
          <span class="clear-icon" id="sel" onclick="clearInput('views')">&#10005;</span>
            <label class="label text-sm">Total Views / Clicks</label><br>
            
        </td>
      </tr>
        


    </div>

     <div class="user-box">
        <tr>
          <td>
             <select name="duration" id="duration" onchange="calculate()" class="form-control">
                <option value="{{$ads->targeted_duration??'1'}}">{{$ads->targeted_duration??'1 day ($1 per views)'}}</option>
                <option value="2">3day ($2 per views)</option>
                <option value="6">7day ($6 per views)</option>   
                <option value="12">15day ($12 per views)</option>

                <option value="24">30day ($24 per views)</option>   
                <option value="42">60day ($42 per views)</option>   

              </select><br>
          </td>
          <td>
             <span class="clear-icon" id="sel" onclick="clearInput('duration')">&#10005;</span>
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
          <span class="clear-icon" id="sel" onclick="clearInput('geo')">&#10005;</span>
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
          <span class="clear-icon" id="sel"  onclick="clearInput('question')">&#10005;</span>
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
            <span class="clear-icon" id="sel"   onclick="clearInput('answer')">&#10005;</span>
           <label>Set answer to your question</label>
        </td></tr></div>
        <div class="user-box">
         <tr>
             
        <td>
          <input type="number" id="time"  name="time" value="{{$ads->time??''}}" placeholder="time in seconds ex: 10" maxlength="60" required="" class="form-control"><br>
        </td>
         <td>
            <span class="clear-icon" id="sel"  onclick="clearInput('time')">&#10005;


           <label>Set your desired minimum  time of watching time in sec</label>  </span><br>
        </td>
      </tr>
    </div>

    <tr>
        <td>
            <div style="float: right;">
             <h5 id="all" class="text-white">20$</h5> 
             <p id="single" class="text-white">1$ per views</p>
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
      var views=all*single;
      document.getElementById('all').innerHTML=views+"$";
      document.getElementById('single').innerHTML=single+"$ per views";
function calculate(){
  var all=document.getElementById('views').value;
    var single=document.getElementById('duration').value;
      var views=all*single;
      document.getElementById('all').innerHTML=views+"$";
      document.getElementById('single').innerHTML=single+"$ per views";
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
<p >You can create a video advertisement and purchase traffic for any website that does not break our rules. After your purchase is complete and your video is approved, Afonete users will view your ads for the amount of time you have chosen. Every time a user views your ad they will receive a small number of money.</p>

<p>You can pay VIDEO ads with your ammount on balance, or you can <a href="" style="color:red">Deposit</a> to add ammount to your balance.</p>
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