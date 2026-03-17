@php use Carbon\Carbon; @endphp
@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div
            class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <div class="mb-4 sm:mb-6 w-fit">
                <a href="{{ route('admin.graduated') }}" class="inline-flex items-center gap-2 text-sm font-medium transition-opacity hover:opacity-80" style="color: var(--on-surface-variant);">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    Back to Graduated
                </a>
            </div>

            <header class="mb-6 lg:mb-8 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-normal tracking-tight mb-1.5"
                        style="color: var(--on-surface); letter-spacing: -0.02em;">
                        Graduated in {{ e($year) }}
                    </h1>
                    <p class="text-sm sm:text-base font-normal" style="color: var(--on-surface-variant);">
                        View students who graduated in this year. Search by name or reg. number.
                    </p>
                </div>
            </header>

            <div class="rounded-3xl p-4 sm:p-5 lg:p-6 mb-6 overflow-hidden min-w-0 w-full" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                <form method="GET" action="{{ route('admin.graduated.view') }}" class="space-y-4 sm:space-y-5">
                    <input type="hidden" name="year" value="{{ e(old('year', $year)) }}">
                    <div class="grid grid-cols-12 gap-4 min-w-0">
                        <div class="col-span-12 form-group min-w-0">
                            <label for="graduated-search" class="form-label">Search by name or reg. number</label>
                            <input type="text" id="graduated-search" name="search" value="{{ e(old('search', $search ?? '')) }}" placeholder="Search..." class="form-input w-full min-w-0">
                            <p id="search-error" class="form-error mt-1 text-sm {{ $errors->has('search') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('search') }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2 min-w-0" style="border-top: 1px solid var(--outline-variant); padding-top: 1.25rem;">
                        <a href="{{ route('admin.graduated.view', ['year' => $year]) }}" class="btn-secondary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 sm:min-w-[120px]" style="border-radius: 12px;">
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

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                @if($students->isEmpty())
                    <div class="flex flex-col items-center justify-center min-h-[min(360px,50vh)] py-12 sm:py-16 px-4 sm:px-6">
                        <div class="rounded-3xl p-8 sm:p-12 text-center w-full max-w-lg">
                            @if(!empty($search))
                                <div class="flex flex-col items-center justify-center">
                                    <div class="dashboard-stat-icon dashboard-stat-icon--blue w-24 h-24 rounded-2xl mx-auto mb-6 flex items-center justify-center" style="border-radius: 16px;">
                                        <i class="fas fa-search text-4xl" aria-hidden="true"></i>
                                    </div>
                                    <h2 class="text-xl font-normal tracking-tight mb-2" style="color: var(--on-surface);">No students found</h2>
                                    <p class="text-sm font-normal mb-6 leading-relaxed" style="color: var(--on-surface-variant);">No graduates in <strong style="color: var(--on-surface);">{{ e($year) }}</strong> match your search. Try a different term or clear the search.</p>

                                    <div class="flex justify-center">
                                        <a href="{{ route('admin.graduated.view', ['year' => $year]) }}" class="btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] rounded-xl font-medium text-sm transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;">
                                            <i class="fas fa-times text-sm" aria-hidden="true"></i>
                                            <span>Clear search</span>
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center">
                                    <div class="dashboard-stat-icon dashboard-stat-icon--blue w-24 h-24 rounded-2xl mx-auto mb-6 flex items-center justify-center" style="border-radius: 16px;">
                                        <i class="fas fa-graduation-cap text-4xl" aria-hidden="true"></i>
                                    </div>
                                    <h2 class="text-xl font-normal tracking-tight mb-2" style="color: var(--on-surface);">No graduates for this year</h2>
                                    <p class="text-sm font-normal mb-6 leading-relaxed" style="color: var(--on-surface-variant);">There are no students recorded as graduated in <strong style="color: var(--on-surface);">{{ e($year) }}</strong>.</p>

                                    <div class="flex justify-center">
                                        <a href="{{ route('admin.graduated') }}" class="btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] rounded-xl font-medium text-sm transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;">
                                            <i class="fas fa-arrow-left text-sm" aria-hidden="true"></i>
                                            <span>Back to Graduated</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0 border-x border-b md:border-x md:border-b" style="border-color: var(--outline-variant);">
                        <ul class="flex flex-col gap-3 md:gap-0 md:divide-y divide-[var(--outline-variant)] p-4 sm:px-6 md:p-0 list-none min-w-0" role="list">
                            <li class="hidden md:flex items-center gap-3 sm:gap-4 px-5 sm:px-6 py-3" style="background: var(--surface-container); border-color: var(--outline-variant);">
                                <span class="text-xs font-medium w-6 sm:w-8 flex-shrink-0" style="color: var(--on-surface-variant);">#</span>
                                <span class="w-10 flex-shrink-0" aria-hidden="true"></span>
                                <span class="text-xs font-medium flex-1 min-w-0" style="color: var(--on-surface-variant);">Name</span>
                                <span class="text-xs font-medium flex-shrink-0 w-28 text-left" style="color: var(--on-surface-variant);">Graduate date</span>
                            </li>
                            @foreach($students as $index => $s)
                                @php
                                    $fullName = trim(($s->firstname ?? '') . ' ' . ($s->lastname ?? '') . ' ' . ($s->othername ?? ''));
                                    $gradDate = $s->graduation_date
                                        ? (is_object($s->graduation_date) ? $s->graduation_date->format('j M Y') : Carbon::parse($s->graduation_date)->format('j M Y'))
                                        : '—';
                                    $avatarSrc = $s->imagelocation
                                        ? (str_starts_with($s->imagelocation, 'students/') ? asset('storage/' . $s->imagelocation) : asset('storage/students/' . $s->imagelocation))
                                        : asset('storage/students/default.png');
                                    $avatarInitial = $fullName ? mb_substr($fullName, 0, 1) : 'S';
                                @endphp
                                <li class="flex flex-col gap-0 rounded-2xl border p-4 md:rounded-none md:border-0 md:border-b md:border-t-0 md:flex-row md:items-center md:gap-4 md:py-4 md:px-5 lg:px-6 md:min-w-0 md:p-0 transition-[background-color] duration-200" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                    <div class="flex items-center gap-3 md:contents">
                                        <span class="text-sm font-medium w-6 sm:w-8 flex-shrink-0 md:block" style="color: var(--on-surface-variant);">{{ ($students->currentPage() - 1) * $students->perPage() + $index + 1 }}</span>
                                        <img src="{{ $avatarSrc }}" alt="" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($avatarInitial) }}&size=80'">
                                        <div class="min-w-0 flex-1 md:min-w-0 md:flex-1">
                                            <span class="text-xs font-medium md:sr-only" style="color: var(--on-surface-variant);">Name</span>
                                            <p class="text-sm font-medium truncate" style="color: var(--on-surface);">
                                                <a href="{{ route('admin.students.show', $s->id) }}" class="transition-opacity hover:opacity-80" style="color: var(--primary);">{{ $fullName ?: '—' }}</a>
                                            </p>
                                            <p class="text-xs truncate mt-0.5" style="color: var(--on-surface-variant);">{{ $s->reg_number ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-3 border-t md:border-t-0 md:mt-0 md:pt-0 flex flex-wrap items-baseline gap-x-4 gap-y-1 md:contents" style="border-color: var(--outline-variant);">
                                        <span class="w-full text-xs font-medium mb-1 md:sr-only" style="color: var(--on-surface-variant);">Graduate date</span>
                                        <span class="text-xs md:flex-shrink-0 md:w-28 font-medium" style="color: var(--on-surface-variant);" title="{{ $gradDate }}">{{ $gradDate }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @if($students->hasPages())
                        <div class="px-5 sm:px-6 py-4" style="border-top: 1px solid var(--outline-variant); background: var(--surface-container-low);">
                            <x-pagination :paginator="$students"/>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </main>
@endsection
