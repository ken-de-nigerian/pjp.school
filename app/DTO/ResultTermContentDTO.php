<?php

declare(strict_types=1);

namespace App\DTO;

use App\Models\Checklist;
use App\Models\Fee;
use Illuminate\Support\Collection;

/**
 * @phpstan-type FeeCollection Collection<int, Fee>
 * @phpstan-type ChecklistCollection Collection<int, Checklist>
 */
final readonly class ResultTermContentDTO
{
    /**
     * @param  FeeCollection  $fees
     * @param  ChecklistCollection  $checklists
     */
    public function __construct(
        public Collection $fees,
        public Collection $checklists,
    ) {}
}
