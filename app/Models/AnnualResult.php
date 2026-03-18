<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnnualResult extends Model
{
    public $timestamps = false;

    protected $table = 'annual_result';

    protected $fillable = [
        'studentId',
        'class',
        'class_arm',
        'term',
        'session',
        'subjects',
        'segment',
        'name',
        'reg_number',
        'ca',
        'assignment',
        'exam',
        'total',
        'status',
        'date_added',
    ];

    protected function casts(): array
    {
        return [
            'date_added' => 'datetime',
        ];
    }

    /**
     * Return null when a segment is the placeholder so the UI never displays "No Segment".
     */
    public function getSegmentAttribute(): null
    {
        return null;
    }

    public function scopeForClassTermSession(Builder $query, string $class, string $term, string $session): void
    {
        $query->where('class_arm', $class)
            ->where('term', $term)
            ->where('session', $session);
    }

    public function scopeBySegment(Builder $query, string $segment): void
    {
        $query->where('segment', $segment);
    }

    public function scopeApproved(Builder $query): void
    {
        $query->where('status', 1);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'studentId', 'id');
    }

    /**
     * Letter grade when the total is scored out of 100. F for total < 40.
     */
    public function gradeLetter(): string
    {
        $score = (float) $this->total;
        if ($score >= 70) {
            return 'A';
        }
        if ($score >= 60) {
            return 'B';
        }
        if ($score >= 50) {
            return 'C';
        }
        if ($score >= 45) {
            return 'D';
        }
        if ($score >= 40) {
            return 'E';
        }

        return 'F';
    }

    /**
     * Accessor for use in views: $result->grade_letter
     */
    public function getGradeLetterAttribute(): string
    {
        return $this->gradeLetter();
    }
}
