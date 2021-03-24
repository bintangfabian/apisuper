<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamQuestionController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LearningMaterialController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StudentAttendanceController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\AttitudeAssessmentController;
use App\Models\Announcement;
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

Route::middleware(['force_return_json'])->group(function () {
    Route::get('images/{id}', [ImageController::class, 'show'])->name('image.show');
    Route::get('register/verify/{id}',  [VerificationController::class, 'verify'])->name('verification.verify');
    Route::get('register/resend', [VerificationController::class, 'resend'])->name('verification.resend');
    // Route::get('email/verify/{id}', 'App\Http\Controllers\VerificationApiController@verify')->name('verificationapi.verify');
    // Route::post('email/resend', 'App\Http\Controllers\VerificationApiController@resend')->name('verificationapi.resend');

    Route::group(['middleware' => ['verified']], function () {
        Route::post('login', 'App\Http\Controllers\UserController@login');
    });

    // need to give token
    Route::middleware('auth:api')->group(function () {
        Route::put('permission', [PermissionController::class, 'update'])->middleware('permission:edit permission');
        Route::put('users', [UserController::class, 'update'])->middleware('verified');
        Route::get('user/detail', 'App\Http\Controllers\UserController@details')->middleware('verified');
        Route::post('images', [ImageController::class, 'store']); 
        Route::post('register', 'App\Http\Controllers\UserController@register')->middleware('permission:register');
        Route::get('recap-user/{role?}', 'App\Http\Controllers\UserController@recapUser')->middleware('permission:recap user');
        Route::post('logout', 'App\Http\Controllers\UserController@logout');
        Route::get('permission', [PermissionController::class, 'index']);
        Route::get('permission/check', [PermissionController::class, 'index']);
        Route::get('grades', [GradeController::class, 'index']);
        Route::get('subjects', [SubjectController::class, 'index']);
        // Route::get('attitude', [AttitudeAssessmentController::class, 'index']);

        // just for user who has crud news permission
        Route::group(['middleware' => ['permission:crud chapter']], function () {
            Route::apiResource('chapter', 'App\Http\Controllers\ChapterController');
            Route::get('chapter/search/{q}', [ChapterController::class, 'search']);
        });

        // just for user who has crud news permission
        Route::group(['middleware' => ['permission:crud news']], function () {
            Route::apiResource('news', 'App\Http\Controllers\NewsController');
        });

        // just for user who has view news permission
        Route::group(['middleware' => ['permission:view news']], function () {
            Route::apiResource('news', 'App\Http\Controllers\NewsController')->only('index', 'show');
        });

        // just for user who has crud learning materials
        Route::group(['middleware' => ['permission:crud learning materials']], function () {
            Route::apiResource('learning-materials', LearningMaterialController::class);
        });

        // just for user who has crud exam
        Route::group(['middleware' => ['permission:crud exam']], function () {
            Route::apiResource('exam', ExamController::class);
        });

        // just for user who has crud question bank
        Route::group(['middleware' => ['permission:crud question bank']], function () {
            Route::apiResource('exam-question', ExamQuestionController::class);
            Route::apiResource('question', QuestionController::class);
        });

        Route::group(['middleware' => ['permission:crud attitude assessment']], function () {
            Route::apiResource('attitude-assessment', AttitudeAssessmentController::class);
            // Route::get('chapter/search/{q}',nt [AttitudeAssessmentController::class, 'search']);
        });

        // just for user who has crud announcement permission
        Route::group(['middleware' => ['permission:crud announcement']], function () {
            Route::get('announcement/management', [AnnouncementController::class, 'management']);
            Route::apiResource('announcement', AnnouncementController::class);
            Route::get('announcement/search/{q}', [AnnouncementController::class, 'search']);
        });

        // just for user who has view announcement permission
        Route::group(['middleware' => ['permission:view announcement']], function () {
            Route::apiResource('announcement', AnnouncementController::class)->only(['index', 'show']);
        });

        // just for user who has crud student attendance permission
        Route::group(['middleware' => ['permission:crud student attendance']], function () {
            Route::apiResource('student-attendance', StudentAttendanceController::class)->except(['show', 'destroy']);
            Route::delete('student-attendance/{date}/{gradeId}', [StudentAttendanceController::class, 'destroyByGradeAndDate']);
            Route::get('student-attendance/{date}/{gradeId}', [StudentAttendanceController::class, 'showByGradeAndDate']);
        });

        // just for user who has recap student attendance permission
        Route::group(['middleware' => ['permission:recap student attendance']], function () {
            Route::get('student-attendance/recap/{month}/{year}', [StudentAttendanceController::class, 'recapByMonthAndYear']);
            Route::get('student-attendance/recap/{gradeId}/{month}/{year}', [StudentAttendanceController::class, 'recap']);
        });
    });

    Route::group(['namespace' => 'Auth', 'middleware' => 'api', 'prefix' => 'password'], function () {
        Route::post('request', [PasswordResetController::class, 'request']);
        Route::get('find/{token}', [PasswordResetController::class, 'find']);
        Route::post('reset', [PasswordResetController::class, 'reset']);
    });
});