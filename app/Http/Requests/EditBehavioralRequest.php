<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditBehavioralRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null || $this->user('teacher') !== null;
    }

    public function rules(): array
    {
        return [
            'reg_number' => 'required|string|max:50',
            'class' => 'required|string|max:100',
            'term' => 'required|string|max:50',
            'session' => 'required|string|max:50',
            'neatness' => 'required|string|max:255',
            'music' => 'required|string|max:255',
            'sports' => 'required|string|max:255',
            'attentiveness' => 'required|string|max:255',
            'punctuality' => 'required|string|max:255',
            'health' => 'required|string|max:255',
            'politeness' => 'required|string|max:255',
        ];
    }
}
