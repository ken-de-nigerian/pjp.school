<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Coercion;
use Illuminate\Foundation\Http\FormRequest;

final class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null || $this->user('teacher') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'attendance' => 'required|array',
            'attendance.*.class' => 'required|string|max:100',
            'attendance.*.term' => 'required|string|max:50',
            'attendance.*.session' => 'required|string|max:50',
            'attendance.*.name' => 'required|string|max:255',
            'attendance.*.reg_number' => 'required|string|max:50',
            'attendance.*.class_roll_call' => 'required|string|max:50',
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function attendanceRows(): array
    {
        return Coercion::listOfStringKeyedMaps($this->validated('attendance'));
    }
}
