<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\ResultRepositoryContract;
use App\Models\AnnualResult;
use App\Models\Position;
use App\Traits\HasTermSessionFilters;
use Illuminate\Support\Collection;

final class ResultRepository implements ResultRepositoryContract
{
    use HasTermSessionFilters;

    public function hasPublishedResults(string $class, string $term, string $session): bool
    {
        return $this->applyTermSessionFilters(Position::query(), $class, $term, $session)->exists();
    }

    public function getPublishedResults(string $class, string $term, string $session): Collection
    {
        return $this->applyTermSessionFilters(Position::query(), $class, $term, $session)
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
        return $this->applyTermSessionFilters(Position::query(), $class, $term, $session)
            ->where('reg_number', $regNumber)
            ->update(['status' => $live]);
    }

    public function setPublishedLiveBulk(string $class, string $term, string $session, array $regNumbers, int $live): int
    {
        if ($regNumbers === []) {
            return 0;
        }

        return $this->applyTermSessionFilters(Position::query(), $class, $term, $session)
            ->whereIn('reg_number', $regNumbers)
            ->update(['status' => $live]);
    }

    public function deletePublishedResults(string $class, string $term, string $session): int
    {
        return $this->applyTermSessionFilters(Position::query(), $class, $term, $session)->delete();
    }
}
