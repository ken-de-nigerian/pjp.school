<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Annual result approval status (annual_result.status).
 *
 * Upload defaults: admin uploads are stored as {@see self::APPROVED};
 * teacher uploads are stored as {@see self::PENDING} until an admin approves.
 */
enum ResultStatus: int
{
    case APPROVED = 1;
    case PENDING = 2;
    case REJECTED = 3;
}
