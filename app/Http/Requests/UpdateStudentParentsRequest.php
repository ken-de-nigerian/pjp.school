<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateStudentParentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null || $this->user('teacher') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'father_name' => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:50',
            'mother_name' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:50',
        ];
    }
}
