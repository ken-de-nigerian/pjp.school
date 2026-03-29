<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Coercion;
use Illuminate\Foundation\Http\FormRequest;

final class UploadResultsTermRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null || $this->user('teacher') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'results' => 'required|array',
            'results.*.studentId' => 'required',
            'results.*.class' => 'required|string|max:100',
            'results.*.term' => 'required|string|max:50',
            'results.*.session' => 'required|string|max:50',
            'results.*.subjects' => 'required|string|max:255',
            'results.*.name' => 'required|string|max:255',
            'results.*.reg_number' => 'required|string|max:50',
            'results.*.ca' => 'required|numeric|min:0|max:15',
            'results.*.assignment' => 'required|numeric|min:0|max:25',
            'results.*.exam' => 'required|numeric|min:0|max:60',
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function resultRows(): array
    {
        $validated = Coercion::stringKeyedMap($this->validated());

        return Coercion::listOfStringKeyedMaps($validated['results'] ?? []);
    }
}
