<?php
namespace App\Http\Controllers\admin;
 use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Balance;
    use Illuminate\Support\Facades\Auth;
use App\Models\ads;
use App\Models\video;
class manage extends Controller
{

    public function ads(){
        return view('admin.payclick');
    }
      public function adsDetail(){
        return view('admin.fomo.adsDetail');
    }
    public function clubs(){
        return view('admin.manage-clubs');
    }

    public function clicked(Request $request){
         $ads=$request->ads;
        return view('admin.ads')->with('id',$ads);
    }
     public function approveAds(Request $request){
      $ads=$request->id;

        $approved=array(
        'message'=> 'Ads approved well',
        'id'=> $ads, );
         $failed=array(
        'message'=> 'Ads approve failed',
        'id'=> $ads, );
      $approve=DB::UPDATE('UPDATE ads set status=:st where id=:id',['st'=>'approved','id'=>$ads]);
      if($approve)
      {

        return view('admin.fomo.adsDetail',$approved);
      }
      else{
        return view('admin.fomo.adsDetail',$failed);
      }
    }
     public function addAds(){
        return view('admin.add-ads');
    }

  public function rejectAds(Request $request){

    $ads=$request->ads;
    $reason=$request->reason;
    $status=array(
        'rejected'=>'Ads have been rejected',
        'id'=>$ads,
);
    $rejectedads=ads::where('id',$ads)->first();
    $rejectedads->update(['rejected_reason'=>$reason,'status'=>'rejected']);
         return view('admin.fomo.adsDetail',$status);
    }
public function createAds(Request $request)
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
      $ways = $request->ways;


    $name = "admin";

        $url = filter_var($url, FILTER_SANITIZE_URL);
        if (empty($url) && empty($banner) && $ways == 'both') {
            // dd("error both");
        return back()->with('error','empty');

        }
         if (isset($banner))
 {
    $file=rand(00000000,99999999).".png";
   $path=public_path('ads');
   $nama_file=$path . '/' . $file;
    file_put_contents($nama_file, file_get_contents($banner));

     // Handle file upload
     if ($request->file()) {
        $fileName = time() . '_' . $request->file->getClientOriginalName();
        $filePath = $request->file('file')->storeAs('ads', $fileName, 'public');

        // return back()
        //     ->with('success', 'File uploaded successfully.')
        //     ->with('file', $fileName);
    }

// if (!file_exists($path)) {
//     return view('admin.add-ads')->with('error','file');
// }

 }
// else{
//     $nama_file=NULL;
// }
if (!empty($url)) {
    // Add HTTP scheme if missing
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }

    // Validate the URL
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        // Initialize cURL
        $ch = curl_init();
        // dd($ch);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
        curl_setopt($ch, CURLOPT_HEADER, true);

        // Execute cURL request
        $headers = curl_exec($ch);
        // Get HTTP response code
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // Close cURL session
        curl_close($ch);
        // dd($http_code);
        // Check if the URL responds with a valid status
        if (!in_array($http_code, [200, 301, 302, 303, 304])) {
            return view('admin.add-ads')->with('error', 'url');
        }
    } else {
        // If the URL is not valid, return an error response
        return view('admin.add-ads')->with('error', 'url');
    }
}



        else{
    $url=NULL;
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
                     $ads->status='approved';
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
                                        // $adsEarn = round(($Earn / $total_FC), 4);
                                        foreach ($usersWithFC as $user) {
                                            // $balance=Balance::where('user',$user->user)->first();

                                            Balance::create(['user'=>$user->user,'ads_earning'=>$adsEarn]);
                                        }
                    }
                        return view ('admin.add-video')->with('message','success');
                    }
                    else{
                    return view ('admin.add-video')->with('message','error');

                    }
    }



    public function videos(){
        return view('admin.videos');
    }

     public function videoDetail(){

     return view('admin.fomo.videoDetail');
    }

      public function rejectVideo(Request $request){

    $ads=$request->video;
    $reason=$request->reason;
    $status=array(
        'rejected'=>'video have been rejected',
        'id'=>$ads,
);
    $rejectedads=video::where('id',$ads)->first();
    $rejectedads->update(['rejected_reason'=>$reason,'status'=>'rejected']);
         return view('admin.fomo.videoDetail',$status);
    }
    public function approveVideo(Request $request){
      $id=$request->id;
      $approve=DB::UPDATE('UPDATE videos set status=:st where id=:id',['st'=>'approved','id'=>$id]);
      if($approve)
      {
 $approved=array(
        'message'=> 'video approved well',
        'id'=> $id, );
        return view('admin.fomo.videoDetail',$approved);
      }
      else{
        $failed=array(
        'message'=> 'videos approve failed',
        'id'=> $id, );
        return view('admin.fomo.videoDetail',$failed);
      }
    }
      public function videoAds(){
        return view('admin.add-video');
    }


public function newVideo(){

    return view("admin.newvideo");
}


    public function createVideo(Request $request)
    {

        // $video=$request->video;

        // dd($request);
       $tittle=$request->tittle;

        $views=$request->views;
        $duration=$request->duration;
        $location=$request->geo;
         $question=$request->question;
        $answer=$request->answer;
        $time=$request->time;
        $earns = $request->user_earns;
        global $price;
          $price=$duration*0.004*$views;
           $Earn=$price*20/100;


         if (!$_FILES['video']['error'] === UPLOAD_ERR_OK) {
        //  echo "Error during file upload: " .
        $err=$_FILES['video']['error'];
         return back()->with('fail',$err);
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
                // dd('Failed to move file: ', $_FILES['video'], $FileName, error_get_last());
                return back()->with('error','video');
            }
            }
            else {

                return back()->with('error','File Is Too Large To Be Stored');
            }
        }
            else{

                return back()->with('error','type');

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
            $video->status='approved';
            $video->user=$name;
            $video->time=$time;
            $video->total_paid=$price;
            $video->user_paid=$price*50/100;
            $video->user='admin';
                    if ($video->save())
                 {
                    $usersWithFC = User::where('has_paid_package', 'fc2')->orWhere('has_paid_package', 'fc1')->get();
                    $total_FC = User::where('has_paid_package', 'fc2')->orWhere('has_paid_package', 'fc1')->count();
    if ($total_FC>0) {
        $adsEarn=$Earn/$total_FC;
        foreach ($usersWithFC as $user) {
            Balance::create(['user'=>$user->user,'ads_earning'=>$adsEarn]);
        }
    }
                        return back()->with('message','success');
                    }
                    else{
                    return back()->with('message','error');

                    }
    }
}
}
