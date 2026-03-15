<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    public $timestamps = false;

    protected $table = 'subjects';

    protected $fillable = ['subject_name', 'grade'];

    public function scopeJunior(Builder $query): void
    {
        $query->where('grade', 'Junior');
    }

    public function scopeSenior(Builder $query): void
    {
        $query->where('grade', 'Senior');
    }
}
