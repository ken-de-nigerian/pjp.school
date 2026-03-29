<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Coercion;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property mixed $firstname
 * @property mixed $lastname
 * @property mixed $id
 */
class Teacher extends Authenticatable
{
    public $timestamps = false;

    protected $table = 'users';

    public const UPDATED_AT = null;

    public const CREATED_AT = null;

    protected $fillable = [
        'email',
        'firstname',
        'lastname',
        'othername',
        'phone',
        'address',
        'country',
        'gender',
        'lga',
        'state',
        'city',
        'date_of_birth',
        'employment_date',
        'assigned_class',
        'subject_to_teach',
        'imagelocation',
        'password',
        'registration_date',
        'password_change_date',
        'form-teacher',
        'modify_results',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'registration_date' => 'datetime',
        ];
    }

    public function getNameAttribute(): string
    {
        return trim(Coercion::string($this->firstname).' '.Coercion::string($this->lastname));
    }
}
