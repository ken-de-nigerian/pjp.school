<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Contracts\NotificationServiceContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Teacher\Concerns\TeacherScope;
use App\Http\Requests\EditAttendanceRequest;
use App\Models\Setting;
use App\Models\Student;
use App\Services\AttendanceService;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

final class AttendanceController extends Controller
{
    use TeacherScope;

    public function __construct(
        private readonly AttendanceService $attendanceService,
        private readonly NotificationServiceContract $notificationService,
        private readonly StudentService $studentService
    ) {}

    public function index(): View
    {
        $this->authorizeTeacherAbility('manageAttendance');

        $settings = Setting::getCached();
        $assigned = $this->teacherAssignedClasses();

        $allWithCounts = $this->studentService->getClassesWithCounts();
        $classes = array_values(array_filter($allWithCounts, fn (array $c) => in_array($c['class_name'] ?? '', $assigned, true)));

        return view('teacher.attendance.index', [
            'classes' => $classes,
            'settings' => $settings,
        ]);
    }

    public function takeAttendance(Request $request): View
    {
        $this->authorizeTeacherAbility('manageAttendance');

        $validated = $request->validate([
            'class' => 'required|string|max:100',
            'term' => 'required|string|max:50',
            'session' => 'required|string|max:50',
        ]);

        $class = $validated['class'];
        $this->ensureTeacherCanAccessClass($class);

        $students = $this->studentService
            ->getStudentsByClassAll($class)
            ->where('status', 2)
            ->values();

        return view('teacher.attendance.take-attendance', [
            'class' => $class,
            'term' => $validated['term'],
            'session' => $validated['session'],
            'students' => $students,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function save(Request $request): JsonResponse
    {
        $this->authorizeTeacherAbility('manageAttendance');

        $request->validate([
            'attendance' => 'required|array|min:1',
            'attendance.*.class' => 'required|string|max:100',
            'attendance.*.term' => 'required|string|max:50',
            'attendance.*.session' => 'required|string|max:50',
            'attendance.*.reg_number' => 'required|string|max:50',
            'attendance.*.name' => 'nullable|string|max:255',
            'attendance.*.class_roll_call' => 'required|string|max:20',
        ]);

        /** @var array<int, array<string, mixed>> $rows */
        $rows = (array) $request->input('attendance', []);
        $first = $rows[0] ?? [];
        $class = (string) ($first['class'] ?? '');
        $term = (string) ($first['term'] ?? '');
        $session = (string) ($first['session'] ?? '');

        $this->ensureTeacherCanAccessClass($class);

        // Hard-scope to the teacher's students in this class (reg_number).
        $allowedRegs = $this->studentService
            ->getStudentsByClassAll($class)
            ->where('status', 2)
            ->pluck('reg_number')
            ->filter()
            ->values()
            ->all();
        $allowed = array_flip(array_map('strval', $allowedRegs));

        $scopedRows = array_values(array_filter($rows, static function (array $r) use ($class, $term, $session, $allowed): bool {
            $reg = (string) ($r['reg_number'] ?? '');

            return $reg !== ''
                && isset($allowed[$reg])
                && (string) ($r['class'] ?? '') === $class
                && (string) ($r['term'] ?? '') === $term
                && (string) ($r['session'] ?? '') === $session;
        }));

        $count = $this->attendanceService->saveRecord($scopedRows);

        if ($count > 0) {
            $teacher = $request->user('teacher');
            $teacherName = $teacher ? trim($teacher->firstname.' '.$teacher->lastname) : 'Teacher';
            if ($teacherName === '') {
                $teacherName = 'Teacher';
            }

            $this->notificationService->add(
                'Attendance Record Added',
                $teacherName.' has added attendance record for class: '.$class.', term: '.$term.', session: '.$session.'.'
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => $count.' attendance record(s) saved.',
        ]);
    }

    public function edit(EditAttendanceRequest $request): JsonResponse
    {
        $this->authorizeTeacherAbility('manageAttendance');

        $v = $request->validated();
        $this->ensureTeacherCanAccessClass($v['class']);

        $allowedRegs = $this->studentService
            ->getStudentsByClassAll($v['class'])
            ->where('status', 2)
            ->pluck('reg_number')
            ->map(static fn ($r) => (string) $r)
            ->filter()
            ->flip()
            ->all();

        $filtered = array_values(array_filter(
            $v['updates'],
            static fn (array $u): bool => isset($allowedRegs[(string) ($u['reg_number'] ?? '')])
        ));

        if ($filtered === []) {
            return response()->json([
                'status' => 'error',
                'message' => 'No permitted changes to save for your assigned students.',
            ], 422);
        }

        $segment = config('school.no_segment', 'No Segment');
        $updated = $this->attendanceService->editRecord(
            $v['class'],
            $v['term'],
            $v['session'],
            $segment,
            $v['date'],
            $filtered
        );

        if ($updated > 0) {
            $teacher = $request->user('teacher');
            $teacherName = $teacher ? trim($teacher->firstname.' '.$teacher->lastname) : 'Teacher';
            if ($teacherName === '') {
                $teacherName = 'Teacher';
            }
            $this->notificationService->add(
                'Attendance Records Edited',
                $teacherName.' has edited '.$updated.' attendance record(s) for class: '.$v['class']
                    .', term: '.$v['term']
                    .', session: '.$v['session']
            );
        }

        return response()->json([
            'status' => $updated > 0 ? 'success' : 'error',
            'message' => $updated > 0
                ? $updated.' attendance record(s) updated successfully.'
                : 'No changes were made to the attendance records.',
        ]);
    }

    public function viewAttendance(Request $request): View
    {
        $this->authorizeTeacherAbility('manageAttendance');

        $settings = Setting::getCached();
        $classes = $this->teacherAssignedClasses();

        $date = trim((string) $request->query('date', date('Y-m-d')));
        $class = trim((string) $request->query('class', ''));
        $term = trim((string) $request->query('term', $settings['term'] ?? 'First Term'));
        $session = trim((string) $request->query('session', $settings['session'] ?? ''));
        $hasFilters = $date !== '' && $class !== '' && $term !== '' && $session !== '';

        if ($hasFilters) {
            $validated = $request->validate([
                'date' => 'required|string|max:50',
                'class' => 'required|string|max:100',
                'term' => 'required|string|max:50',
                'session' => 'required|string|max:50',
            ]);
            $date = $validated['date'];
            $class = $validated['class'];
            $term = $validated['term'];
            $session = $validated['session'];

            $this->ensureTeacherCanAccessClass($class);

            $segment = config('school.no_segment', 'No Segment');
            $records = $this->attendanceService->getRecord($date, $class, $term, $session, $segment);
            $regNumbers = $records->pluck('reg_number')->unique()->filter()->values();
            $studentsByReg = $regNumbers->isNotEmpty()
                ? Student::query()->whereIn('reg_number', $regNumbers->all())->get()->keyBy('reg_number')
                : collect();
        } else {
            $records = collect();
            $studentsByReg = collect();
        }

        return view('teacher.attendance.view-attendance', [
            'classes' => $classes,
            'settings' => $settings,
            'hasFilters' => $hasFilters,
            'students' => $records,
            'studentsByReg' => $studentsByReg,
            'date' => $date,
            'class' => $class,
            'term' => $term,
            'session' => $session,
        ]);
    }
}
