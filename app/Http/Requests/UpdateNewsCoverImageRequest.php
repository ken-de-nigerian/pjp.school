<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsCoverImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    public function rules(): array
    {
        return [
            'newsId' => 'required|integer|exists:news,id',
            'photoimg' => 'required|file|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
