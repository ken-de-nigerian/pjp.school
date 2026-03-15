<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeTeacherPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('teacher') !== null;
    }

    public function rules(): array
    {
        return [
            'oldPassword' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'confirmPassword' => ['required', 'string', 'same:password'],
        ];
    }

    public function attributes(): array
    {
        return [
            'oldPassword' => 'current password',
            'confirmPassword' => 'password confirmation',
        ];
    }
}
