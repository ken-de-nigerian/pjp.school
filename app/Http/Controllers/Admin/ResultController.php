<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\PublishClassResultsAction;
use App\Actions\SendNotificationAction;
use App\Contracts\ResultServiceContract;
use App\Enums\ResultStatus;
use App\Enums\Term;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApproveResultsRequest;
use App\Http\Requests\DeleteResultsRequest;
use App\Http\Requests\EditResultRequest;
use App\Http\Requests\PublishResultRequest;
use App\Http\Requests\RejectResultsRequest;
use App\Http\Requests\UploadResultsTermRequest;
use App\Models\Position;
use App\Models\SchoolClass;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Subject;
use App\Services\StudentService;
use App\Support\Coercion;
use App\Support\DefaultTermSession;
use App\Traits\AuthorizesAdminPermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

final class ResultController extends Controller
{
    use AuthorizesAdminPermission;

    public function __construct(
        private readonly PublishClassResultsAction $publishClassResultsAction,
        private readonly ResultServiceContract $resultService,
        private readonly SendNotificationAction $sendNotificationAction,
        private readonly StudentService $studentService,
    ) {}

    public function upload(Request $request): View
    {
        $this->authorizePermission('upload_result');
        $getClasses = $this->studentService->getClassesArray();
        $getSubjects = Subject::query()->orderBy('grade')->orderBy('subject_name')->get();

        $class = trim(Coercion::string($request->query('class', '')));
        $subjects = trim(Coercion::string($request->query('subjects', '')));
        $term = trim(Coercion::string($request->query('term', DefaultTermSession::getDefaultTerm())));
        $session = trim(Coercion::string($request->query('session', DefaultTermSession::getDefaultSession())));

        $showSheet = $class !== '' && $subjects !== '' && $term !== '' && $session !== '';

        $students = collect();
        $alreadyUploaded = false;
        if ($showSheet) {
            $students = $this->studentService->getStudentsByClassAndSubject($class, $subjects);
            $alreadyUploaded = $this->resultService->hasUploadedResults($class, $term, $session, $subjects);
        }

        return view('admin.results.upload', [
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
        $this->authorizePermission('upload_result');
        $results = $request->resultRows();
        $first = $results[0] ?? [];
        $class = Coercion::string($first['class'] ?? '');
        $term = Coercion::string($first['term'] ?? '');
        $session = Coercion::string($first['session'] ?? '');
        $subjects = Coercion::string($first['subjects'] ?? '');

        if ($this->resultService->hasUploadedResults($class, $term, $session, $subjects)) {
            return response()->json([
                'status' => 'error',
                'message' => $subjects.' results for '.$class.' ('.$term.') already exist.',
            ]);
        }

        $count = $this->resultService->bulkInsert($results, ResultStatus::APPROVED->value);
        if ($count !== count($results)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding '.$subjects.' results for '.$class.'. Please try again later.',
            ]);
        }

        $adminName = $request->user('admin')?->name;
        if (! is_string($adminName) || $adminName === '') {
            $adminName = 'Admin';
        }
        $this->sendNotificationAction->execute(
            'Results Uploaded',
            $adminName.' has uploaded '.$subjects.' results for '.$class.' ('.$term.', '.$session.').'
        );

        return response()->json([
            'status' => 'success',
            'message' => $subjects.' results for '.$class.' have been added successfully.',
        ]);
    }

    public function getUploadedResults(Request $request): View|JsonResponse
    {
        $this->authorizePermission('view_uploaded_results');
        $getClasses = $this->studentService->getClassesArray();
        $getSubjects = Subject::query()->orderBy('grade')->orderBy('subject_name')->get();

        $class = Coercion::string($request->query('class', ''));
        $subjects = Coercion::string($request->query('subjects', ''));
        $term = trim(Coercion::string($request->query('term', DefaultTermSession::getDefaultTerm())));
        $session = trim(Coercion::string($request->query('session', DefaultTermSession::getDefaultSession())));

        if ($class === '' || $term === '' || $session === '' || $subjects === '') {
            if ($request->expectsJson()) {
                return response()->json(['results' => []]);
            }

            return view('admin.results.uploaded', ['results' => collect(), 'class' => $class, 'term' => $term, 'session' => $session, 'subjects' => $subjects, 'getClasses' => $getClasses, 'getSubjects' => $getSubjects]);
        }

        if (! $this->resultService->hasUploadedResults($class, $term, $session, $subjects)) {
            if ($request->expectsJson()) {
                return response()->json(['results' => [], 'message' => 'No results found.']);
            }

            return view('admin.results.uploaded', ['results' => collect(), 'class' => $class, 'term' => $term, 'session' => $session, 'subjects' => $subjects, 'getClasses' => $getClasses, 'getSubjects' => $getSubjects]);
        }

        $results = $this->resultService->getUploadedResults($class, $term, $session, $subjects);

        if ($request->expectsJson()) {
            return response()->json(['results' => $results->values()->all()]);
        }

        return view('admin.results.uploaded', [
            'results' => $results,
            'class' => $class,
            'term' => $term,
            'session' => $session,
            'subjects' => $subjects,
            'getClasses' => $getClasses,
            'getSubjects' => $getSubjects,
        ]);
    }

