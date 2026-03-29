<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\AnnualResult;
use App\Models\Position;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

interface ResultServiceContract
{
    public function hasUploadedResults(string $class, string $term, string $session, string $subjects): bool;

    /** @return array{uploaded: bool, status: int|null} */
    public function getUploadAndApprovalStatus(string $class, string $term, string $session, string $subject): array;

    /** @return Collection<int, AnnualResult> */
    public function getUploadedResults(string $class, string $term, string $session, string $subjects): Collection;

    /**
     * @param  array<int, array<string, mixed>>  $results
     * @param  int  $uploadStatus  ResultStatus: admin uploads use APPROVED (1); teacher uploads use PENDING (2)
     */
    public function bulkInsert(array $results, int $uploadStatus = 1): int;

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
    ): int;

    /** @param array<int> $ids */
    public function approveByIds(array $ids): int;

    /** @param array<int> $ids */
    public function rejectByIds(array $ids): int;

    /** @return Collection<int, AnnualResult> */
    public function searchResults(string $param, ?string $class = null): Collection;

    /** @return Collection<int, mixed> */
    public function getDistinctSessionsFromResults(): Collection;

    public function hasPublishedResults(string $class, string $term, string $session): bool;

    /** @return EloquentCollection<int, Position> */
    public function getPublishedResults(string $class, string $term, string $session): EloquentCollection;

    /** @return Collection<string, EloquentCollection<int, AnnualResult>> */
    public function getSubjectBreakdownForPublished(string $class, string $term, string $session): Collection;

    public function setPublishedLiveStatus(string $class, string $term, string $session, string $regNumber, int $live): int;

    /** @param array<string> $regNumbers */
    public function setPublishedLiveBulk(string $class, string $term, string $session, array $regNumbers, int $live): int;

    public function deleteByContext(string $class, string $term, string $session, string $subjects): int;

    public function deletePublishedResults(string $class, string $term, string $session): int;
}
