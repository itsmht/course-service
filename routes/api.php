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
//Transaction Verification Route
Route::get('/checkout/course/{course}', [CheckoutController::class, 'course']);
Route::post('/checkout/preview', [CheckoutController::class, 'preview']);
Route::middleware([ValidateToken::class])->group(function () {
    Route::post('/checkout/initiate', [CheckoutController::class, 'initiate']);
    });
Route::post('/payment/ipn', [PaymentIpnController::class, 'handle']);
Route::get('/payment/status/{tran_id}', [PaymentStatusController::class, 'status']);