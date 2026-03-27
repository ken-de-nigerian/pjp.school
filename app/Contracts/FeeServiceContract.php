<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Fee;
use Illuminate\Support\Collection;

interface FeeServiceContract
{
    /**
     * Active fee rows for a report card (term and session).
     *
     * @return Collection<int, Fee>
     */
    public function activeForTermSession(string $term, string $session): Collection;

    /**
     * @return Collection<int, Fee>
     */
    public function listForAdminFilters(?string $term, ?string $session): Collection;
}
