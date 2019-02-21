<?php

namespace App\Http\Middleware;
use Auth;
use Closure;

class checkIfLoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if(Auth::guard('admin')->check()){
        return $next($request);
      } elseif(Auth::guard('unit')->check()){
        return $next($request);
      } else{
        return redirect()->route('login')->with('message','Session Expired, Please try to login again');
      }

    }
}
