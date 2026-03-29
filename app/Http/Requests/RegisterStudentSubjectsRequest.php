<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Coercion;
use Illuminate\Foundation\Http\FormRequest;

final class RegisterStudentSubjectsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user('admin');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'studentsList' => ['required', 'string', 'max:255'],
            'subjectsList' => ['required', 'array', 'min:1'],
            'subjectsList.*' => ['string', 'max:100'],
        ];
    }

    public function studentsListString(): string
    {
        return Coercion::string(Coercion::stringKeyedMap($this->validated())['studentsList'] ?? '');
    }

    public function subjectsListCsv(): string
    {
        $v = $this->validated();
        $raw = $v['subjectsList'] ?? [];

        return Coercion::commaSeparatedStrings($raw);
    }
}
