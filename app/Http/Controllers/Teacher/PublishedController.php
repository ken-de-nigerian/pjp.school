<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ResultService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublishedController extends Controller
{
    public function __construct(
        private ResultService $resultService
    ) {}

    /** GET teacher/published — form or view results. */
    public function index(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $class = $request->query('class');
        $term = $request->query('term');
        $session = $request->query('session');
        $settings = Setting::getCached();

        if ($class && $term && $session) {
            if (! $this->resultService->hasPublishedResults($class, $term, $session)) {
                return redirect()->route('teacher.published.index')
                    ->with('error', "No {$term} And {$session} Session results for {$class} found.");
            }
            $students = $this->resultService->getPublishedResults($class, $term, $session);
            $getSegment = $this->resultService->getSegmentsForPublished($class, $term, $session);
            return view('teacher.published.view-results', [
                'students' => $students,
                'getSegment' => $getSegment,
                'class' => $class,
                'term' => $term,
                'session' => $session,
                'settings' => $settings,
            ]);
        }

        return view('teacher.published.index', [
            'settings' => $settings,
            'sessions' => \App\Models\AcademicSession::query()->orderByDesc('session')->get(),
        ]);
    }
}
