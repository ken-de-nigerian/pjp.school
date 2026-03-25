@php use Carbon\Carbon; @endphp
@extends('layouts.app', ['title' => $news->title ?? 'News'])

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Announcement"
                pill="Admin"
                :title="e($news->title)"
            >
                <x-slot name="below">
                    <div class="flex flex-wrap items-center gap-2 text-xs sm:text-sm" style="color: var(--on-surface-variant);">
                        @php
                            $publishedAt = $news->created_at
                                ? Carbon::parse($news->created_at)
                                : ($news->date_added ?? null);
                        @endphp
                        @if($publishedAt)
                            <span class="inline-flex items-center gap-1">
                                <i class="fas fa-clock text-xs" aria-hidden="true"></i>
                                <span>{{ $publishedAt->format('M j, Y g:ia') }}</span>
                            </span>
                        @endif

                        @if($news->category)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs"
                                  style="background: var(--surface-container-high); color: var(--on-surface-variant);">
                                <i class="fas fa-tag text-[11px]" aria-hidden="true"></i>
                                <span class="truncate">{{ e($news->category) }}</span>
                            </span>
                        @endif

                        @if($news->author)
                            <span class="inline-flex items-center gap-1">
                                <i class="fas fa-user text-xs" aria-hidden="true"></i>
                                <span>{{ e($news->author) }}</span>
                            </span>
                        @endif
                    </div>
                </x-slot>
                <x-slot name="actions">
                    <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto lg:flex-shrink-0">
                        <a href="{{ route('admin.news.index') }}" class="admin-dashboard-hero__btn w-full sm:w-auto justify-center min-h-[44px] sm:min-h-0">
                            <i class="fas fa-arrow-left text-xs" aria-hidden="true"></i>
                            <span>Back to announcements</span>
                        </a>

                        @if(Route::has('admin.news.edit'))
                            <a href="{{ route('admin.news.edit', $news) }}" class="admin-dashboard-hero__btn admin-dashboard-hero__btn--primary w-full sm:w-auto justify-center min-h-[44px] sm:min-h-0">
                                <i class="fas fa-pen text-xs" aria-hidden="true"></i>
                                <span>Edit</span>
                            </a>
                        @endif
                    </div>
                </x-slot>
            </x-admin.hero-page>

            <section class="grid grid-cols-1 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)] gap-6 lg:gap-8 items-start">
                <article class="card-refined rounded-3xl overflow-hidden"
                         style="border-color: var(--outline-variant);">
                    @if($news->cover_image)
                        <div class="w-full overflow-hidden max-h-80 lg:max-h-[320px]">
                            <img src="{{ asset('storage/news/'.$news->cover_image) }}"
                                 alt="{{ e($news->title) }}"
                                 class="w-full h-full object-cover"
                                 loading="lazy"
                                 onerror="this.style.display='none';">
                        </div>
                    @endif

                    <div class="p-5 sm:p-6 lg:p-7">
                        <div class="prose max-w-none text-sm sm:text-base" style="color: var(--on-surface);">
                            {!! nl2br(e($news->content)) !!}
                        </div>
                    </div>
                </article>

                <aside class="space-y-4 lg:space-y-5">
                    <div class="card-refined rounded-3xl p-5 sm:p-6"
                         style="border-color: var(--outline-variant);">
                        <h2 class="text-sm font-semibold mb-3" style="color: var(--on-surface);">
                            Details
                        </h2>
                        <dl class="space-y-2 text-sm" style="color: var(--on-surface-variant);">
                            <div class="flex justify-between gap-3">
                                <dt class="font-medium">Status</dt>
                                <dd>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                                          style="background: var(--primary-container); color: var(--on-primary-container);">
                                        <i class="fas fa-bullhorn mr-1 text-[10px]" aria-hidden="true"></i>
                                        Published
                                    </span>
                                </dd>
                            </div>
                            @if($publishedAt)
                                <div class="flex justify-between gap-3">
                                    <dt class="font-medium">Published</dt>
                                    <dd>{{ $publishedAt->diffForHumans() }}</dd>
                                </div>
                            @endif
                            @if($news->author)
                                <div class="flex justify-between gap-3">
                                    <dt class="font-medium">Author</dt>
                                    <dd class="truncate" style="max-width: 10rem;">
                                        {{ e($news->author) }}
                                    </dd>
                                </div>
                            @endif
                            @if($news->category)
                                <div class="flex justify-between gap-3">
                                    <dt class="font-medium">Category</dt>
                                    <dd class="truncate" style="max-width: 10rem;">
                                        {{ e($news->category) }}
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    @if(Route::has('admin.news.destroy'))
                        <div class="card-refined rounded-3xl p-5 sm:p-6 flex flex-col gap-3"
                             style="border-color: var(--outline-variant);">
                            <h2 class="text-sm font-semibold" style="color: var(--on-surface);">
                                Danger zone
                            </h2>
                            <p class="text-xs sm:text-sm" style="color: var(--on-surface-variant);">
                                Deleting this announcement will remove it from the site for everyone.
                            </p>
                            <button type="button"
                                    class="news-delete-btn inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-medium"
                                    style="background: var(--error-container); color: var(--on-error-container);"
                                    data-news-id="{{ $news->id }}"
                                    data-news-title="{{ e($news->title) }}"
                                    data-delete-url="{{ route('admin.news.destroy', $news) }}">
                                <i class="fas fa-trash-alt text-xs" aria-hidden="true"></i>
                                <span>Delete announcement</span>
                            </button>
                        </div>
                    @endif
                </aside>
            </section>
        </div>
    </main>

    <div id="news-delete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto overscroll-contain" aria-modal="true" role="dialog" aria-labelledby="news-delete-modal-title">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="news-delete-modal" aria-hidden="true"></div>
        <div class="relative min-h-full flex items-center justify-center p-4 py-6 sm:p-6">
            <div class="relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-xl py-5 px-4 sm:py-6 sm:px-6 shadow-xl border my-auto" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                <h3 id="news-delete-modal-title" class="text-lg font-semibold mb-2" style="color: var(--on-surface);">Delete announcement</h3>
                <p id="news-delete-modal-message" class="text-sm mb-6" style="color: var(--on-surface-variant);">
                    Are you sure you want to delete "{{ e($news->title) }}"? This action cannot be undone.
                </p>
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-2">
                    <button type="button" class="btn-secondary px-4 py-2.5 rounded-full text-sm w-full sm:w-auto" data-close="news-delete-modal">Cancel</button>
                    <button type="button" id="news-delete-modal-confirm" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full text-sm font-medium w-full sm:w-auto transition-opacity hover:opacity-95" style="background: var(--error-container); color: var(--on-error-container);">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                const csrf = csrfMeta ? csrfMeta.getAttribute('content') : null;
                const deleteModal = document.getElementById('news-delete-modal');
                const deleteModalConfirm = document.getElementById('news-delete-modal-confirm');
                let pendingDeleteUrl = null;

                function openDeleteModal(url) {
                    pendingDeleteUrl = url;
                    if (deleteModal) deleteModal.classList.remove('hidden');
                }

                function closeDeleteModal() {
                    if (deleteModal) deleteModal.classList.add('hidden');
                    pendingDeleteUrl = null;
                }

                document.querySelectorAll('[data-close="news-delete-modal"]').forEach(function(el) {
                    el.addEventListener('click', closeDeleteModal);
                });

                const trigger = document.querySelector('.news-delete-btn');
                if (trigger) {
                    trigger.addEventListener('click', function() {
                        const url = this.getAttribute('data-delete-url');
                        if (url) openDeleteModal(url);
                    });
                }

                if (deleteModalConfirm) {
                    deleteModalConfirm.addEventListener('click', function() {
                        if (!pendingDeleteUrl) return;
                        const btn = this;
                        if (typeof setButtonLoading === 'function') setButtonLoading(btn, true);

                        const body = new URLSearchParams();
                        body.append('_method', 'DELETE');
                        if (csrf) body.append('_token', csrf);

                        fetch(pendingDeleteUrl, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: body
                        })
                        .then(function(r) { return r.json().then(function(data) { return { ok: r.ok, status: r.status, data: data }; }); })
                        .then(function(res) {
                            closeDeleteModal();
                            if (res.ok && res.data && res.data.status === 'success') {
                                if (typeof flashSuccess === 'function') flashSuccess(res.data.message || 'Announcement deleted.');
                                setTimeout(function() {
                                    window.location.href = res.data.redirect || '{{ route('admin.news.index') }}';
                                }, 2800);
                            } else if (typeof flashError === 'function') {
                                const msg = res.data && res.data.message ? (Array.isArray(res.data.message) ? res.data.message.join(' ') : res.data.message) : 'Could not delete announcement.';
                                flashError(msg);
                            }
                        })
                        .catch(function() {
                            if (typeof flashError === 'function') flashError('An error occurred. Please try again.');
                            closeDeleteModal();
                        })
                        .finally(function() {
                            if (typeof setButtonLoading === 'function') setButtonLoading(btn, false);
                        });
                    });
                }
            })();
        </script>
    @endpush
@endsection
