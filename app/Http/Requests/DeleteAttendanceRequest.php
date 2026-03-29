<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Coercion;
use Illuminate\Foundation\Http\FormRequest;

final class DeleteAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'reg_number' => 'nullable|string|max:50',
            'class' => 'required|string|max:100',
            'term' => 'required|string|max:50',
            'session' => 'required|string|max:50',
            'date' => 'required|string|max:100',
        ];
    }

    /**
     * @return array{class: string, term: string, session: string, date: string, reg_number: string|null}
     */
    public function deleteContext(): array
    {
        $v = $this->validated();
        $reg = $v['reg_number'] ?? null;

        return [
            'class' => Coercion::string($v['class'] ?? ''),
            'term' => Coercion::string($v['term'] ?? ''),
            'session' => Coercion::string($v['session'] ?? ''),
            'date' => Coercion::string($v['date'] ?? ''),
            'reg_number' => is_string($reg) && $reg !== '' ? $reg : null,
        ];
    }
}
