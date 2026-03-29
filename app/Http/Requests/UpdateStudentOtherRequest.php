<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Coercion;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateStudentOtherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null || $this->user('teacher') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'house' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
        ];
    }

    public function houseValue(): string
    {
        return Coercion::string(Coercion::stringKeyedMap($this->validated())['house'] ?? '');
    }

    public function categoryValue(): string
    {
        return Coercion::string(Coercion::stringKeyedMap($this->validated())['category'] ?? '');
    }
}
