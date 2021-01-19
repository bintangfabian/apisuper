<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\StatusCode;

class CheckRole
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
        // if($request->user()->role == $role){
            return $next($request);
        // }

        // return response()->json([
        //     'message' => 'Sorry, you are not admin!'
        // ]);

        // return response()->error('Sorry, you are not admin!', StatusCode::UNAUTHORIZED);
    }
}
