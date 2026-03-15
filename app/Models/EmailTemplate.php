<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    public $timestamps = false;

    protected $table = 'email_templates';

    protected $fillable = ['name', 'subject', 'email_body', 'email_status', 'created_at'];
}
