<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadResultsTermRequest;
use App\Models\Notification;
use App\Models\Subject;
use App\Models\Setting;
use App\Services\ResultService;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResultsController extends Controller
{
    public function __construct(
        private StudentService $studentService,
        private ResultService $resultService
    ) {}

    public function index(Request $request): View
    {
        $settings = Setting::getCached();
        $class = trim((string) $request->query('class', ''));
        $subjects = trim((string) $request->query('subjects', ''));
        $term = trim((string) $request->query('term', $settings['term'] ?? 'First Term'));
        $session = trim((string) $request->query('session', $settings['session'] ?? ''));

        $showSheet = $class !== '' && $subjects !== '' && $term !== '' && $session !== '';

        $sessions = \App\Models\AcademicSession::query()->orderByDesc('year')->get();
        $getSubjects = Subject::query()->orderBy('grade')->orderBy('subject_name')->get();

        $students = collect();
        $alreadyUploaded = false;
        if ($showSheet) {
            $students = $this->studentService->getStudentsByClassAndSubject($class, $subjects);
            $alreadyUploaded = $this->resultService->hasUploadedResults($class, $term, $session, $subjects);
        }

        return view('teacher.results.index', [
            'showSheet' => $showSheet,
            'students' => $students,
            'class' => $class,
            'subjects' => $subjects,
            'term' => $term,
            'session' => $session,
            'alreadyUploaded' => $alreadyUploaded,
            'settings' => $settings,
            'getClasses' => $this->studentService->getClassesArray(),
            'getSubjects' => $getSubjects,
            'sessions' => $sessions,
        ]);
    }

    public function uploadTerm(UploadResultsTermRequest $request): JsonResponse
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

        $teacher = $request->user('teacher');
        $teacherName = $teacher ? trim(($teacher->firstname ?? '') . ' ' . ($teacher->lastname ?? '')) : 'Teacher';
        Notification::query()->create([
            'title' => 'Results Uploaded',
            'message' => $teacherName . ' has uploaded ' . $subjects . ' results for ' . $class . ' (' . $term . ', ' . $session . ').',
            'date_added' => now()->format('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $subjects . ' results for ' . $class . ' have been added successfully.',
        ]);
    }
}
