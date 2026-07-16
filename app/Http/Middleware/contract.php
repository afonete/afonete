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

        // Free users (no active paid package) are allowed to use the dashboard without a
        // contract. They must sign only after activating/buying their first real package.
        $paidPackage = strtolower(trim((string) $user->has_paid_package));
        $isFreeUser = ($paidPackage === 'no' || $paidPackage === 'standard' || $paidPackage === '');

        if ($isFreeUser || $user->contract === 'Signed') {
            return $next($request);
        }

        return redirect()->route('user.contract')
            ->with('message', 'Please sign the contract before accessing your dashboard.');

    }
}
