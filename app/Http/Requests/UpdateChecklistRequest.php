<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Term;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateChecklistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:10000'],
            'term' => ['required', 'string', Rule::in(Term::labels())],
            'session' => ['required', 'string', 'regex:/^\d{4}\/\d{4}$/'],
            'is_active' => ['sometimes', 'boolean'],
            'position' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:999999'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false,
            ]);
        }
        if ($this->has('position') && $this->input('position') === '') {
            $this->merge(['position' => null]);
        }
    }
}
