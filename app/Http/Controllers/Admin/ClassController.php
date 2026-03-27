<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddClassRequest;
use App\Http\Requests\DeleteClassRequest;
use App\Http\Requests\EditClassRequest;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final class ClassController extends Controller
{
    public function __construct(
        private readonly StudentService $studentService
    ) {}

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Student::class);
        $classesWithCounts = $this->studentService->getClassesWithCounts();

        if ($request->has('class')) {
            $validated = $request->validate([
                'class' => 'required|string|max:100',
            ]);
            $class = $validated['class'];
            $search = $request->query('q', '');
            $students = $search !== ''
                ? $this->studentService->getStudentsByClassWithSearch($class, $search)
                : $this->studentService->getStudentsByClass($class);

            $students->withQueryString();

            return view('admin.classes.index', [
                'students' => $students,
                'classesWithCounts' => $classesWithCounts,
                'selectedClass' => $class,
                'searchQuery' => $search,
            ]);
        }

        return view('admin.classes.index', [
            'students' => null,
            'classesWithCounts' => $classesWithCounts,
            'selectedClass' => '',
            'searchQuery' => $request->query('q', ''),
        ]);
    }

    public function addClass(AddClassRequest $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('viewAny', Student::class);
        if ($this->studentService->hasClass($request->validated('class_name'))) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('Class already exists.'),
                    'errors' => ['class_name' => [__('Class already exists.')]],
                ], 422);
            }

            return back()->withErrors(['class_name' => __('Class already exists.')]);
        }

        $this->studentService->addClass($request->validated('class_name'));

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Class added.'),
            ]);
        }

        return back()->with('success', __('Class added.'));
    }

    public function updateClass(EditClassRequest $request, SchoolClass $schoolClass): JsonResponse
    {
        Gate::authorize('viewAny', Student::class);
        if ($this->studentService->hasClass($request->validated('class_name'))) {
            throw new HttpResponseException(response()->json([
                'status' => 'error',
                'message' => __('Class already exists.'),
                'errors' => ['class_name' => [__('Class already exists.')]],
            ], 422));
        }

        $updated = $this->studentService->updateClass($schoolClass->id, $request->validated('class_name'));

        if (! $updated) {
            return response()->json([
                'status' => 'error',
                'message' => __('Could not update class.'),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('Class updated.'),
        ]);
    }

    public function destroyClass(DeleteClassRequest $request, SchoolClass $schoolClass): JsonResponse
    {
        Gate::authorize('viewAny', Student::class);
        if ($this->studentService->classHasStudents($request->validated('class_name'))) {
            return response()->json([
                'status' => 'error',
                'message' => __('You can only delete an empty class with no students.'),
            ], 422);
        }

        $deleted = $this->studentService->deleteClassIfEmpty($schoolClass->id, $request->validated('class_name'));

        if (! $deleted) {
            return response()->json([
                'status' => 'error',
                'message' => __('Could not delete class.'),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('Class deleted.'),
        ]);
    }
}
