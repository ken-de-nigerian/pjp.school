<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Contracts\NotificationServiceContract;
use App\Contracts\ResultServiceContract;
use App\Enums\ResultStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Teacher\Concerns\TeacherScope;
use App\Http\Requests\EditResultRequest;
use App\Http\Requests\UploadResultsTermRequest;
use App\Services\StudentService;
use App\Support\DefaultTermSession;
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

        $class = trim((string) $request->query('class', ''));
        $subjects = trim((string) $request->query('subjects', ''));
        $term = trim((string) $request->query('term', DefaultTermSession::getDefaultTerm()));
        $session = trim((string) $request->query('session', DefaultTermSession::getDefaultSession()));

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
        $results = $request->input('results');
        $class = $results[0]['class'] ?? '';
        $term = $results[0]['term'] ?? '';
        $session = $results[0]['session'] ?? '';
        $subjects = $results[0]['subjects'] ?? '';

        $this->ensureTeacherCanAccessClass($class);
        $this->ensureTeacherCanAccessSubject($subjects);

        if ($this->resultService->hasUploadedResults($class, $term, $session, $subjects)) {
            return response()->json([
                'status' => 'error',
                'message' => $subjects.' results for '.$class.' ('.$term.') already exist.',
            ]);
        }

        // Hard-scope uploaded rows to teacher's subject students in the selected class.
        $allowedRegs = $this->studentService
            ->getStudentsByClassAndSubject($class, $subjects)
            ->where('status', 2)
            ->pluck('reg_number')
            ->filter()
            ->values()
            ->all();
        $allowed = array_flip(array_map('strval', $allowedRegs));

        $scopedRows = array_values(array_filter($results, static function (array $r) use ($class, $term, $session, $subjects, $allowed): bool {
            $reg = (string) ($r['reg_number'] ?? '');

            return $reg !== ''
                && isset($allowed[$reg])
                && (string) ($r['class'] ?? '') === $class
                && (string) ($r['term'] ?? '') === $term
                && (string) ($r['session'] ?? '') === $session
                && (string) ($r['subjects'] ?? '') === $subjects;
        }));

        $count = $this->resultService->bulkInsert($scopedRows, ResultStatus::PENDING->value);
        if ($count !== count($scopedRows)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding '.$subjects.' results for '.$class.'. Please try again later.',
            ]);
        }

        $teacher = $request->user('teacher');
        $teacherName = $teacher ? trim($teacher->firstname.' '.$teacher->lastname) : 'Teacher';
        if ($teacherName === '') {
            $teacherName = 'Teacher';
        }

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

        $class = (string) $request->query('class', '');
        $subjects = (string) $request->query('subjects', '');
        $term = trim((string) $request->query('term', DefaultTermSession::getDefaultTerm()));
        $session = trim((string) $request->query('session', DefaultTermSession::getDefaultSession()));

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
                $reg = (string) ($r->reg_number ?? '');

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
        $v = $request->validated();

        $this->ensureTeacherCanAccessClass($v['class']);
        $this->ensureTeacherCanAccessSubject($v['subjects']);

        // Extra safety: ensure the student is registered under this teacher's subject.
        $allowedRegs = $this->studentService
            ->getStudentsByClassAndSubject($v['class'], $v['subjects'])
            ->where('status', 2)
            ->pluck('reg_number')
            ->filter()
            ->values()
            ->all();

        $regNumber = (string) $v['reg_number'];
        if ($regNumber === '' || ! in_array($regNumber, $allowedRegs, true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized.',
            ], 403);
        }

        $updated = $this->resultService->editUploadedResult(
            (string) $v['studentId'],
            $v['class'],
            $v['term'],
            $v['session'],
            $v['subjects'],
            $v['reg_number'],
            $v['ca'],
            $v['assignment'],
            $v['exam']
        );

        if ($updated === 1) {
            $teacher = $request->user('teacher');
            $teacherName = $teacher ? trim($teacher->firstname.' '.$teacher->lastname) : 'Teacher';
            if ($teacherName === '') {
                $teacherName = 'Teacher';
            }

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
}

