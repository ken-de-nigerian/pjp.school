<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $term
 * @property string $session
 * @property bool $is_active
 * @property int $position
 */
final class Checklist extends Model
{
    protected $fillable = [
        'title',
        'term',
        'session',
        'is_active',
        'position',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'position' => 'integer',
        ];
    }
}
