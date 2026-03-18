<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DemoteStudentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

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
            'from_class.different' => "You can't demote students to the same class.",
            'student_ids.required' => 'Please select at least one student to demote.',
        ];
    }
}
