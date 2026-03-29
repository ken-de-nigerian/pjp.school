<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Coercion;
use Illuminate\Foundation\Http\FormRequest;

final class EditBehavioralRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null || $this->user('teacher') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'reg_number' => 'required|string|max:50',
            'class' => 'required|string|max:100',
            'term' => 'required|string|max:50',
            'session' => 'required|string|max:50',
            'neatness' => 'required|string|max:255',
            'music' => 'required|string|max:255',
            'sports' => 'required|string|max:255',
            'attentiveness' => 'required|string|max:255',
            'punctuality' => 'required|string|max:255',
            'health' => 'required|string|max:255',
            'politeness' => 'required|string|max:255',
        ];
    }

    /**
     * @return array{
     *     reg_number: string,
     *     class: string,
     *     term: string,
     *     session: string,
     *     neatness: string,
     *     music: string,
     *     sports: string,
     *     attentiveness: string,
     *     punctuality: string,
     *     health: string,
     *     politeness: string
     * }
     */
    public function editPayload(): array
    {
        $v = Coercion::stringKeyedMap($this->validated());

        return [
            'reg_number' => Coercion::string($v['reg_number'] ?? ''),
            'class' => Coercion::string($v['class'] ?? ''),
            'term' => Coercion::string($v['term'] ?? ''),
            'session' => Coercion::string($v['session'] ?? ''),
            'neatness' => Coercion::string($v['neatness'] ?? ''),
            'music' => Coercion::string($v['music'] ?? ''),
            'sports' => Coercion::string($v['sports'] ?? ''),
            'attentiveness' => Coercion::string($v['attentiveness'] ?? ''),
            'punctuality' => Coercion::string($v['punctuality'] ?? ''),
            'health' => Coercion::string($v['health'] ?? ''),
            'politeness' => Coercion::string($v['politeness'] ?? ''),
        ];
    }
}
