@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <div class="mb-4 sm:mb-6 w-fit">
                <a href="{{ route('admin.subjects.registered') }}" class="inline-flex items-center gap-2 text-sm font-medium transition-opacity hover:opacity-80" style="color: var(--on-surface-variant);">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    Back to Registered
                </a>
            </div>

            <header class="mb-6 lg:mb-8 flex flex-col gap-4 sm:gap-5">
                <div class="flex items-start gap-3 sm:gap-4 min-w-0">
                    <div class="min-w-0 flex-1">
                        <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-normal tracking-tight mb-1 sm:mb-1.5" style="color: var(--on-surface); letter-spacing: -0.02em;">View registered students' subjects</h1>
                        <p class="text-xs sm:text-sm md:text-base font-normal max-w-xl" style="color: var(--on-surface-variant);">Students registered to the selected class and subject.</p>
                    </div>
                </div>
            </header>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                @if($students->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 md:py-40 px-6">
                        <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5" style="border-radius: 16px;">
                            <i class="fas fa-user-graduate text-3xl" aria-hidden="true"></i>
                        </div>
                        <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No students found</h2>
                        <p class="text-sm text-center max-w-sm mb-6" style="color: var(--on-surface-variant);">No students are registered for the selected class and subject. Change the filters or register students to subjects.</p>
                        <div class="flex justify-center">
                            <a href="{{ route('admin.subjects.fetch-classes') }}" class="btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] rounded-xl font-medium text-sm transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;">
                                <i class="fas fa-arrow-left text-sm" aria-hidden="true"></i>
                                Change class
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0 border-x border-b" style="border-color: var(--outline-variant);">
                        <ul class="divide-y divide-[var(--outline-variant)]" role="list">
                            @foreach($students as $index => $s)
                                @php
                                    $fullName = trim(($s->firstname ?? '') . ' ' . ($s->lastname ?? '') . ' ' . ($s->othername ?? ''));
                                    $classDisplay = is_object($s) && isset($s->class) ? $s->class : (is_array($s) ? ($s['class'] ?? '') : '');
                                    $avatarSrc = ($s->imagelocation ?? null)
                                        ? (str_starts_with($s->imagelocation, 'students/') ? asset('storage/' . $s->imagelocation) : asset('storage/students/' . $s->imagelocation))
                                        : asset('storage/students/default.png');
                                    $avatarInitial = $fullName ? mb_substr($fullName, 0, 1) : 'S';
                                @endphp
                                <li class="flex items-center gap-4 px-5 sm:px-6 py-4 transition-colors" style="background: var(--surface-container-lowest);">
                                    <span class="text-sm font-medium w-8 flex-shrink-0" style="color: var(--on-surface-variant);">{{ $index + 1 }}</span>
                                    <img src="{{ $avatarSrc }}" alt="" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($avatarInitial) }}&size=80'">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium truncate" style="color: var(--on-surface);">{{ $fullName ?: '—' }}</p>
                                        <p class="text-xs truncate" style="color: var(--on-surface-variant);">{{ $s->reg_number ?? '' }}</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium" style="background: var(--surface-container-high); color: var(--on-surface-variant);">{{ e($classDisplay) }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
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
