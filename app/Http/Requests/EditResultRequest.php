<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null || $this->user('teacher') !== null;
    }

    public function rules(): array
    {
        return [
            'studentId' => 'required',
            'class' => 'required|string|max:100',
            'term' => 'required|string|max:50',
            'session' => 'required|string|max:50',
            'subjects' => 'required|string|max:255',
            'reg_number' => 'required|string|max:50',
            'ca' => 'required|numeric|min:0',
            'assignment' => 'required|numeric|min:0',
            'exam' => 'required|numeric|min:0',
        ];
    }
}
