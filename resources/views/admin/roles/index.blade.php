@php use App\Models\Role; @endphp
@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <header class="mb-6 lg:mb-8 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 min-w-0">
                <div class="flex items-start gap-3 sm:gap-4 min-w-0">
                    <div class="min-w-0 flex-1">
                        <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-normal tracking-tight mb-1 sm:mb-1.5" style="color: var(--on-surface); letter-spacing: -0.02em;">Roles &amp; permissions</h1>
                        <p class="text-xs sm:text-sm md:text-base font-normal max-w-2xl" style="color: var(--on-surface-variant);">Define who can access attendance, results, students, settings, and more. Edit or add roles below.</p>
                    </div>
                </div>

                @can('create', Role::class)
                    <a href="{{ route('admin.roles.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-3 sm:py-2.5 rounded-xl text-sm font-medium transition-colors border border-dashed border-gray-300 sm:border-solid" style="border-radius: 12px; background-color: var(--primary); color: var(--on-primary);">
                        <i class="fas fa-plus text-xs sm:text-sm" aria-hidden="true"></i>
                        <span>Add role</span>
                    </a>
                @endcan
            </header>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                @if($roles->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 md:py-40 px-6">
                        <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center" style="border-radius: 16px;">
                            <i class="fas fa-user-shield text-3xl" aria-hidden="true"></i>
                        </div>
                        <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No roles yet</h2>
                        <p class="text-sm text-center max-w-sm mb-6" style="color: var(--on-surface-variant);">Create a role and assign permissions for staff accounts.</p>

                        @can('create', Role::class)
                            <div class="flex justify-center">
                                <a href="{{ route('admin.roles.create') }}" class="btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] rounded-xl font-medium text-sm transition-all duration-200 hover:opacity-95" style="border-radius: 12px;">
                                    <i class="fas fa-plus text-sm" aria-hidden="true"></i>
                                    Add role
                                </a>
                            </div>
                        @endcan
                    </div>
                @else
                    <div class="hidden md:grid md:grid-cols-[auto_minmax(0,14rem)_1fr_auto] md:gap-4 lg:gap-6 px-4 sm:px-6 py-3.5 sticky top-0 z-10 min-w-0 items-center" style="background: var(--surface-container); border-bottom: 1px solid var(--outline-variant);">
                        <span class="text-xs font-semibold uppercase tracking-wider" style="color: var(--on-surface-variant);">#</span>
                        <span class="text-xs font-semibold uppercase tracking-wider min-w-0" style="color: var(--on-surface-variant);">Role</span>
                        <span class="text-xs font-semibold uppercase tracking-wider min-w-0" style="color: var(--on-surface-variant);">Enabled permissions</span>
                        <span class="text-xs font-semibold uppercase tracking-wider text-right shrink-0" style="color: var(--on-surface-variant);">Actions</span>
                    </div>

                    <ul class="flex flex-col gap-3 md:gap-0 p-4 sm:px-6 md:p-0 list-none min-w-0" role="list">
                        @foreach($roles as $role)
                            @php
                                $rowNum = ($roles->currentPage() - 1) * $roles->perPage() + $loop->iteration;
                                $enabledLabels = $role->enabledPermissionLabels();
                            @endphp
                            <li class="flex flex-col gap-3 rounded-2xl border p-4 md:rounded-none md:border-0 md:border-b md:border-t-0 md:grid md:grid-cols-[auto_minmax(0,14rem)_1fr_auto] md:gap-4 lg:gap-6 md:items-start md:py-4 md:px-4 lg:px-6 md:min-w-0 transition-[background-color,box-shadow] duration-200 shadow-sm md:shadow-none md:hover:shadow-[var(--elevation-1)] md:hover:bg-[var(--surface-container-low)]" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                <div class="flex items-center gap-3 md:contents">
                                    <span class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center text-sm font-semibold md:w-8 md:h-8 md:place-self-start md:mt-0.5" style="background: var(--primary-container); color: var(--on-primary-container);">{{ $rowNum }}</span>
                                    <div class="min-w-0 flex-1 md:min-w-0 overflow-hidden md:place-self-start">
                                        <span class="text-xs font-medium md:sr-only" style="color: var(--on-surface-variant);">Role</span>
                                        <p class="text-sm font-semibold break-words" style="color: var(--on-surface);">{{ e($role->name) }}</p>
                                    </div>
                                </div>

                                <div class="min-w-0 md:min-w-0 md:place-self-start w-full">
                                    <span class="text-xs font-medium md:sr-only mb-1.5 block" style="color: var(--on-surface-variant);">Enabled permissions</span>
                                    @if(count($enabledLabels) !== 0)
                                        <div class="flex flex-wrap gap-1.5" role="list" aria-label="Enabled permissions">
                                            @foreach($enabledLabels as $permLabel)
                                                <span class="inline-flex items-center max-w-full rounded-lg px-2 py-1 text-[11px] sm:text-xs font-medium leading-tight" style="background: var(--surface-container-high); color: var(--on-surface-variant);" role="listitem">{{ e($permLabel) }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-xs italic" style="color: var(--on-surface-variant);">None enabled</p>
                                    @endif
                                </div>

                                <div class="flex flex-wrap gap-2 md:justify-end md:flex-nowrap md:place-self-start md:shrink-0 md:pt-0.5">
                                    @can('update', $role)
                                        <a href="{{ route('admin.roles.edit', $role->id) }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-medium transition-opacity hover:opacity-90 min-h-[2.75rem] md:min-h-0 flex-1 md:flex-initial" style="background: var(--primary-container); color: var(--on-primary-container); border-radius: 12px;">
                                            <i class="fas fa-pen text-xs" aria-hidden="true"></i>
                                            Edit
                                        </a>
                                    @endcan

                                    @can('delete', $role)
                                        <form id="role-delete-form-{{ $role->id }}" action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="flex flex-1 md:flex-initial min-w-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="role-delete-btn w-full inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-medium transition-opacity hover:opacity-90 min-h-[2.75rem] md:min-h-0 whitespace-nowrap" style="background: var(--error-container); color: var(--on-error-container); border-radius: 12px;" data-form-id="role-delete-form-{{ $role->id }}" data-role-name="{{ e($role->name) }}">
                                                <i class="fas fa-trash-alt text-xs" aria-hidden="true"></i>
                                                Delete
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    @if($roles->hasPages())
                        <div class="px-4 sm:px-6 py-4" style="border-top: 1px solid var(--outline-variant); background: var(--surface-container-low);">
                            <x-pagination :paginator="$roles" />
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </main>

    @if($roles->isNotEmpty())
        <div id="role-delete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto overscroll-contain" aria-modal="true" role="dialog" aria-labelledby="role-delete-modal-title">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="role-delete-modal" aria-hidden="true"></div>
            <div class="relative min-h-full min-h-[100dvh] flex items-center justify-center p-4 py-6 sm:p-6">
                <div class="relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-xl py-5 px-4 sm:py-6 sm:px-6 shadow-xl border my-auto" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                    <h3 id="role-delete-modal-title" class="text-lg font-semibold mb-2" style="color: var(--on-surface);">Delete role</h3>
                    <p id="role-delete-modal-message" class="text-sm mb-6" style="color: var(--on-surface-variant);">Are you sure you want to delete this role?</p>
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-2">
                        <button type="button" class="btn-secondary px-4 py-2.5 rounded-full text-sm w-full sm:w-auto" data-close="role-delete-modal">Cancel</button>
                        <button type="button" id="role-delete-modal-confirm" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full text-sm font-medium w-full sm:w-auto transition-opacity hover:opacity-95" style="background: var(--error-container); color: var(--on-error-container);">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                (function() {
                    const deleteModal = document.getElementById('role-delete-modal');
                    const deleteModalTitle = document.getElementById('role-delete-modal-title');
                    const deleteModalMessage = document.getElementById('role-delete-modal-message');
                    const deleteModalConfirm = document.getElementById('role-delete-modal-confirm');
                    let pendingFormId = null;
                    let indexUrl = @json(route('admin.roles.index'));

                    function openModal(title, message, formId) {
                        pendingFormId = formId;
                        deleteModalTitle.textContent = title;
                        deleteModalMessage.textContent = message;
                        deleteModal.classList.remove('hidden');
                    }
                    function closeModal() {
                        deleteModal.classList.add('hidden');
                        pendingFormId = null;
                    }
                    document.querySelectorAll('[data-close="role-delete-modal"]').forEach(function(el) {
                        el.addEventListener('click', closeModal);
                    });
                    deleteModalConfirm.addEventListener('click', function() {
                        if (!pendingFormId) return;
                        const form = document.getElementById(pendingFormId);
                        if (!form) return;
                        const token = form.querySelector('input[name="_token"]');
                        setButtonLoading(deleteModalConfirm, true);
                        fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-CSRF-TOKEN': token ? token.value : '',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: new URLSearchParams(new FormData(form))
                        })
                        .then(function(r) { return r.json().then(function(d) { return { ok: r.ok, data: d }; }); })
                        .then(function(res) {
                            closeModal();
                            if (res.ok && res.data.status === 'success') {
                                flashSuccess(res.data.message || 'Role deleted.');
                                setTimeout(function() {
                                    window.location.href = res.data.redirect && res.data.redirect.indexOf('http') === 0 ? res.data.redirect : indexUrl;
                                }, 2800);
                            } else {
                                flashError(res.data.message || 'Could not delete role.');
                            }
                        })
                        .catch(function() { flashError('An error occurred.'); })
                        .finally(function() { setButtonLoading(deleteModalConfirm, false); });
                    });
                    document.querySelectorAll('.role-delete-btn').forEach(function(btn) {
                        btn.addEventListener('click', function() {
                            const name = this.getAttribute('data-role-name') || 'this role';
                            openModal('Delete role', 'Delete "' + name + '"? Staff using this role may need reassignment. This cannot be undone.', this.getAttribute('data-form-id'));
                        });
                    });
                })();
            </script>
        @endpush
    @endif
@endsection
