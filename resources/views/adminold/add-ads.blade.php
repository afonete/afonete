 <?php
    use Illuminate\Support\Facades\DB;
    use App\Models\ads;
    $id=request()->query('ads');
$ads=ads::find($id);
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
          <li><a  href="{{route('admin.ads')}}">Manage ads</a></li>
        <li><a  href="{{route('admin.videos')}}">Manage videos</a></li>
        <li><a  href="#">Manage download</a></li>
        <li class="active"><a  href="{{route('admin.video.create')}}">Add ads</a></li>
          
          </ul>
          <!-- <h3> Create a New PTC (Paid to Click) Advertisement</h3> -->
     
    <!-- /.content-header -->

    <!-- Main content -->
  <div class="content">
      <div class="container">
      <div class="row">
        <div class="col-md-6">
          
            <div class="card-body ">
              <div class="info">
  
                  <center>
                    <div class="text-center "><h3 class="font-size-20 "> Create a New PTC (Paid to Click) <br>Advertisement</h3> 
                    <hr><br>
                
</body> 
</html>

                   </div>
                 
                <!-- /.info-box-content -->

       <div class="add_ads" style="background:#1c1d20">
  <form id="adForm" method="post" action="{{route('user.ptc')}}" enctype="multipart/form-data">
    @csrf
  <div>
      @if($message=='success')

    <span class="btn btn-success">
      Click View has been uploaded
    </span>
    @endif
    @if($message=='error')
    <span class="btn btn-danger">
      Failed to upload Click View
    </span>
    @endif
    @if($error=='url')
      <span class="btn btn-danger">
      Invalid url, Upload new link
    </span>
    @endif
    @if($rror=='empty')
      <span class="btn btn-danger">
      Please choose url link or banner to upload
    </span>
    @endif
      @if($rror=='file')
      <span class="btn btn-danger">
      failed to upload banner
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
     
        <div class="user-box" >
           <td colspan="2">
              <select onchange="choose(this.value)" class="form-control"> 
                <option value="both">Add banner and Url to upload  your advertise</option>
                <option value="link">Add Url link of your business</option>
                <option value="banner">Create and upload banner of your business</option>
              </select><br>
           </td>
         </div>
     
    </tr>
        
    <tr class="my-3">
    
   
   <div class="">
      <tr class="user-box-add" id="urli">
        <td>
          <input type="url" name="url" style="color: black;  width: 240px;" id="url" placeholder="ex:http://www.example.com" value="{{$ads->url??''}}"  class="form-control">
            <br>
        </td>
        <td>
          <span class="clear-icon" id="sel"  onclick="clearInput('url')">
        </span><label>Ad Url link</label>
        </td>
      </tr>
    </div>  
     
        

<div class="" >
      <tr class="user-box-add" id="file">
        <td>
          <input type="file" name="file" style="color: black; width: 240px;" class="form-control"><br>
        </td>
        <td>
          <span class="clear-icon" id="sel"  onclick="clearInput('file')"></span>
          Add Banner, allowed format<br> [jpg,jpeg,png]
        </td>
      </tr>
    </div>  


    <div class="">
      <tr class="user-box-add">
        <td>
          <input type="text" id="titt" oninput="changetittle(this.value)"  name="tittle" maxlength="25" value="{{$ads->tittle??''}}" required="" class="form-control" placeholder="Add tittle of your ads"><br>
        </td>
        <td>
          <span class="" id="sel" onclick="clearInput('titt')"></span>
          <label>Ad tittle</label>
        </td>
      </tr>
    </div>  
         <div class="mt-5 d-block"></div>
     <div class="user-box ">
  
        <tr>
          <td>
                <textarea type="text" id="description" oninput="changedesc(this.value)" name="description" value="{{$ads->description??''}}" placeholder="Describe your ads in detail" required="" class="form-control"></textarea><br>
          </td>
          <td>
            <span class="clear-icon" id="sel"  onclick="clearInput('description')"></span>
            <label>Ad description</label>
          </td>
      </tr>

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
          </select><br>
        </td>
        <td>
          <span class="clear-icon" id="sel" onclick="clearInput('views')"></span>
            <label class="label text-sm">Total Views / Clicks</label>
            
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
              <label class="label">View Duration/Time:</label>
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
           <label class="label">Geo Targeting:</label>
            
        </td>
      </tr>

    </div>
    
    <div class="user-box">
      <tr>
        <td>
          <input type="text" id="question" value="{{$ads->question??''}}" name="question" placeholder="ex: tell the color"  maxlength="60" required="" class="form-control">
      <br>  </td>
        <td>
          <span class="clear-icon" id="sel"  onclick="clearInput('question')"> <span id='hideq'>          Set question related your ads</span><span>

        </td>
      </tr>
     

    </div>

     <div class="user-box" id>
      <tr>
        <td>
          <input type="text" id="answer"  name="answer" value="{{$ads->answer??''}}" placeholder="ex: green" maxlength="60" required="" class="form-control"><br>
        </td>
        <td>
          <span id='hidea'>  <span class="clear-icon" id="sel"  onclick="clearInput('answer')"></span>
           Set answer to your question</span>
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
         <style type="text/css">
           #show{
            display: none;
           }
            #file{
            display: none;

           }
 #urli{
            display: none;
           }

         </style>

 
  <script>
   
   
    var all=document.getElementById('views').value;
    var single=document.getElementById('duration').value;


      var views=all*single*0.004;
      document.getElementById('all').innerHTML=views+"$ to be paid";
      document.getElementById('single').innerHTML= single*0.004+"$ per views";
function calculate(){
    
  var all=document.getElementById('views').value;
    var single=document.getElementById('duration').value;

      var views=all*single*0.004;
      document.getElementById('all').innerHTML=views+"$ to be paid";
      document.getElementById('single').innerHTML=single*0.004+"$ per views";
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
             ;


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
<p >You can create a PTC (Paid to Click) advertisement and purchase traffic for any website that does not break our rules. After your purchase is complete and your ad is approved, Fonepo users will view your website for the amount of time you have chosen. Every time a user views your ad they will receive a small number of money.</p>

<p>You can pay PTC ads with your main Coin balance, or you can <a href="{{route('user.dashboard.deposit')}}" style="color:red">Deposit</a> to add Coins to your balance.</p>
                </div>

  <h4 class="font-size-20" style="color: #888b95;">
                <br>  Advertisement Guidelines
   
   <style type="text/css">
     li{
      color: #e7e7e7;
     }
   </style>           <hr>  </h4> </center>
<ul>
  <li>No URL shorteners</li>
<li>No illegal products or services (weapons, drugs, escorts etc.)</li>
<li>No malicious websites or frame breakers / frame busters.</li>
<li>No malware, viruses, spyware or ransomware.</li>
<li>No infinite redirect loops.</li>
<li>No browser locking scripts or other attempts to hijack the browser.</li>
<li>No copyright infringing material.</li>
<li>No x-rated adult content.</li></ul>
              </div>
            </div>
          </div>
       
 
      </div>
       <!-- END OF cl -->
    
 </div>