    public function publish(): View
    {
        $this->authorizePermission('publish_result');
        $settings = Setting::getCached();

        return view('admin.results.publish', [
            'settings' => $settings,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function publishResults(PublishResultRequest $request): JsonResponse
    {
        $this->authorizePermission('publish_result');
        $cts = $request->classTermSession();
        $adminName = $request->user('admin')?->name;
        if (! is_string($adminName) || $adminName === '') {
            $adminName = 'Admin';
        }

        if ($this->resultService->hasPublishedResults($cts['class'], $cts['term'], $cts['session'])) {
            return response()->json([
                'status' => 'error',
                'message' => $cts['term'].' results for '.$cts['class'].' has already been published',
            ]);
        }

        $dto = $this->publishClassResultsAction->execute(
            $cts['class'],
            $cts['term'],
            $cts['session'],
            $adminName
        );

        $payload = $dto->toArray();
        if ($dto->isSuccess()) {
            $payload['redirect'] = route('admin.results.published', [
                'class' => $cts['class'],
                'term' => $cts['term'],
                'session' => $cts['session'],
            ]);
        }

        return response()->json($payload);
    }

    public function viewPublished(Request $request): View
    {
        $this->authorizePermission('view_published_results');

        $class = Coercion::string($request->query('class', ''));
        $term = trim(Coercion::string($request->query('term', DefaultTermSession::getDefaultTerm())));
        $session = trim(Coercion::string($request->query('session', DefaultTermSession::getDefaultSession())));

        $positions = collect();
        $classes = SchoolClass::query()->orderBy('class_name')->get();

        $subjectBreakdown = collect();
        $studentsByReg = collect();

        if ($class !== '' && $term !== '' && $session !== '') {
            $positions = $this->resultService->getPublishedResults($class, $term, $session)
                ->sortBy(static fn (Position $p): string => mb_strtoupper(Coercion::string($p->name ?? '')))
                ->values();
            $subjectBreakdown = $this->resultService->getSubjectBreakdownForPublished($class, $term, $session);
            $regNumbers = $positions->pluck('reg_number')->filter()->unique()->values()->all();
            if (! empty($regNumbers)) {
                $studentsByReg = Student::query()
                    ->whereIn('reg_number', $regNumbers)
                    ->get()
                    ->keyBy('reg_number');
            }
        }

        return view('admin.results.published', [
            'positions' => $positions,
            'subjectBreakdown' => $subjectBreakdown,
            'studentsByReg' => $studentsByReg,
            'classes' => $classes,
            'class' => $class,
            'term' => $term,
            'session' => $session,
        ]);
    }

    public function transcript(): View
    {
        $this->authorizePermission('transcript');

        return view('admin.transcript');
    }

    public function resultsByParams(Request $request): View
    {
        $this->authorizePermission('view_uploaded_results');
        $param = trim(Coercion::string($request->query('param', '')));
        $class = trim(Coercion::string($request->query('class', '')));
        $groupBy = Coercion::string($request->query('group_by', 'session'));

        $validGroupBy = ['session', 'term', 'none'];
        if (! in_array($groupBy, $validGroupBy, true)) {
            $groupBy = 'session';
        }

        $classes = $this->studentService->getClassesArray();
        $sessions = $this->resultService->getDistinctSessionsFromResults();
        $terms = Term::labels();

        $payload = [
            'param' => $param,
            'class' => $class,
            'group_by' => $groupBy,
            'classes' => $classes,
            'sessions' => $sessions,
            'terms' => $terms,
            'groupedResults' => [],
        ];

        if ($param === '') {
            return view('admin.results.manage-results', $payload);
        }

        $rows = $this->resultService->searchResults($param, $class === '' ? null : $class);
        $grouped = [];
        foreach ($rows as $row) {
            if ($groupBy === 'session') {
                $key = Coercion::string($row->session ?? 'Unknown');
            } elseif ($groupBy === 'term') {
                $key = Coercion::string($row->term ?? 'Unknown');
            } else {
                $key = 'Results';
            }
            $grouped[$key][] = $row;
        }
        if ($groupBy === 'session') {
            krsort($grouped, SORT_NATURAL);
        } elseif ($groupBy === 'term') {
            $order = array_flip($terms);
            uksort($grouped, fn ($a, $b) => ($order[$a] ?? 99) <=> ($order[$b] ?? 99));
        }
        $payload['groupedResults'] = $grouped;

        return view('admin.results.manage-results', $payload);
    }

    public function edit(EditResultRequest $request): JsonResponse
    {
        $this->authorizePermission('view_uploaded_results');
        $v = $request->editPayload();
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
            $admin = $request->user('admin');
            $adminName = $admin ? $admin->name : 'Admin';
            $this->sendNotificationAction->execute(
                'Results Edited',
                $adminName.' has edited '.$v['subjects'].' results for student in '.$v['class']
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

    public function approve(ApproveResultsRequest $request): JsonResponse
    {
        $this->authorizePermission('view_uploaded_results');
        $ids = $request->selectedIds();
        $count = $this->resultService->approveByIds($ids);

        if ($count > 0) {
            $admin = $request->user('admin');
            $adminName = $admin ? $admin->name : 'Admin';
            $this->sendNotificationAction->execute(
                'Results Approved',
                "$adminName approved results for $count students"
            );

            return response()->json([
                'status' => 'success',
                'message' => $count.' result(s) have been approved successfully.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Result approval has failed.',
        ]);
    }

    public function reject(RejectResultsRequest $request): JsonResponse
    {
        $this->authorizePermission('view_uploaded_results');
        $ids = $request->selectedIds();
        $count = $this->resultService->rejectByIds($ids);

        if ($count > 0) {
            $admin = $request->user('admin');
            $adminName = $admin ? $admin->name : 'Admin';
            $this->sendNotificationAction->execute(
                'Results Rejected',
                "$adminName rejected results for $count students"
            );

            return response()->json([
                'status' => 'success',
                'message' => $count.' result(s) have been rejected.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Reject failed.',
        ]);
    }

    public function delete(DeleteResultsRequest $request): JsonResponse
    {
        $this->authorizePermission('view_uploaded_results');
        $class = Coercion::string($request->input('class'));
        $term = Coercion::string($request->input('term'));
        $session = Coercion::string($request->input('session'));
        $subjects = Coercion::string($request->input('subjects'));
        $count = $this->resultService->deleteByContext($class, $term, $session, $subjects);

        if ($count > 0) {
            $admin = $request->user('admin');
            $adminName = $admin ? $admin->name : 'Admin';
            $this->sendNotificationAction->execute(
                'Results Deleted',
                $adminName.' has deleted all '.$subjects.' results for '.$class.' ('.$term.', '.$session.').'
            );

            return response()->json([
                'status' => 'success',
                'message' => 'All '.$count.' result(s) for this sheet have been deleted.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No results found to delete.',
        ]);
    }

    public function togglePublishedLive(Request $request): JsonResponse
    {
        $this->authorizePermission('view_published_results');
        $v = Coercion::stringKeyedMap($request->validate([
            'class' => 'required|string|max:100',
            'term' => 'required|string|max:100',
            'session' => 'required|string|max:100',
            'reg_number' => 'required|string|max:50',
            'live' => 'required|boolean',
        ]));
        $live = $request->boolean('live') ? 1 : 0;
        $cts = Coercion::classTermSessionFromValidated($v);
        $updated = $this->resultService->setPublishedLiveStatus(
            $cts['class'],
            $cts['term'],
            $cts['session'],
            Coercion::string($v['reg_number'] ?? ''),
            $live
        );
        if ($updated > 0) {
            return response()->json([
                'status' => 'success',
                'message' => $live ? 'Results are now live.' : 'Results are no longer live.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No published result found to update.',
        ]);
    }

    public function setPublishedLiveBulk(Request $request): JsonResponse
    {
        $this->authorizePermission('view_published_results');
        $v = Coercion::stringKeyedMap($request->validate([
            'class' => 'required|string|max:100',
            'term' => 'required|string|max:100',
            'session' => 'required|string|max:100',
            'selectedRows' => 'required|array',
            'selectedRows.*' => 'string|max:50',
            'live' => 'required|boolean',
        ]));
        $regNumbers = Coercion::listOfStrings($v['selectedRows'] ?? []);
        $live = $request->boolean('live') ? 1 : 0;
        $cts = Coercion::classTermSessionFromValidated($v);
        $updated = $this->resultService->setPublishedLiveBulk(
            $cts['class'],
            $cts['term'],
            $cts['session'],
            $regNumbers,
            $live
        );
        if ($updated > 0) {
            return response()->json([
                'status' => 'success',
                'message' => $updated.' result(s) have been marked as '.($live ? 'live' : 'not live').'.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No published results found to update.',
        ]);
    }

    public function deletePublished(Request $request): JsonResponse
    {
        $this->authorizePermission('view_published_results');
        $v = Coercion::stringKeyedMap($request->validate([
            'class' => 'required|string|max:100',
            'term' => 'required|string|max:100',
            'session' => 'required|string|max:100',
        ]));
        $cts = Coercion::classTermSessionFromValidated($v);
        $count = $this->resultService->deletePublishedResults($cts['class'], $cts['term'], $cts['session']);
        if ($count > 0) {
            $admin = $request->user('admin');
            $adminName = $admin ? $admin->name : 'Admin';
            $this->sendNotificationAction->execute(
                'Published Results Deleted',
                $adminName.' has deleted published results for '.$cts['class'].' ('.$cts['term'].', '.$cts['session'].').'
            );

            return response()->json([
                'status' => 'success',
                'message' => $count.' published result(s) have been deleted.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No published results found to delete.',
        ]);
    }
}
