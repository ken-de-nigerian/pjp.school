@extends('layouts.app')

@section('content')
    @php
        $studentList = $students->first()?->students ?? collect();
        $studentList = $studentList->where('status', 2)->values();
    @endphp
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <div class="mb-4 sm:mb-6 w-fit">
                <a href="{{ route('admin.attendance.index') }}" class="inline-flex items-center gap-2 text-sm font-medium transition-opacity hover:opacity-80" style="color: var(--on-surface-variant);">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    Back to Attendance
                </a>
            </div>

            <header class="mb-6 lg:mb-8">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-normal tracking-tight mb-1.5" style="color: var(--on-surface); letter-spacing: -0.02em;">Take Attendance</h1>
                <p class="text-sm sm:text-base font-normal" style="color: var(--on-surface-variant);">Mark present or absent for each student. Changes are saved when you click Save.</p>
            </header>

            <div class="flex flex-wrap gap-3 sm:gap-4 mb-6">
                <div class="rounded-xl px-4 py-2.5" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    <span class="text-xs font-medium" style="color: var(--on-surface-variant);">Class</span>
                    <p class="text-sm font-medium mt-0.5" style="color: var(--on-surface);">{{ $class }}</p>
                </div>

                <div class="rounded-xl px-4 py-2.5" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    <span class="text-xs font-medium" style="color: var(--on-surface-variant);">Term</span>
                    <p class="text-sm font-medium mt-0.5" style="color: var(--on-surface);">{{ $term }}</p>
                </div>

                <div class="rounded-xl px-4 py-2.5" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    <span class="text-xs font-medium" style="color: var(--on-surface-variant);">Session</span>
                    <p class="text-sm font-medium mt-0.5" style="color: var(--on-surface);">{{ $session }}</p>
                </div>

            </div>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                @if($studentList->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 px-6">
                        <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5" style="border-radius: 16px;">
                            <i class="fas fa-user-graduate text-3xl" aria-hidden="true"></i>
                        </div>
                        <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No students in this class</h2>
                        <p class="text-sm text-center max-w-sm mb-6" style="color: var(--on-surface-variant);">There are no active students in {{ $class }}. Add or assign students to take attendance.</p>
                        <div class="flex justify-center">
                            <a href="{{ route('admin.attendance.index') }}" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-sm font-medium">
                                <i class="fas fa-arrow-left text-sm"></i>
                                Back to Attendance
                            </a>
                        </div>
                    </div>
                @else
                    <form id="attendance-form" class="flex flex-col min-h-0">
                        @csrf
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 px-5 sm:px-6 py-4" style="border-bottom: 1px solid var(--outline-variant);">
                            <p class="text-sm font-medium" style="color: var(--on-surface-variant);">
                                <span id="attendance-count">{{ $studentList->count() }}</span> student(s) · <span id="present-count">0</span> present, <span id="absent-count">0</span> absent
                            </p>
                            <div class="flex gap-2">
                                <button type="button" id="mark-all-present" class="attendance-bulk-btn attendance-bulk-btn--present" aria-pressed="false">All Present</button>
                                <button type="button" id="mark-all-absent" class="attendance-bulk-btn attendance-bulk-btn--absent attendance-bulk-btn--active" aria-pressed="true">All Absent</button>
                            </div>
                        </div>

                        <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0 border-x border-b" style="border-color: var(--outline-variant);">
                            <ul class="divide-y divide-[var(--outline-variant)]" role="list">
                                @foreach($studentList as $index => $student)
                                    @php
                                        $fullName = trim(($student->firstname ?? '') . ' ' . ($student->lastname ?? '') . ' ' . ($student->othername ?? ''));
                                        $avatarSrc = $student->imagelocation
                                            ? (str_starts_with($student->imagelocation, 'students/') ? asset('storage/' . $student->imagelocation) : asset('storage/students/' . $student->imagelocation))
                                            : asset('storage/students/default.png');
                                        $avatarInitial = $fullName ? mb_substr($fullName, 0, 1) : 'S';
                                    @endphp
                                    <li class="flex items-center gap-4 px-5 sm:px-6 py-4 transition-colors attendance-row" style="background: var(--surface-container-lowest);">
                                        <span class="text-sm font-medium w-8 flex-shrink-0" style="color: var(--on-surface-variant);">{{ $index + 1 }}</span>
                                        <img src="{{ $avatarSrc }}" alt="" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($avatarInitial) }}&size=80'">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium truncate" style="color: var(--on-surface);">{{ $fullName ?: '—' }}</p>
                                            <p class="text-xs truncate" style="color: var(--on-surface-variant);">{{ $student->reg_number ?? '' }}</p>
                                        </div>
                                        <div class="flex items-center gap-2 flex-shrink-0" role="group" aria-label="Attendance for {{ e($fullName) }}">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="radio" name="attendance_row_{{ $index }}" value="Present" class="attendance-radio attendance-radio-present sr-only peer" data-reg="{{ e($student->reg_number) }}" data-name="{{ e($fullName) }}" data-row="{{ $index }}">
                                                <span class="px-4 py-2 rounded-xl text-sm font-medium transition-all peer-checked:opacity-100 peer-checked:ring-2 peer-checked:ring-[var(--primary)] opacity-60" style="background: var(--primary-container); color: var(--on-primary-container);">Present</span>
                                            </label>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="radio" name="attendance_row_{{ $index }}" value="Absent" class="attendance-radio attendance-radio-absent sr-only peer" data-reg="{{ e($student->reg_number) }}" data-name="{{ e($fullName) }}" data-row="{{ $index }}" checked>
                                                <span class="px-4 py-2 rounded-xl text-sm font-medium transition-all peer-checked:opacity-100 peer-checked:ring-2 peer-checked:ring-[var(--outline)] opacity-60" style="background: var(--surface-container-high); color: var(--on-surface-variant);">Absent</span>
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="flex justify-center sm:justify-end px-5 sm:px-6 py-5" style="border-top: 1px solid var(--outline-variant); background: var(--surface-container-low);">
                            <button type="submit" id="save-attendance-btn" class="btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[160px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;">
                                <i class="fas fa-save text-sm" aria-hidden="true"></i>
                                Save attendance
                            </button>
                        </div>
                    </form>
                @endif
            </div>

        </div>
    </main>

    @push('scripts')
        @if(!$studentList->isEmpty())
            <script>
                (function() {
                    const form = document.getElementById('attendance-form');
                    const saveBtn = document.getElementById('save-attendance-btn');
                    const presentCountEl = document.getElementById('present-count');
                    const absentCountEl = document.getElementById('absent-count');
                    const classVal = @json($class);
                    const termVal = @json($term);
                    const sessionVal = @json($session);

                    const presentRadios = form.querySelectorAll('input.attendance-radio-present');
                    const absentRadios = form.querySelectorAll('input.attendance-radio-absent');

                    const markAllPresentBtn = document.getElementById('mark-all-present');
                    const markAllAbsentBtn = document.getElementById('mark-all-absent');
                    const total = presentRadios.length;

                    function updateBulkButtons() {
                        let present = 0;
                        presentRadios.forEach(r => { if (r.checked) present++; });
                        const absent = total - present;
                        presentCountEl.textContent = present;
                        absentCountEl.textContent = absent;

                        markAllPresentBtn.classList.toggle('attendance-bulk-btn--active', present === total);
                        markAllPresentBtn.setAttribute('aria-pressed', present === total ? 'true' : 'false');
                        markAllAbsentBtn.classList.toggle('attendance-bulk-btn--active', absent === total);
                        markAllAbsentBtn.setAttribute('aria-pressed', absent === total ? 'true' : 'false');
                    }

                    function updateCounts() {
                        updateBulkButtons();
                    }

                    form.querySelectorAll('.attendance-radio').forEach(radio => {
                        radio.addEventListener('change', updateCounts);
                    });
                    updateCounts();

                    markAllPresentBtn.addEventListener('click', function() {
                        presentRadios.forEach(r => { r.checked = true; });
                        updateCounts();
                    });
                    markAllAbsentBtn.addEventListener('click', function() {
                        absentRadios.forEach(r => { r.checked = true; });
                        updateCounts();
                    });

                    form.addEventListener('submit', async function(e) {
                        e.preventDefault();
                        setButtonLoading(saveBtn, true);

                        const rows = [];
                        for (let i = 0; i < presentRadios.length; i++) {
                            const isPresent = presentRadios[i].checked;
                            const reg = presentRadios[i].dataset.reg;
                            const name = presentRadios[i].dataset.name;
                            rows.push({
                                class: classVal,
                                term: termVal,
                                session: sessionVal,
                                name: name,
                                reg_number: reg,
                                class_roll_call: isPresent ? 'Present' : 'Absent'
                            });
                        }

                        try {
                            const res = await fetch('{{ route("admin.attendance.save") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({ attendance: rows })
                            });
                            const data = await res.json().catch(() => ({}));
                            if (res.ok && data.status === 'success') {
                                flashSuccess(data.message || 'Attendance saved.');
                                setTimeout(function() { window.location.reload(); }, 2800);
                            } else {
                                flashError(data.message || 'Failed to save attendance.');
                            }
                        } catch (err) {
                            flashError('Network error. Please try again.');
                        }
                        setButtonLoading(saveBtn, false);
                    });
                })();
            </script>
        @endif
    @endpush
@endsection
