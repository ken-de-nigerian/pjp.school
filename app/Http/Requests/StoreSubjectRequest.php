<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Subject;
use App\Support\Coercion;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

final class StoreSubjectRequest extends FormRequest
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

    /**
     * Unique subject_name + grade (no duplicate for same grade).
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $exists = Subject::query()
                ->where('subject_name', Coercion::string($this->input('subject_name')))
                ->where('grade', Coercion::string($this->input('grade')))
                ->exists();
            if ($exists) {
                $validator->errors()->add('subject_name', 'This subject has already been registered.');
            }
        });
    }
}
