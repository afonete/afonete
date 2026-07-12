<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

    use Illuminate\Support\Facades\Auth;

    use App\Models\User;
    use App\Models\Contract;
    use App\Models\Payment as Paymodel;

    use App\Models\adventures;
    use App\Models\FCpackage;
    use App\Models\Claim;

use Illuminate\Support\Facades\Redirect;
class UserpackageController extends Controller
{
   
    
    private function MyDepositBalance(){
        $user=Auth::user();
        $userId = $user->id;
        $user = User::find($userId);



        $deposits = $user->deposits->filter(function ($deposit) {
            return $deposit->status == 'approved';
        });
        $deposits_used = $user->deposits->filter(function ($deposit) {
            return $deposit->status == 'used';
        });
    $differences = $deposits->sum('amount_deposited') - $deposits_used->sum('amount_removed');

     $sum = $differences;   

     return $sum;
    }

    public function buypackages(){
        $adventures = adventures::all();
        $MyDepositBalance = $this->MyDepositBalance();
        $fc = FCpackage::all();
        
        return view("user.buypackages",['adventures'=>$adventures,'balance'=>$MyDepositBalance,'fc'=>$fc]);
    }

    public function claim(Request $request){
        $user = Auth::user();
      
        $transaction = $user->deposits->where("transaction_id",$request->transactionId)->first();
        if (!$transaction) {
            return back()->with('error', 'This transaction was not found.');
        }
        if ($transaction->status == 'approved') {
            return back()->with('error', 'This transaction is already approved.');
        }
        $existingClaim = Claim::where('user_id', auth()->id())
        ->where('transaction_no', $request->transactionId)->where("is_fixed",false)
        ->first();
        if ($existingClaim) {
        //  dd($existingClaim);

            return back()->with('error', 'You have already submitted the request approval for this transaction.');
        }

        Claim::create([
            'user_id' => $user->id,
            'transaction_no' => $request->transactionId,
            "claim_type"=>"DEPOSIT",
            'reason' => "Requesting to approve my deposit",
        ]);
                  
   
        return back()->with('success', 'Your deposit approval request has been submitted. Please wait a moment.');
    

    }


    public function deposits(){
        // dd($request);
        // $data = [
        //     "package"=>$request->get('package'),
        //     "category"=>$request->get('category'),
        //     "amount"=>$request->get('amount')
        // ];
        // dd("asdas");
        $user = Auth::user();
        $deposits_pending = $user->deposits->where('status', 'pending')->first();
        // dd($deposits_pending);


        return view("user.otherpayment",["have_pending_deposits"=>$deposits_pending]);
    }
    public function index()
    {
        $user=Auth::user();
        $userId = $user->id;
        $user = User::find($userId);

        $package = Paymodel::where("user",$userId)
        ->where("is_expired",false)
        ->first();
        $deposits = $user->deposits->filter(function ($deposit) {
        return $deposit->status == 'approved';
        });
        $sum = $deposits->sum('amount_deposited');

        $adventures = adventures::all();

        if($user->has_free_package == "yes"){

            return redirect()->route("user.buypackage");
        }

        return view('user.venture-package',["deposits"=>$sum,"adventures"=>$adventures]);
    }
    public function UserPackage()
    {
        $package = FCpackage::all();


        return view('user.user-package',["packages"=>$package]);
    }

public function contract()
    {
return view('user.contract');

    }

public function Savecontract(Request $request)
    {

    $user = Auth::user();
    $userId = $user->id;


    $name = $user->user ?? $user->name;
    $contract=new Contract();

   $sig_string=$request->signature;
   $file=$name.".png";
   $path=public_path('signature');
   $nama_file=$path . '/' . $file;
   if (file_exists($nama_file)) {
     unlink($nama_file);
   }
    file_put_contents($nama_file, file_get_contents($sig_string));

   if(file_exists($nama_file)){
    // return ('well');
    // $path_to_file='signature /'.$file;


                    $user->contract='Signed';


                    $pack=$user->has_paid_package;
                    $contract->name=$name;
                   $contract->contract=$file;
                   $contract->user_id = $userId;


                    if ($user->save() && $contract->save()) {
                     return Redirect::route('user.dashboard')->with('message','You have been  activated Fonepo account , Enjoy unlimited earning on Fonepo');
                    }


    return view('user.preview')->with('message',$file);
     // echo "<p>File Signature saved well - ".$nama_file."</p>";
     // echo "<p style='border:solid 1px teal;width:355px;height:110px;'><img src='".asset($nama_file)."'></p>";
   }

    return view('user.contract')->with('message',$nama_file);

    }


    public function Confirmcontract()
    {
                    $user = Auth::user();

                    $name = $user->user;

                    // $user = User::find($name);

                    $user->contract='Signed';
                    $pack=$user->has_paid_package;
                    // return ($user);
if ($user->save()) {
  return Redirect::route('user.dashboard')->with('message','You have been  activated '.$pack.' account , Enjoy unlimited earning on Fonepo');
}
return view('user.previeu');

    }

}



