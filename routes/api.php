<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentsController;
use App\Http\Controllers\LessonsController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\SetController;
use App\Http\Middleware\AuthenticateToken;
use App\Http\Middleware\OnlyAdmin;
use App\Models\Enrollments;
use Illuminate\Auth\Middleware\Authenticate;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);

Route::middleware([AuthenticateToken::class ])->group(function() {
    Route::get('/courses', [CourseController::class, 'allCourse']);
    Route::get('/courses/{course_slug}', [CourseController::class, 'getCourse']);

    Route::post('/lesson-contents/{lesson_id}/check', [OptionController::class, 'check']);
    Route::post('/courses/{course_slug}/register', [EnrollmentsController::class, 'create']);

    Route::get('/users/progress', [CourseController::class, 'progres']);

    Route::put('/lessons/{lesson_id}/complete', [LessonsController::class, 'completed']);
    Route::middleware([OnlyAdmin::class])->group(function() {
        Route::post('/courses', [CourseController::class, 'create']);
        Route::put('/courses/{course_slug}', [CourseController::class, 'update']);
        Route::delete('/courses/{course_slug}', [CourseController::class, 'delete']);

        Route::post('/courses/{course_slug}/sets', [SetController::class, 'create']);
        Route::delete('/courses/{course_slug}/sets/{set_id}', [SetController::class, 'delete']);


        Route::post('/lessons', [LessonsController::class, 'create']);
        Route::delete('/lessons/{lesson_id}', [LessonsController::class, 'delete']);

    });
    Route::post('/logout',[AuthController::class, 'logout']);
});