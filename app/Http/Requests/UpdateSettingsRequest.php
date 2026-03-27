<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'slogan' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:1000',
            'term' => 'sometimes|string|max:50',
            'session' => 'sometimes|string|max:50',
            'closed' => 'sometimes|string|max:255',
            'resumption' => 'sometimes|string|max:255',
            'timezone' => 'sometimes|string|max:100',
            'scratch_card' => 'sometimes|integer|in:0,1',
            'bulk_sms' => 'sometimes|integer|in:0,1',
            'maintenance_mode' => 'sometimes|integer|in:0,1',
        ];
    }
}
