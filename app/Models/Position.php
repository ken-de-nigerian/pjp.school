<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    public $timestamps = false;

    protected $table = 'positions';

    protected $fillable = [
        'reg_number',
        'name',
        'class',
        'term',
        'session',
        'students_sub_total',
        'students_sub_average',
        'class_position',
        'date_added',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_added' => 'datetime',
        ];
    }

    public function scopeForClassTermSession(Builder $query, string $class, string $term, string $session): void
    {
        $query->where('class', $class)
            ->where('term', $term)
            ->where('session', $session);
    }
}
