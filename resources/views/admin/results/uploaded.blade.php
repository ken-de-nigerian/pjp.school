@extends('layouts.app', ['title' => 'Uploaded results'])

@section('content')
    @php
        $hasFilters = $class && $term && $session && $subjects;
        $uploadedHeroDescription = $hasFilters
            ? 'Review, approve, or edit scores for this class and subject.'
            : 'Choose class, term, session and subject to view uploaded scores.';
    @endphp
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Uploaded results"
                pill="Admin"
                title="Uploaded results"
                :description="$uploadedHeroDescription"
            >
                <x-slot name="above">
                    <a href="{{ route('admin.upload-results') }}" class="admin-page-hero__back mb-2 sm:mb-0">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Upload results
                    </a>
                </x-slot>
                @if($hasFilters)
                    <x-slot name="actions">
                        <a href="{{ route('admin.results.uploaded') }}" class="admin-dashboard-hero__btn w-full lg:w-auto justify-center min-h-[44px] sm:min-h-0">
                            <i class="fas fa-filter text-xs" aria-hidden="true"></i>
                            <span>Change filters</span>
                        </a>
                    </x-slot>
                @endif
            </x-admin.hero-page>

            @if(!$hasFilters)
            <div class="rounded-3xl p-4 sm:p-5 lg:p-6 mb-6 overflow-hidden min-w-0 w-full" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                <form method="GET" action="{{ route('admin.results.uploaded') }}" class="space-y-4 sm:space-y-5">
                    <input hidden name="term" value="{{ $term }}">
                    <input hidden name="session" value="{{ $session }}">

                    <div class="grid grid-cols-12 gap-4 min-w-0">
                        <div class="col-span-12 sm:col-span-6 form-group min-w-0">
                            <label for="upload-class" class="form-label">Class <span style="color: var(--primary);">*</span></label>
                            <x-forms.md-select-native id="upload-class" name="class" class="form-select w-full min-w-0" required>
                                <option value="">Select class</option>
                                @foreach($getClasses as $c)
                                    @php $cn = is_object($c) ? $c->class_name : $c; @endphp
                                    <option value="{{ e($cn) }}" {{ ($class ?? '') === $cn ? 'selected' : '' }}>{{ e($cn) }}</option>
                                @endforeach
                            </x-forms.md-select-native>
                        </div>

                        <div class="col-span-12 sm:col-span-6 form-group min-w-0">
                            <label for="upload-subjects" class="form-label">Subject <span style="color: var(--primary);">*</span></label>
                            <x-forms.md-select-native id="upload-subjects" name="subjects" class="form-select w-full min-w-0" required>
                                <option value="">Select subject</option>
                                @foreach($getSubjects as $s)
                                    <option value="{{ e($s->subject_name) }}" data-grade="{{ e($s->grade) }}" {{ ($subjects ?? '') === $s->subject_name ? 'selected' : '' }}>{{ e($s->subject_name) }}</option>
                                @endforeach
                            </x-forms.md-select-native>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2 min-w-0" style="border-top: 1px solid var(--outline-variant); padding-top: 1.25rem;">
                        <a href="{{ route('admin.results.uploaded') }}" class="btn-secondary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 sm:min-w-[120px]" style="border-radius: 12px;">
                            <i class="fas fa-times text-sm" aria-hidden="true"></i>
                            Clear
                        </a>
                        <button type="submit" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" data-preloader style="border-radius: 12px;">
                            Filter
                        </button>
                    </div>
                </form>
            </div>
            @endif

            @if(!$hasFilters)
                <div class="flex-1 min-h-0 w-full rounded-3xl overflow-hidden flex flex-col items-center justify-center py-16 md:py-24 px-6" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                    <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center" style="border-radius: 16px;">
                        <i class="fas fa-search text-3xl" aria-hidden="true"></i>
                    </div>
                    <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No filters selected</h2>
                    <p class="text-sm text-center max-w-sm" style="color: var(--on-surface-variant);">Choose class and subject in the form above, then click &quot;Filter&quot; to see uploaded scores for that combination.</p>
                </div>
            @else
                <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
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
                    </div>

                    @if($results->isEmpty())
                        <div class="flex flex-col items-center justify-center py-16 md:py-24 px-6">
                            <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center" style="border-radius: 16px;">
                                <i class="fas fa-inbox text-3xl" aria-hidden="true"></i>
                            </div>
                            <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No uploaded results</h2>
                            <p class="text-sm text-center max-w-sm" style="color: var(--on-surface-variant);">No rows for this combination. Upload via the result sheet or teacher workflow, or change the filters above.</p>
                        </div>
                    @else
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-4 sm:px-6 py-4" style="border-bottom: 1px solid var(--outline-variant); background: var(--surface-container-low);">
                            <p class="text-sm font-medium" style="color: var(--on-surface-variant);">
                                <span>{{ $results->count() }}</span> result(s) · Select rows to approve or reject below.
                            </p>
                            <button type="button" id="uploaded-delete-all-btn" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-medium transition-opacity hover:opacity-90 w-fit" style="background: var(--surface-container-high); color: var(--on-surface-variant); border-radius: 12px;">
                                <i class="fas fa-trash-alt" aria-hidden="true"></i> Delete all results
                            </button>
                        </div>
                        <div id="uploaded-results-toolbar" class="hidden flex-wrap items-center gap-2 px-5 sm:px-6 py-3" style="background: var(--surface-container); border-bottom: 1px solid var(--outline-variant);">
                            <span class="text-xs font-medium mr-2" style="color: var(--on-surface-variant);"><span id="uploaded-selected-count">0</span> selected</span>

                            <button type="button" id="uploaded-approve-btn" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-medium transition-opacity hover:opacity-90" style="background: var(--success-container); color: var(--on-success-container); border-radius: 12px;">
                                <i class="fas fa-check" aria-hidden="true"></i> Approve
                            </button>

                            <button type="button" id="uploaded-reject-btn" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-medium transition-opacity hover:opacity-90" style="background: var(--error-container); color: var(--on-error-container); border-radius: 12px;">
                                <i class="fas fa-times" aria-hidden="true"></i> Reject
                            </button>
                        </div>

                        <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0 border-x border-b md:border-x md:border-b" style="border-color: var(--outline-variant);">
                            <ul class="flex flex-col gap-3 md:gap-0 md:divide-y divide-[var(--outline-variant)] p-4 sm:px-6 md:p-0 list-none min-w-0" role="list">
                                <li class="hidden md:flex items-center gap-3 sm:gap-4 px-5 sm:px-6 py-3" style="background: var(--surface-container); border-color: var(--outline-variant);">
                                    <label class="flex items-center flex-shrink-0 cursor-pointer">
                                        <input type="checkbox" id="uploaded-select-all" class="uploaded-row-cb form-checkbox-input w-4 h-4 rounded border-2 cursor-pointer focus:ring-2 focus:ring-offset-0" style="border-color: var(--outline); accent-color: var(--primary);" aria-label="Select all on page">
                                    </label>
                                    <span class="w-10 flex-shrink-0" aria-hidden="true"></span>
                                    <span class="text-xs font-medium flex-1 min-w-0" style="color: var(--on-surface-variant);">Name</span>
                                    <span class="text-xs font-medium flex-shrink-0 w-14 sm:w-24 text-right" style="color: var(--on-surface-variant);">Reg</span>
                                    <span class="text-xs font-medium flex-shrink-0 w-12 text-center" style="color: var(--on-surface-variant);">CA</span>
                                    <span class="text-xs font-medium flex-shrink-0 w-12 text-center hidden sm:block" style="color: var(--on-surface-variant);">Assign</span>
                                    <span class="text-xs font-medium flex-shrink-0 w-12 text-center" style="color: var(--on-surface-variant);">Exam</span>
                                    <span class="text-xs font-medium flex-shrink-0 w-12 text-center" style="color: var(--on-surface-variant);">Total</span>
                                    <span class="text-xs font-medium flex-shrink-0 w-10 text-center" style="color: var(--on-surface-variant);">Grade</span>
                                    <span class="text-xs font-medium flex-shrink-0 w-20 text-right" style="color: var(--on-surface-variant);">Status</span>
                                    <span class="text-xs font-medium flex-shrink-0 w-10 sm:w-24 text-right" style="color: var(--on-surface-variant);">Actions</span>
                                </li>
                                @foreach($results as $r)
                                    @php
                                        $initial = $r->name ? mb_substr(trim($r->name), 0, 1) : 'S';
                                        $imagelocation = $r->student?->imagelocation ?? null;
                                        $avatarSrc = $imagelocation
                                            ? (str_starts_with($imagelocation, 'students/') ? asset('storage/' . $imagelocation) : asset('storage/students/' . $imagelocation))
                                            : asset('storage/students/default.png');
                                    @endphp
                                    <li class="flex flex-col gap-0 rounded-2xl border p-4 md:rounded-none md:border-0 md:border-b md:border-t-0 md:flex-row md:items-center md:gap-4 md:py-4 md:px-5 lg:px-6 md:min-w-0 md:p-0 transition-[background-color] duration-200" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);" data-result-id="{{ $r->id }}" data-student-id="{{ $r->studentId ?? '' }}">
                                        <div class="flex items-center gap-3 md:contents">
                                            <label class="flex items-center flex-shrink-0 cursor-pointer">
                                                <input type="checkbox" name="selectedRows[]" value="{{ $r->id }}" class="uploaded-row-cb form-checkbox-input w-4 h-4 rounded border-2 cursor-pointer focus:ring-2 focus:ring-offset-0" style="border-color: var(--outline); accent-color: var(--primary);" aria-label="Select {{ e($r->name) }}">
                                            </label>
                                            <img src="{{ $avatarSrc }}" alt="" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($initial) }}&size=80'">
                                            <div class="min-w-0 flex-1 md:min-w-0 md:flex-1">
                                                <span class="text-xs font-medium md:sr-only" style="color: var(--on-surface-variant);">Name</span>
                                                <p class="text-sm font-medium truncate" style="color: var(--on-surface);">
                                                    @if(!empty($r->studentId) && Route::has('admin.students.show'))
                                                        <a href="{{ route('admin.students.show', $r->studentId) }}" class="transition-opacity hover:opacity-80" style="color: var(--primary);">{{ e($r->name) }}</a>
                                                    @else
                                                        {{ e($r->name) }}
                                                    @endif
                                                </p>
                                                <p class="text-xs truncate mt-0.5" style="color: var(--on-surface-variant);">{{ e($r->reg_number) }}</p>
                                            </div>
                                        </div>

                                        <div class="mt-3 pt-3 border-t md:border-t-0 md:mt-0 md:pt-0 flex flex-wrap items-baseline gap-x-4 gap-y-1 md:contents" style="border-color: var(--outline-variant);">
                                            <span class="w-full text-xs font-medium mb-1 md:sr-only" style="color: var(--on-surface-variant);">Scores</span>
                                            <span class="text-xs md:flex-shrink-0 md:w-14 lg:w-24 md:flex md:justify-end md:items-center">
                                                <span class="md:hidden" style="color: var(--on-surface-variant);">Reg <span class="font-medium tabular-nums" style="color: var(--on-surface);">{{ e($r->reg_number) }}</span></span>
                                                <span class="hidden md:inline-flex md:items-center md:px-2.5 md:py-1 md:rounded-lg md:text-xs md:font-medium md:truncate md:max-w-full" style="background: var(--surface-container-high); color: var(--on-surface-variant);" title="{{ e($r->reg_number) }}">{{ e($r->reg_number) }}</span>
                                            </span>
                                            <span class="text-xs md:flex-shrink-0 md:w-12 md:text-center"><span class="md:sr-only" style="color: var(--on-surface-variant);">CA </span><span class="font-medium tabular-nums" style="color: var(--on-surface);">{{ $r->ca }}</span></span>
                                            <span class="text-xs hidden sm:inline md:flex-shrink-0 md:w-12 md:text-center md:block"><span class="md:sr-only" style="color: var(--on-surface-variant);">Assign </span><span class="font-medium tabular-nums" style="color: var(--on-surface);">{{ $r->assignment }}</span></span>
                                            <span class="text-xs md:flex-shrink-0 md:w-12 md:text-center"><span class="md:sr-only" style="color: var(--on-surface-variant);">Exam </span><span class="font-medium tabular-nums" style="color: var(--on-surface);">{{ $r->exam }}</span></span>
                                            <span class="text-xs md:flex-shrink-0 md:w-12 md:text-center"><span class="md:sr-only" style="color: var(--on-surface-variant);">Total </span><span class="font-semibold tabular-nums" style="color: var(--on-surface);">{{ $r->total }}</span></span>
                                            <span class="text-xs md:flex-shrink-0 md:w-10 md:text-center"><span class="md:sr-only" style="color: var(--on-surface-variant);">Grade </span><span class="font-semibold tabular-nums" style="color: var(--on-surface);">{{ $r->grade_letter }}</span></span>
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
                                            <button type="button" class="uploaded-edit-btn order-last shrink-0 inline-flex items-center justify-center gap-1 px-2.5 py-1 sm:py-1.5 rounded-lg text-xs font-medium transition-opacity hover:opacity-90 md:flex-shrink-0 md:justify-end md:order-none w-9 h-9 sm:w-auto sm:h-auto sm:min-w-0" style="background: var(--primary-container); color: var(--on-primary-container);" data-student-id="{{ $r->studentId ?? '' }}" data-name="{{ e($r->name) }}" data-reg="{{ e($r->reg_number) }}" data-ca="{{ $r->ca }}" data-assignment="{{ $r->assignment }}" data-exam="{{ $r->exam }}" title="Edit result" aria-label="Edit result">
                                                <i class="fas fa-pen text-sm sm:text-xs" aria-hidden="true"></i>
                                                <span class="hidden sm:inline">Edit</span>
                                            </button>
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

    <div id="uploaded-results-confirm-modal" class="fixed inset-0 z-50 hidden overflow-y-auto overscroll-contain" aria-modal="true" role="dialog" aria-labelledby="uploaded-confirm-modal-title">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="uploaded-results-confirm-modal" aria-hidden="true"></div>
        <div class="relative min-h-full flex items-center justify-center p-4 py-6 sm:p-6">
            <div class="relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-xl py-5 px-4 sm:py-6 sm:px-6 shadow-xl border my-auto" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                <h3 id="uploaded-confirm-modal-title" class="text-lg font-semibold mb-2" style="color: var(--on-surface);"></h3>
                <p id="uploaded-confirm-modal-message" class="text-sm mb-6" style="color: var(--on-surface-variant);"></p>
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-2">
                    <button type="button" class="btn-secondary px-4 py-2.5 rounded-xl text-sm w-full sm:w-auto" data-close="uploaded-results-confirm-modal">Cancel</button>
                    <button type="button" id="uploaded-confirm-modal-confirm" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium w-full sm:w-auto transition-opacity hover:opacity-95"></button>
                </div>
            </div>
        </div>
    </div>

    <div id="edit-result-modal" class="fixed inset-0 z-50 hidden overflow-y-auto overscroll-contain" aria-modal="true" role="dialog" aria-labelledby="edit-result-modal-title">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="edit-result-modal" aria-hidden="true"></div>
        <div class="relative min-h-full flex items-center justify-center p-4 py-6 sm:p-6">
            <div class="relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-xl py-5 px-4 sm:py-6 sm:px-6 shadow-xl border my-auto" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                <h3 id="edit-result-modal-title" class="text-lg font-semibold mb-2" style="color: var(--on-surface);">Edit result</h3>
                <p id="edit-result-modal-student" class="text-sm mb-3" style="color: var(--on-surface-variant);"></p>
                <div class="rounded-xl px-3 py-2.5 mb-4 flex gap-2.5" style="background: var(--surface-container-high); border: 1px solid var(--outline-variant);">
                    <i class="fas fa-info-circle flex-shrink-0 mt-0.5 text-sm" style="color: var(--on-surface-variant);" aria-hidden="true"></i>
                    <p class="text-xs leading-relaxed m-0" style="color: var(--on-surface-variant);">Editing a student's result is a serious matter that can impact their academic record. Please verify all changes before saving to avoid any mistakes or unintended consequences.</p>
                </div>
                <form id="edit-result-form" class="space-y-4">
                    @csrf
                    <input type="hidden" name="studentId" id="edit-result-studentId">
                    <input type="hidden" name="class" value="{{ e($class ?? '') }}">
                    <input type="hidden" name="term" value="{{ e($term ?? '') }}">
                    <input type="hidden" name="session" value="{{ e($session ?? '') }}">
                    <input type="hidden" name="subjects" value="{{ e($subjects ?? '') }}">
                    <input type="hidden" name="reg_number" id="edit-result-reg">
                    <div class="form-group">
                        <label for="edit-result-ca" class="form-label">CA (max 15)</label>
                        <input type="number" id="edit-result-ca" name="ca" min="0" max="15" step="0.5" inputmode="decimal" class="form-input w-full results-score-input edit-result-score-input" placeholder="0 – 15">
                    </div>
                    <div class="form-group">
                        <label for="edit-result-assignment" class="form-label">Assign (max 25)</label>
                        <input type="number" id="edit-result-assignment" name="assignment" min="0" max="25" step="0.5" inputmode="decimal" class="form-input w-full results-score-input edit-result-score-input" placeholder="0 – 25">
                    </div>
                    <div class="form-group">
                        <label for="edit-result-exam" class="form-label">Exam (max 60)</label>
                        <input type="number" id="edit-result-exam" name="exam" min="0" max="60" step="0.5" inputmode="decimal" class="form-input w-full results-score-input edit-result-score-input" placeholder="0 – 60">
                    </div>
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 pt-2">
                        <button type="button" class="btn-secondary px-4 py-2.5 rounded-xl text-sm w-full sm:w-auto" data-close="edit-result-modal">Cancel</button>
                        <button type="submit" id="edit-result-submit" class="btn-primary inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium w-full sm:w-auto">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const classSelect = document.getElementById('upload-class');
                const subjectSelect = document.getElementById('upload-subjects');
                if (!classSelect || !subjectSelect) return;
                function gradeForClass(name) {
                    if (!name) return null;
                    const p = name.substring(0, 3).toUpperCase();
                    if (p === 'JSS') return 'Junior';
                    if (p === 'SSS') return 'Senior';
                    return null;
                }
                function filterSubjectOptions() {
                    const g = gradeForClass(classSelect.value);
                    const opts = subjectSelect.querySelectorAll('option[data-grade]');
                    const sel = subjectSelect.value;
                    let selectedStillVisible = false;
                    opts.forEach(function (opt) {
                        const optGrade = opt.getAttribute('data-grade');
                        const show = !g || optGrade === g;
                        opt.style.display = show ? '' : 'none';
                        opt.disabled = !show;
                        if (opt.value === sel && show) selectedStillVisible = true;
                    });
                    if (sel && !selectedStillVisible) subjectSelect.value = '';
                }
                classSelect.addEventListener('change', filterSubjectOptions);
                filterSubjectOptions();
            })();
        </script>
    @endpush

    @if($hasFilters && !$results->isEmpty())
        @push('scripts')
            <script>
                (function () {
                    const toolbar = document.getElementById('uploaded-results-toolbar');
                    const selectAll = document.getElementById('uploaded-select-all');
                    const checkboxes = document.querySelectorAll('.uploaded-row-cb[name="selectedRows[]"]');
                    const countEl = document.getElementById('uploaded-selected-count');

                    function getSelectedIds() {
                        return [].slice.call(checkboxes).filter(function (c) { return c.checked; }).map(function (c) { return parseInt(c.value, 10); });
                    }
                    function updateToolbar() {
                        const ids = getSelectedIds();
                        const n = ids.length;
                        if (countEl) countEl.textContent = n;
                        if (toolbar) toolbar.classList.toggle('hidden', n === 0);
                        if (selectAll) selectAll.checked = n > 0 && n === checkboxes.length;
                        if (selectAll) selectAll.indeterminate = n > 0 && n < checkboxes.length;
                    }
                    checkboxes.forEach(function (cb) { cb.addEventListener('change', updateToolbar); });
                    if (selectAll) selectAll.addEventListener('change', function () { checkboxes.forEach(function (c) { c.checked = selectAll.checked; }); updateToolbar(); });
                    updateToolbar();

                    const RELOAD_DELAY_MS = 2800;

                    const confirmModal = document.getElementById('uploaded-results-confirm-modal');
                    const confirmTitle = document.getElementById('uploaded-confirm-modal-title');
                    const confirmMessage = document.getElementById('uploaded-confirm-modal-message');
                    const confirmBtn = document.getElementById('uploaded-confirm-modal-confirm');
                    let pendingConfirmAction = null;

                    function openConfirmModal(title, message, confirmText, variant, action) {
                        pendingConfirmAction = action;
                        confirmTitle.textContent = title;
                        confirmMessage.textContent = message;
                        confirmBtn.textContent = confirmText;
                        confirmBtn.className = 'inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium w-full sm:w-auto transition-opacity hover:opacity-95';
                        if (variant === 'success') confirmBtn.style.cssText = 'background: var(--success-container); color: var(--on-success-container);';
                        else if (variant === 'error') confirmBtn.style.cssText = 'background: var(--error-container); color: var(--on-error-container);';
                        else confirmBtn.style.cssText = 'background: var(--surface-container-high); color: var(--on-surface-variant);';
                        confirmModal.classList.remove('hidden');
                    }
                    function closeConfirmModal() {
                        confirmModal.classList.add('hidden');
                        pendingConfirmAction = null;
                    }
                    document.querySelectorAll('[data-close="uploaded-results-confirm-modal"]').forEach(function (el) {
                        el.addEventListener('click', closeConfirmModal);
                    });
                    confirmBtn.addEventListener('click', function () {
                        if (!pendingConfirmAction) return;
                        const action = pendingConfirmAction;
                        pendingConfirmAction = null;
                        if (typeof setButtonLoading === 'function') setButtonLoading(confirmBtn, true);
                        const body = action.deleteAll ? { class: action.class, term: action.term, session: action.session, subjects: action.subjects } : { selectedRows: action.ids };
                        fetch(action.url, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' },
                            body: JSON.stringify(body)
                        }).then(function (r) { return r.json(); }).then(function (d) {
                            if (typeof setButtonLoading === 'function') setButtonLoading(confirmBtn, false);
                            if (d.status === 'success') {
                                if (typeof flashSuccess === 'function') flashSuccess(d.message);
                                closeConfirmModal();
                                setTimeout(function () { window.location.reload(); }, RELOAD_DELAY_MS);
                            } else {
                                if (typeof flashError === 'function') flashError(d.message || 'Action failed.');
                            }
                        }).catch(function () {
                            if (typeof setButtonLoading === 'function') setButtonLoading(confirmBtn, false);
                            if (typeof flashError === 'function') flashError('Request failed.');
                        });
                    });

                    document.getElementById('uploaded-approve-btn').addEventListener('click', function () {
                        const ids = getSelectedIds();
                        if (!ids.length) { if (typeof flashError === 'function') flashError('Select at least one result.'); return; }
                        const n = ids.length;
                        openConfirmModal('Approve results', 'Approve the selected ' + n + ' result(s)? They will be marked as approved.', 'Approve', 'success', { url: @json(route('admin.results.approve')), ids: ids });
                    });
                    document.getElementById('uploaded-reject-btn').addEventListener('click', function () {
                        const ids = getSelectedIds();
                        if (!ids.length) { if (typeof flashError === 'function') flashError('Select at least one result.'); return; }
                        const n = ids.length;
                        openConfirmModal('Reject results', 'Reject the selected ' + n + ' result(s)? They will be marked as rejected.', 'Reject', 'error', { url: @json(route('admin.results.reject')), ids: ids });
                    });
                    document.getElementById('uploaded-delete-all-btn').addEventListener('click', function () {
                        const n = {{ $results->count() }};
                        const msg = 'Delete all ' + n + ' result(s) for {{ e($class) }}, {{ e($subjects) }} ({{ e($term) }}, {{ e($session) }})? This cannot be undone.';
                        openConfirmModal('Delete all results', msg, 'Delete all', 'error', { url: @json(route('admin.results.delete')), deleteAll: true, class: @json($class), term: @json($term), session: @json($session), subjects: @json($subjects) });
                    });

                    const modal = document.getElementById('edit-result-modal');
                    const form = document.getElementById('edit-result-form');
                    document.querySelectorAll('.uploaded-edit-btn').forEach(function (btn) {
                        btn.addEventListener('click', function () {
                            document.getElementById('edit-result-studentId').value = btn.getAttribute('data-student-id') || btn.closest('li').getAttribute('data-student-id') || '';
                            document.getElementById('edit-result-reg').value = btn.getAttribute('data-reg') || '';
                            document.getElementById('edit-result-modal-student').textContent = (btn.getAttribute('data-name') || '') + ' · ' + (btn.getAttribute('data-reg') || '');
                            document.getElementById('edit-result-ca').value = btn.getAttribute('data-ca') || '';
                            document.getElementById('edit-result-assignment').value = btn.getAttribute('data-assignment') || '';
                            document.getElementById('edit-result-exam').value = btn.getAttribute('data-exam') || '';
                            modal.classList.remove('hidden');
                        });
                    });
                    document.querySelectorAll('[data-close="edit-result-modal"]').forEach(function (el) {
                        el.addEventListener('click', function () { modal.classList.add('hidden'); });
                    });
                    function clearEditResultInputErrors() {
                        form.querySelectorAll('.edit-result-score-input').forEach(function (el) { el.classList.remove('results-score-input-empty'); });
                    }
                    form.querySelectorAll('.edit-result-score-input').forEach(function (el) {
                        el.addEventListener('input', clearEditResultInputErrors);
                        el.addEventListener('change', clearEditResultInputErrors);
                    });
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        clearEditResultInputErrors();
                        const caEl = document.getElementById('edit-result-ca');
                        const asgEl = document.getElementById('edit-result-assignment');
                        const examEl = document.getElementById('edit-result-exam');
                        const caV = parseFloat(caEl.value);
                        const asgV = parseFloat(asgEl.value);
                        const examV = parseFloat(examEl.value);
                        const result = typeof validateResultScores === 'function' ? validateResultScores(caEl.value, asgEl.value, examEl.value) : {valid: true};
                        if (!result.valid) {
                            if (typeof markEditResultScoreInputErrors === 'function') markEditResultScoreInputErrors(caEl, asgEl, examEl, caV, asgV, examV);
                            if (typeof flashError === 'function') flashError(result.message);
                            return;
                        }
                        const submitBtn = document.getElementById('edit-result-submit');
                        if (typeof setButtonLoading === 'function') setButtonLoading(submitBtn, true);
                        const fd = new FormData(form);
                        const body = {
                            studentId: fd.get('studentId'),
                            class: fd.get('class'),
                            term: fd.get('term'),
                            session: fd.get('session'),
                            subjects: fd.get('subjects'),
                            reg_number: fd.get('reg_number'),
                            ca: caV,
                            assignment: asgV,
                            exam: examV
                        };
                        fetch(@json(route('admin.results.edit')), {
                            method: 'PUT',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' },
                            body: JSON.stringify(body)
                        }).then(function (r) { return r.json(); }).then(function (d) {
                            if (typeof setButtonLoading === 'function') setButtonLoading(submitBtn, false);
                            if (d.status === 'success') {
                                if (typeof flashSuccess === 'function') flashSuccess(d.message);
                                modal.classList.add('hidden');
                                setTimeout(function () { window.location.reload(); }, RELOAD_DELAY_MS);
                            } else {
                                if (typeof flashError === 'function') flashError(d.message || 'Update failed.');
                            }
                        }).catch(function () { if (typeof setButtonLoading === 'function') setButtonLoading(submitBtn, false); if (typeof flashError === 'function') flashError('Request failed.'); });
                    });
                })();
            </script>
        @endpush
    @endif
@endsection
