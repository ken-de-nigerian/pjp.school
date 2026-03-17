<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteAttendanceRequest;
use App\Http\Requests\EditAttendanceRequest;
use App\Http\Requests\StoreAttendanceRequest;
use App\Models\SchoolClass;
use App\Models\Setting;
use App\Services\AttendanceService;
use App\Services\NotificationService;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(
        private readonly AttendanceService $attendanceService,
        private readonly NotificationService $notificationService,
        private readonly StudentService $studentService
    ) {}

    public function index(): View
    {
        $settings = Setting::getCached();
        $classesWithCounts = $this->studentService->getClassesWithCounts();

        return view('admin.attendance.index', [
            'classes' => $classesWithCounts,
            'settings' => $settings
        ]);
    }

    public function takeAttendance(Request $request): View
    {
        $validated = $request->validate([
            'class' => 'required',
            'term' => 'required',
            'session' => 'required',
        ]);

        $students = SchoolClass::query()->where([
            'class_name' => $validated['class'],
        ])->with('students')->get();

        return view('admin.attendance.take-attendance', [
            'class' => $validated['class'],
            'term' => $validated['term'],
            'session' => $validated['session'],
            'students' => $students,
        ]);
    }

    public function save(StoreAttendanceRequest $request): JsonResponse
    {
        $count = $this->attendanceService
            ->saveRecord($request->validated('attendance'));

        if ($count > 0) {

            $adminName = $request->user('admin')->name ?? 'Admin';
            $first = $request->validated('attendance')[0] ?? [];
            $class = $first['class'] ?? '';
            $term = $first['term'] ?? '';
            $session = $first['session'] ?? '';

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
        $settings = Setting::getCached();
        $classes = SchoolClass::query()->orderBy('class_name')->get();
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
            $segment = config('school.no_segment', 'No Segment');
            $records = $this->attendanceService->getRecord($date, $class, $term, $session, $segment);
        } else {
            $records = collect();
        }

        return view('admin.attendance.view-attendance', [
            'classes' => $classes,
            'settings' => $settings,
            'hasFilters' => $hasFilters,
            'students' => $records,
            'date' => $date,
            'class' => $class,
            'term' => $term,
            'session' => $session,
        ]);
    }

    public function getRecord(Request $request): View|JsonResponse
    {
        $validated = $request->validate([
            'date' => 'required|string|max:50',
            'class' => 'required|string|max:100',
            'term' => 'required|string|max:50',
            'session' => 'required|string|max:50',
        ]);

        $segment = config('school.no_segment', 'No Segment');
        $records = $this->attendanceService->getRecord(
            $validated['date'],
            $validated['class'],
            $validated['term'],
            $validated['session'],
            $segment
        );

        if ($request->expectsJson()) {
            return response()->json($records->values()->all());
        }

        $settings = Setting::getCached();
        $classes = SchoolClass::query()->orderBy('class_name')->get();

        return view('admin.attendance.view-attendance', [
            'classes' => $classes,
            'settings' => $settings,
            'hasFilters' => true,
            'students' => $records,
            'date' => $validated['date'],
            'class' => $validated['class'],
            'term' => $validated['term'],
            'session' => $validated['session'],
        ]);
    }

    public function edit(EditAttendanceRequest $request): JsonResponse
    {
        $v = $request->validated();
        $segment = config('school.no_segment', 'No Segment');

        $updated = $this->attendanceService->editRecord(
            $v['class'],
            $v['term'],
            $v['session'],
            $segment,
            $v['date'],
            $v['updates']
        );

        if ($updated > 0) {
            $adminName = $request->user('admin')->name ?? 'Admin';
            $this->notificationService->add(
                'Attendance Records Edited',
                $adminName . ' has edited ' . $updated . ' attendance record(s) for class: ' . $v['class']
                . ', term: ' . $v['term']
                . ', session: ' . $v['session']
            );
        }

        return response()->json([
            'status' => $updated > 0 ? 'success' : 'error',
            'message' => $updated > 0
                ? $updated . " attendance record(s) updated successfully."
                : "No changes were made to the attendance records",
        ]);
    }

    public function destroy(DeleteAttendanceRequest $request): JsonResponse
    {
        $v = $request->validated();
        $regNumber = $v['reg_number'] ?? null;
        $segment = config('school.no_segment', 'No Segment');

        if ($regNumber !== null && $regNumber !== '') {
            $deleted = $this->attendanceService->deleteOneRecord(
                $regNumber,
                $v['class'],
                $v['term'],
                $v['session'],
                $segment,
                $v['date']
            );
            $message = $deleted > 0
                ? 'Attendance record has been deleted.'
                : 'Unable to delete the record. Please try again.';
        } else {
            $deleted = $this->attendanceService->deleteByClassTermSessionSegmentDate(
                $v['class'],
                $v['term'],
                $v['session'],
                $segment,
                $v['date']
            );
            if ($deleted > 0) {
                $adminName = $request->user('admin')->name ?? 'Admin';
                $this->notificationService->add(
                    'Attendance Record Deleted',
                    $adminName . ' has deleted attendance record for class: ' . $v['class']
                        . ' , ' . $v['term'] . ' , ' . $v['session'] . ' Session.'
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
