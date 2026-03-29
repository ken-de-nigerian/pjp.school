<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Coercion;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateStudentSponsorsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null || $this->user('teacher') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'sponsor_name' => 'nullable|string|max:255',
            'sponsor_occupation' => 'nullable|string|max:255',
            'sponsor_phone' => 'nullable|string|max:50',
            'sponsor_address' => 'nullable|string',
            'relationship' => 'nullable|string|max:100',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function sponsorsPayload(): array
    {
        return Coercion::stringKeyedMap($this->validated());
    }
}
