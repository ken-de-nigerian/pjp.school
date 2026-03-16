<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApproveResultsRequest;
use App\Http\Requests\DeleteResultsRequest;
use App\Http\Requests\EditResultRequest;
use App\Http\Requests\RejectResultsRequest;
use App\Http\Requests\PublishResultRequest;
use App\Http\Requests\UploadResultsTermRequest;
use App\Models\AcademicSession;
use App\Models\Notification;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Setting;
use App\Models\Subject;
use App\Services\ResultPublishService;
use App\Services\ResultService;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ResultController extends Controller
{
    public function __construct(
        private readonly ResultPublishService $resultPublishService,
        private readonly ResultService $resultService,
        private readonly StudentService $studentService,
    ) {}

    public function upload(Request $request): View
    {
        $getClasses = $this->studentService->getClassesArray();
        $getSubjects = Subject::query()->orderBy('grade')->orderBy('subject_name')->get();
        $settings = Setting::getCached();

        $class = trim((string) $request->query('class', ''));
        $subjects = trim((string) $request->query('subjects', ''));
        $term = trim((string) $request->query('term', $settings['term'] ?? 'First Term'));
        $session = trim((string) $request->query('session', $settings['session'] ?? ''));

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

    public function uploadResults(UploadResultsTermRequest $request): JsonResponse
    {
        $results = $request->input('results');
        $class = $results[0]['class'] ?? '';
        $term = $results[0]['term'] ?? '';
        $session = $results[0]['session'] ?? '';
        $subjects = $results[0]['subjects'] ?? '';

        if ($this->resultService->hasUploadedResults($class, $term, $session, $subjects)) {
            return response()->json([
                'status' => 'error',
                'message' => $subjects . ' results for ' . $class . ' (' . $term . ') already exist.',
            ]);
        }

        $count = $this->resultService->bulkInsert($results);
        if ($count !== count($results)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding ' . $subjects . ' results for ' . $class . '. Please try again later.',
            ]);
        }

        $admin = $request->user('admin');
        $adminName = $admin ? $admin->name : 'Admin';
        Notification::query()->create([
            'title' => 'Results Uploaded',
            'message' => $adminName . ' has uploaded ' . $subjects . ' results for ' . $class . ' (' . $term . ', ' . $session . ').',
            'date_added' => now()->format('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $subjects . ' results for ' . $class . ' have been added successfully.',
        ]);
    }

    public function getUploadedResults(Request $request): View|JsonResponse
    {
        $getClasses = $this->studentService->getClassesArray();
        $getSubjects = Subject::query()->orderBy('grade')->orderBy('subject_name')->get();
        $settings = Setting::getCached();

        $class = $request->query('class', '');
        $subjects = $request->query('subjects', '');
        $term = trim((string) $request->query('term', $settings['term'] ?? 'First Term'));
        $session = trim((string) $request->query('session', $settings['session'] ?? ''));

        if (! $class || ! $term || ! $session || ! $subjects) {
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
        $settings = Setting::getCached();
        return view('admin.results.publish', [
            'settings' => $settings,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function publishResults(PublishResultRequest $request)
    {
        $validated = $request->validated();
        $admin = $request->user('admin');
        $adminName = $admin ? $admin->name : 'Admin';

        if ($this->resultService->hasPublishedResults($validated['class'], $validated['term'], $validated['session'])) {
            return response()->json([
                'status' => 'error',
                'message' => $validated['term'] . ' results for ' . $validated['class'] . ' has already been published',
            ]);
        }

        $response = $this->resultPublishService->publish(
            $validated['class'],
            $validated['term'],
            $validated['session'],
            $adminName
        );

        if (($response['status'] ?? '') === 'success') {
            $response['redirect'] = route('admin.results.published', [
                'class' => $validated['class'],
                'term' => $validated['term'],
                'session' => $validated['session'],
            ]);
        }

        return response()->json($response);
    }

    public function viewPublished(Request $request): View
    {
        $settings = Setting::getCached();
        $class = $request->query('class', '');
        $term = trim((string) $request->query('term', $settings['term'] ?? 'First Term'));
        $session = trim((string) $request->query('session', $settings['session'] ?? ''));

        $positions = collect();

        $sessions = AcademicSession::query()->orderByDesc('year')->get();
        $classes = SchoolClass::query()->orderBy('class_name')->get();

        $subjectBreakdown = collect();
        $studentsByReg = collect();

        if ($class !== '' && $term !== '' && $session !== '') {
            $positions = $this->resultService->getPublishedResults($class, $term, $session)
                ->sortBy(fn ($p) => mb_strtoupper($p->name ?? ''))
                ->values();
            $subjectBreakdown = $this->resultService->getSubjectBreakdownForPublished($class, $term, $session);
            $regNumbers = $positions->pluck('reg_number')->filter()->unique()->values()->all();
            if (!empty($regNumbers)) {
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
            'sessions' => $sessions,
            'classes' => $classes,
            'class' => $class,
            'term' => $term,
            'session' => $session,
        ]);
    }

    public function transcript(): View
    {
        return view('admin.transcript');
    }

    public function resultsByParams(Request $request): View
    {
        $param = trim((string) $request->query('param', ''));
        $class = trim((string) $request->query('class', ''));
        $groupBy = $request->query('group_by', 'session');

        $validGroupBy = ['session', 'segment', 'term', 'none'];
        if (!in_array($groupBy, $validGroupBy, true)) {
            $groupBy = 'session';
        }

        $classes = $this->studentService->getClassesArray();
        $sessions = $this->resultService->getDistinctSessionsFromResults();
        $terms = ['First Term', 'Second Term', 'Third Term'];
        $segments = $this->resultService->getDistinctSegmentsFromResults();

        $payload = [
            'param' => $param,
            'class' => $class,
            'group_by' => $groupBy,
            'classes' => $classes,
            'sessions' => $sessions,
            'terms' => $terms,
            'segments' => $segments,
            'groupedResults' => [],
        ];

        if ($param === '') {
            return view('admin.results.manage-results', $payload);
        }

        $rows = $this->resultService->searchResults($param, $class === '' ? null : $class);
        $grouped = [];
        foreach ($rows as $row) {
            if ($groupBy === 'session') {
                $key = $row->session ?? 'Unknown';
            } elseif ($groupBy === 'segment') {
                $key = $row->segment === null || $row->segment === '' ? 'No segment' : $row->segment;
            } elseif ($groupBy === 'term') {
                $key = $row->term ?? 'Unknown';
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
        $v = $request->validated();
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
            $admin = $request->user('admin');
            $adminName = $admin ? $admin->name : 'Admin';
            Notification::query()->create([
                'title' => 'Results Edited',
                'message' => $adminName . ' has edited ' . $v['subjects'] . ' results for student in ' . $v['class'],
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'This student\'s ' . $v['subjects'] . ' result has been updated successfully.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No changes was made to this student\'s result',
        ]);
    }

    public function approve(ApproveResultsRequest $request): JsonResponse
    {
        $ids = $request->input('selectedRows');
        $count = $this->resultService->approveByIds($ids);

        if ($count > 0) {
            $admin = $request->user('admin');
            $adminName = $admin ? $admin->name : 'Admin';
            Notification::query()->create([
                'title' => 'Results Approved',
                'message' => $adminName . ' has approved results for students with ID: ' . implode(', ', $ids),
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => $count . ' result(s) have been approved successfully.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Result approval has failed.',
        ]);
    }

    public function reject(RejectResultsRequest $request): JsonResponse
    {
        $ids = $request->input('selectedRows');
        $count = $this->resultService->rejectByIds($ids);

        if ($count > 0) {
            $admin = $request->user('admin');
            $adminName = $admin ? $admin->name : 'Admin';
            Notification::query()->create([
                'title' => 'Results Rejected',
                'message' => $adminName . ' has rejected results for students with ID: ' . implode(', ', $ids),
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => $count . ' result(s) have been rejected.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Reject failed.',
        ]);
    }

    public function delete(DeleteResultsRequest $request): JsonResponse
    {
        $class = (string) $request->input('class');
        $term = (string) $request->input('term');
        $session = (string) $request->input('session');
        $subjects = (string) $request->input('subjects');
        $count = $this->resultService->deleteByContext($class, $term, $session, $subjects);

        if ($count > 0) {
            $admin = $request->user('admin');
            $adminName = $admin ? $admin->name : 'Admin';
            Notification::query()->create([
                'title' => 'Results Deleted',
                'message' => $adminName . ' has deleted all ' . $subjects . ' results for ' . $class . ' (' . $term . ', ' . $session . ').',
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'All ' . $count . ' result(s) for this sheet have been deleted.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No results found to delete.',
        ]);
    }

    public function togglePublishedLive(Request $request): JsonResponse
    {
        $request->validate([
            'class' => 'required|string|max:100',
            'term' => 'required|string|max:100',
            'session' => 'required|string|max:100',
            'reg_number' => 'required|string|max:50',
            'live' => 'required|boolean',
        ]);
        $live = $request->boolean('live') ? 1 : 0;
        $updated = $this->resultService->setPublishedLiveStatus(
            $request->input('class'),
            $request->input('term'),
            $request->input('session'),
            $request->input('reg_number'),
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
        $request->validate([
            'class' => 'required|string|max:100',
            'term' => 'required|string|max:100',
            'session' => 'required|string|max:100',
            'selectedRows' => 'required|array',
            'selectedRows.*' => 'string|max:50',
            'live' => 'required|boolean',
        ]);
        $regNumbers = array_values(array_filter($request->input('selectedRows', [])));
        $live = $request->boolean('live') ? 1 : 0;
        $updated = $this->resultService->setPublishedLiveBulk(
            $request->input('class'),
            $request->input('term'),
            $request->input('session'),
            $regNumbers,
            $live
        );
        if ($updated > 0) {
            return response()->json([
                'status' => 'success',
                'message' => $updated . ' result(s) have been marked as ' . ($live ? 'live' : 'not live') . '.',
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'No published results found to update.',
        ]);
    }

    public function deletePublished(Request $request): JsonResponse
    {
        $request->validate([
            'class' => 'required|string|max:100',
            'term' => 'required|string|max:100',
            'session' => 'required|string|max:100',
        ]);
        $class = $request->input('class');
        $term = $request->input('term');
        $session = $request->input('session');
        $count = $this->resultService->deletePublishedResults($class, $term, $session);
        if ($count > 0) {
            $admin = $request->user('admin');
            $adminName = $admin ? $admin->name : 'Admin';
            Notification::query()->create([
                'title' => 'Published Results Deleted',
                'message' => $adminName . ' has deleted published results for ' . $class . ' (' . $term . ', ' . $session . ').',
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
            return response()->json([
                'status' => 'success',
                'message' => $count . ' published result(s) have been deleted.',
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'No published results found to delete.',
        ]);
    }
}
