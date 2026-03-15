<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnusedPin extends Model
{
    public $timestamps = false;

    protected $table = 'unused_pins';

    protected $fillable = ['pins', 'session', 'serial_number', 'upload_date'];
}
