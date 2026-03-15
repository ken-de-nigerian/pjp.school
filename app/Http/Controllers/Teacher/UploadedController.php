<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditResultRequest;
use App\Models\Notification;
use App\Models\Setting;
use App\Services\ResultService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UploadedController extends Controller
{
    public function __construct(
        private ResultService $resultService
    ) {}

    /** POST teacher/uploaded/edit-result — legacy teacher edit uploaded result (single row). */
    public function editResult(EditResultRequest $request): JsonResponse
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
            $teacher = $request->user('teacher');
            $teacherName = $teacher ? trim(($teacher->firstname ?? '') . ' ' . ($teacher->lastname ?? '')) : 'Teacher';
            Notification::query()->create([
                'title' => 'Results Edited',
                'message' => $teacherName . ' has edited ' . $v['subjects'] . ' results for ' . $v['reg_number'] . ' in ' . $v['class'],
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

    /** GET teacher/uploaded — form or result sheet. */
    public function index(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $class = $request->query('class');
        $term = $request->query('term');
        $session = $request->query('session');
        $subjects = $request->query('subjects');
        $settings = Setting::getCached();

        if ($class && $term && $session && $subjects) {
            if (! $this->resultService->hasUploadedResults($class, $term, $session, $subjects)) {
                return redirect()->route('teacher.uploaded.index')
                    ->with('error', "No {$subjects} results for {$class} ({$term}) found.");
            }
            $students = $this->resultService->getUploadedResults($class, $term, $session, $subjects);
            return view('teacher.uploaded.view-sheet', [
                'students' => $students,
                'class' => $class,
                'term' => $term,
                'session' => $session,
                'subjects' => $subjects,
                'settings' => $settings,
            ]);
        }

        return view('teacher.uploaded.index', [
            'settings' => $settings,
            'sessions' => \App\Models\AcademicSession::query()->orderByDesc('session')->get(),
        ]);
    }
}
