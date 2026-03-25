@extends('layouts.app', ['title' => 'Notifications'])

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Notifications overview"
                pill="Admin"
                title="Notifications"
                description="View recent system notifications, alerts and messages for this admin account."
            >
                @if($notifications->count() > 0)
                    <x-slot name="actions">
                        <button type="button" class="admin-dashboard-hero__btn w-full sm:w-auto min-h-[44px] sm:min-h-0 justify-center" id="notifications-clear-all-btn">
                            <i class="fas fa-broom text-[10px] sm:text-xs" aria-hidden="true"></i>
                            <span>Clear all</span>
                        </button>
                    </x-slot>
                @endif
            </x-admin.hero-page>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                @if($notifications->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 md:py-40 px-6">
                        <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center" style="border-radius: 16px;">
                            <i class="fas fa-bell-slash text-3xl" aria-hidden="true"></i>
                        </div>
                        <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">You&apos;re all caught up</h2>
                        <p class="text-sm text-center max-w-sm mb-6" style="color: var(--on-surface-variant);">
                            There are no notifications for you right now. New alerts will appear here as they arrive.
                        </p>
                    </div>
                @else
                    <div class="flex-1 flex flex-col min-h-0">
                        <div class="px-4 sm:px-5 lg:px-6 pt-4 pb-3 border-b" style="border-color: var(--outline-variant);">
                            <p class="text-xs sm:text-sm font-medium" style="color: var(--on-surface-variant);">
                                {{ $notifications->total() }} notification(s)
                            </p>
                        </div>

                        <div class="flex-1 overflow-y-auto min-h-0 p-3 sm:p-4 lg:p-5 space-y-2 sm:space-y-3">
                            @foreach($notifications as $n)
                                @php
                                    $createdAt = $n->date_added ?? $n->created_at;
                                @endphp
                                <div class="flex items-start gap-3 sm:gap-4 rounded-2xl px-3 py-3 sm:px-4 sm:py-3 border bg-[var(--surface-container-lowest)]" style="border-color: var(--outline-variant);">
                                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full flex items-center justify-center flex-shrink-0 dashboard-stat-icon dashboard-stat-icon--blue">
                                        <i class="fas fa-bell text-sm sm:text-base" aria-hidden="true"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center justify-between gap-x-2 gap-y-1 mb-1">
                                            <h2 class="text-sm sm:text-base font-semibold break-words sm:line-clamp-2" style="color: var(--on-surface);">
                                                {{ e($n->title) }}
                                            </h2>
                                            @if($createdAt)
                                                <span class="text-[11px] sm:text-xs font-medium whitespace-nowrap" style="color: var(--on-surface-variant);">
                                                    {{ $createdAt->diffForHumans() }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs sm:text-sm break-words" style="color: var(--on-surface-variant);">
                                            {{ e($n->message) }}
                                        </p>
                                        @if($createdAt)
                                            <p class="mt-1 text-[11px] sm:text-xs" style="color: var(--on-surface-variant);">
                                                {{ $createdAt->format('d M Y · H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="flex flex-col items-end gap-2 ml-2">
                                        <button type="button" class="notification-delete-btn inline-flex items-center justify-center gap-1 px-2 py-1.5 rounded-lg text-[11px] sm:text-xs font-medium transition-opacity hover:opacity-90" style="background: var(--error-container); color: var(--on-error-container);" data-delete-url="{{ route('admin.notifications.destroy', $n) }}" data-notification-title="{{ e($n->title) }}">
                                            <i class="fas fa-trash-alt text-[10px]" aria-hidden="true"></i>
                                            <span>Remove</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($notifications->hasPages())
                            <div class="px-4 sm:px-5 lg:px-6 py-3 border-t" style="border-color: var(--outline-variant); background: var(--surface-container-low);">
                                <x-pagination :paginator="$notifications" />
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </main>

    @if($notifications->count() > 0)
        <div id="notifications-delete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto overscroll-contain" aria-modal="true" role="dialog" aria-labelledby="notifications-delete-modal-title">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="notifications-delete-modal" aria-hidden="true"></div>
            <div class="relative min-h-full flex items-center justify-center p-4 py-6 sm:p-6">
                <div class="relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-xl py-5 px-4 sm:py-6 sm:px-6 shadow-xl border my-auto" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                    <h3 id="notifications-delete-modal-title" class="text-lg font-semibold mb-2" style="color: var(--on-surface);">Delete notification</h3>
                    <p id="notifications-delete-modal-message" class="text-sm mb-6" style="color: var(--on-surface-variant);"></p>
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-2">
                        <button type="button" class="btn-secondary px-4 py-2.5 rounded-full text-sm w-full sm:w-auto" data-close="notifications-delete-modal">Cancel</button>
                        <button type="button" id="notifications-delete-modal-confirm" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full text-sm font-medium w-full sm:w-auto transition-opacity hover:opacity-95" style="background: var(--error-container); color: var(--on-error-container);">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                (function () {
                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                    const csrf = csrfMeta ? csrfMeta.getAttribute('content') : null;

                    const modal = document.getElementById('notifications-delete-modal');
                    const modalTitle = document.getElementById('notifications-delete-modal-title');
                    const modalMessage = document.getElementById('notifications-delete-modal-message');
                    const modalConfirm = document.getElementById('notifications-delete-modal-confirm');

                    let pendingUrl = null;

                    function openModal(title, message, url) {
                        pendingUrl = url;
                        if (modalTitle) modalTitle.textContent = title;
                        if (modalMessage) modalMessage.textContent = message;
                        if (modal) modal.classList.remove('hidden');
                    }

                    function closeModal() {
                        if (modal) modal.classList.add('hidden');
                        pendingUrl = null;
                    }

                    document.querySelectorAll('[data-close="notifications-delete-modal"]').forEach(function (el) {
                        el.addEventListener('click', closeModal);
                    });

                    const clearAllBtn = document.getElementById('notifications-clear-all-btn');
                    if (clearAllBtn) {
                        clearAllBtn.addEventListener('click', function () {
                            const url = @json(route('admin.notifications.clear'));
                            openModal(
                                'Clear all notifications',
                                'Are you sure you want to clear all notifications? This action cannot be undone.',
                                url
                            );
                        });
                    }

                    document.querySelectorAll('.notification-delete-btn').forEach(function (btn) {
                        btn.addEventListener('click', function () {
                            const url = this.getAttribute('data-delete-url');
                            const title = this.getAttribute('data-notification-title') || '';
                            const displayTitle = title.trim() !== '' ? '"' + title + '"' : 'this notification';
                            openModal(
                                'Delete notification',
                                'Are you sure you want to delete ' + displayTitle + '? This action cannot be undone.',
                                url
                            );
                        });
                    });

                    if (modalConfirm) {
                        modalConfirm.addEventListener('click', function () {
                            if (!pendingUrl) return;
                            const btn = this;
                            if (typeof setButtonLoading === 'function') setButtonLoading(btn, true);

                            const body = new URLSearchParams();
                            body.append('_method', 'DELETE');
                            if (csrf) body.append('_token', csrf);

                            fetch(pendingUrl, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: body
                            })
                            .then(function (r) { return r.json().then(function (data) { return { ok: r.ok, status: r.status, data: data }; }); })
                            .then(function (res) {
                                closeModal();
                                if (res.ok && res.data && res.data.status === 'success') {
                                    if (typeof flashSuccess === 'function') flashSuccess(res.data.message || 'Notification updated.');
                                    setTimeout(function () {
                                        window.location.href = res.data.redirect || window.location.href;
                                    }, 2800);
                                } else if (typeof flashError === 'function') {
                                    const msg = res.data && res.data.message ? (Array.isArray(res.data.message) ? res.data.message.join(' ') : res.data.message) : 'Action failed.';
                                    flashError(msg);
                                }
                            })
                            .catch(function () {
                                if (typeof flashError === 'function') flashError('An error occurred. Please try again.');
                                closeModal();
                            })
                            .finally(function () {
                                if (typeof setButtonLoading === 'function') setButtonLoading(btn, false);
                            });
                        });
                    }
                })();
            </script>
        @endpush
    @endif
@endsection
