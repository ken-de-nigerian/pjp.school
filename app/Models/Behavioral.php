<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static Builder<static> forClassTermSessionSegment(string $class, string $term, string $session, string $segment)
 */
class Behavioral extends Model
{
    public $timestamps = false;

    protected $table = 'behavioral';

    protected $fillable = [
        'class',
        'term',
        'session',
        'segment',
        'name',
        'reg_number',
        'neatness',
        'music',
        'sports',
        'attentiveness',
        'punctuality',
        'health',
        'politeness',
        'date_added',
    ];

    protected function casts(): array
    {
        return [
            'date_added' => 'datetime',
        ];
    }

    /**
     * @return Builder<static>
     */
    public function scopeForClassTermSessionSegment(Builder $query, string $class, string $term, string $session, string $segment): Builder
    {
        return $query->where('class', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->where('segment', $segment);
    }
}
