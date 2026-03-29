<?php

declare(strict_types=1);

namespace App\Support;

use InvalidArgumentException;

/**
 * Runtime narrowing for request/service payloads.
 */
final class Coercion
{
    public static function string(mixed $value, string $default = ''): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        return $default;
    }

    public static function nullableString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            return $value;
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        return null;
    }

    public static function int(mixed $value, int $default = 0): int
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_string($value) && $value !== '' && ctype_digit($value)) {
            return (int) $value;
        }

        if (is_float($value)) {
            return (int) $value;
        }

        return $default;
    }

    public static function float(mixed $value, float $default = 0.0): float
    {
        if (is_float($value)) {
            return $value;
        }

        if (is_int($value)) {
            return (float) $value;
        }

        if (is_string($value) && is_numeric($value)) {
            return (float) $value;
        }

        return $default;
    }

    public static function bool(mixed $value, bool $default = false): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if ($value === 1 || $value === '1' || $value === 'true') {
            return true;
        }

        if ($value === 0 || $value === '0' || $value === 'false') {
            return false;
        }

        return $default;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{class: string, term: string, session: string}
     */
    public static function classTermSessionFromValidated(array $data): array
    {
        return [
            'class' => self::string($data['class'] ?? ''),
            'term' => self::string($data['term'] ?? ''),
            'session' => self::string($data['session'] ?? ''),
        ];
    }

    /**
     * Non-empty strings joined with commas, or a single scalar as string.
     */
    public static function commaSeparatedStrings(mixed $value): string
    {
        if (is_array($value)) {
            return implode(',', self::listOfStrings($value));
        }

        return self::string($value);
    }

    /**
     * @return list<string>
     */
    public static function listOfStrings(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $out = [];
        foreach ($value as $item) {
            $s = self::string($item);
            if ($s !== '') {
                $out[] = $s;
            }
        }

        return $out;
    }

    /**
     * @return list<int>
     */
    public static function listOfInt(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $out = [];
        foreach ($value as $item) {
            if (is_int($item)) {
                $out[] = $item;

                continue;
            }

            if (is_string($item) && ctype_digit($item)) {
                $out[] = (int) $item;
            }
        }

        return $out;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function listOfStringKeyedMaps(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $out = [];
        foreach ($value as $row) {
            if (! is_array($row)) {
                continue;
            }

            $map = [];
            foreach ($row as $k => $v) {
                if (! is_string($k)) {
                    continue;
                }

                $map[$k] = $v;
            }

            $out[] = $map;
        }

        return $out;
    }

    /**
     * @return array<string, mixed>
     */
    public static function stringKeyedMap(mixed $value): array
    {
        if (! is_array($value)) {
            throw new InvalidArgumentException('Expected an associative array.');
        }

        $map = [];
        foreach ($value as $k => $v) {
            if (! is_string($k)) {
                throw new InvalidArgumentException('Expected string array keys.');
            }

            $map[$k] = $v;
        }

        return $map;
    }
}
