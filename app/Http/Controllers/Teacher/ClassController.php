<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Teacher\Concerns\TeacherScope;
use App\Models\Setting;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassController extends Controller
{
    use TeacherScope;

    public function __construct(
        private readonly StudentService $studentService
    ) {}

    public function index(Request $request): View
    {
        $assigned = $this->teacherAssignedClasses();
        $allWithCounts = $this->studentService->getClassesWithCounts();
        $classesWithCounts = array_values(array_filter($allWithCounts, fn (array $c) => in_array($c['class_name'] ?? '', $assigned, true)));

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

            return view('teacher.class.index', [
                'students' => $students,
                'classesWithCounts' => $classesWithCounts,
                'selectedClass' => $class,
                'searchQuery' => $search,
            ]);
        }

        return view('teacher.class.index', [
            'students' => null,
            'classesWithCounts' => $classesWithCounts,
            'selectedClass' => '',
            'searchQuery' => $request->query('q', ''),
        ]);
    }
}
