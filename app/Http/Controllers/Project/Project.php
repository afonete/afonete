 <?php
 namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Balance;
use Illuminate\Support\Facades\DB;
use App\Models\ads;
use App\Models\video;
class Project extends Controller
{
    public function Showproject()
    {
        return view('user.project.project');
    }
    
    
 public function Showptc(Request $request)
    {
        $id=$request->id;
        return view('user.project.ptc')->with('id',$id);
    }
    
    
    public function Showvideo()
    {
        return view('user.project.video');
    } 
    
     public function Showmyptc()
    {
        return view('user.project.myptc');
    }
    
    
      public function Showmyvideo()
    {
        return view('user.project.myvideo');
    }
    
    
     public function Storeptc(Request $request)
    {
        $url=$request->url;
        $banner=$request->file;
        $tittle=$request->tittle;
        $desc=$request->description;
        $views=$request->views;
        $duration=$request->duration;
        $location=$request->geo;
         $question=$request->question;
        $answer=$request->answer;
      $price=$duration*0.004*$views;
      $Earn=($price*20/100);

     $usedValue = number_format((float)$price, 4, '.', '');
    $user = Auth::user();

    $name = $user->user;

        $url = filter_var($url, FILTER_SANITIZE_URL);
        if (empty($url) && empty($banner)) {
           
        return view('user.project.ptc')->with('error','empty');
        
        }
         if (isset($banner))
 {
    $file=rand(00000000,99999999).".png";
   $path=public_path('ads');
   $nama_file=$path . '/' . $file;
    file_put_contents($nama_file, file_get_contents($banner)); 
if (!file_exists($path)) {
    return view('user.project.ptc')->with('error','file');
}

 }
else{
    $nama_file=NULL;
}
        if (!empty($url)) {
          if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        if (filter_var($url, FILTER_VALIDATE_URL)) {
       // Get headers of the URL
        $headers = get_headers($url);

        // Check if the URL responds with a valid status
        if (!preg_match('/^HTTP\/\d\.\d\s+(200|301|302|303|304)/', $headers[0])) {
                        return view('user.project.ptc')->with('error','url');

        } }
        
        else {
        // If the URL is not valid, return an error response
       return view('user.project.ptc')->with('error','url');
    }       
        }
        else{
    $url=NULL;
}
$select=DB::SELECT("SELECT sum(deposit) as deposit from balances where user='$name' ");
$select2=DB::SELECT("SELECT sum(used) as deposit from balances where user='$name' ");
$deposited=$select[0]->deposit;
$used=$select2[0]->deposit;
$remain=$deposited-$used;
if($remain<$price)
{
  return view('user.project.ptc')->with('less','Not enough money, deposit to upload ads'); 
}
          $ads=new ads();
          
         
                  $ads->url=$url;
                   $ads->banner=$file;
                    $ads->tittle=$tittle;
                    $ads->description=$desc;
                    $ads->targeted_views=$views;
                    $ads->targeted_duration=$duration;
                    $ads->location=$location;
                    $ads->question=$question;
                    $ads->answer=$answer;
                     $ads->status='pending';
                         $ads->total_paid=$price;
                     $ads->user_paid=$price*50/100;
                    $ads->user=$name;
                  
                   
                    if ($ads->save())
                 {
                     $usersWithFC = User::where('has_paid_package', 'fc2')
    ->orWhere('has_paid_package', 'fc1')
    ->get();
         $total_FC = User::where('has_paid_package', 'fc2')
    ->orWhere('has_paid_package', 'fc1')
    ->count();
    if ($total_FC>0) {
        // code...
    
$adsEarn=$Earn/$total_FC;
    foreach ($usersWithFC as $user) {

        Balance::create(['user'=>$user->user,'ads_earning'=>$adsEarn]);
    }
}

                      $update=DB::INSERT("INSERT into  balances (user,created_at,used) values('$name',now(),'$usedValue')");
                        return view ('user.project.ptc')->with('message','success');
                    }
                    else{
                    return view ('user.project.ptc')->with('message','error');

                    }
    }
    
    
     public function Storevideo(Request $request)
    {
       
        // $video=$request->video;
        $tittle=$request->tittle;
       
        $views=$request->views;
        $duration=$request->duration;
        $location=$request->geo;
         $question=$request->question;
        $answer=$request->answer;
        $time=$request->time;
          $price=$duration*0.004*$views;
           $Earn=($price*20/100);
    $usedValue = number_format((float)$price, 4, '.', '');
    $user = Auth::user();

    $name = $user->user;
    
    if (!$_FILES['video']['error'] === UPLOAD_ERR_OK) {
        //  echo "Error during file upload: " . 
        $err=$_FILES['video']['error'];
         return view('user.project.video')->with('fail',$err);
        }

        else{
             // Check if the uploaded file is a video
        $allowedVideoTypes = ['video/mp4'];
        $uploadedFileType = $_FILES['video']['type'];
        if ($uploadedFileType =="video/mp4") {
            $uploadedFileSize = $_FILES['video']['size'];
            $maxFileSize = 62914560; // 60MB in bytes

            // Get the details of the uploaded file
       

        // Check if the file size exceeds the limit
        if ($uploadedFileSize <= $maxFileSize) {
                $targetDir = public_path('video/');
                 global $FileName;
                $file=uniqid() . basename($_FILES['video']['name']);
                $FileName = $targetDir . $file;
               
                 
            if (!move_uploaded_file($_FILES['video']['tmp_name'], $FileName)) {
                return view('user.project.video')->with('error','video');
            }
        }
        else {
            return view('user.project.video')->with('error','size');
        }
}
else{
            return view('user.project.video')->with('error','type');

}
$select=DB::SELECT("SELECT sum(deposit) as deposit from balances where user='$name' ");
$select2=DB::SELECT("SELECT sum(used) as deposit from balances where user='$name' ");
$deposited=$select[0]->deposit;
$used=$select2[0]->deposit;
$remain=$deposited-$used;
if($remain<$price)

{
  return view('user.project.video')->with('less','Not enough money, deposit to upload video'); 
}
  
          $video=new video();
          
         
               
                   $video->video=$file;
                    $video->tittle=$tittle;
                   
                    $video->targeted_views=$views;
                    $video->targeted_duration=$duration;
                    $video->location=$location;
                    $video->question=$question;
                    $video->answer=$answer;
                       $video->time=$time;
                     $video->status='pending';
                    $video->user=$name;
                     $video->time=$time;
                      $ads->total_paid=$price;
                     $ads->user_paid=$price*50/100;
                    if ($video->save())
                 {
            $usersWithFC = User::where('has_paid_package', 'fc2')
    ->orWhere('has_paid_package', 'fc1')
    ->get();
         $total_FC = User::where('has_paid_package', 'fc2')
    ->orWhere('has_paid_package', 'fc1')
    ->count();
    if ($total_FC>0) {
        // code...
    
$adsEarn=$Earn/$total_FC;
    foreach ($usersWithFC as $user) {

        Balance::create(['user'=>$user->user,'ads_earning'=>$adsEarn]);
    }
}
                     $update=DB::INSERT("INSERT into  balances (user,used,created_at) values('$name','$usedValue'',now())");
                        return view ('user.project.video')->with('message','success');
                    }
                    else{
                    return view ('user.project.video')->with('message','error');

                    }
    }
}
}
