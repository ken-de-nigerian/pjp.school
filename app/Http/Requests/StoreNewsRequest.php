<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'content' => 'required|string',
            'message' => 'sometimes|string',
            'photoimg' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('message') && !$this->has('content')) {
            $this->merge(['content' => $this->input('message')]);
        }
    }
}
