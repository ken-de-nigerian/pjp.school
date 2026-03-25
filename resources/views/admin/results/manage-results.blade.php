@extends('layouts.app', ['title' => 'Manage results'])

@section('content')
    @php
        $hasSearch = ($param ?? '') !== '';
        $hasResults = !empty($groupedResults);
        $manageResultsHeroDescription = $hasSearch
            ? 'Find a student by name or reg number, optionally filter by class, and group by session, term or show all together.'
            : 'Search by student name or registration number. Optionally filter by class and choose how to group the results.';
    @endphp
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Search results"
                pill="Admin"
                title="Search results"
                :description="$manageResultsHeroDescription"
            >
                <x-slot name="above">
                    <a href="{{ route('admin.upload-results') }}" class="admin-page-hero__back mb-2 sm:mb-0">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Upload results
                    </a>
                </x-slot>
            </x-admin.hero-page>

            <div class="rounded-3xl p-4 sm:p-5 lg:p-6 mb-6 overflow-hidden min-w-0 w-full" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                <form method="GET" action="{{ route('admin.results-by-params') }}" class="space-y-4 sm:space-y-5">
                    <div class="grid grid-cols-12 gap-4 min-w-0">
                        <div class="col-span-12 sm:col-span-6 lg:col-span-4 form-group min-w-0">
                            <label for="search-class" class="form-label">Class</label>
                            <x-forms.md-select-native id="search-class" name="class" class="form-select w-full min-w-0">
                                <option value="">All classes</option>
                                @foreach($classes ?? [] as $c)
                                    @php $cn = is_object($c) ? $c->class_name : $c; @endphp
                                    <option value="{{ e($cn) }}" {{ ($class ?? '') === $cn ? 'selected' : '' }}>{{ e($cn) }}</option>
                                @endforeach
                            </x-forms.md-select-native>
                        </div>

                        <div class="col-span-12 sm:col-span-6 lg:col-span-4 form-group min-w-0">
                            <label for="param" class="form-label">Name or Reg number <span style="color: var(--primary);">*</span></label>
                            <input type="text" id="param" name="param" value="{{ e($param ?? '') }}" class="form-input w-full min-w-0" placeholder="Enter name or reg number" required>
                        </div>

                        <div class="col-span-12 sm:col-span-6 lg:col-span-4 form-group min-w-0">
                            <label for="group_by" class="form-label">Group results by</label>
                            <x-forms.md-select-native id="group_by" name="group_by" class="form-select w-full min-w-0">
                                <option value="session" {{ ($group_by ?? 'session') === 'session' ? 'selected' : '' }}>Session</option>
                                <option value="none" {{ ($group_by ?? '') === 'none' ? 'selected' : '' }}>Single list</option>
                                <option value="term" {{ ($group_by ?? '') === 'term' ? 'selected' : '' }}>Term</option>
                            </x-forms.md-select-native>
                        </div>
                    </div>
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2 min-w-0" style="border-top: 1px solid var(--outline-variant); padding-top: 1.25rem;">
                        <a href="{{ route('admin.results-by-params') }}" class="btn-secondary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 sm:min-w-[120px]" style="border-radius: 12px;">
                            <i class="fas fa-times text-sm" aria-hidden="true"></i>
                            Clear
                        </a>
                        <button type="submit" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" data-preloader style="border-radius: 12px;">
                            <i class="fas fa-search text-sm" aria-hidden="true"></i>
                            Search
                        </button>
                    </div>
                </form>
            </div>

            @if(!$hasSearch)
                <div class="flex-1 min-h-0 w-full rounded-3xl overflow-hidden flex flex-col items-center justify-center py-16 md:py-24 px-6" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                    <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center" style="border-radius: 16px;">
                        <i class="fas fa-search text-3xl" aria-hidden="true"></i>
                    </div>
                    <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No search yet</h2>
                    <p class="text-sm text-center max-w-sm" style="color: var(--on-surface-variant);">Enter a student name or registration number above and click &quot;Search&quot; to view their results. You can filter by class and choose how to group the results.</p>
                </div>
            @elseif(!$hasResults)
                <div class="flex-1 min-h-0 w-full rounded-3xl overflow-hidden flex flex-col items-center justify-center py-16 md:py-24 px-6" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                    <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center" style="border-radius: 16px;">
                        <i class="fas fa-inbox text-3xl" aria-hidden="true"></i>
                    </div>
                    <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No results found</h2>
                    <p class="text-sm text-center max-w-sm" style="color: var(--on-surface-variant);">No results match &quot;{{ e($param) }}&quot;@if(!empty($class)) in class {{ e($class) }}@endif. Try a different name or reg number, or clear the class filter.</p>
                </div>
            @else
                <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                    <div class="flex flex-col border-b" style="border-color: var(--outline-variant); background: var(--surface-container-low);">
                        <div class="px-4 sm:px-6 pt-4 pb-3">
                            <p class="text-[11px] font-semibold uppercase tracking-wider mb-3" style="color: var(--on-surface-variant); letter-spacing: 0.06em;">Search context</p>
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3">
                                <div class="rounded-xl px-3 py-2.5 min-w-0 border" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                    <span class="text-[10px] font-medium uppercase tracking-wide block mb-1" style="color: var(--on-surface-variant);">Search</span>
                                    <span class="text-sm font-semibold leading-snug line-clamp-2 break-words" style="color: var(--on-surface);" title="{{ e($param) }}">{{ e($param) }}</span>
                                </div>

                                <div class="rounded-xl px-3 py-2.5 min-w-0 border" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                    <span class="text-[10px] font-medium uppercase tracking-wide block mb-1" style="color: var(--on-surface-variant);">Class</span>
                                    <span class="text-sm font-semibold leading-snug line-clamp-2 break-words" style="color: var(--on-surface);">{{ ($class ?? '') === '' ? 'All' : e($class) }}</span>
                                </div>

                                <div class="rounded-xl px-3 py-2.5 min-w-0 border" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                    <span class="text-[10px] font-medium uppercase tracking-wide block mb-1" style="color: var(--on-surface-variant);">Group by</span>
                                    <span class="text-sm font-semibold leading-snug" style="color: var(--on-surface);">
                                        @if(($group_by ?? '') === 'session') Session
                                        @elseif(($group_by ?? '') === 'term') Term
                                        @else Single list
                                        @endif
                                    </span>
                                </div>
                                @php
                                    $totalRows = 0;
                                    foreach ($groupedResults as $groupRows) { $totalRows += is_countable($groupRows) ? count($groupRows) : 0; }
                                @endphp
                                <div class="rounded-xl px-3 py-2.5 min-w-0 border col-span-2 lg:col-span-1" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                    <span class="text-[10px] font-medium uppercase tracking-wide block mb-1" style="color: var(--on-surface-variant);">Total rows</span>
                                    <span class="text-sm font-semibold tabular-nums" style="color: var(--on-surface);">{{ $totalRows }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col divide-y overflow-x-auto" style="border-color: var(--outline-variant);">
                        @foreach($groupedResults as $groupLabel => $rows)
                            <div class="flex flex-col min-w-0">
                                <div class="px-4 sm:px-6 py-3 shrink-0" style="background: var(--surface-container); border-bottom: 1px solid var(--outline-variant);">
                                    <h2 class="text-sm font-semibold" style="color: var(--on-surface);">
                                        @if(($group_by ?? '') === 'session')
                                            Session {{ e($groupLabel) }}
                                        @elseif(($group_by ?? '') === 'term')
                                            {{ e($groupLabel) }}
                                        @else
                                            Results
                                        @endif
                                        <span class="font-normal ml-2 text-xs" style="color: var(--on-surface-variant);">({{ is_countable($rows) ? count($rows) : 0 }} row(s))</span>
                                    </h2>
                                </div>
                                <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0 border-x border-b md:border-x md:border-b" style="border-color: var(--outline-variant);">
                                    <ul class="flex flex-col gap-3 md:gap-0 md:divide-y divide-[var(--outline-variant)] p-4 sm:px-6 md:p-0 list-none min-w-0" role="list">
                                        <li class="hidden md:flex items-center gap-3 sm:gap-4 px-5 sm:px-6 py-3" style="background: var(--surface-container); border-color: var(--outline-variant);">
                                            <span class="w-10 flex-shrink-0" aria-hidden="true"></span>
                                            <span class="text-xs font-medium flex-1 min-w-0" style="color: var(--on-surface-variant);">Subject</span>
                                            <span class="text-xs font-medium flex-shrink-0 w-14 sm:w-24 text-right" style="color: var(--on-surface-variant);">Class</span>
                                            <span class="text-xs font-medium flex-shrink-0 w-12 text-center" style="color: var(--on-surface-variant);">CA</span>
                                            <span class="text-xs font-medium flex-shrink-0 w-12 text-center hidden sm:block" style="color: var(--on-surface-variant);">Assign</span>
                                            <span class="text-xs font-medium flex-shrink-0 w-12 text-center" style="color: var(--on-surface-variant);">Exam</span>
                                            <span class="text-xs font-medium flex-shrink-0 w-12 text-center" style="color: var(--on-surface-variant);">Total</span>
                                            <span class="text-xs font-medium flex-shrink-0 w-10 text-center" style="color: var(--on-surface-variant);">Grade</span>
                                            <span class="text-xs font-medium flex-shrink-0 w-20 text-right" style="color: var(--on-surface-variant);">Status</span>
                                        </li>
                                        @foreach($rows as $r)
                                            @php
                                                $initial = $r->name ? mb_substr(trim($r->name), 0, 1) : 'S';
                                                $imagelocation = $r->student?->imagelocation ?? null;
                                                $avatarSrc = $imagelocation
                                                    ? (str_starts_with($imagelocation, 'students/') ? asset('storage/' . $imagelocation) : asset('storage/students/' . $imagelocation))
                                                    : asset('storage/students/default.png');
                                            @endphp
                                            <li class="flex flex-col gap-0 rounded-2xl border p-4 md:rounded-none md:border-0 md:border-b md:border-t-0 md:flex-row md:items-center md:gap-4 md:py-4 md:px-5 lg:px-6 md:min-w-0 md:p-0" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                                <div class="flex items-center gap-3 md:contents">
                                                    <img src="{{ $avatarSrc }}" alt="" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($initial) }}&size=80'">
                                                    <div class="min-w-0 flex-1 md:min-w-0 md:flex-1">
                                                        <span class="text-xs font-medium md:sr-only" style="color: var(--on-surface-variant);">Subject</span>
                                                        <p class="text-sm font-medium truncate" style="color: var(--on-surface);">{{ e($r->subjects) }}</p>
                                                        <p class="text-xs truncate mt-0.5" style="color: var(--on-surface-variant);">
                                                            @if($r->student && Route::has('admin.students.show'))
                                                                <a href="{{ route('admin.students.show', $r->student) }}" class="transition-opacity hover:opacity-80" style="color: var(--primary);">{{ e($r->name) }}</a>
                                                            @else
                                                                {{ e($r->name) }}
                                                            @endif
                                                            · {{ e($r->reg_number) }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="mt-3 pt-3 border-t md:border-t-0 md:mt-0 md:pt-0 flex flex-wrap items-baseline gap-x-4 gap-y-1 md:contents" style="border-color: var(--outline-variant);">
                                                    <span class="w-full text-xs font-medium mb-1 md:sr-only" style="color: var(--on-surface-variant);">Scores</span>
                                                    <span class="text-xs md:flex-shrink-0 md:w-14 lg:w-24 md:flex md:justify-end md:items-center">
                                                        <span class="hidden md:inline-flex md:items-center md:px-2.5 md:py-1 md:rounded-lg md:text-xs md:font-medium md:truncate md:max-w-full" style="background: var(--surface-container-high); color: var(--on-surface-variant);" title="{{ e($r->class ?? $r->class_arm ?? '') }}">{{ e($r->class ?? $r->class_arm ?? '') }}</span>
                                                    </span>
                                                    <span class="text-xs md:flex-shrink-0 md:w-12 md:text-center"><span class="md:sr-only" style="color: var(--on-surface-variant);">CA </span><span class="font-medium tabular-nums" style="color: var(--on-surface);">{{ $r->ca }}</span></span>
                                                    <span class="text-xs hidden sm:inline md:flex-shrink-0 md:w-12 md:text-center md:block"><span class="md:sr-only" style="color: var(--on-surface-variant);">Assign </span><span class="font-medium tabular-nums" style="color: var(--on-surface);">{{ $r->assignment }}</span></span>
                                                    <span class="text-xs md:flex-shrink-0 md:w-12 md:text-center"><span class="md:sr-only" style="color: var(--on-surface-variant);">Exam </span><span class="font-medium tabular-nums" style="color: var(--on-surface);">{{ $r->exam }}</span></span>
                                                    <span class="text-xs md:flex-shrink-0 md:w-12 md:text-center"><span class="md:sr-only" style="color: var(--on-surface-variant);">Total </span><span class="font-semibold tabular-nums" style="color: var(--on-surface);">{{ $r->total }}</span></span>
                                                    <span class="text-xs md:flex-shrink-0 md:w-10 md:text-center"><span class="md:sr-only" style="color: var(--on-surface-variant);">Grade </span><span class="font-semibold tabular-nums" style="color: var(--on-surface);">{{ $r->grade_letter ?? '—' }}</span></span>
                                                </div>
                                                <div class="mt-2 pt-2 sm:mt-3 sm:pt-3 border-t md:border-t-0 md:mt-0 md:pt-0 w-full flex flex-row items-center justify-between gap-3 md:contents" style="border-color: var(--outline-variant);">
                                                    <span class="sr-only" style="color: var(--on-surface-variant);">Status</span>
                                                    @if((int) $r->status === 1)
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold shrink-0 md:flex-shrink-0 md:w-24 md:flex md:justify-end border border-current/20" style="background: var(--success-container); color: var(--on-success-container);"><i class="fas fa-check text-[10px]" aria-hidden="true"></i> Approved</span>
                                                    @elseif((int) $r->status === 3)
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold shrink-0 md:flex-shrink-0 md:w-24 md:flex md:justify-end border border-current/20" style="background: var(--error-container); color: var(--on-error-container);"><i class="fas fa-times text-[10px]" aria-hidden="true"></i> Rejected</span>
                                                    @else
                                                        <span class="badge-warning inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold shrink-0 md:flex-shrink-0 md:w-24 md:flex md:justify-end"><i class="fas fa-clock text-[10px] opacity-80" aria-hidden="true"></i> Pending</span>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </main>
@endsection
