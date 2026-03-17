<?php

use App\Http\Controllers\ResultCheckController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('home'));
Route::get('/home', function () {
    return view('home');
})->name('home');
Route::get('/result', [ResultCheckController::class, 'index'])->name('result.check');
Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');

/*
|--------------------------------------------------------------------------
| Authentication Routes — Guest (Admin and Teacher Login)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Admin Routes (auth:admin)
|--------------------------------------------------------------------------
*/
require __DIR__.'/admin.php';

/*
|--------------------------------------------------------------------------
| Teacher Routes (auth:teacher)
|--------------------------------------------------------------------------
*/
require __DIR__.'/teacher.php';
