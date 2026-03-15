<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PinCode extends Model
{
    public $timestamps = false;

    protected $table = 'pin_code';

    protected $fillable = ['pin', 'session', 'serial_number', 'upload_date'];
}
