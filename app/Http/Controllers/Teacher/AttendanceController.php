<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\AttendanceService;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(
        private StudentService $studentService,
        private AttendanceService $attendanceService
    ) {}

    /** GET teacher/attendance — class list to take or view attendance. */
    public function index(): View
    {
        $classList = $this->studentService->getClassesWithCounts();
        $settings = Setting::getCached();
        $sessions = \App\Models\AcademicSession::query()->orderByDesc('session')->get();

        return view('teacher.attendance.index', [
            'classList' => $classList,
            'settings' => $settings,
            'sessions' => $sessions,
        ]);
    }

    /** GET teacher/attendance/take-attendance?class=&term=&session= — form to take attendance. */
    public function takeAttendance(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $class = $request->query('class');
        $term = $request->query('term');
        $session = $request->query('session');

        if (! $class || ! $term || ! $session) {
            return redirect()->route('teacher.attendance.index')->with('error', 'Missing class, term or session.');
        }

        $students = $this->studentService->getStudentsByClass($class, 500);
        $students = $students->items();

        return view('teacher.attendance.take-attendance', [
            'students' => $students,
            'class' => $class,
            'term' => $term,
            'session' => $session,
            'settings' => Setting::getCached(),
        ]);
    }

    /** GET teacher/attendance/view-attendance — form to view by date/class/term/session. */
    public function viewAttendance(Request $request): View
    {
        $date = $request->query('date');
        $class = $request->query('class');
        $term = $request->query('term');
        $session = $request->query('session');

        if ($date && $class && $term && $session) {
            $segment = config('school.no_segment', 'No Segment');
            $records = $this->attendanceService->getRecord($date, $class, $term, $session, $segment);
            return view('teacher.attendance.view-attendance', [
                'students' => $records,
                'date' => $date,
                'class' => $class,
                'term' => $term,
                'session' => $session,
                'classList' => $this->studentService->getClassesWithCounts(),
                'settings' => Setting::getCached(),
            ]);
        }

        return view('teacher.attendance.view-attendance', [
            'students' => collect(),
            'classList' => $this->studentService->getClassesWithCounts(),
            'settings' => Setting::getCached(),
        ]);
    }
}
