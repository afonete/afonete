<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\adventures;

class AdventureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $adventure = adventures::paginate(10);

        return view ("admin.uvp.Adventures",["adventures"=>$adventure]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        return view("admin.uvp.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:200',
            'plan' => 'required|string|max:200',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|min:0',
            'percentage' => 'required|numeric|min:0|max:100',
            'duration' => 'required|integer|min:1',
            'total_return' => 'required|string|max:10',
            'currency' => 'required|string|max:3',
            'current_price' => 'required|numeric'
        ]);
//
        // dd($validatedData['current_price']);
        // Create a new Adventure instance with the validated data
        $adventure = adventures::create([
            'name' => $validatedData['name'],
            'plan' => $validatedData['plan'],
            'min_amount' => $validatedData['min_amount'],
            'max_amount' => $validatedData['max_amount'],
            'percentage' => $validatedData['percentage'],
            'duration' => $validatedData['duration'],
            'total_return' => $validatedData['total_return'],
            'currency' => $validatedData['currency'],
            "current_price"=>$validatedData['current_price']
        ]);



        return redirect()->back()->with('message','Adventure Created Successfully!');
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
    public function edit(adventures $adventure)
    {
        //
        // dd($adventure->name);

          return view("admin.uvp.update",["adventure"=>$adventure]);
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
    // Validate the request data
    // dd($request);
    $validatedData = $request->validate([
        'name' => 'required|string|max:200',
        'plan' => 'required|string|max:200',
        'min_amount' => 'required|numeric|min:0',
        'max_amount' => 'required|numeric|min:0',
        'percentage' => 'required|numeric|min:0|max:100',
        'duration' => 'required|integer|min:1',
        'total_return' => 'required|string|max:10',
        'currency' => 'required|string|max:3'
    ]);

    // Find the adventure by id
    $adventure = adventures::findOrFail($id);



    // Update the adventure with the validated data
    $adventure->update([
        'name' => $validatedData['name'],
        'plan' => $validatedData['plan'],
        'min_amount' => $validatedData['min_amount'],
        'max_amount' => $validatedData['max_amount'],
        'percentage' => $validatedData['percentage'],
        'duration' => $validatedData['duration'],
        'total_return' => $validatedData['total_return'],
        'currency' => $validatedData['currency']
    ]);

    // Redirect back with a success message
    return redirect()->back()->with('message', 'Adventure updated successfully!');
}



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the adventure by id
        $adventure = adventures::findOrFail($id);

        // Delete the adventure
        $adventure->delete();

        // Redirect back to the previous page with a success message
        return redirect()->back()->with('success', 'Adventure deleted successfully!');
    }


    public function investors($id){
        $uvp = adventures::findOrFail($id)->payments()->paginate(15);


        return view("admin.uvp.investors",["investors"=>$uvp]);

    }


}
