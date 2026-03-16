<?php

use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\BehavioralController;
use App\Http\Controllers\Admin\BulkController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\FetchController;
use App\Http\Controllers\Admin\AcademicSessionController;
use App\Http\Controllers\Admin\CardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\NotificationsController;
use App\Http\Controllers\Admin\OnlineEntranceController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\SubjectsController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\TeachersController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Auth\AdminLoginController;
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
use App\Http\Controllers\ResultCheckController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Laravel as sole application
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect()->route('home'));

Route::get('/home', function () {
    return view('home');
})->name('home');

// Public result check (legacy: result/index)
Route::get('/result', [ResultCheckController::class, 'index'])->name('result.check');

Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminLoginController::class, 'login']);
});

Route::middleware('guest:teacher')->group(function () {
    Route::get('/teacher/login', [TeacherLoginController::class, 'showLoginForm'])->name('teacher.login');
    Route::post('/teacher/login', [TeacherLoginController::class, 'login']);
});

Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/classes', [ClassController::class, 'index'])->name('classes');
    Route::post('/students/add-class', [ClassController::class, 'addClass'])->name('add.class');
    // Legacy list URL: same UI as Classes (pick class → students)
    Route::get('/students', function (\Illuminate\Http\Request $request) {
        return redirect()->route('admin.classes', $request->query());
    })->name('students.index');
    Route::get('/students/houses', [StudentController::class, 'houses'])->name('students.houses');
    Route::get('/students/houses/view', [StudentController::class, 'viewHouse'])->name('students.houses.view');
    Route::get('/graduated', [StudentController::class, 'graduated'])->name('graduated');
    Route::get('/graduated/view-graduated', [StudentController::class, 'viewGraduated'])->name('graduated.view');
    Route::get('/left_school', [StudentController::class, 'leftSchool'])->name('left_school');
    Route::get('/left_school/view-students', [StudentController::class, 'viewLeftSchool'])->name('left_school.view');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/academic_advancement', [StudentController::class, 'academicAdvancement'])->name('students.academic_advancement');
    Route::get('/academic_advancement/demote-students', [StudentController::class, 'demoteStudents'])->name('students.demote_students');
    Route::get('/students/by-class', [StudentController::class, 'studentsByClassJson'])->name('students.by-class');
    Route::get('/students/search', [StudentController::class, 'search'])->name('students.search');
    Route::get('/students/classlist/pdf', [StudentController::class, 'classListPdf'])->name('students.classlist.pdf');
    Route::post('/students/bulk-fee-status', [StudentController::class, 'bulkToggleFee'])->name('students.bulk-fee-status');
    Route::get('/students/by-reg-number', [StudentController::class, 'getByRegNumber'])->name('students.by-reg-number');
    Route::post('/students/promote', [StudentController::class, 'promote'])->name('students.promote');
    Route::post('/students/demote', [StudentController::class, 'demote'])->name('students.demote');
    Route::post('/students/upload-students-profile', [StudentController::class, 'uploadStudentsProfile'])->name('students.upload-profile');
    Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show')->whereNumber('id');
    Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit')->whereNumber('id');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy')->whereNumber('id');
    Route::put('/students/{id}/account', [StudentController::class, 'updateAccount'])->name('students.update.account')->whereNumber('id');
    Route::put('/students/{id}/academic', [StudentController::class, 'updateAcademic'])->name('students.update.academic')->whereNumber('id');
    Route::put('/students/{id}/contact', [StudentController::class, 'updateContact'])->name('students.update.contact')->whereNumber('id');
    Route::put('/students/{id}/parents', [StudentController::class, 'updateParents'])->name('students.update.parents')->whereNumber('id');
    Route::put('/students/{id}/sponsors', [StudentController::class, 'updateSponsors'])->name('students.update.sponsors')->whereNumber('id');
    Route::put('/students/{id}/other', [StudentController::class, 'updateOther'])->name('students.update.other')->whereNumber('id');
    Route::put('/students/{id}/status', [StudentController::class, 'toggleStatus'])->name('students.toggle.status')->whereNumber('id');
    Route::put('/students/{id}/fee-status', [StudentController::class, 'toggleFee'])->name('students.toggle.fee')->whereNumber('id');
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{admin}', [StaffController::class, 'show'])->name('staff.show')->where('admin', '[a-zA-Z0-9_\-]+');
    Route::get('/staff/{admin}/edit', [StaffController::class, 'edit'])->name('staff.edit')->where('admin', '[a-zA-Z0-9_\-]+');
    Route::put('/staff/{admin}', [StaffController::class, 'update'])->name('staff.update')->where('admin', '[a-zA-Z0-9_\-]+');
    Route::delete('/staff/{admin}', [StaffController::class, 'destroy'])->name('staff.destroy')->where('admin', '[a-zA-Z0-9_\-]+');
    Route::put('/staff/{admin}/reset-password', [StaffController::class, 'resetPassword'])->name('staff.reset-password')->where('admin', '[a-zA-Z0-9_\-]+');
    Route::post('/staff/{admin}/upload-profile', [StaffController::class, 'uploadProfile'])->name('staff.upload-profile')->where('admin', '[a-zA-Z0-9_\-]+');
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('/news', [NewsController::class, 'store'])->name('news.store');
    Route::get('/news/{newsid}', [NewsController::class, 'show'])->name('news.show')->where('newsid', '[a-zA-Z0-9_\-\.]+');
    Route::get('/news/{newsid}/edit', [NewsController::class, 'edit'])->name('news.edit')->where('newsid', '[a-zA-Z0-9_\-\.]+');
    Route::put('/news/{newsid}', [NewsController::class, 'update'])->name('news.update')->where('newsid', '[a-zA-Z0-9_\-\.]+');
    Route::delete('/news/{newsid}', [NewsController::class, 'destroy'])->name('news.destroy')->where('newsid', '[a-zA-Z0-9_\-\.]+');
    Route::post('/news/upload-cover-image', [NewsController::class, 'updateCoverImage'])->name('news.upload-cover-image');
    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::post('/password', [AdminProfileController::class, 'changePassword'])->name('profile.password');
    Route::post('/upload', [AdminProfileController::class, 'uploadAvatar'])->name('profile.upload');
    // Legacy: GET admin/upload (upload UI) — redirect to settings where profile/avatar upload lives
    Route::get('/upload', fn () => redirect()->route('admin.settings.index', [], 302))->name('legacy.upload');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::put('/settings/2fa', [SettingsController::class, 'toggle2FA'])->name('settings.2fa');

    // Email templates (Phase 10)
    Route::get('/email-templates', [EmailTemplateController::class, 'index'])->name('email-templates.index');
    Route::get('/email-templates/{emailTemplate}/edit', [EmailTemplateController::class, 'edit'])->name('email-templates.edit')->where('emailTemplate', '[0-9]+');
    Route::put('/email-templates/{emailTemplate}', [EmailTemplateController::class, 'update'])->name('email-templates.update')->where('emailTemplate', '[0-9]+');

    // Roles & Permissions (Phase 4A)
    Route::get('/roles', [RolesController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RolesController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RolesController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}/edit', [RolesController::class, 'edit'])->name('roles.edit')->whereNumber('id');
    Route::put('/roles/{id}', [RolesController::class, 'update'])->name('roles.update')->whereNumber('id');
    Route::delete('/roles/{id}', [RolesController::class, 'destroy'])->name('roles.destroy')->whereNumber('id');

    // Subjects (Phase 4B)
    Route::get('/subjects', [SubjectsController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/create', [SubjectsController::class, 'create'])->name('subjects.create');
    Route::post('/subjects', [SubjectsController::class, 'store'])->name('subjects.store');
    Route::get('/subjects/{id}/edit', [SubjectsController::class, 'edit'])->name('subjects.edit')->whereNumber('id');
    Route::put('/subjects/{id}', [SubjectsController::class, 'update'])->name('subjects.update')->whereNumber('id');
    Route::delete('/subjects/{id}', [SubjectsController::class, 'destroy'])->name('subjects.destroy')->whereNumber('id');
    Route::get('/subjects/fetch-classes', [SubjectsController::class, 'fetchClasses'])->name('subjects.fetch-classes');
    Route::post('/subjects/register-subjects', [SubjectsController::class, 'registerSubjects'])->name('subjects.register-subjects');
    Route::get('/subjects/registered', [SubjectsController::class, 'registered'])->name('subjects.registered');
    Route::get('/subjects/class-list', [SubjectsController::class, 'classList'])->name('subjects.class-list');

    // Online Entrance (Phase 4D)
    Route::get('/online_entrance', [OnlineEntranceController::class, 'index'])->name('online_entrance.index');
    Route::get('/online_entrance/pdf', [OnlineEntranceController::class, 'applicantsPdf'])->name('online_entrance.pdf');
    Route::get('/online_entrance/{id}', [OnlineEntranceController::class, 'show'])->name('online_entrance.show')->whereNumber('id');

    // Notifications (Phase 5)
    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications.index');
    Route::delete('/notifications/{notification}', [NotificationsController::class, 'destroy'])->name('notifications.destroy')->whereNumber('notification');
    Route::delete('/notifications', [NotificationsController::class, 'destroyAll'])->name('notifications.clear');

    // Phase 6: Status, Bulk, AJAX fetch
    Route::get('/status', [StatusController::class, 'index'])->name('status.index');
    Route::get('/bulk', [BulkController::class, 'index'])->name('bulk.index');
    Route::post('/bulk/send', [BulkController::class, 'send'])->name('bulk.send');
    Route::get('/transcript', [ResultController::class, 'transcript'])->name('transcript');
    Route::get('/results_by_params', [ResultController::class, 'resultsByParams'])->name('results-by-params');
    // Legacy AJAX URL redirects (Phase 10: admin/ajax/* → admin/fetch etc.)
    Route::get('/ajax/students-by-class', fn () => redirect()->route('admin.fetch', request()->query(), 302))->name('legacy.ajax.students-by-class');
    Route::get('/ajax/teacher-details', fn () => redirect()->route('admin.fetch_teacher_details', request()->query(), 302))->name('legacy.ajax.teacher-details');
    Route::get('/ajax/student-details', fn () => redirect()->route('admin.fetch_students_details', request()->query(), 302))->name('legacy.ajax.student-details');
    Route::get('/ajax/subjects-for-class', fn () => redirect()->route('admin.subjectToRegister', request()->query(), 302))->name('legacy.ajax.subjects-for-class');
    Route::get('/fetch', [FetchController::class, 'fetch'])->name('fetch');
    Route::get('/fetch_teacher_details', [FetchController::class, 'fetchTeacherDetails'])->name('fetch_teacher_details');
    Route::get('/fetch_students_details', [FetchController::class, 'fetchStudentDetails'])->name('fetch_students_details');
    Route::get('/subjectToRegister', [FetchController::class, 'subjectToRegister'])->name('subjectToRegister');

    // Teachers (Phase 4C)
    Route::get('/teachers', [TeachersController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/find-teachers', fn () => redirect()->route('admin.teachers.index', request()->query(), 302))->name('teachers.find-teachers');
    Route::get('/teachers/{teacher}/edit', [TeachersController::class, 'edit'])->name('teachers.edit')->where('teacher', '[a-zA-Z0-9_\-]+');
    Route::put('/teachers/{teacher}', [TeachersController::class, 'update'])->name('teachers.update')->where('teacher', '[a-zA-Z0-9_\-]+');
    Route::delete('/teachers/{teacher}', [TeachersController::class, 'destroy'])->name('teachers.destroy')->where('teacher', '[a-zA-Z0-9_\-]+');
    Route::post('/teachers/reset-password', [TeachersController::class, 'resetPassword'])->name('teachers.reset-password');
    Route::post('/teachers/upload-profile', [TeachersController::class, 'uploadProfile'])->name('teachers.upload-profile');
    Route::post('/teachers/update-contact', [TeachersController::class, 'updateContact'])->name('teachers.update-contact');
    Route::post('/teachers/update-employment', [TeachersController::class, 'updateEmployment'])->name('teachers.update-employment');
    Route::post('/teachers/form-teacher-status', [TeachersController::class, 'formTeacherStatus'])->name('teachers.form-teacher-status');
    Route::post('/teachers/modify-results', [TeachersController::class, 'modifyResults'])->name('teachers.modify-results');
    Route::post('/teachers/login-user', [TeachersController::class, 'loginUser'])->name('teachers.login-user');
    // Legacy teacher POST aliases (same controller)
    Route::post('/teachers/reset-teachers-password', [TeachersController::class, 'resetPassword'])->name('legacy.teachers.reset-password');
    Route::post('/teachers/upload-teachers-profile', [TeachersController::class, 'uploadProfile'])->name('legacy.teachers.upload-profile');
    Route::post('/teachers/edit-teachers-contact-address', [TeachersController::class, 'updateContact'])->name('legacy.teachers.update-contact');
    Route::post('/teachers/edit-teachers-employment-status', [TeachersController::class, 'updateEmployment'])->name('legacy.teachers.update-employment');

    // Register Teacher (Phase 4F)
    Route::get('/register_teacher', [TeachersController::class, 'registerForm'])->name('register_teacher.form');
    Route::post('/register_teacher', [TeachersController::class, 'registerStore'])->name('register_teacher.store');
    // Assign Teacher to Class (Phase 4G)
    Route::get('/assign_teacher_to_class', [TeachersController::class, 'assignClassForm'])->name('assign_teacher_to_class.form');
    Route::post('/assign_teacher_to_class', [TeachersController::class, 'assignClassStore'])->name('assign_teacher_to_class.store');

    Route::post('/results/publish', [ResultController::class, 'publishResults'])->name('results.publish');
    Route::post('/results/upload-term', [ResultController::class, 'uploadResults'])->name('results.upload-term');
    /** Canonical pages (nav): Upload results · Publish results */
    Route::get('/upload-results', [ResultController::class, 'upload'])->name('upload-results');
    Route::get('/publish-results', [ResultController::class, 'publish'])->name('publish-results');
    /** Legacy URLs (same handlers) */
    Route::get('/results/uploaded', [ResultController::class, 'getUploadedResults'])->name('results.uploaded');
    Route::get('/results/published', [ResultController::class, 'viewPublished'])->name('results.published');
    Route::post('/results/published/toggle-live', [ResultController::class, 'togglePublishedLive'])->name('results.published.toggle-live');
    Route::post('/results/published/set-live', [ResultController::class, 'setPublishedLiveBulk'])->name('results.published.set-live');
    Route::post('/results/published/delete', [ResultController::class, 'deletePublished'])->name('results.published.delete');
    Route::put('/results/edit', [ResultController::class, 'edit'])->name('results.edit');
    Route::post('/results/approve', [ResultController::class, 'approve'])->name('results.approve');
    Route::post('/results/reject', [ResultController::class, 'reject'])->name('results.reject');
    Route::post('/results/delete', [ResultController::class, 'delete'])->name('results.delete');
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/view-attendance', [AttendanceController::class, 'viewAttendance'])->name('attendance.view');
    Route::get('/attendance/take-attendance', [AttendanceController::class, 'takeAttendance'])->name('attendance.take');
    Route::get('/attendance/record', [AttendanceController::class, 'getRecord'])->name('attendance.record');
    Route::post('/attendance/save', [AttendanceController::class, 'save'])->name('attendance.save');
    Route::put('/attendance/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::post('/attendance/delete', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
    Route::get('/behavioral', [BehavioralController::class, 'index'])->name('behavioral.index');
    Route::get('/behavioral/view-behavioral', [BehavioralController::class, 'viewBehavioral'])->name('behavioral.view');
    Route::get('/behavioral/take-behavioral', [BehavioralController::class, 'takeBehavioral'])->name('behavioral.take');
    Route::get('/behavioral/record', [BehavioralController::class, 'getRecord'])->name('behavioral.record');
    Route::post('/behavioral/save', [BehavioralController::class, 'save'])->name('behavioral.save');
    Route::put('/behavioral/edit', [BehavioralController::class, 'edit'])->name('behavioral.edit');
    Route::delete('/behavioral/delete-one', [BehavioralController::class, 'destroyOne'])->name('behavioral.destroy-one');
    Route::post('/behavioral/delete-all', [BehavioralController::class, 'destroyByRequest'])->name('behavioral.delete-all');
    Route::get('/sessions', [AcademicSessionController::class, 'index'])->name('sessions.index');
    Route::get('/sessions/create', [AcademicSessionController::class, 'create'])->name('sessions.create');
    Route::post('/sessions', [AcademicSessionController::class, 'store'])->name('sessions.store');
    Route::get('/sessions/{id}/edit', [AcademicSessionController::class, 'edit'])->name('sessions.edit')->whereNumber('id');
    Route::put('/sessions/{id}', [AcademicSessionController::class, 'update'])->name('sessions.update')->whereNumber('id');
    Route::delete('/sessions/{id}', [AcademicSessionController::class, 'destroy'])->name('sessions.destroy')->whereNumber('id');
    Route::post('/sessions/{id}/activate', [AcademicSessionController::class, 'activate'])->name('sessions.activate')->whereNumber('id');
    Route::get('/card', [CardController::class, 'index'])->name('card.index');
    Route::get('/card/unused-pins', [CardController::class, 'unusedPins'])->name('card.unused-pins');
    Route::get('/card/used-pins', [CardController::class, 'usedPins'])->name('card.used-pins');
    Route::get('/card/generate-pins', [CardController::class, 'showGenerate'])->name('card.generate-pins');
    Route::post('/card/generate-pins', [CardController::class, 'generatePins'])->name('card.generate-pins.store');
});

Route::middleware('auth:teacher')->prefix('teacher')->name('teacher.')->group(function () {
    Route::post('/logout', [TeacherLoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [TeacherProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [TeacherProfileController::class, 'update'])->name('profile.update');
    Route::post('/password', [TeacherProfileController::class, 'changePassword'])->name('profile.password');
    Route::get('/upload', [TeacherProfileController::class, 'uploadPage'])->name('upload');
    Route::post('/upload', [TeacherProfileController::class, 'upload'])->name('upload.store');
    Route::get('/attendance', [TeacherAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/take-attendance', [TeacherAttendanceController::class, 'takeAttendance'])->name('attendance.take-attendance');
    Route::get('/attendance/view-attendance', [TeacherAttendanceController::class, 'viewAttendance'])->name('attendance.view-attendance');
    Route::get('/behavioral', [TeacherBehavioralController::class, 'index'])->name('behavioral.index');
    Route::get('/behavioral/take-behavioral', [TeacherBehavioralController::class, 'takeBehavioral'])->name('behavioral.take-behavioral');
    Route::get('/behavioral/view-behavioral', [TeacherBehavioralController::class, 'viewBehavioral'])->name('behavioral.view-behavioral');
    Route::get('/class', [TeacherClassController::class, 'index'])->name('class.index');
    Route::get('/class/find-students', [TeacherClassController::class, 'findStudents'])->name('class.find-students');
    Route::get('/subjects', [TeacherSubjectsController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/registered', [TeacherSubjectsController::class, 'registered'])->name('subjects.registered');
    Route::get('/results', [TeacherResultsController::class, 'index'])->name('results.index');
    Route::post('/results/upload-term', [TeacherResultsController::class, 'uploadTerm'])->name('results.upload-term');
    Route::get('/uploaded', [TeacherUploadedController::class, 'index'])->name('uploaded.index');
    Route::post('/uploaded/edit-result', [TeacherUploadedController::class, 'editResult'])->name('uploaded.edit-result');
    Route::get('/published', [TeacherPublishedController::class, 'index'])->name('published.index');
});

Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');
