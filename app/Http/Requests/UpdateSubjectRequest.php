<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user('admin');
    }

    public function rules(): array
    {
        $id = (int) $this->route('id');
        return [
            'subject_name' => ['required', 'string', 'max:255'],
            'grade' => ['required', 'string', 'in:Junior,Senior'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $id = (int) $this->route('id');
            $exists = \App\Models\Subject::query()
                ->where('subject_name', $this->input('subject_name'))
                ->where('grade', $this->input('grade'))
                ->where('id', '!=', $id)
                ->exists();
            if ($exists) {
                $validator->errors()->add('subject_name', $this->input('subject_name') . ' has already been added for this class.');
            }
        });
    }
}
