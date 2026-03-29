<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Coercion;
use Illuminate\Foundation\Http\FormRequest;

final class ToggleStudentStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'status' => 'required|integer|in:1,2',
            'class_arm' => 'required|string|max:50',
        ];
    }

    public function statusValue(): int
    {
        return Coercion::int(Coercion::stringKeyedMap($this->validated())['status'] ?? 0);
    }
}
