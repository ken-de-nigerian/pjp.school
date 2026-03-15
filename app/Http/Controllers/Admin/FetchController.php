<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FetchController extends Controller
{
    public function __construct(
        private StudentService $studentService
    ) {}

    /** GET admin/fetch?class_name= — students by class (JSON). Legacy AJAX. */
    public function fetch(Request $request): JsonResponse
    {
        $class = $request->query('class_name');
        if ($class === null || $class === '') {
            return response()->json(['students' => []]);
        }
        $students = $this->studentService->getStudentsByClass($class, 500);
        $data = $students->getCollection()->map(fn ($s) => [
            'id' => $s->id,
            'reg_number' => $s->reg_number,
            'firstname' => $s->firstname,
            'lastname' => $s->lastname,
            'class' => $s->class,
        ])->values()->all();

        return response()->json(['students' => $data]);
    }

    /** GET admin/fetch_teacher_details?teacher_id= — teacher details (JSON). */
    public function fetchTeacherDetails(Request $request): JsonResponse
    {
        $teacherId = $request->query('teacher_id');
        if ($teacherId === null || $teacherId === '') {
            return response()->json(['teacher' => null]);
        }
        $teacher = Teacher::query()->where('userId', $teacherId)->first();
        if (! $teacher) {
            return response()->json(['teacher' => null]);
        }
        $teacher->makeHidden(['password']);
        return response()->json(['teacher' => $teacher->toArray()]);
    }

    /** GET admin/fetch_students_details?student_id= — student details (JSON). */
    public function fetchStudentDetails(Request $request): JsonResponse
    {
        $studentId = $request->query('student_id');
        if ($studentId === null || $studentId === '') {
            return response()->json(['student' => null]);
        }
        $student = Student::query()->find($studentId);
        if (! $student) {
            return response()->json(['student' => null]);
        }
        return response()->json(['student' => $student->toArray()]);
    }

    /** GET admin/subjectToRegister?student_class= or ?class= — subjects for class (JSON). Legacy AJAX. */
    public function subjectToRegister(Request $request): JsonResponse
    {
        $class = $request->query('student_class') ?? $request->query('class');
        if ($class === null || $class === '') {
            return response()->json(['subjects' => []]);
        }
        $subjects = $this->studentService->getSubjectsToRegister($class);

        return response()->json([
            'subjects' => $subjects->map(fn ($s) => ['id' => $s->id, 'subject_name' => $s->subject_name])->values()->all(),
        ]);
    }
}
