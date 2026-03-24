@props([
    'heading' => '',
    'intro' => null,
    'showHomeLink' => true,
    'imageSrc' => asset('assets/img/signin.svg'),
    'imageAlt' => '',
    'containerMaxClass' => 'lg:max-w-5xl',
])

<section {{ $attributes->class(['auth-split-section flex min-h-[100dvh] flex-col justify-center px-0 py-4 sm:py-6 lg:min-h-dvh lg:px-4 lg:py-10']) }}>
    {{-- Mobile: max-height + scroll when tall; intrinsic height when short so justify-center can vertically center --}}
    <div
        class="mx-auto flex w-full max-w-none max-h-[100dvh] shrink-0 flex-col overflow-y-auto overscroll-y-contain lg:h-auto lg:max-h-none lg:flex-none lg:overflow-visible {{ $containerMaxClass }}"
    >
        <div class="auth-split-card flex w-full shrink-0 flex-col lg:h-auto lg:min-h-0 lg:flex-none">
            <div class="flex w-full shrink-0 flex-col lg:h-auto lg:flex-none lg:flex-row lg:items-stretch">
                {{-- Illustration: lg+ only --}}
                <div class="auth-split-card__visual relative hidden w-full lg:flex lg:w-1/2 lg:items-center lg:justify-center">
                    <div class="mx-auto w-full max-w-md p-6 lg:p-10">
                        <img
                            src="{{ $imageSrc }}"
                            alt="{{ $imageAlt }}"
                            class="pointer-events-none h-auto w-full select-none object-contain"
                            width="480"
                            height="320"
                            loading="lazy"
                            decoding="async"
                        >
                    </div>
                    <div class="auth-split-card__rule hidden lg:block" aria-hidden="true"></div>
                </div>

                {{-- Form: centered block on mobile; stretches with art column on lg+ --}}
                <div class="flex w-full shrink-0 flex-col lg:w-1/2 lg:min-h-0 lg:flex-1">
                    <div
                        class="auth-split-card__panel flex flex-col overflow-visible px-4 pt-[max(0.75rem,env(safe-area-inset-top))] pb-[max(0.75rem,env(safe-area-inset-bottom))] sm:px-6 lg:min-h-0 lg:flex-1 lg:overflow-y-auto lg:p-8 xl:p-10"
                    >
                        <h1 class="mb-2 shrink-0 font-serif text-xl font-semibold tracking-tight text-[var(--text-primary)] sm:text-2xl">
                            {{ $heading }}
                        </h1>
                        @if (filled($intro))
                            <p class="mb-0 shrink-0 text-sm leading-relaxed text-[var(--text-secondary)]">{{ $intro }}</p>
                        @endif
                        @if ($showHomeLink)
                            <p class="mb-0 mt-2 shrink-0 text-sm text-[var(--text-secondary)]">
                                Click
                                <a href="{{ route('home') }}" class="px-0.5 font-medium text-[var(--primary)] underline decoration-[color-mix(in_srgb,var(--primary)_45%,transparent)] underline-offset-2 hover:opacity-90">here</a>
                                to go back home.
                            </p>
                        @endif

                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
