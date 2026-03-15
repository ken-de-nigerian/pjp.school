<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromoteStudentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    public function rules(): array
    {
        return [
            'from_class' => 'required|string|max:100|different:to_class',
            'to_class' => 'required|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'from_class.different' => "You can't promote students to the same class.",
        ];
    }
}
