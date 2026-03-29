<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DTO\StoreResultRemarkDTO;
use App\Support\Coercion;
use Illuminate\Foundation\Http\FormRequest;

final class StoreResultRemarkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    protected function prepareForValidation(): void
    {
        $merge = [];
        foreach (['reg_number', 'class', 'term', 'session'] as $key) {
            if ($this->has($key) && is_string($this->input($key))) {
                $merge[$key] = trim($this->input($key));
            }
        }
        if ($this->has('remark')) {
            $raw = $this->input('remark');
            if ($raw === null) {
                $merge['remark'] = null;
            } else {
                $t = trim(Coercion::string($raw));
                $merge['remark'] = $t === '' ? null : $t;
            }
        }
        if ($merge !== []) {
            $this->merge($merge);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'reg_number' => ['required', 'string', 'max:191'],
            'class' => ['required', 'string', 'max:100'],
            'term' => ['required', 'string', 'max:50'],
            'session' => ['required', 'string', 'max:50'],
            'remark' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function dto(): StoreResultRemarkDTO
    {
        $v = Coercion::stringKeyedMap($this->validated());
        $remarkRaw = $v['remark'] ?? null;
        $remark = null;
        if ($remarkRaw !== null) {
            $t = trim(Coercion::string($remarkRaw));
            $remark = $t === '' ? null : $t;
        }

        return new StoreResultRemarkDTO(
            regNumber: trim(Coercion::string($v['reg_number'] ?? '')),
            class: trim(Coercion::string($v['class'] ?? '')),
            term: trim(Coercion::string($v['term'] ?? '')),
            session: trim(Coercion::string($v['session'] ?? '')),
            remark: $remark,
        );
    }
}
