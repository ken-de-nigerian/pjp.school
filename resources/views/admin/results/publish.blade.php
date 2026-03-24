@extends('layouts.app', ['title' => 'Publish results'])

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Publish results"
                pill="Admin"
                title="Publish results"
                description="Ensure all subject results for a class are uploaded correctly before publishing the final grades."
            >
                @if(Route::has('admin.results.published'))
                    <x-slot name="actions">
                        <a href="{{ route('admin.results.published') }}" class="admin-dashboard-hero__btn admin-dashboard-hero__btn--primary w-full lg:w-auto justify-center min-h-[44px] sm:min-h-0">
                            <i class="fas fa-eye text-xs" aria-hidden="true"></i>
                            <span>View published</span>
                        </a>
                    </x-slot>
                @endif
            </x-admin.hero-page>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl p-5 sm:p-6 lg:p-8" style="background: var(--surface-container-low); box-shadow: var(--elevation-1);">
                <div class="col-span-full flex-1 flex flex-col items-center justify-center min-h-[min(400px,50vh)] py-12 sm:py-16">
                    <div class="rounded-3xl p-4 sm:p-6 lg:p-8 overflow-hidden min-w-0 w-full" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                        <form id="publish-results-form" method="POST" action="{{ route('admin.results.publish') }}" class="space-y-5 sm:space-y-6">
                            @csrf

                            <div class="grid grid-cols-12 gap-4 min-w-0">
                                <div class="col-span-12 sm:col-span-6 form-group min-w-0">
                                    <label for="class" class="form-label">Class <span style="color: var(--primary);">*</span></label>
                                    <select id="class" name="class" class="form-select w-full min-w-0">
                                        <option value="">Select class</option>
                                        <option value="JSS 1">JSS 1</option>
                                        <option value="JSS 2">JSS 2</option>
                                        <option value="JSS 3">JSS 3</option>
                                        <option value="SSS 1">SSS 1</option>
                                        <option value="SSS 2">SSS 2</option>
                                        <option value="SSS 3">SSS 3</option>
                                    </select>
                                    <p id="class-error" class="form-error mt-1 text-sm hidden" aria-live="polite"></p>
                                </div>

                                <div class="col-span-12 sm:col-span-6 form-group min-w-0">
                                    <label for="term" class="form-label">Term <span style="color: var(--primary);">*</span></label>
                                    <select id="term" name="term" class="form-select w-full min-w-0">
                                        <option value="">Select term</option>
                                        <option value="First Term" {{ ($settings['term'] ?? '') === 'First Term' ? 'selected' : '' }}>First Term</option>
                                        <option value="Second Term" {{ ($settings['term'] ?? '') === 'Second Term' ? 'selected' : '' }}>Second Term</option>
                                        <option value="Third Term" {{ ($settings['term'] ?? '') === 'Third Term' ? 'selected' : '' }}>Third Term</option>
                                    </select>
                                    <p id="term-error" class="form-error mt-1 text-sm hidden" aria-live="polite"></p>
                                </div>

                                <div class="col-span-12 sm:col-span-12 form-group min-w-0">
                                    <label for="session" class="form-label">Session <span style="color: var(--primary);">*</span></label>
                                    <select id="session" name="session" class="form-select w-full min-w-0">
                                        <option value="">Select session</option>
                                        @foreach(range((int)date('Y') - 5, (int)date('Y') + 5) as $y)
                                            @php $opt = $y . '/' . ($y + 1); @endphp
                                            <option value="{{ $opt }}" {{ ($settings['session'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                    <p id="session-error" class="form-error mt-1 text-sm hidden" aria-live="polite"></p>
                                </div>
                            </div>

                            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2 min-w-0" style="border-top: 1px solid var(--outline-variant); padding-top: 1.25rem;">
                                <a href="{{ route('admin.publish-results') }}" class="btn-secondary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 sm:min-w-[120px]" style="border-radius: 12px;">
                                    <i class="fas fa-times text-sm" aria-hidden="true"></i>
                                    Clear
                                </a>

                                <button type="submit" id="publish-results-submit" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;">
                                    <i class="fas fa-arrow-right text-sm" aria-hidden="true"></i>
                                    Publish result
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
        <script>
            (function() {
                const form = document.getElementById('publish-results-form');
                const submitBtn = document.getElementById('publish-results-submit');
                const formErrorEl = document.getElementById('publish-form-error');
                const csrf = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                function clearErrors() {
                    [formErrorEl, 'class', 'term', 'session'].forEach(function(id) {
                        const el = typeof id === 'string' ? document.getElementById(id + '-error') : id;
                        if (el) { el.textContent = ''; el.classList.add('hidden'); }
                    });
                }

                function showErrors(errors) {
                    clearErrors();
                    if (errors && typeof errors === 'object') {
                        Object.keys(errors).forEach(function(key) {
                            const msg = Array.isArray(errors[key]) ? errors[key][0] : errors[key];
                            const el = document.getElementById(key + '-error');
                            if (el) { el.textContent = msg; el.classList.remove('hidden'); }
                        });
                    }
                    if (typeof showLaravelErrors === 'function' && errors) showLaravelErrors(errors);
                }

                if (form && submitBtn && csrf) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        clearErrors();

                        const formData = new FormData(form);
                        setButtonLoading(submitBtn, true);

                        fetch(form.getAttribute('action'), {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(function(r) {
                            return r.json().then(function(data) {
                                return { ok: r.ok, status: r.status, data: data };
                            }).catch(function() { return { ok: false, status: r.status, data: {} }; });
                        })
                        .then(function(result) {
                            setButtonLoading(submitBtn, false);
                            const d = result.data || {};

                            if (result.ok && (d.status === 'success')) {
                                if (typeof flashSuccess === 'function') flashSuccess(d.message || 'Results published successfully.');
                                if (d.redirect) {
                                    setTimeout(function() { window.location.href = d.redirect; }, 1200);
                                }
                                return;
                            }

                            if (result.status === 422 && d.errors) {
                                showErrors(d.errors);
                                return;
                            }

                            const msg = d.message || 'Failed to publish results. Please try again.';
                            if (typeof flashError === 'function') flashError(msg);
                            if (formErrorEl) { formErrorEl.textContent = msg; formErrorEl.classList.remove('hidden'); }
                        })
                        .catch(function() {
                            setButtonLoading(submitBtn, false);
                            if (typeof flashError === 'function') flashError('Request failed. Check your connection and try again.');
                        });
                    });
                }
            })();
        </script>
    @endpush
@endsection
