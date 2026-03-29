<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Student;
use App\Support\Coercion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateStudentAcademicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null || $this->user('teacher') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $student = $this->route('student');
        $regNumberRules = ['required', 'string', 'max:50'];
        $regNumberRules[] = $student instanceof Student
            ? Rule::unique('students', 'reg_number')->ignore($student)
            : Rule::unique('students', 'reg_number');

        return [
            'class' => 'required|string|max:100',
            'subjects' => 'required|string|max:500',
            'reg_number' => $regNumberRules,
        ];
    }

    public function className(): string
    {
        return Coercion::string(Coercion::stringKeyedMap($this->validated())['class'] ?? '');
    }

    public function subjectsValue(): string
    {
        return Coercion::string(Coercion::stringKeyedMap($this->validated())['subjects'] ?? '');
    }

    public function regNumber(): string
    {
        return Coercion::string(Coercion::stringKeyedMap($this->validated())['reg_number'] ?? '');
    }
}
