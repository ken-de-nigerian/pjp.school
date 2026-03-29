@extends('layouts.app', ['title' => $staff->name ?? 'Edit staff'])

@section('content')
    <main class="flex-1 overflow-y-auto overflow-x-hidden overscroll-y-none lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
            <x-admin.hero-page
                aria-label="Edit staff"
                pill="Admin"
                title="Edit staff"
                :description="e($staff->name) . ' — ' . e($staff->email ?? '')"
            >
                <x-slot name="above">
                    <a href="{{ route('admin.staff.index') }}" class="admin-page-hero__back mb-2 sm:mb-0">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Back to staff
                    </a>
                </x-slot>
                <x-slot name="actions">
                    <button type="button" class="admin-dashboard-hero__btn admin-dashboard-hero__btn--primary w-full lg:w-auto justify-center min-h-[44px] sm:min-h-0" data-modal="staffResetPassword">
                        <i class="fas fa-lock text-[10px] sm:text-xs" aria-hidden="true"></i>
                        <span>Reset password</span>
                    </button>
                </x-slot>
            </x-admin.hero-page>

            <div class="space-y-4 sm:space-y-6">
                <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                    <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                        <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Account</h2>
                    </div>
                    <form id="edit-staff-account-form" method="POST" action="{{ route('admin.staff.update', $staff) }}" class="p-4 sm:p-5 min-w-0">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                            <div class="form-group">
                                <label for="name" class="form-label">Name <span style="color: var(--primary);">*</span></label>
                                <input type="text" id="name" name="name" class="form-input w-full min-w-0" value="{{ old('name', $staff->name) }}" placeholder="Full name">
                                <p id="name-error" class="form-error mt-1 text-sm {{ $errors->has('name') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('name') }}</p>
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">Email <span style="color: var(--primary);">*</span></label>
                                <input type="email" id="email" name="email" class="form-input w-full min-w-0" value="{{ old('email', $staff->email) }}" placeholder="e.g. staff@school.com">
                                <p id="email-error" class="form-error mt-1 text-sm {{ $errors->has('email') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('email') }}</p>
                            </div>

                            <div class="form-group min-w-0">
                                <label for="photoimg" class="form-label">Profile photo</label>
                                <div class="flex flex-wrap items-center gap-3">
                                    <img id="photoimg-preview" class="w-16 h-16 rounded-full object-cover border-2" style="border-color: var(--outline-variant);" src="{{ $staff->profileImage ? (str_starts_with($staff->profileImage, 'staffs/') ? asset('storage/' . $staff->profileImage) : asset('storage/staffs/' . $staff->profileImage)) : asset('storage/staffs/default.png') }}" alt="Staff" onerror="this.src='https://ui-avatars.com/api/?name=S&size=128'">
                                    <label class="btn-secondary cursor-pointer inline-flex items-center gap-2 px-3 py-2 text-sm mb-0" for="photoimg">Select image</label>
                                    <input type="file" id="photoimg" class="hidden" accept="image/jpeg,image/png,image/jpg" aria-label="Change profile photo">
                                    <button type="button" id="staff-profile-upload-btn" class="text-xs font-medium px-3 py-2 rounded-full cursor-pointer hidden" style="color: var(--on-surface-variant); background: var(--surface-container-high);">
                                        Update photo
                                    </button>
                                </div>
                                <p id="photoimg-error" class="form-error mt-1 text-sm {{ $errors->has('photoimg') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('photoimg') }}</p>
                            </div>

                            <div class="form-group sm:col-span-2">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" id="phone" name="phone" class="form-input w-full min-w-0" value="{{ old('phone', $staff->phone) }}" placeholder="e.g. +234 800 000 0000">
                                <p id="phone-error" class="form-error mt-1 text-sm {{ $errors->has('phone') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('phone') }}</p>
                            </div>

                            <div class="form-group sm:col-span-2">
                                <label for="user_type" class="form-label">Role <span style="color: var(--primary);">*</span></label>
                                <x-forms.md-select-native id="user_type" name="user_type" class="form-select w-full min-w-0">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ (int) old('user_type', $staff->user_type) === (int) $role->id ? 'selected' : '' }}>{{ e($role->name) }}</option>
                                    @endforeach
                                </x-forms.md-select-native>
                                <p id="user_type-error" class="form-error mt-1 text-sm {{ $errors->has('user_type') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('user_type') }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 pt-4">
                            <button type="submit" id="edit-staff-account-btn" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 w-full sm:w-auto min-h-[2.75rem] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <div id="staffResetPassword" class="fixed inset-0 z-50 hidden overflow-y-auto overscroll-contain" aria-modal="true" role="dialog">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="staffResetPassword" aria-hidden="true"></div>
        <div class="relative min-h-full flex items-center justify-center p-4 py-6 sm:p-6">
            <div class="relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-xl py-5 px-4 sm:py-6 sm:px-6 shadow-xl border my-auto" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--on-surface);">Reset Password</h3>
                <form id="staff-password-form" class="min-w-0">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div class="form-group">
                            <label for="staff-new-password" class="form-label">New password</label>
                            <div class="input-group">
                                <input type="password" id="staff-new-password" name="password" class="form-input" placeholder="Enter new password" autocomplete="new-password" minlength="8">
                                <button type="button" class="password-toggle" onclick="togglePassword('staff-new-password', this)" title="Toggle password visibility" aria-label="Toggle password visibility">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                            <p id="staff-password-error" class="form-error hidden mt-1" aria-live="polite"></p>
                        </div>
                        <div class="form-group">
                            <label for="staff-confirm-password" class="form-label">Confirm password</label>
                            <div class="input-group">
                                <input type="password" id="staff-confirm-password" name="password_confirmation" class="form-input" placeholder="Confirm new password" autocomplete="new-password" minlength="8">
                                <button type="button" class="password-toggle" onclick="togglePassword('staff-confirm-password', this)" title="Toggle password visibility" aria-label="Toggle password visibility">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                            <p id="staff-confirm-password-error" class="form-error hidden mt-1" aria-live="polite"></p>
                        </div>
                        <p id="staff-password-form-error" class="form-error hidden mt-1" aria-live="polite"></p>
                    </div>
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 mt-6">
                        <button type="button" class="btn-secondary px-4 py-2.5 rounded-full text-sm w-full sm:w-auto" data-close="staffResetPassword">Close</button>
                        <button type="submit" id="staff-password-btn" class="btn-primary px-4 py-2.5 rounded-full text-sm w-full sm:w-auto">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const csrf = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const passwordModal = document.getElementById('staffResetPassword');
                const passwordForm = document.getElementById('staff-password-form');
                const passwordBtn = document.getElementById('staff-password-btn');
                const resetUrl = '{{ route('admin.staff.reset-password', $staff) }}';
                const uploadProfileUrl = '{{ route('admin.staff.upload-profile', $staff) }}';

                function clearLocalFieldErrors(ids) {
                    if (!Array.isArray(ids)) return;
                    ids.forEach(function(id) {
                        const el = document.getElementById(id + '-error');
                        if (el) { el.textContent = ''; el.classList.add('hidden'); }
                    });
                }

                function submitAjaxForm(form, btn, localFieldIds) {
                    if (!form || !btn) return;
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        clearLocalFieldErrors(localFieldIds || []);
                        if (typeof setButtonLoading === 'function') setButtonLoading(btn, true);
                        fetch(form.action, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            body: new FormData(form)
                        })
                        .then(function(r) { return r.json().then(function(data) { return { ok: r.ok, status: r.status, data: data }; }); })
                        .then(function(res) {
                            if (res.ok && res.data && res.data.status === 'success') {
                                if (typeof flashSuccess === 'function') flashSuccess(res.data.message || 'Updated successfully.');
                                setTimeout(function() { window.location.reload(); }, window.RELOAD_DELAY_MS);
                            } else if (res.data && res.data.errors && typeof showLaravelErrors === 'function') {
                                showLaravelErrors(res.data.errors);
                            } else if (typeof flashError === 'function') {
                                const msg = Array.isArray(res.data && res.data.message) ? res.data.message.join(' ') : (res.data && res.data.message) || 'Update failed.';
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

                submitAjaxForm(
                    document.getElementById('edit-staff-account-form'),
                    document.getElementById('edit-staff-account-btn'),
                    ['name', 'email', 'phone', 'user_type']
                );

                (function() {
                    const profilePreview = document.getElementById('photoimg-preview');
                    const profileInput = document.getElementById('photoimg');
                    const profileUploadBtn = document.getElementById('staff-profile-upload-btn');
                    const photoErrorEl = document.getElementById('photoimg-error');
                    if (!profilePreview || !profileInput || !profileUploadBtn) return;

                    profileInput.addEventListener('change', function() {
                        const file = this.files[0];
                        if (file && file.type.indexOf('image') === 0) {
                            const r = new FileReader();
                            r.onload = function() { profilePreview.src = r.result; };
                            r.readAsDataURL(file);
                            profileUploadBtn.classList.remove('hidden');
                        }
                        if (photoErrorEl) { photoErrorEl.textContent = ''; photoErrorEl.classList.add('hidden'); }
                    });

                    profileUploadBtn.addEventListener('click', function() {
                        const file = profileInput.files[0];
                        if (!file) {
                            if (typeof flashError === 'function') flashError('Please select an image first.');
                            return;
                        }
                        if (typeof setButtonLoading === 'function') setButtonLoading(profileUploadBtn, true);
                        if (photoErrorEl) { photoErrorEl.textContent = ''; photoErrorEl.classList.add('hidden'); }
                        const fd = new FormData();
                        fd.append('photoimg', file);
                        fd.append('_token', csrf || '');
                        fetch(uploadProfileUrl, {
                            method: 'POST',
                            body: fd,
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        })
                        .then(function(r) { return r.json().then(function(data) { return { ok: r.ok, data: data }; }); })
                        .then(function(res) {
                            const data = res.data || {};
                            if (res.ok && data.status === 'success') {
                                profileInput.value = '';
                                profileUploadBtn.classList.add('hidden');
                                if (typeof flashSuccess === 'function') flashSuccess(data.message || 'Profile picture updated.');
                                setTimeout(function() { window.location.reload(); }, window.RELOAD_DELAY_MS);
                            } else if (data.errors && typeof showLaravelErrors === 'function') {
                                showLaravelErrors(data.errors);
                            } else if (typeof flashError === 'function') {
                                flashError(data.message || 'Upload failed.');
                            }
                        })
                        .catch(function() {
                            if (typeof flashError === 'function') flashError('An error occurred. Please try again.');
                        })
                        .finally(function() {
                            if (typeof setButtonLoading === 'function') setButtonLoading(profileUploadBtn, false);
                        });
                    });
                })();

                document.querySelectorAll('[data-modal="staffResetPassword"]').forEach(function(el) {
                    el.addEventListener('click', function() { if (passwordModal) passwordModal.classList.remove('hidden'); });
                });
                document.querySelectorAll('[data-close="staffResetPassword"]').forEach(function(el) {
                    el.addEventListener('click', function() { if (passwordModal) passwordModal.classList.add('hidden'); });
                });

                if (passwordForm && passwordBtn) {
                    passwordForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const newPwd = document.getElementById('staff-new-password').value;
                        const confirmPwd = document.getElementById('staff-confirm-password').value;
                        const formErrEl = document.getElementById('staff-password-form-error');
                        const pwdErrEl = document.getElementById('staff-password-error');
                        const confirmErrEl = document.getElementById('staff-confirm-password-error');

                        if (pwdErrEl) { pwdErrEl.textContent = ''; pwdErrEl.classList.add('hidden'); }
                        if (confirmErrEl) { confirmErrEl.textContent = ''; confirmErrEl.classList.add('hidden'); }
                        if (formErrEl) { formErrEl.textContent = ''; formErrEl.classList.add('hidden'); }

                        if (newPwd.length < 8) {
                            if (pwdErrEl) { pwdErrEl.textContent = 'Password must be at least 8 characters.'; pwdErrEl.classList.remove('hidden'); }
                            return;
                        }
                        if (newPwd !== confirmPwd) {
                            if (confirmErrEl) { confirmErrEl.textContent = 'Passwords do not match.'; confirmErrEl.classList.remove('hidden'); }
                            return;
                        }

                        if (typeof setButtonLoading === 'function') setButtonLoading(passwordBtn, true);
                        const fd = new FormData(passwordForm);
                        fd.append('_method', 'PUT');
                        fetch(resetUrl, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            body: fd
                        })
                        .then(function(r) {
                            if (r.redirected) { window.location.href = r.url; return; }
                            return r.json().then(function(data) { return { ok: r.ok, data: data }; });
                        })
                        .then(function(res) {
                            if (!res) return;
                            const data = res.data;
                            if (res.ok && data && (data.status === 'success' || data.redirect)) {
                                if (typeof flashSuccess === 'function') flashSuccess(data.message || 'Password changed.');
                                if (passwordModal) passwordModal.classList.add('hidden');
                                passwordForm.reset();
                                setTimeout(function() {
                                    if (data.redirect) window.location.href = data.redirect;
                                    else window.location.reload();
                                }, window.RELOAD_DELAY_MS);
                            } else if (data && data.errors) {
                                if (data.errors.password && pwdErrEl) { pwdErrEl.textContent = data.errors.password[0]; pwdErrEl.classList.remove('hidden'); }
                                if (data.errors.password_confirmation && confirmErrEl) { confirmErrEl.textContent = data.errors.password_confirmation[0]; confirmErrEl.classList.remove('hidden'); }
                            } else {
                                if (formErrEl) { formErrEl.textContent = (data && data.message) ? (Array.isArray(data.message) ? data.message.join(' ') : data.message) : 'Update failed.'; formErrEl.classList.remove('hidden'); }
                            }
                        })
                        .catch(function() {
                            if (formErrEl) { formErrEl.textContent = 'An error occurred.'; formErrEl.classList.remove('hidden'); }
                        })
                        .finally(function() {
                            if (typeof setButtonLoading === 'function') setButtonLoading(passwordBtn, false);
                        });
                    });
                }
            });
        </script>
    @endpush
@endsection
