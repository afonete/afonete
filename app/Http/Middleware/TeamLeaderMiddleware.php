<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\TeamLeader;

class TeamLeaderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
      
        // Get team leader status for current user
        $username = $request->query('username');
        $teamLeader = TeamLeader::where('User_name', $username)->first();
        
    
        if (!$teamLeader) {
            return redirect()->route('team.leader');
        }
    
        // Store team leader data in session for easy access
        session(['team_leader_status' => $teamLeader->status]);
        session(['team_leader_Usen_Name' => $username]);
    
        // Route based on application status
        switch ($teamLeader->status) {
            case 'pending':
                // return redirect()->route('team-leader.pending-approval');
                return $next($request);
            
            case 'confirmed':
                return $next($request);
                
            case 'rejected':
                return redirect()->route('team-leader.rejected')
                               ->with('message', 'Your application was not approved.');
                
            case 'suspended':
                return redirect()->route('team-leader.suspended')
                               ->with('message', 'Your account has been suspended.');
                
            default:
                return redirect()->route('team.leader');
        }
    }
    
}
