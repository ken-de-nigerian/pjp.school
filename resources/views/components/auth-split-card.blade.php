@props([
    'heading' => '',
    'intro' => null,
    'showHomeLink' => true,
    'imageSrc' => asset('assets/img/forgot-pass.svg'),
    'imageAlt' => '',
    'containerMaxClass' => 'lg:max-w-5xl',
])

<header class="fixed inset-x-0 top-0 z-20 lg:hidden">
    <div class="mx-auto flex w-full items-center justify-between border-b border-[var(--outline-variant)] bg-[color-mix(in_srgb,var(--surface-container-lowest)_92%,transparent)] px-4 py-[max(0.75rem,env(safe-area-inset-top))] shadow-[var(--elevation-1)] backdrop-blur-md">
        <div class="flex items-center">
            <x-site-logo
                :href="route('home')"
                loading="eager"
                variant="app"
                :aria-label="__('Logo')"
            />
        </div>

        @if ($showHomeLink)
            <a
                href="{{ route('home') }}"
                class="ml-3 inline-flex h-10 w-10 items-center justify-center rounded-full border border-[var(--outline-variant)] bg-[var(--surface-container-low)] text-[var(--text-secondary)] transition-colors hover:bg-[var(--surface-container)] hover:text-[var(--primary)]"
                title="{{ __('Back to home') }}"
                aria-label="{{ __('Back to home') }}"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
            </a>
        @endif
    </div>
</header>

<section {{ $attributes->class(['auth-split-section flex min-h-[100dvh] flex-col justify-center px-0 pb-4 pt-[calc(env(safe-area-inset-top)+5rem)] sm:pb-6 sm:pt-[calc(env(safe-area-inset-top)+5.5rem)] lg:min-h-dvh lg:px-4 lg:py-10']) }}>
    <div class="mx-auto flex w-full max-w-none max-h-[100dvh] shrink-0 flex-col overflow-y-auto overscroll-y-contain lg:h-auto lg:max-h-none lg:flex-none lg:overflow-visible {{ $containerMaxClass }}">
        <div class="auth-split-card relative flex w-full shrink-0 flex-col lg:h-auto lg:min-h-0 lg:flex-none">
            <div class="flex w-full shrink-0 flex-col lg:h-auto lg:flex-none lg:flex-row lg:items-stretch">
                <div class="auth-split-card__visual relative hidden w-full lg:flex lg:w-1/2 lg:items-center lg:justify-center">
                    <div class="mx-auto w-full max-w-md p-6 lg:p-10">
                        <img src="{{ $imageSrc }}" alt="{{ $imageAlt }}" class="pointer-events-none h-auto w-full select-none object-contain" width="480" height="320" loading="lazy" decoding="async">
                    </div>
                    <div class="auth-split-card__rule hidden lg:block" aria-hidden="true"></div>
                </div>

                <div class="flex w-full shrink-0 flex-col lg:w-1/2 lg:min-h-0 lg:flex-1">
                    <div class="auth-split-card__panel flex flex-col overflow-visible px-4 pt-[max(0.75rem,env(safe-area-inset-top))] pb-[max(0.75rem,env(safe-area-inset-bottom))] sm:px-6 lg:min-h-0 lg:flex-1 lg:overflow-y-auto lg:pt-24 lg:pr-8 lg:pb-8 lg:pl-8 xl:pt-15 xl:pr-10 xl:pb-10 xl:pl-10">
                        <div class="hidden lg:flex absolute top-0 left-0 z-10 items-center justify-start px-8 py-6 xl:px-10">
                            <div class="flex items-center">
                                <x-site-logo
                                    :href="route('home')"
                                    loading="eager"
                                    variant="app"
                                    :aria-label="__('Logo')"
                                />
                            </div>
                        </div>

                        <h1 class="mb-2 shrink-0 font-serif text-xl font-semibold tracking-tight text-[var(--text-primary)] sm:text-2xl">
                            {{ $heading }}
                        </h1>

                        @if (filled($intro))
                            <p class="mb-0 shrink-0 text-sm leading-relaxed text-[var(--text-secondary)]">{{ $intro }}</p>
                        @endif

                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
