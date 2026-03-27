<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Contracts\ResultServiceContract;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Teacher;
use App\Services\StudentService;
use App\Traits\AuthorizesAdminPermission;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class StatusController extends Controller
{
    use AuthorizesAdminPermission;

    public function __construct(
        private readonly ResultServiceContract $resultService,
        private readonly StudentService $studentService,
    ) {}

    public function index(Request $request): View
    {
        $this->authorizePermission('check_result_status');
        $settings = Setting::getCached();
        $getClasses = $this->studentService->getClassesArray();

        $class = trim((string) $request->query('class', ''));
        $term = trim((string) $request->query('term', $settings['term'] ?? 'First Term'));
        $session = trim((string) $request->query('session', $settings['session'] ?? ''));

        if ($term === '' && isset($settings['term']) && $settings['term'] !== '') {
            $term = (string) $settings['term'];
        }

        $payload = [
            'getClasses' => $getClasses,
            'settings' => $settings,
            'class' => $class,
            'term' => $term,
            'session' => $session,
            'teacherSubjects' => [],
        ];

        $hasFilters = $class !== '' && $term !== '' && $session !== '';

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
