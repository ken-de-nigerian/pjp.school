@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            @php
                $registerSubjectsHeroDescription = $hasFilters
                    ? 'Class: ' . e($selectedClass) . ' — Select a student to register their subjects.'
                    : 'Choose a class to view students and register their subjects.';
            @endphp

            <x-admin.hero-page
                aria-label="Register students to subjects"
                pill="Admin"
                title="Register students to subjects"
                :description="$registerSubjectsHeroDescription"
            >
                <x-slot name="above">
                    <a href="{{ route('admin.subjects.index', ['grade' => 'Junior']) }}" class="admin-page-hero__back mb-2 sm:mb-0">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Back to subjects
                    </a>
                </x-slot>
                <x-slot name="actions">
                    <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto lg:flex-shrink-0">
                        @if($hasFilters)
                            <a href="{{ route('admin.subjects.fetch-classes') }}" class="admin-dashboard-hero__btn w-full sm:w-auto justify-center min-h-[44px] sm:min-h-0">
                                <i class="fas fa-filter text-xs" aria-hidden="true"></i>
                                <span>Change class</span>
                            </a>
                        @endif
                        @if(Route::has('admin.subjects.registered'))
                            <a href="{{ route('admin.subjects.registered') }}" class="admin-dashboard-hero__btn admin-dashboard-hero__btn--primary w-full sm:w-auto justify-center min-h-[44px] sm:min-h-0">
                                <i class="fas fa-eye text-xs" aria-hidden="true"></i>
                                <span>View registered</span>
                            </a>
                        @endif
                    </div>
                </x-slot>
            </x-admin.hero-page>

            @if(!$hasFilters)
            <div class="rounded-3xl p-4 sm:p-5 lg:p-6 mb-6 overflow-hidden min-w-0 w-full" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                <form method="GET" action="{{ route('admin.subjects.fetch-classes') }}" class="space-y-4 sm:space-y-5">
                    <div class="form-group min-w-0">
                        <label for="class" class="form-label">Select class</label>
                        <select id="class" name="class" class="form-select w-full min-w-0">
                            <option value="">Choose class</option>
                            @foreach($getClasses as $c)
                                @php $className = is_object($c) ? $c->class_name : $c; @endphp
                                <option value="{{ e($className) }}" {{ ($selectedClass ?? '') === $className ? 'selected' : '' }}>{{ e($className) }}</option>
                            @endforeach
                        </select>
                        <p id="class-error" class="form-error mt-1 text-sm {{ $errors->has('class') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('class') }}</p>
                    </div>
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2 min-w-0" style="border-top: 1px solid var(--outline-variant); padding-top: 1.25rem;">
                        <a href="{{ route('admin.subjects.fetch-classes') }}" class="btn-secondary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 sm:min-w-[120px]" style="border-radius: 12px;">
                            <i class="fas fa-times text-sm" aria-hidden="true"></i>
                            Clear
                        </a>
                        <button type="submit" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" data-preloader style="border-radius: 12px;">
                            <i class="fas fa-arrow-right text-sm" aria-hidden="true"></i>
                            View students
                        </button>
                    </div>
                </form>
            </div>
            @endif

            @if(!$hasFilters)
                <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden flex flex-col items-center justify-center py-16 md:py-24 px-6" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                    <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center" style="border-radius: 16px;">
                        <i class="fas fa-search text-3xl" aria-hidden="true"></i>
                    </div>
                    <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No filters selected</h2>
                    <p class="text-sm text-center max-w-sm" style="color: var(--on-surface-variant);">Choose a class in the form above, then click &quot;View students&quot; to see the list and register subjects.</p>
                </div>
            @else
            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                @if($students->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 md:py-40 px-6">
                        <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5" style="border-radius: 16px;">
                            <i class="fas fa-user-graduate text-3xl" aria-hidden="true"></i>
                        </div>
                        <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No students in this class</h2>
                        <p class="text-sm text-center max-w-sm mb-6" style="color: var(--on-surface-variant);">There are no students in {{ e($selectedClass) }}. Add or assign students first.</p>
                        <div class="flex justify-center">
                            <a href="{{ route('admin.subjects.fetch-classes') }}" class="btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] rounded-xl font-medium text-sm transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;">
                                <i class="fas fa-arrow-left text-sm" aria-hidden="true"></i>
                                Change class
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0 border-x border-b md:border-x md:border-b" style="border-color: var(--outline-variant);">
                        <ul class="flex flex-col gap-3 md:gap-0 md:divide-y divide-[var(--outline-variant)] p-4 sm:px-6 md:p-0 list-none min-w-0" role="list">
                            <li class="hidden md:flex items-center gap-3 sm:gap-4 px-5 sm:px-6 py-3" style="background: var(--surface-container); border-color: var(--outline-variant);">
                                <span class="text-xs font-medium w-8 flex-shrink-0" style="color: var(--on-surface-variant);">#</span>
                                <span class="w-10 flex-shrink-0" aria-hidden="true"></span>
                                <span class="text-xs font-medium flex-1 min-w-0" style="color: var(--on-surface-variant);">Name</span>
                                <span class="text-xs font-medium flex-shrink-0 w-10 sm:w-32 text-right" style="color: var(--on-surface-variant);">Actions</span>
                            </li>
                            @foreach($students as $index => $s)
                                @php
                                    $fullName = trim(($s->firstname ?? '') . ' ' . ($s->lastname ?? '') . ' ' . ($s->othername ?? ''));
                                    $avatarSrc = $s->imagelocation
                                        ? (str_starts_with($s->imagelocation, 'students/') ? asset('storage/' . $s->imagelocation) : asset('storage/students/' . $s->imagelocation))
                                        : asset('storage/students/default.png');
                                    $avatarInitial = $fullName ? mb_substr($fullName, 0, 1) : 'S';
                                @endphp
                                <li class="flex flex-col gap-0 rounded-2xl border p-4 md:rounded-none md:border-0 md:border-b md:border-t-0 md:flex-row md:items-center md:gap-4 md:py-4 md:px-5 lg:px-6 md:min-w-0 md:p-0 transition-[background-color] duration-200" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                    <div class="flex items-center gap-3 md:contents">
                                        <span class="text-sm font-medium w-8 flex-shrink-0 md:block" style="color: var(--on-surface-variant);">{{ $index + 1 }}</span>
                                        <img src="{{ $avatarSrc }}" alt="" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($avatarInitial) }}&size=80'">
                                        <div class="min-w-0 flex-1 md:min-w-0 md:flex-1">
                                            <span class="text-xs font-medium md:sr-only" style="color: var(--on-surface-variant);">Name</span>
                                            <p class="text-sm font-medium truncate" style="color: var(--on-surface);">
                                                @if(Route::has('admin.students.show'))
                                                    <a href="{{ route('admin.students.show', $s) }}" class="transition-opacity hover:opacity-80" style="color: var(--primary);">{{ $fullName ?: '—' }}</a>
                                                @else
                                                    {{ $fullName ?: '—' }}
                                                @endif
                                            </p>
                                            <p class="text-xs truncate mt-0.5" style="color: var(--on-surface-variant);">{{ $s->reg_number ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-3 border-t md:border-t-0 md:mt-0 md:pt-0 w-full flex flex-row items-center justify-end gap-3 md:contents" style="border-color: var(--outline-variant);">
                                        <span class="w-full md:w-48 md:flex-shrink-0 md:ml-auto">
                                            <button type="button" class="register-subject-btn inline-flex items-center justify-center gap-1.5 px-3 py-2.5 sm:px-4 sm:py-2 rounded-xl text-xs sm:text-sm font-medium transition-opacity hover:opacity-90 w-full" style="background: var(--primary-container); color: var(--on-primary-container); border-radius: 12px;" data-reg="{{ e($s->reg_number) }}" data-name="{{ e($fullName ?: $s->reg_number) }}" data-subjects="{{ e($s->subjects ?? '') }}">
                                                <i class="fas fa-list-check text-xs" aria-hidden="true"></i>
                                                <span>Register subjects</span>
                                            </button>
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @if(method_exists($students, 'hasPages') && $students->hasPages())
                        <div class="px-5 sm:px-6 py-4" style="border-top: 1px solid var(--outline-variant); background: var(--surface-container-low);">
                            <x-pagination :paginator="$students" />
                        </div>
                    @endif
                @endif
            </div>
            @endif
        </div>
    </main>

    <div id="register-subject-modal" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 sm:p-6 bg-black/50 backdrop-blur-sm" aria-modal="true" role="dialog" aria-labelledby="register-subject-modal-title">
        <div class="register-subject-modal-panel relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-2xl shadow-2xl flex flex-col" style="background: var(--surface-container-lowest); border: 1px solid var(--outline-variant); box-shadow: var(--elevation-2);">
            <div class="flex-shrink-0 px-5 sm:px-6 pt-5 sm:pt-6 pb-3 flex items-start justify-between gap-3" style="border-bottom: 1px solid var(--outline-variant);">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-3 mb-1">
                        <h3 id="register-subject-modal-title" class="text-lg font-semibold truncate" style="color: var(--on-surface);">Register subjects for <span id="register-modal-student-name"></span></h3>
                    </div>
                    <p class="text-sm mt-1" style="color: var(--on-surface-variant);">Select the subjects to register for this student.</p>
                </div>
                <button type="button" onclick="closeModal('register-subject-modal')" class="header-icon-btn w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" aria-label="Close" style="color: var(--on-surface);">
                    <i class="fas fa-times text-sm" aria-hidden="true"></i>
                </button>
            </div>

            <form id="register-subject-form" class="flex flex-col flex-1 min-h-0 flex">
                <input type="hidden" name="studentsList" id="modal-students-list">
                <div class="flex-1 min-h-0 overflow-y-auto px-5 sm:px-6 py-4">
                    <p id="studentsList-error" class="form-error text-sm mb-2 hidden" aria-live="polite"></p>
                    <p id="subjectsList-error" class="form-error text-sm mb-2 hidden" aria-live="polite"></p>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="text-xs font-medium uppercase tracking-wider" style="color: var(--on-surface-variant);">Subjects</span>
                        <div class="flex gap-2">
                            <button type="button" id="register-modal-select-all" class="text-xs font-medium px-2 py-1 rounded-lg transition-colors" style="color: var(--primary); background: var(--primary-container);">Select all</button>
                            <button type="button" id="register-modal-clear" class="text-xs font-medium px-2 py-1 rounded-lg transition-colors" style="color: var(--on-surface-variant); background: var(--surface-container-high);">Clear</button>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 rounded-xl p-2 border max-h-52 overflow-y-auto" style="border-color: var(--outline-variant); background: var(--surface-container-low);">
                        @foreach($subjects as $sub)
                            <label class="register-subject-option flex items-center gap-2 cursor-pointer px-3 py-2.5 rounded-lg border transition-colors min-h-[2.5rem] w-full text-left hover:bg-[var(--surface-container-high)] focus-within:bg-[var(--surface-container-high)]" style="border-color: var(--outline-variant); color: var(--on-surface);">
                                <input type="checkbox" name="subjectsList[]" value="{{ e($sub->subject_name) }}" class="register-subject-checkbox form-checkbox-input w-4 h-4 sm:w-5 sm:h-5 rounded border-2 flex-shrink-0 cursor-pointer focus:ring-2 focus:ring-offset-0 focus:ring-[var(--primary)] focus:outline-none" style="border-color: var(--outline); accent-color: var(--primary);">
                                <span class="text-xs sm:text-sm font-medium truncate min-w-0">{{ e($sub->subject_name) }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="flex-shrink-0 flex flex-col-reverse sm:flex-row justify-end gap-2 px-5 sm:px-6 py-4" style="border-top: 1px solid var(--outline-variant); background: var(--surface-container-lowest);">
                    <button type="button" onclick="closeModal('register-subject-modal')" class="btn-secondary px-4 py-2.5 rounded-xl text-sm w-full sm:w-auto">Cancel</button>
                    <button type="submit" id="register-subject-submit-btn" class="btn-primary inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium w-full sm:w-auto transition-opacity hover:opacity-95" style="border-radius: 12px;">Save</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                const studentNameEl = document.getElementById('register-modal-student-name');
                const studentsListEl = document.getElementById('modal-students-list');
                const form = document.getElementById('register-subject-form');
                const submitBtn = document.getElementById('register-subject-submit-btn');
                const csrf = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                function clearRegisterModalErrors() {
                    ['studentsList', 'subjectsList'].forEach(function(id) {
                        const el = document.getElementById(id + '-error');
                        if (el) { el.textContent = ''; el.classList.add('hidden'); }
                    });
                }

                document.querySelectorAll('.register-subject-btn').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        clearRegisterModalErrors();
                        studentsListEl.value = this.getAttribute('data-reg');
                        studentNameEl.textContent = this.getAttribute('data-name');
                        const subjectsStr = this.getAttribute('data-subjects') || '';
                        const registered = subjectsStr.split(',').map(function (s) {
                            return s.trim();
                        }).filter(Boolean);
                        if (form) {
                            form.querySelectorAll('.register-subject-checkbox').forEach(function(cb) {
                                cb.checked = registered.indexOf(cb.value) !== -1;
                            });
                        }
                        openModal('register-subject-modal');
                    });
                });

                document.getElementById('register-modal-select-all') && document.getElementById('register-modal-select-all').addEventListener('click', function() {
                    if (form) form.querySelectorAll('.register-subject-checkbox').forEach(function(c) { c.checked = true; });
                });
                document.getElementById('register-modal-clear') && document.getElementById('register-modal-clear').addEventListener('click', function() {
                    if (form) form.querySelectorAll('.register-subject-checkbox').forEach(function(c) { c.checked = false; });
                });

            if (form) form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (!csrf) return;
                    clearRegisterModalErrors();
                    setButtonLoading(submitBtn, true);
                    const formData = new FormData(form);
                    fetch('{{ route("admin.subjects.register-subjects") }}', {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(function(r) {
                        if (r.status === 422) {
                            return r.json().then(function(data) {
                                if (data.errors && typeof showLaravelErrors === 'function') {
                                    showLaravelErrors(data.errors);
                                } else {
                                    flashError(data.message || 'Please correct the errors and try again.');
                                }
                                throw new Error('Validation failed');
                            });
                        }
                        return r.json();
                    })
                    .then(function(data) {
                        if (data.status === 'success') {
                            flashSuccess(data.message || 'Subjects registered successfully.');
                            closeModal('register-subject-modal');
                        } else {
                            flashError(Array.isArray(data.message) ? data.message.join(' ') : (data.message || 'Could not register subjects.'));
                        }
                    })
                    .catch(function(err) {
                        if (err.message !== 'Validation failed' && typeof flashError === 'function') {
                            flashError('An error occurred. Please try again.');
                        }
                    })
                    .finally(function() { setButtonLoading(submitBtn, false); });
                });
            })();
        </script>
    @endpush
@endsection
