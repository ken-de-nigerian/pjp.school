@extends('layouts.app', ['title' => 'Behavioural records'])

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
            @php
                $viewBehDesc = $hasFilters
                    ? 'View and edit behavioural records for the selected class, term and session.'
                    : 'Filter by class, term and session to view uploaded behavioural records.';
            @endphp
            <x-admin.hero-page
                aria-label="View behavioural records"
                pill="Teacher"
                title="View Behavioural"
                :description="$viewBehDesc"
            >
                <x-slot name="above">
                    <a href="{{ route('teacher.behavioral.index') }}" class="admin-page-hero__back mb-2 sm:mb-0">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Back to Behavioural Analysis
                    </a>
                </x-slot>
                @if($hasFilters)
                    <x-slot name="actions">
                        <a href="{{ route('teacher.behavioral.view') }}" class="admin-dashboard-hero__btn w-full lg:w-auto justify-center min-h-[44px] sm:min-h-0">
                            <i class="fas fa-filter text-xs" aria-hidden="true"></i>
                            <span>Change filters</span>
                        </a>
                    </x-slot>
                @endif
            </x-admin.hero-page>

            @if(!$hasFilters)
                <div class="rounded-3xl p-4 sm:p-5 lg:p-6 mb-6 overflow-hidden min-w-0 w-full" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    <form method="GET" action="{{ route('teacher.behavioral.view') }}" class="space-y-4 sm:space-y-5">
                        <div class="grid grid-cols-12 gap-4 min-w-0">
                            <div class="col-span-12 sm:col-span-6 form-group min-w-0">
                                <label for="view-behavioral-class" class="form-label">Class</label>
                                <x-forms.md-select-native id="view-behavioral-class" name="class" class="form-select w-full min-w-0">
                                    <option value="">Select class</option>
                                    @foreach($classes as $c)
                                        <option value="{{ e(is_string($c)?$c:($c['class_name'] ?? '')) }}" {{ ($class ?? '') === (is_string($c)?$c:($c['class_name'] ?? '')) ? 'selected' : '' }}>
                                            {{ e(is_string($c)?$c:($c['class_name'] ?? '')) }}
                                        </option>
                                    @endforeach
                                </x-forms.md-select-native>
                                <p id="class-error" class="form-error mt-1 text-sm {{ $errors->has('class') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('class') }}</p>
                            </div>

                            <div class="col-span-12 sm:col-span-6 form-group min-w-0">
                                <label for="view-behavioral-term" class="form-label">Term</label>
                                <x-forms.md-select-native id="view-behavioral-term" name="term" class="form-select w-full min-w-0">
                                    <option value="First Term" {{ ($term ?? $settings['term'] ?? '') === 'First Term' ? 'selected' : '' }}>First Term</option>
                                    <option value="Second Term" {{ ($term ?? $settings['term'] ?? '') === 'Second Term' ? 'selected' : '' }}>Second Term</option>
                                    <option value="Third Term" {{ ($term ?? $settings['term'] ?? '') === 'Third Term' ? 'selected' : '' }}>Third Term</option>
                                </x-forms.md-select-native>
                                <p id="term-error" class="form-error mt-1 text-sm {{ $errors->has('term') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('term') }}</p>
                            </div>

                            <div class="col-span-12 sm:col-span-12 form-group min-w-0">
                                <label for="view-behavioral-session" class="form-label">Session</label>
                                <x-forms.md-select-native id="view-behavioral-session" name="session" class="form-select w-full min-w-0">
                                    <option value="">Select session</option>
                                    @foreach(range((int)date('Y') - 5, (int)date('Y') + 5) as $y)
                                        @php $opt = $y . '/' . ($y + 1); @endphp
                                        <option value="{{ $opt }}" {{ ($session ?? $settings['session'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </x-forms.md-select-native>
                                <p id="session-error" class="form-error mt-1 text-sm {{ $errors->has('session') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('session') }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2 min-w-0" style="border-top: 1px solid var(--outline-variant); padding-top: 1.25rem;">
                            <a href="{{ route('teacher.behavioral.view') }}" class="btn-secondary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 sm:min-w-[120px]" style="border-radius: 12px;">
                                <i class="fas fa-times text-sm" aria-hidden="true"></i>
                                Clear
                            </a>
                            <button type="submit" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" data-preloader style="border-radius: 12px;">
                                <i class="fas fa-eye text-sm" aria-hidden="true"></i>
                                View behavioural
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            @if(!$hasFilters)
                <div class="flex-1 min-h-0 w-full rounded-3xl overflow-hidden flex flex-col items-center justify-center py-16 md:py-24 px-6" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center" style="border-radius: 16px;">
                        <i class="fas fa-search text-3xl" aria-hidden="true"></i>
                    </div>
                    <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No filters selected</h2>
                    <p class="text-sm text-center max-w-sm" style="color: var(--on-surface-variant);">Choose class, term and session in the form above, then click &quot;View behavioural&quot; to see records.</p>
                </div>
            @else
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
                </div>

                <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    @if($records->isEmpty())
                        <div class="flex flex-col items-center justify-center py-16 px-6">
                            <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5" style="border-radius: 16px;">
                                <i class="fas fa-clipboard-list text-3xl" aria-hidden="true"></i>
                            </div>
                            <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No records for this selection</h2>
                            <p class="text-sm text-center max-w-sm mb-6" style="color: var(--on-surface-variant);">There are no behavioural records for {{ $class }}, {{ $term }}, {{ $session }}. Take behavioural analysis first or choose another filter.</p>
                            <div class="flex justify-center">
                                <a href="{{ route('teacher.behavioral.view') }}" class="btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] rounded-xl font-medium text-sm transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;">
                                    <i class="fas fa-filter text-sm"></i>
                                    Change filters
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 sm:px-6 py-4" style="border-bottom: 1px solid var(--outline-variant); background: var(--surface-container-low);">
                            <p class="text-sm font-medium" style="color: var(--on-surface-variant);">
                                <span id="behavioral-records-count">{{ $records->count() }}</span> record(s)
                            </p>
                        </div>

                        <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0 w-full min-w-0 max-h-[min(70vh,32rem)] lg:max-h-none lg:min-h-[12rem]" style="border-color: var(--outline-variant); -webkit-overflow-scrolling: touch;">
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
                                        $studentForRecord = $studentsByReg->get($record->reg_number ?? '');
                                    @endphp
                                    <li class="behavioral-record-row border-b border-[var(--outline-variant)] last:border-b-0 min-w-0 w-full" style="background: var(--surface-container-lowest);" data-reg="{{ e($record->reg_number) }}" data-name="{{ e($record->name ?? '') }}">
                                        <div class="behavioral-view-mode flex flex-col lg:grid lg:grid-cols-behavioral-records gap-3 lg:gap-x-2 lg:gap-y-2 lg:items-stretch px-4 sm:px-6 py-4 lg:py-3 min-w-0">
                                            <div class="flex items-center gap-3 min-w-0 lg:contents">
                                                <span class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center text-sm font-semibold lg:w-8 lg:h-8 lg:place-self-center" style="background: var(--primary-container); color: var(--on-primary-container);">{{ $index + 1 }}</span>
                                                <img src="{{ $avatarSrc }}" alt="" width="40" height="40" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2 lg:place-self-center" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($avatarInitial) }}&size=80'">
                                                <div class="flex flex-col justify-center min-w-0 flex-1 lg:flex-none lg:min-w-0 lg:py-1 lg:pr-2 overflow-hidden">
                                                    <p class="text-sm font-semibold truncate" style="color: var(--on-surface);" title="{{ e($record->name ?? '') }}">
                                                        {{ e($record->name ?? '—') }}
                                                    </p>
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
                                            </div>
                                        </div>

                                        <div class="behavioral-edit-mode hidden flex-col gap-4 p-4 sm:px-6 pb-5 min-w-0 overflow-hidden" style="background: var(--surface-container-low); border-top: 1px solid var(--outline-variant);">
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
                                                <button type="button" class="behavioral-cancel-edit btn-secondary w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-3 sm:px-5 sm:py-2.5 rounded-xl text-sm font-medium whitespace-nowrap min-h-[2.75rem] sm:min-h-0" style="border-radius: 12px;">Cancel</button>
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
            @endif
        </div>
    </main>

    @if($hasFilters && $records->isNotEmpty())
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
            }
        </style>

        @push('scripts')
            <script>
                (function() {
                    const editUrl = @json(route('teacher.behavioral.edit'));
                    const classVal = @json($class);
                    const termVal = @json($term);
                    const sessionVal = @json($session);
                    const fieldKeys = @json(array_keys($behaviorFields));
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                    function getPayload(row) {
                        const reg = row.dataset.reg || '';
                        const payload = { reg_number: reg, class: classVal, term: termVal, session: sessionVal };
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
                })();
            </script>
        @endpush
    @endif
@endsection
