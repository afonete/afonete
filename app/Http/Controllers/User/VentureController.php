<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Contract;
use Illuminate\Support\Facades\Redirect;

class VentureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function contract(){

        return view("user.venture-contract");
    }

    public function SaveContract(Request $request)
    {

    $user = Auth::user();

    $name = $user->name;
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


                    $user->contract='Signed';
                    $pack=$user->has_paid_package;
                    $contract->name=$name;
                    $contract->user_id = $user->id;

                        $contract->contract=$file;
                    if ($user->save() && $contract->save()) {
                        return Redirect::route('user.dashboard')->with('message','You have been  activated Bifonex account ,
                         Enjoy unlimited earning on Bifonex');
                    }

                  return view('user.preview')->with('message',$file);
    }

   return view('user.venture.contract')->with('message',$nama_file);

   }





    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
