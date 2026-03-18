<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Contracts\NotificationServiceContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\BulkToggleStudentFeeRequest;
use App\Http\Requests\DemoteStudentsRequest;
use App\Http\Requests\PromoteStudentsRequest;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\ToggleStudentFeeRequest;
use App\Http\Requests\ToggleStudentStatusRequest;
use App\Http\Requests\UpdateStudentAcademicRequest;
use App\Http\Requests\UpdateStudentAccountRequest;
use App\Http\Requests\UpdateStudentContactRequest;
use App\Http\Requests\UpdateStudentOtherRequest;
use App\Http\Requests\UpdateStudentParentsRequest;
use App\Http\Requests\UpdateStudentSponsorsRequest;
use App\Http\Requests\UploadStudentProfilePhotoRequest;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function __construct(
        private readonly StudentService $studentService,
        private readonly NotificationServiceContract $notificationService,
    ) {}

    public function classListPdf(Request $request): View|RedirectResponse
    {
        Gate::authorize('viewAny', Student::class);

        $class = $request->query('class', '');
        if ($class === '') {
            return redirect()->route('admin.classes')
                ->with('error', 'Please select a class.');
        }

        $students = $this->studentService->getStudentsByClassAll($class);

        return view('admin.students.classlist-pdf', [
            'students' => $students,
            'selectedClass' => $class,
        ]);
    }

    public function create(Request $request): View
    {
        Gate::authorize('create', Student::class);

        $classes = $this->studentService->getClassesArray();
        $selectedClass = $request->query('class', '');

        $subjects = $selectedClass ? $this->studentService->getSubjectsToRegister($selectedClass) : collect();
        $juniorSubjects = $this->studentService->getSubjectsToRegister('JSS 1A');
        $seniorSubjects = $this->studentService->getSubjectsToRegister('SSS 1A');

        $states = [
            'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno',
            'Cross River', 'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'Gombe', 'Imo', 'Jigawa',
            'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Kogi', 'Kwara', 'Lagos', 'Nasarawa', 'Niger',
            'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau', 'Rivers', 'Sokoto', 'Taraba', 'Yobe',
            'Zamfara', 'FCT Abuja',
        ];
        $houses = config('school.houses', []);

        return view('admin.students.create', [
            'classes' => $classes,
            'selectedClass' => $selectedClass,
            'subjects' => $subjects,
            'juniorSubjects' => $juniorSubjects,
            'seniorSubjects' => $seniorSubjects,
            'nextRegNumber' => $this->studentService->getNextRegNumber(),
            'states' => $states,
            'houses' => $houses,
        ]);
    }

    public function store(StoreStudentRequest $request): JsonResponse|RedirectResponse
    {
        Gate::authorize('create', Student::class);

        $validated = $request->validated();
        unset($validated['image']);
        $imagelocation = $request->hasFile('image')
            ? $request->file('image')->store('students', 'public')
            : null;

        $student = $this->studentService->create($validated, $imagelocation);

        $adminName = $request->user('admin')->name ?? 'Admin';
        $this->notificationService->add(
            'New Student Registered',
            $adminName.' has registered a new student: '.$validated['firstname'].' '.$validated['lastname'].', into class: '.$validated['class']
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Student registered successfully.'),
                'redirect' => route('admin.students.show', $student->id),
            ]);
        }

        return redirect()
            ->route('admin.students.show', $student->id)
            ->with('success', __('Student registered successfully.'));
    }

    public function show(Student $student): View|RedirectResponse
    {
        Gate::authorize('view', $student);

        return view('admin.students.show', ['student' => $student]);
    }

    public function edit(Student $student): View|RedirectResponse
    {
        Gate::authorize('update', $student);

        $classes = $this->studentService->getClassesArray();
        $subjects = $student->class ? $this->studentService->getSubjectsToRegister($student->class) : collect();
        $juniorSubjects = $this->studentService->getSubjectsToRegister('JSS 1A');
        $seniorSubjects = $this->studentService->getSubjectsToRegister('SSS 1A');
        $states = [
            'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno',
            'Cross River', 'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'Gombe', 'Imo', 'Jigawa',
            'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Kogi', 'Kwara', 'Lagos', 'Nasarawa', 'Niger',
            'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau', 'Rivers', 'Sokoto', 'Taraba', 'Yobe',
            'Zamfara', 'FCT Abuja',
        ];
        $houses = config('school.houses', []);

        return view('admin.students.edit', [
            'student' => $student,
            'classes' => $classes,
            'subjects' => $subjects,
            'juniorSubjects' => $juniorSubjects,
            'seniorSubjects' => $seniorSubjects,
            'states' => $states,
            'houses' => $houses,
        ]);
    }

    public function updateAccount(UpdateStudentAccountRequest $request, Student $student): JsonResponse|RedirectResponse
    {
        Gate::authorize('update', $student);

        $this->studentService->updateAccount($student->id, $request->validated());

        $adminName = $request->user('admin')->name ?? 'Admin';
        $this->notificationService->add(
            'Student Profile Updated',
            $adminName.' has updated the profile information of student: '.$student->firstname.' '.$student->lastname.', in class: '.$student->class
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['status' => 'success', 'message' => __('Student\'s profile has been updated successfully.')]);
        }

        return redirect()
            ->route('admin.students.edit', $student)
            ->with('success', __('Student\'s profile has been updated successfully.'));
    }

    public function updateAcademic(UpdateStudentAcademicRequest $request, Student $student): JsonResponse|RedirectResponse
    {
        Gate::authorize('update', $student);

        $this->studentService->updateAcademicProfile(
            $student->id,
            $request->input('class'),
            $request->input('subjects', ''),
            $request->input('reg_number')
        );

        $adminName = $request->user('admin')->name ?? 'Admin';
        $this->notificationService->add(
            'Student Academic Status Updated',
            $adminName.' has updated the academic status of student: '.$student->firstname.' '.$student->lastname.', in class: '.$student->class
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['status' => 'success', 'message' => __('Student\'s account status has been updated successfully.')]);
        }

        return redirect()
            ->route('admin.students.edit', $student)
            ->with('success', __('Student\'s account status has been updated successfully.'));
    }

    public function updateContact(UpdateStudentContactRequest $request, Student $student): JsonResponse|RedirectResponse
    {
        Gate::authorize('update', $student);

        $this->studentService->updateContactAddress($student->id, $request->validated());

        $adminName = $request->user('admin')->name ?? 'Admin';
        $this->notificationService->add(
            'Contact Information Updated',
            $adminName.' has updated the contact information of student: '.$student->firstname.' '.$student->lastname.', in class: '.$student->class
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['status' => 'success', 'message' => __('Student\'s contact address has been updated successfully')]);
        }

        return redirect()
            ->route('admin.students.edit', $student)
            ->with('success', __('Student\'s contact address has been updated successfully'));
    }

    public function updateParents(UpdateStudentParentsRequest $request, Student $student): JsonResponse|RedirectResponse
    {
        Gate::authorize('update', $student);

        $this->studentService->updateParentsInformation($student->id, $request->validated());

        $adminName = $request->user('admin')->name ?? 'Admin';
        $this->notificationService->add(
            'Parents Information Updated',
            $adminName.' has updated the parent\'s information of student: '.$student->firstname.' '.$student->lastname.', in class: '.$student->class
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['status' => 'success', 'message' => __('Parent\'s information has been updated successfully.')]);
        }

        return redirect()
            ->route('admin.students.edit', $student)
            ->with('success', __('Parent\'s information has been updated successfully.'));
    }

    public function updateSponsors(UpdateStudentSponsorsRequest $request, Student $student): JsonResponse|RedirectResponse
    {
        Gate::authorize('update', $student);

        $this->studentService->updateSponsorsInformation($student->id, $request->validated());

        $adminName = $request->user('admin')->name ?? 'Admin';
        $this->notificationService->add(
            'Sponsors Information Updated',
            $adminName.' has updated the sponsor\'s information of student: '.$student->firstname.' '.$student->lastname.', in class: '.$student->class
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['status' => 'success', 'message' => __('Sponsor\'s information has been updated successfully.')]);
        }

        return redirect()
            ->route('admin.students.edit', $student)
            ->with('success', __('Sponsor\'s information has been updated successfully.'));
    }

    public function updateOther(UpdateStudentOtherRequest $request, Student $student): JsonResponse|RedirectResponse
    {
        Gate::authorize('update', $student);

        $v = $request->validated();
        $this->studentService->updateOtherInformation(
            $student->id,
            $v['house'] ?? '',
            $v['category'] ?? ''
        );

        $adminName = $request->user('admin')->name ?? 'Admin';
        $this->notificationService->add(
            'Students Information Updated',
            $adminName.' has updated the information of student: '.$student->firstname.' '.$student->lastname.', in class: '.$student->class
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['status' => 'success', 'message' => __('Student\'s information has been updated successfully.')]);
        }

        return redirect()
            ->route('admin.students.edit', $student)
            ->with('success', __('Student\'s information has been updated successfully.'));
    }

    public function destroy(Student $student): JsonResponse|RedirectResponse
    {
        Gate::authorize('delete', $student);

        $this->studentService->delete($student->id);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Student deleted.'),
                'redirect' => route('admin.classes'),
            ]);
        }

        return redirect()
            ->route('admin.classes')
            ->with('success', __('Student deleted.'));
    }

    public function uploadStudentsProfile(UploadStudentProfilePhotoRequest $request): JsonResponse
    {
        $studentId = (int) $request->input('studentId');
        $student = $this->studentService->getById($studentId);
        if (! $student) {
            return response()->json(['status' => 'error', 'message' => 'Student not found.'], 404);
        }
        Gate::authorize('update', $student);

        $path = $request->file('photoimg')->store('students', 'public');
        $updated = $this->studentService->updateProfilePicture($studentId, $path);
        if ($updated) {
            $adminName = $request->user('admin')->name ?? 'Admin';
            $this->notificationService->add(
                'Profile Picture Updated',
                $adminName.' has updated the profile picture of student: '.$student->firstname.' '.$student->lastname.', in class: '.$student->class
            );
        }

        return response()->json(
            $updated
                ? ['status' => 'success', 'message' => __('Profile picture updated.'), 'image_url' => asset('storage/'.$path)]
                : ['status' => 'error', 'message' => __('No changes have been made to your profile.')]
        );
    }

    public function toggleStatus(ToggleStudentStatusRequest $request, Student $student): JsonResponse|RedirectResponse
    {
        Gate::authorize('update', $student);

        if ($request->input('status') == 1) {
            $class_arm = 'left-school';
        } else {
            $pattern = '/(JSS|SSS) [1-3]/';
            if (preg_match($pattern, $student->class, $matches)) {
                $class_arm = $matches[0];
            } else {
                $class_arm = 'left-school';
            }
        }

        $this->studentService->toggleStatus(
            $student->id,
            $request->input('status'),
            $class_arm
        );

        $adminName = $request->user('admin')->name ?? 'Admin';
        $this->notificationService->add(
            'Students Status Updated',
            $adminName.' has updated the status of student: '.$student->firstname.' '.$student->lastname
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['status' => 'success', 'message' => __('Student\'s account status has been updated successfully.')]);
        }

        return back()->with('success', __('Student\'s account status has been updated successfully.'));
    }

    public function toggleFee(ToggleStudentFeeRequest $request, Student $student): JsonResponse|RedirectResponse
    {
        Gate::authorize('update', $student);

        $this->studentService->toggleFeeStatus($student->id, (int) $request->input('fee_status'));

        $adminName = $request->user('admin')->name ?? 'Admin';
        $this->notificationService->add(
            'Fee Status Updated',
            $adminName.' has updated the fee status of student: '.$student->firstname.' '.$student->lastname.', in class: '.$student->class
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['status' => 'success', 'message' => __('Student\'s fee status has been updated successfully.')]);
        }

        return back()->with('success', __('Student\'s fee status has been updated successfully.'));
    }

    public function bulkToggleFee(BulkToggleStudentFeeRequest $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('viewAny', Student::class);

        $feeStatus = (int) $request->input('fee_status');

        if ($request->boolean('entire_class') && $request->filled('class')) {
            $students = $this->studentService->getStudentsByClassAll($request->input('class'));
            $ids = $students->pluck('id')->all();
        } else {
            $ids = array_values(array_filter((array) $request->input('ids', []), 'is_numeric'));
        }

        $updated = $this->studentService->updateFeeStatusBulk($ids, $feeStatus);

        $message = $updated === 0
            ? __('No students updated.')
            : trans_choice('Fee status updated for :count student.|Fee status updated for :count students.', $updated, ['count' => $updated]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'redirect' => route('admin.classes', ['class' => $request->input('class', '')]),
            ]);
        }

        return back()->with('success', $message);
    }

    public function academicAdvancement(): View
    {
        Gate::authorize('viewAny', Student::class);
        $getClasses = $this->studentService->getClassesArray();

        return view('admin.students.academic-advancement', ['getClasses' => $getClasses]);
    }

    public function demoteStudents(): View
    {
        Gate::authorize('viewAny', Student::class);
        $getClasses = $this->studentService->getClassesArray();

        return view('admin.students.demote-students', ['getClasses' => $getClasses]);
    }

    public function studentsByClassJson(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Student::class);
        $class = $request->query('class', '');
        if ($class === '') {
            return response()->json(['students' => []]);
        }
        $paginator = $this->studentService->getStudentsByClass($class, 500);
        $students = collect($paginator->items())->map(fn ($s) => [
            'id' => $s->id,
            'reg_number' => $s->reg_number,
            'firstname' => $s->firstname,
            'lastname' => $s->lastname,
        ])->values()->all();

        return response()->json(['students' => $students]);
    }

    public function promote(PromoteStudentsRequest $request): RedirectResponse|JsonResponse
    {
        $fromClass = (string) $request->input('from_class');
        $toClass = (string) $request->input('to_class');

        $count = Student::query()->where('class', $fromClass)->count();
        if ($count === 0) {
            $errors = ['from_class' => [__('There are no students in this class to promote.')]];

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('There are no students in this class to promote.'),
                    'errors' => $errors,
                ], 422);
            }

            return back()->withErrors($errors)->withInput();
        }

        $this->studentService->promote($fromClass, $toClass);

        $adminName = $request->user('admin')->name ?? 'Admin';
        $this->notificationService->add(
            'Class Promotion',
            $adminName.' has promoted students from: '.$fromClass.' to '.$toClass
        );

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Promotion was done successfully.'),
            ]);
        }

        return back()->with('success', __('Promotion was done successfully.'));
    }

    public function demote(DemoteStudentsRequest $request): RedirectResponse|JsonResponse
    {
        $fromClass = (string) $request->input('from_class');
        $toClass = (string) $request->input('to_class');

        $this->studentService->demote(
            $toClass,
            $request->input('student_ids')
        );

        $adminName = $request->user('admin')->name ?? 'Admin';
        $this->notificationService->add(
            'Class Demotion',
            $adminName.' has demoted students from: '.$fromClass.' to '.$toClass
        );

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Students demoted successfully.'),
            ]);
        }

        return back()->with('success', __('Students demoted successfully.'));
    }

    public function houses(): View
    {
        Gate::authorize('viewAny', Student::class);
        $getHouses = $this->studentService->getHouseCounts();

        return view('admin.students.houses', ['getHouses' => $getHouses]);
    }

    public function viewHouse(Request $request): View|RedirectResponse
    {
        Gate::authorize('viewAny', Student::class);

        $validated = $request->validate([
            'house' => 'required|string|max:100',
            'search' => 'nullable|string|max:255',
            'class' => 'nullable|string|max:100',
        ]);

        $house = $validated['house'];
        $search = $validated['search'] ?? '';
        $class = $validated['class'] ?? '';
        $students = $this->studentService->getStudentsInHouse($house, $search ?: null, $class ?: null);
        $getClasses = $this->studentService->getClassesArray();

        return view('admin.students.view-house', [
            'house' => $house,
            'students' => $students,
            'getClasses' => $getClasses,
            'search' => $search,
            'classFilter' => $class,
        ]);
    }

    public function graduated(): View|RedirectResponse
    {
        Gate::authorize('viewAny', Student::class);
        $graduationYearsWithCounts = $this->studentService->getGraduationYearsWithCounts();

        return view('admin.students.graduated', ['graduationYearsWithCounts' => $graduationYearsWithCounts]);
    }

    public function viewGraduated(Request $request): View|RedirectResponse
    {
        Gate::authorize('viewAny', Student::class);

        $validated = $request->validate([
            'year' => 'required|string|max:20',
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
        ]);

        $year = $validated['year'];
        $search = $validated['search'] ?? '';
        $perPage = 25;
        $currentPage = (int) $request->query('page', 1);

        $collection = $this->studentService->getStudentsByGraduationYear($year);

        if ($search !== '') {
            $q = strtolower($search);
            $collection = $collection->filter(function ($s) use ($q) {
                $name = strtolower(trim(($s->firstname ?? '').' '.($s->lastname ?? '').' '.($s->othername ?? '')));
                $reg = strtolower((string) ($s->reg_number ?? ''));

                return str_contains($name, $q) || str_contains($reg, $q);
            })->values();
        }

        $total = $collection->count();
        $items = $total > 0 ? $collection->slice(($currentPage - 1) * $perPage, $perPage)->values() : $collection;
        $students = new LengthAwarePaginator($items, $total, $perPage, $currentPage, ['path' => $request->url()]);
        $students->withQueryString();

        return view('admin.students.view-graduated', [
            'students' => $students,
            'year' => $year,
            'search' => $search,
        ]);
    }

    public function leftSchool(): View
    {
        Gate::authorize('viewAny', Student::class);
        $leftSchoolYearsWithCounts = $this->studentService->getLeftSchoolYearsWithCounts();

        return view('admin.students.left-school', ['leftSchoolYearsWithCounts' => $leftSchoolYearsWithCounts]);
    }

    public function viewLeftSchool(Request $request): View|RedirectResponse
    {
        Gate::authorize('viewAny', Student::class);

        $validated = $request->validate([
            'year' => 'required|string|max:20',
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
        ]);

        $year = $validated['year'];
        $search = $validated['search'] ?? '';
        $perPage = 25;
        $currentPage = (int) $request->query('page', 1);

        $collection = $this->studentService->getStudentsWhoLeftSchool($year);

        if ($search !== '') {
            $q = strtolower($search);
            $collection = $collection->filter(function ($s) use ($q) {
                $name = strtolower(trim(($s->firstname ?? '').' '.($s->lastname ?? '').' '.($s->othername ?? '')));
                $reg = strtolower((string) ($s->reg_number ?? ''));

                return str_contains($name, $q) || str_contains($reg, $q);
            })->values();
        }

        $total = $collection->count();
        $items = $total > 0 ? $collection->slice(($currentPage - 1) * $perPage, $perPage)->values() : $collection;
        $students = new LengthAwarePaginator($items, $total, $perPage, $currentPage, ['path' => $request->url()]);
        $students->withQueryString();

        return view('admin.students.view-left-school', [
            'students' => $students,
            'year' => $year,
            'search' => $search,
        ]);
    }
}
