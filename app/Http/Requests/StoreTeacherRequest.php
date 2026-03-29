<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Coercion;
use Illuminate\Foundation\Http\FormRequest;

final class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user('admin');
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'othername' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'string', 'max:20'],
            'formattedPhone' => ['nullable', 'string', 'max:50'],
            'lga' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'employment_date' => ['required', 'date'],
            'form_teacher' => ['nullable', 'integer', 'in:0,1'],
            'assigned_class' => ['required', 'array'],
            'assigned_class.*' => ['string', 'max:50'],
            'subject_to_teach' => ['required', 'array'],
            'subject_to_teach.*' => ['string', 'max:100'],
        ];
    }

    public function passwordPlain(): string
    {
        return Coercion::string(Coercion::stringKeyedMap($this->validated())['password'] ?? '');
    }

    public function registrationFullName(): string
    {
        $v = $this->validated();

        return trim(Coercion::string($v['firstname'] ?? '').' '.Coercion::string($v['lastname'] ?? ''));
    }

    /**
     * @return array<string, mixed>
     */
    public function attributesForTeacherCreate(string $passwordHash, string $imagelocation): array
    {
        $v = $this->validated();

        return [
            'imagelocation' => $imagelocation,
            'email' => Coercion::string($v['email'] ?? ''),
            'password' => $passwordHash,
            'firstname' => Coercion::string($v['firstname'] ?? ''),
            'lastname' => Coercion::string($v['lastname'] ?? ''),
            'othername' => Coercion::string($v['othername'] ?? ''),
            'date_of_birth' => Coercion::string($v['date_of_birth'] ?? ''),
            'gender' => Coercion::string($v['gender'] ?? ''),
            'phone' => Coercion::string($v['formattedPhone'] ?? ''),
            'lga' => Coercion::string($v['lga'] ?? ''),
            'state' => Coercion::string($v['state'] ?? ''),
            'city' => Coercion::string($v['city'] ?? ''),
            'country' => Coercion::string($v['country'] ?? ''),
            'address' => Coercion::string($v['address'] ?? ''),
            'employment_date' => Coercion::string($v['employment_date'] ?? ''),
            'assigned_class' => Coercion::commaSeparatedStrings($v['assigned_class'] ?? []),
            'subject_to_teach' => Coercion::commaSeparatedStrings($v['subject_to_teach'] ?? []),
            'form-teacher' => Coercion::int($v['form_teacher'] ?? 2, 2),
            'registration_date' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
