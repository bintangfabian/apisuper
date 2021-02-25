<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Role
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
        // return $next($request);
        if(!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        if($user->hasRole('1')) {
            return $next($request);
        }
        if($user->hasRole('2')) {
            return $next($request);
        }
        if($user->hasRole('3')) {
            return $next($request);
        }
        if($user->hasRole('4')) {
            return $next($request);
        }
        if($user->hasRole('5')) {
            return $next($request);
        }
    }
}
