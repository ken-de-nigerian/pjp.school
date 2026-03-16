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

            <header class="mb-6 lg:mb-8 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                <div class="min-w-0 flex-1">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-normal tracking-tight mb-1.5" style="color: var(--on-surface); letter-spacing: -0.02em;">View registered students' subjects</h1>
                    <p class="text-sm sm:text-base font-normal" style="color: var(--on-surface-variant);">
                        @if($hasFilters)
                            Students registered to the selected class and subject.
                        @else
                            Choose class and subject to see which students are registered.
                        @endif
                    </p>
                </div>
                @if($hasFilters)
                    <a href="{{ route('admin.subjects.registered') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-opacity hover:opacity-90 shrink-0" style="color: var(--on-surface-variant); background: var(--surface-container-high); border-radius: 12px;">
                        <i class="fas fa-filter text-xs" aria-hidden="true"></i>
                        <span>Change filters</span>
                    </a>
                @endif
            </header>

            @if(!$hasFilters)
            <div class="rounded-3xl p-4 sm:p-5 lg:p-6 mb-6 overflow-hidden min-w-0 w-full" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                <form method="GET" action="{{ route('admin.subjects.registered') }}" id="registered-filter-form" class="space-y-4 sm:space-y-5">
                    <div class="grid grid-cols-12 gap-4 min-w-0">
                        <div class="col-span-12 sm:col-span-6 form-group min-w-0">
                            <label for="filter-class" class="form-label">Select class</label>
                            <select id="filter-class" name="class" class="form-select w-full min-w-0">
                                <option value="">Choose class</option>
                                @foreach($getClasses as $c)
                                    @php $className = is_object($c) ? $c->class_name : $c; @endphp
                                    <option value="{{ e($className) }}" {{ ($filterClass ?? '') === $className ? 'selected' : '' }}>{{ e($className) }}</option>
                                @endforeach
                            </select>
                            <p id="class-error" class="form-error mt-1 text-sm {{ $errors->has('class') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('class') }}</p>
                        </div>
                        <div class="col-span-12 sm:col-span-6 form-group min-w-0">
                            <label for="filter-subjects" class="form-label">Select subject</label>
                            <select id="filter-subjects" name="subjects" class="form-select w-full min-w-0">
                                <option value="">Choose subject</option>
                                @foreach($getSubjects as $s)
                                    <option value="{{ e($s->subject_name) }}" data-grade="{{ e($s->grade) }}" {{ ($filterSubject ?? '') === $s->subject_name ? 'selected' : '' }}>{{ e($s->subject_name) }}</option>
                                @endforeach
                            </select>
                            <p id="subjects-error" class="form-error mt-1 text-sm {{ $errors->has('subjects') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('subjects') }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2 min-w-0" style="border-top: 1px solid var(--outline-variant); padding-top: 1.25rem;">
                        <a href="{{ route('admin.subjects.registered') }}" class="btn-secondary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 sm:min-w-[120px]" style="border-radius: 12px;">
                            <i class="fas fa-times text-sm" aria-hidden="true"></i>
                            Clear
                        </a>
                        <button type="submit" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" data-preloader style="border-radius: 12px;">
                            <i class="fas fa-search text-sm" aria-hidden="true"></i>
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
                    <p class="text-sm text-center max-w-sm" style="color: var(--on-surface-variant);">Choose class and subject in the form above, then click &quot;View students&quot; to see registered students.</p>
                </div>
            @else
            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                @if($students->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 md:py-40 px-6">
                        <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5" style="border-radius: 16px;">
                            <i class="fas fa-user-graduate text-3xl" aria-hidden="true"></i>
                        </div>
                        <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No students found</h2>
                        <p class="text-sm text-center max-w-sm mb-6" style="color: var(--on-surface-variant);">No students are registered for the selected class and subject. Change the filters above or register students to subjects.</p>
                        <div class="flex justify-center">
                            <a href="{{ route('admin.subjects.registered') }}" class="btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] rounded-xl font-medium text-sm transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;">
                                <i class="fas fa-filter text-sm" aria-hidden="true"></i>
                                Change filters
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
                                <span class="text-xs font-medium flex-shrink-0 w-24" style="color: var(--on-surface-variant);">Class</span>
                            </li>
                            @foreach($students as $index => $s)
                                @php
                                    $fullName = trim(($s->firstname ?? '') . ' ' . ($s->lastname ?? '') . ' ' . ($s->othername ?? ''));
                                    $classDisplay = is_object($s) && isset($s->class) ? $s->class : (is_array($s) ? ($s['class'] ?? '') : '');
                                    $avatarSrc = ($s->imagelocation ?? null)
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
                                            <p class="text-sm font-medium truncate" style="color: var(--on-surface);">{{ $fullName ?: '—' }}</p>
                                            <p class="text-xs truncate mt-0.5" style="color: var(--on-surface-variant);">{{ $s->reg_number ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-3 border-t md:border-t-0 md:mt-0 md:pt-0 flex flex-wrap items-baseline gap-x-4 gap-y-1 md:contents" style="border-color: var(--outline-variant);">
                                        <span class="w-full text-xs font-medium mb-1 md:sr-only" style="color: var(--on-surface-variant);">Class</span>
                                        <span class="text-xs md:flex-shrink-0 md:w-24"><span class="sr-only">Class </span><span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium" style="background: var(--surface-container-high); color: var(--on-surface-variant);">{{ e($classDisplay) }}</span></span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            @endif
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
