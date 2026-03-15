<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\Notification;
use App\Models\Subject;
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
                'message' => $admin->name . ' has added a new subject: ' . $subject->subject_name . ' for ' . $subject->grade . ' class',
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        $message = $subject->subject_name . ' subject has been added successfully.';
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

    public function edit(int $id): RedirectResponse
    {
        $subject = Subject::query()->find($id);
        if (! $subject) {
            abort(404);
        }
        Gate::authorize('update', $subject);

        return redirect()->route('admin.subjects.index', [
            'grade' => $subject->grade,
            'edit' => $subject->id,
        ]);
    }

    public function update(UpdateSubjectRequest $request, int $id): JsonResponse|RedirectResponse
    {
        $subject = Subject::query()->find($id);
        if (! $subject) {
            abort(404);
        }

        Gate::authorize('update', $subject);
        $subject->update($request->validated());
        $admin = $request->user('admin');

        if ($admin) {
            Notification::query()->create([
                'title' => 'Subject Edited',
                'message' => $admin->name . ' has edited subject: ' . $subject->subject_name . ' for ' . $subject->grade . ' class',
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        $message = $subject->subject_name . ' has been updated successfully for this class.';
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

    public function destroy(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $subject = Subject::query()->find($id);
        if (! $subject) {
            abort(404);
        }

        Gate::authorize('delete', $subject);
        $name = $subject->subject_name;
        $grade = $subject->grade;
        $subject->delete();

        $admin = $request->user('admin');
        if ($admin) {
            Notification::query()->create([
                'title' => 'Subject Deleted',
                'message' => $admin->name . ' has deleted ' . $name . ' subject with ID: ' . $id,
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

        if ($request->has('class')) {
            $validated = $request->validate([
                'class' => 'required|string|max:100',
            ]);
            $class = $validated['class'];
            $students = $this->studentService->getStudentsByClass($class, 100);
            $subjects = $this->studentService->getSubjectsToRegister($class);

            return view('admin.subjects.register-students', [
                'getClasses' => $getClasses,
                'students' => $students,
                'subjects' => $subjects,
                'selectedClass' => $class,
            ]);
        }

        return view('admin.subjects.fetch-classes', [
            'getClasses' => $getClasses,
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
                    'message' => $admin->name . ' has registered subjects for student with ID: ' . $studentsList,
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

        if ($request->has('class') || $request->has('subjects')) {
            $validated = $request->validate([
                'class' => 'required|string|max:100',
                'subjects' => 'required|string|max:100',
            ]);
            $class = $validated['class'];
            $subjects = $validated['subjects'];
            $students = $this->studentService->getStudentsByClassAndSubject($class, $subjects);

            return view('admin.subjects.view-registered-students', [
                'getClasses' => $getClasses,
                'getSubjects' => $getSubjects,
                'students' => $students,
                'filterClass' => $class,
                'filterSubject' => $subjects,
            ]);
        }

        return view('admin.subjects.registered', [
            'getClasses' => $getClasses,
            'getSubjects' => $getSubjects,
        ]);
    }
}
