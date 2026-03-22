<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Fee categories for display on report cards and admin.
 */
enum FeeCategoryEnum: string
{
    case BOARDER = 'boarder';
    case DAY = 'day';
    case GENERAL = 'general';

    public function label(): string
    {
        return match ($this) {
            self::BOARDER => 'Boarder',
            self::DAY => 'Day',
            self::GENERAL => 'General',
        };
    }

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
