<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Models\TeamLeader;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class TeamLeaderController extends Controller
{
    public function index()
    {
        return view('home.team-leader');
    }

    public function apply(Request $request)
    {
        // First check for existing email or phone or username
        $existingUser = TeamLeader::where('User_name', $request->username)->first();
        if ($existingUser) {
            return response()->json([
                'success' => false,
                'message' => 'This user name is already registered'
            ], 422);
        }

        $existingEmail = TeamLeader::where('Email', $request->email)->first();
        if ($existingEmail) {
            return response()->json([
                'success' => false,
                'message' => 'This email is already registered'
            ], 422);
        }

        $existingPhone = TeamLeader::where('Phone', $request->phone)->first();
        if ($existingPhone) {
            return response()->json([
                'success' => false,
                'message' => 'This phone number is already registered'
            ], 422);
        }



        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:team_leaders,User_name',
            'email' => 'required|email|max:255',
            'country' => 'required|string',
            'phone' => [
                'required',
                'string',
                'regex:/^([0-9\s\-\+\(\)]*)$/'
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        TeamLeader::create([
            'Names' => $validated['name'],
            'User_name' => $validated['username'],
            'Email' => $validated['email'],
            'Country' => $validated['country'],
            'Phone' => $validated['phone'],
            'status' => 'pending'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'user' => $validated['username'],
            'country' => $validated['country'],
            'activation' => rand(1111111, 9999999),
            'father' => null,
            'has_request' => 'registed',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully'
        ]);
    }



}
