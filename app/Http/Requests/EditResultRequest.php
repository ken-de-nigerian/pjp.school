<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Coercion;
use Illuminate\Foundation\Http\FormRequest;

final class EditResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null || $this->user('teacher') !== null;
    }

    /** @return array<string, mixed> */
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

    /**
     * @return array{
     *     studentId: string,
     *     class: string,
     *     term: string,
     *     session: string,
     *     subjects: string,
     *     reg_number: string,
     *     ca: float,
     *     assignment: float,
     *     exam: float
     * }
     */
    public function editPayload(): array
    {
        $v = Coercion::stringKeyedMap($this->validated());

        return [
            'studentId' => Coercion::string($v['studentId'] ?? ''),
            'class' => Coercion::string($v['class'] ?? ''),
            'term' => Coercion::string($v['term'] ?? ''),
            'session' => Coercion::string($v['session'] ?? ''),
            'subjects' => Coercion::string($v['subjects'] ?? ''),
            'reg_number' => Coercion::string($v['reg_number'] ?? ''),
            'ca' => Coercion::float($v['ca'] ?? 0),
            'assignment' => Coercion::float($v['assignment'] ?? 0),
            'exam' => Coercion::float($v['exam'] ?? 0),
        ];
    }
}
