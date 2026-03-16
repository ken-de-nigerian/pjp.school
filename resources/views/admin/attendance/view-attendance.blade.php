@extends('layouts.app')

@section('content')
    @php
        $records = $students ?? collect();
        $isPresent = fn ($record) => (int) ($record->class_roll_call ?? 0) === 1;
    @endphp
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <div class="mb-4 sm:mb-6 w-fit">
                <a href="{{ route('admin.attendance.index') }}" class="inline-flex items-center gap-2 text-sm font-medium transition-opacity hover:opacity-80" style="color: var(--on-surface-variant);">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    Back to Attendance
                </a>
            </div>

            <header class="mb-6 lg:mb-8 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                <div class="min-w-0 flex-1">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-normal tracking-tight mb-1.5" style="color: var(--on-surface); letter-spacing: -0.02em;">View Attendance</h1>
                    <p class="text-sm sm:text-base font-normal" style="color: var(--on-surface-variant);">
                        @if($hasFilters)
                            View and edit attendance for the selected date, class, term, session and segment.
                        @else
                            Filter by date, class, term, session, and segment to view uploaded records.
                        @endif
                    </p>
                </div>

                @if($hasFilters)
                    <a href="{{ route('admin.attendance.view') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-opacity hover:opacity-90 shrink-0" style="color: var(--on-surface-variant); background: var(--surface-container-high); border-radius: 12px;">
                        <i class="fas fa-filter text-xs" aria-hidden="true"></i>
                        <span>Change filters</span>
                    </a>
                @endif
            </header>

            @if(!$hasFilters)
            <div class="rounded-3xl p-4 sm:p-5 lg:p-6 mb-6 overflow-hidden min-w-0 w-full" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                <form method="GET" action="{{ route('admin.attendance.view') }}" class="space-y-4 sm:space-y-5">
                    <div class="grid grid-cols-12 gap-4 min-w-0">
                        <div class="col-span-12 sm:col-span-6 form-group min-w-0">
                            <label for="view-attendance-date" class="form-label">Date</label>
                            <input type="date" id="view-attendance-date" name="date" class="form-input w-full min-w-0" value="{{ e($date ?? date('Y-m-d')) }}">
                            <p id="date-error" class="form-error mt-1 text-sm {{ $errors->has('date') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('date') }}</p>
                        </div>
                        <div class="col-span-12 sm:col-span-6 form-group min-w-0">
                            <label for="view-attendance-class" class="form-label">Class</label>
                            <select id="view-attendance-class" name="class" class="form-select w-full min-w-0">
                                <option value="">Select class</option>
                                @foreach($classes as $c)
                                    <option value="{{ e($c->class_name) }}" {{ ($class ?? '') === $c->class_name ? 'selected' : '' }}>{{ e($c->class_name) }}</option>
                                @endforeach
                            </select>
                            <p id="class-error" class="form-error mt-1 text-sm {{ $errors->has('class') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('class') }}</p>
                        </div>
                        <div class="col-span-12 sm:col-span-6 form-group min-w-0">
                            <label for="view-attendance-term" class="form-label">Term</label>
                            <select id="view-attendance-term" name="term" class="form-select w-full min-w-0">
                                <option value="First Term" {{ ($term ?? $settings['term'] ?? '') === 'First Term' ? 'selected' : '' }}>First Term</option>
                                <option value="Second Term" {{ ($term ?? $settings['term'] ?? '') === 'Second Term' ? 'selected' : '' }}>Second Term</option>
                                <option value="Third Term" {{ ($term ?? $settings['term'] ?? '') === 'Third Term' ? 'selected' : '' }}>Third Term</option>
                            </select>
                            <p id="term-error" class="form-error mt-1 text-sm {{ $errors->has('term') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('term') }}</p>
                        </div>
                        <div class="col-span-12 sm:col-span-6 form-group min-w-0">
                            <label for="view-attendance-session" class="form-label">Session</label>
                            <select id="view-attendance-session" name="session" class="form-select w-full min-w-0">
                                <option value="">Select session</option>
                                @foreach(range((int)date('Y') - 5, (int)date('Y') + 5) as $y)
                                    @php $opt = $y . '/' . ($y + 1); @endphp
                                    <option value="{{ $opt }}" {{ ($session ?? $settings['session'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                            <p id="session-error" class="form-error mt-1 text-sm {{ $errors->has('session') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('session') }}</p>
                        </div>
                        <div class="col-span-12 sm:col-span-6 form-group min-w-0">
                            <label for="view-attendance-segment" class="form-label">Segment</label>
                            <select id="view-attendance-segment" name="segment" class="form-select w-full min-w-0">
                                <option value="First" {{ ($segment ?? $settings['segment'] ?? '') === 'First' ? 'selected' : '' }}>First Segment</option>
                                <option value="Second" {{ ($segment ?? $settings['segment'] ?? '') === 'Second' ? 'selected' : '' }}>Second Segment</option>
                                <option value="Third" {{ ($segment ?? $settings['segment'] ?? '') === 'Third' ? 'selected' : '' }}>Third Segment</option>
                            </select>
                            <p id="segment-error" class="form-error mt-1 text-sm {{ $errors->has('segment') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('segment') }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2 min-w-0" style="border-top: 1px solid var(--outline-variant); padding-top: 1.25rem;">
                        <a href="{{ route('admin.attendance.view') }}" class="btn-secondary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 sm:min-w-[120px]" style="border-radius: 12px;">
                            <i class="fas fa-times text-sm" aria-hidden="true"></i>
                            Clear
                        </a>
                        <button type="submit" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" data-preloader style="border-radius: 12px;">
                            <i class="fas fa-eye text-sm" aria-hidden="true"></i>
                            View attendance
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
                    <p class="text-sm text-center max-w-sm" style="color: var(--on-surface-variant);">Choose date, class, term, session and segment in the form above, then click &quot;View attendance&quot; to see records.</p>
                </div>
            @else
            <div class="flex flex-wrap gap-3 sm:gap-4 mb-6">
                <div class="rounded-xl px-4 py-2.5" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    <span class="text-xs font-medium" style="color: var(--on-surface-variant);">Date</span>
                    <p class="text-sm font-medium mt-0.5" style="color: var(--on-surface);">{{ $date }}</p>
                </div>
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
                <div class="rounded-xl px-4 py-2.5" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    <span class="text-xs font-medium" style="color: var(--on-surface-variant);">Segment</span>
                    <p class="text-sm font-medium mt-0.5" style="color: var(--on-surface);">{{ $segment }}</p>
                </div>
            </div>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                @if($records->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 px-6">
                        <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5" style="border-radius: 16px;">
                            <i class="fas fa-calendar-day text-3xl" aria-hidden="true"></i>
                        </div>
                        <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No records for this selection</h2>
                        <p class="text-sm text-center max-w-sm mb-6" style="color: var(--on-surface-variant);">There are no attendance records for {{ $date }}, {{ $class }}, {{ $term }}, {{ $session }}, {{ $segment }}. Take attendance first or choose another date.</p>
                        <div class="flex justify-center">
                            <a href="{{ route('admin.attendance.view') }}" class="btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] rounded-xl font-medium text-sm transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;">
                                <i class="fas fa-filter text-sm"></i>
                                Change filters
                            </a>
                        </div>
                    </div>
                @else
                    <form id="attendance-records-form" class="flex flex-col min-h-0">
                        @csrf
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 px-5 sm:px-6 py-4" style="border-bottom: 1px solid var(--outline-variant);">
                            <p class="text-sm font-medium" style="color: var(--on-surface-variant);">
                                <span id="records-count">{{ $records->count() }}</span> record(s) · <span id="present-count">0</span> present, <span id="absent-count">0</span> absent
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" id="mark-all-present" class="attendance-bulk-btn attendance-bulk-btn--present" aria-pressed="false">All Present</button>
                                <button type="button" id="mark-all-absent" class="attendance-bulk-btn attendance-bulk-btn--absent" aria-pressed="true">All Absent</button>
                                <button type="button" id="delete-all-records-btn" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium transition-all border" style="background: var(--error-container); color: var(--on-error-container); border-color: var(--outline-variant);" title="Delete all records for this date">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                    Delete all
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0 border-x border-b" style="border-color: var(--outline-variant);">
                            <ul class="divide-y divide-[var(--outline-variant)]" role="list">
                                @foreach($records as $index => $record)
                                    @php $present = $isPresent($record); @endphp
                                    <li class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 px-4 sm:px-6 py-4 transition-colors attendance-record-row" style="background: var(--surface-container-lowest);">
                                        <div class="flex items-center min-w-0 flex-1 gap-3 sm:gap-4">
                                            <span class="text-sm font-medium w-7 sm:w-8 flex-shrink-0" style="color: var(--on-surface-variant);">{{ $index + 1 }}</span>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-medium break-words" style="color: var(--on-surface);">{{ $record->name ?? '—' }}</p>
                                                <p class="text-xs truncate" style="color: var(--on-surface-variant);">{{ $record->reg_number ?? '' }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 flex-shrink-0 sm:pl-0 pl-10" role="group" aria-label="Attendance for {{ e($record->name) }}">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="radio" name="attendance_row_{{ $index }}" value="Present" class="attendance-radio attendance-radio-present sr-only peer" data-reg="{{ e($record->reg_number) }}" data-name="{{ e($record->name) }}" data-initial="{{ $present ? 'Present' : 'Absent' }}" {{ $present ? 'checked' : '' }}>
                                                <span class="px-3 sm:px-4 py-2 rounded-xl text-sm font-medium transition-all peer-checked:opacity-100 peer-checked:ring-2 peer-checked:ring-[var(--primary)] opacity-60" style="background: var(--primary-container); color: var(--on-primary-container);">Present</span>
                                            </label>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="radio" name="attendance_row_{{ $index }}" value="Absent" class="attendance-radio attendance-radio-absent sr-only peer" data-reg="{{ e($record->reg_number) }}" data-name="{{ e($record->name) }}" data-initial="{{ $present ? 'Present' : 'Absent' }}" {{ $present ? '' : 'checked' }}>
                                                <span class="px-3 sm:px-4 py-2 rounded-xl text-sm font-medium transition-all peer-checked:opacity-100 peer-checked:ring-2 peer-checked:ring-[var(--outline)] opacity-60" style="background: var(--surface-container-high); color: var(--on-surface-variant);">Absent</span>
                                            </label>
                                            <button type="button" class="attendance-delete-one inline-flex items-center justify-center w-9 h-9 rounded-xl text-sm transition-opacity hover:opacity-100 opacity-80" style="background: var(--error-container); color: var(--on-error-container);" data-reg="{{ e($record->reg_number) }}" data-name="{{ e($record->name) }}" title="Delete this record" aria-label="Delete record for {{ e($record->name) }}">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="flex justify-center sm:justify-end px-5 sm:px-6 py-5" style="border-top: 1px solid var(--outline-variant); background: var(--surface-container-low);">
                            <button type="submit" id="save-records-btn" class="btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[160px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;">
                                <i class="fas fa-save text-sm" aria-hidden="true"></i>
                                Save changes
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            @if($hasFilters && $records->isNotEmpty())
            <div id="attendance-delete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto overscroll-contain" aria-modal="true" role="dialog" aria-labelledby="attendance-delete-modal-title">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="attendance-delete-modal" aria-hidden="true"></div>
                <div class="relative min-h-full min-h-[100dvh] flex items-center justify-center p-4 py-6 sm:p-6">
                    <div class="relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-xl py-5 px-4 sm:py-6 sm:px-6 shadow-xl border my-auto" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                        <h3 id="attendance-delete-modal-title" class="text-lg font-semibold mb-2" style="color: var(--on-surface);">Delete record</h3>
                        <p id="attendance-delete-modal-message" class="text-sm mb-6" style="color: var(--on-surface-variant);">Are you sure you want to delete this attendance record?</p>
                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-2">
                            <button type="button" class="btn-secondary px-4 py-2.5 rounded-full text-sm w-full sm:w-auto" data-close="attendance-delete-modal">Cancel</button>
                            <button type="button" id="attendance-delete-modal-confirm" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full text-sm font-medium w-full sm:w-auto transition-opacity hover:opacity-95" style="background: var(--error-container); color: var(--on-error-container);">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endif
        </div>
    </main>

    @if($hasFilters && !$records->isEmpty())
    @push('scripts')
        <script>
            (function() {
                const form = document.getElementById('attendance-records-form');
                const saveBtn = document.getElementById('save-records-btn');
                const presentCountEl = document.getElementById('present-count');
                const absentCountEl = document.getElementById('absent-count');
                const classVal = @json($class);
                const termVal = @json($term);
                const sessionVal = @json($session);
                const segmentVal = @json($segment);
                const dateVal = @json($date);

                const csrfToken = form.querySelector('input[name="_token"]').value;
                const deleteUrl = @json(route('admin.attendance.destroy'));

                const presentRadios = form.querySelectorAll('input.attendance-radio-present');
                const absentRadios = form.querySelectorAll('input.attendance-radio-absent');
                const markAllPresentBtn = document.getElementById('mark-all-present');
                const markAllAbsentBtn = document.getElementById('mark-all-absent');
                const total = presentRadios.length;

                function getCurrent(rowIndex) {
                    return presentRadios[rowIndex].checked ? 'Present' : 'Absent';
                }

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

                form.querySelectorAll('.attendance-radio').forEach(radio => {
                    radio.addEventListener('change', updateBulkButtons);
                });
                updateBulkButtons();

                markAllPresentBtn.addEventListener('click', function() {
                    presentRadios.forEach(r => { r.checked = true; });
                    updateBulkButtons();
                });
                markAllAbsentBtn.addEventListener('click', function() {
                    absentRadios.forEach(r => { r.checked = true; });
                    updateBulkButtons();
                });

                async function doDelete(payload) {
                    const res = await fetch(deleteUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(payload)
                    });
                    const data = await res.json().catch(() => ({}));
                    return { ok: res.ok, data };
                }

                const deleteModal = document.getElementById('attendance-delete-modal');
                const deleteModalTitle = document.getElementById('attendance-delete-modal-title');
                const deleteModalMessage = document.getElementById('attendance-delete-modal-message');
                const deleteModalConfirm = document.getElementById('attendance-delete-modal-confirm');
                let pendingDeletePayload = null;

                function openDeleteModal(title, message, payload) {
                    pendingDeletePayload = payload;
                    deleteModalTitle.textContent = title;
                    deleteModalMessage.textContent = message;
                    deleteModal.classList.remove('hidden');
                }
                function closeDeleteModal() {
                    deleteModal.classList.add('hidden');
                    pendingDeletePayload = null;
                }

                document.querySelectorAll('[data-close="attendance-delete-modal"]').forEach(function(el) {
                    el.addEventListener('click', closeDeleteModal);
                });

                deleteModalConfirm.addEventListener('click', async function() {
                    if (!pendingDeletePayload) return;
                    const payload = pendingDeletePayload;
                    setButtonLoading(deleteModalConfirm, true);
                    try {
                        const { ok, data } = await doDelete(payload);
                        setButtonLoading(deleteModalConfirm, false);
                        closeDeleteModal();
                        if (ok && data.status === 'success') {
                            flashSuccess(data.message || 'Record deleted.');
                            setTimeout(function() { window.location.reload(); }, 2800);
                        } else {
                            flashError(data.message || 'Failed to delete.');
                        }
                    } catch (err) {
                        setButtonLoading(deleteModalConfirm, false);
                        closeDeleteModal();
                        flashError('An error occurred. Please try again.');
                    }
                });

                document.getElementById('delete-all-records-btn').addEventListener('click', function() {
                    openDeleteModal(
                        'Delete all records?',
                        'Delete all attendance records for this date? This cannot be undone.',
                        { class: classVal, term: termVal, session: sessionVal, segment: segmentVal, date: dateVal }
                    );
                });

                form.querySelectorAll('.attendance-delete-one').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const reg = this.dataset.reg;
                        const name = this.dataset.name || reg;
                        openDeleteModal(
                            'Delete record?',
                            'Delete attendance record for ' + (name || reg) + '?',
                            { reg_number: reg, class: classVal, term: termVal, session: sessionVal, segment: segmentVal, date: dateVal }
                        );
                    });
                });

                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const rowsToUpdate = [];
                    for (let i = 0; i < presentRadios.length; i++) {
                        const initial = presentRadios[i].dataset.initial;
                        const current = getCurrent(i);
                        if (current !== initial) {
                            rowsToUpdate.push({
                                reg_number: presentRadios[i].dataset.reg,
                                class_roll_call: current
                            });
                        }
                    }
                    if (rowsToUpdate.length === 0) {
                        flashWarning('No changes to save.');
                        return;
                    }

                    setButtonLoading(saveBtn, true);

                    try {
                        const res = await fetch(@json(route('admin.attendance.edit')), {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                class: classVal,
                                term: termVal,
                                session: sessionVal,
                                segment: segmentVal,
                                date: dateVal,
                                updates: rowsToUpdate
                            })
                        });

                        const data = await res.json().catch(() => ({}));

                        setButtonLoading(saveBtn, false);

                        if (res.ok && data.status === 'success') {
                            flashSuccess(data.message || 'Records updated successfully.');
                            setTimeout(function() { window.location.reload(); }, 2800);
                        } else {
                            flashError(data.message || 'Failed to update records. Please try again.');
                        }
                    } catch (err) {
                        console.error('Update error:', err);
                        setButtonLoading(saveBtn, false);
                        flashError('An error occurred. Please try again.');
                    }
                });
            })();
        </script>
    @endpush
    @endif
@endsection
