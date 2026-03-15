<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectsController extends Controller
{
    public function __construct(
        private StudentService $studentService
    ) {}

    /** GET teacher/subjects — form to select class and subject. */
    public function index(): View
    {
        $getClasses = $this->studentService->getClassesArray();
        $subjects = Subject::query()->orderBy('subject_name')->get();

        return view('teacher.subjects.index', [
            'getClasses' => $getClasses,
            'subjects' => $subjects,
        ]);
    }

    /** GET teacher/subjects/registered?class=&subjects= — students in class registered for subject. */
    public function registered(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $class = $request->query('class');
        $subjects = $request->query('subjects');

        if (! $class && ! $subjects) {
            return redirect()->route('teacher.subjects.index')->with('error', 'Select class and subject.');
        }

        $students = $this->studentService->getStudentsByClassAndSubject($class ?? '', $subjects ?? '');

        return view('teacher.subjects.registered', [
            'students' => $students,
            'class' => $class,
            'subjects' => $subjects,
            'getClasses' => $this->studentService->getClassesArray(),
        ]);
    }
}
