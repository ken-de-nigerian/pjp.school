<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Reusable term/session filter application for Eloquent queries.
 * Use in services that filter by class, term, and session.
 */
trait HasTermSessionFilters
{
    /**
     * Apply class, term, and session filters to a query that has forClassTermSession scope.
     */
    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    protected function applyTermSessionFilters(Builder $query, string $class, string $term, string $session): Builder
    {
        return $query->where('class', $class)
            ->where('term', $term)
            ->where('session', $session);
    }
}
