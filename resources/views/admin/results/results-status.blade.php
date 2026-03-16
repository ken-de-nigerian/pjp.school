@extends('layouts.app')

@section('content')
    @php
        $hasFilters = ($class ?? '') !== '' && ($term ?? '') !== '' && ($session ?? '') !== '';
        $teacherSubjects = $teacherSubjects ?? [];
    @endphp
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <header class="mb-6 lg:mb-8 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                <div class="min-w-0 flex-1">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-normal tracking-tight mb-1.5" style="color: var(--on-surface); letter-spacing: -0.02em;">Result status</h1>
                    <p class="text-sm sm:text-base font-normal" style="color: var(--on-surface-variant);">
                        @if($hasFilters)
                            Teachers assigned to this class and upload/approval status for each subject.
                        @else
                            Choose class, term and session to see which teachers have uploaded results and their approval status.
                        @endif
                    </p>
                </div>

                @if($hasFilters)
                    <a href="{{ route('admin.status.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-opacity hover:opacity-90 shrink-0" style="color: var(--on-surface-variant); background: var(--surface-container-high); border-radius: 12px;">
                        <i class="fas fa-filter text-xs" aria-hidden="true"></i>
                        <span>Change filters</span>
                    </a>
                @endif
            </header>

            <div class="rounded-3xl p-4 sm:p-5 lg:p-6 mb-6 overflow-hidden min-w-0 w-full" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                <form method="GET" action="{{ route('admin.status.index') }}" class="space-y-4 sm:space-y-5">
                    <div class="grid grid-cols-12 gap-4 min-w-0">
                        <div class="col-span-12 sm:col-span-6 lg:col-span-4 form-group min-w-0">
                            <label for="status-class" class="form-label">Class <span style="color: var(--primary);">*</span></label>
                            <select id="status-class" name="class" class="form-select w-full min-w-0" required>
                                <option value="">Select class</option>
                                @foreach($getClasses ?? [] as $c)
                                    @php $cn = is_object($c) ? $c->class_name : $c; @endphp
                                    <option value="{{ e($cn) }}" {{ ($class ?? '') === $cn ? 'selected' : '' }}>{{ e($cn) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-12 sm:col-span-6 lg:col-span-4 form-group min-w-0">
                            <label for="status-term" class="form-label">Term <span style="color: var(--primary);">*</span></label>
                            <select id="status-term" name="term" class="form-select w-full min-w-0" required>
                                <option value="">Select term</option>
                                <option value="First Term" {{ ($term ?? '') === 'First Term' ? 'selected' : '' }}>First Term</option>
                                <option value="Second Term" {{ ($term ?? '') === 'Second Term' ? 'selected' : '' }}>Second Term</option>
                                <option value="Third Term" {{ ($term ?? '') === 'Third Term' ? 'selected' : '' }}>Third Term</option>
                            </select>
                        </div>
                        <div class="col-span-12 sm:col-span-6 lg:col-span-4 form-group min-w-0">
                            <label for="status-session" class="form-label">Session <span style="color: var(--primary);">*</span></label>
                            <select id="status-session" name="session" class="form-select w-full min-w-0" required>
                                <option value="">Select session</option>
                                @foreach(range((int)date('Y') - 5, (int)date('Y') + 5) as $y)
                                    @php $opt = $y . '/' . ($y + 1); @endphp
                                    <option value="{{ $opt }}" {{ ($session ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2 min-w-0" style="border-top: 1px solid var(--outline-variant); padding-top: 1.25rem;">
                        <a href="{{ route('admin.status.index') }}" class="btn-secondary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 sm:min-w-[120px]" style="border-radius: 12px;">
                            <i class="fas fa-times text-sm" aria-hidden="true"></i>
                            Clear
                        </a>
                        <button type="submit" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" data-preloader style="border-radius: 12px;">
                            <i class="fas fa-check-circle text-sm" aria-hidden="true"></i>
                            View status
                        </button>
                    </div>
                </form>
            </div>

            @if(!$hasFilters)
                <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden flex flex-col items-center justify-center py-16 md:py-24 px-6" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                    <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center" style="border-radius: 16px;">
                        <i class="fas fa-check-circle text-3xl" aria-hidden="true"></i>
                    </div>
                    <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No filters selected</h2>
                    <p class="text-sm text-center max-w-sm" style="color: var(--on-surface-variant);">Choose class, term and session above, then click &quot;View status&quot; to see teachers assigned to the class and whether each subject&apos;s results have been uploaded and approved.</p>
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
                                    <span class="text-[10px] font-medium uppercase tracking-wide block mb-1" style="color: var(--on-surface-variant);">Term</span>
                                    <span class="text-sm font-semibold leading-snug line-clamp-2 break-words" style="color: var(--on-surface);" title="{{ e($term) }}">{{ e($term) }}</span>
                                </div>
                                <div class="rounded-xl px-3 py-2.5 min-w-0 border" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                    <span class="text-[10px] font-medium uppercase tracking-wide block mb-1" style="color: var(--on-surface-variant);">Session</span>
                                    <span class="text-sm font-semibold leading-snug line-clamp-2 break-words" style="color: var(--on-surface);" title="{{ e($session) }}">{{ e($session) }}</span>
                                </div>
                                <div class="rounded-xl px-3 py-2.5 min-w-0 border col-span-2 lg:col-span-1" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                    <span class="text-[10px] font-medium uppercase tracking-wide block mb-1" style="color: var(--on-surface-variant);">Teachers</span>
                                    <span class="text-sm font-semibold tabular-nums" style="color: var(--on-surface);">{{ count($teacherSubjects) }}</span>
                                </div>
                            </div>
                            <div class="mt-3 pt-3" style="border-top: 1px solid var(--outline-variant);">
                                <a href="{{ route('admin.status.index', ['class' => $class, 'term' => $term, 'session' => $session, 'view' => 'sheet']) }}" class="inline-flex items-center gap-2 text-sm font-medium transition-opacity hover:opacity-80" style="color: var(--primary);">
                                    <i class="fas fa-file-alt" aria-hidden="true"></i>
                                    View result sheet
                                </a>
                            </div>
                        </div>
                    </div>

                    @if(empty($teacherSubjects))
                        <div class="flex flex-col items-center justify-center py-16 md:py-24 px-6">
                            <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center" style="border-radius: 16px;">
                                <i class="fas fa-chalkboard-teacher text-3xl" aria-hidden="true"></i>
                            </div>
                            <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No teachers assigned</h2>
                            <p class="text-sm text-center max-w-sm" style="color: var(--on-surface-variant);">No teachers are assigned to {{ e($class) }}. Assign teachers to this class to see their subjects and result status here.</p>
                        </div>
                    @else
                        <div class="divide-y overflow-x-auto" style="border-color: var(--outline-variant);">
                            @foreach($teacherSubjects as $item)
                                @php
                                    $teacher = $item['teacher'];
                                    $subjects = $item['subjects'];
                                @endphp
                                <div class="flex flex-col min-w-0">
                                    <div class="px-4 sm:px-6 py-4 sm:py-5" style="background: var(--surface-container-low);">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                                            <div class="flex items-center gap-3 min-w-0">
                                                @php
                                                    $initial = $teacher->firstname ? mb_substr(trim($teacher->firstname), 0, 1) : 'T';
                                                    $imagelocation = $teacher->imagelocation ?? null;
                                                    $avatarSrc = $imagelocation
                                                        ? (str_starts_with($imagelocation, 'teachers/') ? asset('storage/' . $imagelocation) : asset('storage/teachers/' . $imagelocation))
                                                        : asset('storage/teachers/default.png');
                                                @endphp
                                                <img src="{{ $avatarSrc }}" alt="" class="w-12 h-12 rounded-full object-cover flex-shrink-0 border-2" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($teacher->name ?? $initial) }}&size=96'">
                                                <div class="min-w-0">
                                                    <h2 class="text-base font-semibold truncate" style="color: var(--on-surface);">{{ e($teacher->name ?? $teacher->firstname . ' ' . $teacher->lastname) }}</h2>
                                                    <p class="text-xs truncate" style="color: var(--on-surface-variant);">{{ count($subjects) }} subject(s)</p>
                                                </div>
                                            </div>
                                        </div>
                                        <ul class="flex flex-col gap-2 list-none p-0 m-0" role="list">
                                            @foreach($subjects as $sub)
                                                <li class="flex flex-wrap items-center gap-2 sm:gap-4 py-2.5 px-3 rounded-xl border min-w-0" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                                    <span class="text-sm font-medium min-w-0 flex-1" style="color: var(--on-surface);">{{ e($sub['name']) }}</span>
                                                    <div class="flex flex-wrap items-center gap-2 shrink-0">
                                                        @if($sub['uploaded'])
                                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border border-current/20" style="background: var(--success-container); color: var(--on-success-container);">
                                                                <i class="fas fa-upload text-[10px]" aria-hidden="true"></i> Uploaded
                                                            </span>
                                                            @if((int) $sub['status'] === 1)
                                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border border-current/20" style="background: var(--success-container); color: var(--on-success-container);">
                                                                    <i class="fas fa-check text-[10px]" aria-hidden="true"></i> Approved
                                                                </span>
                                                            @elseif((int) $sub['status'] === 3)
                                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border border-current/20" style="background: var(--error-container); color: var(--on-error-container);">
                                                                    <i class="fas fa-times text-[10px]" aria-hidden="true"></i> Rejected
                                                                </span>
                                                            @else
                                                                <span class="badge-warning inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold">
                                                                    <i class="fas fa-clock text-[10px] opacity-80" aria-hidden="true"></i> Pending
                                                                </span>
                                                            @endif
                                                        @else
                                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border" style="background: var(--surface-container-high); color: var(--on-surface-variant); border-color: var(--outline-variant);">
                                                                <i class="fas fa-minus-circle text-[10px]" aria-hidden="true"></i> Not uploaded
                                                            </span>
                                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border" style="background: var(--surface-container-high); color: var(--on-surface-variant); border-color: var(--outline-variant);">
                                                                —
                                                            </span>
                                                        @endif
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </main>
@endsection
