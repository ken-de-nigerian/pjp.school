@php use App\Models\Student; @endphp
@extends('layouts.app', ['title' => 'My classes'])

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Teacher classes and students"
                pill="Teacher"
                :title="$students !== null ? e($selectedClass) : 'Students / Classes'"
                :description="$students !== null ? 'Students in this class - search and export below.' : 'Open a class to see students assigned to you.'"
            >
                @if($students !== null)
                    <x-slot name="above">
                        <a href="{{ route('teacher.class.index') }}" class="admin-page-hero__back mb-2 sm:mb-0">
                            <i class="fas fa-arrow-left" aria-hidden="true"></i>
                            Change class
                        </a>
                    </x-slot>
                @endif
            </x-admin.hero-page>

            @if($students === null)
                <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl p-5 sm:p-6 lg:p-8" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-6">
                        @forelse($classesWithCounts as $c)
                            <div class="h-full min-h-[200px]">
                                <div class="relative flex flex-col h-full overflow-hidden rounded-2xl transition-all duration-200" style="background: var(--surface-container-lowest); border: 1px solid var(--outline-variant);">
                                    <div class="p-5 sm:p-6 flex-1 flex flex-col items-center justify-center gap-3 text-center">
                                        <div
                                            class="dashboard-quick-icon dashboard-quick-icon--blue w-14 h-14 rounded-2xl flex-shrink-0 flex items-center justify-center"
                                            style="border-radius: 16px;">
                                            <i class="fas fa-chalkboard text-xl" aria-hidden="true"></i>
                                        </div>
                                        <div>
                                            <h2 class="text-base sm:text-lg font-medium mb-0"
                                                style="color: var(--on-surface);">
                                                {{ e($c['class_name']) }}
                                            </h2>
                                            <p class="text-2xl sm:text-3xl font-normal tracking-tight mb-0"
                                               style="color: var(--on-surface);">
                                                {{ $c['user_count'] ?? 0 }}
                                            </p>
                                            <p class="text-sm font-normal mb-0"
                                               style="color: var(--on-surface-variant);">
                                                Student(s)
                                            </p>
                                        </div>
                                    </div>

                                    <div class="px-4 pb-4 sm:px-5 sm:pb-5 pt-0" style="border-top: 1px solid var(--outline-variant);">
                                        <a href="{{ route('teacher.class.index', ['class' => $c['class_name']]) }}"
                                           class="btn-primary flex items-center justify-center gap-2 px-6 py-3 rounded-2xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98] w-full"
                                           style="background: var(--primary); color: var(--on-primary); margin-top: 15px;">
                                            <i class="fas fa-door-open" aria-hidden="true"></i>
                                            <span>Open class</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div
                                class="col-span-full flex-1 flex flex-col items-center justify-center min-h-[min(400px,50vh)] py-12 sm:py-16">
                                <div class="rounded-3xl p-8 sm:p-12 text-center w-full max-w-lg"
                                     style="background: var(--surface-container-lowest); border: 1px solid var(--outline-variant);">
                                    <div
                                        class="dashboard-stat-icon dashboard-stat-icon--blue w-24 h-24 rounded-2xl mx-auto mb-6 flex items-center justify-center"
                                        style="border-radius: 16px;">
                                        <i class="fas fa-chalkboard text-4xl" aria-hidden="true"></i>
                                    </div>
                                    <h2 class="text-xl font-normal tracking-tight mb-2"
                                        style="color: var(--on-surface);">No classes found</h2>
                                    <p class="text-sm font-normal mb-0" style="color: var(--on-surface-variant);">Add a
                                        class using the button above, then assign students to classes when adding or
                                        editing students.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

            @if($students !== null)
                <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    <div class="flex flex-col gap-3 px-4 sm:px-6 py-3 border-b sm:flex-row sm:flex-wrap sm:items-center sm:justify-between" style="border-color: var(--outline-variant); background: var(--surface-container-low);">
                        <div
                            class="flex flex-col sm:flex-row sm:items-center gap-2 w-full sm:w-auto sm:flex-1 sm:max-w-md">
                            <form method="GET" action="{{ route('teacher.class.index') }}" class="flex items-center gap-2 flex-1 min-w-0">
                                <input type="hidden" name="class" value="{{ e($selectedClass) }}">
                                <label for="classes-students-search" class="sr-only">Search students</label>
                                <div class="flex-1 min-w-0 flex items-center gap-2 rounded-xl pl-3 pr-2 py-2 border transition-colors" style="background: var(--surface-container); border-color: var(--outline-variant);">
                                    <i class="fas fa-search text-sm flex-shrink-0" style="color: var(--on-surface-variant);"></i>
                                    <input type="search" id="classes-students-search" name="q" value="{{ e($searchQuery ?? '') }}" placeholder="Search by name or reg. number..." class="flex-1 min-w-0 border-0 bg-transparent py-1 text-sm focus:ring-0 focus:outline-none" style="color: var(--on-surface);" autocomplete="off">
                                    @if(!empty($searchQuery))
                                        <a href="{{ route('teacher.class.index', ['class' => $selectedClass]) }}" class="flex-shrink-0 p-1 rounded-lg transition-opacity hover:opacity-80" style="color: var(--on-surface-variant);" aria-label="Clear search"><i class="fas fa-times text-xs"></i></a>
                                    @endif
                                </div>
                                <button type="submit" class="flex-shrink-0 px-4 py-2 rounded-xl text-sm font-medium transition-opacity hover:opacity-90" style="background: var(--primary); color: var(--on-primary);">Search</button>
                            </form>
                        </div>
                    </div>

                    @if($students->isEmpty())
                        <div class="flex flex-col items-center justify-center py-16 px-6">
                            <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center" style="border-radius: 16px;">
                                <i class="fas fa-user-graduate text-3xl" aria-hidden="true"></i>
                            </div>
                            @if(!empty($searchQuery))
                                <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No students found</h2>
                                <p class="text-sm text-center max-w-sm mb-6" style="color: var(--on-surface-variant);">No students in {{ e($selectedClass) }} match "{{ e($searchQuery) }}". Try a different search or clear the search.</p>
                                <a href="{{ route('teacher.class.index', ['class' => $selectedClass]) }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-medium text-sm transition-opacity hover:opacity-90" style="background: var(--primary); color: var(--on-primary); border-radius: 12px;">Clear search</a>
                            @else
                                <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No students in this class</h2>
                                <p class="text-sm text-center max-w-sm mb-6" style="color: var(--on-surface-variant);">There are no students in {{ e($selectedClass) }} assigned to you.</p>
                                <a href="{{ route('teacher.class.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-medium text-sm transition-opacity hover:opacity-90" style="background: var(--primary); color: var(--on-primary); border-radius: 12px;">Change Class</a>
                            @endif
                        </div>
                    @else
                        <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0 border-x border-b md:border-x md:border-b" style="border-color: var(--outline-variant);">
                            <ul class="flex flex-col gap-3 md:gap-0 md:divide-y divide-[var(--outline-variant)] p-4 sm:px-6 md:p-0 list-none min-w-0" role="list">
                                <li class="hidden md:flex items-center gap-3 sm:gap-4 px-5 sm:px-6 py-3" style="background: var(--surface-container); border-color: var(--outline-variant);">
                                    <span class="text-xs font-medium w-6 flex-shrink-0" style="color: var(--on-surface-variant);">#</span>
                                    <span class="w-10 flex-shrink-0" aria-hidden="true"></span>
                                    <span class="text-xs font-medium flex-1 min-w-0" style="color: var(--on-surface-variant);">Name</span>
                                    <span class="text-xs font-medium flex-shrink-0 w-16" style="color: var(--on-surface-variant);">Class</span>
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
                                            <span class="text-sm font-medium w-6 flex-shrink-0 md:block" style="color: var(--on-surface-variant);">{{ ($students->currentPage() - 1) * $students->perPage() + $index + 1 }}</span>
                                            <img src="{{ $avatarSrc }}" alt="" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($avatarInitial) }}&size=80'">
                                            <div class="min-w-0 flex-1 md:min-w-0 md:flex-1">
                                                <span class="text-xs font-medium md:sr-only" style="color: var(--on-surface-variant);">Name</span>
                                                <p class="text-sm font-medium truncate" style="color: var(--on-surface);">
                                                    {{ $fullName ?: '—' }}
                                                </p>
                                                <p class="text-xs truncate mt-0.5" style="color: var(--on-surface-variant);">{{ $s->reg_number ?? '' }}</p>
                                            </div>
                                        </div>

                                        <div class="mt-3 pt-3 border-t md:border-t-0 md:mt-0 md:pt-0 flex flex-wrap items-baseline gap-x-4 gap-y-1 md:contents" style="border-color: var(--outline-variant);">
                                            <span class="w-full text-xs font-medium mb-1 md:sr-only" style="color: var(--on-surface-variant);">Class</span>
                                            <span class="text-xs md:flex-shrink-0 md:w-24"><span class="md:sr-only" style="color: var(--on-surface-variant);">Class </span><span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium" style="background: var(--surface-container-high); color: var(--on-surface-variant);">{{ e($s->class ?? '') }}</span></span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        @if($students->hasPages())
                            <div class="px-5 sm:px-6 py-4"
                                 style="border-top: 1px solid var(--outline-variant); background: var(--surface-container-low);">
                                <x-pagination :paginator="$students"/>
                            </div>
                        @endif
                    @endif
                </div>
            @endif
        </div>
    </main>
@endsection
