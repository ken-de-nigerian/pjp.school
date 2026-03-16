<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Setting;
use App\Models\Teacher;
use App\Services\ResultService;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StatusController extends Controller
{
    public function __construct(
        private readonly ResultService  $resultService,
        private readonly StudentService $studentService,
    ) {}

    public function index(Request $request): View
    {
        $settings = Setting::getCached();
        $getClasses = $this->studentService->getClassesArray();

        $class = trim((string) $request->query('class', ''));
        $term = trim((string) $request->query('term', $settings['term'] ?? 'First Term'));
        $session = trim((string) $request->query('session', $settings['session'] ?? ''));

        if ($term === '' && isset($settings['term']) && $settings['term'] !== '') {
            $term = (string) $settings['term'];
        }
        $viewSheet = $request->query('view') === 'sheet';

        $payload = [
            'getClasses' => $getClasses,
            'settings' => $settings,
            'class' => $class,
            'term' => $term,
            'session' => $session,
            'teacherSubjects' => [],
        ];

        $hasFilters = $class !== '' && $term !== '' && $session !== '';

        if ($hasFilters && $viewSheet) {
            $segment = 'First';
            $getResults = $this->resultService->getResultsByClass($class, $term, $session, $segment);

            return view('admin.results.result-sheet', [
                'getClasses' => $getClasses,
                'getResults' => $getResults,
                'class' => $class,
                'term' => $term,
                'session' => $session,
                'segment' => $segment,
            ]);
        }

        if ($hasFilters) {
            $teachers = Teacher::query()->get()->filter(function (Teacher $t) use ($class): bool {
                $assigned = $t->assigned_class ?? '';
                if ($assigned === '') {
                    return false;
                }
                $classes = array_map('trim', explode(',', $assigned));

                return in_array($class, $classes, true);
            });
            $teacherSubjects = [];
            foreach ($teachers as $t) {
                $subjectsList = array_filter(array_map('trim', explode(',', $t->subject_to_teach ?? '')));
                $subjectStatuses = [];
                foreach ($subjectsList as $subjectName) {
                    $subjectName = (string) $subjectName;
                    if ($subjectName === '') {
                        continue;
                    }
                    $status = $this->resultService->getUploadAndApprovalStatus($class, $term, $session, $subjectName);
                    $subjectStatuses[] = [
                        'name' => $subjectName,
                        'uploaded' => $status['uploaded'],
                        'status' => $status['status'],
                    ];
                }
                if (count($subjectStatuses) > 0) {
                    $teacherSubjects[] = [
                        'teacher' => $t,
                        'subjects' => $subjectStatuses,
                    ];
                }
            }
            $payload['teacherSubjects'] = $teacherSubjects;
        }

        return view('admin.results.results-status', $payload);
    }
}
