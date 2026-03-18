<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Support\Collection;

interface ResultRepositoryContract
{
    public function hasPublishedResults(string $class, string $term, string $session): bool;

    public function getPublishedResults(string $class, string $term, string $session): Collection;

    public function getSegmentsForPublished(string $class, string $term, string $session): Collection;

    public function getSubjectBreakdownForPublished(string $class, string $term, string $session): Collection;

    public function setPublishedLiveStatus(string $class, string $term, string $session, string $regNumber, int $live): int;

    /** @param array<string> $regNumbers */
    public function setPublishedLiveBulk(string $class, string $term, string $session, array $regNumbers, int $live): int;

    public function deletePublishedResults(string $class, string $term, string $session): int;
}
