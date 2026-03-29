<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Contracts\NotificationServiceContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditBehavioralRequest;
use App\Http\Requests\StoreBehavioralRequest;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Teacher;
use App\Services\BehavioralService;
use App\Services\StudentService;
use App\Support\Coercion;
use App\Traits\TeacherScope;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

final class BehavioralController extends Controller
{
    use TeacherScope;

    public function __construct(
        private readonly BehavioralService $behavioralService,
        private readonly NotificationServiceContract $notificationService,
        private readonly StudentService $studentService
    ) {}

    public function index(): View
    {
        $this->authorizeTeacherAbility('manageBehavioral');

        $settings = Setting::getCached();
        $assigned = $this->teacherAssignedClasses();
        $allWithCounts = $this->studentService->getClassesWithCounts();
        $classes = array_values(array_filter($allWithCounts, fn (array $c) => in_array($c['class_name'] ?? '', $assigned, true)));

        return view('teacher.behavioral.index', [
            'classes' => $classes,
            'settings' => $settings,
        ]);
    }

    public function takeBehavioral(Request $request): View
    {
        $this->authorizeTeacherAbility('manageBehavioral');

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

        return view('teacher.behavioral.take-behavioral', [
            'class' => $class,
            'term' => $cts['term'],
            'session' => $cts['session'],
            'students' => $students,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function save(StoreBehavioralRequest $request): JsonResponse
    {
        $this->authorizeTeacherAbility('manageBehavioral');

        $students = $request->behavioralRows();
        $first = $students[0] ?? [];
        $class = Coercion::string($first['class'] ?? '');
        $term = Coercion::string($first['term'] ?? '');
        $session = Coercion::string($first['session'] ?? '');

        $this->ensureTeacherCanAccessClass($class);

        if ($this->behavioralService->hasBehavioralAnalysis($class, $term, $session)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Behavioral analysis for '.$class.' in term '.$term.' and session '.$session.' already exists',
            ]);
        }

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
        foreach ($students as $r) {
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

        $count = $this->behavioralService->bulkInsert($scopedRows);
        if ($count !== count($scopedRows)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding behavioral analysis for '.$class.'. Please try again later.',
            ]);
        }

        $teacherName = $this->teacherDisplayName($request);

        $this->notificationService->add(
            'Behavioral Record Added',
            $teacherName.' has added behavioral record for class: '.$class.' , '.$term.' , '.$session.' Session.'
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Behavioral analysis for '.$class.' has been added successfully.',
        ]);
    }

    public function edit(EditBehavioralRequest $request): JsonResponse
    {
        $this->authorizeTeacherAbility('manageBehavioral');

        $v = $request->editPayload();
        $this->ensureTeacherCanAccessClass($v['class']);

        $allowedRegs = $this->studentService
            ->getStudentsByClassAll($v['class'])
            ->where('status', 2)
            ->pluck('reg_number')
            ->map(static fn (mixed $r): string => Coercion::string($r))
            ->filter()
            ->flip()
            ->all();

        if (! isset($allowedRegs[$v['reg_number']])) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot edit behavioural records for this student.',
            ], 403);
        }

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
            $teacherName = $this->teacherDisplayName($request);
            $this->notificationService->add(
                'Behavioral Record Edited',
                $teacherName.' has edited a behavioural record for class: '.$v['class'].', term: '.$v['term'].', session: '.$v['session'].'.'
            );

            return response()->json([
                'status' => 'success',
                'message' => 'This student\'s behavioural record has been updated successfully.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No changes were made to this student\'s behavioural record.',
        ]);
    }

    public function viewBehavioral(Request $request): View
    {
        $this->authorizeTeacherAbility('manageBehavioral');

        $settings = Setting::getCached();
        $classes = $this->teacherAssignedClasses();

        $class = trim(Coercion::string($request->query('class', '')));
        $term = trim(Coercion::string($request->query('term', Coercion::string($settings['term'] ?? 'First Term'))));
        $session = trim(Coercion::string($request->query('session', Coercion::string($settings['session'] ?? ''))));
        $hasFilters = $class !== '' && $term !== '' && $session !== '';

        if ($hasFilters) {
            $validated = Coercion::stringKeyedMap($request->validate([
                'class' => 'required|string|max:100',
                'term' => 'required|string|max:50',
                'session' => 'required|string|max:50',
            ]));
            $cts = Coercion::classTermSessionFromValidated($validated);
            $class = $cts['class'];
            $term = $cts['term'];
            $session = $cts['session'];

            $this->ensureTeacherCanAccessClass($class);

            $records = $this->behavioralService->getRecord($class, $term, $session);
            $regNumbers = $records->pluck('reg_number')->unique()->filter()->values();
            $studentsByReg = $regNumbers->isNotEmpty()
                ? Student::query()->whereIn('reg_number', $regNumbers->all())->get()->keyBy('reg_number')
                : collect();
        } else {
            $records = collect();
            $studentsByReg = collect();
        }

        return view('teacher.behavioral.view-behavioral', [
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
