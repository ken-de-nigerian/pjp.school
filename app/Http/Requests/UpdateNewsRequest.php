<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    public function rules(): array
    {
        return [
            'newsId' => 'sometimes|nullable',
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'message' => 'required|string',
            'content' => 'sometimes|string',
            'photoimg' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('content') && !$this->has('message')) {
            $this->merge(['message' => $this->input('content')]);
        }
    }
}
