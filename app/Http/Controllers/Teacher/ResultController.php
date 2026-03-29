<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Contracts\NotificationServiceContract;
use App\Contracts\ResultServiceContract;
use App\Enums\ResultStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditResultRequest;
use App\Http\Requests\UploadResultsTermRequest;
use App\Models\Teacher;
use App\Services\StudentService;
use App\Support\Coercion;
use App\Support\DefaultTermSession;
use App\Traits\TeacherScope;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

final class ResultController extends Controller
{
    use TeacherScope;

    public function __construct(
        private readonly NotificationServiceContract $notificationService,
        private readonly ResultServiceContract $resultService,
        private readonly StudentService $studentService
    ) {}

    public function upload(Request $request): View
    {
        $getClasses = $this->teacherAssignedClasses();
        $getSubjects = $this->teacherSubjects();

        $class = trim(Coercion::string($request->query('class', '')));
        $subjects = trim(Coercion::string($request->query('subjects', '')));
        $term = trim(Coercion::string($request->query('term', DefaultTermSession::getDefaultTerm())));
        $session = trim(Coercion::string($request->query('session', DefaultTermSession::getDefaultSession())));

        $showSheet = $class !== '' && $subjects !== '' && $term !== '' && $session !== '';

        $students = collect();
        $alreadyUploaded = false;
        if ($showSheet) {
            $this->ensureTeacherCanAccessClass($class);
            $this->ensureTeacherCanAccessSubject($subjects);
            $students = $this->studentService->getStudentsByClassAndSubject($class, $subjects);
            $alreadyUploaded = $this->resultService->hasUploadedResults($class, $term, $session, $subjects);
        }

        return view('teacher.results.upload', [
            'getClasses' => $getClasses,
            'getSubjects' => $getSubjects,
            'class' => $class,
            'subjects' => $subjects,
            'term' => $term,
            'session' => $session,
            'showSheet' => $showSheet,
            'hasFilters' => $showSheet,
            'students' => $students,
            'alreadyUploaded' => $alreadyUploaded,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function uploadResults(UploadResultsTermRequest $request): JsonResponse
    {
        $results = $request->resultRows();
        $first = $results[0] ?? [];
        $class = Coercion::string($first['class'] ?? '');
        $term = Coercion::string($first['term'] ?? '');
        $session = Coercion::string($first['session'] ?? '');
        $subjects = Coercion::string($first['subjects'] ?? '');

        $this->ensureTeacherCanAccessClass($class);
        $this->ensureTeacherCanAccessSubject($subjects);

        if ($this->resultService->hasUploadedResults($class, $term, $session, $subjects)) {
            return response()->json([
                'status' => 'error',
                'message' => $subjects.' results for '.$class.' ('.$term.') already exist.',
            ]);
        }

        $allowedRegs = $this->studentService
            ->getStudentsByClassAndSubject($class, $subjects)
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
        foreach ($results as $r) {
            $reg = Coercion::string($r['reg_number'] ?? '');
            if ($reg === '' || ! isset($allowed[$reg])) {
                continue;
            }
            if (Coercion::string($r['class'] ?? '') !== $class
                || Coercion::string($r['term'] ?? '') !== $term
                || Coercion::string($r['session'] ?? '') !== $session
                || Coercion::string($r['subjects'] ?? '') !== $subjects) {
                continue;
            }
            $scopedRows[] = $r;
        }

        $count = $this->resultService->bulkInsert($scopedRows, ResultStatus::PENDING->value);
        if ($count !== count($scopedRows)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding '.$subjects.' results for '.$class.'. Please try again later.',
            ]);
        }

        $teacherName = $this->teacherDisplayName($request);

        $this->notificationService->add(
            'Results Uploaded',
            $teacherName.' has uploaded '.$subjects.' results for '.$class.' ('.$term.', '.$session.').'
        );

        return response()->json([
            'status' => 'success',
            'message' => $subjects.' results for '.$class.' have been added successfully.',
        ]);
    }

    public function getUploadedResults(Request $request): View|JsonResponse
    {
        $getClasses = $this->teacherAssignedClasses();
        $getSubjects = $this->teacherSubjects();

        $class = Coercion::string($request->query('class', ''));
        $subjects = Coercion::string($request->query('subjects', ''));
        $term = trim(Coercion::string($request->query('term', DefaultTermSession::getDefaultTerm())));
        $session = trim(Coercion::string($request->query('session', DefaultTermSession::getDefaultSession())));

        $hasFilters = $class !== '' && $term !== '' && $session !== '' && $subjects !== '';
        if (! $hasFilters) {
            if ($request->expectsJson()) {
                return response()->json(['results' => []]);
            }

            return view('teacher.results.uploaded', [
                'results' => collect(),
                'class' => $class,
                'term' => $term,
                'session' => $session,
                'subjects' => $subjects,
                'getClasses' => $getClasses,
                'getSubjects' => $getSubjects,
                'canEdit' => $this->teacherPolicyAllows('modifyResults'),
            ]);
        }

        $this->ensureTeacherCanAccessClass($class);
        $this->ensureTeacherCanAccessSubject($subjects);

        if (! $this->resultService->hasUploadedResults($class, $term, $session, $subjects)) {
            if ($request->expectsJson()) {
                return response()->json(['results' => [], 'message' => 'No results found.']);
            }

            return view('teacher.results.uploaded', [
                'results' => collect(),
                'class' => $class,
                'term' => $term,
                'session' => $session,
                'subjects' => $subjects,
                'getClasses' => $getClasses,
                'getSubjects' => $getSubjects,
                'canEdit' => $this->teacherPolicyAllows('modifyResults'),
            ]);
        }

        $allowedRegs = $this->studentService
            ->getStudentsByClassAndSubject($class, $subjects)
            ->where('status', 2)
            ->pluck('reg_number')
            ->filter()
            ->values()
            ->all();

        $results = $this->resultService->getUploadedResults($class, $term, $session, $subjects)
            ->filter(function ($r) use ($allowedRegs) {
                $reg = Coercion::string($r->reg_number ?? '');

                return $reg !== '' && in_array($reg, $allowedRegs, true);
            })
            ->values();

        if ($request->expectsJson()) {
            return response()->json(['results' => $results->values()->all()]);
        }

        return view('teacher.results.uploaded', [
            'results' => $results,
            'class' => $class,
            'term' => $term,
            'session' => $session,
            'subjects' => $subjects,
            'getClasses' => $getClasses,
            'getSubjects' => $getSubjects,
            'canEdit' => $this->teacherPolicyAllows('modifyResults'),
        ]);
    }

    public function edit(EditResultRequest $request): JsonResponse
    {
        $this->authorizeTeacherAbility('modifyResults');
        $v = $request->editPayload();

        $this->ensureTeacherCanAccessClass($v['class']);
        $this->ensureTeacherCanAccessSubject($v['subjects']);

        $allowedRegs = $this->studentService
            ->getStudentsByClassAndSubject($v['class'], $v['subjects'])
            ->where('status', 2)
            ->pluck('reg_number')
            ->filter()
            ->values()
            ->all();

        $regNumber = $v['reg_number'];
        if ($regNumber === '' || ! in_array($regNumber, $allowedRegs, true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized.',
            ], 403);
        }

        $updated = $this->resultService->editUploadedResult(
            $v['studentId'],
            $v['class'],
            $v['term'],
            $v['session'],
            $v['subjects'],
            $v['reg_number'],
            $v['ca'],
            $v['assignment'],
            $v['exam']
        );

        if ($updated > 0) {
            $teacherName = $this->teacherDisplayName($request);

            $this->notificationService->add(
                'Results Edited',
                $teacherName.' has edited '.$v['subjects'].' results for student in '.$v['class']
            );

            return response()->json([
                'status' => 'success',
                'message' => 'This student\'s '.$v['subjects'].' result has been updated successfully.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No changes was made to this student\'s result',
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
