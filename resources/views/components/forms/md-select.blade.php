@props([
    'id',
    'name',
    'label',
    'icon' => null,
    'error' => null,
    'required' => false,
    'groupClass' => '',
])

<div @class(['form-group', $groupClass])>
    <label for="{{ $id }}" class="form-label">
        {{ $label }}
        @if ($required)
            <span class="text-red-500" aria-label="{{ __('required') }}">*</span>
        @endif
    </label>

    <div @class(['m3-select', 'm3-select--error' => filled($error)])>
        @if ($icon)
            <i class="{{ $icon }} m3-select__icon" aria-hidden="true"></i>
        @endif
        <select
            id="{{ $id }}"
            name="{{ $name }}"
            aria-required="{{ $required ? 'true' : 'false' }}"
            @if (filled($error)) aria-invalid="true" aria-describedby="{{ $id }}-error" @else aria-invalid="false" @endif
            @class(['m3-select__control', 'md3-native-select', 'form-select--error' => filled($error)])
            {{ $attributes->except(['id', 'name', 'label', 'icon', 'error', 'required', 'groupClass']) }}
        >
            {{ $slot }}
        </select>
    </div>

    <p id="{{ $id }}-error" class="form-error {{ filled($error) ? '' : 'hidden' }}" role="alert" aria-live="polite">
        {{ $error }}
    </p>
</div>
