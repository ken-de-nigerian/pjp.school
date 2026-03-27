<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class DeleteClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:classes,id',
            'class_name' => 'required|string|max:100',
        ];
    }
}
