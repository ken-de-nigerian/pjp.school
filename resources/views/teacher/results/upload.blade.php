@extends('layouts.app')

@section('content')
    @php
        $uploadUrl = route('teacher.results.upload-term');
        $viewUploadedUrl = route('teacher.uploaded.index', [
            'class' => $class ?? '',
            'term' => $term ?? '',
            'session' => $session ?? '',
            'subjects' => $subjects ?? '',
        ]);
    @endphp
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            @php
                $uploadHeroDescription = !empty($hasFilters)
                    ? e($class) . ' Â· ' . e($subjects) . ' Â· ' . e($term) . ' Â· ' . e($session) . ' â€” CA (15) Â· Assign (25) Â· Exam (60)'
                    : 'Choose class and subject below, then click "Load result sheet" to enter scores.';
            @endphp
            <x-admin.hero-page
                aria-label="Upload results"
                pill="Teacher"
                title="Upload results"
                :description="$uploadHeroDescription"
            >
                <x-slot name="actions">
                    <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
                        @if(!empty($hasFilters))
                            <a href="{{ route('teacher.results.index') }}" class="admin-dashboard-hero__btn w-full lg:w-auto justify-center min-h-[44px] sm:min-h-0">
                                <i class="fas fa-filter text-xs" aria-hidden="true"></i>
                                <span>Change filters</span>
                            </a>
                        @endif
                        @if(Route::has('teacher.uploaded.index'))
                            <a href="{{ route('teacher.uploaded.index') }}" class="admin-dashboard-hero__btn admin-dashboard-hero__btn--primary w-full lg:w-auto justify-center min-h-[44px] sm:min-h-0">
                                <i class="fas fa-eye text-xs" aria-hidden="true"></i>
                                <span>View uploaded</span>
                            </a>
                        @endif
                    </div>
                </x-slot>
            </x-admin.hero-page>

            @if(empty($hasFilters))
                <div class="rounded-3xl p-4 sm:p-5 lg:p-6 mb-6 overflow-hidden min-w-0 w-full" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    <form method="GET" action="{{ route('teacher.results.index') }}" class="space-y-4 sm:space-y-5">
                        <input type="hidden" name="term" value="{{ e($term ?? '') }}">
                        <input type="hidden" name="session" value="{{ e($session ?? '') }}">
                        <div class="grid grid-cols-12 gap-4 min-w-0">
                            <div class="col-span-12 sm:col-span-6 form-group min-w-0">
                                <label for="upload-class" class="form-label">Class <span style="color: var(--primary);">*</span></label>
                                <select id="upload-class" name="class" class="form-select w-full min-w-0">
                                    <option value="">Select class</option>
                                    @foreach($getClasses as $c)
                                        @php $cn = is_object($c) ? $c->class_name : $c; @endphp
                                        <option value="{{ e($cn) }}" {{ ($class ?? '') === $cn ? 'selected' : '' }}>{{ e($cn) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-12 sm:col-span-6 form-group min-w-0">
                                <label for="upload-subjects" class="form-label">Subject <span style="color: var(--primary);">*</span></label>
                                <select id="upload-subjects" name="subjects" class="form-select w-full min-w-0">
                                    <option value="">Select subject</option>
                                    @foreach($getSubjects as $s)
                                        <option value="{{ e(is_string($s) ? $s : ($s->subject_name ?? '')) }}" {{ ($subjects ?? '') === (is_string($s) ? $s : ($s->subject_name ?? '')) ? 'selected' : '' }}>
                                            {{ e(is_string($s) ? $s : ($s->subject_name ?? '')) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2 min-w-0" style="border-top: 1px solid var(--outline-variant); padding-top: 1.25rem;">
                            <a href="{{ route('teacher.results.index') }}" class="btn-secondary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 sm:min-w-[120px]" style="border-radius: 12px;">
                                <i class="fas fa-times text-sm" aria-hidden="true"></i>
                                Clear
                            </a>
                            <button type="submit" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" data-preloader style="border-radius: 12px;">
                                <i class="fas fa-users text-sm" aria-hidden="true"></i>
                                Load result sheet
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            @if(empty($hasFilters))
                <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden flex flex-col items-center justify-center py-16 md:py-24 px-6" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center" style="border-radius: 16px;">
                        <i class="fas fa-search text-3xl" aria-hidden="true"></i>
                    </div>
                    <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No filters selected</h2>
                    <p class="text-sm text-center max-w-sm" style="color: var(--on-surface-variant);">Choose class and subject in the form above, then click &quot;Load result sheet&quot; to see students and enter scores.</p>
                </div>
            @else
                @if(!empty($showSheet))
                    <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                        <div class="flex flex-col border-b" style="border-color: var(--outline-variant); background: var(--surface-container-low);">
                            <div class="px-4 sm:px-6 pt-4 pb-3">
                                <p class="text-[11px] font-semibold uppercase tracking-wider mb-3" style="color: var(--on-surface-variant); letter-spacing: 0.06em;">Result sheet context</p>
                                <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3">
                                    <div class="rounded-xl px-3 py-2.5 min-w-0 border" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                        <span class="text-[10px] font-medium uppercase tracking-wide block mb-1" style="color: var(--on-surface-variant);">Class</span>
                                        <span class="text-sm font-semibold leading-snug line-clamp-2 break-words" style="color: var(--on-surface);" title="{{ e($class) }}">{{ e($class) }}</span>
                                    </div>

                                    <div class="rounded-xl px-3 py-2.5 min-w-0 border" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                        <span class="text-[10px] font-medium uppercase tracking-wide block mb-1" style="color: var(--on-surface-variant);">Subject</span>
                                        <span class="text-sm font-semibold leading-snug line-clamp-2 break-words" style="color: var(--on-surface);" title="{{ e($subjects) }}">{{ e($subjects) }}</span>
                                    </div>

                                    <div class="rounded-xl px-3 py-2.5 min-w-0 border" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                        <span class="text-[10px] font-medium uppercase tracking-wide block mb-1" style="color: var(--on-surface-variant);">Term</span>
                                        <span class="text-sm font-semibold leading-snug line-clamp-2 break-words" style="color: var(--on-surface);" title="{{ e($term) }}">{{ e($term) }}</span>
                                    </div>

                                    <div class="rounded-xl px-3 py-2.5 min-w-0 border col-span-2 lg:col-span-1" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                        <span class="text-[10px] font-medium uppercase tracking-wide block mb-1" style="color: var(--on-surface-variant);">Session</span>
                                        <span class="text-sm font-semibold leading-snug line-clamp-2 break-words" style="color: var(--on-surface);" title="{{ e($session) }}">{{ e($session) }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($alreadyUploaded)
                                <div class="px-4 sm:px-6 pb-4 pt-4" style="border-top: 1px solid var(--outline-variant); background: var(--surface-container);">
                                    <div class="rounded-2xl px-4 py-3.5 sm:px-5 sm:py-4 flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4" style="border: 1px solid var(--outline-variant);">
                                        <div class="flex items-start gap-3 min-w-0 flex-1">
                                            <div class="min-w-0 pt-0.5">
                                                <p class="text-sm font-semibold">Already uploaded</p>
                                                <p class="text-xs sm:text-sm mt-0.5 leading-relaxed" style="opacity: 0.92;">Scores for this class, term, session and subject are on file. Open the list to review or edit.</p>
                                            </div>
                                        </div>

                                        <a href="{{ $viewUploadedUrl }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium shrink-0 w-full sm:w-auto sm:self-center" style="background: var(--primary); color: var(--on-primary); border-radius: 12px;">
                                            <i class="fas fa-external-link-alt text-xs opacity-90" aria-hidden="true"></i>
                                            View / edit uploaded
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($students->isEmpty())
                            <div class="flex flex-col items-center justify-center py-16 md:py-40 px-6">
                                <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5" style="border-radius: 16px;">
                                    <i class="fas fa-user-graduate text-3xl" aria-hidden="true"></i>
                                </div>
                                <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No students for this subject</h2>
                                <p class="text-sm text-center max-w-md mb-6" style="color: var(--on-surface-variant);">No students in this class are registered for <strong>{{ e($subjects) }}</strong>. Register the subject for students or <a href="{{ route('teacher.upload-results') }}" class="font-medium underline-offset-2 hover:underline" style="color: var(--primary);">choose another class/subject</a>.</p>
                            </div>
                        @else
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 sm:px-6 py-4" style="border-bottom: 1px solid var(--outline-variant); background: var(--surface-container-low);">
                                <p class="text-sm font-medium" style="color: var(--on-surface-variant);">
                                    <span>{{ $students->count() }}</span> student(s) Â· Enter CA (15), Assign (25), Exam (60)
                                </p>
                            </div>

                            <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0" style="border-color: var(--outline-variant); background: var(--surface-container-lowest);">
                                <div class="hidden lg:grid lg:grid-cols-results-upload sticky top-0 z-10 px-4 sm:px-6 py-3 gap-2 text-xs font-semibold uppercase tracking-wider" style="background: var(--surface-container); border-bottom: 1px solid var(--outline-variant); color: var(--on-surface-variant);">
                                    <span class="lg:pl-2 text-center">#</span>
                                    <span class="min-w-0">Student</span>
                                    <span class="min-w-0 text-center">CA (15)</span>
                                    <span class="min-w-0 text-center">Assign (25)</span>
                                    <span class="min-w-0 text-center">Exam (60)</span>
                                </div>

                                <div id="results-upload-form" class="min-w-0 min-w-[min(100%,520px)] lg:min-w-0">
                                    @csrf
                                    <ul class="divide-y divide-[var(--outline-variant)] list-none p-0 m-0" id="results-sheet-list" role="list">
                                        @foreach($students as $s)
                                            @php
                                                $fullName = trim(($s->firstname ?? '') . ' ' . ($s->lastname ?? '') . ' ' . ($s->othername ?? ''));
                                                $displayName = trim(($s->firstname ?? '') . ' ' . ($s->lastname ?? ''));
                                                $avatarSrc = ($s->imagelocation ?? null)
                                                    ? (str_starts_with($s->imagelocation, 'students/') ? asset('storage/' . $s->imagelocation) : asset('storage/students/' . $s->imagelocation))
                                                    : asset('storage/students/default.png');
                                                $avatarInitial = $fullName ? mb_substr($fullName, 0, 1) : 'S';
                                            @endphp
                                            <li class="results-sheet-row flex flex-col lg:grid lg:grid-cols-results-upload gap-4 lg:gap-2 lg:items-stretch px-4 sm:px-6 py-5 lg:py-3 transition-colors" style="background: var(--surface-container-lowest);" data-index="{{ $loop->index }}">
                                                <span class="flex-shrink-0 w-8 h-8 rounded-xl flex items-center justify-center text-sm font-semibold lg:place-self-center" style="background: var(--primary-container); color: var(--on-primary-container);">{{ $loop->iteration }}</span>

                                                <div class="flex items-center gap-3 min-w-0 lg:py-1 lg:pr-2">
                                                    <img src="{{ $avatarSrc }}" alt="" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($avatarInitial) }}&size=80'">
                                                    <div class="flex flex-col justify-center min-w-0 flex-1">
                                                        <p class="text-sm font-semibold break-words" style="color: var(--on-surface);">
                                                            @if(Route::has('teacher.students.show'))
                                                                <a href="{{ route('teacher.students.show', $s) }}" class="transition-opacity hover:opacity-80" style="color: var(--primary);">{{ $fullName ?: 'â€”' }}</a>
                                                            @else
                                                                {{ $fullName ?: 'â€”' }}
                                                            @endif
                                                        </p>
                                                        <p class="text-xs truncate mt-0.5 tabular-nums" style="color: var(--on-surface-variant);">{{ e($s->reg_number ?? '') }}</p>
                                                    </div>
                                                    <input type="hidden" class="r-studentId" value="{{ $s->id }}">
                                                    <input type="hidden" class="r-name" value="{{ e($displayName) }}">
                                                    <input type="hidden" class="r-reg" value="{{ e($s->reg_number ?? '') }}">
                                                </div>

                                                <div class="form-group min-w-0 flex flex-col lg:py-1">
                                                    <label class="form-label lg:sr-only flex items-center gap-2" for="score-ca-{{ $loop->index }}">
                                                        <i class="fas fa-clipboard-check text-xs opacity-70" style="color: var(--on-surface-variant);" aria-hidden="true"></i>
                                                        <span>CA (max 15)</span>
                                                    </label>
                                                    <input id="score-ca-{{ $loop->index }}" type="number" name="ca" min="0" max="15" step="0.5" value="" inputmode="decimal" required class="form-input results-score-input w-full min-w-[7rem] tabular-nums rounded-xl border text-sm py-2.5 px-3 text-center sm:min-w-[6.5rem]" style="border-color: var(--outline-variant); background: var(--surface-container-lowest);" placeholder="0 â€“ 15" {{ $alreadyUploaded ? 'disabled' : '' }}>
                                                </div>

                                                <div class="form-group min-w-0 flex flex-col lg:py-1">
                                                    <label class="form-label lg:sr-only flex items-center gap-2" for="score-as-{{ $loop->index }}">
                                                        <i class="fas fa-tasks text-xs opacity-70" style="color: var(--on-surface-variant);" aria-hidden="true"></i>
                                                        <span>Assign (max 25)</span>
                                                    </label>
                                                    <input id="score-as-{{ $loop->index }}" type="number" name="assignment" min="0" max="25" step="0.5" value="" inputmode="decimal" required class="form-input results-score-input w-full min-w-[7rem] tabular-nums rounded-xl border text-sm py-2.5 px-3 text-center sm:min-w-[6.5rem]" style="border-color: var(--outline-variant); background: var(--surface-container-lowest);" placeholder="0 â€“ 25" {{ $alreadyUploaded ? 'disabled' : '' }}>
                                                </div>

                                                <div class="form-group min-w-0 flex flex-col lg:py-1">
                                                    <label class="form-label lg:sr-only flex items-center gap-2" for="score-ex-{{ $loop->index }}">
                                                        <i class="fas fa-file-alt text-xs opacity-70" style="color: var(--on-surface-variant);" aria-hidden="true"></i>
                                                        <span>Exam (max 60)</span>
                                                    </label>
                                                    <input id="score-ex-{{ $loop->index }}" type="number" name="exam" min="0" max="60" step="0.5" value="" inputmode="decimal" required class="form-input results-score-input w-full min-w-[7rem] tabular-nums rounded-xl border text-sm py-2.5 px-3 text-center sm:min-w-[6.5rem]" style="border-color: var(--outline-variant); background: var(--surface-container-lowest);" placeholder="0 â€“ 60" {{ $alreadyUploaded ? 'disabled' : '' }}>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                @if(!$alreadyUploaded)
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-4 px-5 sm:px-6 py-5" style="border-top: 1px solid var(--outline-variant); background: var(--surface-container-low);">
                                        <button type="button" id="results-upload-submit" class="btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] sm:min-w-[200px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98] w-full sm:w-auto" style="border-radius: 12px;">
                                            <i class="fas fa-cloud-upload-alt" aria-hidden="true"></i>
                                            Save {{ e($term) }} term results
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif
            @endif
        </div>
    </main>

    <style>
        /* Align with take-behavioural: # + student + reg + three score columns */
        .lg\:grid-cols-results-upload {
            grid-template-columns: 3rem minmax(11rem, 1.45fr) minmax(7.25rem, 1fr) minmax(7.25rem, 1fr) minmax(7.25rem, 1fr);
            align-items: stretch;
        }
        @media (max-width: 1023px) {
            .lg\:grid-cols-results-upload { grid-template-columns: 1fr; }
        }
    </style>

    @push('scripts')
        <script>
            (function () {
                const classSelect = document.getElementById('upload-class');
                const subjectSelect = document.getElementById('upload-subjects');
                if (classSelect && subjectSelect) {
                    function gradeForClass(name) {
                        if (!name) return null;
                        const p = name.substring(0, 3).toUpperCase();
                        if (p === 'JSS') return 'Junior';
                        if (p === 'SSS') return 'Senior';
                        return null;
                    }
                    function filterSubjects() {
                        const g = gradeForClass(classSelect.value);
                        const opts = subjectSelect.querySelectorAll('option[data-grade]');
                        const sel = subjectSelect.value;
                        let ok = false;
                        opts.forEach(function (opt) {
                            const show = !g || opt.getAttribute('data-grade') === g;
                            opt.style.display = show ? '' : 'none';
                            opt.disabled = !show;
                            if (opt.value === sel && show) ok = true;
                        });
                        if (sel && !ok) subjectSelect.value = '';
                    }
                    classSelect.addEventListener('change', filterSubjects);
                    filterSubjects();
                }

                let term = @json($term ?? 'First Term');
                let session = @json($session ?? '');
                let className = @json($class ?? '');
                let subjects = @json($subjects ?? '');
                let uploadUrl = @json($uploadUrl);
                let already = @json($alreadyUploaded ?? false);
                let token = @json(csrf_token());

                const btn = document.getElementById('results-upload-submit');
                if (!btn || already) return;

                btn.addEventListener('click', function () {
                    const rows = document.querySelectorAll('.results-sheet-row');
                    const results = [];
                    let err = null;
                    rows.forEach(function (row) {
                        const ca = row.querySelector('input[name="ca"]');
                        const as = row.querySelector('input[name="assignment"]');
                        const ex = row.querySelector('input[name="exam"]');
                        if (!ca || !as || !ex) return;
                        const caV = parseFloat(ca.value);
                        const asV = parseFloat(as.value);
                        const exV = parseFloat(ex.value);
                        if (isNaN(caV) || isNaN(asV) || isNaN(exV)) { err = 'Enter all scores for every student.'; return; }
                        if (caV < 0 || caV > 15 || asV < 0 || asV > 25 || exV < 0 || exV > 60) { err = 'CA must be â‰¤15, Assign â‰¤25, Exam â‰¤60.'; return; }
                        results.push({
                            studentId: row.querySelector('.r-studentId').value,
                            class: className,
                            term: term,
                            session: session,
                            subjects: subjects,
                            name: row.querySelector('.r-name').value,
                            reg_number: row.querySelector('.r-reg').value
                        });
                        results[results.length - 1].ca = caV;
                        results[results.length - 1].assignment = asV;
                        results[results.length - 1].exam = exV;
                    });
                    if (err) {
                        if (typeof markEmptyResultsScoreInputsOnSubmit === 'function') markEmptyResultsScoreInputsOnSubmit();
                        if (typeof flashError === 'function') flashError(err);
                        return;
                    }
                    document.querySelectorAll('.results-score-input-empty').forEach(function (el) { el.classList.remove('results-score-input-empty'); });
                    if (!results.length) {
                        if (typeof flashError === 'function') flashError('No rows to upload.');
                        return;
                    }

                    if (typeof setButtonLoading === 'function') setButtonLoading(btn, true);

                    fetch(uploadUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ results: results })
                    })
                        .then(function (r) {
                            return r.text().then(function (text) {
                                let d = {};
                                try { d = text ? JSON.parse(text) : {}; } catch (e) { d = {}; }
                                return { ok: r.ok, d: d };
                            });
                        })
                        .then(function (res) {
                            if (typeof setButtonLoading === 'function') setButtonLoading(btn, false);
                            if (!res.ok) {
                                let msg = res.d.message || 'Upload failed. Please try again later.';
                                if (res.d.errors && typeof res.d.errors === 'object') {
                                    const keys = Object.keys(res.d.errors);
                                    if (keys.length) {
                                        const m = res.d.errors[keys[0]];
                                        msg = Array.isArray(m) ? m[0] : m;
                                    }
                                }
                                if (typeof flashError === 'function') flashError(msg);
                                return;
                            }
                            const d = res.d;
                            if (d.status === 'success') {
                                if (typeof flashSuccess === 'function') flashSuccess(d.message || 'Results saved.');
                                setTimeout(function () { window.location.href = @json($viewUploadedUrl); }, 2800);
                            } else {
                                if (typeof flashError === 'function') flashError(d.message || 'Upload failed. Please try again later.');
                            }
                        })
                        .catch(function () {
                            if (typeof setButtonLoading === 'function') setButtonLoading(btn, false);
                            if (typeof flashError === 'function') flashError('Request failed. Check your connection and try again.');
                        });
                });
            })();
        </script>
    @endpush
@endsection
