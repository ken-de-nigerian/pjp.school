@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <div class="mb-4 sm:mb-6 w-fit">
                <a href="{{ route('admin.subjects.index', ['grade' => 'Junior']) }}" class="inline-flex items-center gap-2 text-sm font-medium transition-opacity hover:opacity-80" style="color: var(--on-surface-variant);">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    Back to Subjects
                </a>
            </div>

            <header class="mb-6 lg:mb-8 flex flex-col gap-4 sm:gap-5">
                <div class="flex items-start gap-3 sm:gap-4 min-w-0">
                    <div class="min-w-0 flex-1">
                        <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-normal tracking-tight mb-1 sm:mb-1.5" style="color: var(--on-surface); letter-spacing: -0.02em;">View registered students' subjects</h1>
                        <p class="text-xs sm:text-sm md:text-base font-normal max-w-xl" style="color: var(--on-surface-variant);">Filter by class and subject to see which students are registered to which subjects.</p>
                    </div>
                </div>
            </header>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl p-5 sm:p-6 lg:p-8" style="background: var(--surface-container-low); box-shadow: var(--elevation-1);">
                <div class="col-span-full flex-1 flex flex-col items-center justify-center min-h-[min(400px,50vh)] py-12 sm:py-16">
                    <div class="rounded-3xl p-4 sm:p-6 lg:p-8 overflow-hidden min-w-0 w-full" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                        <form method="GET" action="{{ route('admin.subjects.registered') }}" id="registered-filter-form" class="space-y-5 sm:space-y-6">
                            <div class="form-group min-w-0">
                                <label for="filter-class" class="form-label">Select class</label>
                                <select id="filter-class" name="class" class="form-select w-full min-w-0">
                                    <option value="">Choose class</option>
                                    @foreach($getClasses as $c)
                                        @php $className = is_object($c) ? $c->class_name : $c; @endphp
                                        <option value="{{ e($className) }}" {{ old('class') === $className ? 'selected' : '' }}>{{ e($className) }}</option>
                                    @endforeach
                                </select>
                                <p id="class-error" class="form-error mt-1 text-sm {{ $errors->has('class') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('class') }}</p>
                            </div>

                            <div class="form-group min-w-0">
                                <label for="filter-subjects" class="form-label">Select subject</label>
                                <select id="filter-subjects" name="subjects" class="form-select w-full min-w-0">
                                    <option value="">Choose subject</option>
                                    @foreach($getSubjects as $s)
                                        <option value="{{ e($s->subject_name) }}" data-grade="{{ e($s->grade) }}" {{ old('subjects') === $s->subject_name ? 'selected' : '' }}>{{ e($s->subject_name) }}</option>
                                    @endforeach
                                </select>
                                <p id="subjects-error" class="form-error mt-1 text-sm {{ $errors->has('subjects') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('subjects') }}</p>
                            </div>

                            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2 min-w-0" style="border-top: 1px solid var(--outline-variant); padding-top: 1.25rem;">
                                <a href="{{ route('admin.subjects.index', ['grade' => 'Junior']) }}" class="btn-secondary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 sm:min-w-[120px]" style="border-radius: 12px;">
                                    <i class="fas fa-times text-sm" aria-hidden="true"></i>
                                    Cancel
                                </a>

                                <button type="submit" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" data-preloader style="border-radius: 12px;">
                                    <i class="fas fa-search text-sm" aria-hidden="true"></i>
                                    View students
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
                const classSelect = document.getElementById('filter-class');
                const subjectSelect = document.getElementById('filter-subjects');
                if (!classSelect || !subjectSelect) return;

                function getGradeForClass(className) {
                    if (!className) return null;
                    const prefix = className.substring(0, 3).toUpperCase();
                    if (prefix === 'JSS') return 'Junior';
                    if (prefix === 'SSS') return 'Senior';
                    return null;
                }

                function filterSubjectOptions() {
                    const grade = getGradeForClass(classSelect.value);
                    const options = subjectSelect.querySelectorAll('option[data-grade]');
                    const selectedValue = subjectSelect.value;
                    let selectedStillVisible = false;
                    options.forEach(function(opt) {
                        const optGrade = opt.getAttribute('data-grade');
                        const show = !grade || optGrade === grade;
                        opt.style.display = show ? '' : 'none';
                        opt.disabled = !show;
                        if (opt.value === selectedValue && show) selectedStillVisible = true;
                    });
                    if (selectedValue && !selectedStillVisible) subjectSelect.value = '';
                }

                classSelect.addEventListener('change', filterSubjectOptions);
                filterSubjectOptions();
            })();
        </script>
    @endpush
@endsection
