<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Coercion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

final class BulkToggleStudentFeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'fee_status' => 'required|integer|in:0,1',
            'ids' => 'nullable|array',
            'ids.*' => 'integer',
            'class' => 'nullable|string|max:100',
            'entire_class' => 'nullable|boolean',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->boolean('entire_class')) {
                if (empty($this->input('class'))) {
                    $validator->errors()->add('class', 'Class is required when marking entire class.');
                }
            } else {
                $ids = $this->input('ids', []);
                if (! is_array($ids) || count(array_filter($ids, 'is_numeric')) === 0) {
                    $validator->errors()->add('ids', 'Select at least one student.');
                }
            }
        });
    }

    public function feeStatusValue(): int
    {
        return Coercion::int(Coercion::stringKeyedMap($this->validated())['fee_status'] ?? 0);
    }

    public function className(): string
    {
        return Coercion::string(Coercion::stringKeyedMap($this->validated())['class'] ?? '');
    }

    /**
     * @return list<int>
     */
    public function studentIds(): array
    {
        return Coercion::listOfInt($this->input('ids', []));
    }
}
