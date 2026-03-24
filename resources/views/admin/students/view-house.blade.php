@extends('layouts.app', ['title' => 'House students'])

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="House members"
                pill="Admin"
                :title="'House: ' . e($house)"
                description="View and manage students in this house. Filter by class or search by name or reg. number."
            >
                <x-slot name="above">
                    <a href="{{ route('admin.students.houses') }}" class="admin-page-hero__back mb-2 sm:mb-0">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Back to houses
                    </a>
                </x-slot>
            </x-admin.hero-page>

            <div class="rounded-3xl p-4 sm:p-5 lg:p-6 mb-6 overflow-hidden min-w-0 w-full" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                <form method="GET" action="{{ route('admin.students.houses.view') }}" class="space-y-4 sm:space-y-5">
                    <input type="hidden" name="house" value="{{ e(old('house', $house)) }}">
                    <p id="house-error" class="form-error text-sm {{ $errors->has('house') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('house') }}</p>

                    <div class="grid grid-cols-12 gap-4 min-w-0">
                        <div class="col-span-12 sm:col-span-6 form-group min-w-0">
                            <label for="house-search" class="form-label">Search by name or reg. number</label>
                            <input type="text" id="house-search" name="search" value="{{ e(old('search', $search ?? '')) }}" placeholder="Search..." class="form-input w-full min-w-0">
                            <p id="search-error" class="form-error mt-1 text-sm {{ $errors->has('search') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('search') }}</p>
                        </div>

                        <div class="col-span-12 sm:col-span-6 form-group min-w-0">
                            <label for="house-class" class="form-label">Class</label>
                            <select id="house-class" name="class" class="form-select w-full min-w-0">
                                <option value="">All classes</option>
                                @foreach($getClasses as $c)
                                    @php $className = is_object($c) ? $c->class_name : $c; @endphp
                                    <option value="{{ e($className) }}" {{ old('class', $classFilter ?? '') === $className ? 'selected' : '' }}>{{ e($className) }}</option>
                                @endforeach
                            </select>
                            <p id="class-error" class="form-error mt-1 text-sm {{ $errors->has('class') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('class') }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2 min-w-0" style="border-top: 1px solid var(--outline-variant); padding-top: 1.25rem;">
                        <a href="{{ route('admin.students.houses.view', ['house' => $house]) }}" class="btn-secondary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 sm:min-w-[120px]" style="border-radius: 12px;">
                            <i class="fas fa-times text-sm" aria-hidden="true"></i>
                            Clear
                        </a>
                        <button type="submit" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" data-preloader style="border-radius: 12px;">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                @if($students->isEmpty())
                    <div class="flex flex-col items-center justify-center min-h-[min(360px,50vh)] py-12 sm:py-16 px-4 sm:px-6">
                        <div class="rounded-3xl p-8 sm:p-12 text-center w-full max-w-lg">
                            @if(!empty($search) || !empty($classFilter))
                                <div class="flex flex-col items-center justify-center">
                                    <div class="dashboard-stat-icon dashboard-stat-icon--blue w-24 h-24 rounded-2xl mx-auto mb-6 flex items-center justify-center" style="border-radius: 16px;">
                                        <i class="fas fa-search text-4xl" aria-hidden="true"></i>
                                    </div>
                                    <h2 class="text-xl font-normal tracking-tight mb-2" style="color: var(--on-surface);">No students found</h2>
                                    <p class="text-sm font-normal mb-6 leading-relaxed" style="color: var(--on-surface-variant);">No students in <strong style="color: var(--on-surface);">{{ e($house) }}</strong> match your filters. Try a different search or clear filters.</p>

                                    <div class="flex justify-center">
                                        <a href="{{ route('admin.students.houses.view', ['house' => $house]) }}" class="btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] rounded-xl font-medium text-sm transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;">
                                            <i class="fas fa-times text-sm" aria-hidden="true"></i>
                                            <span>Clear filters</span>
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center">
                                    <div class="dashboard-stat-icon dashboard-stat-icon--blue w-24 h-24 rounded-2xl mx-auto mb-6 flex items-center justify-center" style="border-radius: 16px;">
                                        <i class="fas fa-users text-4xl" aria-hidden="true"></i>
                                    </div>
                                    <h2 class="text-xl font-normal tracking-tight mb-2" style="color: var(--on-surface);">No students in this house</h2>
                                    <p class="text-sm font-normal mb-6 leading-relaxed" style="color: var(--on-surface-variant);">There are no students assigned to <strong style="color: var(--on-surface);">{{ e($house) }}</strong>. Assign houses when adding or editing students.</p>

                                    <div class="flex justify-center">
                                        <a href="{{ route('admin.students.houses') }}" class="btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] rounded-xl font-medium text-sm transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;">
                                            <i class="fas fa-arrow-left text-sm" aria-hidden="true"></i>
                                            <span>Back to Houses</span>
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
                                <span class="text-xs font-medium flex-shrink-0 w-14 sm:w-24 text-right" style="color: var(--on-surface-variant);">Class</span>
                                <span class="text-xs font-medium flex-shrink-0 w-10 sm:w-24 text-right" style="color: var(--on-surface-variant);">Actions</span>
                            </li>
                            @foreach($students as $index => $s)
                                @php
                                    $fullName = trim(($s->firstname ?? '') . ' ' . ($s->lastname ?? '') . ' ' . ($s->othername ?? ''));
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
                                                <a href="{{ route('admin.students.show', $s) }}" class="transition-opacity hover:opacity-80" style="color: var(--primary);">{{ $fullName ?: '—' }}</a>
                                            </p>
                                            <p class="text-xs truncate mt-0.5" style="color: var(--on-surface-variant);">{{ $s->reg_number ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-3 border-t md:border-t-0 md:mt-0 md:pt-0 w-full flex flex-row items-center justify-between gap-3 md:contents" style="border-color: var(--outline-variant);">
                                        <span class="text-xs md:flex-shrink-0 md:w-24"><span class="md:sr-only" style="color: var(--on-surface-variant);">Class </span><span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium" style="background: var(--surface-container-high); color: var(--on-surface-variant);" title="{{ e($s->class ?? '') }}">{{ e($s->class ?? '') }}</span></span>
                                        <span class="md:flex-shrink-0 md:w-24 md:flex md:justify-end">
                                            <a href="{{ route('admin.students.edit', $s->id) }}" class="inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium transition-opacity hover:opacity-90 w-9 h-9 sm:w-auto sm:h-auto sm:min-w-0" style="background: var(--primary); color: var(--on-primary); border-radius: 12px;" title="Edit student" aria-label="Edit student">
                                                <i class="fas fa-pen text-sm sm:text-xs" aria-hidden="true"></i>
                                                <span class="hidden sm:inline">Edit</span>
                                            </a>
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @if($students->hasPages())
                        <div class="px-5 sm:px-6 py-4" style="border-top: 1px solid var(--outline-variant); background: var(--surface-container-low);">
                            <x-pagination :paginator="$students->withQueryString()" />
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </main>
@endsection
