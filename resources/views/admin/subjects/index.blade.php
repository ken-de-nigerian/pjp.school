@php use App\Models\Subject; @endphp
@extends('layouts.app')

@section('content')
    @php
        $storeUrl = route('admin.subjects.store');
    @endphp
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <header class="mb-6 lg:mb-8 flex flex-col gap-4 sm:gap-5 lg:gap-6">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 min-w-0">
                    <div class="flex items-start gap-3 sm:gap-4 min-w-0">
                        <div class="min-w-0 flex-1">
                            <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-normal tracking-tight mb-1 sm:mb-1.5" style="color: var(--on-surface); letter-spacing: -0.02em;">Subjects</h1>
                            <p class="text-xs sm:text-sm md:text-base font-normal max-w-xl" style="color: var(--on-surface-variant);">Manage subjects by grade. Add or edit subjects from this page.</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2 sm:gap-3 min-w-0">
                    <a href="{{ route('admin.subjects.index', ['grade' => 'Junior']) }}" class="px-3 py-2 sm:px-4 sm:py-2.5 rounded-xl text-xs sm:text-sm font-medium transition-colors min-h-[2.5rem] sm:min-h-0 flex items-center justify-center {{ $filterGrade !== 'Junior' ? 'opacity-80 hover:opacity-100' : '' }}" style="{{ $filterGrade === 'Junior' ? 'background: var(--primary); color: var(--on-primary);' : 'background: var(--surface-container-high); color: var(--on-surface-variant);' }}">Junior ({{ $juniorCount }})</a>
                    <a href="{{ route('admin.subjects.index', ['grade' => 'Senior']) }}" class="px-3 py-2 sm:px-4 sm:py-2.5 rounded-xl text-xs sm:text-sm font-medium transition-colors min-h-[2.5rem] sm:min-h-0 flex items-center justify-center {{ $filterGrade !== 'Senior' ? 'opacity-80 hover:opacity-100' : '' }}" style="{{ $filterGrade === 'Senior' ? 'background: var(--primary); color: var(--on-primary);' : 'background: var(--surface-container-high); color: var(--on-surface-variant);' }}">Senior ({{ $seniorCount }})</a>
                    @can('create', Subject::class)
                    <button type="button" id="subject-open-create-modal" class="ml-auto inline-flex items-center justify-center gap-2 px-3 py-2 sm:px-4 sm:py-2.5 rounded-xl text-xs sm:text-sm font-medium transition-colors min-h-[2.5rem] sm:min-h-0 hover:opacity-100 border border-dashed border-gray-300 sm:border-solid" style="border-radius: 12px; background-color: var(--primary); color: var(--on-primary);">
                        <i class="fas fa-plus text-[10px] sm:text-xs" aria-hidden="true"></i>
                        <span>Add Subject</span>
                    </button>
                    @endcan
                </div>
            </header>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                @if($subjects->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 md:py-40 px-6">
                        <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5" style="border-radius: 16px;">
                            <i class="fas fa-book text-3xl" aria-hidden="true"></i>
                        </div>
                        <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No subjects yet</h2>
                        <p class="text-sm text-center max-w-md mb-6" style="color: var(--on-surface-variant);">Add your first subject to get started, or change the grade filter above.</p>
                        @can('create', Subject::class)
                            <div class="flex justify-center">
                                <button type="button" class="subject-open-create-modal btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] rounded-xl font-medium text-sm transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;">
                                    <i class="fas fa-plus text-sm" aria-hidden="true"></i>
                                    Add Subject
                                </button>
                            </div>
                        @endcan
                    </div>
                @else
                    <div class="hidden md:grid md:grid-cols-[auto_1fr_auto_auto] md:gap-4 lg:gap-6 px-4 sm:px-6 py-3.5 sticky top-0 z-10 min-w-0" style="background: var(--surface-container); border-bottom: 1px solid var(--outline-variant);">
                        <span class="text-xs font-semibold uppercase tracking-wider" style="color: var(--on-surface-variant);">#</span>
                        <span class="text-xs font-semibold uppercase tracking-wider min-w-0" style="color: var(--on-surface-variant);">Subject</span>
                        <span class="text-xs font-semibold uppercase tracking-wider" style="color: var(--on-surface-variant);">Grade</span>
                        <span class="text-xs font-semibold uppercase tracking-wider text-right" style="color: var(--on-surface-variant);">Actions</span>
                    </div>

                    <ul class="flex flex-col gap-3 md:gap-0 p-4 sm:px-6 md:p-0 list-none min-w-0" role="list" style="color: var(--on-surface);">
                        @foreach($subjects as $subject)
                            @php $rowNum = ($subjects->currentPage() - 1) * $subjects->perPage() + $loop->iteration; @endphp
                            <li class="subject-row flex flex-col gap-3 rounded-2xl border p-4 md:rounded-none md:border-0 md:border-b md:border-t-0 md:grid md:grid-cols-[auto_1fr_auto_auto] md:gap-4 lg:gap-6 md:items-center md:py-4 md:px-4 lg:px-6 md:min-w-0 transition-[background-color,box-shadow] duration-200 shadow-sm md:shadow-none md:hover:shadow-[var(--elevation-1)] md:hover:bg-[var(--surface-container-low)]" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                <div class="flex items-center gap-3 md:contents">
                                    <span class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center text-sm font-semibold md:w-8 md:h-8 md:place-self-center" style="background: var(--primary-container); color: var(--on-primary-container);">{{ $rowNum }}</span>
                                    <div class="flex flex-col min-w-0 overflow-hidden">
                                        <span class="text-xs font-medium md:sr-only" style="color: var(--on-surface-variant);">Subject</span>
                                        <p class="text-sm font-medium break-words min-w-0 truncate subject-row-name" style="color: var(--on-surface);">{{ e($subject->subject_name) }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="text-xs font-medium md:sr-only" style="color: var(--on-surface-variant);">Grade</span>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium subject-row-grade" style="background: var(--surface-container-high); color: var(--on-surface-variant);">{{ e($subject->grade) }}</span>
                                </div>

                                <div class="flex flex-wrap gap-2 md:justify-end md:flex-nowrap">
                                    @can('update', $subject)
                                    <button type="button" class="subject-open-edit-modal inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-medium transition-opacity hover:opacity-90 min-h-[2.75rem] md:min-h-0 flex-1 md:flex-initial" style="background: var(--primary-container); color: var(--on-primary-container);" data-subject-id="{{ $subject->id }}" data-subject-name="{{ e($subject->subject_name) }}" data-subject-grade="{{ e($subject->grade) }}">
                                        <i class="fas fa-pen text-xs" aria-hidden="true"></i>
                                        Edit
                                    </button>
                                    @endcan
                                    @can('delete', $subject)
                                    <form id="subject-delete-form-{{ $subject->id }}" action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" class="flex flex-1 md:flex-initial min-w-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="subject-delete-btn w-full inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-medium transition-opacity hover:opacity-90 min-h-[2.75rem] md:min-h-0 whitespace-nowrap" style="background: var(--error-container); color: var(--on-error-container);" data-form-id="subject-delete-form-{{ $subject->id }}" data-subject-name="{{ e($subject->subject_name) }}">
                                            <i class="fas fa-trash-alt text-xs" aria-hidden="true"></i>
                                            Delete
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    @if($subjects->hasPages())
                        <div class="px-4 sm:px-6 py-4" style="border-top: 1px solid var(--outline-variant);">
                            <x-pagination :paginator="$subjects" />
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </main>

    {{-- Create / Edit modal (index is auth:admin + viewAny subjects) --}}
    @auth('admin')
    <div id="subject-form-modal" class="fixed inset-0 z-50 hidden overflow-y-auto overscroll-contain" aria-modal="true" role="dialog" aria-labelledby="subject-form-modal-title">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="subject-form-modal" aria-hidden="true"></div>
        <div class="relative min-h-full min-h-[100dvh] flex items-center justify-center p-4 py-6 sm:p-6">
            <div class="relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-xl py-5 px-4 sm:py-6 sm:px-6 shadow-xl border my-auto" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                <h3 id="subject-form-modal-title" class="text-lg font-semibold mb-1" style="color: var(--on-surface);">Add Subject</h3>
                <p class="text-sm mb-5" style="color: var(--on-surface-variant);">Subject name and grade are required.</p>
                <form id="subject-form-modal-form" action="{{ $storeUrl }}" method="POST" class="space-y-4 min-w-0">
                    @csrf
                    <input type="hidden" name="_method" id="subject-form-method" value="" autocomplete="off">

                    <div class="form-group min-w-0">
                        <label for="modal_subject_name" class="form-label">Subject name</label>
                        <input type="text" id="modal_subject_name" name="subject_name" value="" class="form-input w-full min-w-0" placeholder="e.g. Mathematics" autocomplete="off">
                        <p id="modal_subject_name-error" class="form-error hidden mt-1.5 text-sm" style="color: var(--on-error-container);" aria-live="polite"></p>
                    </div>
                    <div class="form-group min-w-0">
                        <label for="modal_grade" class="form-label">Grade</label>
                        <select id="modal_grade" name="grade" class="form-select w-full min-w-0">
                            <option value="">Select grade</option>
                            <option value="Junior" @selected($filterGrade === 'Junior')>Junior</option>
                            <option value="Senior" @selected($filterGrade === 'Senior')>Senior</option>
                        </select>
                        <p id="modal_grade-error" class="form-error hidden mt-1.5 text-sm" style="color: var(--on-error-container);" aria-live="polite"></p>
                    </div>
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 pt-2">
                        <button type="button" class="btn-secondary px-4 py-2.5 rounded-full text-sm w-full sm:w-auto" data-close="subject-form-modal">Cancel</button>
                        <button type="submit" id="subject-form-modal-submit" class="btn-primary inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full text-sm font-medium w-full sm:w-auto">
                            <i class="fas fa-save text-xs" aria-hidden="true"></i>
                            <span id="subject-form-submit-label">Save Subject</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endauth

    @if($subjects->isNotEmpty())
    <div id="subject-delete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto overscroll-contain" aria-modal="true" role="dialog" aria-labelledby="subject-delete-modal-title">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="subject-delete-modal" aria-hidden="true"></div>
        <div class="relative min-h-full min-h-[100dvh] flex items-center justify-center p-4 py-6 sm:p-6">
            <div class="relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-xl py-5 px-4 sm:py-6 sm:px-6 shadow-xl border my-auto" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                <h3 id="subject-delete-modal-title" class="text-lg font-semibold mb-2" style="color: var(--on-surface);">Delete subject</h3>
                <p id="subject-delete-modal-message" class="text-sm mb-6" style="color: var(--on-surface-variant);">Are you sure you want to delete this subject?</p>
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-2">
                    <button type="button" class="btn-secondary px-4 py-2.5 rounded-full text-sm w-full sm:w-auto" data-close="subject-delete-modal">Cancel</button>
                    <button type="button" id="subject-delete-modal-confirm" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full text-sm font-medium w-full sm:w-auto transition-opacity hover:opacity-95" style="background: var(--error-container); color: var(--on-error-container);">Delete</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @push('scripts')
        <script>
            (function() {
                const storeUrl = @json($storeUrl);
                const filterGrade = @json($filterGrade);
                const csrf = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const editSubject = @json($editSubject ? ['id' => $editSubject->id, 'subject_name' => $editSubject->subject_name, 'grade' => $editSubject->grade] : null);

                const formModal = document.getElementById('subject-form-modal');
                const formModalForm = document.getElementById('subject-form-modal-form');
                const formModalTitle = document.getElementById('subject-form-modal-title');
                const formMethodInput = document.getElementById('subject-form-method');
                const modalName = document.getElementById('modal_subject_name');
                const modalGrade = document.getElementById('modal_grade');
                const submitBtn = document.getElementById('subject-form-modal-submit');
                const submitLabel = document.getElementById('subject-form-submit-label');

                function clearModalErrors() {
                    if (typeof clearFieldErrors === 'function') {
                        clearFieldErrors(['modal_subject_name', 'modal_grade']);
                    }
                    ['modal_subject_name', 'modal_grade'].forEach(function(id) {
                        const el = document.getElementById(id + '-error');
                        if (el) { el.textContent = ''; el.classList.add('hidden'); }
                    });
                }

                function mapModalErrors(errors) {
                    const map = {subject_name: 'modal_subject_name', grade: 'modal_grade'};
                    if (typeof showLaravelErrors === 'function') {
                        showLaravelErrors(errors, map);
                    } else if (errors && typeof errors === 'object') {
                        Object.keys(errors).forEach(function(k) {
                            const pid = (map[k] || k) + '-error';
                            const p = document.getElementById(pid);
                            if (p) {
                                p.textContent = Array.isArray(errors[k]) ? errors[k][0] : errors[k];
                                p.classList.remove('hidden');
                            }
                        });
                    }
                }

                function openFormModalCreate() {
                    if (!formModal || !formModalForm) return;
                    clearModalErrors();
                    formModalTitle.textContent = 'Add Subject';
                    if (submitLabel) submitLabel.textContent = 'Save Subject';
                    formModalForm.action = storeUrl;
                    if (formMethodInput) { formMethodInput.value = ''; formMethodInput.name = ''; }
                    if (modalName) modalName.value = '';
                    if (modalGrade) {
                        modalGrade.value = filterGrade === 'Senior' ? 'Senior' : 'Junior';
                    }
                    formModal.classList.remove('hidden');
                    if (modalName) setTimeout(function() { modalName.focus(); }, 100);
                }

                function openFormModalEdit(id, name, grade) {
                    if (!formModal || !formModalForm) return;
                    clearModalErrors();
                    formModalTitle.textContent = 'Edit Subject';
                    if (submitLabel) submitLabel.textContent = 'Update Subject';
                    formModalForm.action = storeUrl.replace(/\/?$/, '') + '/' + encodeURIComponent(id);
                    if (formMethodInput) { formMethodInput.name = '_method'; formMethodInput.value = 'PUT'; }
                    if (modalName) modalName.value = name || '';
                    if (modalGrade) modalGrade.value = (grade === 'Senior') ? 'Senior' : 'Junior';
                    formModal.classList.remove('hidden');
                    if (modalName) setTimeout(function() { modalName.focus(); }, 100);
                }

                function closeFormModal() {
                    if (formModal) formModal.classList.add('hidden');
                }

                document.querySelectorAll('#subject-open-create-modal, .subject-open-create-modal').forEach(function(el) {
                    el.addEventListener('click', function(e) { e.preventDefault(); openFormModalCreate(); });
                });
                document.querySelectorAll('.subject-open-edit-modal').forEach(function(el) {
                    el.addEventListener('click', function(e) {
                        e.preventDefault();
                        openFormModalEdit(
                            this.getAttribute('data-subject-id'),
                            this.getAttribute('data-subject-name'),
                            this.getAttribute('data-subject-grade')
                        );
                    });
                });
                document.querySelectorAll('[data-close="subject-form-modal"]').forEach(function(el) {
                    el.addEventListener('click', closeFormModal);
                });

                if (formModalForm && submitBtn) {
                    formModalForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        clearModalErrors();
                        const isPut = formMethodInput && formMethodInput.value === 'PUT';
                        const fd = new FormData(formModalForm);
                        if (!isPut && formMethodInput && !formMethodInput.value) {
                            fd.delete('_method');
                        }
                        setButtonLoading(submitBtn, true);
                        fetch(formModalForm.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: new URLSearchParams(fd)
                        })
                        .then(function(r) { return r.json().then(function(data) { return { ok: r.ok, status: r.status, data: data }; }); })
                        .then(function(res) {
                            if (res.ok && res.data.status === 'success') {
                                closeFormModal();
                                flashSuccess(res.data.message || 'Saved.');
                                setTimeout(function() {
                                    window.location.href = res.data.redirect || window.location.pathname + '?grade=' + encodeURIComponent((modalGrade && modalGrade.value) || filterGrade);
                                }, 2800);
                            } else if (res.data && res.data.errors) {
                                mapModalErrors(res.data.errors);
                            } else {
                                flashError(res.data && res.data.message ? (Array.isArray(res.data.message) ? res.data.message.join(' ') : res.data.message) : 'Could not save.');
                            }
                        })
                        .catch(function() { flashError('An error occurred. Please try again.'); })
                        .finally(function() { setButtonLoading(submitBtn, false); });
                    });
                }

                if (editSubject && formModal) {
                    openFormModalEdit(editSubject.id, editSubject.subject_name, editSubject.grade);
                    if (window.history && window.history.replaceState) {
                        const u = new URL(window.location.href);
                        u.searchParams.delete('edit');
                        window.history.replaceState({}, '', u.pathname + u.search);
                    }
                }
            })();
        </script>
        @if($subjects->isNotEmpty())
        <script>
            (function() {
                const deleteModal = document.getElementById('subject-delete-modal');
                if (!deleteModal) return;
                const deleteModalTitle = document.getElementById('subject-delete-modal-title');
                const deleteModalMessage = document.getElementById('subject-delete-modal-message');
                const deleteModalConfirm = document.getElementById('subject-delete-modal-confirm');
                let pendingDeleteFormId = null;

                function openDeleteModal(title, message, formId) {
                    pendingDeleteFormId = formId;
                    deleteModalTitle.textContent = title;
                    deleteModalMessage.textContent = message;
                    deleteModal.classList.remove('hidden');
                }
                function closeDeleteModal() {
                    deleteModal.classList.add('hidden');
                    pendingDeleteFormId = null;
                }

                document.querySelectorAll('[data-close="subject-delete-modal"]').forEach(function(el) {
                    el.addEventListener('click', closeDeleteModal);
                });

                deleteModalConfirm.addEventListener('click', function() {
                    if (!pendingDeleteFormId) return;
                    const form = document.getElementById(pendingDeleteFormId);
                    if (!form) return;
                    const btn = deleteModalConfirm;
                    const token = form.querySelector('input[name="_token"]');
                    const body = new URLSearchParams(new FormData(form));
                    setButtonLoading(btn, true);
                    fetch(form.action, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': token && token.value, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        body: body
                    })
                    .then(function(r) { return r.json().then(function(data) { return { ok: r.ok, data: data }; }); })
                    .then(function(res) {
                        closeDeleteModal();
                        if (res.ok && res.data.status === 'success') {
                            flashSuccess(res.data.message || 'Subject deleted successfully.');
                            setTimeout(function() {
                                window.location.href = res.data.redirect || '{{ route('admin.subjects.index', ['grade' => $filterGrade ?? 'Junior']) }}';
                            }, 2800);
                        } else {
                            flashError(Array.isArray(res.data.message) ? res.data.message.join(' ') : (res.data.message || 'Could not delete subject.'));
                        }
                    })
                    .catch(function() { flashError('An error occurred. Please try again.'); })
                    .finally(function() { setButtonLoading(btn, false); });
                });

                document.querySelectorAll('.subject-delete-btn').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        const name = this.getAttribute('data-subject-name') || 'this subject';
                        const formId = this.getAttribute('data-form-id');
                        openDeleteModal('Delete subject: ' + name, 'Are you sure you want to delete "' + name + '"? This cannot be undone.', formId);
                    });
                });
            })();
        </script>
        @endif
    @endpush
@endsection
