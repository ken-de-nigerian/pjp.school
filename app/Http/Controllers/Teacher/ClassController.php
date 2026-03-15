<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassController extends Controller
{
    public function __construct(
        private StudentService $studentService
    ) {}

    /** GET teacher/class — class list. */
    public function index(): View
    {
        $classList = $this->studentService->getClassesWithCounts();

        return view('teacher.class.index', [
            'classList' => $classList,
        ]);
    }

    /** GET teacher/class/find-students?class=&search= — students in class, optional search. */
    public function findStudents(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $class = $request->query('class');
        $search = $request->query('search');

        if (! $class && ! $search) {
            return redirect()->route('teacher.class.index')->with('error', 'Missing class or search.');
        }

        if ($search !== null && $search !== '' && $class) {
            $searchResult = $this->studentService->search($search, $class);
            $students = $searchResult['results'];
            $totalItems = $searchResult['count'];
        } elseif ($class) {
            $paginator = $this->studentService->getStudentsByClass($class, 50);
            $students = $paginator->items();
            $totalItems = $paginator->total();
        } else {
            $students = [];
            $totalItems = 0;
        }

        return view('teacher.class.find-students', [
            'students' => $students,
            'totalItems' => $totalItems,
            'class' => $class,
            'search' => $search,
            'getClasses' => $this->studentService->getClassesArray(),
        ]);
    }
}
