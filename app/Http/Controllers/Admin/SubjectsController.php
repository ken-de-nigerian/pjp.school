<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\Notification;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SubjectsController extends Controller
{
    public function __construct(
        private readonly StudentService $studentService
    ) {}

    public function index(Request $request): View|RedirectResponse
    {
        Gate::authorize('viewAny', Subject::class);

        $grade = $request->query('grade');
        if ($grade !== 'Junior' && $grade !== 'Senior') {
            return redirect()->route('admin.subjects.index', ['grade' => 'Junior']);
        }

        $query = Subject::query()->where('grade', $grade)->orderBy('subject_name');
        $subjects = $query->paginate(20)->withQueryString();
        $juniorCount = Subject::query()->where('grade', 'Junior')->count();
        $seniorCount = Subject::query()->where('grade', 'Senior')->count();

        $editSubject = null;
        $editId = $request->query('edit');
        if ($editId !== null && $editId !== '' && ctype_digit((string) $editId)) {
            $candidate = Subject::query()->find((int) $editId);
            if ($candidate !== null) {
                Gate::authorize('update', $candidate);
                $editSubject = $candidate;
            }
        }

        return view('admin.subjects.index', [
            'subjects' => $subjects,
            'juniorCount' => $juniorCount,
            'seniorCount' => $seniorCount,
            'filterGrade' => $grade,
            'editSubject' => $editSubject,
        ]);
    }

    public function create(): RedirectResponse
    {
        Gate::authorize('create', Subject::class);

        return redirect()->route('admin.subjects.index', ['grade' => 'Junior']);
    }

    public function store(StoreSubjectRequest $request): JsonResponse|RedirectResponse
    {
        Gate::authorize('create', Subject::class);

        $subject = Subject::query()->create($request->validated());
        $admin = $request->user('admin');

        if ($admin) {
            Notification::query()->create([
                'title' => 'New Subject Added',
                'message' => $admin->name.' has added a new subject: '.$subject->subject_name.' for '.$subject->grade.' class',
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        $message = $subject->subject_name.' subject has been added successfully.';
        $redirectUrl = route('admin.subjects.index', ['grade' => $subject->grade]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect()->to($redirectUrl)->with('success', $message);
    }

    public function edit(Subject $subject): RedirectResponse
    {
        Gate::authorize('update', $subject);

        return redirect()->route('admin.subjects.index', [
            'grade' => $subject->grade,
            'edit' => $subject->id,
        ]);
    }

    public function update(UpdateSubjectRequest $request, Subject $subject): JsonResponse|RedirectResponse
    {
        Gate::authorize('update', $subject);
        $subject->update($request->validated());
        $admin = $request->user('admin');

        if ($admin) {
            Notification::query()->create([
                'title' => 'Subject Edited',
                'message' => $admin->name.' has edited subject: '.$subject->subject_name.' for '.$subject->grade.' class',
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        $message = $subject->subject_name.' has been updated successfully for this class.';
        $redirectUrl = route('admin.subjects.index', ['grade' => $subject->grade]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect()->to($redirectUrl)->with('success', $message);
    }

    public function destroy(Request $request, Subject $subject): JsonResponse|RedirectResponse
    {
        Gate::authorize('delete', $subject);

        if ($this->subjectAssignedToTeacher($subject->subject_name)) {
            $message = __('This subject cannot be deleted because it is assigned to one or more teachers. Unassign it first.');

            return $this->destroyErrorResponse($request, $message);
        }
        if ($this->subjectRegisteredToStudents($subject->subject_name)) {
            $message = __('This subject cannot be deleted because it is registered for one or more students. Unregister it first.');

            return $this->destroyErrorResponse($request, $message);
        }

        $name = $subject->subject_name;
        $grade = $subject->grade;
        $subject->delete();

        $admin = $request->user('admin');
        if ($admin) {
            Notification::query()->create([
                'title' => 'Subject Deleted',
                'message' => $admin->name.' has deleted '.$name.' subject with: '.$subject->subject_name,
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        $message = __('This subject has been deleted successfully.');
        $redirectUrl = route('admin.subjects.index', ['grade' => $grade]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect()->to($redirectUrl)->with('success', $message);
    }

    public function fetchClasses(Request $request): View|RedirectResponse
    {
        Gate::authorize('viewAny', Subject::class);
        $getClasses = $this->studentService->getClassesArray();
        $class = $request->query('class');
        $hasFilters = $class !== null && $class !== '';

        if ($hasFilters) {
            $validated = $request->validate([
                'class' => 'required|string|max:100',
            ]);
            $class = $validated['class'];
            $students = $this->studentService->getStudentsByClass($class, 100);
            $subjects = $this->studentService->getSubjectsToRegister($class);
        } else {
            $students = collect();
            $subjects = collect();
        }

        return view('admin.subjects.register-students', [
            'getClasses' => $getClasses,
            'students' => $students,
            'subjects' => $subjects,
            'selectedClass' => $hasFilters ? $class : null,
            'hasFilters' => $hasFilters,
        ]);
    }

    public function registerSubjects(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Subject::class);

        $validated = $request->validate([
            'studentsList' => 'required|string|max:255',
            'subjectsList' => 'required|array|min:1',
            'subjectsList.*' => 'string|max:100',
        ]);

        $studentsList = $validated['studentsList'];
        $subjectsList = is_array($validated['subjectsList'])
            ? implode(',', $validated['subjectsList'])
            : (string) $validated['subjectsList'];

        $updated = $this->studentService->registerStudentSubjects((string) $studentsList, $subjectsList);

        if ($updated === 1) {
            $admin = $request->user('admin');
            if ($admin) {
                Notification::query()->create([
                    'title' => 'Subjects Registered For Student',
                    'message' => $admin->name.' has registered subjects for student with ID: '.$studentsList,
                    'date_added' => now()->format('Y-m-d H:i:s'),
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'This student\'s subjects has been registered successfully.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No change has been made to this student\'s subjects',
        ]);
    }

    public function registered(Request $request): View|RedirectResponse
    {
        Gate::authorize('viewAny', Subject::class);

        $getClasses = $this->studentService->getClassesArray();
        $getSubjects = Subject::query()->orderBy('grade')->orderBy('subject_name')->get();
        $filterClass = $request->query('class');
        $filterSubject = $request->query('subjects');
        $hasFilters = $filterClass !== null && $filterClass !== '' && $filterSubject !== null && $filterSubject !== '';

        if ($hasFilters) {
            $validated = $request->validate([
                'class' => 'required|string|max:100',
                'subjects' => 'required|string|max:100',
            ]);
            $filterClass = $validated['class'];
            $filterSubject = $validated['subjects'];
            $students = $this->studentService->getStudentsByClassAndSubject($filterClass, $filterSubject);
        } else {
            $students = collect();
        }

        return view('admin.subjects.view-registered-students', [
            'getClasses' => $getClasses,
            'getSubjects' => $getSubjects,
            'students' => $students,
            'filterClass' => $filterClass,
            'filterSubject' => $filterSubject,
            'hasFilters' => $hasFilters,
        ]);
    }

    /**
     * Check if the subject name appears in any teacher's subject_to_teach (comma-separated list).
     */
    private function subjectAssignedToTeacher(string $subjectName): bool
    {
        $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $subjectName);

        return Teacher::query()
            ->where('subject_to_teach', $subjectName)
            ->orWhere('subject_to_teach', 'like', $escaped.',%')
            ->orWhere('subject_to_teach', 'like', '%,'.$escaped.',%')
            ->orWhere('subject_to_teach', 'like', '%,'.$escaped)
            ->exists();
    }

    /**
     * Check if the subject name appears in any student's subjects (comma-separated list).
     */
    private function subjectRegisteredToStudents(string $subjectName): bool
    {
        $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $subjectName);

        return Student::query()
            ->where('subjects', $subjectName)
            ->orWhere('subjects', 'like', $escaped.',%')
            ->orWhere('subjects', 'like', '%,'.$escaped.',%')
            ->orWhere('subjects', 'like', '%,'.$escaped)
            ->exists();
    }

    private function destroyErrorResponse(Request $request, string $message): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => $message,
            ], 422);
        }

        return back()->with('error', $message);
    }
}
