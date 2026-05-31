<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class Users extends Controller
{
    public function add_user()
    {
        return view('admin.add-user');
    }
     public function post_user(Request $request )
    {
$user=new users();
$user->name=$request->name;
$user->email=$request->email;
$user->user=$request->user;
$user->password=$request->password;
$user->phone=$request->phone;
$user->country=$request->country;

    }
    }
