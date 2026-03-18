<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Support\Collection;

interface ResultServiceContract
{
    public function hasUploadedResults(string $class, string $term, string $session, string $subjects): bool;

    /** @return array{uploaded: bool, status: int|null} */
    public function getUploadAndApprovalStatus(string $class, string $term, string $session, string $subject): array;

    public function getUploadedResults(string $class, string $term, string $session, string $subjects): Collection;

    public function getResultsByClass(string $class, string $term, string $session): Collection;

    /** @param array<int, array<string, mixed>> $results */
    public function bulkInsert(array $results): int;

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

    public function searchResults(string $param, ?string $class = null): Collection;

    public function getDistinctSessionsFromResults(): Collection;

    public function getDistinctSegmentsFromResults(): Collection;

    public function hasPublishedResults(string $class, string $term, string $session): bool;

    public function getPublishedResults(string $class, string $term, string $session): Collection;

    public function getSegmentsForPublished(string $class, string $term, string $session): Collection;

    public function getSubjectBreakdownForPublished(string $class, string $term, string $session): Collection;

    public function setPublishedLiveStatus(string $class, string $term, string $session, string $regNumber, int $live): int;

    /** @param array<string> $regNumbers */
    public function setPublishedLiveBulk(string $class, string $term, string $session, array $regNumbers, int $live): int;

    public function deleteByContext(string $class, string $term, string $session, string $subjects): int;

    public function deletePublishedResults(string $class, string $term, string $session): int;
}
