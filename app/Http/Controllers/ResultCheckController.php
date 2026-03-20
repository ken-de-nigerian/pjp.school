<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ResultCheckService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ResultCheckController extends Controller
{
    public function __construct(
        private readonly ResultCheckService $resultCheckService
    ) {}

    /**
     * @throws Throwable
     */
    public function index(Request $request): View|RedirectResponse
    {
        $settings = $this->resultCheckService->getSettings();

        $term = $request->query('term');
        $session = $request->query('session');
        $class = $request->query('class');
        $regNumber = $request->query('reg_number');
        $scratchCard = $request->query('scratch_card');

        $scratchRequired = $this->resultCheckService->isScratchCardRequired();

        if ($term === null || $term === '' || $session === null || $session === '' || $class === null || $class === '' || $regNumber === null || $regNumber === '') {
            return view('result.check-result', [
                'settings' => $settings,
                'scratchRequired' => $scratchRequired,
            ]);
        }

        if ($scratchRequired && ($scratchCard === null || $scratchCard === '')) {
            return redirect()->route('result.check')->with('error', 'Scratch card number is required.');
        }

        if (! $this->resultCheckService->hasStudentId($regNumber)) {
            return redirect()->route('result.check')->with('error', 'A student with this ID Number does not exist.');
        }

        if (! $this->resultCheckService->hasApprovedFees($regNumber)) {
            return view('result.fee-error');
        }

        if (! $this->resultCheckService->hasPublished($class, $term, $session)) {
            return redirect()->route('result.check')->with('error', $term.' results for '.$class.' have not been published yet.');
        }

        if ($scratchRequired) {
            $pinError = $this->resultCheckService->validateAndRecordPin($scratchCard, $regNumber, $class, $term, $session);
            if ($pinError !== null) {
                return redirect()->route('result.check')->with('error', $pinError);
            }
        }

        $student = $this->resultCheckService->getStudentReport($regNumber);
        $reportCard = $this->resultCheckService->getReportCard($regNumber, $class, $term, $session);
        if (! $reportCard) {
            return redirect()->route('result.check')->with('error', 'No published result found for this student and selection.');
        }

        $behavioral = $this->resultCheckService->getBehavioral($regNumber, $term, $session);
        $getSegment = $this->resultCheckService->getSegment($regNumber, $class, $term, $session);

        return view('result.result', [
            'student' => $student,
            'reportCard' => $reportCard,
            'behavioral' => $behavioral,
            'getSegment' => $getSegment,
            'term' => $term,
            'session' => $session,
            'class' => $class,
            'settings' => $settings,
        ]);
    }
}
