<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentSponsorsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null || $this->user('teacher') !== null;
    }

    public function rules(): array
    {
        return [
            'sponsor_name' => 'nullable|string|max:255',
            'sponsor_occupation' => 'nullable|string|max:255',
            'sponsor_phone' => 'nullable|string|max:50',
            'sponsor_address' => 'nullable|string',
            'relationship' => 'nullable|string|max:100',
        ];
    }
}
