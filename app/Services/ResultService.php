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

    /**
     * Get upload and approval status for a subject in a class/term/session.
     * Returns ['uploaded' => bool, 'status' => int|null] where status is 1 = approved, 3 = rejected, 2/0 = pending.
     */
    public function getUploadAndApprovalStatus(string $class, string $term, string $session, string $subject): array
    {
        $row = AnnualResult::query()
            ->where('class', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->where('subjects', $subject)
            ->first();
        if ($row === null) {
            return ['uploaded' => false, 'status' => null];
        }
        return ['uploaded' => true, 'status' => (int) $row->status];
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

    /**
     * Search results by name or reg_number, optionally filter by class.
     * Returns results ordered by session (desc), term, subjects.
     */
    public function searchResults(string $param, ?string $class = null): Collection
    {
        $like = '%' . addcslashes($param, '%_\\') . '%';
        $query = AnnualResult::query()
            ->with('student')
            ->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhere('reg_number', 'like', $like);
            });
        if ($class !== null && $class !== '') {
            $query->where(function ($q) use ($class) {
                $q->where('class_arm', $class)->orWhere('class', $class);
            });
        }
        return $query
            ->orderBy('session', 'desc')
            ->orderBy('term')
            ->orderBy('subjects')
            ->get();
    }

    /** Distinct sessions from annual_result, for search filters. */
    public function getDistinctSessionsFromResults(): Collection
    {
        return AnnualResult::query()
            ->distinct()
            ->orderByRaw('session DESC')
            ->pluck('session')
            ->filter();
    }

    /** Distinct segments from annual_result (including empty as "No segment"). */
    public function getDistinctSegmentsFromResults(): Collection
    {
        $segments = AnnualResult::query()
            ->distinct()
            ->orderBy('segment')
            ->pluck('segment')
            ->map(fn ($s) => $s === null || $s === '' ? 'No segment' : $s);
        return $segments->unique()->values();
    }

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

    public function getPublishedResults(string $class, string $term, string $session): Collection
    {
        return Position::query()
            ->where('class', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->orderBy('class_position')
            ->get();
    }

    public function getSegmentsForPublished(string $class, string $term, string $session): Collection
    {
        return AnnualResult::query()
            ->where('class_arm', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->distinct()
            ->pluck('segment');
    }

    public function getSubjectBreakdownForPublished(string $class, string $term, string $session): Collection
    {
        return AnnualResult::query()
            ->forClassTermSession($class, $term, $session)
            ->approved()
            ->orderBy('subjects')
            ->get()
            ->groupBy('reg_number');
    }

    public function setPublishedLiveStatus(string $class, string $term, string $session, string $regNumber, int $live): int
    {
        return Position::query()
            ->where('class', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->where('reg_number', $regNumber)
            ->update(['status' => $live]);
    }

    public function setPublishedLiveBulk(string $class, string $term, string $session, array $regNumbers, int $live): int
    {
        if (empty($regNumbers)) {
            return 0;
        }
        return Position::query()
            ->where('class', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->whereIn('reg_number', $regNumbers)
            ->update(['status' => $live]);
    }

    public function deletePublishedResults(string $class, string $term, string $session): int
    {
        return Position::query()
            ->where('class', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->delete();
    }
}
