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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TeachersController extends Controller
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

    public function edit(string $teacher): View|RedirectResponse
    {
        $teacherModel = Teacher::query()->where('userId', $teacher)->first();
        if (! $teacherModel) {
            abort(404, 'Teacher not found.');
        }
        Gate::authorize('update', $teacherModel);

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
            'teacher' => $teacherModel,
            'getClasses' => $getClasses,
            'getSubjects' => $getSubjects,
            'states' => $states,
        ]);
    }

    public function update(UpdateTeacherRequest $request, string $teacher): JsonResponse|RedirectResponse
    {
        $teacherModel = Teacher::query()->where('userId', $teacher)->first();
        if (! $teacherModel) {
            abort(404, 'Teacher not found.');
        }
        Gate::authorize('update', $teacherModel);

        $data = $request->only([
            'firstname',
            'lastname',
            'othername',
            'email',
            'phone',
            'date_of_birth',
            'employment_date',
            'gender',
        ]);
        $teacherModel->update($data);

        $admin = $request->user('admin');
        if ($admin) {
            Notification::query()->create([
                'title' => 'Teacher Account Edited',
                'message' => $admin->name.' has edited the account information of teacher: '.$teacherModel->firsname.' '.$teacherModel->lastname.'.',
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $teacherModel->firstname.' '.$teacherModel->lastname.' profile has been updated successfully.',
            ]);
        }

        return redirect()->route('admin.teachers.index')
            ->with('success', $teacherModel->firstname.' '.$teacherModel->lastname.' profile has been updated successfully.');
    }

    public function resetPassword(ResetTeacherPasswordRequest $request): JsonResponse
    {
        $teacher = Teacher::query()->where('userId', $request->input('userId'))->first();
        if (! $teacher) {
            return response()->json(['status' => 'error', 'message' => 'Teacher not found.'], 404);
        }

        Gate::authorize('update', $teacher);
        $teacher->update([
            'password' => Hash::make($request->input('password')),
            'password_change_date' => now()->format('Y-m-d H:i:s'),
        ]);

        $admin = $request->user('admin');
        if ($admin) {
            Notification::query()->create([
                'title' => 'Teacher Password Reset',
                'message' => $admin->name.' has reset password of teacher: '.$teacher->firstname.' '.$teacher->lastname.'.',
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Teacher\'s password has been successfully changed.',
        ]);
    }

    public function uploadProfile(UploadTeacherProfileRequest $request): JsonResponse
    {
        $teacher = Teacher::query()->where('userId', $request->input('userId'))->first();
        if (! $teacher) {
            return response()->json(['status' => 'error', 'message' => 'Teacher not found.'], 404);
        }

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

    public function updateContact(UpdateTeacherContactRequest $request): JsonResponse
    {
        $teacher = Teacher::query()->where('userId', $request->input('userId'))->first();
        if (! $teacher) {
            return response()->json(['status' => 'error', 'message' => 'Teacher not found.'], 404);
        }

        Gate::authorize('update', $teacher);
        $teacher->update($request->only(['lga', 'state', 'city', 'country', 'address']));

        $admin = $request->user('admin');
        if ($admin) {
            Notification::query()->create([
                'title' => 'Teacher Contact Updated',
                'message' => $admin->name.' has updated the contact details of teacher: '.$teacher->firstname.' '.$teacher->lastname.'.',
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Teacher\'s contact address has been updated successfully.',
        ]);
    }

    public function updateEmployment(UpdateTeacherEmploymentRequest $request): JsonResponse
    {
        $teacher = Teacher::query()->where('userId', $request->input('userId'))->first();
        if (! $teacher) {
            return response()->json(['status' => 'error', 'message' => 'Teacher not found.'], 404);
        }

        Gate::authorize('update', $teacher);
        $assignedClass = is_array($request->input('assigned_class')) ? implode(',', $request->input('assigned_class')) : (string) $request->input('assigned_class');
        $subjectToTeach = is_array($request->input('subject_to_teach')) ? implode(',', $request->input('subject_to_teach')) : (string) $request->input('subject_to_teach');
        $teacher->update([
            'assigned_class' => $assignedClass,
            'subject_to_teach' => $subjectToTeach,
        ]);

        $admin = $request->user('admin');
        if ($admin) {
            Notification::query()->create([
                'title' => 'Teacher Employment Status Updated',
                'message' => $admin->name.' has updated the employment status of teacher: '.$teacher->firstname.' '.$teacher->lastname.'.',
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Teacher\'s employment status has been updated successfully',
        ]);
    }

    public function formTeacherStatus(Request $request): JsonResponse
    {
        $userId = $request->input('userId');
        $formTeacher = (int) $request->input('form_teacher', 0);
        if ($userId === null || $userId === '') {
            return response()->json(['status' => 'error', 'message' => 'User ID is required.'], 422);
        }

        $teacher = Teacher::query()->where('userId', $userId)->first();
        if (! $teacher) {
            return response()->json(['status' => 'error', 'message' => 'Teacher not found.'], 404);
        }

        Gate::authorize('update', $teacher);
        $teacher->update(['form-teacher' => $formTeacher === 1 ? 1 : 0]);

        $admin = $request->user('admin');
        if ($admin) {
            Notification::query()->create([
                'title' => 'Teacher Form Status Updated',
                'message' => $admin->name.' has updated the form status of teacher: '.$teacher->firstname.' '.$teacher->lastname.'.',
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Form teacher status has been updated successfully.',
        ]);
    }

    public function modifyResults(Request $request): JsonResponse
    {
        $userId = $request->input('userId');
        $modifyResults = (int) $request->input('modify_results', 0);
        if ($userId === null || $userId === '') {
            return response()->json(['status' => 'error', 'message' => 'User ID is required.'], 422);
        }

        $teacher = Teacher::query()->where('userId', $userId)->first();
        if (! $teacher) {
            return response()->json(['status' => 'error', 'message' => 'Teacher not found.'], 404);
        }

        Gate::authorize('update', $teacher);
        $teacher->update(['modify_results' => $modifyResults === 1 ? 1 : 0]);

        $admin = $request->user('admin');
        if ($admin) {
            Notification::query()->create([
                'title' => 'Teacher Result Modification Status Updated',
                'message' => $admin->name.' has updated the result modification status of teacher: '.$teacher->firstname.' '.$teacher->lastname.'.',
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Modify results permission has been updated successfully.',
        ]);
    }

    public function destroy(Request $request, string $teacher): JsonResponse
    {
        $teacherModel = Teacher::query()->where('userId', $teacher)->first();
        if (! $teacherModel) {
            return response()->json([
                'status' => 'error',
                'message' => 'Teacher not found.',
            ], 404);
        }

        Gate::authorize('delete', $teacherModel);
        $fullName = trim(($teacherModel->firstname ?? '').' '.($teacherModel->lastname ?? ''));

        $teacherModel->delete();

        $admin = $request->user('admin');
        if ($admin) {
            Notification::query()->create([
                'title' => 'Teacher Account Deleted',
                'message' => $admin->name.' has deleted a teacher account: '.$fullName.' ('.$teacherModel->userId.')',
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

        $userId = $this->uniqueId();
        $assignedClass = is_array($request->input('assigned_class')) ? implode(',', $request->input('assigned_class')) : '';
        $subjectToTeach = is_array($request->input('subject_to_teach')) ? implode(',', $request->input('subject_to_teach')) : '';

        $data = [
            'userId' => $userId,
            'imagelocation' => 'default.png',
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'othername' => $request->input('othername'),
            'date_of_birth' => $request->input('date_of_birth'),
            'gender' => $request->input('gender'),
            'phone' => $request->input('formattedPhone'),
            'lga' => $request->input('lga'),
            'state' => $request->input('state'),
            'city' => $request->input('city'),
            'country' => $request->input('country'),
            'address' => $request->input('address'),
            'employment_date' => $request->input('employment_date'),
            'assigned_class' => $assignedClass,
            'subject_to_teach' => $subjectToTeach,
            'form-teacher' => (int) $request->input('form_teacher', 2),
            'registration_date' => now()->format('Y-m-d H:i:s'),
        ];

        if ($request->hasFile('photoimg')) {
            $file = $request->file('photoimg');
            $filename = Str::random(12).'.'.strtolower($file->getClientOriginalExtension());
            $file->storeAs('teachers', $filename, 'public');
            $data['imagelocation'] = $filename;
        }

        Teacher::query()->create($data);

        $admin = $request->user('admin');
        if ($admin) {
            Notification::query()->create([
                'title' => 'Teacher Registered',
                'message' => $admin->name.' has registered a new teacher: '.$request->input('firstname').' '.$request->input('lastname'),
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $request->input('firstname').' '.$request->input('lastname').' account has been registered successfully.',
                'redirect' => 'admin/teachers',
            ]);
        }

        return redirect()->route('admin.teachers.index')->with('success', $request->input('firstname').' '.$request->input('lastname').' account has been registered successfully.');
    }

    public function assignClassForm(): View
    {
        Gate::authorize('viewAny', Teacher::class);
        $getTeachers = Teacher::query()->orderBy('firstname')->orderBy('lastname')->get();
        $getClasses = SchoolClass::query()->orderBy('class_name')->get();

        $teacherAssignedClasses = [];
        foreach ($getTeachers as $t) {
            $assigned = $t->assigned_class ?? '';
            $teacherAssignedClasses[$t->userId] = $assigned !== ''
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
        $teacher = Teacher::query()->where('userId', $request->input('teachersList'))->first();
        if (! $teacher) {
            return response()->json(['status' => 'error', 'message' => 'Teacher not found.'], 404);
        }
        Gate::authorize('update', $teacher);
        $assignedClass = is_array($request->input('assigned_class')) ? implode(',', $request->input('assigned_class')) : (string) $request->input('assigned_class');
        $teacher->update(['assigned_class' => $assignedClass]);
        $admin = $request->user('admin');
        if ($admin) {
            Notification::query()->create([
                'title' => 'Teacher Registration',
                'message' => $admin->name.' has assigned teacher: '.$teacher->firstname.' to class '.$assignedClass,
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
