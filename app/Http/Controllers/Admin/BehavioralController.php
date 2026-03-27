<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditBehavioralRequest;
use App\Http\Requests\StoreBehavioralRequest;
use App\Models\Notification;
use App\Models\SchoolClass;
use App\Models\Setting;
use App\Models\Student;
use App\Services\BehavioralService;
use App\Services\StudentService;
use App\Traits\AuthorizesAdminPermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

final class BehavioralController extends Controller
{
    use AuthorizesAdminPermission;

    public function __construct(
        private readonly BehavioralService $behavioralService,
        private readonly StudentService $studentService
    ) {}

    public function index(): View
    {
        $this->authorizePermission('behavioural_analysis');
        $settings = Setting::getCached();
        $classesWithCounts = $this->studentService->getClassesWithCounts();

        return view('admin.behavioral.index', [
            'classes' => $classesWithCounts,
            'settings' => $settings,
        ]);
    }

    public function takeBehavioral(Request $request): View
    {
        $this->authorizePermission('behavioural_analysis');
        $validated = $request->validate([
            'class' => 'required',
            'term' => 'required',
            'session' => 'required',
        ]);

        $students = SchoolClass::query()->where([
            'class_name' => $validated['class'],
        ])->with('students')->get();

        return view('admin.behavioral.take-behavioral', [
            'class' => $validated['class'],
            'term' => $validated['term'],
            'session' => $validated['session'],
            'students' => $students,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function save(StoreBehavioralRequest $request): JsonResponse
    {
        $this->authorizePermission('behavioural_analysis');
        $students = $request->input('students');

        $class = $students[0]['class'] ?? '';
        $term = $students[0]['term'] ?? '';
        $session = $students[0]['session'] ?? '';

        if ($this->behavioralService->hasBehavioralAnalysis($class, $term, $session)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Behavioral analysis for '.$class.' in term '.$term.' and session '.$session.' already exists',
            ]);
        }

        $count = $this->behavioralService->bulkInsert($students);
        if ($count !== count($students)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding behavioral analysis for '.$class.'. Please try again later.',
            ]);
        }

        $admin = $request->user('admin');
        $adminName = $admin ? $admin->name : 'Admin';

        Notification::query()->create([
            'title' => 'Behavioral Record Added',
            'message' => $adminName.' has added behavioral record for class: '.$class.' , '.$term.' , '.$session.' Session.',
            'date_added' => now()->format('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Behavioral analysis for '.$class.' has been added successfully.',
        ]);
    }

    public function viewBehavioral(Request $request): View
    {
        $this->authorizePermission('view_uploaded_behavioural_analysis');
        $settings = Setting::getCached();
        $classes = SchoolClass::query()->orderBy('class_name')->get();
        $class = trim((string) $request->query('class', ''));
        $term = trim((string) $request->query('term', $settings['term'] ?? 'First Term'));
        $session = trim((string) $request->query('session', $settings['session'] ?? ''));
        $hasFilters = $class !== '' && $term !== '' && $session !== '';

        if ($hasFilters) {
            $validated = $request->validate([
                'class' => 'required|string|max:100',
                'term' => 'required|string|max:50',
                'session' => 'required|string|max:50',
            ]);
            $class = $validated['class'];
            $term = $validated['term'];
            $session = $validated['session'];
            $records = $this->behavioralService->getRecord($class, $term, $session);
            $regNumbers = $records->pluck('reg_number')->unique()->filter()->values();
            $studentsByReg = $regNumbers->isNotEmpty()
                ? Student::query()->whereIn('reg_number', $regNumbers->all())->get()->keyBy('reg_number')
                : collect();
        } else {
            $records = collect();
            $studentsByReg = collect();
        }

        return view('admin.behavioral.view-behavioral', [
            'classes' => $classes,
            'settings' => $settings,
            'hasFilters' => $hasFilters,
            'students' => $records,
            'studentsByReg' => $studentsByReg,
            'class' => $class,
            'term' => $term,
            'session' => $session,
        ]);
    }

    public function getRecord(Request $request): View|JsonResponse
    {
        $this->authorizePermission('view_uploaded_behavioural_analysis');
        $validated = $request->validate([
            'class' => 'required|string|max:100',
            'term' => 'required|string|max:50',
            'session' => 'required|string|max:50',
        ]);

        $records = $this->behavioralService->getRecord(
            $validated['class'],
            $validated['term'],
            $validated['session']
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

        return view('admin.behavioral.view-behavioral', [
            'classes' => $classes,
            'settings' => $settings,
            'hasFilters' => true,
            'students' => $records,
            'studentsByReg' => $studentsByReg,
            'class' => $validated['class'],
            'term' => $validated['term'],
            'session' => $validated['session'],
        ]);
    }

    public function edit(EditBehavioralRequest $request): JsonResponse
    {
        $this->authorizePermission('view_uploaded_behavioural_analysis');
        $v = $request->validated();
        $updated = $this->behavioralService->editRecord(
            $v['reg_number'],
            $v['class'],
            $v['term'],
            $v['session'],
            $v['neatness'],
            $v['music'],
            $v['sports'],
            $v['attentiveness'],
            $v['punctuality'],
            $v['health'],
            $v['politeness']
        );

        if ($updated === 1) {
            $admin = $request->user('admin');
            $adminName = $admin ? $admin->name : 'Admin';
            Notification::query()->create([
                'title' => 'Behavioral Record Edited',
                'message' => $adminName.' has edited the behavioral record for class: '.$v['class'].', term: '.$v['term'].', session: '.$v['session'],
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'This student\'s behavioral record has been updated successfully.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No changes was made to this student\'s behavioral record',
        ]);
    }

    public function destroyByRequest(Request $request): JsonResponse
    {
        $this->authorizePermission('view_uploaded_behavioural_analysis');
        $class = $request->input('class');
        $term = $request->input('term');
        $session = $request->input('session');
        if (empty($class) || empty($term) || empty($session)) {
            return response()->json(['status' => 'error', 'message' => 'Missing parameters.'], 422);
        }
        $classDecoded = urldecode((string) $class);
        $termDecoded = urldecode((string) $term);
        $sessionDecoded = urldecode((string) $session);
        $deleted = $this->behavioralService->deleteRecord($classDecoded, $termDecoded, $sessionDecoded);
        if ($deleted > 0) {
            $admin = $request->user('admin');
            if ($admin) {
                Notification::query()->create([
                    'title' => 'Behavioral Record Deleted',
                    'message' => $admin->name.' has deleted a behavioral record.',
                    'date_added' => now()->format('Y-m-d H:i:s'),
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Behavioral record has been deleted successfully.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unable to delete the record. Please try again.',
        ]);
    }

    public function destroyOne(Request $request): JsonResponse
    {
        $this->authorizePermission('view_uploaded_behavioural_analysis');
        $validated = $request->validate([
            'reg_number' => 'required|string|max:50',
            'class' => 'required|string|max:100',
            'term' => 'required|string|max:50',
            'session' => 'required|string|max:50',
        ]);

        $deleted = $this->behavioralService->deleteOneRecord(
            $validated['reg_number'],
            $validated['class'],
            $validated['term'],
            $validated['session']
        );

        if ($deleted > 0) {
            $admin = $request->user('admin');
            if ($admin) {
                Notification::query()->create([
                    'title' => 'Behavioral Record Deleted',
                    'message' => $admin->name.' has deleted a student\'s behavioural record.',
                    'date_added' => now()->format('Y-m-d H:i:s'),
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Behavioural record has been deleted.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Record not found or could not be deleted.',
        ]);
    }
}
