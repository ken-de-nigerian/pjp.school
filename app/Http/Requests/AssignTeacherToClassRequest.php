<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class AssignTeacherToClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user('admin');
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'teachersList' => ['required', 'string'],
            'assigned_class' => ['required', 'array'],
            'assigned_class.*' => ['string', 'max:50'],
        ];
    }
}
