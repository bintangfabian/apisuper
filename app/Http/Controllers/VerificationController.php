<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\StatusCode;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify($user_id, Request $request)
    {
        if (!$request->hasValidSignature()) {
            return response()->error('Invalid/Expired url provided.', StatusCode::UNAUTHORIZED);
        }

        $user = User::findOrFail($user_id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
        return redirect()->away(env('FRONT_END_URL') . 'login');
    }

    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email|string|max:255'
        ]);

        try {
            $user = User::where('email', '=', $request->input('email'))->firstOrFail();
        } catch (\Throwable $th) {
            return response()->error('Email is not found.', StatusCode::NOT_FOUND);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->error('Email has already verified.', StatusCode::BAD_REQUEST);
        }

        $user->sendEmailVerificationNotification();

        return response()->successWithMessage('Email verification link sent on your email id', StatusCode::OK);
    }
}
