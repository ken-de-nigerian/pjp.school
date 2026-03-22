<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    /**
     * @deprecated Segment is no longer used; do not filter by segment in new code.
     */
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

    protected function gradeLetter(): Attribute
    {
        return Attribute::make(
            get: function () {
                $score = (float) $this->total;

                return match (true) {
                    $score >= 70 => 'A',
                    $score >= 60 => 'B',
                    $score >= 50 => 'C',
                    $score >= 45 => 'D',
                    $score >= 40 => 'E',
                    default => 'F',
                };
            }
        );
    }

    protected function resultRemarks(): Attribute
    {
        return Attribute::make(
            get: function () {
                $score = (float) $this->total;

                return match (true) {
                    $score >= 80 => 'Excellent',
                    $score >= 70 => 'V.Good',
                    $score >= 60 => 'Good',
                    $score >= 50 => 'Fair',
                    $score >= 40 => 'Poor',
                    default => 'V.Poor',
                };
            }
        );
    }
}
