<?php

namespace App\Http\Middleware;

use App\Enumeration\Role;
use Closure;
use Auth;

class Buyer
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
        if (!Auth::check() || Auth::user()->role != Role::$BUYER) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
