<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
