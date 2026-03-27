<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ResetStaffPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'password' => 'required|string|min:8|max:255|confirmed',
        ];
    }
}
