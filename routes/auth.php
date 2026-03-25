<?php

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\TeacherLoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes — Guest (Admin and Teacher Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest:admin')->group(function () {
    Route::controller(AdminLoginController::class)->group(function () {
        Route::get('/admin/login', 'showLoginForm')->name('admin.login');
        Route::post('/admin/login', 'login');
    });
});

Route::middleware('guest:teacher')->group(function () {
    Route::controller(TeacherLoginController::class)->group(function () {
        Route::get('/teacher/login', 'showLoginForm')->name('teacher.login');
        Route::post('/teacher/login', 'login');
    });
});

/*
| Fallback
*/
Route::get('/login', fn () => redirect()->route('home'))->name('login');
