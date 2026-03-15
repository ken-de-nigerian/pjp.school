<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddClassRequest;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ClassController extends Controller
{
    public function __construct(
        private readonly StudentService $studentService
    ) {}

    public function index(Request $request): View|RedirectResponse
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

            if (method_exists($students, 'withQueryString')) {
                $students->withQueryString();
            }

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
        if ($this->studentService->hasClass($request->input('class_name'))) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('Class already exists.'),
                    'errors' => ['class_name' => [__('Class already exists.')]],
                ], 422);
            }
            return back()->withErrors(['class_name' => __('Class already exists.')]);
        }

        $this->studentService->addClass($request->input('class_name'));

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Class added.'),
            ]);
        }

        return back()->with('success', __('Class added.'));
    }
}
