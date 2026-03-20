<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\TeacherLoginController;
use App\Http\Controllers\Teacher\AttendanceController;
use App\Http\Controllers\Teacher\BehavioralController;
use App\Http\Controllers\Teacher\ClassController;
use App\Http\Controllers\Teacher\DashboardController;
use App\Http\Controllers\Teacher\ProfileController;
use App\Http\Controllers\Teacher\ResultController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Teacher Routes (auth:teacher)
|--------------------------------------------------------------------------
|
| This routes file defines only the Teacher module features.
| All teacher endpoints are scoped to the authenticated teacher via auth()->user().
|
*/
Route::middleware('auth:teacher')
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {

        // Sidebar expects these.
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [TeacherLoginController::class, 'logout'])->name('logout');

        // Attendance
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/take', [AttendanceController::class, 'takeAttendance'])->name('attendance.take');
        Route::post('/attendance', [AttendanceController::class, 'save'])->name('attendance.save');
        Route::get('/attendance/view', [AttendanceController::class, 'viewAttendance'])->name('attendance.view');

        // Behavioural
        Route::get('/behavioral', [BehavioralController::class, 'index'])->name('behavioral.index');
        Route::get('/behavioral/take', [BehavioralController::class, 'takeBehavioral'])->name('behavioral.take');
        Route::post('/behavioral', [BehavioralController::class, 'save'])->name('behavioral.save');
        Route::get('/behavioral/view', [BehavioralController::class, 'viewBehavioral'])->name('behavioral.view');

        // Classes (assigned class + student list)
        Route::get('/class', [ClassController::class, 'index'])->name('class.index');

        // Results
        Route::get('/results', [ResultController::class, 'upload'])->name('results.index');
        Route::post('/results/upload', [ResultController::class, 'uploadResults'])->name('results.upload-term');
        Route::get('/results/uploaded', [ResultController::class, 'getUploadedResults'])->name('uploaded.index');
        Route::put('/results', [ResultController::class, 'edit'])->name('results.edit');

        // Profile
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.index');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
        Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar');
    });
