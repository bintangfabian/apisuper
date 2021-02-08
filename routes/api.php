<?php

use App\Http\Controllers\ImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
});


Route::post('login', 'App\Http\Controllers\UserController@login')->name('login');
Route::resource('user/edit', 'App\Http\Controllers\UserController');
Route::resource('/news', 'App\Http\Controllers\NewsController');


Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user/detail', 'App\Http\Controllers\UserController@details');
    Route::post('logout', 'App\Http\Controllers\UserController@logout');
    Route::resource('/siswa', 'App\Http\Controllers\SiswaController');
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
