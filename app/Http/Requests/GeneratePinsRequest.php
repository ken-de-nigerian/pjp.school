<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Coercion;
use Illuminate\Foundation\Http\FormRequest;

final class GeneratePinsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'session' => 'required|string|max:50',
            'count' => 'required|integer|min:1|max:500',
        ];
    }

    public function sessionValue(): string
    {
        $m = Coercion::stringKeyedMap($this->validated());

        return Coercion::string($m['session'] ?? '');
    }

    public function countValue(): int
    {
        $m = Coercion::stringKeyedMap($this->validated());

        return Coercion::int($m['count'] ?? 0);
    }
}
