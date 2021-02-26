<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\StatusCode;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;

class EnsureApiEmailIsVerified
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
        $user = $request->user() ?? User::where('email', $request->get('email'))->first();
        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            return response()->error("Email belum terverifikasi!", StatusCode::UNAUTHORIZED);
        }
        return $next($request);
    }
}
