<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class DeleteAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'reg_number' => 'nullable|string|max:50',
            'class' => 'required|string|max:100',
            'term' => 'required|string|max:50',
            'session' => 'required|string|max:50',
            'date' => 'required|string|max:100',
        ];
    }
}
