<?php

namespace App\Http\Middleware;

use App\Models\TeamLeader;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserhasPaidPackage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // ── Admins always pass ──
        if ($user->utype === 'ADM') {
            return $next($request);
        }

        // ── TEAM LEADER / SUPER LEADER with activated package → always pass ──
        // This prevents the free-access overwrite and any condition edge-cases
        // from blocking confirmed, activated leaders.
        $isLeaderPackage = in_array($user->has_paid_package, ['TEAM_LEADER', 'SUPER_LEADER']);

        if ($isLeaderPackage) {
            return $next($request);
        }

        // ── Self-repair: if a confirmed leader's package was overwritten ──
        // to 'standard' or 'no' by a previous buggy middleware run,
        // restore it from the activations table.
        $teamLeader = TeamLeader::where('User_name', $user->user)
            ->orWhere('Email', $user->email)
            ->first();

        if ($teamLeader && $teamLeader->status === 'confirmed') {
            $usedActivation = \App\Models\Activations::where('email', $user->email)
                ->whereIn('package', ['TEAM_LEADER', 'SUPER_LEADER'])
                ->where('stutus', 'used')
                ->first();

            if ($usedActivation) {
                $user->has_paid_package = $usedActivation->package;
                $user->has_free_package = 'no';
                $user->save();
                return $next($request);
            }

            // Confirmed but NOT activated → send to pending-approval
            return redirect()->to(url('/team-leader/pending-approval?username=' . $teamLeader->User_name));
        }

        if ($teamLeader && $teamLeader->status === 'pending') {
            return redirect()->to(url('/team-leader/pending-approval?username=' . $teamLeader->User_name));
        }

        if ($teamLeader && $teamLeader->status === 'rejected') {
            return redirect()->to(url('/team-leader/rejected?username=' . $teamLeader->User_name));
        }

        if ($teamLeader && $teamLeader->status === 'suspended') {
            return redirect()->route('team.leader')->with('message', 'Your Team Leader account is suspended.');
        }

        // ── Free dashboard access for regular users ──
        if ($user->utype === 'USR') {
            $settings = \App\Models\WithdrawalSetting::current();
            if ($settings && $settings->allow_free_dashboard_access && $user->has_free_package !== 'yes') {
                $user->has_free_package = 'yes';
                $user->has_paid_package = 'standard';
                $user->save();
            }
        }

        // ── Standard package check for regular users ──
        if ($user->utype === 'USR' && ($user->has_paid_package !== 'no' && $user->has_paid_package != '' || $user->has_free_package == 'yes')) {
            return $next($request);
        }

        return redirect()->route('user.venture');
    }
}
