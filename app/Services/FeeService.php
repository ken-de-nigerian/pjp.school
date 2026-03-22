<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\FeeServiceContract;
use App\Models\Fee;
use Illuminate\Support\Collection;

final class FeeService implements FeeServiceContract
{
    public function activeForTermSession(string $term, string $session): Collection
    {
        return Fee::query()
            ->where('term', $term)
            ->where('session', $session)
            ->where('is_active', true)
            ->orderBy('category')
            ->orderBy('title')
            ->get();
    }

    public function listForAdminFilters(?string $term, ?string $session): Collection
    {
        $q = Fee::query()->orderByDesc('session')->orderBy('term')->orderBy('category')->orderBy('title');

        if ($term !== null && $term !== '') {
            $q->where('term', $term);
        }
        if ($session !== null && $session !== '') {
            $q->where('session', $session);
        }

        return $q->get();
    }
}
