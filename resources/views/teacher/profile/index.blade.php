@extends('layouts.app', ['title' => 'My profile'])

@php
    $t = $teacher ?? $user;
    $avatarName = urlencode(trim(($t->firstname ?? '') . ' ' . ($t->lastname ?? '')) ?: 'Teacher');
    $photo = !empty($t->imagelocation)
        ? (str_starts_with((string) $t->imagelocation, 'teachers/')
            ? asset('storage/' . $t->imagelocation)
            : asset('storage/teachers/' . $t->imagelocation))
        : asset('storage/teachers/default.png');
@endphp

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Teacher profile"
                pill="Teacher"
                title="Profile"
                description="{{ e(trim(($t->firstname ?? '') . ' ' . ($t->lastname ?? ''))) }} · {{ e($t->email ?? '') }}"
            >
                <x-slot name="actions">
                    <button type="button" class="admin-dashboard-hero__btn admin-dashboard-hero__btn--primary w-full lg:w-auto justify-center min-h-[44px] sm:min-h-0" data-modal="teacher-password-modal">
                        <i class="fas fa-lock text-xs" aria-hidden="true"></i>
                        <span>Change password</span>
                    </button>
                </x-slot>
            </x-admin.hero-page>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6">
                <div class="lg:col-span-4 order-2 lg:order-1">
                    <div class="rounded-3xl p-5 sm:p-6 flex flex-col self-start w-full" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                        <p class="text-[11px] font-semibold uppercase tracking-wider mb-4" style="color: var(--on-surface-variant); letter-spacing: 0.06em;">Profile photo</p>
                        <div class="flex flex-col items-center text-center">
                            <img id="photoimg-preview-lg" class="w-28 h-28 sm:w-32 sm:h-32 rounded-full object-cover border-2 mb-4" style="border-color: var(--outline-variant);" src="{{ $photo }}" alt="" onerror="this.src='https://ui-avatars.com/api/?name={{ $avatarName }}&size=256&background=bbdefb&color=0d47a1'; this.onerror=null;">
                            <label for="photoimg" class="btn-secondary cursor-pointer inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm rounded-xl w-full sm:w-auto mb-2" style="border-radius: 12px;">
                                <i class="fas fa-image text-xs" aria-hidden="true"></i>
                                Choose image
                            </label>
                            <input type="file" id="photoimg" class="hidden" accept="image/jpeg,image/png,image/jpg" aria-label="Select profile photo">
                            <button type="button" id="teacher-avatar-upload-btn" class="hidden w-full sm:w-auto items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-opacity hover:opacity-90" style="background: var(--primary); color: var(--on-primary); border-radius: 12px;">
                                Upload photo
                            </button>
                            <p id="photoimg-error" class="form-error mt-2 text-sm text-left w-full {{ $errors->has('photoimg') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('photoimg') }}</p>
                            <p class="text-xs mt-3 leading-relaxed" style="color: var(--on-surface-variant);">JPG or PNG, max 5&nbsp;MB.</p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-8 order-1 lg:order-2">
                    <div class="rounded-3xl overflow-hidden" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                        <div class="px-4 sm:px-6 py-4 border-b" style="border-color: var(--outline-variant); background: var(--surface-container-low);">
                            <h2 class="text-base font-semibold" style="color: var(--on-surface);">Personal information</h2>
                            <p class="text-sm mt-0.5" style="color: var(--on-surface-variant);">Update how you appear across the teacher portal.</p>
                        </div>
                        <form id="teacher-profile-form" class="p-4 sm:p-6 min-w-0" method="post" action="{{ route('teacher.profile.update') }}" novalidate>
                            @csrf

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                                <div class="form-group">
                                    <label for="firstname" class="form-label">First name <span style="color: var(--primary);">*</span></label>
                                    <input type="text" id="firstname" name="firstname" class="form-input w-full min-w-0" value="{{ old('firstname', $t->firstname) }}" autocomplete="given-name" placeholder="Enter your first name">
                                    <p id="firstname-error" class="form-error mt-1 text-sm hidden" aria-live="polite"></p>
                                </div>

                                <div class="form-group">
                                    <label for="lastname" class="form-label">Last name <span style="color: var(--primary);">*</span></label>
                                    <input type="text" id="lastname" name="lastname" class="form-input w-full min-w-0" value="{{ old('lastname', $t->lastname) }}" autocomplete="family-name" placeholder="Enter your last name">
                                    <p id="lastname-error" class="form-error mt-1 text-sm hidden" aria-live="polite"></p>
                                </div>

                                <div class="form-group">
                                    <label for="othername" class="form-label">Other names</label>
                                    <input type="text" id="othername" name="othername" class="form-input w-full min-w-0" value="{{ old('othername', $t->othername) }}" placeholder="Middle or other names (optional)">
                                    <p id="othername-error" class="form-error mt-1 text-sm hidden" aria-live="polite"></p>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="email" class="form-input w-full min-w-0" value="{{ old('email', $t->email) }}" autocomplete="email" placeholder="name@school.com" readonly>
                                    <p id="email-error" class="form-error mt-1 text-sm hidden" aria-live="polite"></p>
                                </div>

                                <div class="form-group sm:col-span-2">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" id="phone" name="phone" class="form-input w-full min-w-0" value="{{ old('phone', $t->phone) }}" autocomplete="tel" placeholder="+234 800 000 0000">
                                    <p id="phone-error" class="form-error mt-1 text-sm hidden" aria-live="polite"></p>
                                </div>
                                <input type="hidden" id="country" name="country" value="{{ old('country', $t->country) }}">
                            </div>

                            <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 sm:gap-3 pt-6 mt-2 border-t" style="border-color: var(--outline-variant);">
                                <button type="submit" id="teacher-profile-save-btn" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-medium w-full sm:w-auto" style="border-radius: 12px;">
                                    <i class="fas fa-check text-xs" aria-hidden="true"></i>
                                    Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="teacher-password-modal" class="fixed inset-0 z-50 hidden overflow-y-auto overscroll-contain" aria-modal="true" role="dialog" aria-labelledby="teacher-password-title">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="teacher-password-modal" aria-hidden="true"></div>
        <div class="relative min-h-full flex items-center justify-center p-4 py-6 sm:p-6">
            <div class="relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-2xl py-5 px-4 sm:py-6 sm:px-6 my-auto" style="background: var(--surface-container-lowest); border: 1px solid var(--outline-variant);">
                <h3 id="teacher-password-title" class="text-lg font-semibold mb-1" style="color: var(--on-surface);">Change password</h3>
                <p class="text-sm mb-5" style="color: var(--on-surface-variant);">Enter your current password, then choose a new one.</p>
                <form id="teacher-password-form" class="min-w-0" method="post" action="{{ route('teacher.profile.password') }}" novalidate>
                    @csrf
                    <div class="space-y-4">
                        <div class="form-group">
                            <label for="teacher-old-password" class="form-label">Current password</label>
                            <div class="input-group">
                                <input type="password" id="teacher-old-password" name="oldPassword" class="form-input" placeholder="Current password" autocomplete="current-password" minlength="6">
                                <button type="button" class="password-toggle" onclick="togglePassword('teacher-old-password', this)" title="Show password" aria-label="Show password">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                            <p id="teacher-old-password-error" class="form-error hidden mt-1 text-sm" aria-live="polite"></p>
                        </div>

                        <div class="form-group">
                            <label for="teacher-new-password" class="form-label">New password</label>
                            <div class="input-group">
                                <input type="password" id="teacher-new-password" name="password" class="form-input" placeholder="At least 8 characters" autocomplete="new-password" minlength="8">
                                <button type="button" class="password-toggle" onclick="togglePassword('teacher-new-password', this)" title="Show password" aria-label="Show password">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                            <p id="teacher-new-password-error" class="form-error hidden mt-1 text-sm" aria-live="polite"></p>
                        </div>

                        <div class="form-group">
                            <label for="teacher-confirm-password" class="form-label">Confirm new password</label>
                            <div class="input-group">
                                <input type="password" id="teacher-confirm-password" name="confirmPassword" class="form-input" placeholder="Repeat new password" autocomplete="new-password" minlength="8">
                                <button type="button" class="password-toggle" onclick="togglePassword('teacher-confirm-password', this)" title="Show password" aria-label="Show password">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                            <p id="teacher-confirm-password-error" class="form-error hidden mt-1 text-sm" aria-live="polite"></p>
                        </div>
                        <p id="teacher-password-form-error" class="form-error hidden mt-1 text-sm" aria-live="polite"></p>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 mt-6">
                        <button type="button" class="btn-secondary px-4 py-2.5 rounded-xl text-sm w-full sm:w-auto" style="border-radius: 12px;" data-close="teacher-password-modal">Cancel</button>
                        <button type="submit" id="teacher-password-submit" class="btn-primary px-4 py-2.5 rounded-xl text-sm w-full sm:w-auto" style="border-radius: 12px;">Update password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const csrf = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const avatarUploadUrl = @json(route('teacher.profile.avatar'));
                const passwordUrl = @json(route('teacher.profile.password'));

                const profileFieldIds = ['firstname', 'lastname', 'othername', 'email', 'phone', 'country'];

                function clearLocalFieldErrors(ids) {
                    if (!Array.isArray(ids)) return;
                    ids.forEach(function (id) {
                        const el = document.getElementById(id + '-error');
                        if (el) {
                            el.textContent = '';
                            el.classList.add('hidden');
                        }
                    });
                }

                function showFieldError(fieldId, message) {
                    const el = document.getElementById(fieldId + '-error');
                    if (el) {
                        el.textContent = message;
                        el.classList.remove('hidden');
                    }
                }

                function validateTeacherProfileForm() {
                    const first = (document.getElementById('firstname') && document.getElementById('firstname').value || '').trim();
                    const last = (document.getElementById('lastname') && document.getElementById('lastname').value || '').trim();
                    const email = (document.getElementById('email') && document.getElementById('email').value || '').trim();
                    const emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

                    if (!first) {
                        showFieldError('firstname', 'First name is required.');
                        return false;
                    }
                    if (!last) {
                        showFieldError('lastname', 'Last name is required.');
                        return false;
                    }
                    if (email && !emailOk) {
                        showFieldError('email', 'Please enter a valid email address.');
                        return false;
                    }
                    return true;
                }

                const profileForm = document.getElementById('teacher-profile-form');
                const profileSaveBtn = document.getElementById('teacher-profile-save-btn');
                if (profileForm && profileSaveBtn) {
                    profileForm.addEventListener('submit', function (e) {
                        e.preventDefault();
                        clearLocalFieldErrors(profileFieldIds);
                        if (!validateTeacherProfileForm()) {
                            return;
                        }
                        if (typeof setButtonLoading === 'function') setButtonLoading(profileSaveBtn, true);
                        fetch(profileForm.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: new FormData(profileForm),
                        })
                            .then(function (r) {
                                return r.json().then(function (data) {
                                    return { ok: r.ok, status: r.status, data: data };
                                });
                            })
                            .then(function (res) {
                                if (res.ok && res.data && res.data.status === 'success') {
                                    if (typeof flashSuccess === 'function') flashSuccess(res.data.message || 'Profile updated successfully.');
                                    setTimeout(function () {
                                        window.location.reload();
                                    }, 1200);
                                } else if (res.data && res.data.errors && typeof showLaravelErrors === 'function') {
                                    showLaravelErrors(res.data.errors);
                                } else if (typeof flashError === 'function') {
                                    const msg = Array.isArray(res.data && res.data.message)
                                        ? res.data.message.join(' ')
                                        : (res.data && res.data.message) || 'Update failed.';
                                    flashError(msg);
                                }
                            })
                            .catch(function () {
                                if (typeof flashError === 'function') flashError('An error occurred. Please try again.');
                            })
                            .finally(function () {
                                if (typeof setButtonLoading === 'function') setButtonLoading(profileSaveBtn, false);
                            });
                    });
                }

                (function () {
                    const profilePreview = document.getElementById('photoimg-preview-lg');
                    const profileInput = document.getElementById('photoimg');
                    const profileUploadBtn = document.getElementById('teacher-avatar-upload-btn');
                    const photoErrorEl = document.getElementById('photoimg-error');
                    if (!profilePreview || !profileInput || !profileUploadBtn) return;

                    const maxBytes = 5 * 1024 * 1024;
                    const allowed = ['image/jpeg', 'image/png', 'image/jpg'];

                    profileInput.addEventListener('change', function () {
                        const file = this.files[0];
                        if (photoErrorEl) {
                            photoErrorEl.textContent = '';
                            photoErrorEl.classList.add('hidden');
                        }
                        if (!file) return;
                        if (allowed.indexOf(file.type) === -1) {
                            if (photoErrorEl) {
                                photoErrorEl.textContent = 'Please choose a JPG or PNG image.';
                                photoErrorEl.classList.remove('hidden');
                            }
                            this.value = '';
                            return;
                        }
                        if (file.size > maxBytes) {
                            if (photoErrorEl) {
                                photoErrorEl.textContent = 'Image must be 5 MB or smaller.';
                                photoErrorEl.classList.remove('hidden');
                            }
                            this.value = '';
                            return;
                        }
                        if (file.type.indexOf('image') === 0) {
                            const r = new FileReader();
                            r.onload = function () {
                                profilePreview.src = r.result;
                            };
                            r.readAsDataURL(file);
                            profileUploadBtn.classList.remove('hidden');
                        }
                    });

                    profileUploadBtn.addEventListener('click', function () {
                        const file = profileInput.files[0];
                        if (!file) {
                            if (typeof flashError === 'function') flashError('Please select an image first.');
                            return;
                        }
                        if (typeof setButtonLoading === 'function') setButtonLoading(profileUploadBtn, true);
                        if (photoErrorEl) {
                            photoErrorEl.textContent = '';
                            photoErrorEl.classList.add('hidden');
                        }
                        const fd = new FormData();
                        fd.append('photoimg', file);
                        fd.append('_token', csrf || '');
                        fetch(avatarUploadUrl, {
                            method: 'POST',
                            body: fd,
                            headers: {
                                Accept: 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        })
                            .then(function (r) {
                                return r.json().then(function (data) {
                                    return { ok: r.ok, data: data };
                                });
                            })
                            .then(function (res) {
                                const data = res.data || {};
                                if (res.ok && data.status === 'success') {
                                    profileInput.value = '';
                                    profileUploadBtn.classList.add('hidden');
                                    if (data.image_url && profilePreview) profilePreview.src = data.image_url;
                                    if (typeof flashSuccess === 'function') flashSuccess(data.message || 'Profile picture updated.');
                                } else if (data.errors && typeof showLaravelErrors === 'function') {
                                    showLaravelErrors(data.errors);
                                } else if (typeof flashError === 'function') {
                                    flashError(data.message || 'Upload failed.');
                                }
                            })
                            .catch(function () {
                                if (typeof flashError === 'function') flashError('An error occurred. Please try again.');
                            })
                            .finally(function () {
                                if (typeof setButtonLoading === 'function') setButtonLoading(profileUploadBtn, false);
                            });
                    });
                })();

                const passwordModal = document.getElementById('teacher-password-modal');
                document.querySelectorAll('[data-modal="teacher-password-modal"]').forEach(function (el) {
                    el.addEventListener('click', function () {
                        if (passwordModal) passwordModal.classList.remove('hidden');
                    });
                });
                document.querySelectorAll('[data-close="teacher-password-modal"]').forEach(function (el) {
                    el.addEventListener('click', function () {
                        if (passwordModal) passwordModal.classList.add('hidden');
                    });
                });

                const passwordForm = document.getElementById('teacher-password-form');
                const passwordSubmitBtn = document.getElementById('teacher-password-submit');
                const pwdFieldMap = {
                    oldPassword: 'teacher-old-password',
                    password: 'teacher-new-password',
                    confirmPassword: 'teacher-confirm-password',
                };

                if (passwordForm && passwordSubmitBtn) {
                    passwordForm.addEventListener('submit', function (e) {
                        e.preventDefault();
                        const oldPwd = (document.getElementById('teacher-old-password') && document.getElementById('teacher-old-password').value) || '';
                        const newPwd = (document.getElementById('teacher-new-password') && document.getElementById('teacher-new-password').value) || '';
                        const confirmPwd = (document.getElementById('teacher-confirm-password') && document.getElementById('teacher-confirm-password').value) || '';
                        const formErrEl = document.getElementById('teacher-password-form-error');
                        const oldErr = document.getElementById('teacher-old-password-error');
                        const newErr = document.getElementById('teacher-new-password-error');
                        const confirmErr = document.getElementById('teacher-confirm-password-error');

                        [oldErr, newErr, confirmErr, formErrEl].forEach(function (el) {
                            if (el) {
                                el.textContent = '';
                                el.classList.add('hidden');
                            }
                        });

                        if (oldPwd.length < 6) {
                            if (oldErr) {
                                oldErr.textContent = 'Current password is required (min. 6 characters).';
                                oldErr.classList.remove('hidden');
                            }
                            return;
                        }
                        if (newPwd.length < 8) {
                            if (newErr) {
                                newErr.textContent = 'New password must be at least 8 characters.';
                                newErr.classList.remove('hidden');
                            }
                            return;
                        }
                        if (newPwd !== confirmPwd) {
                            if (confirmErr) {
                                confirmErr.textContent = 'Passwords do not match.';
                                confirmErr.classList.remove('hidden');
                            }
                            return;
                        }

                        if (typeof setButtonLoading === 'function') setButtonLoading(passwordSubmitBtn, true);
                        const fd = new FormData(passwordForm);
                        fetch(passwordUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrf,
                                Accept: 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: fd,
                        })
                            .then(function (r) {
                                return r.json().then(function (data) {
                                    return { ok: r.ok, data: data };
                                });
                            })
                            .then(function (res) {
                                const data = res.data;
                                if (res.ok && data && data.status === 'success') {
                                    if (typeof flashSuccess === 'function') flashSuccess(data.message || 'Password changed.');
                                    if (passwordModal) passwordModal.classList.add('hidden');
                                    passwordForm.reset();
                                } else if (data && data.errors && typeof showLaravelErrors === 'function') {
                                    showLaravelErrors(data.errors, pwdFieldMap);
                                } else if (typeof flashError === 'function') {
                                    const msg = data && data.message
                                        ? Array.isArray(data.message)
                                            ? data.message.join(' ')
                                            : data.message
                                        : 'Update failed.';
                                    flashError(msg);
                                }
                            })
                            .catch(function () {
                                if (formErrEl) {
                                    formErrEl.textContent = 'An error occurred. Please try again.';
                                    formErrEl.classList.remove('hidden');
                                }
                            })
                            .finally(function () {
                                if (typeof setButtonLoading === 'function') setButtonLoading(passwordSubmitBtn, false);
                            });
                    });
                }
            });
        </script>
    @endpush
@endsection
