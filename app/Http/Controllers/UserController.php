<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditPermissionsUser;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUser;
use App\Http\Resources\UserResource;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\StatusCode;
use Carbon\Carbon;
use Avatar;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public $successStatus = 200;

    public function login(LoginUserRequest $request)
    {
        // try {
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials))
            return response()->error('Email atau password salah', StatusCode::UNAUTHORIZED);
        $user = $request->user();
        $permission = $user->hasPermissionTo('login');
        if (!$permission) {
            return response()->error('user tidak mempunyai hak akses', StatusCode::UNAUTHORIZED);
        }
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if (!$request->remember_me) $token->expires_at = Carbon::now()->timezone('Asia/Jakarta')->addWeeks(1);
        if ($request->remember_me) $token->expires_at = Carbon::now()->timezone('Asia/Jakarta')->addWeeks(2);
        $token->save();
        return response()->successWithKey([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'token' => $tokenResult->accessToken,
            'type' => 'Bearer',
            'image_id' => $user->image->id,
            'role' => $user->getRoleNames()[0],
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
        try {
            $user = new User($request->validated());
            $user->save();

            $user->assignRole($request['role']);

            $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
            Storage::put('public/images/avatars/' . $user->id . '/avatar.png', (string) $avatar);

            $user->sendEmailVerificationNotification();

            $user->image()->create(['path' => "avatars/$user->id/avatar.png", 'thumbnail' => true]);
            if ($request['role'] === 'Siswa') {
                $gradeId = (Grade::where('name', $request->grade)->select('id')->get())[0]->id;
                $user->student()->create(['user_id' => $user->id, 'grade_id' => $gradeId]);
            } else if ($request['role'] === 'Wali Siswa') {
                $user->guardianOfStudent()->create(['user_id' => $user->id, 'student_id' => $request->student_id]);
            }
            // return response()->successWithMessage('hai!', StatusCode::CREATED);
            return response()->successWithMessage("Successfully created user!", StatusCode::CREATED);
        } catch (\Throwable $th) {
            return response()->error("Failed created user! $th", StatusCode::INTERNAL_SERVER_ERROR);
        }
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
        return response()->successWithKey(new UserResource($user), 'user');
    }

    public function update(UpdateUser $request)
    {
        try {
            $user = User::findOrFail($request->user()->id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) {
            return response()->error('User is not found', StatusCode::UNAUTHORIZED);
        } catch (\Throwable $th) {
            return response()->error('Something went error', StatusCode::INTERNAL_SERVER_ERROR);
        }
        // return $request['email'];
        if (request()->hasFile('image')) {
            $image = Image::make($request->image)->fit(278, 278, null, 'center');
            $path = $user->image->path;
            $image->save(Storage::disk('local')->path("public/images/$path"));
        }
        $user->fill($request->validated());
        $user->update();
        return response()->successWithKey(new UserResource($user), 'user');
    }

    public function recapUser(Request $request, $role = null)
    {
        $user = (new User)->newQuery();
        if ($request->query('search')) {
            $searchQuery = $request->query('search');
            $user->where('name', 'LIKE', '%' . $searchQuery . '%')->orWhere('email', 'LIKE', '%' . $searchQuery . '%');
        }
        // dd($request->query('search'));
        if ($role) {
            $user->with('roles')->role($role);
        } else {
            $user->with('roles');
        }
        return $user->paginate(15);
    }
}
