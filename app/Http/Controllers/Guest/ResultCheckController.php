<?php

declare(strict_types=1);

namespace App\Http\Controllers\Guest;

use App\Contracts\ChecklistServiceContract;
use App\Contracts\FeeServiceContract;
use App\DTO\ResultTermContentDTO;
use App\Http\Controllers\Controller;
use App\Services\ResultCheckService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

final class ResultCheckController extends Controller
{
    public function __construct(
        private readonly ResultCheckService $resultCheckService,
        private readonly FeeServiceContract $feeService,
        private readonly ChecklistServiceContract $checklistService
    ) {}

    /**
     * @return list<string>
     */
    private static function allowedResultClasses(): array
    {
        return ['JSS 1', 'JSS 2', 'JSS 3', 'SSS 1', 'SSS 2', 'SSS 3'];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function checkResultValidationRules(bool $scratchRequired): array
    {
        $rules = [
            'term' => ['required', 'string', Rule::in(['First Term', 'Second Term', 'Third Term'])],
            'session' => ['required', 'string', 'regex:/^\d{4}\/\d{4}$/'],
            'class' => ['required', 'string', Rule::in(self::allowedResultClasses())],
            'reg_number' => ['required', 'string', 'max:191'],
        ];
        if ($scratchRequired) {
            $rules['scratch_card'] = ['required', 'string', 'max:191'];
        }

        return $rules;
    }

    /**
     * @param  array<string, string>  $messages
     */
    private function redirectCheckFormWithFieldErrors(array $messages): RedirectResponse
    {
        $bag = new MessageBag;
        foreach ($messages as $field => $message) {
            $bag->add($field, $message);
        }

        return redirect()->route('result.check')
            ->withErrors($bag)
            ->withInput();
    }

    /**
     * @throws Throwable
     */
    public function index(Request $request): View|RedirectResponse
    {
        $settings = $this->resultCheckService->getSettings();

        $scratchRequired = $this->resultCheckService->isScratchCardRequired();

        if (! $request->hasAny(['term', 'session', 'class', 'reg_number'])) {
            return view('result.check-result', [
                'settings' => $settings,
                'scratchRequired' => $scratchRequired,
            ]);
        }

        $validator = Validator::make(
            $request->query->all(),
            $this->checkResultValidationRules($scratchRequired)
        );

        if ($validator->fails()) {
            return redirect()->route('result.check')
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        $term = $data['term'];
        $session = $data['session'];
        $class = $data['class'];
        $regNumber = trim((string) $data['reg_number']);
        $scratchCard = isset($data['scratch_card']) ? trim((string) $data['scratch_card']) : trim((string) $request->query('scratch_card', ''));

        if (! $this->resultCheckService->hasStudentId($regNumber)) {
            return $this->redirectCheckFormWithFieldErrors([
                'reg_number' => 'A student with this ID Number does not exist.',
            ]);
        }

        if (! $this->resultCheckService->hasApprovedFees($regNumber)) {
            return view('result.fee-error');
        }

        if (! $this->resultCheckService->hasPublished($class, $term, $session)) {
            return $this->redirectCheckFormWithFieldErrors([
                'class' => $term.' results for '.$class.' have not been published yet.',
            ]);
        }

        if ($scratchRequired) {
            $pinError = $this->resultCheckService->validateAndRecordPin($scratchCard, $regNumber, $class, $term, $session);
            if ($pinError !== null) {
                return $this->redirectCheckFormWithFieldErrors([
                    'scratch_card' => $pinError,
                ]);
            }
        }

        $student = $this->resultCheckService->getStudentReport($regNumber);
        $reportCard = $this->resultCheckService->getReportCard($regNumber, $class, $term, $session);
        if (! $reportCard) {
            return $this->redirectCheckFormWithFieldErrors([
                'reg_number' => 'No published result found for this student and selection.',
            ]);
        }

        $behavioral = $this->resultCheckService->getBehavioral($regNumber, $term, $session);
        $getSegment = $this->resultCheckService->getSegment($regNumber, $class, $term, $session);
        $classCount = $this->resultCheckService->getClassCount($class);

        $termContent = new ResultTermContentDTO(
            $this->feeService->activeForTermSession($term, $session),
            $this->checklistService->activeForTermSession($term, $session),
        );

        return view('result.result', [
            'student' => $student,
            'reportCard' => $reportCard,
            'behavioral' => $behavioral,
            'getSegment' => $getSegment,
            'term' => $term,
            'session' => $session,
            'class' => $class,
            'settings' => $settings,
            'classCount' => $classCount,
            'fees' => $termContent->fees,
            'checklists' => $termContent->checklists,
            'principalRemark' => $reportCard->resolvedPrincipalRemark(),
        ]);
    }
}
