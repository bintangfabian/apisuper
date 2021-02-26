<?php

use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ImageController;
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

Route::middleware(['return.json'])->group(function () {
    Route::post('register', 'App\Http\Controllers\UserController@register');
    Route::get('images/{id}', [ImageController::class, 'show'])->name('image.show');
    Route::post('login', 'App\Http\Controllers\UserController@login')->name('login');
    Route::get('email/verify/{id}', 'App\Http\Controllers\VerificationApiController@verify')->name('verificationapi.verify');
    Route::post('email/resend', 'App\Http\Controllers\VerificationApiController@resend')->name('verificationapi.resend');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user/detail', 'App\Http\Controllers\UserController@details');
        Route::post('logout', 'App\Http\Controllers\UserController@logout');
        Route::resource('/siswa', 'App\Http\Controllers\SiswaController');
    });

    Route::group(['middleware' => ['auth:api', 'role:3']], function () {
        Route::resource('/news', 'App\Http\Controllers\NewsController');
    });

    Route::group(['namespace' => 'Auth', 'middleware' => 'api', 'prefix' => 'password'], function () {
        Route::post('request', [PasswordResetController::class, 'request']);
        Route::get('find/{token}', [PasswordResetController::class, 'find']);
        Route::post('reset', [PasswordResetController::class, 'reset']);
    });

    // Route::group(['middleware' => ['auth:api', 'role:1']], function () {
    //     Route::resource('/news', 'App\Http\Controllers\NewsController');
    // });

    Route::resource('news', 'App\Http\Controllers\NewsController');
});


Route::resource('user/edit', 'App\Http\Controllers\UserController');
