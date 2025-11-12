<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FiringController;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/courses/create', [FiringController::class, 'create'])->name('courses.create');
Route::post('/courses/store', [FiringController::class, 'store'])->name('courses.store');

