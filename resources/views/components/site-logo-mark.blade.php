@props([
    'loading' => 'lazy',
    'alt' => '',
    'width' => null,
    'height' => null,
])

@php
    $src = asset('storage/' . config('school.logo_file', 'logo/logo.jpg'));
@endphp

<img
    src="{{ $src }}"
    alt="{{ $alt }}"
    @if ($width) width="{{ $width }}" @endif
    @if ($height) height="{{ $height }}" @endif
    loading="{{ $loading }}"
    decoding="async"
    {{ $attributes }}
>
