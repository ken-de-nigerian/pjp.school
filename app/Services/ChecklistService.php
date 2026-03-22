<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ChecklistServiceContract;
use App\Models\Checklist;
use Illuminate\Support\Collection;

final class ChecklistService implements ChecklistServiceContract
{
    public function activeForTermSession(string $term, string $session): Collection
    {
        return Checklist::query()
            ->where('term', $term)
            ->where('session', $session)
            ->where('is_active', true)
            ->orderBy('position')
            ->orderBy('id')
            ->get();
    }

    public function listForAdminFilters(?string $term, ?string $session): Collection
    {
        $q = Checklist::query()
            ->orderByDesc('session')
            ->orderBy('term')
            ->orderBy('position')
            ->orderBy('id');

        if ($term !== null && $term !== '') {
            $q->where('term', $term);
        }
        if ($session !== null && $session !== '') {
            $q->where('session', $session);
        }

        return $q->get();
    }
}
