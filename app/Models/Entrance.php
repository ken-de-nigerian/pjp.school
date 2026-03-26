<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** @property string $uniqueID
 * @property mixed $payment_status
 */
class Entrance extends Model
{
    public $timestamps = false;

    protected $table = 'entrance';

    protected $fillable = [
        'uniqueID',
        'candidates_surname',
        'candidates_firstname',
        'candidates_middlename',
        'candidates_date_of_birth',
        'candidates_place_of_birth',
        'candidates_nationality',
        'states',
        'candidates_lga',
        'candidates_town',
        'candidates_village',
        'selectgender',
        'candidates_current_school',
        'candidates_current_class',
        'applying_for',
        'certificate',
        'blood_group',
        'disability',
        'sickness',
        'fathers_surname',
        'fathers_firstname',
        'fathers_middlename',
        'fathers_occupation',
        'fathers_address',
        'fathers_phone',
        'mothers_surname',
        'mothers_firstname',
        'mothers_middlename',
        'mothers_occupation',
        'mothers_address',
        'mothers_phone',
        'guardians_surname',
        'guardians_firstname',
        'guardians_middlename',
        'guardians_occupation',
        'guardians_address',
        'guardians_phone',
        'payment_mode',
        'payment_status',
    ];

    /** Order by surname for a list. */
    public function scopeOrdered($query)
    {
        return $query->orderBy('candidates_surname', 'asc');
    }
}
