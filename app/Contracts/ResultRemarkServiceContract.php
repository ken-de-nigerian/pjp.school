<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\StoreResultRemarkDTO;

interface ResultRemarkServiceContract
{
    public function storeOrUpdate(StoreResultRemarkDTO $dto): void;
}
