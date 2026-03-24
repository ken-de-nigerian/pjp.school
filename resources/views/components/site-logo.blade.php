@props([
    'href' => null,
    'onclick' => null,
    'loading' => 'lazy',
    'ariaLabel' => null,
    /** app: MD3 admin/teacher | guest: light header | footer: dark footer band */
    'variant' => 'app',
])

@php
    $src = asset('storage/' . config('school.logo_file', 'logo/logo.jpg'));
    $line1 = config('school.brand_line1', 'Pope John Paul II');
    $line2 = config('school.brand_line2', 'Model Sec Sch');
    $variant = in_array($variant, ['app', 'guest', 'footer'], true) ? $variant : 'app';
    $baseClass = 'site-logo site-logo--' . $variant;
@endphp

@if ($href)
    <a
        {{ $attributes->class([$baseClass]) }}
        href="{{ $href }}"
        @if ($onclick) onclick="{{ $onclick }}" @endif
        @if ($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
    >
@else
    <div
        {{ $attributes->class([$baseClass]) }}
        @if ($ariaLabel) role="img" aria-label="{{ $ariaLabel }}" @endif
    >
@endif
        <div class="site-logo__mark" aria-hidden="true">
            <img
                class="site-logo__img"
                src="{{ $src }}"
                alt=""
                width="48"
                height="48"
                loading="{{ $loading }}"
                decoding="async"
            >
        </div>
        <div class="site-logo__wordmark">
            <span class="site-logo__line site-logo__line--primary">{{ $line1 }}</span>
            <span class="site-logo__line site-logo__line--secondary">{{ $line2 }}</span>
        </div>
@if ($href)
    </a>
@else
    </div>
@endif
