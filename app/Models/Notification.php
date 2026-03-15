<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = false;

    protected $table = 'notifications';

    protected $fillable = ['title', 'message', 'date_added'];

    protected function casts(): array
    {
        return [
            'date_added' => 'datetime',
        ];
    }
}
