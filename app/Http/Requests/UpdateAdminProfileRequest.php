<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    public function rules(): array
    {
        return [
            'fullName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'formattedPhone' => ['required', 'string', 'max:50'],
        ];
    }

    public function attributes(): array
    {
        return [
            'fullName' => 'full name',
            'formattedPhone' => 'phone',
        ];
    }
}
