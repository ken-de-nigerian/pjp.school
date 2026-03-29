<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignTeacherToClassRequest;
use App\Http\Requests\ResetTeacherPasswordRequest;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherContactRequest;
use App\Http\Requests\UpdateTeacherEmploymentRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Http\Requests\UploadTeacherProfileRequest;
use App\Models\Notification;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Services\StudentService;
use App\Support\Coercion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

final class TeachersController extends Controller
{
    public function __construct(
        private readonly StudentService $studentService
    ) {}

    public function index(): View
    {
        Gate::authorize('viewAny', Teacher::class);

        $teachers = Teacher::query()
            ->orderBy('firstname')
            ->orderBy('lastname')
            ->paginate(15);

        return view('admin.teachers.index', ['teachers' => $teachers]);
    }

    public function edit(Teacher $teacher): View
    {
        Gate::authorize('update', $teacher);

        $getClasses = $this->studentService->getClassesArray();
        $getSubjects = Subject::query()
            ->orderBy('subject_name')
            ->get()
            ->unique('subject_name')
            ->values();
        $states = [
            'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno',
            'Cross River', 'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'Gombe', 'Imo', 'Jigawa',
            'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Kogi', 'Kwara', 'Lagos', 'Nasarawa', 'Niger',
            'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau', 'Rivers', 'Sokoto', 'Taraba', 'Yobe',
            'Zamfara', 'FCT Abuja',
        ];

        return view('admin.teachers.edit', [
            'teacher' => $teacher,
            'getClasses' => $getClasses,
            'getSubjects' => $getSubjects,
            'states' => $states,
        ]);
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher): JsonResponse|RedirectResponse
    {
        Gate::authorize('update', $teacher);

        $teacher->update($request->profileUpdateAttributes());

        $admin = $request->user('admin');
        if ($admin) {
            $adminName = is_string($admin->name) && $admin->name !== '' ? $admin->name : 'Admin';
            Notification::query()->create([
                'title' => 'Teacher Account Edited',
                'message' => $adminName.' has edited the account information of teacher: '.Coercion::string($teacher->firstname).' '.Coercion::string($teacher->lastname).'.',
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        $full = trim(Coercion::string($teacher->firstname).' '.Coercion::string($teacher->lastname));

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $full.' profile has been updated successfully.',
            ]);
        }

        return redirect()->route('admin.teachers.index')
            ->with('success', $full.' profile has been updated successfully.');
    }

