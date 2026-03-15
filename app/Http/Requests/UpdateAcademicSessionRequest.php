<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAcademicSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    public function rules(): array
    {
        $id = (int) $this->route('id');

        return [
            'year' => [
                'required',
                'string',
                'max:50',
                Rule::unique('academic_sessions', 'year')->ignore($id),
            ],
        ];
    }
}
