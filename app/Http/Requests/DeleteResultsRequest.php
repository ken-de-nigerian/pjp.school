<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class DeleteResultsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'class' => 'required|string|max:255',
            'term' => 'required|string|max:255',
            'session' => 'required|string|max:255',
            'subjects' => 'required|string|max:255',
        ];
    }
}
