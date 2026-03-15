@extends('layouts.app')

@section('content')
    @php
        $showSheet = $showSheet ?? false;
        $uploadUrl = route('teacher.results.upload-term');
        $sessOpts = $sessions->map(fn ($sess) => $sess->year . '/' . ($sess->year + 1))->unique()->values();
        if ($sessOpts->isEmpty()) {
            foreach (range((int) date('Y') - 5, (int) date('Y') + 1) as $y) {
                $sessOpts->push($y . '/' . ($y + 1));
            }
        }
    @endphp
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <div class="mb-4 sm:mb-6 w-fit">
                @if($showSheet)
                    <a href="{{ route('teacher.results.index') }}" class="inline-flex items-center gap-2 text-sm font-medium transition-opacity hover:opacity-80" style="color: var(--on-surface-variant);">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Change class / subject
                    </a>
                @else
                    <a href="{{ route('teacher.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-medium transition-opacity hover:opacity-80" style="color: var(--on-surface-variant);">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Dashboard
                    </a>
                @endif
            </div>

            <header class="mb-6 lg:mb-8 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                <div class="min-w-0 flex-1">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-normal tracking-tight mb-1.5" style="color: var(--on-surface); letter-spacing: -0.02em;">
                        @if($showSheet){{ e($class) }} · {{ e($subjects) }}@else Upload results @endif
                    </h1>
                    <p class="text-sm sm:text-base font-normal" style="color: var(--on-surface-variant);">
                        @if($showSheet)
                            {{ e($term) }} · {{ e($session) }} · CA (15) · Assign (25) · Exam (60)
                        @else
                            Select class, subject, term and session to open the result sheet.
                        @endif
                    </p>
                </div>
            </header>

            @if(!$showSheet)
                <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl p-5 sm:p-6 lg:p-8" style="background: var(--surface-container-low); box-shadow: var(--elevation-1);">
                    <div class="rounded-3xl p-4 sm:p-6 lg:p-8 overflow-hidden min-w-0 w-full max-w-3xl mx-auto" style="background: var(--surface-container-lowest); border: 1px solid var(--outline-variant); box-shadow: var(--elevation-1);">
                        <div class="flex items-center gap-4 mb-6 sm:mb-8">
                            <div class="dashboard-quick-icon dashboard-quick-icon--blue w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0" style="border-radius: 16px;">
                                <i class="fas fa-filter text-lg" aria-hidden="true"></i>
                            </div>
                            <div>
                                <h2 class="text-base sm:text-lg font-medium" style="color: var(--on-surface);">Result sheet filters</h2>
                                <p class="text-sm font-normal" style="color: var(--on-surface-variant);">Load students registered for the subject in this class</p>
                            </div>
                        </div>
                        <form method="GET" action="{{ route('teacher.results.index') }}" class="space-y-5 sm:space-y-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                                <div class="form-group min-w-0">
                                    <label for="teacher-upload-class" class="form-label">Class <span style="color: var(--primary);">*</span></label>
                                    <select id="teacher-upload-class" name="class" class="form-select w-full min-w-0" required>
                                        <option value="">Select class</option>
                                        @foreach($getClasses as $cn)
                                            @php $cname = is_object($cn) ? ($cn->class_name ?? '') : $cn; @endphp
                                            <option value="{{ $cname }}" {{ ($class ?? '') === $cname ? 'selected' : '' }}>{{ $cname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group min-w-0">
                                    <label for="teacher-subject" class="form-label">Subject <span style="color: var(--primary);">*</span></label>
                                    <select id="teacher-subject" name="subjects" class="form-select w-full min-w-0" required>
                                        <option value="">Select subject</option>
                                        @foreach($getSubjects as $s)
                                            <option value="{{ e($s->subject_name) }}" data-grade="{{ e($s->grade) }}" {{ ($subjects ?? '') === $s->subject_name ? 'selected' : '' }}>{{ e($s->subject_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group min-w-0">
                                    <label for="teacher-term" class="form-label">Term</label>
                                    <select id="teacher-term" name="term" class="form-select w-full min-w-0">
                                        <option value="First Term" {{ ($term ?? '') === 'First Term' ? 'selected' : '' }}>First Term</option>
                                        <option value="Second Term" {{ ($term ?? '') === 'Second Term' ? 'selected' : '' }}>Second Term</option>
                                        <option value="Third Term" {{ ($term ?? '') === 'Third Term' ? 'selected' : '' }}>Third Term</option>
                                    </select>
                                </div>
                                <div class="form-group min-w-0">
                                    <label for="teacher-session" class="form-label">Session <span style="color: var(--primary);">*</span></label>
                                    <select id="teacher-session" name="session" class="form-select w-full min-w-0" required>
                                        <option value="">Select session</option>
                                        @foreach($sessOpts as $opt)
                                            <option value="{{ e($opt) }}" {{ ($session ?? '') === $opt ? 'selected' : '' }}>{{ e($opt) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-4" style="border-top: 1px solid var(--outline-variant);">
                                <button type="submit" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-medium w-full sm:w-auto" style="border-radius: 12px;">
                                    <i class="fas fa-users text-sm" aria-hidden="true"></i>
                                    Load result sheet
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            @if($showSheet)
                <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                    <div class="flex flex-col gap-3 px-4 sm:px-6 py-3 border-b" style="border-color: var(--outline-variant); background: var(--surface-container-low);">
                        @if($alreadyUploaded)
                            <div class="rounded-xl p-4 w-full flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3" style="background: var(--secondary-container); border: 1px solid var(--outline-variant);">
                                <p class="text-sm" style="color: var(--on-secondary-container);">Results for this class, term, session and subject are already uploaded.</p>
                                <a href="{{ route('teacher.results.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium shrink-0" style="background: var(--primary); color: var(--on-primary); border-radius: 12px;">Upload another</a>
                            </div>
                        @endif
                        <div class="flex flex-wrap gap-2 w-full">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium" style="background: var(--primary-container); color: var(--on-primary-container);">{{ e($class) }}</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium" style="background: var(--surface-container-high); color: var(--on-surface-variant);">{{ e($subjects) }}</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium" style="background: var(--surface-container-high); color: var(--on-surface-variant);">{{ e($term) }}</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium" style="background: var(--surface-container-high); color: var(--on-surface-variant);">{{ e($session) }}</span>
                        </div>
                    </div>

                    @if($students->isEmpty())
                        <div class="flex flex-col items-center justify-center py-16 px-6">
                            <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center" style="border-radius: 16px;">
                                <i class="fas fa-user-graduate text-3xl" aria-hidden="true"></i>
                            </div>
                            <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No students in this class</h2>
                            <p class="text-sm text-center max-w-md mb-6" style="color: var(--on-surface-variant);"><a href="{{ route('teacher.results.index') }}" class="font-medium underline-offset-2 hover:underline" style="color: var(--primary);">Change filters</a></p>
                        </div>
                    @else
                        <div class="overflow-hidden min-w-0" style="background: var(--surface-container-lowest);">
                            <div class="hidden lg:grid lg:grid-cols-results-sheet sticky top-0 z-10 px-4 sm:px-6 py-3 gap-x-3 text-xs font-semibold uppercase tracking-wider min-w-0" style="background: var(--surface-container); border-bottom: 1px solid var(--outline-variant); color: var(--on-surface-variant);">
                                <span class="w-12"></span>
                                <span class="min-w-0">Name</span>
                                <span>Reg #</span>
                                <span class="text-center">CA (15)</span>
                                <span class="text-center">ASSIGN (25)</span>
                                <span class="text-center">EXAM (60)</span>
                            </div>
                            <div id="results-upload-form" class="min-w-0">
                                <ul class="divide-y divide-[var(--outline-variant)] list-none p-0 m-0" role="list">
                                    @foreach($students as $s)
                                        @php
                                            $displayName = trim(($s->firstname ?? '') . ' ' . ($s->lastname ?? ''));
                                            $avatarSrc = ($s->imagelocation ?? null)
                                                ? (str_starts_with($s->imagelocation, 'students/') ? asset('storage/' . $s->imagelocation) : asset('storage/students/' . $s->imagelocation))
                                                : asset('storage/students/default.png');
                                            $avatarInitial = $displayName ? mb_substr($displayName, 0, 1) : 'S';
                                        @endphp
                                        <li class="results-sheet-row px-4 sm:px-6 py-4 lg:py-3 min-w-0" style="background: var(--surface-container-lowest);">
                                            <div class="flex flex-col lg:grid lg:grid-cols-results-sheet lg:gap-x-3 lg:items-center gap-3 lg:gap-y-0">
                                                <div class="flex items-center gap-3 lg:block lg:w-12 shrink-0">
                                                    <img src="{{ $avatarSrc }}" alt="" class="w-10 h-10 rounded-full object-cover border border-[var(--outline-variant)] lg:mx-auto" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($avatarInitial) }}&size=80'">
                                                    <div class="lg:hidden min-w-0 flex-1">
                                                        <p class="text-sm font-semibold truncate" style="color: var(--on-surface);">{{ e($displayName) }}</p>
                                                        <p class="text-xs truncate" style="color: var(--on-surface-variant);">{{ e($s->reg_number ?? '') }}</p>
                                                    </div>
                                                </div>
                                                <p class="hidden lg:block text-sm font-medium truncate min-w-0" style="color: var(--on-surface);">{{ e($displayName) }}</p>
                                                <span class="hidden lg:inline text-xs tabular-nums px-2 py-1 rounded-lg truncate max-w-[7rem]" style="background: var(--surface-container-high); color: var(--on-surface-variant);">{{ e($s->reg_number ?? '') }}</span>
                                                <div class="grid grid-cols-3 lg:contents gap-2">
                                                    <div class="min-w-0">
                                                        <label class="lg:sr-only text-xs font-medium block mb-1" style="color: var(--on-surface-variant);">CA (15)</label>
                                                        <input type="number" name="ca" min="0" max="15" step="0.5" value="" required class="form-input results-score-input w-full text-center tabular-nums rounded-xl text-sm py-2 px-2 min-w-0" style="border-color: var(--outline-variant); max-width: 5rem;" placeholder="0–15" {{ $alreadyUploaded ? 'disabled' : '' }}>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <label class="lg:sr-only text-xs font-medium block mb-1" style="color: var(--on-surface-variant);">ASSIGN (25)</label>
                                                        <input type="number" name="assignment" min="0" max="25" step="0.5" value="" required class="form-input results-score-input w-full text-center tabular-nums rounded-xl text-sm py-2 px-2 min-w-0" style="border-color: var(--outline-variant); max-width: 5rem;" placeholder="0–25" {{ $alreadyUploaded ? 'disabled' : '' }}>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <label class="lg:sr-only text-xs font-medium block mb-1" style="color: var(--on-surface-variant);">EXAM (60)</label>
                                                        <input type="number" name="exam" min="0" max="60" step="0.5" value="" required class="form-input results-score-input w-full text-center tabular-nums rounded-xl text-sm py-2 px-2 min-w-0" style="border-color: var(--outline-variant); max-width: 5rem;" placeholder="0–60" {{ $alreadyUploaded ? 'disabled' : '' }}>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" class="r-studentId" value="{{ $s->id }}">
                                            <input type="hidden" class="r-name" value="{{ e($displayName) }}">
                                            <input type="hidden" class="r-reg" value="{{ e($s->reg_number ?? '') }}">
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            @if(!$alreadyUploaded)
                                <div class="px-4 sm:px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3" style="background: var(--surface-container); border-top: 1px solid var(--outline-variant);">
                                    <button type="button" id="results-upload-submit" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-medium w-full sm:w-auto" style="border-radius: 12px;">
                                        <i class="fas fa-cloud-upload-alt" aria-hidden="true"></i>
                                        Save results
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </main>

    <style>
        .lg\:grid-cols-results-sheet { grid-template-columns: 3rem minmax(0, 1.25fr) minmax(0, 0.75fr) 5rem 5rem 5rem; align-items: center; }
        @media (max-width: 1023px) { .lg\:grid-cols-results-sheet { grid-template-columns: 1fr; } }
    </style>

    @push('scripts')
    <script>
        (function () {
            var classSelect = document.getElementById('teacher-upload-class');
            var subjectSelect = document.getElementById('teacher-subject');
            if (classSelect && subjectSelect) {
                function gradeForClass(name) {
                    if (!name) return null;
                    var p = name.substring(0, 3).toUpperCase();
                    if (p === 'JSS') return 'Junior';
                    if (p === 'SSS') return 'Senior';
                    return null;
                }
                function filterSubjects() {
                    var g = gradeForClass(classSelect.value);
                    subjectSelect.querySelectorAll('option[data-grade]').forEach(function (opt) {
                        var show = !g || opt.getAttribute('data-grade') === g;
                        opt.style.display = show ? '' : 'none';
                        opt.disabled = !show;
                    });
                    var sel = subjectSelect.value;
                    var ok = Array.from(subjectSelect.options).some(function (o) { return o.value === sel && !o.disabled; });
                    if (sel && !ok) subjectSelect.value = '';
                }
                classSelect.addEventListener('change', filterSubjects);
                filterSubjects();
            }
            @if($showSheet && !$students->isEmpty() && !$alreadyUploaded)
            var term = @json($term);
            var session = @json($session);
            var className = @json($class);
            var subjects = @json($subjects);
            var token = @json(csrf_token());
            var btn = document.getElementById('results-upload-submit');
            if (btn) {
                btn.addEventListener('click', function () {
                    var rows = document.querySelectorAll('.results-sheet-row');
                    var results = [];
                    var err = null;
                    rows.forEach(function (row) {
                        var ca = parseFloat(row.querySelector('input[name="ca"]').value);
                        var as = parseFloat(row.querySelector('input[name="assignment"]').value);
                        var ex = parseFloat(row.querySelector('input[name="exam"]').value);
                        if (isNaN(ca) || isNaN(as) || isNaN(ex)) { err = 'Enter all scores for every student.'; return; }
                        if (ca < 0 || ca > 15 || as < 0 || as > 25 || ex < 0 || ex > 60) { err = 'CA must be ≤15, Assign ≤25, Exam ≤60.'; return; }
                        results.push({
                            studentId: row.querySelector('.r-studentId').value,
                            class: className, term: term, session: session, subjects: subjects,
                            name: row.querySelector('.r-name').value,
                            reg_number: row.querySelector('.r-reg').value,
                            ca: ca, assignment: as, exam: ex
                        });
                    });
                    if (err) {
                        if (typeof markEmptyResultsScoreInputsOnSubmit === 'function') markEmptyResultsScoreInputsOnSubmit();
                        if (typeof flashError === 'function') flashError(err);
                        return;
                    }
                    document.querySelectorAll('.results-score-input-empty').forEach(function (el) { el.classList.remove('results-score-input-empty'); });
                    if (!results.length) { if (typeof flashError === 'function') flashError('No rows to upload.'); return; }
                    if (typeof setButtonLoading === 'function') setButtonLoading(btn, true);
                    fetch(@json($uploadUrl), {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                        body: JSON.stringify({ results: results })
                    }).then(function (r) {
                        return r.text().then(function (text) {
                            var d = {};
                            try { d = text ? JSON.parse(text) : {}; } catch (e) { d = {}; }
                            return { ok: r.ok, d: d };
                        });
                    }).then(function (res) {
                        if (typeof setButtonLoading === 'function') setButtonLoading(btn, false);
                        if (!res.ok) {
                            var msg = res.d.message || 'Upload failed.';
                            if (res.d.errors && typeof res.d.errors === 'object') {
                                var keys = Object.keys(res.d.errors);
                                if (keys.length) {
                                    var m = res.d.errors[keys[0]];
                                    msg = Array.isArray(m) ? m[0] : m;
                                }
                            }
                            if (typeof flashError === 'function') flashError(msg);
                            return;
                        }
                        var d = res.d;
                        if (d.status === 'success') {
                            if (typeof flashSuccess === 'function') flashSuccess(d.message || 'Results saved.');
                            setTimeout(function () { window.location.href = @json(route('teacher.results.index')); }, 2800);
                        } else {
                            if (typeof flashError === 'function') flashError(d.message || 'Upload failed.');
                        }
                    }).catch(function () {
                        if (typeof setButtonLoading === 'function') setButtonLoading(btn, false);
                        if (typeof flashError === 'function') flashError('Request failed. Check your connection and try again.');
                    });
                });
            }
            @endif
        })();
    </script>
    @endpush
@endsection
