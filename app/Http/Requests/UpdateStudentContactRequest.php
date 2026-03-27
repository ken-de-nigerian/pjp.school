<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateStudentContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null || $this->user('teacher') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'lga' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'nationality' => 'required|string|max:100',
            'address' => 'required|string',
        ];
    }
}
