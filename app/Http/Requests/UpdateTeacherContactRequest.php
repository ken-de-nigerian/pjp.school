<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Coercion;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateTeacherContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user('admin');
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'lga' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function contactUpdateAttributes(): array
    {
        $v = $this->validated();

        return [
            'lga' => Coercion::string($v['lga'] ?? ''),
            'state' => Coercion::string($v['state'] ?? ''),
            'city' => Coercion::string($v['city'] ?? ''),
            'country' => Coercion::string($v['country'] ?? ''),
            'address' => Coercion::string($v['address'] ?? ''),
        ];
    }
}
