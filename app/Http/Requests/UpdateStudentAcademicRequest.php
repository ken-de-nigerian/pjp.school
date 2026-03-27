<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateStudentAcademicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null || $this->user('teacher') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'class' => 'required|string|max:100',
            'subjects' => 'required|string|max:500',
            'reg_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('students', 'reg_number')->ignore($this->route('id')),
            ],
        ];
    }
}
