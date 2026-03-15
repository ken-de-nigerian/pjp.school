@props([
    'paginator',
    'showSummary' => true,
])

@php
    $paginator = $paginator ?? null;
    $hasPages = $paginator && method_exists($paginator, 'hasPages') && $paginator->hasPages();
@endphp

@if($hasPages)
@php
    $currentPage = $paginator->currentPage();
    $totalPages = $paginator->lastPage();
    $totalItems = $paginator->total();

    // Sliding window: show first, last, current and neighbours; ellipsis when gap
    $maxVisible = 7;
    if ($totalPages <= $maxVisible) {
        $pageRange = range(1, $totalPages);
    } else {
        $pageRange = [];
        $pageRange[] = 1;
        $delta = 2;
        $left = max(2, $currentPage - $delta);
        $right = min($totalPages - 1, $currentPage + $delta);
        if ($left > 2) {
            $pageRange[] = '…';
        }
        for ($i = $left; $i <= $right; $i++) {
            $pageRange[] = $i;
        }
        if ($right < $totalPages - 1) {
            $pageRange[] = '…';
        }
        if ($totalPages > 1) {
            $pageRange[] = $totalPages;
        }
    }
@endphp

<div {{ $attributes->merge(['class' => 'mt-4 flex flex-wrap justify-between items-center gap-3']) }}>
    @if($showSummary && $totalItems > 0)
        <p class="text-xs" style="color: var(--text-secondary);">
            Showing {{ $paginator->firstItem() }} – {{ $paginator->lastItem() }}
            of {{ $totalItems }} entries
        </p>
    @else
        <span class="flex-1" aria-hidden="true"></span>
    @endif

    <nav class="flex flex-wrap gap-1" aria-label="Pagination">
        @if($currentPage > 1)
            <a href="{{ $paginator->url($currentPage - 1) }}"
               rel="prev"
               class="pagination-link pagination-link--default">
                Prev
            </a>
        @endif

        @foreach($pageRange as $i)
            @if($i === '…')
                <span class="px-3 py-1.5 text-xs" style="color: var(--text-secondary);">…</span>
            @else
                @if($i == $currentPage)
                    <span class="pagination-link pagination-link--active" aria-current="page">{{ $i }}</span>
                @else
                    <a href="{{ $paginator->url($i) }}"
                       class="pagination-link pagination-link--default">{{ $i }}</a>
                @endif
            @endif
        @endforeach

        @if($currentPage < $totalPages)
            <a href="{{ $paginator->url($currentPage + 1) }}"
               rel="next"
               class="pagination-link pagination-link--default">
                Next
            </a>
        @endif
    </nav>
</div>
@endif
