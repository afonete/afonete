<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Team extends Controller
{
    //
    public function index(){
        return view('user.team.structure');
    }
    public function create(){
        return view('user.team.createTeam');
    }
    public function summary(){
        return view('user.team.summary');
    }
}
