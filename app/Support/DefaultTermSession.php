<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Setting;

/**
 * Provides default term and session from cached settings for use in controllers and services.
 */
final class DefaultTermSession
{
    public static function getDefaultTerm(): string
    {
        $settings = Setting::getCached();

        return trim(Coercion::string($settings['term'] ?? 'First Term'));
    }

    public static function getDefaultSession(): string
    {
        $settings = Setting::getCached();

        return trim(Coercion::string($settings['session'] ?? ''));
    }
}
