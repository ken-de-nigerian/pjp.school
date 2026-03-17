<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\BehavioralService;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BehavioralController extends Controller
{
    public function __construct(
        private StudentService $studentService,
        private BehavioralService $behavioralService
    ) {}

    /** GET teacher/behavioral — class list. */
    public function index(): View
    {
        $classList = $this->studentService->getClassesWithCounts();
        $settings = Setting::getCached();

        return view('teacher.behavioral.index', [
            'classList' => $classList,
            'settings' => $settings,
        ]);
    }

    /** GET teacher/behavioral/take-behavioral?class=&term=&session= — form. */
    public function takeBehavioral(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $class = $request->query('class');
        $term = $request->query('term');
        $session = $request->query('session');

        if (! $class || ! $term || ! $session) {
            return redirect()->route('teacher.behavioral.index')->with('error', 'Missing class, term or session.');
        }

        $students = $this->studentService->getStudentsByClass($class, 500)->items();

        return view('teacher.behavioral.take-behavioral', [
            'students' => $students,
            'class' => $class,
            'term' => $term,
            'session' => $session,
            'settings' => Setting::getCached(),
        ]);
    }

    /** GET teacher/behavioral/view-behavioral — form or record list. */
    public function viewBehavioral(Request $request): View
    {
        $class = $request->query('class');
        $term = $request->query('term');
        $session = $request->query('session');

        if ($class && $term && $session) {
            $segment = config('school.no_segment', 'No Segment');
            $records = $this->behavioralService->getRecord($class, $term, $session, $segment);
            return view('teacher.behavioral.view-behavioral', [
                'students' => $records,
                'class' => $class,
                'term' => $term,
                'session' => $session,
                'classList' => $this->studentService->getClassesWithCounts(),
                'settings' => Setting::getCached(),
            ]);
        }

        return view('teacher.behavioral.view-behavioral', [
            'students' => collect(),
            'classList' => $this->studentService->getClassesWithCounts(),
            'settings' => Setting::getCached(),
        ]);
    }
}
