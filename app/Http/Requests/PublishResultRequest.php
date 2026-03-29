<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Coercion;
use Illuminate\Foundation\Http\FormRequest;

final class PublishResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'class' => 'required|string|max:100',
            'term' => 'required|string|max:50',
            'session' => 'required|string|max:50',
        ];
    }

    /**
     * @return array{class: string, term: string, session: string}
     */
    public function classTermSession(): array
    {
        return Coercion::classTermSessionFromValidated(Coercion::stringKeyedMap($this->validated()));
    }
}
