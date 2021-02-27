<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['force_return_json'])->group(function () {
    Route::get('images/{id}', [ImageController::class, 'show'])->name('image.show');
    Route::get('email/verify/{id}', 'App\Http\Controllers\VerificationApiController@verify')->name('verificationapi.verify');
    Route::post('email/resend', 'App\Http\Controllers\VerificationApiController@resend')->name('verificationapi.resend');
    Route::get('register/verify/{id}',  [VerificationController::class, 'verify'])->name('verification.verify');
    Route::get('register/resend', [VerificationController::class, 'resend'])->name('verification.resend');

    Route::group(['middleware' => ['verified']], function () {
        Route::post('login', 'App\Http\Controllers\UserController@login');
    });

    // need to give token
    Route::middleware('auth:api')->group(function () {
        Route::post('images', [ImageController::class, 'store']);
        Route::post('register', 'App\Http\Controllers\UserController@register')->middleware('permission:register');
        Route::get('user/detail', 'App\Http\Controllers\UserController@details');
        Route::post('logout', 'App\Http\Controllers\UserController@logout');
        Route::get('permission', [PermissionController::class, 'index']);
        // just for user who has crud news permission
        Route::group(['middleware' => ['permission:crud news']], function () {
            Route::resource('news', 'App\Http\Controllers\NewsController');
        });
        // just for user who has view news permission
        Route::group(['middleware' => ['permission:view news']], function () {
            Route::resource('news', 'App\Http\Controllers\NewsController')->only('index', 'show');
        });

        // just for user who has crud announcement permission
        Route::group(['middleware' => ['permission:crud announcement']], function () {
            Route::apiResource('announcement', AnnouncementController::class);
        });

        // just for user who has view announcement permission
        Route::group(['middleware' => ['permission:view announcement']], function () {
            Route::apiResource('announcement', AnnouncementController::class)->only(['index', 'show']);
        });
    });

    Route::group(['namespace' => 'Auth', 'middleware' => 'api', 'prefix' => 'password'], function () {
        Route::post('request', [PasswordResetController::class, 'request']);
        Route::get('find/{token}', [PasswordResetController::class, 'find']);
        Route::post('reset', [PasswordResetController::class, 'reset']);
    });
});
