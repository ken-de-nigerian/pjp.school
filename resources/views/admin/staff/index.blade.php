@extends('layouts.app', ['title' => 'Staff'])

@php
    $list = $searchResults ?? $staff;
    $isEmpty = $searchResults !== null ? $searchResults->isEmpty() : $staff->isEmpty();
@endphp

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Staff directory"
                pill="Admin"
                title="Staff"
                description="View and manage staff accounts. Add a new staff member or edit roles and details."
            >
                <x-slot name="actions">
                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto lg:flex-shrink-0">
                        <form method="GET" action="{{ route('admin.staff.index') }}" class="flex gap-2 flex-1 lg:flex-initial min-w-0">
                            <div class="flex-1 min-w-0 flex items-center gap-2 rounded-xl pl-3 pr-2 py-2 border transition-colors" style="background: var(--surface-container); border-color: var(--outline-variant);">
                                <i class="fas fa-search text-sm flex-shrink-0" style="color: var(--on-surface-variant);"></i>
                                <input type="search" name="search" value="{{ e($searchQuery ?? '') }}" placeholder="Search by name or email" class="flex-1 min-w-0 border-0 bg-transparent py-1 text-sm focus:ring-0 focus:outline-none" style="color: var(--on-surface);" autocomplete="off">
                            </div>

                            <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium shrink-0" data-preloader style="border-radius: 12px; border: 1px solid var(--outline-variant); background: var(--surface-container-low); color: var(--on-surface);">
                                <i class="fas fa-search text-xs" aria-hidden="true"></i>
                                <span class="hidden sm:inline">Search</span>
                            </button>
                        </form>

                        @if(Route::has('admin.staff.create'))
                            <a href="{{ route('admin.staff.create') }}" class="admin-dashboard-hero__btn admin-dashboard-hero__btn--primary w-full lg:w-auto justify-center min-h-[44px] sm:min-h-0">
                                <i class="fas fa-plus text-[10px] sm:text-xs" aria-hidden="true"></i>
                                <span>Add staff</span>
                            </a>
                        @endif
                    </div>
                </x-slot>
            </x-admin.hero-page>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                @if($isEmpty)
                    <div class="flex flex-col items-center justify-center py-16 md:py-40 px-6">
                        <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5" style="border-radius: 16px;">
                            <i class="fas fa-users-cog text-3xl" aria-hidden="true"></i>
                        </div>

                        <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No staff yet</h2>

                        <p class="text-sm text-center max-w-sm mb-6" style="color: var(--on-surface-variant);">
                            @if($searchQuery !== '')
                                No staff match your search. Try different keywords or clear the search.
                            @else
                                Add a staff member to get started.
                            @endif
                        </p>

                        @if($searchQuery === '' && Route::has('admin.staff.create'))
                            <div class="flex justify-center">
                                <a href="{{ route('admin.staff.create') }}" class="btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] rounded-xl font-medium text-sm transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;">
                                    <i class="fas fa-user-plus text-sm" aria-hidden="true"></i>
                                    Add Staff
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="overflow-y-auto flex-1 min-h-0 p-4 sm:p-5 lg:p-6" style="border-color: var(--outline-variant);">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
                            @foreach($list as $index => $s)
                                @php
                                    $avatarSrc = $s->profileImage
                                        ? (str_starts_with($s->profileImage, 'staffs/') ? asset('storage/' . $s->profileImage) : asset('storage/staffs/' . $s->profileImage))
                                        : asset('storage/staffs/default.png');
                                    $avatarName = str_replace(' ', '+', e($s->name));
                                @endphp

                                <div class="rounded-2xl overflow-hidden flex flex-col transition-shadow hover:shadow-md" style="background: var(--surface-container-lowest); border: 1px solid var(--outline-variant); box-shadow: var(--elevation-1);">
                                    <div class="p-4 sm:p-5 flex flex-col items-center text-center flex-1 min-h-0">
                                        <img src="{{ $avatarSrc }}" alt="" class="w-20 h-20 sm:w-24 sm:h-24 rounded-full object-cover border-2 flex-shrink-0 mb-3" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ $avatarName }}&size=128'">

                                        <h3 class="text-sm sm:text-base font-medium mb-0.5 truncate w-full" style="color: var(--on-surface);">
                                            @can('update', $s)
                                                <a href="{{ route('admin.staff.edit', $s->adminId) }}" class="hover:underline" style="color: var(--primary);">{{ e($s->name) }}</a>
                                            @else
                                                <span>{{ e($s->name) }}</span>
                                            @endcan
                                        </h3>

                                        <p class="text-xs sm:text-sm truncate w-full mb-2" style="color: var(--on-surface-variant);" title="{{ e($s->email ?? '') }}">{{ $s->email ?? '—' }}</p>

                                        @if($s->role && trim((string)($s->role->name ?? '')) !== '')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium mb-4" style="background: var(--surface-container-high); color: var(--on-surface-variant);">{{ e($s->role->name) }}</span>
                                        @else
                                            <span class="text-xs mb-4" style="color: var(--on-surface-variant);">—</span>
                                        @endif
                                    </div>

                                    <div class="p-3 sm:p-4 pt-0 flex flex-row sm:flex-wrap items-stretch sm:items-center justify-center gap-2 pt-4 w-full" style="border-top: 1px solid var(--outline-variant); background: var(--surface-container);">
                                        @can('update', $s)
                                            <a href="{{ route('admin.staff.edit', $s->adminId) }}" class="inline-flex items-center justify-center gap-1.5 flex-1 sm:flex-initial min-h-[44px] sm:min-h-0 px-3 py-2.5 sm:py-2 rounded-xl text-xs sm:text-sm font-medium transition-opacity hover:opacity-90 min-w-0" style="background: var(--primary-container); color: var(--on-primary-container); border-radius: 12px;">
                                                <i class="fas fa-pen text-xs flex-shrink-0" aria-hidden="true"></i>
                                                <span class="truncate">Edit</span>
                                            </a>
                                        @endcan
                                        @can('delete', $s)
                                            <button type="button" class="staff-delete-btn inline-flex items-center justify-center gap-1.5 flex-1 sm:flex-initial min-h-[44px] sm:min-h-0 px-3 py-2.5 sm:py-2 rounded-xl text-xs sm:text-sm font-medium transition-opacity hover:opacity-90 min-w-0" style="background: var(--error-container); color: var(--on-error-container); border-radius: 12px;" data-staff-id="{{ e($s->adminId) }}" data-staff-name="{{ e($s->name) }}" data-delete-url="{{ route('admin.staff.destroy', $s->adminId) }}">
                                                <i class="fas fa-trash-alt text-xs flex-shrink-0" aria-hidden="true"></i>
                                                <span class="truncate">Delete</span>
                                            </button>
                                        @endcan
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($staff && $searchResults === null && $staff->hasPages())
                        <div class="px-5 sm:px-6 py-4" style="border-top: 1px solid var(--outline-variant); background: var(--surface-container-low);">
                            <x-pagination :paginator="$staff" />
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </main>

    @if(!$isEmpty)
        <div id="staff-delete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto overscroll-contain" aria-modal="true" role="dialog" aria-labelledby="staff-delete-modal-title">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="staff-delete-modal" aria-hidden="true"></div>
            <div class="relative min-h-full min-h-[100dvh] flex items-center justify-center p-4 py-6 sm:p-6">
                <div class="relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-xl py-5 px-4 sm:py-6 sm:px-6 shadow-xl border my-auto" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                    <h3 id="staff-delete-modal-title" class="text-lg font-semibold mb-2" style="color: var(--on-surface);">Delete staff</h3>
                    <p id="staff-delete-modal-message" class="text-sm mb-6" style="color: var(--on-surface-variant);">Are you sure you want to delete this staff member? This action cannot be undone.</p>
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-2">
                        <button type="button" class="btn-secondary px-4 py-2.5 rounded-full text-sm w-full sm:w-auto" data-close="staff-delete-modal">Cancel</button>
                        <button type="button" id="staff-delete-modal-confirm" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full text-sm font-medium w-full sm:w-auto transition-opacity hover:opacity-95" style="background: var(--error-container); color: var(--on-error-container);">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(!$isEmpty)
    @push('scripts')
        <script>
            (function() {
                const csrf = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const deleteModal = document.getElementById('staff-delete-modal');
                const deleteModalTitle = document.getElementById('staff-delete-modal-title');
                const deleteModalMessage = document.getElementById('staff-delete-modal-message');
                const deleteModalConfirm = document.getElementById('staff-delete-modal-confirm');
                let pendingDeleteUrl = null;

                function openDeleteModal(name, url) {
                    pendingDeleteUrl = url;

                    if (deleteModalTitle) {
                        deleteModalTitle.textContent = 'Delete staff';
                    }

                    if (deleteModalMessage) {
                        const displayName = name || 'this staff member';
                        deleteModalMessage.textContent = `Are you sure you want to delete "${displayName}"? This action cannot be undone.`;
                    }

                    if (deleteModal) {
                        deleteModal.classList.remove('hidden');
                    }
                }
                function closeDeleteModal() {
                    if (deleteModal) deleteModal.classList.add('hidden');
                    pendingDeleteUrl = null;
                }

                document.querySelectorAll('[data-close="staff-delete-modal"]').forEach(function(el) {
                    el.addEventListener('click', closeDeleteModal);
                });

                if (deleteModalConfirm) {
                    deleteModalConfirm.addEventListener('click', function() {
                        if (!pendingDeleteUrl) return;
                        const btn = this;
                        if (typeof setButtonLoading === 'function') setButtonLoading(btn, true);
                        fetch(pendingDeleteUrl, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': csrf || '', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        })
                        .then(function(r) { return r.json().then(function(data) { return { ok: r.ok, status: r.status, data: data }; }); })
                        .then(function(res) {
                            closeDeleteModal();
                            if (res.ok && res.data && res.data.status === 'success') {
                                if (typeof flashSuccess === 'function') flashSuccess(res.data.message || 'Staff deleted.');
                                setTimeout(function() {
                                    window.location.href = res.data.redirect || '{{ route('admin.staff.index') }}';
                                }, 2800);
                            } else {
                                if (typeof flashError === 'function') flashError(res.data && res.data.message ? (Array.isArray(res.data.message) ? res.data.message.join(' ') : res.data.message) : 'Could not delete staff.');
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

                document.querySelectorAll('.staff-delete-btn').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        const name = this.getAttribute('data-staff-name') || 'this staff member';
                        const url = this.getAttribute('data-delete-url');
                        if (url) openDeleteModal(name, url);
                    });
                });
            })();
        </script>
    @endpush
    @endif
@endsection
