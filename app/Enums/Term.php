<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Academic term values used across results, attendance, and behavioral modules.
 */
enum Term: string
{
    case FIRST = 'First Term';
    case SECOND = 'Second Term';
    case THIRD = 'Third Term';

    /**
     * @return array<string>
     */
    public static function labels(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Default term when none is selected (e.g. in filters).
     */
    public static function default(): self
    {
        return self::FIRST;
    }
}
