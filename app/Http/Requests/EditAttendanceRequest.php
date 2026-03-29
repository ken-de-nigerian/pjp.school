<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Coercion;
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

    /**
     * @return array{class: string, term: string, session: string, date: string}
     */
    public function attendanceContext(): array
    {
        $v = $this->validated();

        return [
            'class' => Coercion::string($v['class'] ?? ''),
            'term' => Coercion::string($v['term'] ?? ''),
            'session' => Coercion::string($v['session'] ?? ''),
            'date' => Coercion::string($v['date'] ?? ''),
        ];
    }

    /**
     * @return list<array{reg_number: string, class_roll_call: string}>
     */
    public function attendanceUpdates(): array
    {
        $v = $this->validated();
        $rows = Coercion::listOfStringKeyedMaps($v['updates'] ?? []);
        $out = [];
        foreach ($rows as $row) {
            $reg = Coercion::string($row['reg_number'] ?? '');
            if ($reg === '') {
                continue;
            }

            $out[] = [
                'reg_number' => $reg,
                'class_roll_call' => Coercion::string($row['class_roll_call'] ?? ''),
            ];
        }

        return $out;
    }
}
