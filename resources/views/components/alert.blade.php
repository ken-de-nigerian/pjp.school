@props(['type' => 'info', 'message' => null])

@php
    $classes = match($type) {
        'success' => 'bg-green-100 border-green-400 text-green-700',
        'error' => 'bg-red-100 border-red-400 text-red-700',
        'warning' => 'bg-amber-100 border-amber-400 text-amber-700',
        default => 'bg-blue-100 border-blue-400 text-blue-700',
    };
@endphp

<div {{ $attributes->merge(['class' => "border rounded px-4 py-2 $classes", 'role' => 'alert']) }}>
    @if($message){{ $message }}@else{{ $slot }}@endif
</div>
