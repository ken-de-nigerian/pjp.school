<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateStudentOtherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null || $this->user('teacher') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'house' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
        ];
    }
}
