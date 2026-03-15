<?php

namespace App\Helpers;

class ClassArm
{
    private const PATTERN = '/(JSS|SSS) [1-3]/';

    /**
     * Extract class arm (e.g. "JSS 1", "SSS 2") from the full class name.
     */
    public static function fromClass(string $class): string
    {
        if (preg_match(self::PATTERN, $class, $matches)) {
            return $matches[0];
        }

        return $class === 'Graduated' ? 'Graduated' : 'Left';
    }
}
