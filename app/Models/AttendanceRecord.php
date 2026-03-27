<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static Builder<static> forClassTermSessionSegment(string $class, string $term, string $session)
 */
class AttendanceRecord extends Model
{
    public $timestamps = false;

    protected $table = 'attendance_list';

    protected $fillable = [
        'class',
        'term',
        'session',
        'segment',
        'name',
        'reg_number',
        'class_roll_call',
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
     * Segment filter removed (post-migration); segment column is stored as config('school.no_segment').
     *
     * @param  Builder<AttendanceRecord>  $query
     * @return Builder<AttendanceRecord>
     */
    public function scopeForClassTermSessionSegment(Builder $query, string $class, string $term, string $session): Builder
    {
        return $query->where('class', $class)
            ->where('term', $term)
            ->where('session', $session);
    }
}
