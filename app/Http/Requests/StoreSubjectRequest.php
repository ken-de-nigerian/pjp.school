<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Subject;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user('admin');
    }

    public function rules(): array
    {
        return [
            'subject_name' => ['required', 'string', 'max:255'],
            'grade' => ['required', 'string', 'in:Junior,Senior'],
        ];
    }

    /**
     * Unique subject_name + grade (no duplicate for same grade).
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $exists = Subject::query()
                ->where('subject_name', $this->input('subject_name'))
                ->where('grade', $this->input('grade'))
                ->exists();
            if ($exists) {
                $validator->errors()->add('subject_name', 'This subject has already been registered.');
            }
        });
    }
}
