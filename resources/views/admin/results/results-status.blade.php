@extends('layouts.app', ['title' => 'Results status'])

@section('content')
    @php
        $hasFilters = ($class ?? '') !== '' && ($term ?? '') !== '' && ($session ?? '') !== '';
        $teacherSubjects = $teacherSubjects ?? [];

        // Sort teachers alphabetically by name for a consistent display
        usort($teacherSubjects, function ($a, $b) {
            $ta = $a['teacher'] ?? null;
            $tb = $b['teacher'] ?? null;
            $nameA = $ta ? trim(($ta->name ?? ($ta->firstname . ' ' . $ta->lastname))) : '';
            $nameB = $tb ? trim(($tb->name ?? ($tb->firstname . ' ' . $tb->lastname))) : '';
            return strcasecmp($nameA, $nameB);
        });
        $resultsStatusHeroDescription = $hasFilters
            ? 'Teachers assigned to this class and upload/approval status for each subject.'
            : 'Choose class, term and session to see which teachers have uploaded results and their approval status.';
    @endphp
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Result status"
                pill="Admin"
                title="Result status"
                :description="$resultsStatusHeroDescription"
            />

            <div class="rounded-3xl p-4 sm:p-5 lg:p-6 mb-6 overflow-hidden min-w-0 w-full" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                <form method="GET" action="{{ route('admin.status.index') }}" class="space-y-4 sm:space-y-5">
                    <div class="grid grid-cols-12 gap-4 min-w-0">
                        <div class="col-span-12 sm:col-span-6 lg:col-span-4 form-group min-w-0">
                            <label for="status-class" class="form-label">Class <span style="color: var(--primary);">*</span></label>
                            <x-forms.md-select-native id="status-class" name="class" class="form-select w-full min-w-0" required>
                                <option value="">Select class</option>
                                @foreach($getClasses ?? [] as $c)
                                    @php $cn = is_object($c) ? $c->class_name : $c; @endphp
                                    <option value="{{ e($cn) }}" {{ ($class ?? '') === $cn ? 'selected' : '' }}>{{ e($cn) }}</option>
                                @endforeach
                            </x-forms.md-select-native>
                        </div>
                        <div class="col-span-12 sm:col-span-6 lg:col-span-4 form-group min-w-0">
                            <label for="status-term" class="form-label">Term <span style="color: var(--primary);">*</span></label>
                            <x-forms.md-select-native id="status-term" name="term" class="form-select w-full min-w-0" required>
                                <option value="">Select term</option>
                                <option value="First Term" {{ ($term ?? '') === 'First Term' ? 'selected' : '' }}>First Term</option>
                                <option value="Second Term" {{ ($term ?? '') === 'Second Term' ? 'selected' : '' }}>Second Term</option>
                                <option value="Third Term" {{ ($term ?? '') === 'Third Term' ? 'selected' : '' }}>Third Term</option>
                            </x-forms.md-select-native>
                        </div>
                        <div class="col-span-12 sm:col-span-6 lg:col-span-4 form-group min-w-0">
                            <label for="status-session" class="form-label">Session <span style="color: var(--primary);">*</span></label>
                            <x-forms.md-select-native id="status-session" name="session" class="form-select w-full min-w-0" required>
                                <option value="">Select session</option>
                                @foreach(range((int)date('Y') - 5, (int)date('Y') + 5) as $y)
                                    @php $opt = $y . '/' . ($y + 1); @endphp
                                    <option value="{{ $opt }}" {{ ($session ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </x-forms.md-select-native>
                        </div>
                    </div>
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2 min-w-0" style="border-top: 1px solid var(--outline-variant); padding-top: 1.25rem;">
                        <button type="submit" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 w-full sm:w-auto min-h-[2.75rem] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" data-preloader style="border-radius: 12px;">
                            <i class="fas fa-check-circle text-sm" aria-hidden="true"></i>
                            View status
                        </button>
                    </div>
                </form>
            </div>

            @if(!$hasFilters)
                <div class="flex-1 min-h-0 w-full rounded-3xl overflow-hidden flex flex-col items-center justify-center py-16 md:py-24 px-6" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
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
                        </div>
                    </div>

                    @if(! empty($teacherSubjects))
                        <div class="flex-1 flex flex-col min-h-0 w-full overflow-hidden">
                            <div class="block md:hidden border-t" style="border-color: var(--outline-variant);">
                                <ul class="flex flex-col gap-3 p-4 sm:px-6 md:p-0 list-none min-w-0" role="list">
                                    @foreach($teacherSubjects as $index => $item)
                                        @php
                                            $teacher = $item['teacher'];
                                            $subjects = $item['subjects'];
                                            $initial = $teacher->firstname ? mb_substr(trim($teacher->firstname), 0, 1) : 'T';
                                            $imagelocation = $teacher->imagelocation ?? null;
                                            $avatarSrc = $imagelocation
                                                ? (str_starts_with($imagelocation, 'teachers/') ? asset('storage/' . $imagelocation) : asset('storage/teachers/' . $imagelocation))
                                                : asset('storage/teachers/default.png');
                                            $totalSubjects = count($subjects);
                                            $uploadedCount = collect($subjects)->where('uploaded', true)->count();
                                        @endphp
                                        <li class="status-card flex flex-col gap-0 rounded-2xl border p-4 md:rounded-none md:border-0 md:border-b md:border-t-0 md:flex-row md:items-center md:gap-4 md:py-4 md:px-5 lg:px-6 md:min-w-0 md:p-0 transition-[background-color] duration-200 flex-wrap" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                            <div class="flex items-center gap-3 md:contents">
                                                <img src="{{ $avatarSrc }}" alt="" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($teacher->name ?? $initial) }}&size=80'">
                                                <div class="min-w-0 flex-1 md:min-w-0 md:flex-1">
                                                    <p class="text-sm font-medium truncate" style="color: var(--on-surface);">{{ e($teacher->name ?? $teacher->firstname . ' ' . $teacher->lastname) }}</p>
                                                    <p class="text-xs truncate mt-0.5" style="color: var(--on-surface-variant);">{{ $totalSubjects }} subject(s)</p>
                                                </div>
                                            </div>

                                            <div class="mt-3 pt-3 border-t md:border-t-0 md:mt-0 md:pt-0 w-full flex flex-row flex-wrap items-center justify-between gap-3" style="border-color: var(--outline-variant);">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border" style="background: var(--surface-container-high); color: var(--on-surface-variant); border-color: var(--outline-variant);">
                                                    <i class="fas fa-upload text-[10px]" aria-hidden="true"></i>
                                                    {{ $uploadedCount }} / {{ $totalSubjects }} uploaded
                                                </span>

                                                <button type="button" class="status-toggle-subjects inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium transition-opacity hover:opacity-90" style="background: var(--primary-container); color: var(--on-primary-container); border-radius: 999px;" aria-expanded="false" aria-controls="status-subjects-mobile-{{ $index }}" id="status-toggle-mobile-{{ $index }}">
                                                    <i class="fas fa-book-open text-xs" aria-hidden="true"></i>
                                                    <span>Subjects ({{ $totalSubjects }})</span>
                                                </button>
                                            </div>

                                            <div id="status-subjects-mobile-{{ $index }}" class="status-subjects-panel hidden w-full border-t mt-2" style="border-color: var(--outline-variant); background: var(--surface-container);" aria-hidden="true">
                                                <div class="px-3 sm:px-4 py-3">
                                                    <p class="text-[11px] font-semibold uppercase tracking-wider mb-2" style="color: var(--on-surface-variant); letter-spacing: 0.06em;">
                                                        Subject status — {{ e($teacher->name ?? $teacher->firstname . ' ' . $teacher->lastname) }}
                                                    </p>
                                                    <div class="flex flex-col gap-2">
                                                        @foreach($subjects as $sub)
                                                            <div class="flex flex-wrap items-center justify-between gap-2 px-3 py-2 rounded-xl border" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                                                <div class="min-w-0 flex-1">
                                                                    <p class="text-sm font-medium truncate" style="color: var(--on-surface);">
                                                                        {{ e($sub['name']) }}
                                                                    </p>
                                                                </div>
                                                                <div class="flex flex-wrap items-center gap-2">
                                                                    @if($sub['uploaded'])
                                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border border-current/20" style="background: var(--success-container); color: var(--on-success-container);">
                                                                            <i class="fas fa-upload text-[10px]" aria-hidden="true"></i>
                                                                            Uploaded
                                                                        </span>
                                                                        @if((int) $sub['status'] === 1)
                                                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border border-current/20" style="background: var(--success-container); color: var(--on-success-container);">
                                                                                <i class="fas fa-check text-[10px]" aria-hidden="true"></i>
                                                                                Approved
                                                                            </span>
                                                                        @elseif((int) $sub['status'] === 3)
                                                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border border-current/20" style="background: var(--error-container); color: var(--on-error-container);">
                                                                                <i class="fas fa-times text-[10px]" aria-hidden="true"></i>
                                                                                Rejected
                                                                            </span>
                                                                        @else
                                                                            <span class="badge-warning inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold">
                                                                                <i class="fas fa-clock text-[10px] opacity-80" aria-hidden="true"></i>
                                                                                Pending
                                                                            </span>
                                                                        @endif
                                                                    @else
                                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border" style="background: var(--surface-container-high); color: var(--on-surface-variant); border-color: var(--outline-variant);">
                                                                            <i class="fas fa-minus-circle text-[10px]" aria-hidden="true"></i>
                                                                            Not uploaded
                                                                        </span>
                                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border" style="background: var(--surface-container-high); color: var(--on-surface-variant); border-color: var(--outline-variant);">
                                                                            —
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="hidden md:block overflow-x-auto">
                                <table class="min-w-full border-separate border-spacing-0" style="border-color: var(--outline-variant);">
                                    <thead>
                                        <tr style="background: var(--surface-container-highest); border-bottom: 1px solid var(--outline-variant);">
                                            <th scope="col" class="px-4 sm:px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wide" style="color: var(--on-surface-variant); letter-spacing: 0.06em;">Teacher</th>
                                            <th scope="col" class="px-4 sm:px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wide" style="color: var(--on-surface-variant); letter-spacing: 0.06em;">Overview</th>
                                            <th scope="col" class="px-4 sm:px-6 py-3 text-right text-[11px] font-semibold uppercase tracking-wide" style="color: var(--on-surface-variant); letter-spacing: 0.06em;">Subjects</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($teacherSubjects as $index => $item)
                                        @php
                                            $teacher = $item['teacher'];
                                            $subjects = $item['subjects'];
                                            $initial = $teacher->firstname ? mb_substr(trim($teacher->firstname), 0, 1) : 'T';
                                            $imagelocation = $teacher->imagelocation ?? null;
                                            $avatarSrc = $imagelocation
                                                ? (str_starts_with($imagelocation, 'teachers/') ? asset('storage/' . $imagelocation) : asset('storage/teachers/' . $imagelocation))
                                                : asset('storage/teachers/default.png');
                                            $totalSubjects = count($subjects);
                                            $uploadedCount = collect($subjects)->where('uploaded', true)->count();
                                        @endphp
                                        <tr style="background: var(--surface-container-low); border-top: 1px solid var(--outline-variant);">
                                            <td class="px-4 sm:px-6 py-3 align-middle">
                                                <div class="flex items-center gap-3 min-w-0">
                                                    <img src="{{ $avatarSrc }}" alt="" class="w-9 h-9 rounded-full object-cover flex-shrink-0 border" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($teacher->name ?? $initial) }}&size=96'">
                                                    <div class="min-w-0">
                                                        <div class="text-sm font-medium truncate" style="color: var(--on-surface);">
                                                            {{ e($teacher->name ?? $teacher->firstname . ' ' . $teacher->lastname) }}
                                                        </div>
                                                        <div class="text-xs" style="color: var(--on-surface-variant);">
                                                            {{ $totalSubjects }} subject(s)
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 sm:px-6 py-3 align-middle">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border" style="background: var(--surface-container-high); color: var(--on-surface-variant); border-color: var(--outline-variant);">
                                                        <i class="fas fa-upload text-[10px]" aria-hidden="true"></i>
                                                        {{ $uploadedCount }} / {{ $totalSubjects }} uploaded
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-4 sm:px-6 py-3 align-middle text-right">
                                                <button type="button"
                                                        class="status-toggle-subjects inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium transition-opacity hover:opacity-90"
                                                        style="background: var(--primary-container); color: var(--on-primary-container); border-radius: 999px;"
                                                        aria-expanded="false"
                                                        aria-controls="status-subjects-{{ $index }}"
                                                        id="status-toggle-{{ $index }}">
                                                    <i class="fas fa-book-open text-xs" aria-hidden="true"></i>
                                                    <span>Subjects ({{ $totalSubjects }})</span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr id="status-subjects-{{ $index }}" class="status-subjects-row hidden" aria-hidden="true">
                                            <td colspan="3" class="px-4 sm:px-6 pb-4 pt-0 align-top">
                                                <div class="mt-2 rounded-2xl border overflow-hidden" style="border-color: var(--outline-variant); background: var(--surface-container);">
                                                    <div class="px-3 sm:px-4 py-2 border-b" style="border-color: var(--outline-variant);">
                                                        <p class="text-[11px] font-semibold uppercase tracking-wider" style="color: var(--on-surface-variant); letter-spacing: 0.06em;">
                                                            Subject status — {{ e($teacher->name ?? $teacher->firstname . ' ' . $teacher->lastname) }}
                                                        </p>
                                                    </div>
                                                    <div class="overflow-x-auto">
                                                        <table class="min-w-full text-sm">
                                                            <thead>
                                                            <tr class="border-b" style="border-color: var(--outline-variant); background: var(--surface-container-high);">
                                                                <th class="px-3 sm:px-4 py-2 text-left text-xs font-medium" style="color: var(--on-surface-variant);">Subject</th>
                                                                <th class="px-3 sm:px-4 py-2 text-left text-xs font-medium" style="color: var(--on-surface-variant);">Upload status</th>
                                                                <th class="px-3 sm:px-4 py-2 text-left text-xs font-medium" style="color: var(--on-surface-variant);">Approval status</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody class="divide-y" style="border-color: var(--outline-variant);">
                                                            @foreach($subjects as $sub)
                                                                <tr style="background: var(--surface-container-lowest);">
                                                                    <td class="px-3 sm:px-4 py-2 align-middle">
                                                                                <span class="text-sm font-medium" style="color: var(--on-surface);">
                                                                                    {{ e($sub['name']) }}
                                                                                </span>
                                                                    </td>
                                                                    <td class="px-3 sm:px-4 py-2 align-middle">
                                                                        @if($sub['uploaded'])
                                                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border border-current/20" style="background: var(--success-container); color: var(--on-success-container);">
                                                                                        <i class="fas fa-upload text-[10px]" aria-hidden="true"></i>
                                                                                        Uploaded
                                                                                    </span>
                                                                        @else
                                                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border" style="background: var(--surface-container-high); color: var(--on-surface-variant); border-color: var(--outline-variant);">
                                                                                        <i class="fas fa-minus-circle text-[10px]" aria-hidden="true"></i>
                                                                                        Not uploaded
                                                                                    </span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="px-3 sm:px-4 py-2 align-middle">
                                                                        @if($sub['uploaded'])
                                                                            @if((int) $sub['status'] === 1)
                                                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border border-current/20" style="background: var(--success-container); color: var(--on-success-container);">
                                                                                            <i class="fas fa-check text-[10px]" aria-hidden="true"></i>
                                                                                            Approved
                                                                                        </span>
                                                                            @elseif((int) $sub['status'] === 3)
                                                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border border-current/20" style="background: var(--error-container); color: var(--on-error-container);">
                                                                                            <i class="fas fa-times text-[10px]" aria-hidden="true"></i>
                                                                                            Rejected
                                                                                        </span>
                                                                            @else
                                                                                <span class="badge-warning inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold">
                                                                                            <i class="fas fa-clock text-[10px] opacity-80" aria-hidden="true"></i>
                                                                                            Pending
                                                                                        </span>
                                                                            @endif
                                                                        @else
                                                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border" style="background: var(--surface-container-high); color: var(--on-surface-variant); border-color: var(--outline-variant);">
                                                                                        —
                                                                                    </span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-16 md:py-24 px-6">
                            <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center" style="border-radius: 16px;">
                                <i class="fas fa-chalkboard-teacher text-3xl" aria-hidden="true"></i>
                            </div>
                            <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No teachers assigned</h2>
                            <p class="text-sm text-center max-w-sm" style="color: var(--on-surface-variant);">No teachers are assigned to {{ e($class) }}. Assign teachers to this class to see their subjects and result status here.</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </main>

    @if($hasFilters && !empty($teacherSubjects))
        @push('scripts')
            <script>
                (function () {
                    document.querySelectorAll('.status-toggle-subjects').forEach(function (btn) {
                        btn.addEventListener('click', function () {
                            const id = btn.getAttribute('aria-controls');
                            const panel = document.getElementById(id);
                            if (!panel) return;
                            const isHidden = panel.classList.contains('hidden');

                            // Close all panels (mobile and desktop)
                            document.querySelectorAll('.status-subjects-row, .status-subjects-panel').forEach(function (p) {
                                p.classList.add('hidden');
                                p.setAttribute('aria-hidden', 'true');
                            });
                            document.querySelectorAll('.status-toggle-subjects').forEach(function (b) {
                                b.setAttribute('aria-expanded', 'false');
                            });

                            // Open the requested one (if it was hidden)
                            if (isHidden) {
                                panel.classList.remove('hidden');
                                panel.setAttribute('aria-hidden', 'false');
                                btn.setAttribute('aria-expanded', 'true');
                            }
                        });
                    });
                })();
            </script>
        @endpush
    @endif
@endsection
