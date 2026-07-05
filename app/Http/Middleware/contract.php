<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class contract
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

        if ($user->utype === 'ADM') {
            return $next($request);
        }

        // Free/standard users are allowed to use the dashboard without a
        // contract. Do NOT auto-assign contract='Signed' for these users.
        // They must sign only after activating/buying a real package.
        $paidPackage = strtolower(trim((string) $user->has_paid_package));
        $isFreeStandard = $user->utype === 'USR'
            && $user->has_free_package === 'yes'
            && $paidPackage === 'standard';

        if ($isFreeStandard || ($user->utype === 'USR' && $user->contract === 'Signed')) {
            return $next($request);
        }

        return redirect()->route('user.contract')
            ->with('message', 'Please sign the contract before accessing your dashboard.');

    }
}
