@php use Carbon\Carbon; @endphp
@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <div class="mb-4 sm:mb-6 w-fit">
                <a href="{{ route('admin.left_school') }}" class="inline-flex items-center gap-2 text-sm font-medium transition-opacity hover:opacity-80" style="color: var(--on-surface-variant);">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    Back to Left School
                </a>
            </div>

            <header class="mb-6 lg:mb-8 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-normal tracking-tight mb-1.5" style="color: var(--on-surface); letter-spacing: -0.02em;">
                        Left school in {{ e($year) }}
                    </h1>
                    <p class="text-sm sm:text-base font-normal" style="color: var(--on-surface-variant);">
                        View students who left school in this year. Search by name or reg. number.
                    </p>
                </div>
            </header>

            <div class="rounded-3xl p-4 sm:p-5 lg:p-6 mb-6 overflow-hidden min-w-0 w-full" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                <form method="GET" action="{{ route('admin.left_school.view') }}" class="space-y-4 sm:space-y-5">
                    <input type="hidden" name="year" value="{{ e(old('year', $year)) }}">
                    <div class="grid grid-cols-12 gap-4 min-w-0">
                        <div class="col-span-12 form-group min-w-0">
                            <label for="left-school-search" class="form-label">Search by name or reg. number</label>
                            <input type="text" id="left-school-search" name="search" value="{{ e(old('search', $search ?? '')) }}" placeholder="Search..." class="form-input w-full min-w-0">
                            <p id="search-error" class="form-error mt-1 text-sm {{ $errors->has('search') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('search') }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2 min-w-0" style="border-top: 1px solid var(--outline-variant); padding-top: 1.25rem;">
                        <a href="{{ route('admin.left_school.view', ['year' => $year]) }}" class="btn-secondary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 sm:min-w-[120px]" style="border-radius: 12px;">
                            <i class="fas fa-times text-sm" aria-hidden="true"></i>
                            Clear
                        </a>
                        <button type="submit" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;">
                            <i class="fas fa-search text-sm" aria-hidden="true"></i>
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                @if($students->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 px-6">
                        <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center">
                            <i class="fas fa-user-graduate text-3xl" aria-hidden="true"></i>
                        </div>
                        @if(!empty($search))
                            <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No students found</h2>
                            <p class="text-sm text-center max-w-sm mb-6" style="color: var(--on-surface-variant);">No students who left in {{ e($year) }} match your search. Try a different term or clear the search.</p>
                            <div class="w-full flex justify-center">
                                <a href="{{ route('admin.left_school.view', ['year' => $year]) }}" class="btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] max-w-[280px] w-full rounded-xl font-medium text-sm transition-opacity hover:opacity-90" style="border-radius: 12px;">Clear search</a>
                            </div>
                        @else
                            <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No students for this year</h2>
                            <p class="text-sm text-center max-w-sm mb-6" style="color: var(--on-surface-variant);">There are no students recorded as having left school in {{ e($year) }}.</p>
                            <div class="flex justify-center w-full">
                                <a href="{{ route('admin.left_school') }}" class="btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] rounded-xl font-medium text-sm transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px; width: fit-content;">
                                    <i class="fas fa-arrow-left text-sm" aria-hidden="true"></i>
                                    Back to Left School
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0 border-x border-b" style="border-color: var(--outline-variant);">
                        <ul class="divide-y divide-[var(--outline-variant)] min-w-[320px] sm:min-w-0" role="list">
                            <li class="flex flex-wrap sm:flex-nowrap items-stretch sm:items-center gap-x-2 sm:gap-x-4 gap-y-1 px-4 sm:px-6 py-3" style="background: var(--surface-container); border-color: var(--outline-variant);">
                                <span class="text-xs font-medium w-6 sm:w-8 flex-shrink-0 self-center" style="color: var(--on-surface-variant);">#</span>
                                <span class="w-10 flex-shrink-0" aria-hidden="true"></span>
                                <span class="text-xs font-medium flex-1 min-w-0 order-1" style="color: var(--on-surface-variant);">Name</span>
                                <span class="text-xs font-medium flex-shrink-0 w-full sm:w-28 text-right sm:text-left order-2 sm:ml-4" style="color: var(--on-surface-variant);">Left school date</span>
                            </li>
                            @foreach($students as $index => $s)
                                @php
                                    $fullName = trim(($s->firstname ?? '') . ' ' . ($s->lastname ?? '') . ' ' . ($s->othername ?? ''));
                                    $leftDate = $s->left_school_date
                                        ? (is_object($s->left_school_date) ? $s->left_school_date->format('j M Y') : Carbon::parse($s->left_school_date)->format('j M Y'))
                                        : '—';
                                    $avatarSrc = $s->imagelocation
                                        ? (str_starts_with($s->imagelocation, 'students/') ? asset('storage/' . $s->imagelocation) : asset('storage/students/' . $s->imagelocation))
                                        : asset('storage/students/default.png');
                                    $avatarInitial = $fullName ? mb_substr($fullName, 0, 1) : 'S';
                                @endphp
                                <li class="flex flex-wrap sm:flex-nowrap items-stretch sm:items-center gap-x-2 sm:gap-x-4 gap-y-2 px-4 sm:px-6 py-4 transition-colors" style="background: var(--surface-container-lowest);">
                                    <span class="text-sm font-medium w-6 sm:w-8 flex-shrink-0 self-center" style="color: var(--on-surface-variant);">{{ ($students->currentPage() - 1) * $students->perPage() + $index + 1 }}</span>
                                    <img src="{{ $avatarSrc }}" alt="" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2 self-center" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($avatarInitial) }}&size=80'">
                                    <div class="min-w-0 flex-1 order-1">
                                        <p class="text-sm font-medium break-words sm:truncate" style="color: var(--on-surface);">
                                            <a href="{{ route('admin.students.show', $s->id) }}" class="transition-opacity hover:opacity-80" style="color: var(--primary);">{{ $fullName ?: '—' }}</a>
                                        </p>
                                        <p class="text-xs break-words sm:truncate mt-0.5" style="color: var(--on-surface-variant);">{{ $s->reg_number ?? '' }}</p>
                                    </div>
                                    <div class="flex-shrink-0 w-full sm:w-28 flex justify-end sm:justify-start order-2 self-center sm:ml-4 mt-1 sm:mt-0">
                                        <span class="text-sm sm:text-xs font-medium sm:inline-flex sm:px-1.5 sm:py-0.5 sm:rounded-lg max-w-full" style="color: var(--on-surface-variant);" title="{{ $leftDate }}">{{ $leftDate }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @if($students->hasPages())
                        <div class="px-5 sm:px-6 py-4" style="border-top: 1px solid var(--outline-variant); background: var(--surface-container-low);">
                            <x-pagination :paginator="$students" />
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </main>
@endsection