    public function resetPassword(ResetTeacherPasswordRequest $request, Teacher $teacher): JsonResponse
    {
        Gate::authorize('update', $teacher);
        $teacher->update([
            'password' => Hash::make($request->plainPassword()),
            'password_change_date' => now()->format('Y-m-d H:i:s'),
        ]);

        $admin = $request->user('admin');
        if ($admin) {
            $adminName = is_string($admin->name) && $admin->name !== '' ? $admin->name : 'Admin';
            Notification::query()->create([
                'title' => 'Teacher Password Reset',
                'message' => $adminName.' has reset password of teacher: '.Coercion::string($teacher->firstname).' '.Coercion::string($teacher->lastname).'.',
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Teacher\'s password has been successfully changed.',
        ]);
    }

    public function uploadProfile(UploadTeacherProfileRequest $request, Teacher $teacher): JsonResponse
    {
        Gate::authorize('update', $teacher);

        $file = $request->file('photoimg');
        $ext = $file->getClientOriginalExtension();
        $filename = Str::random(12).'.'.strtolower($ext);
        $path = $file->storeAs('teachers', $filename, 'public');

        if (! $path) {
            return response()->json(['status' => 'error', 'message' => 'Unable to save the image. Please try again.']);
        }

        $teacher->update(['imagelocation' => $filename]);

        return response()->json(['status' => 'success']);
    }

    public function updateContact(UpdateTeacherContactRequest $request, Teacher $teacher): JsonResponse
    {
        Gate::authorize('update', $teacher);
        $teacher->update($request->contactUpdateAttributes());

        $admin = $request->user('admin');
        if ($admin) {
            $adminName = is_string($admin->name) && $admin->name !== '' ? $admin->name : 'Admin';
            Notification::query()->create([
                'title' => 'Teacher Contact Updated',
                'message' => $adminName.' has updated the contact details of teacher: '.Coercion::string($teacher->firstname).' '.Coercion::string($teacher->lastname).'.',
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Teacher\'s contact address has been updated successfully.',
        ]);
    }

    public function updateEmployment(UpdateTeacherEmploymentRequest $request, Teacher $teacher): JsonResponse
    {
        Gate::authorize('update', $teacher);

        $teacher->update([
            'assigned_class' => $request->assignedClassCsv(),
            'subject_to_teach' => $request->subjectToTeachCsv(),
        ]);

        $admin = $request->user('admin');
        if ($admin) {
            $adminName = is_string($admin->name) && $admin->name !== '' ? $admin->name : 'Admin';
            Notification::query()->create([
                'title' => 'Teacher Employment Status Updated',
                'message' => $adminName.' has updated the employment status of teacher: '.Coercion::string($teacher->firstname).' '.Coercion::string($teacher->lastname).'.',
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Teacher\'s employment status has been updated successfully',
        ]);
    }

    public function formTeacherStatus(Request $request, Teacher $teacher): JsonResponse
    {
        Gate::authorize('update', $teacher);

        $formTeacher = Coercion::int($request->input('form_teacher', 0));
        $teacher->update(['form-teacher' => $formTeacher === 1 ? 1 : 0]);

        $admin = $request->user('admin');
        if ($admin) {
            $adminName = is_string($admin->name) && $admin->name !== '' ? $admin->name : 'Admin';
            Notification::query()->create([
                'title' => 'Teacher Form Status Updated',
                'message' => $adminName.' has updated the form status of teacher: '.Coercion::string($teacher->firstname).' '.Coercion::string($teacher->lastname).'.',
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Form teacher status has been updated successfully.',
        ]);
    }

    public function modifyResults(Request $request, Teacher $teacher): JsonResponse
    {
        Gate::authorize('update', $teacher);

        $modifyResults = Coercion::int($request->input('modify_results', 0));
        $teacher->update(['modify_results' => $modifyResults === 1 ? 1 : 0]);

        $admin = $request->user('admin');
        if ($admin) {
            $adminName = is_string($admin->name) && $admin->name !== '' ? $admin->name : 'Admin';
            Notification::query()->create([
                'title' => 'Teacher Result Modification Status Updated',
                'message' => $adminName.' has updated the result modification status of teacher: '.Coercion::string($teacher->firstname).' '.Coercion::string($teacher->lastname).'.',
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Modify results permission has been updated successfully.',
        ]);
    }

    public function destroy(Request $request, Teacher $teacher): JsonResponse
    {
        Gate::authorize('delete', $teacher);

        $fullName = trim(Coercion::string($teacher->firstname).' '.Coercion::string($teacher->lastname));

        $teacher->delete();

        $admin = $request->user('admin');
        if ($admin) {
            $adminName = is_string($admin->name) && $admin->name !== '' ? $admin->name : 'Admin';
            Notification::query()->create([
                'title' => 'Teacher Account Deleted',
                'message' => $adminName.' has deleted a teacher account: '.$fullName.' ('.Coercion::int($teacher->getKey()).')',
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => $fullName !== '' ? $fullName.' has been deleted successfully.' : 'Teacher has been deleted successfully.',
            'redirect' => route('admin.teachers.index'),
        ]);
    }

    public function registerForm(): View
    {
        Gate::authorize('viewAny', Teacher::class);
        $getClasses = $this->studentService->getClassesArray();
        $getSubjects = Subject::query()
            ->orderBy('subject_name')
            ->get()
            ->unique('subject_name')
            ->values();
        $states = [
            'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno',
            'Cross River', 'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'Gombe', 'Imo', 'Jigawa',
            'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Kogi', 'Kwara', 'Lagos', 'Nasarawa', 'Niger',
            'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau', 'Rivers', 'Sokoto', 'Taraba', 'Yobe',
            'Zamfara', 'FCT Abuja',
        ];

        return view('admin.teachers.register', [
            'getClasses' => $getClasses,
            'getSubjects' => $getSubjects,
            'states' => $states,
        ]);
    }

    public function registerStore(StoreTeacherRequest $request): JsonResponse|RedirectResponse
    {
        Gate::authorize('viewAny', Teacher::class);
        if (Teacher::query()->where('email', $request->input('email'))->exists()) {
            $msg = 'This email is already registered.';
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => $msg]);
            }

            return redirect()->back()->withInput()->withErrors(['email' => $msg]);
        }

        $imagelocation = 'default.png';
        if ($request->hasFile('photoimg')) {
            $file = $request->file('photoimg');
            $filename = Str::random(12).'.'.strtolower($file->getClientOriginalExtension());
            $file->storeAs('teachers', $filename, 'public');
            $imagelocation = $filename;
        }

        $data = $request->attributesForTeacherCreate(Hash::make($request->passwordPlain()), $imagelocation);

        Teacher::query()->create($data);

        $admin = $request->user('admin');
        if ($admin) {
            $adminName = is_string($admin->name) && $admin->name !== '' ? $admin->name : 'Admin';
            Notification::query()->create([
                'title' => 'Teacher Registered',
                'message' => $adminName.' has registered a new teacher: '.$request->registrationFullName(),
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }
        $regName = $request->registrationFullName();
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $regName.' account has been registered successfully.',
                'redirect' => 'admin/teachers',
            ]);
        }

        return redirect()->route('admin.teachers.index')->with('success', $regName.' account has been registered successfully.');
    }

    public function assignClassForm(): View
    {
        Gate::authorize('viewAny', Teacher::class);
        $getTeachers = Teacher::query()->orderBy('firstname')->orderBy('lastname')->get();
        $getClasses = SchoolClass::query()->orderBy('class_name')->get();

        $teacherAssignedClasses = [];
        foreach ($getTeachers as $t) {
            $assigned = $t->assigned_class ?? '';
            $teacherAssignedClasses[Coercion::int($t->getKey())] = $assigned !== ''
                ? array_map('trim', explode(',', $assigned))
                : [];
        }

        return view('admin.teachers.assign-class', [
            'getTeachers' => $getTeachers,
            'getClasses' => $getClasses,
            'teacherAssignedClasses' => $teacherAssignedClasses,
        ]);
    }

    public function assignClassStore(AssignTeacherToClassRequest $request): JsonResponse|RedirectResponse
    {
        $teacher = Teacher::query()->find($request->teacherId());
        if ($teacher === null) {
            abort(404);
        }

        Gate::authorize('update', $teacher);

        $assignedClass = $request->assignedClassCsv();
        $teacher->update(['assigned_class' => $assignedClass]);
        $admin = $request->user('admin');
        if ($admin) {
            $adminName = is_string($admin->name) && $admin->name !== '' ? $admin->name : 'Admin';
            Notification::query()->create([
                'title' => 'Teacher Registration',
                'message' => $adminName.' has assigned teacher: '.Coercion::string($teacher->firstname).' to class '.$assignedClass,
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'A new class has been assigned to the teacher successfully.',
            ]);
        }

        return redirect()->route('admin.assign_teacher_to_class.form')->with('success', 'A new class has been assigned to the teacher successfully.');
    }

    protected function uniqueId(): string
    {
        return substr(number_format(time() * rand(), 0, '', ''), 0, 12);
    }
}
