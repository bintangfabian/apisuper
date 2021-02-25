<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\StatusCode;
use Carbon\Carbon;
use Avatar;
use Validator;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public $successStatus = 200;

    public function login(LoginUserRequest $request)
    {
        // try {
            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials))
                return response()->error('Email or password is not correct', StatusCode::UNAUTHORIZED);
            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->remember_me) $token->expires_at = Carbon::now()->timezone('Asia/Jakarta')->addWeeks(1);
            $token->save();
            return response()->successWithKey([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'token' => $tokenResult->accessToken,
                'type' => 'Bearer',
                // 'image_id' => $user->image->id,
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ], 'user_data');
        // } catch (\Throwable $th) {
        //     return response()->error('Failed to login!', StatusCode::INTERNAL_SERVER_ERROR);
        // }
    }

    public function register(RegisterUserRequest $request)
    {
        // try {
            $user = new User($request->validated());
            $user->password = bcrypt($user->password);
            $user->save();
            

            // $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
            // Storage::put('public/images/avatars/' . $user->id . '/avatar.png', (string) $avatar);

            // $user->image()->create(['path' => "avatars/$user->id/avatar.png", 'thumbnail' => true]);

            // return response()->successWithMessage('hai!', StatusCode::CREATED);
            if($request['role'] == 1) {
                $user->assignRole('1');
            }
            if($request['role'] == 2) {
                $user->assignRole('2');
            }
            if($request['role'] == 3) {
                $user->assignRole('3');
            }
            if($request['role'] == 4) {
                $user->assignRole('4');
            }
            if($request['role'] == 5) {
                $user->assignRole('5');
            }
            return response()->successWithMessage("Successfully created user!", StatusCode::CREATED);
        // } catch (\Throwable $th) {
        //     return response()->error('Failed created user!', StatusCode::INTERNAL_SERVER_ERROR);
        // }
    }


    public function logout(Request $request)
    {
        // $request->user()->token()->revoke();
        $logout = $request->user()->token()->revoke();
        if ($logout) {
            return response()->successWithMessage('Successfully logout!');
        }
    }

    public function details()
    {
        $user = Auth::user();
        // return response()->json(['success' => $user], $this->successStatus);
        return response()->successWithMessage([
            'nama' => $user->name,
            'role' => $user->role,
            'email' => $user->email,
            'expired_at' => $user->expired_at,
        ]);
    }
}
