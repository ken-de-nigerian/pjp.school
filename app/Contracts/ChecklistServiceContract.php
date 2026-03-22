<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Checklist;
use Illuminate\Support\Collection;

interface ChecklistServiceContract
{
    /**
     * @return Collection<int, Checklist>
     */
    public function activeForTermSession(string $term, string $session): Collection;

    /**
     * @return Collection<int, Checklist>
     */
    public function listForAdminFilters(?string $term, ?string $session): Collection;
}
