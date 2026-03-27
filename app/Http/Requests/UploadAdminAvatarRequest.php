<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UploadAdminAvatarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    /** @return array<string, mixed> */
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
