<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FeeCategoryEnum;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $amount
 * @property FeeCategoryEnum $category
 * @property string $term
 * @property string $session
 * @property bool $is_active
 */
class Fee extends Model
{
    protected $fillable = [
        'title',
        'description',
        'amount',
        'category',
        'term',
        'session',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'category' => FeeCategoryEnum::class,
            'is_active' => 'boolean',
        ];
    }
}
