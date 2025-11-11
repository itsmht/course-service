<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\ValidateToken;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Authentication routes
Route::get('courses', [CourseController::class, 'courses']);
Route::get('courseDetails/{slofuncrsi}', [CourseController::class, 'courseDetails']);


 
Route::middleware([ValidateToken::class])->group(function () {
    Route::get('myEnrollments', [CourseController::class, 'myEnrollments']);
    });