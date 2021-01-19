<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\StatusCode;
use Carbon\Carbon;
use Validator;

class UserController extends Controller
{
    public $successStatus = 200;

    public function login(Request $request){
        try {
            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials))
                return response()->error('Email or password is not correct', StatusCode::UNAUTHORIZED);
            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->remember_me)
                $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();
            return response()->successWithKey([
                'token' => $tokenResult->accessToken,
                'type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ], 'personal_access_token');
        } 
            catch (\Throwable $th) {
                return response()->error('Failed to login!', StatusCode::INTERNAL_SERVER_ERROR);
            }
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'role' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'c_password' => 'required|same:password',
            ]);

            if ($validator->fails()) {
                return response()->error('Failed created user!', StatusCode::INTERNAL_SERVER_ERROR);            
            }

            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            return response()->successWithMessage('Successfully created user!', StatusCode::CREATED);
        } catch (\Throwable $th) {
            return response()->error('Failed created user!', StatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function logout(Request $request)
    {
        // $request->user()->token()->revoke();
        $logout = $request->user()->token()->revoke();
        if($logout){
            return response()->successWithMessage('Successfully logout!');
        }
    }

    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }
}
