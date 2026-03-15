<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmailTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'subject' => 'required|string|max:500',
            'email_body' => 'required|string',
            'email_status' => 'nullable|in:0,1',
        ];
    }
}
