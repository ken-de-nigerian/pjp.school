<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\FeeCategoryEnum;
use App\Enums\Term;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreFeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'amount' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'category' => ['required', 'string', Rule::in(FeeCategoryEnum::values())],
            'term' => ['required', 'string', Rule::in(Term::labels())],
            'session' => ['required', 'string', 'regex:/^\d{4}\/\d{4}$/'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false,
            ]);
        }
    }
}
