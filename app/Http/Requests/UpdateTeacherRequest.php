<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Coercion;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user('admin');
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'othername' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'date_of_birth' => ['required', 'date'],
            'employment_date' => ['required', 'date'],
            'gender' => ['required', 'string', 'max:20'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function profileUpdateAttributes(): array
    {
        $v = $this->validated();

        return [
            'firstname' => Coercion::string($v['firstname'] ?? ''),
            'lastname' => Coercion::string($v['lastname'] ?? ''),
            'othername' => Coercion::string($v['othername'] ?? ''),
            'email' => Coercion::string($v['email'] ?? ''),
            'phone' => Coercion::string($v['phone'] ?? ''),
            'date_of_birth' => Coercion::string($v['date_of_birth'] ?? ''),
            'employment_date' => Coercion::string($v['employment_date'] ?? ''),
            'gender' => Coercion::string($v['gender'] ?? ''),
        ];
    }
}
