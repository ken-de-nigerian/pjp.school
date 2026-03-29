<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Role;
use App\Support\Coercion;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    protected function prepareForValidation(): void
    {
        $merge = [];
        foreach (Role::permissionKeys() as $col) {
            if ($this->has($col)) {
                $v = $this->input($col);
                $merge[$col] = in_array($v, [1, '1', true, 'true'], true) ? 1 : 0;
            } else {
                $merge[$col] = 0;
            }
        }
        $this->merge($merge);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $rules = ['name' => ['required', 'string', 'max:255']];
        foreach (Role::permissionKeys() as $col) {
            $rules[$col] = ['required', Rule::in([0, 1])];
        }

        return $rules;
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            $sum = 0;
            foreach (Role::permissionKeys() as $col) {
                $sum += Coercion::int($this->input($col, 0));
            }
            if ($sum < 1) {
                $v->errors()->add('permissions', __('Select at least one permission.'));
            }
        });
    }
}
