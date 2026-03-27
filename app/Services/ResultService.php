<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ResultRepositoryContract;
use App\Contracts\ResultServiceContract;
use App\Enums\ResultStatus;
use App\Helpers\ClassArm;
use App\Models\AnnualResult;
use App\Models\Student;
use App\Support\AnnualResultAggregation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
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
            ->select(['status'])
            ->first();
        if ($row === null) {
            return ['uploaded' => false, 'status' => null];
        }

        return ['uploaded' => true, 'status' => (int) $row->status];
    }

    public function getUploadedResults(string $class, string $term, string $session, string $subjects): Collection
    {
        $base = AnnualResult::query()
            ->where('class', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->where('subjects', $subjects);

        $results = AnnualResultAggregation::applyAggregatedSubjectScores($base)
            ->orderBy('name')
            ->get();

        return $this->hydrateStudentsForAggregatedAnnualResults($results);
    }

    public function getResultsByClass(string $class, string $term, string $session): Collection
    {
        $base = AnnualResult::query()
            ->where('class', $class)
            ->where('term', $term)
            ->where('session', $session);

        $results = AnnualResultAggregation::applyAggregatedSubjectScores($base)
            ->orderBy('name')
            ->get();

        return $this->hydrateStudentsForAggregatedAnnualResults($results);
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

        Cache::forget('annual_result.distinct_sessions');

        return count($rows);
    }

    public function editUploadedResult(
        string $studentId,
        string $class,
        string $term,
        string $session,
        string $subjects,
        string $reg_number,
        float|int|string $ca,
        float|int|string $assignment,
        float|int|string $exam
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
        if ($ids === []) {
            return 0;
        }

        $expanded = $this->expandAnnualResultRowIdsForLegacyDuplicates($ids);

        return AnnualResult::query()
            ->whereIn('id', $expanded)
            ->update(['status' => ResultStatus::APPROVED->value]);
    }

    public function rejectByIds(array $ids): int
    {
        if ($ids === []) {
            return 0;
        }

        $expanded = $this->expandAnnualResultRowIdsForLegacyDuplicates($ids);

        return AnnualResult::query()
            ->whereIn('id', $expanded)
            ->update(['status' => ResultStatus::REJECTED->value]);
    }

    /** @return Collection<int, AnnualResult> */
    public function fetchResultsByName(string $name): Collection
    {
        $like = '%'.addcslashes($name, '%_\\').'%';

        $base = AnnualResult::query()
            ->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhere('reg_number', 'like', $like);
            });

        $results = AnnualResultAggregation::applyAggregatedSubjectScores($base)
            ->orderByDesc('date_added')
            ->get();

        return $this->hydrateStudentsForAggregatedAnnualResults($results);
    }

    /**
     * Search results by name or reg_number, optionally filter by class.
     * Returns results ordered by session (desc), term, subjects.
     */
    public function searchResults(string $param, ?string $class = null): Collection
    {
        $like = '%'.addcslashes($param, '%_\\').'%';
        $query = AnnualResult::query();
        if ($class !== null && $class !== '') {
            $query->where(function ($q) use ($class) {
                $q->where('class_arm', $class)->orWhere('class', $class);
            });
        }
        $query->where(function ($q) use ($like) {
            $q->where('name', 'like', $like)
                ->orWhere('reg_number', 'like', $like);
        });

        $results = AnnualResultAggregation::applyAggregatedSubjectScores($query)
            ->orderBy('session', 'desc')
            ->orderBy('term')
            ->orderBy('subjects')
            ->get();

        return $this->hydrateStudentsForAggregatedAnnualResults($results);
    }

    /** Distinct sessions from annual_result, for search filters. */
    public function getDistinctSessionsFromResults(): Collection
    {
        return Cache::remember('annual_result.distinct_sessions', 120, function () {
            return AnnualResult::query()
                ->select('session')
                ->distinct()
                ->orderByDesc('session')
                ->pluck('session')
                ->filter();
        });
    }

    /** @deprecated Segment is no longer used; kept for contract compatibility. */
    public function getDistinctSegmentsFromResults(): Collection
    {
        return collect();
    }

    public function deleteByContext(string $class, string $term, string $session, string $subjects): int
    {
        $deleted = (int) AnnualResult::query()
            ->where('class', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->where('subjects', $subjects)
            ->delete();
        if ($deleted > 0) {
            Cache::forget('annual_result.distinct_sessions');
        }

        return $deleted;
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

    /**
     * @param  Collection<int, AnnualResult>  $results
     * @return Collection<int, AnnualResult>
     */
    private function hydrateStudentsForAggregatedAnnualResults(Collection $results): Collection
    {
        $studentIds = $results->pluck('studentId')->filter()->map(static fn ($id) => (int) $id)->unique()->values()->all();
        if ($studentIds === []) {
            return $results;
        }

        $students = Student::query()
            ->whereIn('id', $studentIds)
            ->get(['id', 'reg_number', 'imagelocation', 'firstname', 'lastname', 'othername'])
            ->keyBy('id');
        foreach ($results as $row) {
            $sid = $row->studentId ?? null;
            if ($sid !== null && isset($students[(int) $sid])) {
                $row->setRelation('student', $students[(int) $sid]);
            }
        }

        return $results;
    }

    /**
     * When the UI shows one aggregated row (MIN id), approve/reject must touch every legacy
     * duplicate row for the same subject context.
     *
     * @param  array<int|string>  $ids
     * @return list<int>
     */
    private function expandAnnualResultRowIdsForLegacyDuplicates(array $ids): array
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids))));
        if ($ids === []) {
            return [];
        }

        $rows = AnnualResult::query()
            ->whereIn('id', $ids)
            ->get(['id', 'reg_number', 'subjects', 'class', 'class_arm', 'term', 'session']);

        if ($rows->isEmpty()) {
            return [];
        }

        $tuples = [];
        $seenTuple = [];
        foreach ($rows as $row) {
            $key = $row->reg_number.'|'.$row->subjects.'|'.$row->class.'|'.$row->class_arm.'|'.$row->term.'|'.$row->session;
            if (isset($seenTuple[$key])) {
                continue;
            }
            $seenTuple[$key] = true;
            $tuples[] = [
                'reg_number' => $row->reg_number,
                'subjects' => $row->subjects,
                'class' => $row->class,
                'class_arm' => $row->class_arm,
                'term' => $row->term,
                'session' => $row->session,
            ];
        }

        $expanded = AnnualResult::query()
            ->where(function ($q) use ($tuples) {
                foreach ($tuples as $t) {
                    $q->orWhere(function ($w) use ($t) {
                        $w->where('reg_number', $t['reg_number'])
                            ->where('subjects', $t['subjects'])
                            ->where('class', $t['class'])
                            ->where('class_arm', $t['class_arm'])
                            ->where('term', $t['term'])
                            ->where('session', $t['session']);
                    });
                }
            })
            ->pluck('id')
            ->map(static fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        return array_values($expanded);
    }
}
