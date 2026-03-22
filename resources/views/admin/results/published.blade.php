@extends('layouts.app')

@section('content')
    @php
        $hasFilters = ($class ?? '') !== '' && ($term ?? '') !== '' && ($session ?? '') !== '';
        $publishedHeroDescription = $hasFilters
            ? 'View published positions, subject scores, and set results as live or not live.'
            : 'Choose class, term and session to view published result positions.';
    @endphp
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Published results"
                pill="Admin"
                title="Published results"
                :description="$publishedHeroDescription"
            >
                <x-slot name="above">
                    <a href="{{ route('admin.publish-results') }}" class="admin-page-hero__back mb-2 sm:mb-0">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Publish results
                    </a>
                </x-slot>
                @if($hasFilters)
                    <x-slot name="actions">
                        <a href="{{ route('admin.results.published') }}" class="admin-dashboard-hero__btn w-full lg:w-auto justify-center min-h-[44px] sm:min-h-0">
                            <i class="fas fa-filter text-xs" aria-hidden="true"></i>
                            <span>Change filters</span>
                        </a>
                    </x-slot>
                @endif
            </x-admin.hero-page>

            @if(!$hasFilters)
                <div class="rounded-3xl p-4 sm:p-5 lg:p-6 mb-6 overflow-hidden min-w-0 w-full" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                    <form method="GET" action="{{ route('admin.results.published') }}" class="space-y-4 sm:space-y-5">
                        <div class="grid grid-cols-12 gap-4 min-w-0">
                            <div class="col-span-12 sm:col-span-6 form-group min-w-0">
                                <label for="published-class" class="form-label">Class <span style="color: var(--primary);">*</span></label>
                                <select id="published-class" name="class" class="form-select w-full min-w-0" required>
                                    <option value="">Select class</option>
                                    <option value="JSS 1">JSS 1</option>
                                    <option value="JSS 2">JSS 2</option>
                                    <option value="JSS 3">JSS 3</option>
                                    <option value="SSS 1">SSS 1</option>
                                    <option value="SSS 2">SSS 2</option>
                                    <option value="SSS 3">SSS 3</option>
                                </select>
                            </div>

                            <div class="col-span-12 sm:col-span-6 form-group min-w-0">
                                <label for="published-term" class="form-label">Term <span style="color: var(--primary);">*</span></label>
                                <select id="published-term" name="term" class="form-select w-full min-w-0" required>
                                    <option value="">Select term</option>
                                    <option value="First Term" {{ ($term ?? '') === 'First Term' ? 'selected' : '' }}>First Term</option>
                                    <option value="Second Term" {{ ($term ?? '') === 'Second Term' ? 'selected' : '' }}>Second Term</option>
                                    <option value="Third Term" {{ ($term ?? '') === 'Third Term' ? 'selected' : '' }}>Third Term</option>
                                </select>
                            </div>

                            <div class="col-span-12 sm:col-span-12 form-group min-w-0">
                                <label for="published-session" class="form-label">Session <span style="color: var(--primary);">*</span></label>
                                <select id="published-session" name="session" class="form-select w-full min-w-0" required>
                                    <option value="">Select session</option>
                                    @foreach(range((int)date('Y') - 5, (int)date('Y') + 5) as $y)
                                        @php $opt = $y . '/' . ($y + 1); @endphp
                                        <option value="{{ $opt }}" {{ ($session ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2 min-w-0" style="border-top: 1px solid var(--outline-variant); padding-top: 1.25rem;">
                            <a href="{{ route('admin.results.published') }}" class="btn-secondary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 sm:min-w-[120px]" style="border-radius: 12px;">
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
                <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden flex flex-col items-center justify-center py-16 md:py-24 px-6" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                    <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center" style="border-radius: 16px;">
                        <i class="fas fa-search text-3xl" aria-hidden="true"></i>
                    </div>
                    <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No filters selected</h2>
                    <p class="text-sm text-center max-w-sm" style="color: var(--on-surface-variant);">Choose class, term and session in the form above, then click &quot;Filter&quot; to see published positions.</p>
                </div>
            @else
                <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                    <div class="flex flex-col border-b" style="border-color: var(--outline-variant); background: var(--surface-container-low);">
                        <div class="px-4 sm:px-6 pt-4 pb-3">
                            <p class="text-[11px] font-semibold uppercase tracking-wider mb-3" style="color: var(--on-surface-variant); letter-spacing: 0.06em;">Result sheet context</p>
                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3">
                                <div class="rounded-xl px-3 py-2.5 min-w-0 border" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                    <span class="text-[10px] font-medium uppercase tracking-wide block mb-1" style="color: var(--on-surface-variant);">Class</span>
                                    <span class="text-sm font-semibold leading-snug line-clamp-2 break-words" style="color: var(--on-surface);" title="{{ e($class) }}">{{ e($class) }}</span>
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

                    @if($positions->isEmpty())
                        <div class="flex flex-col items-center justify-center py-16 md:py-24 px-6">
                            <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center" style="border-radius: 16px;">
                                <i class="fas fa-inbox text-3xl" aria-hidden="true"></i>
                            </div>
                            <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No published results</h2>
                            <p class="text-sm text-center max-w-sm" style="color: var(--on-surface-variant);">No published results for this class, term and session. Publish results from the Publish results page.</p>
                        </div>
                    @else
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-4 sm:px-6 py-4" style="border-bottom: 1px solid var(--outline-variant); background: var(--surface-container-low);">
                            <p class="text-sm font-medium" style="color: var(--on-surface-variant);">
                                <span>{{ $positions->count() }}</span> student(s) · Select rows to mark live or not live below.
                            </p>
                            <button type="button" id="published-delete-btn" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-medium transition-opacity hover:opacity-90 w-fit" style="background: var(--surface-container-high); color: var(--on-surface-variant); border-radius: 12px;">
                                <i class="fas fa-trash-alt" aria-hidden="true"></i> Delete published results
                            </button>
                        </div>

                        <div id="published-results-toolbar" class="hidden flex flex-wrap items-center gap-2 px-5 sm:px-6 py-3" style="background: var(--surface-container); border-bottom: 1px solid var(--outline-variant);">
                            <span class="text-xs font-medium mr-2" style="color: var(--on-surface-variant);"><span id="published-selected-count">0</span> selected</span>
                            <button type="button" id="published-mark-live-btn" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-medium transition-opacity hover:opacity-90" style="background: var(--success-container); color: var(--on-success-container); border-radius: 12px;">
                                <i class="fas fa-broadcast-tower" aria-hidden="true"></i> Mark live
                            </button>
                            <button type="button" id="published-mark-not-live-btn" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-medium transition-opacity hover:opacity-90" style="background: var(--surface-container-high); color: var(--on-surface-variant); border-radius: 12px;">
                                <i class="fas fa-eye-slash" aria-hidden="true"></i> Mark not live
                            </button>
                        </div>

                        <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0 border-x border-b md:border-x md:border-b" style="border-color: var(--outline-variant);">
                            <ul class="flex flex-col gap-3 md:gap-0 md:divide-y divide-[var(--outline-variant)] p-4 sm:px-6 md:p-0 list-none min-w-0" role="list">
                                <li class="hidden md:flex items-center gap-3 sm:gap-4 px-5 sm:px-6 py-3" style="background: var(--surface-container); border-color: var(--outline-variant);">
                                    <label class="flex items-center flex-shrink-0 cursor-pointer">
                                        <input type="checkbox" id="published-select-all" class="published-row-cb form-checkbox-input w-4 h-4 rounded border-2 cursor-pointer focus:ring-2 focus:ring-offset-0" style="border-color: var(--outline); accent-color: var(--primary);" aria-label="Select all on page">
                                    </label>
                                    <span class="w-10 flex-shrink-0" aria-hidden="true"></span>
                                    <span class="text-xs font-medium flex-1 min-w-0" style="color: var(--on-surface-variant);">Name</span>
                                    <span class="text-xs font-medium flex-shrink-0 w-12 text-right" style="color: var(--on-surface-variant);">Total</span>
                                    <span class="text-xs font-medium flex-shrink-0 w-14 text-right" style="color: var(--on-surface-variant);">Average</span>
                                    <span class="text-xs font-medium flex-shrink-0 w-14 text-center" style="color: var(--on-surface-variant);">Position</span>
                                    <span class="text-xs font-medium flex-shrink-0 w-20" style="color: var(--on-surface-variant);">Status</span>
                                    <span class="text-xs font-medium flex-shrink-0 w-[7.75rem]" style="color: var(--on-surface-variant);">Remark</span>
                                    <span class="text-xs font-medium flex-shrink-0 w-24 text-right" style="color: var(--on-surface-variant);">Subjects</span>
                                </li>
                                @foreach($positions as $p)
                                    @php
                                        $initial = $p->name ? mb_substr(trim($p->name), 0, 1) : 'S';
                                        $student = $studentsByReg->get($p->reg_number ?? '');
                                        $imagelocation = $student->imagelocation ?? null;
                                        $avatarSrc = $imagelocation
                                            ? (str_starts_with($imagelocation, 'students/') ? asset('storage/' . $imagelocation) : asset('storage/students/' . $imagelocation))
                                            : asset('storage/students/default.png');
                                        $subjects = $subjectBreakdown->get($p->reg_number ?? '', collect());
                                        $isLive = (int) ($p->status ?? 0) === 1;
                                        $pos = (int) ($p->class_position ?? 0);
                                        $v = $pos % 100;
                                        $positionOrdinal = $pos <= 0 ? '—' : ($v >= 11 && $v <= 13 ? $pos . 'th' : ($pos % 10 === 1 ? $pos . 'st' : ($pos % 10 === 2 ? $pos . 'nd' : ($pos % 10 === 3 ? $pos . 'rd' : $pos . 'th'))));
                                    @endphp
                                    <li class="published-row flex flex-col gap-0 rounded-2xl border p-4 md:rounded-none md:border-0 md:border-b md:border-t-0 md:flex-row md:items-center md:gap-4 md:py-4 md:px-5 lg:px-6 md:min-w-0 md:p-0 transition-[background-color] duration-200 flex-wrap" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);" data-reg-number="{{ e($p->reg_number) }}">
                                        <div class="flex items-center gap-3 md:contents">
                                            <label class="flex items-center flex-shrink-0 cursor-pointer">
                                                <input type="checkbox" name="selectedRows[]" value="{{ e($p->reg_number) }}" class="published-row-cb form-checkbox-input w-4 h-4 rounded border-2 cursor-pointer focus:ring-2 focus:ring-offset-0" style="border-color: var(--outline); accent-color: var(--primary);" aria-label="Select {{ e($p->name) }}">
                                            </label>
                                            <img src="{{ $avatarSrc }}" alt="" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($initial) }}&size=80'">
                                            <div class="min-w-0 flex-1 md:min-w-0 md:flex-1">
                                                <span class="text-xs font-medium md:sr-only" style="color: var(--on-surface-variant);">Name</span>
                                                <p class="text-sm font-medium truncate" style="color: var(--on-surface);">
                                                    @if($student && Route::has('admin.students.show'))
                                                        <a href="{{ route('admin.students.show', $student) }}" class="transition-opacity hover:opacity-80" style="color: var(--primary);">{{ e($p->name) }}</a>
                                                    @else
                                                        {{ e($p->name) }}
                                                    @endif
                                                </p>
                                                <p class="text-xs truncate mt-0.5" style="color: var(--on-surface-variant);">{{ e($p->reg_number) }}</p>
                                            </div>
                                        </div>

                                        <div class="mt-3 pt-3 border-t md:border-t-0 md:mt-0 md:pt-0 flex flex-wrap items-baseline gap-x-4 gap-y-1 md:contents" style="border-color: var(--outline-variant);">
                                            <span class="w-full text-xs font-medium mb-1 md:sr-only" style="color: var(--on-surface-variant);">Scores</span>
                                            <span class="text-xs md:flex-shrink-0 md:w-12 md:text-right"><span class="md:sr-only" style="color: var(--on-surface-variant);">Total </span><span class="font-semibold tabular-nums" style="color: var(--on-surface);">{{ $p->students_sub_total }}</span></span>
                                            <span class="text-xs md:flex-shrink-0 md:w-14 md:text-right"><span class="md:sr-only" style="color: var(--on-surface-variant);">Average </span><span class="font-semibold tabular-nums" style="color: var(--on-surface);">{{ number_format((float)$p->students_sub_average, 2) }}</span></span>
                                            <span class="text-xs md:flex-shrink-0 md:w-14 md:text-center"><span class="md:sr-only" style="color: var(--on-surface-variant);">Position </span><span class="font-semibold tabular-nums" style="color: var(--on-surface);">{{ $positionOrdinal }}</span></span>
                                        </div>

                                        <div class="mt-2 pt-2 sm:mt-3 sm:pt-3 border-t md:border-t-0 md:mt-0 md:pt-0 w-full flex flex-row flex-wrap items-center justify-between gap-3 md:contents" style="border-color: var(--outline-variant);">
                                            <span class="sr-only" style="color: var(--on-surface-variant);">Status</span>
                                            @if($isLive)
                                                <span class="published-status-badge inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 md:flex-shrink-0 md:flex md:justify-end border border-current/20" style="background: var(--success-container); color: var(--on-success-container);"><i class="fas fa-broadcast-tower text-[10px]" aria-hidden="true"></i> Live</span>
                                            @else
                                                <span class="published-status-badge inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 md:flex-shrink-0 md:flex md:justify-end border border-current/20" style="background: var(--error-container); color: var(--on-error-container);"><i class="fas fa-eye-slash text-[10px]" aria-hidden="true"></i> Not live</span>
                                            @endif

                                            <button type="button" class="published-principal-remark-btn order-first w-full sm:w-auto md:order-none md:w-[7.75rem] md:flex-shrink-0 inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium transition-opacity hover:opacity-90 border" style="background: var(--surface-container-high); color: var(--on-surface-variant); border-color: var(--outline-variant);" data-reg-number="{{ e($p->reg_number) }}" aria-haspopup="dialog">
                                                <i class="fas fa-comment-alt text-[10px]" aria-hidden="true"></i>
                                                <span>Remark</span>
                                            </button>

                                            <button type="button" class="published-toggle-subjects order-last md:order-none shrink-0 inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium transition-opacity hover:opacity-90 md:flex-shrink-0 md:justify-end" style="background: var(--primary-container); color: var(--on-primary-container);" aria-expanded="false" aria-controls="published-subjects-{{ $loop->index }}" id="published-toggle-{{ $loop->index }}">
                                                <i class="fas fa-book-open text-xs" aria-hidden="true"></i>
                                                <span>Subjects ({{ $subjects->count() }})</span>
                                            </button>
                                        </div>

                                        <div id="published-subjects-{{ $loop->index }}" class="published-subjects-detail hidden w-full border-t mt-2" style="border-color: var(--outline-variant); background: var(--surface-container);" aria-hidden="true">
                                            <div class="px-4 sm:px-6 py-3">
                                                <p class="text-[11px] font-semibold uppercase tracking-wider mb-2" style="color: var(--on-surface-variant); letter-spacing: 0.06em;">Subject scores — {{ e($p->name) }}</p>
                                                @if($subjects->isEmpty())
                                                    <p class="text-sm" style="color: var(--on-surface-variant);">No subject data.</p>
                                                @else
                                                    <div class="overflow-x-auto rounded-xl border min-w-0" style="border-color: var(--outline-variant);">
                                                        <table class="min-w-full text-sm">
                                                            <thead>
                                                                <tr class="border-b" style="border-color: var(--outline-variant); background: var(--surface-container-high);">
                                                                    <th class="px-3 py-2 text-left text-xs font-medium" style="color: var(--on-surface-variant);">Subject</th>
                                                                    <th class="px-3 py-2 text-center text-xs font-medium w-14" style="color: var(--on-surface-variant);">CA</th>
                                                                    <th class="px-3 py-2 text-center text-xs font-medium w-14" style="color: var(--on-surface-variant);">Assign</th>
                                                                    <th class="px-3 py-2 text-center text-xs font-medium w-14" style="color: var(--on-surface-variant);">Exam</th>
                                                                    <th class="px-3 py-2 text-center text-xs font-medium w-14" style="color: var(--on-surface-variant);">Total</th>
                                                                    <th class="px-3 py-2 text-center text-xs font-medium w-10" style="color: var(--on-surface-variant);">Grade</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y" style="border-color: var(--outline-variant);">
                                                                @foreach($subjects as $sub)
                                                                    <tr style="background: var(--surface-container-lowest);">
                                                                        <td class="px-3 py-2 font-medium" style="color: var(--on-surface);">{{ e($sub->subjects) }}</td>
                                                                        <td class="px-3 py-2 text-center tabular-nums" style="color: var(--on-surface);">{{ $sub->ca }}</td>
                                                                        <td class="px-3 py-2 text-center tabular-nums" style="color: var(--on-surface);">{{ $sub->assignment }}</td>
                                                                        <td class="px-3 py-2 text-center tabular-nums" style="color: var(--on-surface);">{{ $sub->exam }}</td>
                                                                        <td class="px-3 py-2 text-center tabular-nums font-semibold" style="color: var(--on-surface);">{{ $sub->total }}</td>
                                                                        <td class="px-3 py-2 text-center font-semibold" style="color: var(--on-surface);">{{ $sub->grade_letter }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
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

    <div id="published-remark-modal" class="fixed inset-0 z-[60] hidden overflow-y-auto overscroll-contain" aria-modal="true" role="dialog" aria-labelledby="published-remark-modal-title">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="published-remark-modal" aria-hidden="true"></div>
        <div class="relative min-h-full min-h-[100dvh] flex items-center justify-center p-4 py-6 sm:p-6">
            <div class="relative w-full max-w-lg min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-2xl py-5 px-4 sm:py-6 sm:px-6 shadow-xl border my-auto" style="background: var(--surface-container-lowest); border-color: var(--outline-variant); border-radius: 16px;">
                <h3 id="published-remark-modal-title" class="text-lg font-semibold mb-1" style="color: var(--on-surface);">Principal&rsquo;s remark</h3>
                <p class="text-sm mb-4" style="color: var(--on-surface-variant);">This text appears on the student&rsquo;s published result sheet.</p>
                <form id="published-remark-form" class="space-y-4">
                    <div class="form-group">
                        <label for="published-remark-textarea" class="form-label">Remark</label>
                        <textarea id="published-remark-textarea" name="remark" rows="5" maxlength="1000" class="form-input w-full min-h-[7.5rem] resize-y" placeholder="Optional — max 1000 characters" style="font-size: 0.9375rem;"></textarea>
                        <p class="text-xs mt-1" style="color: var(--on-surface-variant);"><span id="published-remark-char-count">0</span> / 1000</p>
                    </div>
                    <input type="hidden" name="reg_number" id="published-remark-reg-number" value="">
                    <input type="hidden" name="class" id="published-remark-class" value="">
                    <input type="hidden" name="term" id="published-remark-term" value="">
                    <input type="hidden" name="session" id="published-remark-session" value="">
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 pt-2" style="border-top: 1px solid var(--outline-variant);">
                        <button type="button" class="btn-secondary px-4 py-2.5 rounded-xl text-sm w-full sm:w-auto" data-close="published-remark-modal">Cancel</button>
                        <button type="submit" id="published-remark-submit" class="btn-primary px-4 py-2.5 rounded-xl text-sm font-medium w-full sm:w-auto inline-flex items-center justify-center gap-2">Save remark</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="published-confirm-modal" class="fixed inset-0 z-50 hidden overflow-y-auto overscroll-contain" aria-modal="true" role="dialog" aria-labelledby="published-confirm-modal-title">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="published-confirm-modal" aria-hidden="true"></div>
        <div class="relative min-h-full min-h-[100dvh] flex items-center justify-center p-4 py-6 sm:p-6">
            <div class="relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-xl py-5 px-4 sm:py-6 sm:px-6 shadow-xl border my-auto" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                <h3 id="published-confirm-modal-title" class="text-lg font-semibold mb-2" style="color: var(--on-surface);"></h3>
                <p id="published-confirm-modal-message" class="text-sm mb-6" style="color: var(--on-surface-variant);"></p>
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-2">
                    <button type="button" class="btn-secondary px-4 py-2.5 rounded-xl text-sm w-full sm:w-auto" data-close="published-confirm-modal">Cancel</button>
                    <button type="button" id="published-confirm-modal-confirm" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium w-full sm:w-auto transition-opacity hover:opacity-95"></button>
                </div>
            </div>
        </div>
    </div>

    @if($hasFilters && !$positions->isEmpty())
        @push('scripts')
            <script>
                (function () {
                    const setLiveUrl = @json(route('admin.results.published.set-live'));
                    const deleteUrl = @json(route('admin.results.published.delete'));
                    const remarkUrl = @json(route('admin.results.remark'));
                    const csrf = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const classVal = @json($class);
                    const termVal = @json($term);
                    const sessionVal = @json($session);
                    const positionsCount = @json($positions->count());
                    const RELOAD_DELAY_MS = 2800;
                    const remarksByReg = @json($positions->pluck('remark', 'reg_number')->all());

                    const toolbar = document.getElementById('published-results-toolbar');
                    const selectAll = document.getElementById('published-select-all');
                    const checkboxes = document.querySelectorAll('.published-row-cb[name="selectedRows[]"]');
                    const countEl = document.getElementById('published-selected-count');

                    function getSelectedRegNumbers() {
                        return [].slice.call(checkboxes).filter(function (c) { return c.checked; }).map(function (c) { return c.value; });
                    }

                    function updateToolbar() {
                        const ids = getSelectedRegNumbers();
                        const n = ids.length;
                        if (countEl) countEl.textContent = n;
                        if (toolbar) toolbar.classList.toggle('hidden', n === 0);
                        if (selectAll) {
                            selectAll.checked = n > 0 && n === checkboxes.length;
                            selectAll.indeterminate = n > 0 && n < checkboxes.length;
                        }
                    }

                    if (selectAll && checkboxes.length) {
                        selectAll.addEventListener('change', function () {
                            checkboxes.forEach(function (c) { c.checked = selectAll.checked; });
                            updateToolbar();
                        });
                        checkboxes.forEach(function (c) { c.addEventListener('change', updateToolbar); });
                        updateToolbar();
                    }

                    document.querySelectorAll('.published-toggle-subjects').forEach(function (btn) {
                        btn.addEventListener('click', function () {
                            const id = btn.getAttribute('aria-controls');
                            const panel = document.getElementById(id);
                            if (!panel) return;
                            const expanded = btn.getAttribute('aria-expanded') === 'true';
                            btn.setAttribute('aria-expanded', !expanded);
                            panel.classList.toggle('hidden', expanded);
                            panel.setAttribute('aria-hidden', expanded ? 'true' : 'false');
                        });
                    });

                    const confirmModal = document.getElementById('published-confirm-modal');
                    const confirmTitle = document.getElementById('published-confirm-modal-title');
                    const confirmMessage = document.getElementById('published-confirm-modal-message');
                    const confirmBtn = document.getElementById('published-confirm-modal-confirm');
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
                    document.querySelectorAll('[data-close="published-confirm-modal"]').forEach(function (el) {
                        el.addEventListener('click', closeConfirmModal);
                    });
                    confirmBtn.addEventListener('click', function () {
                        if (!pendingConfirmAction || !csrf) return;
                        const action = pendingConfirmAction;
                        pendingConfirmAction = null;
                        if (typeof setButtonLoading === 'function') setButtonLoading(confirmBtn, true);
                        const body = action.deleteAll
                            ? {class: classVal, term: termVal, session: sessionVal}
                            : {
                                class: classVal,
                                term: termVal,
                                session: sessionVal,
                                selectedRows: action.selectedRows,
                                live: action.live
                            };
                        const url = action.deleteAll ? deleteUrl : setLiveUrl;
                        fetch(url, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
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

                    document.getElementById('published-mark-live-btn').addEventListener('click', function () {
                        const regNumbers = getSelectedRegNumbers();
                        if (!regNumbers.length) { if (typeof flashError === 'function') flashError('Select at least one result.'); return; }
                        const n = regNumbers.length;
                        openConfirmModal('Mark live', 'Mark the selected ' + n + ' result(s) as live? They will be visible to students.', 'Mark live', 'success', { url: setLiveUrl, selectedRows: regNumbers, live: true });
                    });
                    document.getElementById('published-mark-not-live-btn').addEventListener('click', function () {
                        const regNumbers = getSelectedRegNumbers();
                        if (!regNumbers.length) { if (typeof flashError === 'function') flashError('Select at least one result.'); return; }
                        const n = regNumbers.length;
                        openConfirmModal('Mark not live', 'Mark the selected ' + n + ' result(s) as not live? They will no longer be visible to students.', 'Mark not live', 'error', { url: setLiveUrl, selectedRows: regNumbers, live: false });
                    });
                    document.getElementById('published-delete-btn').addEventListener('click', function () {
                        const msg = 'Delete all ' + positionsCount + ' published result(s) for ' + classVal + ', ' + termVal + ', ' + sessionVal + '? This cannot be undone.';
                        openConfirmModal('Delete published results', msg, 'Delete all', 'error', { deleteAll: true });
                    });

                    const remarkModal = document.getElementById('published-remark-modal');
                    const remarkForm = document.getElementById('published-remark-form');
                    const remarkTextarea = document.getElementById('published-remark-textarea');
                    const remarkRegInput = document.getElementById('published-remark-reg-number');
                    const remarkClassInput = document.getElementById('published-remark-class');
                    const remarkTermInput = document.getElementById('published-remark-term');
                    const remarkSessionInput = document.getElementById('published-remark-session');
                    const remarkSubmit = document.getElementById('published-remark-submit');
                    const remarkCharCount = document.getElementById('published-remark-char-count');

                    function openRemarkModal(regNumber) {
                        if (!remarkModal || !remarkTextarea || !remarkRegInput) return;
                        remarkRegInput.value = regNumber;
                        if (remarkClassInput) remarkClassInput.value = classVal;
                        if (remarkTermInput) remarkTermInput.value = termVal;
                        if (remarkSessionInput) remarkSessionInput.value = sessionVal;
                        const existing = remarksByReg[regNumber];
                        remarkTextarea.value = existing != null && existing !== '' ? String(existing) : '';
                        updateRemarkCharCount();
                        remarkModal.classList.remove('hidden');
                        remarkTextarea.focus();
                    }

                    function closeRemarkModal() {
                        if (remarkModal) remarkModal.classList.add('hidden');
                    }

                    function updateRemarkCharCount() {
                        if (remarkCharCount && remarkTextarea) {
                            remarkCharCount.textContent = String(remarkTextarea.value.length);
                        }
                    }

                    document.querySelectorAll('[data-close="published-remark-modal"]').forEach(function (el) {
                        el.addEventListener('click', closeRemarkModal);
                    });

                    document.querySelectorAll('.published-principal-remark-btn').forEach(function (btn) {
                        btn.addEventListener('click', function () {
                            const reg = btn.getAttribute('data-reg-number');
                            if (!reg) return;
                            openRemarkModal(reg);
                        });
                    });

                    if (remarkTextarea) {
                        remarkTextarea.addEventListener('input', updateRemarkCharCount);
                    }

                    if (remarkForm) {
                        remarkForm.addEventListener('submit', function (e) {
                            e.preventDefault();
                            if (!csrf || !remarkUrl) return;
                            const payload = {
                                reg_number: remarkRegInput ? remarkRegInput.value : '',
                                class: remarkClassInput ? remarkClassInput.value : classVal,
                                term: remarkTermInput ? remarkTermInput.value : termVal,
                                session: remarkSessionInput ? remarkSessionInput.value : sessionVal,
                                remark: remarkTextarea ? remarkTextarea.value : ''
                            };
                            if (typeof setButtonLoading === 'function') setButtonLoading(remarkSubmit, true);
                            fetch(remarkUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrf,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify(payload)
                            }).then(function (r) { return r.json().then(function (d) { return { ok: r.ok, d: d }; }); }).then(function (_ref) {
                                if (typeof setButtonLoading === 'function') setButtonLoading(remarkSubmit, false);
                                const d = _ref.d;
                                if (_ref.ok && d.status === 'success') {
                                    if (payload.reg_number) {
                                        remarksByReg[payload.reg_number] = d.remark != null ? d.remark : null;
                                    }
                                    if (typeof flashSuccess === 'function') flashSuccess(d.message || 'Saved.');
                                    closeRemarkModal();
                                } else {
                                    const msg = (d && d.message) ? d.message : (d && d.errors && Object.keys(d.errors).length ? Object.values(d.errors).flat().join(' ') : 'Could not save remark.');
                                    if (typeof flashError === 'function') flashError(msg);
                                }
                            }).catch(function () {
                                if (typeof setButtonLoading === 'function') setButtonLoading(remarkSubmit, false);
                                if (typeof flashError === 'function') flashError('Request failed.');
                            });
                        });
                    }
                })();
            </script>
        @endpush
    @endif
@endsection
