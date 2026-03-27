<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Subject;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

final class UpdateSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user('admin');
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'subject_name' => ['required', 'string', 'max:255'],
            'grade' => ['required', 'string', 'in:Junior,Senior'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $id = (int) $this->route('id');
            $exists = Subject::query()
                ->where('subject_name', $this->input('subject_name'))
                ->where('grade', $this->input('grade'))
                ->where('id', '!=', $id)
                ->exists();
            if ($exists) {
                $validator->errors()->add('subject_name', $this->input('subject_name').' has already been added for this class.');
            }
        });
    }
}
