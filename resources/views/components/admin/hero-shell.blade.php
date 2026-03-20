@props([
    'ariaLabel' => null,
])

<section class="admin-dashboard-hero" @if($ariaLabel) aria-label="{{ $ariaLabel }}" @endif>
    <div class="admin-dashboard-hero__bg" aria-hidden="true"></div>
    <div class="admin-dashboard-hero__inner">
        {{ $slot }}
    </div>
</section>
