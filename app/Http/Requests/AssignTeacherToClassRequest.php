<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Coercion;
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
            'teachersList' => ['required', 'integer', 'exists:users,id'],
            'assigned_class' => ['required', 'array'],
            'assigned_class.*' => ['string', 'max:50'],
        ];
    }

    public function teacherId(): int
    {
        return Coercion::int(Coercion::stringKeyedMap($this->validated())['teachersList'] ?? 0);
    }

    public function assignedClassCsv(): string
    {
        return Coercion::commaSeparatedStrings($this->input('assigned_class'));
    }
}
