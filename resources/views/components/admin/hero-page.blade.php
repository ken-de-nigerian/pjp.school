@props([
    'ariaLabel' => null,
    'pill' => 'Admin',
    'hidePill' => false,
    'title' => null,
    'titleId' => null,
    'description' => null,
])

<x-admin.hero-shell :aria-label="$ariaLabel">
    @unless($hidePill)
        <div class="admin-dashboard-hero__intro flex flex-wrap items-center gap-2 sm:gap-3 mb-4">
            <span class="admin-dashboard-hero__brand inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-wide">
                <span class="h-2 w-2 rounded-full bg-pjp-gold-400 shadow-[0_0_0_4px_rgba(230,160,0,0.22)]" aria-hidden="true"></span>
                {{ $pill }}
            </span>
        </div>
    @endunless

    @isset($above)
        <div class="admin-page-hero__above">
            {{ $above }}
        </div>
    @endisset

    @if($title || $description || isset($actions))
        <div class="admin-dashboard-hero__header flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div class="min-w-0 space-y-2">
                @if($title)
                    <h1 @if($titleId) id="{{ $titleId }}" @endif class="admin-page-hero__title text-2xl sm:text-3xl font-bold leading-tight">{{ $title }}</h1>
                @endif
                @if($description)
                    <p class="admin-page-hero__description text-sm sm:text-base leading-relaxed">{{ $description }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="flex flex-wrap items-center gap-2 shrink-0">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    @endif

    @isset($below)
        <div class="admin-page-hero__below">
            {{ $below }}
        </div>
    @endisset
</x-admin.hero-shell>
