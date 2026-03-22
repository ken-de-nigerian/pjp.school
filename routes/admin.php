<?php

use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\BehavioralController;
use App\Http\Controllers\Admin\BulkController;
use App\Http\Controllers\Admin\CardController;
use App\Http\Controllers\Admin\ChecklistController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FeeController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\NotificationsController;
use App\Http\Controllers\Admin\OnlineEntranceController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\ResultRemarkController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectsController;
use App\Http\Controllers\Admin\TeachersController;
use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes — Authenticated (auth:admin)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes — Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes — Classes and Students Index
    |--------------------------------------------------------------------------
    */
    Route::controller(ClassController::class)->group(function () {
        Route::get('/classes', 'index')->name('classes');
        Route::post('/students/add-class', 'addClass')->name('add.class');
        Route::put('/students/classes/{schoolClass}', 'updateClass')->name('classes.update');
        Route::delete('/students/classes/{schoolClass}', 'destroyClass')->name('classes.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes — Student Management
    |--------------------------------------------------------------------------
    */
    Route::controller(StudentController::class)->group(function () {
        Route::get('/students/houses', 'houses')->name('students.houses');
        Route::get('/students/houses/view', 'viewHouse')->name('students.houses.view');
        Route::get('/graduated', 'graduated')->name('graduated');
        Route::get('/graduated/view-graduated', 'viewGraduated')->name('graduated.view');
        Route::get('/left_school', 'leftSchool')->name('left_school');
        Route::get('/left_school/view-students', 'viewLeftSchool')->name('left_school.view');
        Route::get('/students/create', 'create')->name('students.create');
        Route::post('/students', 'store')->name('students.store');
        Route::get('/academic_advancement', 'academicAdvancement')->name('students.academic_advancement');
        Route::get('/academic_advancement/demote-students', 'demoteStudents')->name('students.demote_students');
        Route::get('/students/by-class', 'studentsByClassJson')->name('students.by-class');
        Route::get('/students/classlist/pdf', 'classListPdf')->name('students.classlist.pdf');
        Route::post('/students/bulk-fee-status', 'bulkToggleFee')->name('students.bulk-fee-status');
        Route::post('/students/promote', 'promote')->name('students.promote');
        Route::post('/students/demote', 'demote')->name('students.demote');
        Route::post('/students/upload-students-profile', 'uploadStudentsProfile')->name('students.upload-profile');
        Route::get('/students/{student}', 'show')->name('students.show');
        Route::get('/students/{student}/edit', 'edit')->name('students.edit');
        Route::delete('/students/{student}', 'destroy')->name('students.destroy');
        Route::put('/students/{student}/account', 'updateAccount')->name('students.update.account');
        Route::put('/students/{student}/academic', 'updateAcademic')->name('students.update.academic');
        Route::put('/students/{student}/contact', 'updateContact')->name('students.update.contact');
        Route::put('/students/{student}/parents', 'updateParents')->name('students.update.parents');
        Route::put('/students/{student}/sponsors', 'updateSponsors')->name('students.update.sponsors');
        Route::put('/students/{student}/other', 'updateOther')->name('students.update.other');
        Route::put('/students/{student}/status', 'toggleStatus')->name('students.toggle.status');
        Route::put('/students/{student}/fee-status', 'toggleFee')->name('students.toggle.fee');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes — Staff Management
    |--------------------------------------------------------------------------
    */
    Route::controller(StaffController::class)->group(function () {
        Route::get('/staff', 'index')->name('staff.index');
        Route::get('/staff/create', 'create')->name('staff.create');
        Route::post('/staff', 'store')->name('staff.store');
        Route::get('/staff/{admin}/edit', 'edit')->name('staff.edit')->where('admin', '[a-zA-Z0-9_\-]+');
        Route::put('/staff/{admin}', 'update')->name('staff.update')->where('admin', '[a-zA-Z0-9_\-]+');
        Route::delete('/staff/{admin}', 'destroy')->name('staff.destroy')->where('admin', '[a-zA-Z0-9_\-]+');
        Route::put('/staff/{admin}/reset-password', 'resetPassword')->name('staff.reset-password')->where('admin', '[a-zA-Z0-9_\-]+');
        Route::post('/staff/{admin}/upload-profile', 'uploadProfile')->name('staff.upload-profile')->where('admin', '[a-zA-Z0-9_\-]+');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes — News
    |--------------------------------------------------------------------------
    */
    Route::controller(NewsController::class)->group(function () {
        Route::get('/news', 'index')->name('news.index');
        Route::get('/news/create', 'create')->name('news.create');
        Route::post('/news', 'store')->name('news.store');
        Route::get('/news/{news}', 'show')->name('news.show');
        Route::get('/news/{news}/edit', 'edit')->name('news.edit');
        Route::put('/news/{news}', 'update')->name('news.update');
        Route::delete('/news/{news}', 'destroy')->name('news.destroy');
        Route::post('/news/upload-cover-image', 'updateCoverImage')->name('news.upload-cover-image');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes — Profile and Settings
    |--------------------------------------------------------------------------
    */
    Route::controller(AdminProfileController::class)->group(function () {
        Route::get('/profile', 'show')->name('profile.show');
        Route::post('/profile', 'update')->name('profile.update');
        Route::post('/password', 'changePassword')->name('profile.password');
        Route::post('/upload', 'uploadAvatar')->name('profile.upload');
    });

    Route::controller(SettingsController::class)->group(function () {
        Route::get('/settings', 'index')->name('settings.index');
        Route::put('/settings', 'update')->name('settings.update');
        Route::put('/settings/2fa', 'toggle2FA')->name('settings.2fa');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes — Roles and Permissions
    |--------------------------------------------------------------------------
    */
    Route::controller(RolesController::class)->group(function () {
        Route::get('/roles', 'index')->name('roles.index');
        Route::get('/roles/create', 'create')->name('roles.create');
        Route::post('/roles', 'store')->name('roles.store');
        Route::get('/roles/{role}/edit', 'edit')->name('roles.edit');
        Route::put('/roles/{role}', 'update')->name('roles.update');
        Route::delete('/roles/{role}', 'destroy')->name('roles.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes — Subjects
    |--------------------------------------------------------------------------
    */
    Route::controller(SubjectsController::class)->group(function () {
        Route::get('/subjects', 'index')->name('subjects.index');
        Route::get('/subjects/create', 'create')->name('subjects.create');
        Route::post('/subjects', 'store')->name('subjects.store');
        Route::get('/subjects/{subject}/edit', 'edit')->name('subjects.edit');
        Route::put('/subjects/{subject}', 'update')->name('subjects.update');
        Route::delete('/subjects/{subject}', 'destroy')->name('subjects.destroy');
        Route::get('/subjects/fetch-classes', 'fetchClasses')->name('subjects.fetch-classes');
        Route::post('/subjects/register-subjects', 'registerSubjects')->name('subjects.register-subjects');
        Route::get('/subjects/registered', 'registered')->name('subjects.registered');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes — Online Entrance
    |--------------------------------------------------------------------------
    */
    Route::controller(OnlineEntranceController::class)->group(function () {
        Route::get('/online_entrance', 'index')->name('online_entrance.index');
        Route::get('/online_entrance/pdf', 'applicantsPdf')->name('online_entrance.pdf');
        Route::get('/online_entrance/{entrance}', 'show')->name('online_entrance.show');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes — Notifications
    |--------------------------------------------------------------------------
    */
    Route::controller(NotificationsController::class)->group(function () {
        Route::get('/notifications', 'index')->name('notifications.index');
        Route::delete('/notifications/{notification}', 'destroy')->name('notifications.destroy');
        Route::delete('/notifications', 'destroyAll')->name('notifications.clear');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes — Status and Bulk
    |--------------------------------------------------------------------------
    */
    Route::get('/status', [StatusController::class, 'index'])->name('status.index');
    Route::controller(BulkController::class)->group(function () {
        Route::get('/bulk', 'index')->name('bulk.index');
        Route::post('/bulk/send', 'send')->name('bulk.send');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes — Results
    |--------------------------------------------------------------------------
    */
    Route::controller(ResultController::class)->group(function () {
        Route::get('/transcript', 'transcript')->name('transcript');
        Route::get('/results_by_params', 'resultsByParams')->name('results-by-params');
        Route::post('/results/publish', 'publishResults')->name('results.publish');
        Route::post('/results/upload-term', 'uploadResults')->name('results.upload-term');
        Route::get('/upload-results', 'upload')->name('upload-results');
        Route::get('/publish-results', 'publish')->name('publish-results');
        Route::get('/results/uploaded', 'getUploadedResults')->name('results.uploaded');
        Route::get('/results/published', 'viewPublished')->name('results.published');
        Route::post('/results/published/toggle-live', 'togglePublishedLive')->name('results.published.toggle-live');
        Route::post('/results/published/set-live', 'setPublishedLiveBulk')->name('results.published.set-live');
        Route::post('/results/published/delete', 'deletePublished')->name('results.published.delete');
        Route::post('/results/remark', [ResultRemarkController::class, 'storeOrUpdate'])->name('results.remark');
        Route::put('/results/edit', 'edit')->name('results.edit');
        Route::post('/results/approve', 'approve')->name('results.approve');
        Route::post('/results/reject', 'reject')->name('results.reject');
        Route::post('/results/delete', 'delete')->name('results.delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes — Teacher Management
    |--------------------------------------------------------------------------
    */
    Route::controller(TeachersController::class)->group(function () {
        Route::get('/teachers', 'index')->name('teachers.index');
        Route::get('/teachers/{teacher}/edit', 'edit')->name('teachers.edit')->where('teacher', '[a-zA-Z0-9_\-]+');
        Route::put('/teachers/{teacher}', 'update')->name('teachers.update')->where('teacher', '[a-zA-Z0-9_\-]+');
        Route::delete('/teachers/{teacher}', 'destroy')->name('teachers.destroy')->where('teacher', '[a-zA-Z0-9_\-]+');
        Route::post('/teachers/reset-password', 'resetPassword')->name('teachers.reset-password');
        Route::post('/teachers/upload-profile', 'uploadProfile')->name('teachers.upload-profile');
        Route::post('/teachers/update-contact', 'updateContact')->name('teachers.update-contact');
        Route::post('/teachers/update-employment', 'updateEmployment')->name('teachers.update-employment');
        Route::post('/teachers/form-teacher-status', 'formTeacherStatus')->name('teachers.form-teacher-status');
        Route::post('/teachers/modify-results', 'modifyResults')->name('teachers.modify-results');
        Route::post('/teachers/reset-teachers-password', 'resetPassword')->name('legacy.teachers.reset-password');
        Route::post('/teachers/upload-teachers-profile', 'uploadProfile')->name('legacy.teachers.upload-profile');
        Route::post('/teachers/edit-teachers-contact-address', 'updateContact')->name('legacy.teachers.update-contact');
        Route::post('/teachers/edit-teachers-employment-status', 'updateEmployment')->name('legacy.teachers.update-employment');
        Route::get('/register_teacher', 'registerForm')->name('register_teacher.form');
        Route::post('/register_teacher', 'registerStore')->name('register_teacher.store');
        Route::get('/assign_teacher_to_class', 'assignClassForm')->name('assign_teacher_to_class.form');
        Route::post('/assign_teacher_to_class', 'assignClassStore')->name('assign_teacher_to_class.store');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes — Attendance
    |--------------------------------------------------------------------------
    */
    Route::controller(AttendanceController::class)->group(function () {
        Route::get('/attendance', 'index')->name('attendance.index');
        Route::get('/attendance/view-attendance', 'viewAttendance')->name('attendance.view');
        Route::get('/attendance/take-attendance', 'takeAttendance')->name('attendance.take');
        Route::get('/attendance/record', 'getRecord')->name('attendance.record');
        Route::post('/attendance/save', 'save')->name('attendance.save');
        Route::put('/attendance/edit', 'edit')->name('attendance.edit');
        Route::post('/attendance/delete', 'destroy')->name('attendance.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes — Behavioral
    |--------------------------------------------------------------------------
    */
    Route::controller(BehavioralController::class)->group(function () {
        Route::get('/behavioral', 'index')->name('behavioral.index');
        Route::get('/behavioral/view-behavioral', 'viewBehavioral')->name('behavioral.view');
        Route::get('/behavioral/take-behavioral', 'takeBehavioral')->name('behavioral.take');
        Route::get('/behavioral/record', 'getRecord')->name('behavioral.record');
        Route::post('/behavioral/save', 'save')->name('behavioral.save');
        Route::put('/behavioral/edit', 'edit')->name('behavioral.edit');
        Route::delete('/behavioral/delete-one', 'destroyOne')->name('behavioral.destroy-one');
        Route::post('/behavioral/delete-all', 'destroyByRequest')->name('behavioral.delete-all');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes — Scratch Card / Pins
    |--------------------------------------------------------------------------
    */
    Route::controller(CardController::class)->group(function () {
        Route::get('/card', 'index')->name('card.index');
        Route::get('/card/unused-pins', 'unusedPins')->name('card.unused-pins');
        Route::get('/card/unused-pins/pdf', 'unusedPinsPdf')->name('card.unused-pins.pdf');
        Route::get('/card/used-pins', 'usedPins')->name('card.used-pins');
        Route::post('/card/generate-pins', 'generatePins')->name('card.generate-pins.store');
    });

    /*
    |--------------------------------------------------------------------------
    | Report card fees & checklists (student result page content)
    |--------------------------------------------------------------------------
    */
    Route::resource('fees', FeeController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('checklists', ChecklistController::class)->only(['index', 'store', 'update', 'destroy']);
});
