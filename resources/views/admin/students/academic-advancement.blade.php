@extends('layouts.app', ['title' => 'Academic advancement'])

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Promote students"
                pill="Admin"
                title="Promote students"
                description="Promotion is done class by class starting from SSS 3 down to JSS 1."
            >
                <x-slot name="above">
                    <a href="{{ route('admin.classes') }}" class="admin-page-hero__back mb-2 sm:mb-0">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Back to students
                    </a>
                </x-slot>
                @if($layoutRole->manage_students ?? 0)
                    <x-slot name="actions">
                        <a href="{{ route('admin.students.demote_students') }}" class="admin-dashboard-hero__btn admin-dashboard-hero__btn--primary w-full lg:w-auto justify-center min-h-[44px] sm:min-h-0">
                            <i class="fas fa-arrow-down-long text-[10px] sm:text-xs" aria-hidden="true"></i>
                            <span>Demote students</span>
                        </a>
                    </x-slot>
                @endif
            </x-admin.hero-page>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl p-5 sm:p-6 lg:p-8" style="background: var(--surface-container-low); box-shadow: var(--elevation-1);">
                <div class="col-span-full flex-1 flex flex-col items-center justify-center min-h-[min(400px,50vh)] py-12 sm:py-16">
                    <div class="rounded-3xl p-4 sm:p-6 lg:p-8 overflow-hidden min-w-0 w-full" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                        <form action="{{ route('admin.students.promote') }}" method="POST" class="p-5 sm:p-6 space-y-5" id="promote-form">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                                <div class="md:col-span-1 form-group">
                                    <label for="promoteStudentFrom" class="form-label">From this class</label>
                                    <x-forms.md-select-native id="promoteStudentFrom" name="from_class" class="form-select" data-load-students>
                                        <option value="">Select a class</option>
                                        @foreach(array_reverse($getClasses) as $class)
                                            <option value="{{ e($class->class_name) }}">{{ e($class->class_name) }}</option>
                                        @endforeach
                                    </x-forms.md-select-native>
                                    <p id="from_class-error" class="form-error {{ $errors->has('from_class') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('from_class') }}</p>
                                </div>

                                <div class="md:col-span-1 form-group">
                                    <label for="promoteStudentTo" class="form-label">To this class</label>
                                    <x-forms.md-select-native id="promoteStudentTo" name="to_class" class="form-select">
                                        <option value="">Select a class</option>
                                        <option value="Graduated">Graduated</option>
                                        @foreach(array_reverse($getClasses) as $class)
                                            <option value="{{ e($class->class_name) }}">{{ e($class->class_name) }}</option>
                                        @endforeach
                                    </x-forms.md-select-native>
                                    <p id="to_class-error" class="form-error {{ $errors->has('to_class') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('to_class') }}</p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="studentId" class="form-label">Select students</label>
                                <div id="studentId" class="rounded-2xl border overflow-hidden max-h-64" style="border-color: var(--outline-variant); background: var(--surface-container-lowest);">
                                    <div class="px-4 py-2 border-b text-xs sm:text-sm flex items-center justify-between gap-2"
                                         style="border-color: var(--outline-variant); color: var(--on-surface-variant);">
                                        <span class="font-medium">Students in selected class</span>
                                        <div class="flex items-center gap-2">
                                            <button type="button" id="promote-select-all" class="text-[11px] sm:text-xs font-medium px-2 py-1 rounded-lg transition-colors" style="color: var(--primary); background: var(--primary-container);">
                                                Select all
                                            </button>
                                            <button type="button" id="promote-clear" class="text-[11px] sm:text-xs font-medium px-2 py-1 rounded-lg transition-colors" style="color: var(--on-surface-variant); background: var(--surface-container-high);">
                                                Clear
                                            </button>
                                        </div>
                                    </div>
                                    <div id="student-list-container" class="max-h-52 overflow-y-auto">
                                        <div class="px-4 py-3 text-xs sm:text-sm" style="color: var(--on-surface-variant);">
                                            Select a class above to load students.
                                        </div>
                                    </div>
                                </div>
                                <p id="student_ids-error" class="form-error {{ $errors->has('student_ids') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('student_ids') }}</p>
                            </div>

                            <div class="pt-2 border-t" style="border-color: var(--outline-variant);">
                                <button type="submit" id="promoteBtn" class="btn-primary w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 999px;">
                                    <i class="fas fa-arrow-up-long text-xs" aria-hidden="true"></i>
                                    <span>Promote students</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fromSelect = document.getElementById('promoteStudentFrom');
            const studentListContainer = document.getElementById('student-list-container');
            const selectAllBtn = document.getElementById('promote-select-all');
            const clearBtn = document.getElementById('promote-clear');
            const studentShowBase = @json(rtrim(route('admin.students.show', ['student' => 0]), '0'));

            function setMessage(text) {
                if (!studentListContainer) return;
                studentListContainer.innerHTML = '';
                const div = document.createElement('div');
                div.className = 'px-4 py-3 text-xs sm:text-sm';
                div.style.color = 'var(--on-surface-variant)';
                div.textContent = text;
                studentListContainer.appendChild(div);
            }

            function toggleAll(checked) {
                if (!studentListContainer) return;
                studentListContainer.querySelectorAll('input[type="checkbox"][name="student_ids[]"]').forEach(function (cb) {
                    cb.checked = checked;
                });
            }

            if (selectAllBtn) {
                selectAllBtn.addEventListener('click', function () {
                    toggleAll(true);
                });
            }

            if (clearBtn) {
                clearBtn.addEventListener('click', function () {
                    toggleAll(false);
                });
            }

            if (fromSelect && studentListContainer) {
                fromSelect.addEventListener('change', function () {
                    const classVal = this.value;
                    if (!classVal) {
                        setMessage('Select a class above to load students.');
                        return;
                    }

                    setMessage('Loading…');

                    fetch('{{ route("admin.students.by-class") }}?class=' + encodeURIComponent(classVal), {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    }).then(function (r) { return r.json(); }).then(function (data) {
                        studentListContainer.innerHTML = '';

                        if (data && data.students && data.students.length) {
                            const grid = document.createElement('div');
                            grid.className = 'grid grid-cols-1 sm:grid-cols-2 gap-2 px-3 py-3';

                            data.students.forEach(function (s) {
                                const label = document.createElement('label');
                                label.className = 'flex items-center gap-2 cursor-pointer px-3 py-2.5 rounded-lg border transition-colors min-h-[2.5rem] w-full text-left hover:bg-[var(--surface-container-high)] focus-within:bg-[var(--surface-container-high)]';
                                label.style.borderColor = 'var(--outline-variant)';
                                label.style.color = 'var(--on-surface)';

                                const checkbox = document.createElement('input');
                                checkbox.type = 'checkbox';
                                checkbox.name = 'student_ids[]';
                                checkbox.value = s.id;
                                checkbox.className = 'form-checkbox-input w-4 h-4 sm:w-5 sm:h-5 rounded border-2 flex-shrink-0 cursor-pointer focus:ring-2 focus:ring-offset-0 focus:ring-[var(--primary)] focus:outline-none';
                                checkbox.style.borderColor = 'var(--outline)';

                                const textWrapper = document.createElement('span');
                                textWrapper.className = 'flex flex-col text-xs sm:text-sm min-w-0 flex-1';

                                const nameLine = document.createElement('span');
                                nameLine.className = 'font-medium truncate flex items-center gap-1.5 flex-wrap';
                                const nameText = ((s.firstname || '') + ' ' + (s.lastname || '')).trim() || (s.reg_number || 'Student');

                                if (studentShowBase && s.id) {
                                    const showLink = document.createElement('a');
                                    showLink.href = studentShowBase + s.id;
                                    showLink.className = 'transition-opacity hover:opacity-80 truncate';
                                    showLink.style.color = 'var(--primary)';
                                    showLink.textContent = nameText;
                                    showLink.setAttribute('target', '_blank');
                                    showLink.setAttribute('rel', 'noopener');
                                    nameLine.appendChild(showLink);
                                } else {
                                    nameLine.textContent = nameText;
                                }

                                const regLine = document.createElement('span');
                                regLine.className = 'text-[11px] sm:text-xs truncate';
                                regLine.style.color = 'var(--on-surface-variant)';
                                regLine.textContent = s.reg_number || '';

                                textWrapper.appendChild(nameLine);
                                textWrapper.appendChild(regLine);

                                label.appendChild(checkbox);
                                label.appendChild(textWrapper);
                                grid.appendChild(label);
                            });

                            studentListContainer.appendChild(grid);
                        } else {
                            setMessage('No students in this class.');
                        }
                    }).catch(function () {
                        setMessage('Failed to load students.');
                    });
                });
            }

            const form = document.getElementById('promote-form');
            const promoteBtn = document.getElementById('promoteBtn');
            const promoteFieldIds = ['from_class', 'to_class', 'student_ids'];

            if (form && promoteBtn) {
                let token = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                if (!token) token = form.querySelector('input[name="_token"]') && form.querySelector('input[name="_token"]').value;

                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    if (typeof clearFieldErrors === 'function') clearFieldErrors(promoteFieldIds);

                    if (!token) {
                        if (typeof flashError === 'function') flashError('Security token missing. Please refresh the page.');
                        return;
                    }

                    if (typeof setButtonLoading === 'function') setButtonLoading(promoteBtn, true);

                    fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }).then(function (response) {
                        if (response.status === 422) {
                            return response.json().then(function (data) {
                                if (data.errors && typeof showLaravelErrors === 'function') {
                                    showLaravelErrors(data.errors);
                                }
                                throw new Error('Validation failed');
                            });
                        }
                        return response.json();
                    }).then(function (data) {
                        if (data.status === 'success' && typeof flashSuccess === 'function') {
                            flashSuccess(data.message || 'Students promoted.');
                        } else if (data.status === 'error' && typeof flashError === 'function') {
                            flashError(data.message || 'Unable to promote students.');
                        }
                    }).catch(function (err) {
                        if (err.message !== 'Validation failed' && typeof flashError === 'function') {
                            flashError('An error occurred while promoting students.');
                        }
                    }).finally(function () {
                        if (typeof setButtonLoading === 'function') setButtonLoading(promoteBtn, false);
                    });
                });
            }
        });
    </script>
    @endpush
@endsection
