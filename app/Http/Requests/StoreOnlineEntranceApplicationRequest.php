<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreOnlineEntranceApplicationRequest extends FormRequest
{
    private const NIGERIAN_STATES = [
        'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno',
        'Cross River', 'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'Gombe', 'Imo', 'Jigawa',
        'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Kogi', 'Kwara', 'Lagos', 'Nasarawa', 'Niger',
        'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau', 'Rivers', 'Sokoto', 'Taraba', 'Yobe', 'Zamfara', 'FCT',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $phone = ['nullable', 'string', 'max:30', 'regex:/^[0-9+\s\-()]{7,20}$/'];
        $phoneRequired = ['required', 'string', 'max:30', 'regex:/^[0-9+\s\-()]{7,20}$/'];

        return [
            'surname' => ['required', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'dob' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'place_of_birth' => ['required', 'string', 'max:255'],
            'gender' => ['required', Rule::in(['Male', 'Female'])],
            'country' => ['required', 'string', 'max:255'],
            'state' => ['required', Rule::in(self::NIGERIAN_STATES)],
            'lga' => ['required', 'string', 'max:255'],
            'town' => ['required', 'string', 'max:255'],
            'village' => ['nullable', 'string', 'max:255'],
            'current_school' => ['required', 'string', 'max:255'],
            'current_class' => ['required', 'string', 'max:50'],
            'applying_for' => ['required', Rule::in(['JSS 1', 'JSS 2', 'JSS 3', 'SSS 1'])],
            'has_leaving_cert' => ['required', Rule::in(['Yes', 'No'])],
            'blood_group' => ['required', Rule::in(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])],
            'disability' => ['nullable', 'string', 'max:255'],
            'special_care' => ['nullable', 'string', 'max:255'],
            'father_surname' => ['required', 'string', 'max:255'],
            'father_firstname' => ['required', 'string', 'max:255'],
            'father_middlename' => ['nullable', 'string', 'max:255'],
            'father_occupation' => ['required', 'string', 'max:255'],
            'father_address' => ['required', 'string', 'max:255'],
            'father_phone' => $phoneRequired,
            'mother_surname' => ['required', 'string', 'max:255'],
            'mother_firstname' => ['required', 'string', 'max:255'],
            'mother_middlename' => ['nullable', 'string', 'max:255'],
            'mother_occupation' => ['required', 'string', 'max:255'],
            'mother_address' => ['required', 'string', 'max:255'],
            'mother_phone' => $phoneRequired,
            'guardian_surname' => ['nullable', 'string', 'max:255'],
            'guardian_firstname' => ['nullable', 'string', 'max:255'],
            'guardian_middlename' => ['nullable', 'string', 'max:255'],
            'guardian_occupation' => ['nullable', 'string', 'max:255'],
            'guardian_address' => ['nullable', 'string', 'max:255'],
            'guardian_phone' => $phone,
            'declaration' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'declaration.accepted' => 'You must agree to the declaration before submitting.',
            'father_phone.regex' => 'Please enter a valid phone number for the father.',
            'mother_phone.regex' => 'Please enter a valid phone number for the mother.',
            'guardian_phone.regex' => 'Please enter a valid phone number for the guardian.',
        ];
    }
}
