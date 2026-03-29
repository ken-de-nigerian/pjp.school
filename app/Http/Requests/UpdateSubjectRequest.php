<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Subject;
use App\Support\Coercion;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

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
            $id = Coercion::int($this->route('id'));
            $name = Coercion::string($this->input('subject_name'));
            $grade = Coercion::string($this->input('grade'));
            $exists = Subject::query()
                ->where('subject_name', $name)
                ->where('grade', $grade)
                ->where('id', '!=', $id)
                ->exists();
            if ($exists) {
                $validator->errors()->add('subject_name', $name.' has already been added for this class.');
            }
        });
    }
}
