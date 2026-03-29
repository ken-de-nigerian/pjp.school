<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Contracts\NotificationServiceContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditAttendanceRequest;
use App\Http\Requests\StoreAttendanceRequest;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Teacher;
use App\Services\AttendanceService;
use App\Services\StudentService;
use App\Support\Coercion;
use App\Traits\TeacherScope;
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

        $validated = Coercion::stringKeyedMap($request->validate([
            'class' => 'required|string|max:100',
            'term' => 'required|string|max:50',
            'session' => 'required|string|max:50',
        ]));
        $cts = Coercion::classTermSessionFromValidated($validated);
        $class = $cts['class'];
        $this->ensureTeacherCanAccessClass($class);

        $students = $this->studentService
            ->getStudentsByClassAll($class)
            ->where('status', 2)
            ->values();

        return view('teacher.attendance.take-attendance', [
            'class' => $class,
            'term' => $cts['term'],
            'session' => $cts['session'],
            'students' => $students,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function save(StoreAttendanceRequest $request): JsonResponse
    {
        $this->authorizeTeacherAbility('manageAttendance');

        $rows = $request->attendanceRows();
        $first = $rows[0] ?? [];
        $class = Coercion::string($first['class'] ?? '');
        $term = Coercion::string($first['term'] ?? '');
        $session = Coercion::string($first['session'] ?? '');

        $this->ensureTeacherCanAccessClass($class);

        $allowedRegs = $this->studentService
            ->getStudentsByClassAll($class)
            ->where('status', 2)
            ->pluck('reg_number')
            ->filter()
            ->values()
            ->all();
        $allowed = [];
        foreach ($allowedRegs as $reg) {
            $s = Coercion::string($reg);
            if ($s !== '') {
                $allowed[$s] = true;
            }
        }

        $scopedRows = [];
        foreach ($rows as $r) {
            $reg = Coercion::string($r['reg_number'] ?? '');
            if ($reg === '' || ! isset($allowed[$reg])) {
                continue;
            }
            if (Coercion::string($r['class'] ?? '') !== $class
                || Coercion::string($r['term'] ?? '') !== $term
                || Coercion::string($r['session'] ?? '') !== $session) {
                continue;
            }
            $scopedRows[] = $r;
        }

        $count = $this->attendanceService->saveRecord($scopedRows);

        if ($count > 0) {
            $teacherName = $this->teacherDisplayName($request);
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

        $ctx = $request->attendanceContext();
        $this->ensureTeacherCanAccessClass($ctx['class']);

        $allowedRegs = $this->studentService
            ->getStudentsByClassAll($ctx['class'])
            ->where('status', 2)
            ->pluck('reg_number')
            ->map(static fn (mixed $r): string => Coercion::string($r))
            ->filter()
            ->flip()
            ->all();

        $filtered = [];
        foreach ($request->attendanceUpdates() as $u) {
            if (isset($allowedRegs[$u['reg_number']])) {
                $filtered[] = $u;
            }
        }

        if ($filtered === []) {
            return response()->json([
                'status' => 'error',
                'message' => 'No permitted changes to save for your assigned students.',
            ], 422);
        }

        $updated = $this->attendanceService->editRecord(
            $ctx['class'],
            $ctx['term'],
            $ctx['session'],
            $ctx['date'],
            $filtered
        );

        if ($updated > 0) {
            $teacherName = $this->teacherDisplayName($request);
            $this->notificationService->add(
                'Attendance Records Edited',
                $teacherName.' has edited '.$updated.' attendance record(s) for class: '.$ctx['class']
                    .', term: '.$ctx['term']
                    .', session: '.$ctx['session']
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

        $date = trim(Coercion::string($request->query('date', date('Y-m-d'))));
        $class = trim(Coercion::string($request->query('class', '')));
        $term = trim(Coercion::string($request->query('term', Coercion::string($settings['term'] ?? 'First Term'))));
        $session = trim(Coercion::string($request->query('session', Coercion::string($settings['session'] ?? ''))));
        $hasFilters = $date !== '' && $class !== '' && $term !== '' && $session !== '';

        if ($hasFilters) {
            $validated = Coercion::stringKeyedMap($request->validate([
                'date' => 'required|string|max:50',
                'class' => 'required|string|max:100',
                'term' => 'required|string|max:50',
                'session' => 'required|string|max:50',
            ]));
            $date = Coercion::string($validated['date'] ?? '');
            $cts = Coercion::classTermSessionFromValidated($validated);
            $class = $cts['class'];
            $term = $cts['term'];
            $session = $cts['session'];

            $this->ensureTeacherCanAccessClass($class);

            $records = $this->attendanceService->getRecord($date, $class, $term, $session);
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

    private function teacherDisplayName(Request $request): string
    {
        $teacher = $request->user('teacher');
        if (! $teacher instanceof Teacher) {
            return 'Teacher';
        }

        $teacherName = trim(Coercion::string($teacher->firstname).' '.Coercion::string($teacher->lastname));

        return $teacherName !== '' ? $teacherName : 'Teacher';
    }
}
