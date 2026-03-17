@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-semibold">{{ e($news->title) }}</h1>
    <div class="flex gap-2">
        <a href="{{ route('admin.news.edit', $news->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</a>
        <a href="{{ route('admin.news.index') }}" class="text-blue-600 hover:underline py-2">Back to News</a>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden max-w-4xl">
    @if($news->cover_image)
        <img src="{{ asset('storage/news/'.$news->cover_image) }}" alt="{{ e($news->title) }}" class="w-full max-h-80 object-cover" onerror="this.style.display='none';">
    @endif
    <div class="p-6">
        <p class="text-sm text-gray-500">
            {{ $news->created_at?->format('M j, Y H:i') ?? $news->date_added?->format('M j, Y H:i') }}
            @if($news->category)
                · {{ e($news->category) }}
            @endif
            @if($news->author)
                · {{ e($news->author) }}
            @endif
        </p>
        <div class="prose max-w-none mt-4">
            {!! nl2br(e($news->content)) !!}
        </div>
    </div>
    <div class="px-6 pb-6 flex gap-2">
        <button type="button"
                class="news-delete-btn text-red-600 hover:underline text-sm"
                data-news-id="{{ $news->id }}"
                data-news-title="{{ e($news->title) }}"
                data-delete-url="{{ route('admin.news.destroy', $news->id) }}">
            Delete
        </button>
    </div>
</div>

<div id="news-delete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto overscroll-contain" aria-modal="true" role="dialog" aria-labelledby="news-delete-modal-title">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="news-delete-modal" aria-hidden="true"></div>
    <div class="relative min-h-full min-h-[100dvh] flex items-center justify-center p-4 py-6 sm:p-6">
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
