<?php

use App\Http\Controllers\Auth\TeacherLoginController;
use App\Http\Controllers\Teacher\AttendanceController as TeacherAttendanceController;
use App\Http\Controllers\Teacher\BehavioralController as TeacherBehavioralController;
use App\Http\Controllers\Teacher\ClassController as TeacherClassController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\ProfileController as TeacherProfileController;
use App\Http\Controllers\Teacher\PublishedController as TeacherPublishedController;
use App\Http\Controllers\Teacher\ResultsController as TeacherResultsController;
use App\Http\Controllers\Teacher\SubjectsController as TeacherSubjectsController;
use App\Http\Controllers\Teacher\UploadedController as TeacherUploadedController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Teacher Routes — Authenticated (auth:teacher)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:teacher')->prefix('teacher')->name('teacher.')->group(function () {
    Route::post('/logout', [TeacherLoginController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Teacher Routes — Dashboard and Profile
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::controller(TeacherProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::post('/profile', 'update')->name('profile.update');
        Route::post('/password', 'changePassword')->name('profile.password');
        Route::get('/upload', 'uploadPage')->name('upload');
        Route::post('/upload', 'upload')->name('upload.store');
    });

    /*
    |--------------------------------------------------------------------------
    | Teacher Routes — Attendance
    |--------------------------------------------------------------------------
    */
    Route::controller(TeacherAttendanceController::class)->group(function () {
        Route::get('/attendance', 'index')->name('attendance.index');
        Route::get('/attendance/take-attendance', 'takeAttendance')->name('attendance.take-attendance');
        Route::get('/attendance/view-attendance', 'viewAttendance')->name('attendance.view-attendance');
    });

    /*
    |--------------------------------------------------------------------------
    | Teacher Routes — Behavioral
    |--------------------------------------------------------------------------
    */
    Route::controller(TeacherBehavioralController::class)->group(function () {
        Route::get('/behavioral', 'index')->name('behavioral.index');
        Route::get('/behavioral/take-behavioral', 'takeBehavioral')->name('behavioral.take-behavioral');
        Route::get('/behavioral/view-behavioral', 'viewBehavioral')->name('behavioral.view-behavioral');
    });

    /*
    |--------------------------------------------------------------------------
    | Teacher Routes — Class
    |--------------------------------------------------------------------------
    */
    Route::controller(TeacherClassController::class)->group(function () {
        Route::get('/class', 'index')->name('class.index');
        Route::get('/class/find-students', 'findStudents')->name('class.find-students');
    });

    /*
    |--------------------------------------------------------------------------
    | Teacher Routes — Subjects
    |--------------------------------------------------------------------------
    */
    Route::controller(TeacherSubjectsController::class)->group(function () {
        Route::get('/subjects', 'index')->name('subjects.index');
        Route::get('/subjects/registered', 'registered')->name('subjects.registered');
    });

    /*
    |--------------------------------------------------------------------------
    | Teacher Routes — Results
    |--------------------------------------------------------------------------
    */
    Route::controller(TeacherResultsController::class)->group(function () {
        Route::get('/results', 'index')->name('results.index');
        Route::post('/results/upload-term', 'uploadTerm')->name('results.upload-term');
    });

    /*
    |--------------------------------------------------------------------------
    | Teacher Routes — Uploaded and Published Results
    |--------------------------------------------------------------------------
    */
    Route::controller(TeacherUploadedController::class)->group(function () {
        Route::get('/uploaded', 'index')->name('uploaded.index');
        Route::post('/uploaded/edit-result', 'editResult')->name('uploaded.edit-result');
    });
    Route::get('/published', [TeacherPublishedController::class, 'index'])->name('published.index');
});
