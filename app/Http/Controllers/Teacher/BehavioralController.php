<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Contracts\NotificationServiceContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Teacher\Concerns\TeacherScope;
use App\Models\Setting;
use App\Models\Student;
use App\Services\BehavioralService;
use App\Services\StudentService;
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

        return view('teacher.behavioral.take-behavioral', [
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
        $this->authorizeTeacherAbility('manageBehavioral');

        $request->validate([
            'students' => 'required|array|min:1',
            'students.*.class' => 'required|string|max:100',
            'students.*.term' => 'required|string|max:50',
            'students.*.session' => 'required|string|max:50',
            'students.*.reg_number' => 'required|string|max:50',
            'students.*.name' => 'nullable|string|max:255',
            'students.*.neatness' => 'nullable|string|max:255',
            'students.*.music' => 'nullable|string|max:255',
            'students.*.sports' => 'nullable|string|max:255',
            'students.*.attentiveness' => 'nullable|string|max:255',
            'students.*.punctuality' => 'nullable|string|max:255',
            'students.*.health' => 'nullable|string|max:255',
            'students.*.politeness' => 'nullable|string|max:255',
        ]);

        /** @var array<int, array<string, mixed>> $students */
        $students = (array) $request->input('students', []);
        $first = $students[0] ?? [];
        $class = (string) ($first['class'] ?? '');
        $term = (string) ($first['term'] ?? '');
        $session = (string) ($first['session'] ?? '');
        $segment = config('school.no_segment', 'No Segment');

        $this->ensureTeacherCanAccessClass($class);

        if ($this->behavioralService->hasBehavioralAnalysis($class, $term, $session, $segment)) {
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
        $allowed = array_flip(array_map('strval', $allowedRegs));

        $scopedRows = array_values(array_filter($students, static function (array $r) use ($class, $term, $session, $allowed): bool {
            $reg = (string) ($r['reg_number'] ?? '');

            return $reg !== ''
                && isset($allowed[$reg])
                && (string) ($r['class'] ?? '') === $class
                && (string) ($r['term'] ?? '') === $term
                && (string) ($r['session'] ?? '') === $session;
        }));

        $count = $this->behavioralService->bulkInsert($scopedRows);
        if ($count !== count($scopedRows)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding behavioral analysis for '.$class.'. Please try again later.',
            ]);
        }

        $teacher = $request->user('teacher');
        $teacherName = $teacher ? trim($teacher->firstname.' '.$teacher->lastname) : 'Teacher';
        if ($teacherName === '') {
            $teacherName = 'Teacher';
        }

        $this->notificationService->add(
            'Behavioral Record Added',
            $teacherName.' has added behavioral record for class: '.$class.' , '.$term.' , '.$session.' Session.'
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Behavioral analysis for '.$class.' has been added successfully.',
        ]);
    }

    public function viewBehavioral(Request $request): View
    {
        $this->authorizeTeacherAbility('manageBehavioral');

        $settings = Setting::getCached();
        $classes = $this->teacherAssignedClasses();

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

            $this->ensureTeacherCanAccessClass($class);

            $segment = config('school.no_segment', 'No Segment');
            $records = $this->behavioralService->getRecord($class, $term, $session, $segment);
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
}
