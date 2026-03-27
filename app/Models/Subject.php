<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $subject_name
 * @property mixed $grade
 * @property mixed $id
 */
class Subject extends Model
{
    public $timestamps = false;

    protected $table = 'subjects';

    protected $fillable = ['subject_name', 'grade'];

    /** @param Builder<Subject> $query */
    public function scopeJunior(Builder $query): void
    {
        $query->where('grade', 'Junior');
    }

    /** @param Builder<Subject> $query */
    public function scopeSenior(Builder $query): void
    {
        $query->where('grade', 'Senior');
    }
}
