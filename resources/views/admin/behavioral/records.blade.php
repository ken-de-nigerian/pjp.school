@extends('layouts.app')

@section('content')
    @php
        $records = $students ?? collect();
        $behaviorFields = [
            'neatness'      => ['label' => 'Neatness',      'icon' => 'fa-broom'],
            'music'         => ['label' => 'Music',         'icon' => 'fa-music'],
            'sports'        => ['label' => 'Sports',        'icon' => 'fa-futbol'],
            'attentiveness' => ['label' => 'Attentiveness', 'icon' => 'fa-eye'],
            'punctuality'   => ['label' => 'Punctuality',   'icon' => 'fa-clock'],
            'health'        => ['label' => 'Health',        'icon' => 'fa-heartbeat'],
            'politeness'    => ['label' => 'Politeness',    'icon' => 'fa-hand-holding-heart'],
        ];
    @endphp
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <div class="mb-4 sm:mb-6 w-fit">
                <a href="{{ route('admin.behavioral.view') }}" class="inline-flex items-center gap-2 text-sm font-medium transition-opacity hover:opacity-80" style="color: var(--on-surface-variant);">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    Back to View Behavioural
                </a>
            </div>

            <header class="mb-6 lg:mb-8">
                <div class="flex items-start gap-4">
                    <div class="dashboard-quick-icon dashboard-quick-icon--blue w-12 h-12 rounded-2xl flex-shrink-0 flex items-center justify-center" style="border-radius: 16px;">
                        <i class="fas fa-clipboard-list text-lg" aria-hidden="true"></i>
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-normal tracking-tight mb-1.5" style="color: var(--on-surface); letter-spacing: -0.02em;">Behavioural records</h1>
                        <p class="text-sm sm:text-base font-normal" style="color: var(--on-surface-variant);">View records, click Edit to change a student's analysis, or delete individually or all at once.</p>
                    </div>
                </div>
            </header>

            <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2 sm:gap-4 mb-6 min-w-0">
                <div class="rounded-xl px-3 py-2.5 sm:px-4 min-w-0 sm:max-w-[14rem]" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    <span class="text-xs font-medium uppercase tracking-wider block truncate" style="color: var(--on-surface-variant);">Class</span>
                    <p class="text-sm font-semibold mt-0.5 truncate" style="color: var(--on-surface);" title="{{ e($class) }}">{{ $class }}</p>
                </div>
                <div class="rounded-xl px-3 py-2.5 sm:px-4 min-w-0 sm:max-w-[12rem]" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    <span class="text-xs font-medium uppercase tracking-wider block truncate" style="color: var(--on-surface-variant);">Term</span>
                    <p class="text-sm font-semibold mt-0.5 truncate" style="color: var(--on-surface);" title="{{ e($term) }}">{{ $term }}</p>
                </div>
                <div class="rounded-xl px-3 py-2.5 sm:px-4 min-w-0 sm:max-w-[10rem]" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    <span class="text-xs font-medium uppercase tracking-wider block truncate" style="color: var(--on-surface-variant);">Session</span>
                    <p class="text-sm font-semibold mt-0.5 truncate" style="color: var(--on-surface);" title="{{ e($session) }}">{{ $session }}</p>
                </div>
                <div class="rounded-xl px-3 py-2.5 sm:px-4 min-w-0 sm:max-w-[10rem] col-span-2 sm:col-span-1" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    <span class="text-xs font-medium uppercase tracking-wider block truncate" style="color: var(--on-surface-variant);">Segment</span>
                    <p class="text-sm font-semibold mt-0.5 truncate" style="color: var(--on-surface);" title="{{ e($segment) }}">{{ $segment }}</p>
                </div>
            </div>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                @if($records->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 px-6">
                        <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5" style="border-radius: 16px;">
                            <i class="fas fa-clipboard-list text-3xl" aria-hidden="true"></i>
                        </div>
                        <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No records for this selection</h2>
                        <p class="text-sm text-center max-w-sm mb-6" style="color: var(--on-surface-variant);">There are no behavioural records for {{ $class }}, {{ $term }}, {{ $session }}, {{ $segment }}. Take behavioural analysis first or choose another filter.</p>
                        <div class="flex justify-center">
                            <a href="{{ route('admin.behavioral.view') }}"
                               class="btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] rounded-xl font-medium text-sm transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;">
                                <i class="fas fa-arrow-left text-sm"></i>
                                Back to View Behavioural
                            </a>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 sm:px-6 py-4" style="border-bottom: 1px solid var(--outline-variant); background: var(--surface-container-low);">
                        <p class="text-sm font-medium" style="color: var(--on-surface-variant);">
                            <span id="behavioral-records-count">{{ $records->count() }}</span> record(s)
                        </p>
                        <button type="button" id="behavioral-delete-all-btn" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium transition-opacity hover:opacity-90" style="background: var(--error-container); color: var(--on-error-container); border: 1px solid var(--outline-variant);" title="Delete all records for this class, term, session and segment">
                            <i class="fas fa-trash-alt text-xs"></i>
                            Delete all
                        </button>
                    </div>

                    <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0 w-full min-w-0 max-h-[min(70vh,32rem)] lg:max-h-none lg:min-h-[12rem]" style="border-color: var(--outline-variant); -webkit-overflow-scrolling: touch;">
                        {{-- Desktop: table header --}}
                        <div class="hidden lg:grid lg:grid-cols-behavioral-records sticky top-0 z-10 px-4 sm:px-6 py-3 gap-x-2 gap-y-1 text-xs font-semibold uppercase tracking-wider min-w-0" style="background: var(--surface-container); border-bottom: 1px solid var(--outline-variant); color: var(--on-surface-variant);">
                            <span class="lg:pl-2 min-w-0">#</span>
                            <span class="sr-only min-w-0">Photo</span>
                            <span class="min-w-0 truncate">Student</span>
                            @foreach($behaviorFields as $key => $config)
                                <span class="min-w-0 truncate" title="{{ $config['label'] }}">{{ $config['label'] }}</span>
                            @endforeach
                            <span class="min-w-0 truncate text-right pr-1">Actions</span>
                        </div>

                        <ul class="divide-y divide-[var(--outline-variant)] min-w-0 w-full" role="list" id="behavioral-records-list">
                            @foreach($records as $index => $record)
                                @php
                                    $avatarSrc = ($record->imagelocation ?? null)
                                        ? (str_starts_with($record->imagelocation, 'students/') ? asset('storage/' . $record->imagelocation) : asset('storage/students/' . $record->imagelocation))
                                        : asset('storage/students/default.png');
                                    $avatarInitial = $record->name ? mb_substr(trim($record->name), 0, 1) : 'S';
                                @endphp
                                <li class="behavioral-record-row border-b border-[var(--outline-variant)] last:border-b-0 min-w-0 w-full" style="background: var(--surface-container-lowest);" data-reg="{{ e($record->reg_number) }}" data-name="{{ e($record->name ?? '') }}">
                                    {{-- View mode: on mobile = card; on desktop = grid (avatar matches all-students table) --}}
                                    <div class="behavioral-view-mode flex flex-col lg:grid lg:grid-cols-behavioral-records gap-3 lg:gap-x-2 lg:gap-y-2 lg:items-stretch px-4 sm:px-6 py-4 lg:py-3 min-w-0">
                                        {{-- Mobile: # + avatar + name in one row; lg: each becomes grid cell via contents --}}
                                        <div class="flex items-center gap-3 min-w-0 lg:contents">
                                            <span class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center text-sm font-semibold lg:w-8 lg:h-8 lg:place-self-center" style="background: var(--primary-container); color: var(--on-primary-container);">{{ $index + 1 }}</span>
                                            <img src="{{ $avatarSrc }}" alt="" width="40" height="40" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2 lg:place-self-center" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($avatarInitial) }}&size=80'">
                                            <div class="flex flex-col justify-center min-w-0 flex-1 lg:flex-none lg:min-w-0 lg:py-1 lg:pr-2 overflow-hidden">
                                                <p class="text-sm font-semibold truncate" style="color: var(--on-surface);" title="{{ e($record->name ?? '') }}">{{ e($record->name ?? '—') }}</p>
                                                <p class="text-xs truncate mt-0.5" style="color: var(--on-surface-variant);" title="{{ e($record->reg_number ?? '') }}">{{ e($record->reg_number ?? '') }}</p>
                                            </div>
                                        </div>

                                        @foreach($behaviorFields as $key => $config)
                                            <div class="behavioral-view-cell flex flex-col justify-center min-w-0 overflow-hidden lg:py-1" data-field="{{ $key }}">
                                                <span class="lg:sr-only text-xs font-medium mt-1 lg:mt-0" style="color: var(--on-surface-variant);">{{ $config['label'] }}:</span>
                                                <p class="behavioral-view-value text-sm font-normal overflow-hidden line-clamp-2 break-words" style="color: var(--on-surface);" title="{{ e($record->$key ?? '') }}">{{ e($record->$key ?? '—') }}</p>
                                            </div>
                                        @endforeach

                                        <div class="flex flex-wrap items-stretch sm:items-center gap-2 pt-1 lg:pt-0 lg:py-1 lg:justify-end lg:place-self-center flex-shrink-0 w-full lg:w-auto">
                                            <button type="button" class="behavioral-edit-btn inline-flex items-center justify-center gap-1.5 px-3 sm:px-4 py-2 rounded-xl text-xs font-medium transition-opacity hover:opacity-95 whitespace-nowrap" style="background: var(--primary-container); color: var(--on-primary-container); border-radius: 12px;" title="Edit this record">
                                                <i class="fas fa-pen text-xs"></i>
                                                Edit
                                            </button>

                                            <button type="button" class="behavioral-delete-one inline-flex items-center justify-center gap-1.5 px-3 sm:px-4 py-2 rounded-xl text-xs font-medium transition-opacity hover:opacity-90 whitespace-nowrap" style="background: var(--error-container); color: var(--on-error-container); border-radius: 12px;" title="Delete this record">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                                Delete
                                            </button>
                                        </div>
                                    </div>

                                    <div class="behavioral-edit-mode hidden flex flex-col gap-4 p-4 sm:px-6 pb-5 min-w-0 overflow-hidden" style="background: var(--surface-container-low); border-top: 1px solid var(--outline-variant);">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <img src="{{ $avatarSrc }}" alt="" width="40" height="40" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($avatarInitial) }}&size=80'">
                                            <p class="text-sm font-semibold truncate flex-1 min-w-0" style="color: var(--on-surface);" title="{{ e($record->name ?? '') }}">Edit · <span class="font-normal" style="color: var(--on-surface-variant);">{{ e($record->name ?? '') }}</span></p>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 min-w-0">
                                            @foreach($behaviorFields as $key => $config)
                                                <div class="form-group min-w-0 overflow-hidden">
                                                    <label for="record-{{ $index }}-{{ $key }}" class="form-label flex items-center gap-2 text-xs font-medium">
                                                        <i class="fas {{ $config['icon'] }} opacity-70 flex-shrink-0" style="color: var(--on-surface-variant);"></i>
                                                        <span class="truncate">{{ $config['label'] }}</span>
                                                    </label>
                                                    <textarea id="record-{{ $index }}-{{ $key }}" class="behavioral-record-field form-input w-full min-w-0 resize-y rounded-xl border min-h-[2.5rem] text-sm py-2 px-3 max-h-24" rows="2" maxlength="255" data-field="{{ $key }}" placeholder="Comment or note" style="border-color: var(--outline-variant); background: var(--surface-container-lowest);">{{ e($record->$key ?? '') }}</textarea>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="flex flex-col-reverse w-full gap-3 sm:flex-row sm:justify-end sm:gap-2">
                                            <button type="button" class="behavioral-cancel-edit btn-secondary w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-3 sm:px-5 sm:py-2.5 rounded-xl text-sm font-medium whitespace-nowrap min-h-[2.75rem] sm:min-h-0" style="border-radius: 12px;">
                                                Cancel
                                            </button>

                                            <button type="button" class="behavioral-save-row btn-primary w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-3 sm:px-5 sm:py-2.5 rounded-xl text-sm font-medium hover:opacity-95 whitespace-nowrap min-h-[2.75rem] sm:min-h-0" style="border-radius: 12px;">
                                                <i class="fas fa-save text-xs"></i>
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </main>

    @if($records->isNotEmpty())
        <div id="behavioral-delete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto overscroll-contain" aria-modal="true" role="dialog" aria-labelledby="behavioral-delete-modal-title">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="behavioral-delete-modal" aria-hidden="true"></div>
            <div class="relative min-h-full min-h-[100dvh] flex items-center justify-center p-4 py-6 sm:p-6">
                <div class="relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-xl py-5 px-4 sm:py-6 sm:px-6 shadow-xl border my-auto" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                    <h3 id="behavioral-delete-modal-title" class="text-lg font-semibold mb-2" style="color: var(--on-surface);">Delete record</h3>
                    <p id="behavioral-delete-modal-message" class="text-sm mb-6" style="color: var(--on-surface-variant);">Are you sure you want to delete this behavioural record?</p>
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-2">
                        <button type="button" class="btn-secondary px-4 py-2.5 rounded-full text-sm w-full sm:w-auto" data-close="behavioral-delete-modal">Cancel</button>
                        <button type="button" id="behavioral-delete-modal-confirm" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full text-sm font-medium w-full sm:w-auto transition-opacity hover:opacity-95" style="background: var(--error-container); color: var(--on-error-container);">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        .lg\:grid-cols-behavioral-records {
            grid-template-columns: 2.75rem 2.75rem minmax(6.5rem, 1.05fr) repeat(7, minmax(3.5rem, 0.92fr)) minmax(7.5rem, auto);
        }
        @media (max-width: 1023px) {
            .lg\:grid-cols-behavioral-records { grid-template-columns: 1fr; }
        }
        @media (min-width: 1024px) and (max-width: 1279px) {
            .lg\:grid-cols-behavioral-records {
                grid-template-columns: 2.5rem 2.5rem minmax(5rem, 0.95fr) repeat(7, minmax(3rem, 0.85fr)) minmax(7rem, auto);
            }
        }
        .behavioral-record-row.is-editing .behavioral-view-mode { display: none !important; }
        .behavioral-record-row.is-editing .behavioral-edit-mode { display: flex !important; }
        /* Consistent text: prevent overflow, max 2 lines for trait values */
        .behavioral-view-value {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            word-break: break-word;
            overflow-wrap: break-word;
        }
        @media (min-width: 1024px) {
            .behavioral-view-cell .behavioral-view-value {
                min-height: 2.5rem;
            }
            html {
                overflow: hidden;
                height: 100%;
            }
        }
    </style>

    @push('scripts')
        @if($records->isNotEmpty())
        <script>
            (function() {
                const editUrl = @json(route('admin.behavioral.edit'));
                const deleteOneUrl = @json(route('admin.behavioral.destroy-one'));
                const deleteAllUrl = @json(route('admin.behavioral.delete-all'));
                const classVal = @json($class);
                const termVal = @json($term);
                const sessionVal = @json($session);
                const segmentVal = @json($segment);
                const fieldKeys = @json(array_keys($behaviorFields));
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                function getPayload(row) {
                    const reg = row.dataset.reg || '';
                    const payload = { reg_number: reg, class: classVal, term: termVal, session: sessionVal, segment: segmentVal };
                    fieldKeys.forEach(function(key) {
                        const input = row.querySelector('.behavioral-record-field[data-field="' + key + '"]');
                        payload[key] = input ? (input.value || '').trim().slice(0, 255) : '';
                    });
                    return payload;
                }

                function setRowViewValues(row, data) {
                    fieldKeys.forEach(function(key) {
                        const cell = row.querySelector('.behavioral-view-cell[data-field="' + key + '"]');
                        const input = row.querySelector('.behavioral-record-field[data-field="' + key + '"]');
                        if (cell) cell.querySelector('p').textContent = (data && data[key]) ? data[key] : '—';
                        if (input) input.value = (data && data[key]) ? data[key] : '';
                    });
                }

                function closeEditMode(row) {
                    row.classList.remove('is-editing');
                    row.querySelector('.behavioral-edit-mode').classList.add('hidden');
                }

                function openEditMode(row) {
                    document.querySelectorAll('.behavioral-record-row.is-editing').forEach(function(r) { closeEditMode(r); });
                    row.classList.add('is-editing');
                    row.querySelector('.behavioral-edit-mode').classList.remove('hidden');
                }

                document.getElementById('behavioral-records-list').addEventListener('click', function(e) {
                    const row = e.target.closest('.behavioral-record-row');
                    if (!row) return;

                    if (e.target.closest('.behavioral-edit-btn')) {
                        e.preventDefault();
                        openEditMode(row);
                    }
                    if (e.target.closest('.behavioral-cancel-edit')) {
                        e.preventDefault();
                        closeEditMode(row);
                    }
                    if (e.target.closest('.behavioral-save-row')) {
                        e.preventDefault();
                        const saveBtn = row.querySelector('.behavioral-save-row');
                        setButtonLoading(saveBtn, true);
                        const payload = getPayload(row);
                        fetch(editUrl, {
                            method: 'PUT',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
                            body: JSON.stringify(payload)
                        })
                        .then(function(res) { return res.json().catch(function() { return {}; }).then(function(data) { return { ok: res.ok, data: data }; }); })
                        .then(function(result) {
                            setButtonLoading(saveBtn, false);
                            if (result.ok && result.data.status === 'success') {
                                setRowViewValues(row, payload);
                                closeEditMode(row);
                                flashSuccess(result.data.message || 'Record updated.');
                            } else {
                                flashError(result.data.message || 'Failed to update record.');
                            }
                        })
                        .catch(function() {
                            setButtonLoading(saveBtn, false);
                            flashError('Network error. Please try again.');
                        });
                    }
                });

                const deleteModal = document.getElementById('behavioral-delete-modal');
                const deleteModalTitle = document.getElementById('behavioral-delete-modal-title');
                const deleteModalMessage = document.getElementById('behavioral-delete-modal-message');
                const deleteModalConfirm = document.getElementById('behavioral-delete-modal-confirm');
                let pendingDeletePayload = null;
                let pendingDeleteIsAll = false;
                let pendingDeleteRow = null;

                function openDeleteModal(title, message, payload, isAll, rowEl) {
                    pendingDeletePayload = payload;
                    pendingDeleteIsAll = isAll || false;
                    pendingDeleteRow = rowEl || null;
                    deleteModalTitle.textContent = title;
                    deleteModalMessage.textContent = message;
                    deleteModal.classList.remove('hidden');
                }
                function closeDeleteModal() {
                    deleteModal.classList.add('hidden');
                    pendingDeletePayload = null;
                    pendingDeleteIsAll = false;
                    pendingDeleteRow = null;
                }

                document.querySelectorAll('[data-close="behavioral-delete-modal"]').forEach(function(el) {
                    el.addEventListener('click', closeDeleteModal);
                });

                deleteModalConfirm.addEventListener('click', function() {
                    if (!pendingDeletePayload) return;
                    const payload = pendingDeletePayload;
                    const isAll = pendingDeleteIsAll;
                    setButtonLoading(deleteModalConfirm, true);
                    const url = isAll ? deleteAllUrl : deleteOneUrl;
                    const method = isAll ? 'POST' : 'DELETE';
                    fetch(url, {
                        method: method,
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
                        body: JSON.stringify(payload)
                    })
                    .then(function(res) { return res.json().catch(function() { return {}; }).then(function(data) { return { ok: res.ok, data: data }; }); })
                    .then(function(result) {
                        setButtonLoading(deleteModalConfirm, false);
                        closeDeleteModal();
                            if (result.ok && result.data.status === 'success') {
                                flashSuccess(result.data.message || 'Record deleted.');
                                if (isAll) {
                                    setTimeout(function() { window.location.reload(); }, 2800);
                                } else {
                                    if (pendingDeleteRow && pendingDeleteRow.parentNode) pendingDeleteRow.remove();
                                    const countEl = document.getElementById('behavioral-records-count');
                                    if (countEl) countEl.textContent = document.querySelectorAll('.behavioral-record-row').length;
                                }
                            } else {
                            flashError(result.data.message || 'Could not delete.');
                        }
                    })
                    .catch(function() {
                        setButtonLoading(deleteModalConfirm, false);
                        closeDeleteModal();
                        flashError('An error occurred. Please try again.');
                    });
                });

                document.getElementById('behavioral-delete-all-btn').addEventListener('click', function() {
                    openDeleteModal(
                        'Delete all records?',
                        'Delete all behavioural records for this class, term, session and segment? This cannot be undone.',
                        { class: classVal, term: termVal, session: sessionVal, segment: segmentVal },
                        true,
                        null
                    );
                });

                document.getElementById('behavioral-records-list').addEventListener('click', function(e) {
                    const deleteBtn = e.target.closest('.behavioral-delete-one');
                    if (!deleteBtn) return;
                    e.preventDefault();
                    const row = deleteBtn.closest('.behavioral-record-row');
                    const name = row ? row.dataset.name || row.dataset.reg : 'this student';
                    openDeleteModal(
                        'Delete record?',
                        'Delete behavioural record for ' + name + '? This cannot be undone.',
                        { reg_number: row.dataset.reg || '', class: classVal, term: termVal, session: sessionVal, segment: segmentVal },
                        false,
                        row
                    );
                });
            })();
        </script>
        @endif
    @endpush
@endsection
