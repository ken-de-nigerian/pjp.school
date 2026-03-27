@extends('layouts.app', ['title' => 'Assign teacher to class'])

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Assign teacher to class"
                pill="Admin"
                title="Assign teacher to class"
                description="Select a teacher and one or more classes to assign."
            >
                <x-slot name="above">
                    <a href="{{ route('admin.teachers.index') }}" class="admin-page-hero__back mb-2 sm:mb-0">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Back to teachers
                    </a>
                </x-slot>
            </x-admin.hero-page>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl p-5 sm:p-6 lg:p-8" style="background: var(--surface-container-low); box-shadow: var(--elevation-1);">
                <div class="col-span-full flex-1 flex flex-col items-center justify-center min-h-[min(400px,50vh)] py-12 sm:py-16">
                    <div class="rounded-3xl p-4 sm:p-6 lg:p-8 overflow-hidden min-w-0 w-full" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                        <form action="{{ route('admin.assign_teacher_to_class.store') }}" method="POST" id="assign-form" class="space-y-5 sm:space-y-6">
                            @csrf

                            <div class="form-group min-w-0">
                                <label for="teachersList" class="form-label">Select teacher <span class="text-red-500" aria-hidden="true">*</span></label>
                                <x-forms.md-select-native id="teachersList" name="teachersList" class="form-select w-full min-w-0" required>
                                    <option value="">Choose teacher</option>
                                    @foreach($getTeachers as $t)
                                        <option value="{{ e($t->userId) }}" {{ old('teachersList') == $t->userId ? 'selected' : '' }}>
                                            {{ e(trim($t->firstname . ' ' . $t->lastname)) }}
                                        </option>
                                    @endforeach
                                </x-forms.md-select-native>
                                <p id="teachersList-error" class="form-error mt-1 text-sm {{ $errors->has('teachersList') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('teachersList') }}</p>
                            </div>

                    <div class="form-group min-w-0">
                        <span class="form-label block">Assign class(es) <span class="text-red-500" aria-hidden="true">*</span></span>
                        <p class="text-sm mt-0.5 mb-2" style="color: var(--on-surface-variant);">Select one or more classes for this teacher.</p>
                        <div class="flex flex-wrap gap-2 mb-3">
                            <button type="button" id="assign-classes-select-all" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-opacity hover:opacity-90" style="background: var(--primary-container); color: var(--on-primary-container); border-radius: 10px;">
                                <i class="fas fa-check-double" aria-hidden="true"></i>
                                Select all
                            </button>
                            <button type="button" id="assign-classes-clear" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-opacity hover:opacity-90 border" style="border-color: var(--outline-variant); background: var(--surface-container); color: var(--on-surface); border-radius: 10px;">
                                <i class="fas fa-times" aria-hidden="true"></i>
                                Clear
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-3 sm:gap-4">
                                    @php
                                        $oldClasses = old('assigned_class', []);
                                    @endphp
                                    @foreach($getClasses as $c)
                                        <label class="inline-flex items-center gap-2 cursor-pointer rounded-xl px-4 py-2.5 border transition-colors min-h-[44px]" style="background: var(--surface-container); border-color: var(--outline-variant); color: var(--on-surface);">
                                            <input type="checkbox" name="assigned_class[]" value="{{ e($c->class_name) }}" class="w-4 h-4 rounded border-2 cursor-pointer focus:ring-2 focus:ring-offset-0" style="border-color: var(--outline); accent-color: var(--primary);" {{ in_array($c->class_name, $oldClasses) ? 'checked' : '' }}>
                                            <span class="text-sm font-medium">{{ e($c->class_name) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <p id="assigned_class-error" class="form-error mt-1 text-sm {{ $errors->has('assigned_class') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('assigned_class') }}</p>
                            </div>

                            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2 min-w-0" style="border-top: 1px solid var(--outline-variant); padding-top: 1.25rem;">
                                <a href="{{ route('admin.teachers.index') }}" class="btn-secondary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 sm:min-w-[120px]" style="border-radius: 12px;">
                                    <i class="fas fa-times text-sm" aria-hidden="true"></i>
                                    Cancel
                                </a>
                                <button type="submit" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" data-preloader style="border-radius: 12px;">
                                    <i class="fas fa-link text-sm" aria-hidden="true"></i>
                                    Assign to Class
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
        (function() {
            let teacherAssignedClasses = @json($teacherAssignedClasses ?? []);
            const isFormRevalidation = {{ old('teachersList') !== null ? 'true' : 'false' }};
            const select = document.getElementById('teachersList');
            const checkboxes = document.querySelectorAll('#assign-form input[name="assigned_class[]"]');

            function applyClassesForTeacher(teacherId) {
                const classes = teacherAssignedClasses[teacherId] || [];
                checkboxes.forEach(function(cb) {
                    cb.checked = classes.indexOf(cb.value) !== -1;
                });
            }

            if (select) {
                select.addEventListener('change', function() {
                    const id = this.value;
                    if (id) {
                        applyClassesForTeacher(id);
                    } else {
                        checkboxes.forEach(function(cb) { cb.checked = false; });
                    }
                });
                if (select.value && !isFormRevalidation) {
                    applyClassesForTeacher(select.value);
                }
            }

            const selectAllBtn = document.getElementById('assign-classes-select-all');
            const clearBtn = document.getElementById('assign-classes-clear');
            if (selectAllBtn) {
                selectAllBtn.addEventListener('click', function() {
                    checkboxes.forEach(function(cb) { cb.checked = true; });
                });
            }
            if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                    checkboxes.forEach(function(cb) { cb.checked = false; });
                });
            }
        })();
    </script>
    @endpush
@endsection
