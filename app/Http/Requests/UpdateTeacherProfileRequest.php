<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('teacher') !== null;
    }

    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'max:255', 'alpha'],
            'lastname' => ['required', 'string', 'max:255', 'alpha'],
            'formattedPhone' => ['required', 'string', 'max:50'],
            'country' => ['required', 'string', 'max:100'],
            'gender' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'formattedPhone' => 'phone',
        ];
    }
}
