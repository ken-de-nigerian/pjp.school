<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrance extends Model
{
    public $timestamps = false;

    protected $table = 'entrance';

    protected $fillable = [];

    /** Order by surname for a list. */
    public function scopeOrdered($query)
    {
        return $query->orderBy('candidates_surname', 'asc');
    }
}
