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
 * @property string $reg_number
 * @property int $id
 * @property mixed $imagelocation
 * @property mixed $dob
 * @property mixed $gender
 * @property mixed $contact_phone
 * @property string|null $subjects
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
 * @property int|string $fee_status
 * @property mixed $time_of_reg
 * @property mixed $left_school_date
 * @property mixed $graduation_date
 */
class Student extends Model
{
    /** Year bucket when a class-based leaver has no usable dates (route/query param value). */
    public const LEFT_SCHOOL_UNDATED_YEAR = 'undated';

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

    /** @return HasMany<AnnualResult, $this> */
    public function annualResults(): HasMany
    {
        return $this->hasMany(AnnualResult::class, 'reg_number', 'reg_number');
    }

    /** @return HasMany<Position, $this> */
    public function positions(): HasMany
    {
        return $this->hasMany(Position::class, 'reg_number', 'reg_number');
    }

    /** @return HasMany<Behavioral, $this> */
    public function behavioralRecords(): HasMany
    {
        return $this->hasMany(Behavioral::class, 'reg_number', 'reg_number');
    }

    /** @return HasMany<AttendanceRecord, $this> */
    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class, 'reg_number', 'reg_number');
    }

    /** @param Builder<Student> $query */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', 2);
    }

    /**
     * Exclude students whose `class` column marks them as no longer on the active roster.
     * Normalizes with LOWER/TRIM so Left, LEFT, left-school, Graduated, etc. are all excluded.
     *
     * @param Builder<Student> $query
     */
    public function scopeNotLeftOrGraduated(Builder $query): void
    {
        $column = $query->getModel()->qualifyColumn('class');
        $query->whereRaw(
            sprintf('LOWER(TRIM(COALESCE(%s, \'\'))) NOT IN (?, ?, ?, ?)', $column),
            ['left', 'left-school', 'left school', 'graduated']
        );
    }

    /**
     * `Class` column indicates the student left (not graduated). Matches Left, LEFT, left-school, etc.
     *
     * @param Builder<Student> $query
     */
    public function scopeClassIndicatesLeftSchool(Builder $query): void
    {
        $column = $query->getModel()->qualifyColumn('class');
        $query->whereRaw(
            sprintf('LOWER(TRIM(COALESCE(%s, \'\'))) IN (?, ?, ?)', $column),
            ['left', 'left-school', 'left school']
        );
    }

    /**
     * @param Builder<Student> $query
     */
    public function scopeExcludeGraduatedClass(Builder $query): void
    {
        $column = $query->getModel()->qualifyColumn('class');
        $query->whereRaw(
            sprintf('LOWER(TRIM(COALESCE(%s, \'\'))) <> ?', $column),
            ['graduated']
        );
    }

    /** @param Builder<Student> $query */
    public function scopeByClass(Builder $query, string $class): void
    {
        $query->where('class', $class);
    }

    /** @param Builder<Student> $query */
    public function scopeByClassArm(Builder $query, string $classArm): void
    {
        $query->where('class_arm', $classArm);
    }

    /** @param Builder<Student> $query */
    public function scopeByHouse(Builder $query, string $house): void
    {
        $query->where('house', $house);
    }

    public static function deriveClassArm(string $class): string
    {
        return ClassArm::fromClass($class);
    }
}
