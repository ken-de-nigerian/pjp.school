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
use App\Models\Setting;
use App\Models\Subject;
use App\Services\ResultPublishService;
use App\Services\ResultService;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
        $class = $request->query('class', '');
        $term = $request->query('term', '');
        $session = $request->query('session', '');
        $subjects = $request->query('subjects', '');

        if (! $class || ! $term || ! $session || ! $subjects) {
            if ($request->expectsJson()) {
                return response()->json(['results' => []]);
            }

            return view('admin.results.uploaded', ['results' => collect(), 'class' => $class, 'term' => $term, 'session' => $session, 'subjects' => $subjects]);
        }

        if (! $this->resultService->hasUploadedResults($class, $term, $session, $subjects)) {
            if ($request->expectsJson()) {
                return response()->json(['results' => [], 'message' => 'No results found.']);
            }

            return view('admin.results.uploaded', ['results' => collect(), 'class' => $class, 'term' => $term, 'session' => $session, 'subjects' => $subjects]);
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
        ]);
    }

    public function publish()
    {
        //
    }

    public function publishResults(PublishResultRequest $request)
    {
        $validated = $request->validated();
        $admin = $request->user('admin');
        $adminName = $admin ? $admin->name : 'Admin';

        $response = $this->resultPublishService->publish(
            $validated['class'],
            $validated['term'],
            $validated['session'],
            $adminName
        );

        return response()->json($response);
    }

    public function viewPublished(Request $request): View
    {
        $class = $request->query('class', '');
        $term = $request->query('term', '');
        $session = $request->query('session', '');
        $positions = collect();
        $sessions = AcademicSession::query()->orderByDesc('year')->get();
        $classes = SchoolClass::query()->orderBy('class_name')->get();

        if ($class !== '' && $term !== '' && $session !== '') {
            $positions = $this->resultService->getPublishedResults($class, $term, $session);
        }

        return view('admin.results.published', [
            'positions' => $positions,
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
        if ($param === '') {
            return view('admin.results.manage-results');
        }
        $rows = $this->resultService->fetchResultsByName($param);

        return view('admin.results.manage-results', ['searchResults' => $rows, 'param' => $param]);
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
}
