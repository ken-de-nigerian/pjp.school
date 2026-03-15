<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GeneratePinsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    public function rules(): array
    {
        return [
            'session' => 'required|string|max:50',
        ];
    }

    /**
     * Get pins from request: either "pins" array or gen_pin1 .. gen_pin500.
     */
    public function getPins(): array
    {
        $pins = $this->input('pins', []);
        if (is_array($pins) && ! empty($pins)) {
            return array_values(array_filter(array_map('trim', $pins)));
        }
        $out = [];
        for ($i = 1; $i <= 500; $i++) {
            $v = $this->input('gen_pin' . $i);
            if ($v !== null && trim((string) $v) !== '') {
                $out[] = trim((string) $v);
            }
        }
        return $out;
    }
}
