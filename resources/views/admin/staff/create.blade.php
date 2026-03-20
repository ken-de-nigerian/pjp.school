@extends('layouts.app')

@section('content')
    <main class="flex-1 overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
            <x-admin.hero-page
                aria-label="Register staff"
                pill="Admin"
                title="Register staff"
                description="Create a new staff account. Set name, email, role, and password."
            >
                <x-slot name="above">
                    <a href="{{ route('admin.staff.index') }}" class="admin-page-hero__back mb-2 sm:mb-0">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Back to staff
                    </a>
                </x-slot>
            </x-admin.hero-page>

            <div class="space-y-4 sm:space-y-6">
                <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                    <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                        <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Account</h2>
                    </div>
                    <form id="create-staff-form" method="POST" action="{{ route('admin.staff.store') }}" class="p-4 sm:p-5 min-w-0">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                            <div class="form-group">
                                <label for="name" class="form-label">Name <span style="color: var(--primary);">*</span></label>
                                <input type="text" id="name" name="name" class="form-input w-full min-w-0" value="{{ old('name') }}" placeholder="Full name">
                                <p id="name-error" class="form-error mt-1 text-sm {{ $errors->has('name') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('name') }}</p>
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">Email <span style="color: var(--primary);">*</span></label>
                                <input type="email" id="email" name="email" class="form-input w-full min-w-0" value="{{ old('email') }}" placeholder="e.g. staff@school.com">
                                <p id="email-error" class="form-error mt-1 text-sm {{ $errors->has('email') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('email') }}</p>
                            </div>

                            <div class="form-group sm:col-span-2">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" id="phone" name="phone" class="form-input w-full min-w-0" value="{{ old('phone') }}" placeholder="e.g. +234 800 000 0000">
                                <p id="phone-error" class="form-error mt-1 text-sm {{ $errors->has('phone') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('phone') }}</p>
                            </div>

                            <div class="form-group sm:col-span-2">
                                <label for="user_type" class="form-label">Role <span style="color: var(--primary);">*</span></label>
                                <select id="user_type" name="user_type" class="form-select w-full min-w-0">
                                    <option value="">Select role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ (int) old('user_type') === (int) $role->id ? 'selected' : '' }}>{{ e($role->name) }}</option>
                                    @endforeach
                                </select>
                                <p id="user_type-error" class="form-error mt-1 text-sm {{ $errors->has('user_type') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('user_type') }}</p>
                            </div>

                            <div class="form-group sm:col-span-2">
                                <label for="password" class="form-label">Password <span style="color: var(--primary);">*</span></label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password" class="form-input w-full min-w-0" placeholder="Min. 8 characters" minlength="8" autocomplete="new-password">
                                    <button type="button" class="password-toggle" onclick="togglePassword('password', this)" title="Toggle password visibility" aria-label="Toggle password visibility">
                                        <i class="fas fa-eye" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <p id="password-error" class="form-error mt-1 text-sm {{ $errors->has('password') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('password') }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 pt-4">
                            <a href="{{ route('admin.staff.index') }}" class="btn-secondary px-6 py-2.5 rounded-full text-sm w-full sm:w-auto text-center">Cancel</a>
                            <button type="submit" id="create-staff-btn" class="btn-primary px-6 py-2.5 rounded-full text-sm w-full sm:w-auto">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const csrf = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const form = document.getElementById('create-staff-form');
                const btn = document.getElementById('create-staff-btn');

                function clearLocalFieldErrors(ids) {
                    if (!Array.isArray(ids)) return;
                    ids.forEach(function(id) {
                        const el = document.getElementById(id + '-error');
                        if (el) { el.textContent = ''; el.classList.add('hidden'); }
                    });
                }

                if (form && btn) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        clearLocalFieldErrors(['name', 'email', 'phone', 'user_type', 'password']);
                        if (typeof setButtonLoading === 'function') setButtonLoading(btn, true);
                        fetch(form.action, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            body: new FormData(form)
                        })
                        .then(function(r) { return r.json().then(function(data) { return { ok: r.ok, status: r.status, data: data }; }); })
                        .then(function(res) {
                            if (res.ok && res.data && res.data.status === 'success') {
                                if (typeof flashSuccess === 'function') flashSuccess(res.data.message || 'Staff registered successfully.');
                                setTimeout(function() {
                                    window.location.href = res.data.redirect || '{{ route('admin.staff.index') }}';
                                }, 2800);
                            } else if (res.data && res.data.errors && typeof showLaravelErrors === 'function') {
                                showLaravelErrors(res.data.errors);
                            } else if (typeof flashError === 'function') {
                                const msg = Array.isArray(res.data && res.data.message) ? res.data.message.join(' ') : (res.data && res.data.message) || 'Registration failed.';
                                flashError(msg);
                            }
                        })
                        .catch(function() {
                            if (typeof flashError === 'function') flashError('An error occurred. Please try again.');
                        })
                        .finally(function() {
                            if (typeof setButtonLoading === 'function') setButtonLoading(btn, false);
                        });
                    });
                }
            });
        </script>
    @endpush
@endsection
