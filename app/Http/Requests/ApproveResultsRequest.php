<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Coercion;
use Illuminate\Foundation\Http\FormRequest;

final class ApproveResultsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'selectedRows' => 'required|array',
            'selectedRows.*' => 'integer|exists:annual_result,id',
        ];
    }

    /**
     * @return list<int>
     */
    public function selectedIds(): array
    {
        $v = Coercion::stringKeyedMap($this->validated());

        return Coercion::listOfInt($v['selectedRows'] ?? []);
    }
}
