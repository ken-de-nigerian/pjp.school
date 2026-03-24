@props([
    'title' => 'Page',
    'headline' => null,
    'eyebrow' => 'PJP Great',
])

@php
    $__headline = $headline ?? $title;
@endphp

<main id="main-content" class="pt-20">
    <section class="relative bg-educave-900 text-white py-16 md:py-24 overflow-hidden">
        <div class="absolute inset-0 opacity-[0.12] bg-[radial-gradient(circle_at_30%_20%,theme(colors.educave.400),transparent_55%)]"></div>
        <div class="container mx-auto px-4 md:px-8 lg:px-16 relative z-10">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-educave-300 mb-4">{{ $eyebrow }}</p>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold tracking-tight">{{ $__headline }}</h1>
        </div>
    </section>
    <section class="bg-white py-14 md:py-20">
        <div class="container mx-auto px-4 md:px-8 lg:px-16 max-w-3xl text-gray-600 leading-relaxed space-y-5 text-base md:text-lg">
            {{ $slot }}
        </div>
    </section>
</main>
