@extends('layouts.app', ['title' => 'Settings'])

@section('content')
    <main class="flex-1 overflow-y-auto overflow-x-hidden overscroll-y-none lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
            <x-admin.hero-page
                aria-label="School settings"
                pill="Admin"
                title="Settings"
                description="Manage your profile, security, and school configuration."
            />

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
                @if($layoutAdmin)
                    <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                        <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                            <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Profile Settings</h2>
                        </div>
                        <form id="profile-form" class="min-w-0">

                            @csrf
                            <input type="hidden" id="formattedPhone" name="formattedPhone" value="{{ old('formattedPhone', $layoutAdmin->phone ?? '') }}">
                            <input type="hidden" name="adminId" id="adminId" value="{{ $layoutAdmin->adminId ?? '' }}">

                            <div class="p-4 sm:p-5 min-w-0">
                                <div class="form-group">
                                    <label for="fullname" class="form-label">Full name</label>
                                    <input type="text" id="fullname" name="fullName" class="form-input" value="{{ old('fullName', $layoutAdmin->name ?? '') }}" placeholder="e.g. John Doe" required>
                                    <p id="fullName-error" class="form-error hidden" aria-live="polite"></p>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Profile photo <span style="color: var(--primary);">*</span></label>
                                    <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                                        <label class="relative cursor-pointer" for="uploadfile-1" title="Replace photo">
                                            <img id="uploadfile-1-preview" class="w-16 h-16 rounded-full object-cover border-2 shadow-sm" style="border-color: var(--outline-variant);" src="{{ ($layoutAdmin && $layoutAdmin->profileImage) ? asset('storage/staffs/' . $layoutAdmin->profileImage) : 'https://ui-avatars.com/api/?name=Admin' }}" alt="Avatar">
                                        </label>
                                        <label class="btn-secondary cursor-pointer inline-flex items-center px-3 py-2 text-sm mb-0" for="uploadfile-1">Change</label>
                                        <input type="file" id="uploadfile-1" class="hidden" accept="image/jpeg,image/png,image/jpg">
                                    </div>
                                    <p id="file-error" class="form-error hidden" aria-live="polite"></p>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $layoutAdmin->email ?? '') }}" placeholder="e.g. admin@school.com" required>
                                    <p id="email-error" class="form-error hidden" aria-live="polite"></p>
                                </div>

                                <div class="form-group">
                                    <label for="phone" class="form-label">Mobile number <span style="color: var(--primary);">*</span></label>
                                    <input type="text" id="phone" class="form-input" value="{{ old('phone', $layoutAdmin->phone ?? '') }}" placeholder="e.g. +234 800 000 0000" autocomplete="off" required>
                                    <p id="formattedPhone-error" class="form-error hidden" aria-live="polite"></p>
                                </div>

                                <div class="flex justify-end pt-2">
                                    <button type="button" id="profileBtn" class="btn-primary px-6 py-2.5 rounded-full text-sm w-full sm:w-auto">Save change</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif

                <div class="space-y-4">
                    @if($layoutAdmin)
                        <div class="rounded-xl p-4 sm:p-5 border" style="background: var(--surface-container-low); border-color: var(--card-border);">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div class="min-w-0">
                                    <h3 class="text-sm sm:text-base font-semibold mb-1" style="color: var(--on-surface);">Change Password</h3>
                                    <p class="text-sm mb-0" style="color: var(--on-surface-variant);">Set a unique password to protect your account.</p>
                                </div>
                                <div class="flex flex-col items-stretch md:items-end gap-1">
                                    <button type="button" class="btn-primary px-4 py-2 rounded-full text-sm w-full sm:w-auto" data-modal="changePassword">Change Password</button>
                                    @if($layoutAdmin->password_change_date ?? null)
                                        <p class="text-xs" style="color: var(--on-surface-variant);">Last changed: {{ $layoutAdmin->password_change_date?->format('d M, Y') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl p-4 sm:p-5 border" style="background: var(--surface-container-low); border-color: var(--card-border);">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div class="min-w-0">
                                    <h3 class="text-sm sm:text-base font-semibold mb-1" style="color: var(--on-surface);">2-Step Verification</h3>
                                    <p class="text-sm mb-0" style="color: var(--on-surface-variant);">Secure your account with 2-Step security. When enabled, you will need your password and a special code to sign in.</p>
                                </div>
                                <label class="settings-switch flex-shrink-0 self-start md:self-center">
                                    <input type="checkbox" name="security" id="security" value="1" class="settings-switch-input" data-2fa {{ (int)($layoutAdmin->security ?? 0) === 1 ? 'checked' : '' }}>
                                    <span class="settings-switch-track"></span>
                                </label>
                            </div>
                            <p id="2fa-error" class="form-error hidden mt-2" aria-live="polite"></p>
                        </div>

                        <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                            <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                                <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">General Permissions</h2>
                            </div>
                            <div class="p-4 sm:p-5 space-y-4 sm:space-y-5">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-medium mb-0.5" style="color: var(--on-surface);">Enable | Disable Scratch Card</h4>
                                        <p class="text-xs" style="color: var(--on-surface-variant);">Toggle the scratch card feature on or off.</p>
                                    </div>
                                    <label class="settings-switch flex-shrink-0">
                                        <input type="checkbox" name="scratch_card" id="scratch_card" value="1" class="settings-switch-input settings-toggle" data-field="scratch_card" {{ (int)($settings['scratch_card'] ?? 0) === 1 ? 'checked' : '' }}>
                                        <span class="settings-switch-track"></span>
                                    </label>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-medium mb-0.5" style="color: var(--on-surface);">Enable | Disable Bulk SMS</h4>
                                        <p class="text-xs" style="color: var(--on-surface-variant);">Toggle the Bulk SMS feature on or off.</p>
                                    </div>
                                    <label class="settings-switch flex-shrink-0">
                                        <input type="checkbox" name="bulk_sms" id="bulk_sms" value="1" class="settings-switch-input settings-toggle" data-field="bulk_sms" {{ (int)($settings['bulk_sms'] ?? 0) === 1 ? 'checked' : '' }}>
                                        <span class="settings-switch-track"></span>
                                    </label>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-medium mb-0.5" style="color: var(--on-surface);">Maintenance Mode</h4>
                                        <p class="text-xs" style="color: var(--on-surface-variant);">Enable or disable maintenance mode for the site.</p>
                                    </div>
                                    <label class="settings-switch flex-shrink-0">
                                        <input type="checkbox" name="maintenance_mode" id="maintenance_mode" value="1" class="settings-switch-input settings-toggle" data-field="maintenance_mode" {{ (int)($settings['maintenance_mode'] ?? 0) === 1 ? 'checked' : '' }}>
                                        <span class="settings-switch-track"></span>
                                    </label>
                                </div>
                                <p id="general-permissions-error" class="form-error hidden mt-1" aria-live="polite"></p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                    <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                        <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Website Settings</h2>
                    </div>
                    <form id="setup-form" class="p-4 sm:p-5 min-w-0">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 min-w-0">
                            <div class="form-group">
                                <label for="name" class="form-label">School Name</label>
                                <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $settings['name'] ?? '') }}" placeholder="e.g. PJP School">
                                <p id="name-error" class="form-error hidden" aria-live="polite"></p>
                            </div>

                            <div class="form-group">
                                <label for="slogan" class="form-label">School Slogan</label>
                                <input type="text" id="slogan" name="slogan" class="form-input" value="{{ old('slogan', $settings['slogan'] ?? '') }}" placeholder="e.g. Excellence in Education">
                                <p id="slogan-error" class="form-error hidden" aria-live="polite"></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address" class="form-label">School Address</label>
                            <textarea id="address" name="address" rows="3" class="form-input" placeholder="e.g. 123 Main Street, City">{{ old('address', $settings['address'] ?? '') }}</textarea>
                            <p id="address-error" class="form-error hidden" aria-live="polite"></p>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="button" id="setupBtn" class="btn-primary px-6 py-2.5 rounded-full text-sm w-full sm:w-auto">Update</button>
                        </div>
                    </form>
                </div>

                <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                    <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                        <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Academic Configurations</h2>
                    </div>

                    <form id="config-form" class="p-4 sm:p-5 min-w-0">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 min-w-0">
                            <div class="form-group">
                                <label for="term" class="form-label">Current Term</label>
                                <x-forms.md-select-native id="term" name="term" class="form-select">
                                    <option value="First Term" {{ ($settings['term'] ?? '') === 'First Term' ? 'selected' : '' }}>First Term</option>
                                    <option value="Second Term" {{ ($settings['term'] ?? '') === 'Second Term' ? 'selected' : '' }}>Second Term</option>
                                    <option value="Third Term" {{ ($settings['term'] ?? '') === 'Third Term' ? 'selected' : '' }}>Third Term</option>
                                </x-forms.md-select-native>
                                <p id="term-error" class="form-error hidden" aria-live="polite"></p>
                            </div>

                            <div class="form-group">
                                <label for="session" class="form-label">Current Session</label>
                                <x-forms.md-select-native id="session" name="session" class="form-select">
                                    @foreach(range((int)date('Y') - 5, (int)date('Y') + 5) as $y)
                                        @php $opt = $y . '/' . ($y + 1); @endphp
                                        <option value="{{ $opt }}" {{ ($settings['session'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </x-forms.md-select-native>
                                <p id="session-error" class="form-error hidden" aria-live="polite"></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                            <div class="form-group">
                                <label for="closed" class="form-label">Closed Date</label>
                                <input type="text" id="closed" name="closed" class="form-input" value="{{ old('closed', $settings['closed'] ?? '') }}" placeholder="e.g. 12 Jan 2026">
                                <p id="closed-error" class="form-error hidden" aria-live="polite"></p>
                            </div>

                            <div class="form-group">
                                <label for="resumption" class="form-label">Resumption Date</label>
                                <input type="text" id="resumption" name="resumption" class="form-input" value="{{ old('resumption', $settings['resumption'] ?? '') }}" placeholder="e.g. 01 Apr 2026">
                                <p id="resumption-error" class="form-error hidden" aria-live="polite"></p>
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="button" id="configBtn" class="btn-primary px-6 py-2.5 rounded-full text-sm w-full sm:w-auto">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <div id="changePassword" class="fixed inset-0 z-50 hidden overflow-y-auto overscroll-contain" aria-modal="true" role="dialog">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="changePassword" aria-hidden="true"></div>
        <div class="relative min-h-full flex items-center justify-center p-4 py-6 sm:p-6">
            <div class="relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-xl py-5 px-4 sm:py-6 sm:px-6 shadow-xl border my-auto" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
            <h3 class="text-lg font-semibold mb-4" style="color: var(--on-surface);">Reset Password</h3>
            <form id="password-form" class="min-w-0">
                @csrf
                <div class="space-y-4">
                    <div class="form-group">
                        <label for="oldPassword" class="form-label">Old Password</label>
                        <div class="input-group">
                            <input type="password" id="oldPassword" name="oldPassword" class="form-input" placeholder="Enter old password" autocomplete="off">
                            <button type="button" class="password-toggle" onclick="togglePassword('oldPassword', this)" title="Toggle password visibility" aria-label="Toggle password visibility">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                        <p id="oldPassword-error" class="form-error hidden" aria-live="polite"></p>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-input" placeholder="Enter new password" autocomplete="off" minlength="8">
                            <button type="button" class="password-toggle" onclick="togglePassword('password', this)" title="Toggle password visibility" aria-label="Toggle password visibility">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                        <p id="password-error" class="form-error hidden" aria-live="polite"></p>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" id="confirmPassword" name="confirmPassword" class="form-input" placeholder="Confirm password" autocomplete="off" minlength="8">
                            <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword', this)" title="Toggle password visibility" aria-label="Toggle password visibility">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                        <p id="confirmPassword-error" class="form-error hidden" aria-live="polite"></p>
                    </div>
                    <p id="password-form-error" class="form-error hidden" aria-live="polite"></p>
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 mt-6">
                    <button type="button" class="btn-secondary px-4 py-2.5 rounded-full text-sm w-full sm:w-auto" data-close="changePassword">Close</button>
                    <button type="submit" id="passwordBtn" class="btn-primary px-4 py-2.5 rounded-full text-sm w-full sm:w-auto">Change Password</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const csrf = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content') || (document.querySelector('#profile-form input[name="_token"]') && document.querySelector('#profile-form input[name="_token"]').value);
                const phoneInput = document.getElementById('phone');
                const formattedPhoneInput = document.getElementById('formattedPhone');
                if (phoneInput) phoneInput.addEventListener('input', function() { formattedPhoneInput.value = this.value; });

                document.getElementById('profileBtn') && document.getElementById('profileBtn').addEventListener('click', function(e) {
                    e.preventDefault();
                    if (formattedPhoneInput) formattedPhoneInput.value = document.getElementById('phone').value;
                    const form = document.getElementById('profile-form');
                    const btn = this;
                    clearFieldErrors(['fullName', 'email', 'formattedPhone']);
                    setButtonLoading(btn, true);
                    fetch('{{ route("admin.profile.update") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                        body: new URLSearchParams(new FormData(form))
                    })
                    .then(function(r) { return r.json().then(function(data) { return { ok: r.ok, data: data }; }); })
                    .then(function(res) {
                        const data = res.data;
                        if (data.status === 'success') {
                            flashSuccess(data.message || 'Profile updated.');
                            setTimeout(function() {
                                if (data.redirect) window.location.href = '/' + data.redirect;
                                else window.location.reload();
                            }, 2800);
                        } else if (data.errors) {
                            showLaravelErrors(data.errors);
                        } else {
                            flashError(Array.isArray(data.message) ? data.message.join(' ') : (data.message || 'Update failed.'));
                        }
                    })
                    .catch(function() { flashError('An error occurred.'); })
                    .finally(function() { setButtonLoading(btn, false); });
                });

                document.getElementById('uploadfile-1') && document.getElementById('uploadfile-1').addEventListener('change', function() {
                    const file = this.files[0];
                    if (!file) return;
                    const fd = new FormData();
                    fd.append('photoimg', file);
                    fd.append('_token', csrf);
                    fd.append('adminId', document.getElementById('adminId') && document.getElementById('adminId').value || '');
                    const errEl = document.getElementById('file-error');
                    errEl.textContent = '';
                    errEl.classList.add('hidden');
                    fetch('{{ route("admin.profile.upload") }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                        body: fd
                    })
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        if (data.status === 'success') {
                            flashSuccess('Photo updated.');
                            setTimeout(function() { window.location.reload(); }, 2800);
                        } else {
                            errEl.textContent = (data.message || 'Upload failed.');
                            errEl.classList.remove('hidden');
                        }
                    })
                    .catch(function() {
                        errEl.textContent = 'An error occurred.';
                        errEl.classList.remove('hidden');
                    });
                });

                document.querySelectorAll('[data-modal="changePassword"]').forEach(function(el) {
                    el.addEventListener('click', function() {
                        document.getElementById('changePassword').classList.remove('hidden');
                    });
                });
                document.querySelectorAll('[data-close="changePassword"]').forEach(function(el) {
                    el.addEventListener('click', function() {
                        document.getElementById('changePassword').classList.add('hidden');
                    });
                });

                const passwordForm = document.getElementById('password-form');
                if (passwordForm) passwordForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const btn = document.getElementById('passwordBtn');
                    const formErrEl = document.getElementById('password-form-error');
                    clearFieldErrors(['oldPassword', 'password', 'confirmPassword']);
                    if (formErrEl) { formErrEl.textContent = ''; formErrEl.classList.add('hidden'); }
                    setButtonLoading(btn, true);
                    const fd = new FormData(this);
                    fd.append('password_confirmation', document.getElementById('confirmPassword').value);
                    fetch('{{ route("admin.profile.password") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                        body: new URLSearchParams(fd)
                    })
                    .then(function(r) { return r.json().then(function(data) { return { ok: r.ok, data: data }; }); })
                    .then(function(res) {
                        const data = res.data;
                        if (data.status === 'success') {
                            flashSuccess(data.message || 'Password changed.');
                            document.getElementById('changePassword').classList.add('hidden');
                            setTimeout(function() {
                                if (data.redirect) window.location.href = '/' + data.redirect;
                                else window.location.reload();
                            }, 2800);
                        } else if (data.errors) {
                            showLaravelErrors(data.errors, { password_confirmation: 'confirmPassword' });
                        } else {
                            const msg = Array.isArray(data.message) ? data.message.join(' ') : (data.message || 'Update failed.');
                            if (formErrEl) { formErrEl.textContent = msg; formErrEl.classList.remove('hidden'); }
                            else flashError(msg);
                        }
                    })
                    .catch(function() {
                        if (formErrEl) { formErrEl.textContent = 'An error occurred.'; formErrEl.classList.remove('hidden'); }
                        else flashError('An error occurred.');
                    })
                    .finally(function() { setButtonLoading(btn, false); });
                });

                document.querySelectorAll('.settings-toggle').forEach(function(toggle) {
                    toggle.addEventListener('change', function() {
                        const el = this;
                        el.blur();
                        const scrollEl = document.querySelector('main.overflow-y-auto');
                        const scrollTop = scrollEl ? scrollEl.scrollTop : 0;
                        const field = el.getAttribute('data-field');
                        const value = el.checked ? 1 : 0;
                        fetch('{{ route("admin.settings.update") }}', {
                            method: 'PUT',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            body: JSON.stringify({ [field]: value })
                        })
                        .then(function(r) { return r.json(); })
                        .then(function(data) {
                            if (data.status === 'success') flashSuccess('Setting updated.');
                            else flashError(data.message || 'Update failed.');
                            if (scrollEl) setTimeout(function() { scrollEl.scrollTop = scrollTop; }, 50);
                        })
                        .catch(function() {
                            flashError('An error occurred.');
                            if (scrollEl) setTimeout(function() { scrollEl.scrollTop = scrollTop; }, 50);
                        });
                    });
                });

                document.querySelector('[data-2fa]') && document.querySelector('[data-2fa]').addEventListener('change', function() {
                    const el = this;
                    el.blur();
                    const scrollEl = document.querySelector('main.overflow-y-auto');
                    const scrollTop = scrollEl ? scrollEl.scrollTop : 0;
                    const value = el.checked ? 1 : 0;
                    fetch('{{ route("admin.settings.2fa") }}', {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                        body: JSON.stringify({ security: value })
                    })
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        if (data.status === 'success') flashSuccess('2-Step verification updated.');
                        else flashError(data.message || 'Update failed.');
                        if (scrollEl) setTimeout(function() { scrollEl.scrollTop = scrollTop; }, 50);
                    })
                    .catch(function() {
                        flashError('An error occurred.');
                        if (scrollEl) setTimeout(function() { scrollEl.scrollTop = scrollTop; }, 0);
                    });
                });

                document.getElementById('setupBtn') && document.getElementById('setupBtn').addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = document.getElementById('setup-form');
                    const btn = this;
                    clearFieldErrors(['name', 'slogan', 'address']);
                    setButtonLoading(btn, true);
                    const payload = {name: form.name.value, slogan: form.slogan.value, address: form.address.value};
                    fetch('{{ route("admin.settings.update") }}', {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        body: JSON.stringify(payload)
                    })
                    .then(function(r) { return r.json().then(function(data) { return { ok: r.ok, data: data }; }); })
                    .then(function(res) {
                        const data = res.data;
                        if (data.status === 'success') {
                            flashSuccess('Website settings updated.');
                            setTimeout(function() { window.location.reload(); }, 2800);
                        } else if (data.errors) showLaravelErrors(data.errors);
                        else flashError(data.message || 'Update failed.');
                    })
                    .catch(function() { flashError('An error occurred.'); })
                    .finally(function() { setButtonLoading(btn, false); });
                });

                document.getElementById('configBtn') && document.getElementById('configBtn').addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = document.getElementById('config-form');
                    const btn = this;
                    clearFieldErrors(['term', 'session', 'closed', 'resumption']);
                    setButtonLoading(btn, true);
                    const payload = {
                        term: form.term.value,
                        session: form.session.value,
                        closed: form.closed.value,
                        resumption: form.resumption.value
                    };
                    fetch('{{ route("admin.settings.update") }}', {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        body: JSON.stringify(payload)
                    })
                    .then(function(r) { return r.json().then(function(data) { return { ok: r.ok, data: data }; }); })
                    .then(function(res) {
                        const data = res.data;
                        if (data.status === 'success') {
                            flashSuccess('Academic configuration updated.');
                            setTimeout(function() { window.location.reload(); }, 2800);
                        } else if (data.errors) showLaravelErrors(data.errors);
                        else flashError(data.message || 'Update failed.');
                    })
                    .catch(function() { flashError('An error occurred.'); })
                    .finally(function() { setButtonLoading(btn, false); });
                });
            });
        </script>
    @endpush
@endsection
