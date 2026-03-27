<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $reg_number
 * @property mixed $used_count
 */
class UsedPin extends Model
{
    public $timestamps = false;

    protected $table = 'used_pins';

    protected $fillable = [
        'pins',
        'reg_number',
        'used_count',
        'class',
        'term',
        'session',
        'time_used',
    ];

    protected function casts(): array
    {
        return [
            'time_used' => 'datetime',
        ];
    }

    /** @return BelongsTo<Student, $this> */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'reg_number', 'reg_number');
    }
}
