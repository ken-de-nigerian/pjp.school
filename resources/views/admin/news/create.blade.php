@extends('layouts.app', ['title' => 'Create news'])

@section('content')
    <main class="flex-1 overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
            <x-admin.hero-page
                aria-label="Create announcement"
                pill="Admin"
                title="Create announcement"
                description="Publish a new school-wide announcement with title, category, content and optional cover image."
            >
                <x-slot name="above">
                    <a href="{{ route('admin.news.index') }}" class="admin-page-hero__back mb-2 sm:mb-0">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Back to announcements
                    </a>
                </x-slot>
            </x-admin.hero-page>

            <div class="space-y-4 sm:space-y-6">
                <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                    <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                        <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Announcement details</h2>
                    </div>

                    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" id="news-form" class="p-4 sm:p-5 min-w-0 space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                            <div class="form-group sm:col-span-2">
                                <label for="title" class="form-label">Title <span style="color: var(--primary);">*</span></label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="e.g. School Resumption Announcement" class="form-input w-full min-w-0">
                                <p id="title-error" class="form-error mt-1 text-sm hidden" aria-live="polite"></p>
                            </div>

                            <div class="form-group sm:col-span-2">
                                <label for="category" class="form-label">Category <span style="color: var(--primary);">*</span></label>
                                <input type="text" name="category" id="category" value="{{ old('category') }}" placeholder="e.g. Announcement, Holiday, Exam" class="form-input w-full min-w-0">
                                <p id="category-error" class="form-error mt-1 text-sm hidden" aria-live="polite"></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Author</label>
                            <p class="text-sm" style="color: var(--on-surface-variant);">
                                {{ $layoutAdmin->name ?? 'Admin' }} <span class="text-xs">(set automatically from the logged in admin)</span>
                            </p>
                        </div>

                        <div class="form-group">
                            <label for="content" class="form-label">Content <span style="color: var(--primary);">*</span></label>
                            <textarea name="content" id="content" rows="8" placeholder="Write the announcement content..." class="form-input w-full min-w-0" style="min-height: 180px;">{{ old('content') }}</textarea>
                            <p id="content-error" class="form-error mt-1 text-sm hidden" aria-live="polite"></p>
                        </div>

                        <div class="form-group">
                            <label for="photoimg" class="form-label">Cover image (optional)</label>
                            <input type="file" name="photoimg" id="photoimg" accept="image/jpeg,image/png,image/jpg" class="form-input w-full min-w-0 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary">
                            <p class="mt-1 text-xs" style="color: var(--on-surface-variant);">
                                Allowed: jpg, jpeg, png. Max 2MB. Will be resized to 556×350.
                            </p>
                            <p id="photoimg-error" class="form-error mt-1 text-sm hidden" aria-live="polite"></p>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 pt-2">
                            <a href="{{ route('admin.news.index') }}" class="btn-secondary px-6 py-2.5 rounded-full text-sm w-full sm:w-auto text-center">
                                Cancel
                            </a>
                            <button type="submit" class="btn-primary px-6 py-2.5 rounded-full text-sm w-full sm:w-auto" id="news-submit-btn">
                                Post announcement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    @push('scripts')
        <script>
            (function () {
                const form = document.getElementById('news-form');
                const submitBtn = document.getElementById('news-submit-btn');

                function clearCreateErrors() {
                    if (typeof clearFieldErrors === 'function') {
                        clearFieldErrors(['title', 'category', 'content', 'photoimg']);
                    } else {
                        ['title-error', 'category-error', 'content-error', 'photoimg-error'].forEach(function (id) {
                            const el = document.getElementById(id);
                            if (el) {
                                el.textContent = '';
                                el.classList.add('hidden');
                            }
                        });
                    }
                }

                if (form && submitBtn) {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        clearCreateErrors();

                        let token = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        if (!token) token = form.querySelector('input[name="_token"]') && form.querySelector('input[name="_token"]').value;
                        if (!token) {
                            if (typeof flashError === 'function') flashError('Security token missing. Please refresh the page.');
                            return;
                        }

                        if (typeof setButtonLoading === 'function') setButtonLoading(submitBtn, true);
                        const formData = new FormData(form);

                        fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                            .then(function (r) {
                                if (r.status === 422) {
                                    return r.json().then(function (data) {
                                        if (data.errors && typeof showLaravelErrors === 'function') {
                                            showLaravelErrors(data.errors);
                                        } else if (data.message && typeof flashError === 'function') {
                                            flashError(data.message);
                                        }
                                        throw new Error('Validation failed');
                                    });
                                }
                                return r.json();
                            })
                            .then(function (data) {
                                if (data.status === 'success') {
                                    if (typeof flashSuccess === 'function') flashSuccess(data.message || 'Your news has been posted successfully.');
                                    if (data.redirect) {
                                        setTimeout(function () { window.location.href = data.redirect; }, 2800);
                                    } else {
                                        setTimeout(function () { window.location.reload(); }, 2800);
                                    }
                                } else {
                                    if (typeof flashError === 'function') flashError(data.message || 'Could not post announcement.');
                                }
                            })
                            .catch(function (err) {
                                if (err.message !== 'Validation failed' && typeof flashError === 'function') {
                                    flashError('An error occurred. Please try again.');
                                }
                            })
                            .finally(function () {
                                if (typeof setButtonLoading === 'function') setButtonLoading(submitBtn, false);
                            });
                    });
                }
            })();
        </script>
    @endpush
@endsection
