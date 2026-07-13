<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\FCpackage;

class FCpackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = FCpackage::orderBy('created_at', 'desc')->get();


        return view("admin.fc.fcpackage",[
            "Adventures"=>$packages
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view("admin.fc.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

          $result = FCpackage::create($request->validate([
            "name"=>'required|string|unique:fcspackages,name',
            "price"=>'required|numeric|unique:fcspackages,price,except,id',
          ]));

        return back()->with("message","Package Created Successfully");
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
        $packages = FCpackage::find($id);
        if(!$packages) return back();


        return view("admin.fc.update",[
            "adventure"=>$packages
        ]);
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
    $fcpackage = FCpackage::findOrFail($id);

    $fcpackage->update($request->validate([
        "name" => 'required|string|unique:fcspackages,name,' . $id,
        "price" => 'required|numeric|unique:fcspackages,price,' . $id,
    ]));

    return back()->with("message", "Package Updated Successfully");
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fcpackage = FCpackage::findOrFail($id);
        $fcpackage->delete();

        return back()->with("message", "Package Deleted Successfully");
    }
    public function investors($id){
        $package = FCpackage::findOrFail($id);

        $uvp =  $package->payments()->paginate(15);

        return view("admin.fc.investors",["investors"=>$uvp,"package"=>$package->name]);

    }

}
