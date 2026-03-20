<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ResultRepositoryContract;
use App\Contracts\ResultServiceContract;
use App\Enums\ResultStatus;
use App\Helpers\ClassArm;
use App\Models\AnnualResult;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

final readonly class ResultService implements ResultServiceContract
{
    public function __construct(
        private ResultRepositoryContract $resultRepository
    ) {}

    public function hasUploadedResults(string $class, string $term, string $session, string $subjects): bool
    {
        return AnnualResult::query()
            ->where('class', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->where('subjects', $subjects)
            ->exists();
    }

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

    public function getResultsByClass(string $class, string $term, string $session): Collection
    {
        return AnnualResult::query()
            ->where('class', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->orderBy('name')
            ->get();
    }

    /**
     * @param  int  $uploadStatus  {@see ResultStatus::APPROVED} for admin uploads; {@see ResultStatus::PENDING} for teacher uploads
     *
     * @throws Throwable
     */
    public function bulkInsert(array $results, int $uploadStatus = 1): int
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
                'segment' => config('school.no_segment', 'No Segment'),
                'ca' => $ca,
                'assignment' => $assignment,
                'exam' => $exam,
                'total' => $total,
                'status' => $uploadStatus,
                'date_added' => now()->format('Y-m-d H:i:s'),
            ];
        }

        if (empty($rows)) {
            return 0;
        }

        DB::transaction(function () use ($rows) {
            foreach ($rows as $row) {
                AnnualResult::query()->create($row);
            }
        });

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
        $weight = 1.0;
        $total = $weight * (floatval($ca) + floatval($assignment) + floatval($exam));

        return AnnualResult::query()
            ->where('studentId', $studentId)
            ->where('class', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->where('subjects', $subjects)
            ->where('reg_number', $reg_number)
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
            ->update(['status' => ResultStatus::APPROVED->value]);
    }

    public function rejectByIds(array $ids): int
    {
        if (empty($ids)) {
            return 0;
        }

        return AnnualResult::query()
            ->whereIn('id', $ids)
            ->update(['status' => ResultStatus::REJECTED->value]);
    }

    public function fetchResultsByName(string $name): Collection
    {
        $like = '%'.addcslashes($name, '%_\\').'%';

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
        $like = '%'.addcslashes($param, '%_\\').'%';
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
        return $this->resultRepository->hasPublishedResults($class, $term, $session);
    }

    public function getPublishedResults(string $class, string $term, string $session): Collection
    {
        return $this->resultRepository->getPublishedResults($class, $term, $session);
    }

    public function getSegmentsForPublished(string $class, string $term, string $session): Collection
    {
        return $this->resultRepository->getSegmentsForPublished($class, $term, $session);
    }

    public function getSubjectBreakdownForPublished(string $class, string $term, string $session): Collection
    {
        return $this->resultRepository->getSubjectBreakdownForPublished($class, $term, $session);
    }

    public function setPublishedLiveStatus(string $class, string $term, string $session, string $regNumber, int $live): int
    {
        return $this->resultRepository->setPublishedLiveStatus($class, $term, $session, $regNumber, $live);
    }

    public function setPublishedLiveBulk(string $class, string $term, string $session, array $regNumbers, int $live): int
    {
        return $this->resultRepository->setPublishedLiveBulk($class, $term, $session, $regNumbers, $live);
    }

    public function deletePublishedResults(string $class, string $term, string $session): int
    {
        return $this->resultRepository->deletePublishedResults($class, $term, $session);
    }
}
