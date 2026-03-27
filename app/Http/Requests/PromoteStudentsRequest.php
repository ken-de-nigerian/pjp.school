<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class PromoteStudentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'from_class' => 'required|string|max:100|different:to_class',
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'integer|exists:students,id',
            'to_class' => 'required|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'from_class.different' => "You can't promote students to the same class.",
            'student_ids.required' => 'Please select at least one student to promote.',
        ];
    }
}
