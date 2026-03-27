<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ResetTeacherPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user('admin');
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'userId' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }
}
