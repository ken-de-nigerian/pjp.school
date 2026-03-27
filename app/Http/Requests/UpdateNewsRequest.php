<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'newsId' => 'required|integer|exists:news,id',
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'content' => 'required|string',
            'photoimg' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
