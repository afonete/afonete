<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;

class TasksController extends Controller
{
    
   public function task(){
        return view('admin.task');
    }


    public function store(Request $request)
    {
       $task=$request->task;
       $desc=$request->input('desc');
       $days=$request->day;
        $months=$request->month;
        $code=$request->code;


      $store=new Task();
      $store->task=$task;
      $store->description=$desc;
     
      $store->month=$months;
      $store->code=$code;
$message="Task successfuly added";
      if ($store->save()) {
          return view('admin.task',compact('message','code'));
      }

return view('admin.task')->with('message','Task not added');

           }
    }
