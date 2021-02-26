<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\FindPasswordReset;
use App\Http\Requests\RequestPasswordReset;
use App\Models\PasswordReset;
use App\Models\ResetPassword;
use App\Models\User;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\Notifications\ResetPasswordRequest;
use App\Notifications\ResetPasswordSuccess;
use App\StatusCode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function request(RequestPasswordReset $request)
    {
        $user = User::where('email', $request->validated())->first();
        if (!$user) return response()->error('Email tidak ditemukan', StatusCode::UNPROCESSABLE_ENTITY);
        $resetPassword = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => \Str::random(60)
            ]
        );
        if ($user && $resetPassword)
            $user->notify(
                new PasswordResetRequest($resetPassword->token)
            );
        return response()->successWithMessage('Email telah dikirim untuk mengatur ulang kata sandi Anda.');
    }

    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find(FindPasswordReset $request)
    {
        $resetPassword = PasswordReset::where('token', $request->validated())->first();
        // if (!$resetPassword)
        //     return response()->error('Reset password token is invalid!');

        if (Carbon::parse($resetPassword->updated_at)->addMinutes(720)->isPast()) {
            $resetPassword->delete();
            return response()->error('Reset password token is invalid!');
        }

        return response()->successWithKey($resetPassword, 'reset_password_token');
    }

    /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed|min:8',
            'token' => 'required|string'
        ]);
        $resetPassword = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();
        if (!$resetPassword)
            return response()->error('Reset password token is invalid!');
        $user = User::where('email', $resetPassword->email)->first();
        if (!$user)
            return response()->error('Email tidak ditemukan.', StatusCode::NOT_FOUND);
        $user->password = $request->password;
        $user->save();
        $resetPassword->delete();
        $user->notify(new PasswordResetSuccess($resetPassword));
        return response()->successWithKey($user, 'user');
    }
}
