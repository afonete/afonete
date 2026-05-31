<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Claim;
use App\Models\Deposits;


class ClaimController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkNewClaim(){
        $unfixedClaims = Claim::where("is_fixed",false)->count();
        
        return response()->json(["new_claims"=>$unfixedClaims]);
    }
    public function index()
    {
            
        $claims = Claim::orderBy("is_fixed","asc")->paginate(10);
        $unfixedClaims = Claim::where("is_fixed",false)->count();
        // dd($unfixedClaims);
        // dd($claims);
        return view("admin.claims.ClaimList",['claims'=> $claims,"unfixedClaims"=>$unfixedClaims]);
    }

    public function fixClaim(Request $request){

          $claim = Claim::where("id",$request->claim)->first();
          $deposit = Deposits::where("transaction_id",$request->transcation)->first();
            if(!$deposit){
                dd("Invalid Transaction Id");
            }
            if($request->type == "fixed"){
                $claim->is_fixed = true;
            }
            if($request->type == "unfixed"){
                $claim->is_fixed = false;
            }
            $deposit->status = "approved";

            
            if($claim->save() && $deposit->save()){
                return back()->with("success","Claim was successfull ".$request->type);
            }
            else{
                return back()->with("error","Claim was failed to be ".$request->type);

            }
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
