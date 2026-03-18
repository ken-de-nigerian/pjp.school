<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $id
 */
class SchoolClass extends Model
{
    public $timestamps = false;

    protected $table = 'classes';

    protected $fillable = ['class_name', 'time_added'];

    protected function casts(): array
    {
        return [
            'time_added' => 'datetime',
        ];
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class', 'class_name');
    }
}
