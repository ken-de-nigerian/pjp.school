<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class StoreResultRemarkDTO
{
    public function __construct(
        public string $regNumber,
        public string $class,
        public string $term,
        public string $session,
        public ?string $remark,
    ) {}
}
