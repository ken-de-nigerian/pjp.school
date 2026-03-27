<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class EditAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null || $this->user('teacher') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'class' => 'required|string|max:100',
            'term' => 'required|string|max:50',
            'session' => 'required|string|max:50',
            'date' => 'required|string|max:50',
            'updates' => 'required|array|min:1',
            'updates.*.reg_number' => 'required|string|max:100',
            'updates.*.class_roll_call' => 'required|string|in:Present,Absent',
        ];
    }
}
