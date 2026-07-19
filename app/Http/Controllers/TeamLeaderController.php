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

        // Automatically log them in upon successful application
        Auth::login($user);

        // Put details in session for middleware and views
        session(['team_leader_status' => 'pending']);
        session(['team_leader_Usen_Name' => $user->user]);

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully',
            'username' => $user->user
        ]);
    }

    public function completeApplication(Request $request)
    {
        $request->validate([
            'username'  => 'required|string',
            'whatsapp'  => 'required|string|max:255',
            'instagram' => 'required|string|max:255',
        ]);

        $leader = TeamLeader::where('User_name', $request->username)->firstOrFail();
        $leader->update([
            'whatsapp'  => $request->whatsapp,
            'instagram' => $request->instagram,
        ]);

        return redirect()->back()->with('success', 'Application details updated successfully! The administration will check them shortly.');
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        $events = \App\Models\TeamLeaderEvent::where('user_id', $user->id)->latest()->get();
        $socials = \App\Models\TeamLeaderSocial::where('user_id', $user->id)->latest()->get();
        
        // Page 4: Admin Video Content
        $adminVideos = \App\Models\video::where('status', 'approved')->orWhere('status', 'active')->latest()->take(10)->get();
        
        // Page 5: Admin Advertisement Banners
        $adminBanners = \App\Models\ads::where('status', 'approved')->orWhere('status', 'active')->latest()->take(10)->get();

        return view('team-leader.dashboard', compact('events', 'socials', 'adminVideos', 'adminBanners'));
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'type'       => 'required|string|in:physical,zoom',
            'location'   => 'nullable|string|max:255',
            'zoom_link'  => 'nullable|string|max:500',
            'event_time' => 'required|date',
        ]);

        $user = Auth::user();

        // Check for any conflicting approved events scheduled at this exact time
        $conflict = \App\Models\TeamLeaderEvent::where('user_id', $user->id)
            ->where('event_time', $request->event_time)
            ->where('status', 'approved')
            ->exists();

        if ($conflict) {
            return redirect()->back()->with('error', 'Conflict Alert: You already have an approved event scheduled at this exact date & time!');
        }

        \App\Models\TeamLeaderEvent::create([
            'user_id'    => $user->id,
            'title'      => $request->title,
            'type'       => $request->type,
            'location'   => $request->location,
            'zoom_link'  => $request->zoom_link,
            'event_time' => $request->event_time,
            'status'     => 'pending',
        ]);

        return redirect()->back()->with('success', 'Event planned successfully! It has been submitted for admin approval.');
    }

    public function submitEventProof(Request $request, $id)
    {
        $request->validate([
            'proof_notes' => 'required|string|max:1000',
            'proof_file'  => 'nullable|file|mimes:jpg,jpeg,png,pdf,mp4|max:51200', // max 50MB
        ]);

        $event = \App\Models\TeamLeaderEvent::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        $path = null;
        if ($request->hasFile('proof_file')) {
            $path = $request->file('proof_file')->store('proof_of_payment', 'public');
        }

        $event->update([
            'proof_submitted' => true,
            'proof_files'     => $path,
            'proof_notes'     => $request->proof_notes,
            'proof_status'    => 'pending',
        ]);

        return redirect()->back()->with('success', 'Meeting proof uploaded successfully! The administration will audit and review your proof.');
    }

    public function storeSocial(Request $request)
    {
        $request->validate([
            'platform'     => 'required|string|max:50',
            'profile_link' => 'required|url|max:500',
            'views'        => 'nullable|integer|min:0',
        ]);

        \App\Models\TeamLeaderSocial::create([
            'user_id'      => Auth::id(),
            'platform'     => $request->platform,
            'profile_link' => $request->profile_link,
            'views_count'  => $request->views ?? 0,
            'status'       => 'pending',
        ]);

        return redirect()->back()->with('success', 'Social media ambassador link submitted successfully for review!');
    }

    public function showLoginForm()
    {
        return view('home.team-leader-login');
    }

    public function loginLeader(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');
        $password = $request->input('password');

        // Check if username or email matches
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'user';

        if (Auth::attempt([$field => $login, 'password' => $password])) {
            $request->session()->regenerate();

            // Verify they are a team leader
            $user = Auth::user();
            $teamLeader = \App\Models\TeamLeader::where('User_name', $user->user)->first();

            if ($teamLeader) {
                // Put details in session for middleware and views
                session(['team_leader_status' => $teamLeader->status]);
                session(['team_leader_Usen_Name' => $user->user]);

                if ($teamLeader->status === 'confirmed') {
                    if ($user->has_paid_package === 'TEAM_LEADER') {
                        return redirect()->route('team-leader.dashboard');
                    }
                    return redirect()->to(url('/team-leader/pending-approval?username='.$user->user));
                }

                if ($teamLeader->status === 'pending') {
                    return redirect()->to(url('/team-leader/pending-approval?username='.$user->user));
                }

                if ($teamLeader->status === 'rejected') {
                    return redirect()->to(url('/team-leader/rejected?username='.$user->user));
                }
            }

            // Fallback if not a leader
            return redirect()->route('user.dashboard');
        }

        return redirect()->back()
            ->withInput($request->only('login'))
            ->withErrors(['login' => 'These credentials do not match our team leader records.']);
    }

    public function logoutLeader(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('team-leader.login')->with('success', 'You have been logged out.');
    }

}