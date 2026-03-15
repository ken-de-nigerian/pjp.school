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

    /** GET teacher/behavioral/take-behavioral?class=&term=&session=&segment= — form. */
    public function takeBehavioral(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $class = $request->query('class');
        $term = $request->query('term');
        $session = $request->query('session');
        $segment = $request->query('segment');

        if (! $class || ! $term || ! $session || ! $segment) {
            return redirect()->route('teacher.behavioral.index')->with('error', 'Missing class, term, session or segment.');
        }

        $students = $this->studentService->getStudentsByClass($class, 500)->items();

        return view('teacher.behavioral.take-behavioral', [
            'students' => $students,
            'class' => $class,
            'term' => $term,
            'session' => $session,
            'segment' => $segment,
            'settings' => Setting::getCached(),
        ]);
    }

    /** GET teacher/behavioral/view-behavioral — form or record list. */
    public function viewBehavioral(Request $request): View
    {
        $class = $request->query('class');
        $term = $request->query('term');
        $session = $request->query('session');
        $segment = $request->query('segment');

        if ($class && $term && $session && $segment) {
            $records = $this->behavioralService->getRecord($class, $term, $session, $segment);
            return view('teacher.behavioral.view-behavioral', [
                'students' => $records,
                'class' => $class,
                'term' => $term,
                'session' => $session,
                'segment' => $segment,
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
