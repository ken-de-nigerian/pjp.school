<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\ClassArm;
use App\Models\AnnualResult;
use App\Models\Position;
use Illuminate\Support\Collection;

class ResultService
{
    private const DEFAULT_RESULT_SEGMENT = 'No Segment';

    public function hasUploadedResults(string $class, string $term, string $session, string $subjects): bool
    {
        return AnnualResult::query()
            ->where('class', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->where('subjects', $subjects)
            ->exists();
    }

    public function getUploadedResults(string $class, string $term, string $session, string $subjects): Collection
    {
        return AnnualResult::query()
            ->with('student')
            ->where('class', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->where('subjects', $subjects)
            ->orderBy('name')
            ->get();
    }

    public function getResultsByClass(string $class, string $term, string $session, string $segment): Collection
    {
        return AnnualResult::query()
            ->where('class', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->where('segment', $segment)
            ->orderBy('name')
            ->get();
    }

    public function bulkInsert(array $results): int
    {
        $rows = [];
        foreach ($results as $r) {

            $ca = (float) ($r['ca'] ?? 0);
            $assignment = (float) ($r['assignment'] ?? 0);
            $exam = (float) ($r['exam'] ?? 0);

            $total = $ca + $assignment + $exam;

            $class = $r['class'] ?? '';
            $classArm = ClassArm::fromClass($class);

            $rows[] = [
                'studentId' => $r['studentId'] ?? null,
                'class' => $class,
                'class_arm' => $classArm,
                'term' => $r['term'] ?? '',
                'session' => $r['session'] ?? '',
                'subjects' => $r['subjects'] ?? '',
                'name' => $r['name'] ?? '',
                'reg_number' => $r['reg_number'] ?? '',
                'segment' => self::DEFAULT_RESULT_SEGMENT,
                'ca' => $ca,
                'assignment' => $assignment,
                'exam' => $exam,
                'total' => $total,
                'status' => 1,
                'date_added' => now()->format('Y-m-d H:i:s'),
            ];
        }

        if (empty($rows)) {
            return 0;
        }

        foreach ($rows as $row) {
            AnnualResult::query()->create($row);
        }

        return count($rows);
    }

    public function editUploadedResult(
        string $studentId,
        string $class,
        string $term,
        string $session,
        string $subjects,
        string $reg_number,
        $ca,
        $assignment,
        $exam
    ): int {
        $seg = self::DEFAULT_RESULT_SEGMENT;
        $weight = 1.0;
        $total = $weight * (floatval($ca) + floatval($assignment) + floatval($exam));

        return AnnualResult::query()
            ->where('studentId', $studentId)
            ->where('class', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->where('subjects', $subjects)
            ->where('reg_number', $reg_number)
            ->where('segment', $seg)
            ->update([
                'ca' => $ca,
                'assignment' => $assignment,
                'exam' => $exam,
                'total' => $total,
            ]);
    }

    public function approveByIds(array $ids): int
    {
        if (empty($ids)) {
            return 0;
        }

        return AnnualResult::query()
            ->whereIn('id', $ids)
            ->update(['status' => 1]);
    }

    public function rejectByIds(array $ids): int
    {
        if (empty($ids)) {
            return 0;
        }
        return AnnualResult::query()
            ->whereIn('id', $ids)
            ->update(['status' => 3]);
    }

    public function fetchResultsByName(string $name): Collection
    {
        $like = '%' . addcslashes($name, '%_\\') . '%';

        return AnnualResult::query()
            ->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhere('reg_number', 'like', $like);
            })
            ->orderByDesc('date_added')
            ->get();
    }

    public function deleteByIds(array $ids): int
    {
        if (empty($ids)) {
            return 0;
        }
        return (int) AnnualResult::query()->whereIn('id', $ids)->delete();
    }

    /** Delete all uploaded results for the given class, term, session and subject. */
    public function deleteByContext(string $class, string $term, string $session, string $subjects): int
    {
        return (int) AnnualResult::query()
            ->where('class', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->where('subjects', $subjects)
            ->delete();
    }

    public function hasPublishedResults(string $class, string $term, string $session): bool
    {
        return Position::query()
            ->where('class', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->exists();
    }

    /** Legacy: getPublishedResults — positions for class/term/session, order by class_position. */
    public function getPublishedResults(string $class, string $term, string $session): Collection
    {
        return Position::query()
            ->where('class', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->orderBy('class_position')
            ->get();
    }

    /** Legacy: getSegments — distinct segment names from annual_result for class/term/session. */
    public function getSegmentsForPublished(string $class, string $term, string $session): Collection
    {
        return AnnualResult::query()
            ->where('class_arm', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->distinct()
            ->pluck('segment');
    }
}
