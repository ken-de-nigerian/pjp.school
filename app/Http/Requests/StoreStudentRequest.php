<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    public function rules(): array
    {
        return [
            'reg_number' => 'required|string|max:50|unique:students,reg_number',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'othername' => 'required|string|max:255',
            'dob' => 'required|string|max:50',
            'gender' => 'nullable|string|max:50',
            'class' => 'required|string|max:100',
            'subjects' => 'required|string|max:500',
            'house' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'contact_phone' => 'required|string|max:50',
            'lga' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'nationality' => 'required|string|max:100',
            'address' => 'required|string',
            'father_name' => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:50',
            'mother_name' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:50',
            'sponsor_name' => 'nullable|string|max:255',
            'sponsor_occupation' => 'nullable|string|max:255',
            'sponsor_phone' => 'nullable|string|max:50',
            'sponsor_address' => 'nullable|string',
            'relationship' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
        ];
    }
}
