<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Claim;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class ShareClaimsMiddleware
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

        $claims = Claim::where('is_fixed', false)->count(); 
        $pending = Auth::user()->deposits()->where("status","pending")->first();
        // dd($claims);
        View::share(['unfixedClaims'=> $claims,"have_pending_deposits"=>$pending]);     
        return $next($request);
    }
}
