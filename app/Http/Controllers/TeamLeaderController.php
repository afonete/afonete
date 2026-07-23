<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Models\TeamLeader;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class TeamLeaderController extends Controller
{
    public function index()
    {
        return view('home.team-leader');
    }

    /**
     * Proxy the REST Countries API server-side to avoid browser CORS
     * issues caused by the API's 301 redirect to a CDN that lacks
     * Access-Control-Allow-Origin headers.
     *
     * Results are cached for 24 hours — country data rarely changes.
     */
    public function countries()
    {
        $countries = Cache::remember('rest_countries_list', 86400, function () {
            try {
                $response = Http::timeout(15)
                    ->get('https://restcountries.com/v3.1/all', [
                        'fields' => 'name,idd',
                    ]);

                if (!$response->successful()) {
                    return [];
                }

                $data = $response->json();

                // Exclude Chile & Israel, then sort A-Z
                $excluded = ['Chile', 'Israel'];

                $filtered = array_values(array_filter($data, function ($c) use ($excluded) {
                    return !in_array($c['name']['common'] ?? '', $excluded);
                }));

                usort($filtered, function ($a, $b) {
                    return strcasecmp(
                        $a['name']['common'] ?? '',
                        $b['name']['common'] ?? ''
                    );
                });

                // Slim down to only what the frontend needs
                return array_map(function ($c) {
                    return [
                        'name' => $c['name']['common'] ?? '',
                        'dial' => $c['idd']['root'] ?? '',
                    ];
                }, $filtered);
            } catch (\Exception $e) {
                \Log::error('REST Countries proxy failed: ' . $e->getMessage());
                return [];
            }
        });

        return response()->json($countries);
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
            'username'          => 'required|string',
            'whatsapp'          => 'required|string|max:255',
            'instagram'         => 'required|string|max:255',
            'leadership_level'  => 'required|string|in:TEAM_LEADER,SUPER_LEADER',
        ]);

        $leader = TeamLeader::where('User_name', $request->username)->firstOrFail();
        $leader->update([
            'whatsapp'          => $request->whatsapp,
            'instagram'         => $request->instagram,
            'leadership_level'  => $request->leadership_level,
        ]);

        return redirect()->back()->with('success', 'Application details updated successfully! The administration will check them shortly.');
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        $events = \App\Models\TeamLeaderEvent::where('user_id', $user->id)->latest()->get();
        $socials = \App\Models\TeamLeaderSocial::where('user_id', $user->id)->latest()->get();
        
        // Page 4: Official Videos (from dedicated team_leader_videos table)
        $adminVideos = \App\Models\TeamLeaderVideo::whereIn('status', ['approved', 'active'])
            ->orderBy('sort_order')->latest()->get();
        
        // Page 5: Marketing Banners (from dedicated team_leader_banners table)
        $adminBanners = \App\Models\TeamLeaderBanner::whereIn('status', ['approved', 'active'])
            ->orderBy('sort_order')->latest()->get();

        // SUPER LEADER credit info
        $teamLeader = \App\Models\TeamLeader::where('User_name', $user->user)->first();
        $credit = null;
        if ($teamLeader) {
            $credit = $teamLeader->superLeaderCredit;
        }

        return view('team-leader.dashboard', compact('events', 'socials', 'adminVideos', 'adminBanners', 'credit', 'teamLeader'));
    }

    /**
     * ALL-IN-ONE page — combines every team leader feature on a single page.
     */
    public function allFeatures()
    {
        $user = Auth::user();
        $teamLeader = \App\Models\TeamLeader::where('User_name', $user->user)->first();

        // Activation / duration
        $activation = \App\Models\Activations::where('email', $user->email)
            ->whereIn('package', ['TEAM_LEADER', 'SUPER_LEADER'])->first();

        $durationDays   = $activation ? (int)($activation->period ?? 60) : 60;
        $activationDate = $activation ? \Carbon\Carbon::parse($activation->updated_at) : null;
        $expiryDate     = $activationDate ? $activationDate->copy()->addDays($durationDays) : null;
        $tasks          = $activation ? $activation->task : '';

        // Credit (SUPER LEADER only)
        $credit = $teamLeader ? $teamLeader->superLeaderCredit : null;

        // Events (split by type)
        $allEvents   = \App\Models\TeamLeaderEvent::where('user_id', $user->id)->latest()->get();
        $planEvents  = $allEvents->where('event_type', 'plan');
        $zoomEvents  = $allEvents->where('event_type', 'zoom');

        // Event images for carousel
        $eventImages = $allEvents->pluck('event_image_1')->filter();
        foreach ($allEvents as $e) {
            if ($e->event_image_2) {
                $imgs = explode(',', $e->event_image_2);
                foreach ($imgs as $img) {
                    if (trim($img)) {
                        $eventImages->push(trim($img));
                    }
                }
            }
        }
        $eventImages = $eventImages->values();

        $announcements = \App\Models\TeamLeaderAnnouncement::where('is_active', true)
            ->orderBy('sort_order')
            ->latest()
            ->get();

        // Socials
        $socials = \App\Models\TeamLeaderSocial::where('user_id', $user->id)->latest()->get();

        // Videos & Banners
        $adminVideos  = \App\Models\TeamLeaderVideo::whereIn('status', ['approved', 'active'])->orderBy('sort_order')->latest()->get();
        $adminBanners = \App\Models\TeamLeaderBanner::whereIn('status', ['approved', 'active'])->orderBy('sort_order')->latest()->take(6)->get();

        // Referral stats for charts
        $directReferrals  = $user->referrals()->count();
        $activeReferrals  = $user->referrals()->where('has_paid_package', '!=', 'no')->where('has_paid_package', '!=', '')->count();

        return view('team-leader.all', compact(
            'teamLeader', 'activation', 'durationDays', 'activationDate', 'expiryDate',
            'tasks', 'credit', 'allEvents', 'planEvents', 'zoomEvents', 'eventImages',
            'socials', 'adminVideos', 'adminBanners', 'directReferrals', 'activeReferrals',
            'announcements'
        ));
    }

    /**
     * Store event plan or zoom report from the ALL page.
     */
    public function storeEventReport(Request $request)
    {
        $request->validate([
            'event_type' => 'required|in:plan,zoom',
        ]);

        $data = [
            'user_id'    => Auth::id(),
            'title'      => $request->event_type === 'plan' ? 'Event Plan Report' : 'Zoom Session Report',
            'type'       => $request->event_type === 'zoom' ? 'zoom' : 'physical',
            'event_type' => $request->event_type,
            'status'     => 'pending',
        ];

        if ($request->event_type === 'plan') {
            if ($request->hasFile('event_image_1')) {
                $data['event_image_1'] = $request->file('event_image_1')->store('event_images', 'public');
            }
            if ($request->hasFile('event_image_2')) {
                $files = $request->file('event_image_2');
                if (is_array($files)) {
                    $paths = [];
                    foreach ($files as $file) {
                        $paths[] = $file->store('event_images', 'public');
                    }
                    $data['event_image_2'] = implode(',', $paths);
                } else {
                    $data['event_image_2'] = $files->store('event_images', 'public');
                }
            }
            $data['proof_notes']    = $request->description ?? '';
            $data['event_done_on']  = $request->event_done_on ?? null;
            $data['hotel_location'] = $request->hotel_location ?? '';

            $time = $request->event_time ?? now()->format('H:i');
            $data['event_time'] = $request->event_done_on . ' ' . $time . ':00';
        } else {
            $data['zoom_link'] = $request->zoom_link ?? '';
            $data['country']   = $request->country ?? '';
            $data['place']     = $request->place ?? '';
            $data['location']  = $request->location ?? '';
            $data['event_date']= $request->event_date ?? null;

            $time = $request->event_time ?? now()->format('H:i');
            $data['event_time'] = $request->event_date . ' ' . $time . ':00';
        }

        \App\Models\TeamLeaderEvent::create($data);

        return redirect()->back()->with('success', ucfirst($request->event_type) . ' report submitted successfully!');
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
                    // Check if activated (has_paid_package set to TEAM_LEADER or SUPER_LEADER)
                    $activated = in_array($user->has_paid_package, ['TEAM_LEADER', 'SUPER_LEADER']);

                    // Self-repair: if the package was overwritten by the free-access
                    // middleware, restore it from the activations table.
                    if (!$activated) {
                        $usedActivation = \App\Models\Activations::where('email', $user->email)
                            ->whereIn('package', ['TEAM_LEADER', 'SUPER_LEADER'])
                            ->where('stutus', 'used')
                            ->first();

                        if ($usedActivation) {
                            $user->has_paid_package = $usedActivation->package;
                            $user->has_free_package = 'no';
                            $user->save();
                            $activated = true;
                        }
                    }

                    if ($activated) {
                        // Already activated → go to user dashboard.
                        // The 'contract' middleware will intercept and force
                        // contract signing before granting dashboard access.
                        return redirect()->route('user.dashboard');
                    }

                    // Confirmed but NOT activated → send to pending-approval
                    // where they'll see their activation code + "Activate Now"
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