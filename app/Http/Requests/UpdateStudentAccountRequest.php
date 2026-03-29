<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Coercion;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateStudentAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null || $this->user('teacher') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'othername' => 'required|string|max:255',
            'dob' => 'required|string|max:50',
            'gender' => 'nullable|string|max:50',
            'contact_phone' => 'required|string|max:50',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function accountPayload(): array
    {
        return Coercion::stringKeyedMap($this->validated());
    }
}
