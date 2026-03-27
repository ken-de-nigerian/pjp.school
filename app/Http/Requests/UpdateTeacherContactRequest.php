<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateTeacherContactRequest extends FormRequest
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
            'lga' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:500'],
        ];
    }
}
