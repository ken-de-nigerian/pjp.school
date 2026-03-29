<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Coercion;
use Illuminate\Foundation\Http\FormRequest;

final class UploadStudentProfilePhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'studentId' => ['required', 'integer', 'exists:students,id'],
            'photoimg' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'photoimg' => 'profile photo',
            'studentId' => 'student',
        ];
    }

    public function studentId(): int
    {
        return Coercion::int(Coercion::stringKeyedMap($this->validated())['studentId'] ?? 0);
    }
}
