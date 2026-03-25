@php use App\Models\Role; @endphp
@extends('layouts.app', ['title' => 'Create role'])

@section('content')
    @php
        $groups = Role::permissionGroups();
        $totalPerms = count(Role::permissionKeys());
    @endphp
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-6xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Add role"
                pill="Admin"
                title="Add role"
                description="Saves without leaving the page. At least one permission must be on."
            >
                <x-slot name="above">
                    <a href="{{ route('admin.roles.index') }}" class="admin-page-hero__back mb-2 sm:mb-0">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Back to roles
                    </a>
                </x-slot>
            </x-admin.hero-page>

            <form action="{{ route('admin.roles.store') }}" method="POST" id="role-form" class="min-w-0 flex flex-col gap-4 sm:gap-5" data-role-form="create" data-total-perms="{{ $totalPerms }}">
                @csrf

                <div class="role-m3-card overflow-hidden">
                    <div class="role-m3-card-header px-4 sm:px-5 py-3.5 flex items-center justify-between gap-3 flex-wrap">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0" style="background: var(--primary-container); color: var(--on-primary-container);">
                                <i class="fas fa-signature text-sm" aria-hidden="true"></i>
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-sm font-medium leading-tight" style="color: var(--on-surface);">Role name</h2>
                                <p class="text-xs mt-0.5" style="color: var(--on-surface-variant);">{{ $totalPerms }} permissions — enable at least one below</p>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-1.5 tabular-nums shrink-0 py-1 px-3 rounded-lg" style="background: var(--surface-container-lowest); border: 1px solid var(--divider);">
                            <span id="role-enabled-count" class="text-lg font-medium" style="color: var(--on-surface);">0</span>
                            <span class="text-sm opacity-50">/</span>
                            <span class="text-sm" style="color: var(--on-surface-variant);">{{ $totalPerms }}</span>
                            <span class="text-xs ml-1 hidden sm:inline" style="color: var(--on-surface-variant);">enabled</span>
                        </div>
                    </div>
                    <div class="px-4 sm:px-5 py-4" style="background: var(--surface-container-lowest);">
                        <label for="name" class="form-label sr-only">Role name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Exam officer" class="form-input w-full min-w-0 rounded-xl py-2.5 px-3 text-sm" autocomplete="off" style="background: var(--surface-container-low); border: 1px solid var(--divider);" aria-describedby="name-error">
                        <p id="name-error" class="form-error mt-2 text-sm @error('name') @else hidden @enderror" role="alert">@error('name'){{ $message }}@enderror</p>
                    </div>
                </div>

                <div class="role-m3-card px-4 sm:px-5 py-3 flex flex-col sm:flex-row sm:flex-wrap sm:items-center sm:justify-between gap-3">
                    <p class="text-sm font-medium" style="color: var(--on-surface);">Permissions <span class="font-normal text-xs" style="color: var(--on-surface-variant);">(at least one required)</span></p>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" id="role-perms-select-all" class="btn-secondary inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-medium">
                            <i class="fas fa-check-double text-[10px]" aria-hidden="true"></i>
                            Select all
                        </button>
                        <button type="button" id="role-perms-clear-all" class="btn-secondary inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-medium">
                            <i class="fas fa-times text-[10px]" aria-hidden="true"></i>
                            Clear all
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                    @foreach($groups as $group)
                        <div class="role-m3-card flex flex-col min-h-0 overflow-hidden h-full">
                            <div class="role-m3-card-header px-4 sm:px-5 py-4 flex items-start gap-3 shrink-0">
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0" style="background: var(--primary-container); color: var(--on-primary-container);">
                                    <i class="fas {{ $group['icon'] }} text-sm" aria-hidden="true"></i>
                                </div>
                                <div class="min-w-0 pt-0.5">
                                    <h2 class="text-base font-medium leading-tight" style="color: var(--on-surface);">{{ $group['title'] }}</h2>
                                    <p class="text-xs mt-0.5 leading-snug line-clamp-2" style="color: var(--on-surface-variant);">{{ $group['subtitle'] }}</p>
                                </div>
                            </div>
                            <div class="p-3 sm:p-4 flex-1 flex flex-col min-h-0">
                                <div class="role-m3-list flex-1 flex flex-col">
                                    @foreach($group['keys'] as $label => $col)
                                        <label class="role-m3-list-row flex items-center justify-between gap-3 sm:gap-4 px-3 sm:px-4 py-3 sm:py-3.5 cursor-pointer transition-colors min-h-[3rem] shrink-0">
                                            <input type="hidden" name="{{ $col }}" value="0">
                                            <span class="text-sm font-medium leading-snug text-left flex-1 min-w-0 pr-2" style="color: var(--on-surface);">{{ $label }}</span>
                                            <div class="relative flex items-center justify-end shrink-0 w-11 h-7">
                                                <input type="checkbox" name="{{ $col }}" value="1" {{ old($col, 0) == 1 ? 'checked' : '' }} class="role-perm-cb peer sr-only absolute inset-0 w-full h-full cursor-pointer z-10 opacity-0" aria-label="{{ e($label) }}" data-col="{{ $col }}">
                                                <span class="pointer-events-none absolute inset-0 rounded-full transition-colors duration-200 peer-focus-visible:ring-2 peer-focus-visible:ring-offset-2 peer-focus-visible:ring-[var(--primary)]" style="background: var(--surface-container-highest);"></span>
                                                <span class="pointer-events-none absolute inset-0 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity duration-200" style="background: var(--primary);"></span>
                                                <span class="pointer-events-none absolute top-1 left-1 w-5 h-5 rounded-full transition-transform duration-200 ease-out bg-white peer-checked:translate-x-[1.125rem]" style="box-shadow: var(--elevation-1);"></span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2 min-w-0" style="padding-top: 1.25rem;">
                    <a href="{{ route('admin.roles.index') }}" class="btn-secondary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 sm:min-w-[120px]" style="border-radius: 12px;">
                        <i class="fas fa-x text-sm" aria-hidden="true"></i>
                        Cancel
                    </a>
                    <button type="submit" id="role-form-submit" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;">
                        <i class="fas fa-save text-sm" aria-hidden="true"></i>
                        <span class="role-submit-label">Save changes</span>
                    </button>
                </div>
            </form>
        </div>
    </main>
    @push('scripts')
        <script>
            (function () {
                const form = document.getElementById('role-form');
                if (!form) return;
                const submitBtn = document.getElementById('role-form-submit');
                const nameInput = document.getElementById('name');
                const nameError = document.getElementById('name-error');
                const permError = document.getElementById('permissions-error');
                const enabledCountEl = document.getElementById('role-enabled-count');

                function allCbs() { return form.querySelectorAll('.role-perm-cb'); }
                function countEnabled() {
                    let n = 0;
                    allCbs().forEach(function (cb) { if (cb.checked) n++; });
                    return n;
                }
                function updateBadge() {
                    if (enabledCountEl) enabledCountEl.textContent = String(countEnabled());
                }
                allCbs().forEach(function (cb) { cb.addEventListener('change', updateBadge); });
                updateBadge();

                document.getElementById('role-perms-select-all').addEventListener('click', function () {
                    allCbs().forEach(function (cb) { cb.checked = true; });
                    if (permError) { permError.textContent = ''; permError.classList.add('hidden'); }
                    updateBadge();
                });
                document.getElementById('role-perms-clear-all').addEventListener('click', function () {
                    allCbs().forEach(function (cb) { cb.checked = false; });
                    updateBadge();
                });

                function clearErrors() {
                    if (nameError) { nameError.textContent = ''; nameError.classList.add('hidden'); }
                    if (nameInput) nameInput.style.boxShadow = '';
                    if (permError) { permError.textContent = ''; permError.classList.add('hidden'); }
                }
                function showNameError(msg) {
                    if (!nameError) return;
                    nameError.textContent = msg || '';
                    nameError.classList.toggle('hidden', !msg);
                    if (nameInput && msg) nameInput.style.boxShadow = 'inset 0 0 0 2px var(--error-container, #f88)';
                }
                function showPermError(msg) {
                    if (!permError) return;
                    permError.textContent = msg || '';
                    permError.classList.toggle('hidden', !msg);
                }
                function resetFormAfterCreate() {
                    nameInput.value = '';
                    allCbs().forEach(function (cb) { cb.checked = false; });
                    updateBadge();
                    clearErrors();
                }

                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    clearErrors();
                    if (countEnabled() < 1) {
                        showPermError('Select at least one permission.');
                        if (typeof flashError === 'function') flashError('Select at least one permission.');
                        return;
                    }
                    const token = form.querySelector('input[name="_token"]');
                    if (typeof setButtonLoading === 'function') setButtonLoading(submitBtn, true);
                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': token ? token.value : ''
                        },
                        body: new FormData(form)
                    })
                        .then(function (res) { return res.json().then(function (data) { return { ok: res.ok, status: res.status, data: data }; }); })
                        .then(function (r) {
                            if (r.ok && r.data.status === 'success') {
                                if (typeof flashSuccess === 'function') flashSuccess(r.data.message || 'Saved.');
                                resetFormAfterCreate();
                                return;
                            }
                            if (r.status === 422 && r.data.errors) {
                                showNameError((r.data.errors.name && r.data.errors.name[0]) || '');
                                showPermError((r.data.errors.permissions && r.data.errors.permissions[0]) || '');
                                const keys = Object.keys(r.data.errors);
                                if (typeof flashError === 'function') flashError(keys.length ? r.data.errors[keys[0]][0] : 'Check the form.');
                                return;
                            }
                            if (typeof flashError === 'function') flashError((r.data && r.data.message) || 'Could not save.');
                        })
                        .catch(function () { if (typeof flashError === 'function') flashError('Network error.'); })
                        .finally(function () { if (typeof setButtonLoading === 'function') setButtonLoading(submitBtn, false); });
                });
            })();
        </script>
    @endpush
@endsection
