<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadTeacherAvatarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('teacher') !== null;
    }

    public function rules(): array
    {
        return [
            'photoimg' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'photoimg' => 'profile photo',
        ];
    }
}
