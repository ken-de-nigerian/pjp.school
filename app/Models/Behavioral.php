<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static Builder<static> forClassTermSessionSegment(string $class, string $term, string $session)
 *
 * @property mixed $reg_number
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
     * Return null when a segment is the placeholder, so the UI never displays "No Segment".
     */
    public function getSegmentAttribute(): null
    {
        return null;
    }

    /**
     * Filter by class, term, session.
     * Segment filter removed (post-migration); the segment column is stored as config('school.no_segment').
     *
     * @param  Builder<Behavioral>  $query
     * @return Builder<Behavioral>
     */
    public function scopeForClassTermSessionSegment(Builder $query, string $class, string $term, string $session): Builder
    {
        return $query->where('class', $class)
            ->where('term', $term)
            ->where('session', $session);
    }
}
