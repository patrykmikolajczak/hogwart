<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeacherPointsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// logowanie
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.post')
    ->middleware('guest');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

Route::middleware('auth')->group(function () {

    // dashboard (po zalogowaniu)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/teacher/points/create', [TeacherPointsController::class, 'create'])
        ->name('teacher.points.create');

    Route::post('/teacher/points', [TeacherPointsController::class, 'store'])
        ->name('teacher.points.store');
    
    Route::get('/teacher/points/history', [TeacherPointsController::class, 'history'])
        ->name('teacher.points.history');

});
