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
    if( (($request->user()->contract === 'Signed' && $request->user()->utype === 'USR' )
     || $request->user()->has_free_package === 'yes')  || $request->user()->utype === 'ADM')
        {


            return $next($request);
        }


        else {
            // dd("s);d"

            return redirect()->route('user.contract');
        }

    }
}
