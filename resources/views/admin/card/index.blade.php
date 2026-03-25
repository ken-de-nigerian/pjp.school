@extends('layouts.app', ['title' => 'Scratch cards'])

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Scratch cards"
                pill="Admin"
                title="Scratch card"
                description="Manage result-check scratch cards. View unused and used pins, or generate new pins for a session."
            />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 mb-6">
                <a href="{{ route('admin.card.unused-pins') }}" class="rounded-2xl overflow-hidden flex flex-col transition-colors" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    <div class="p-4 sm:p-5 flex flex-col flex-1">
                        <div class="flex items-center justify-between mb-3">
                            <div class="dashboard-stat-icon dashboard-stat-icon--blue w-10 h-10 rounded-xl flex items-center justify-center" style="border-radius: 12px;">
                                <i class="fas fa-key text-sm" aria-hidden="true"></i>
                            </div>
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full" style="background: var(--surface-container-high); color: var(--on-surface-variant);">Unused</span>
                        </div>
                        <p class="text-2xl sm:text-3xl font-bold tabular-nums mb-1" style="color: var(--on-surface);">{{ $unused_count ?? 0 }}</p>
                        <p class="text-xs sm:text-sm" style="color: var(--on-surface-variant);">Session: {{ e($settings['session'] ?? '—') }}</p>
                        <span class="mt-3 inline-flex items-center gap-1.5 text-sm font-medium" style="color: var(--primary);">
                            View unused pins
                            <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                        </span>
                    </div>
                </a>

                <a href="{{ route('admin.card.used-pins') }}" class="rounded-2xl overflow-hidden flex flex-col transition-colors" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    <div class="p-4 sm:p-5 flex flex-col flex-1">
                        <div class="flex items-center justify-between mb-3">
                            <div class="dashboard-stat-icon dashboard-stat-icon--blue w-10 h-10 rounded-xl flex items-center justify-center" style="border-radius: 12px;">
                                <i class="fas fa-check-circle text-sm" aria-hidden="true"></i>
                            </div>
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full" style="background: var(--surface-container-high); color: var(--on-surface-variant);">Used</span>
                        </div>
                        <p class="text-2xl sm:text-3xl font-bold tabular-nums mb-1" style="color: var(--on-surface);">{{ $used_count ?? 0 }}</p>
                        <p class="text-xs sm:text-sm" style="color: var(--on-surface-variant);">Session: {{ e($settings['session'] ?? '—') }}</p>
                        <span class="mt-3 inline-flex items-center gap-1.5 text-sm font-medium" style="color: var(--primary);">
                            View used pins
                            <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                        </span>
                    </div>
                </a>
            </div>

            <div class="rounded-3xl overflow-hidden p-4 sm:p-5 lg:p-6" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-lg font-semibold mb-1" style="color: var(--on-surface);">
                            Generate new pins
                        </h2>
                        <p class="text-sm" style="color: var(--on-surface-variant);">
                            Create scratch card pins for a session. Choose the session and number of pins (1–500).
                        </p>
                    </div>

                    <div class="flex justify-start sm:justify-end">
                        <button type="button" id="card-generate-open-btn" class="btn-primary inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition hover:opacity-95 active:scale-[0.98] shrink-0" aria-controls="card-generate-modal" aria-haspopup="dialog">
                            <i class="fas fa-plus-circle text-sm"></i>
                            <span>Generate pins</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="card-generate-modal" class="fixed inset-0 z-50 hidden overflow-y-auto overscroll-contain" aria-modal="true" role="dialog" aria-labelledby="card-generate-modal-title" aria-hidden="true">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="card-generate-modal" aria-hidden="true"></div>
        <div class="relative min-h-full flex items-center justify-center p-4 py-6 sm:p-6">
            <div class="relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-xl py-5 px-4 sm:py-6 sm:px-6 shadow-xl border my-auto" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                <h2 id="card-generate-modal-title" class="text-lg font-semibold mb-4" style="color: var(--on-surface);">Generate pins</h2>

                <form id="card-generate-form" class="space-y-4" action="{{ route('admin.card.generate-pins.store') }}" method="post">
                    @csrf
                    <input type="hidden" id="card-generate-session" name="session" value="{{ $settings['session'] }}">

                    <div class="form-group">
                        <label for="card-generate-count" class="form-label">Number of pins</label>
                        <input type="number" id="card-generate-count" name="count" value="500" min="1" max="500" placeholder="500" class="form-input w-full min-w-0">
                        <p id="card-generate-count-error" class="form-error mt-1 text-sm hidden" aria-live="polite"></p>
                        <p class="mt-1 text-xs" style="color: var(--on-surface-variant);">Between 1 and 500. Default is 500.</p>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 pt-2">
                        <button type="button" class="btn-secondary px-4 py-2.5 rounded-full text-sm w-full sm:w-auto" data-close="card-generate-modal">Cancel</button>
                        <button type="submit" id="card-generate-submit-btn" class="btn-primary px-4 py-2.5 rounded-full text-sm w-full sm:w-auto">Generate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('card-generate-modal');
                const openBtn = document.getElementById('card-generate-open-btn');
                const form = document.getElementById('card-generate-form');
                const submitBtn = document.getElementById('card-generate-submit-btn');
                let csrf = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                if (!csrf && form) csrf = form.querySelector('input[name="_token"]') && form.querySelector('input[name="_token"]').value;

                function openCardModal() {
                    if (modal) {
                        modal.classList.remove('hidden');
                        modal.setAttribute('aria-hidden', 'false');
                    }
                }
                function closeCardModal() {
                    if (modal) {
                        modal.classList.add('hidden');
                        modal.setAttribute('aria-hidden', 'true');
                    }
                }

                if (openBtn) openBtn.addEventListener('click', openCardModal);
                document.querySelectorAll('[data-close="card-generate-modal"]').forEach(function(el) {
                    el.addEventListener('click', closeCardModal);
                });

                if (form && submitBtn) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        const formError = document.getElementById('card-generate-form-error');
                        const countErrorEl = document.getElementById('card-generate-count-error');
                        const sessionErrorEl = document.getElementById('card-generate-session-error');
                        if (formError) { formError.classList.add('hidden'); formError.textContent = ''; }
                        if (countErrorEl) { countErrorEl.classList.add('hidden'); countErrorEl.textContent = ''; }
                        if (sessionErrorEl) { sessionErrorEl.classList.add('hidden'); sessionErrorEl.textContent = ''; }

                        if (!csrf) {
                            if (typeof flashError === 'function') flashError('Security token missing. Please refresh the page.');
                            return;
                        }

                        if (typeof setButtonLoading === 'function') setButtonLoading(submitBtn, true);

                        fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: new FormData(form)
                        })
                        .then(function(r) {
                            if (r.status === 422) {
                                return r.json().then(function(data) {
                                    if (data.errors && typeof showLaravelErrors === 'function') {
                                        showLaravelErrors(data.errors, { session: 'card-generate-session', count: 'card-generate-count' });
                                    } else {
                                        const msg = data.message || (data.errors && ((data.errors.session && data.errors.session[0]) || (data.errors.count && data.errors.count[0]))) || 'Please correct the errors and try again.';
                                        if (formError) { formError.textContent = msg; formError.classList.remove('hidden'); }
                                        if (data.errors && data.errors.count && data.errors.count[0] && countErrorEl) {
                                            countErrorEl.textContent = data.errors.count[0]; countErrorEl.classList.remove('hidden');
                                        }
                                        if (data.errors && data.errors.session && data.errors.session[0] && sessionErrorEl) {
                                            sessionErrorEl.textContent = data.errors.session[0]; sessionErrorEl.classList.remove('hidden');
                                        }
                                        if (typeof flashError === 'function') flashError(msg);
                                    }
                                    throw new Error('Validation failed');
                                });
                            }
                            return r.json().then(function(data) {
                                return { status: r.status, ok: r.ok, data: data };
                            }).catch(function() {
                                return { status: r.status, ok: false, data: {} };
                            });
                        })
                        .then(function(res) {
                            if (res.status === 422) return;
                            const d = res.data || {};
                            if (res.ok && d.status === 'success') {
                                if (typeof flashSuccess === 'function') flashSuccess(d.message || 'Pins generated successfully.');
                                closeCardModal();
                                setTimeout(function() { window.location.reload(); }, 1500);
                            } else {
                                if (typeof flashError === 'function') flashError(d.message || 'Failed to generate pins.');
                            }
                        })
                        .catch(function(err) {
                            if (err && err.message !== 'Validation failed' && typeof flashError === 'function') {
                                flashError('An error occurred. Please try again.');
                            }
                        })
                        .finally(function() {
                            if (typeof setButtonLoading === 'function') setButtonLoading(submitBtn, false);
                        });
                    });
                }
            });
        </script>
    @endpush
@endsection
