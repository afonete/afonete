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
        if ($user && $user->utype === 'USR') {
            $settings = \App\Models\WithdrawalSetting::current();
            if ($settings && $settings->allow_free_dashboard_access && $user->has_free_package !== 'yes') {
                $user->has_free_package = 'yes';
                $user->has_paid_package = 'standard';
                $user->contract = 'Signed';
                $user->save();
            }
        }

        if( $request->user()->utype === 'USR' && ( $request->user()->has_paid_package !== 'no' && $request->user()->has_paid_package != '' ||  $request->user()->has_free_package == 'yes' )
     || $request->user()->utype === 'ADM'
    )

        {
            // dd($request->user());
            return $next($request);
        }else {
            $teamLeader = TeamLeader::where('User_name', $request->user()->user)
                ->orWhere('Email', $request->user()->email)
                ->orWhere('Phone', $request->user()->phone)
                ->first();

            if ($teamLeader) {
                if ($teamLeader->status == 'pending') {
                    return redirect()->to(url('/team-leader/pending-approval?username=' . $teamLeader->User_name));
                }
                if ($teamLeader->status === 'rejected') {
                    return redirect()->to(url('/team-leader/rejected?username='.$teamLeader->User_name));

                }
            }

            return redirect()->route('user.venture');
        }

    }
}
