<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Services\ResultService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StatusController extends Controller
{
    public function __construct(
        private ResultService $resultService
    ) {}

    /** GET admin/status — form; with class, term, session, segment shows result sheet (Phase 6). */
    public function index(Request $request): View
    {
        $setting = \App\Models\Setting::getCached();
        $sessions = \App\Models\AcademicSession::query()->orderByDesc('id')->get();
        $getClasses = SchoolClass::query()->orderBy('class_name')->get();
        $class = $request->query('class');
        $term = $request->query('term');
        $session = $request->query('session');
        $segment = 'First';

        if (($class !== null && $class !== '') && ($term !== null && $term !== '') && ($session !== null && $session !== '')) {
            $classDecoded = urldecode($class);
            $termDecoded = urldecode($term);
            $sessionDecoded = urldecode($session);
            $getResults = $this->resultService->getResultsByClass($classDecoded, $termDecoded, $sessionDecoded, $segment);

            return view('admin.results.result-sheet', [
                'getClasses' => $getClasses,
                'sessions' => $sessions,
                'getResults' => $getResults,
                'class' => $classDecoded,
                'term' => $termDecoded,
                'session' => $sessionDecoded,
                'segment' => $segment,
            ]);
        }

        return view('admin.results.results-status', [
            'getClasses' => $getClasses,
            'sessions' => $sessions,
            'settings' => $setting,
        ]);
    }
}
