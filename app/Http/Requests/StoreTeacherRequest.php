<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user('admin');
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'othername' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'string', 'max:20'],
            'formattedPhone' => ['nullable', 'string', 'max:50'],
            'lga' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'employment_date' => ['required', 'date'],
            'form_teacher' => ['nullable', 'integer', 'in:0,1'],
            'assigned_class' => ['required', 'array'],
            'assigned_class.*' => ['string', 'max:50'],
            'subject_to_teach' => ['required', 'array'],
            'subject_to_teach.*' => ['string', 'max:100'],
        ];
    }
}
