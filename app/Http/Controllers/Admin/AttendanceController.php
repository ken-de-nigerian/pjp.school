<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Contracts\NotificationServiceContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteAttendanceRequest;
use App\Http\Requests\EditAttendanceRequest;
use App\Http\Requests\StoreAttendanceRequest;
use App\Models\SchoolClass;
use App\Models\Setting;
use App\Models\Student;
use App\Services\AttendanceService;
use App\Services\StudentService;
use App\Support\Coercion;
use App\Traits\AuthorizesAdminPermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

final class AttendanceController extends Controller
{
    use AuthorizesAdminPermission;

    public function __construct(
        private readonly AttendanceService $attendanceService,
        private readonly NotificationServiceContract $notificationService,
        private readonly StudentService $studentService
    ) {}

    public function index(): View
    {
        $this->authorizePermission('attendance');
        $settings = Setting::getCached();
        $classesWithCounts = $this->studentService->getClassesWithCounts();

        return view('admin.attendance.index', [
            'classes' => $classesWithCounts,
            'settings' => $settings,
        ]);
    }

    public function takeAttendance(Request $request): View
    {
        $this->authorizePermission('attendance');
        $validated = Coercion::stringKeyedMap($request->validate([
            'class' => 'required',
            'term' => 'required',
            'session' => 'required',
        ]));
        $cts = Coercion::classTermSessionFromValidated($validated);

        $students = SchoolClass::query()->where([
            'class_name' => $cts['class'],
        ])->with('students')->get();

        return view('admin.attendance.take-attendance', [
            'class' => $cts['class'],
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
        $this->authorizePermission('attendance');
        $rows = $request->attendanceRows();
        $count = $this->attendanceService->saveRecord($rows);

        if ($count > 0) {

            $adminName = $request->user('admin')->name ?? 'Admin';
            $first = $rows[0] ?? [];
            $class = Coercion::string($first['class'] ?? '');
            $term = Coercion::string($first['term'] ?? '');
            $session = Coercion::string($first['session'] ?? '');

            $this->notificationService->add(
                'Attendance Record Added',
                "$adminName has added attendance record for class: $class, term: $term, session: $session."
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => "$count attendance record(s) saved.",
        ]);
    }

    public function viewAttendance(Request $request): View
    {
        $this->authorizePermission('view_uploaded_attendance');
        $settings = Setting::getCached();
        $classes = SchoolClass::query()->orderBy('class_name')->get();
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
            $ctx = Coercion::classTermSessionFromValidated($validated);
            $date = Coercion::string($validated['date'] ?? '');
            $class = $ctx['class'];
            $term = $ctx['term'];
            $session = $ctx['session'];
            $records = $this->attendanceService->getRecord($date, $class, $term, $session);
            $regNumbers = $records->pluck('reg_number')->unique()->filter()->values();
            $studentsByReg = $regNumbers->isNotEmpty()
                ? Student::query()->whereIn('reg_number', $regNumbers->all())->get()->keyBy('reg_number')
                : collect();
        } else {
            $records = collect();
            $studentsByReg = collect();
        }

        return view('admin.attendance.view-attendance', [
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

    public function getRecord(Request $request): View|JsonResponse
    {
        $this->authorizePermission('view_uploaded_attendance');
        $validated = Coercion::stringKeyedMap($request->validate([
            'date' => 'required|string|max:50',
            'class' => 'required|string|max:100',
            'term' => 'required|string|max:50',
            'session' => 'required|string|max:50',
        ]));
        $date = Coercion::string($validated['date'] ?? '');
        $cts = Coercion::classTermSessionFromValidated($validated);

        $records = $this->attendanceService->getRecord(
            $date,
            $cts['class'],
            $cts['term'],
            $cts['session']
        );

        if ($request->expectsJson()) {
            return response()->json($records->values()->all());
        }

        $settings = Setting::getCached();
        $classes = SchoolClass::query()->orderBy('class_name')->get();
        $regNumbers = $records->pluck('reg_number')->unique()->filter()->values();
        $studentsByReg = $regNumbers->isNotEmpty()
            ? Student::query()->whereIn('reg_number', $regNumbers->all())->get()->keyBy('reg_number')
            : collect();

        return view('admin.attendance.view-attendance', [
            'classes' => $classes,
            'settings' => $settings,
            'hasFilters' => true,
            'students' => $records,
            'studentsByReg' => $studentsByReg,
            'date' => $date,
            'class' => $cts['class'],
            'term' => $cts['term'],
            'session' => $cts['session'],
        ]);
    }

    public function edit(EditAttendanceRequest $request): JsonResponse
    {
        $this->authorizePermission('view_uploaded_attendance');
        $ctx = $request->attendanceContext();

        $updated = $this->attendanceService->editRecord(
            $ctx['class'],
            $ctx['term'],
            $ctx['session'],
            $ctx['date'],
            $request->attendanceUpdates()
        );

        if ($updated > 0) {
            $adminName = $request->user('admin')->name ?? 'Admin';
            $this->notificationService->add(
                'Attendance Records Edited',
                $adminName.' has edited '.$updated.' attendance record(s) for class: '.$ctx['class']
                .', term: '.$ctx['term']
                .', session: '.$ctx['session']
            );
        }

        return response()->json([
            'status' => $updated > 0 ? 'success' : 'error',
            'message' => $updated > 0
                ? $updated.' attendance record(s) updated successfully.'
                : 'No changes were made to the attendance records',
        ]);
    }

    public function destroy(DeleteAttendanceRequest $request): JsonResponse
    {
        $this->authorizePermission('view_uploaded_attendance');
        $ctx = $request->deleteContext();
        $regNumber = $ctx['reg_number'];

        if ($regNumber !== null && $regNumber !== '') {
            $deleted = $this->attendanceService->deleteOneRecord(
                $regNumber,
                $ctx['class'],
                $ctx['term'],
                $ctx['session'],
                $ctx['date']
            );
            $message = $deleted > 0
                ? 'Attendance record has been deleted.'
                : 'Unable to delete the record. Please try again.';
        } else {
            $deleted = $this->attendanceService->deleteByClassTermSessionSegmentDate(
                $ctx['class'],
                $ctx['term'],
                $ctx['session'],
                $ctx['date']
            );
            if ($deleted > 0) {
                $adminName = $request->user('admin')->name ?? 'Admin';
                $this->notificationService->add(
                    'Attendance Record Deleted',
                    $adminName.' has deleted attendance record for class: '.$ctx['class']
                        .' , '.$ctx['term'].' , '.$ctx['session'].' Session.'
                );
            }
            $message = $deleted > 0
                ? 'All attendance records for this date have been deleted.'
                : 'Unable to delete the records. Please try again.';
        }

        return response()->json([
            'status' => $deleted > 0 ? 'success' : 'error',
            'message' => $message,
        ]);
    }
}
