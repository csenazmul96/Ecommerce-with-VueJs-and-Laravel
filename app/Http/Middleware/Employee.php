<?php

namespace App\Http\Middleware;

use App\Enumeration\Role;
use Closure;
use Auth;

class Employee
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
        if (Auth::check() && (Auth::user()->role == Role::$ADMIN || Auth::user()->role == Role::$EMPLOYEE)) {
            return $next($request);

        }

        return redirect()->route('login_admin');
    }
}
