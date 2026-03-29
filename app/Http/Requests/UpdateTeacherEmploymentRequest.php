<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Coercion;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateTeacherEmploymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user('admin');
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'assigned_class' => ['required', 'array'],
            'assigned_class.*' => ['string', 'max:50'],
            'subject_to_teach' => ['required', 'array'],
            'subject_to_teach.*' => ['string', 'max:100'],
        ];
    }

    public function assignedClassCsv(): string
    {
        return Coercion::commaSeparatedStrings($this->input('assigned_class'));
    }

    public function subjectToTeachCsv(): string
    {
        return Coercion::commaSeparatedStrings($this->input('subject_to_teach'));
    }
}
