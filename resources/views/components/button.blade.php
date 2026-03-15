@props(['variant' => 'primary'])

@php
    $base = 'inline-flex items-center px-4 py-2 rounded font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50';
    $classes = match($variant) {
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
        'secondary' => 'bg-gray-200 text-gray-800 hover:bg-gray-300 focus:ring-gray-500',
        default => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
    };
@endphp

<button {{ $attributes->merge(['type' => 'submit', 'class' => "$base $classes"]) }}>
    {{ $slot }}
</button>
