<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApproveResultsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    public function rules(): array
    {
        return [
            'selectedRows' => 'required|array',
            'selectedRows.*' => 'integer|exists:annual_result,id',
        ];
    }
}
