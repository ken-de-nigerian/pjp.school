<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Annual result approval status (annual_result.status).
 */
enum ResultStatus: int
{
    case APPROVED = 1;
    case PENDING = 2;
    case REJECTED = 3;
}
