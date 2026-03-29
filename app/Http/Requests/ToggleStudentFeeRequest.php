<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Coercion;
use Illuminate\Foundation\Http\FormRequest;

final class ToggleStudentFeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'fee_status' => 'required|integer|in:1,2',
        ];
    }

    public function feeStatusValue(): int
    {
        return Coercion::int(Coercion::stringKeyedMap($this->validated())['fee_status'] ?? 0);
    }
}
