<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBehavioralRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null || $this->user('teacher') !== null;
    }

    public function rules(): array
    {
        return [
            'students' => 'required|array',
            'students.*.class' => 'required|string|max:100',
            'students.*.term' => 'required|string|max:50',
            'students.*.session' => 'required|string|max:50',
            'students.*.segment' => 'required|string|max:50',
            'students.*.name' => 'required|string|max:255',
            'students.*.reg_number' => 'required|string|max:50',
            'students.*.neatness' => 'nullable|string|max:255',
            'students.*.music' => 'nullable|string|max:255',
            'students.*.sports' => 'nullable|string|max:255',
            'students.*.attentiveness' => 'nullable|string|max:255',
            'students.*.punctuality' => 'nullable|string|max:255',
            'students.*.health' => 'nullable|string|max:255',
            'students.*.politeness' => 'nullable|string|max:255',
        ];
    }
}
