<?php

namespace App\Models;

use App\Helpers\ClassArm;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $class
 * @property mixed $firstname
 * @property mixed $lastname
 * @property mixed $othername
 * @property mixed $reg_number
 * @property mixed $id
 * @property mixed $imagelocation
 * @property mixed $dob
 * @property mixed $gender
 * @property mixed $contact_phone
 * @property mixed $subjects
 * @property mixed $lga
 * @property mixed $state
 * @property mixed $city
 * @property mixed $address
 * @property mixed $father_name
 * @property mixed $father_occupation
 * @property mixed $father_phone
 * @property mixed $mother_name
 * @property mixed $mother_occupation
 * @property mixed $mother_phone
 * @property mixed $sponsor_name
 * @property mixed $sponsor_occupation
 * @property mixed $sponsor_phone
 * @property mixed $relationship
 * @property mixed $sponsor_address
 * @property mixed $house
 * @property mixed $category
 * @property mixed $fee_status
 * @property mixed $time_of_reg
 * @property mixed $left_school_date
 * @property mixed $graduation_date
 */
class Student extends Model
{
    public $timestamps = false;

    protected $table = 'students';

    protected $fillable = [
        'reg_number',
        'firstname',
        'lastname',
        'othername',
        'dob',
        'gender',
        'class',
        'class_arm',
        'subjects',
        'status',
        'fee_status',
        'house',
        'category',
        'imagelocation',
        'contact_phone',
        'lga',
        'state',
        'city',
        'nationality',
        'address',
        'father_name',
        'father_occupation',
        'father_phone',
        'mother_name',
        'mother_occupation',
        'mother_phone',
        'sponsor_name',
        'sponsor_occupation',
        'sponsor_phone',
        'sponsor_address',
        'relationship',
        'time_of_reg',
        'left_school_date',
        'graduation_date',
    ];

    protected function casts(): array
    {
        return [
            'time_of_reg' => 'datetime',
            'left_school_date' => 'datetime',
            'graduation_date' => 'datetime',
        ];
    }

    public function annualResults(): HasMany
    {
        return $this->hasMany(AnnualResult::class, 'reg_number', 'reg_number');
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class, 'reg_number', 'reg_number');
    }

    public function behavioralRecords(): HasMany
    {
        return $this->hasMany(Behavioral::class, 'reg_number', 'reg_number');
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class, 'reg_number', 'reg_number');
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('status', 2);
    }

    public function scopeNotLeftOrGraduated(Builder $query): void
    {
        $query->whereNotIn('class', ['Left', 'Graduated']);
    }

    public function scopeByClass(Builder $query, string $class): void
    {
        $query->where('class', $class);
    }

    public function scopeByClassArm(Builder $query, string $classArm): void
    {
        $query->where('class_arm', $classArm);
    }

    public function scopeByHouse(Builder $query, string $house): void
    {
        $query->where('house', $house);
    }

    public static function deriveClassArm(string $class): string
    {
        return ClassArm::fromClass($class);
    }
}
