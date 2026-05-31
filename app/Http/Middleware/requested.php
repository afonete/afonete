<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class requested
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
    if( $request->user()->has_request === 'confirmed'|| $request->user()->has_request === 'registed'  )
        {

            return $next($request);
        }   
     
      
        else {
                  

            return view('requested');
        }

    }
}
