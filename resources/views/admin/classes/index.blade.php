@php use App\Models\Student; @endphp
@extends('layouts.app', ['title' => 'Classes'])

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Classes and students overview"
                pill="Admin"
                :title="$students !== null ? e($selectedClass) : 'Students / Classes'"
                :description="$students !== null ? 'Students in this class — search, fees, and export below.' : 'Open a class to see students, or register a new student. Add a class if needed.'"
            >
                @if($students !== null)
                    <x-slot name="above">
                        <a href="{{ route('admin.classes') }}" class="admin-page-hero__back mb-2 sm:mb-0">
                            <i class="fas fa-arrow-left" aria-hidden="true"></i>
                            Change class
                        </a>
                    </x-slot>
                @endif

                @if($students === null)
                    <x-slot name="actions">
                        <div class="flex flex-col sm:flex-row flex-wrap gap-2 w-full lg:w-auto lg:flex-shrink-0">
                            @can('create', Student::class)
                                <a href="{{ route('admin.students.create') }}"
                                   class="admin-dashboard-hero__btn w-full sm:w-auto justify-center min-h-[44px] sm:min-h-0">
                                    <i class="fas fa-user-plus text-xs sm:text-sm" aria-hidden="true"></i>
                                    <span>Register student</span>
                                </a>
                            @endcan
                            @if(Route::has('admin.add.class'))
                                <button type="button" id="add-class-btn"
                                        class="admin-dashboard-hero__btn admin-dashboard-hero__btn--primary w-full sm:w-auto justify-center min-h-[44px] sm:min-h-0"
                                        aria-haspopup="dialog" aria-expanded="false" aria-controls="add-class-modal">
                                    <i class="fas fa-plus text-[10px] sm:text-xs" aria-hidden="true"></i>
                                    <span>Add class</span>
                                </button>
                            @endif
                        </div>
                    </x-slot>
                @endif
            </x-admin.hero-page>

            @if($students === null)
                <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl p-5 sm:p-6 lg:p-8" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-6">
                        @forelse($classesWithCounts as $c)
                            <div class="h-full min-h-[200px]">
                                <div
                                    class="relative flex flex-col h-full overflow-hidden rounded-2xl transition-all duration-200"
                                    style="background: var(--surface-container-lowest); border: 1px solid var(--outline-variant);">
                                    <button type="button"
                                            class="absolute top-3 right-3 w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center hover:bg-[rgba(0,0,0,0.04)]"
                                            style="color: var(--on-surface-variant);"
                                            aria-haspopup="menu"
                                            aria-expanded="false"
                                            data-class-menu-toggle="{{ $c['id'] }}">
                                        <i class="fas fa-ellipsis-vertical text-sm" aria-hidden="true"></i>
                                        <span class="sr-only">Class actions</span>
                                    </button>
                                    <div class="absolute top-11 right-3 w-40 rounded-xl shadow-lg border text-sm bg-[var(--surface-container-lowest)] z-10 hidden"
                                         style="border-color: var(--outline-variant);"
                                         role="menu"
                                         data-class-menu="{{ $c['id'] }}">
                                        <button type="button"
                                                class="w-full flex items-center gap-2 px-3 py-2 text-left hover:bg-[color-mix(in_srgb,var(--on-surface)_6%,transparent)]"
                                                style="color: var(--on-surface);"
                                                data-class-id="{{ $c['id'] }}"
                                                data-class-name="{{ e($c['class_name']) }}"
                                                data-class-students="{{ (int)($c['user_count'] ?? 0) }}"
                                                onclick="openEditClassModal(this)">
                                            <i class="fas fa-pen text-xs" aria-hidden="true"></i>
                                            <span>Edit class</span>
                                        </button>
                                        <button type="button"
                                                class="w-full flex items-center gap-2 px-3 py-2 text-left hover:bg-[color-mix(in_srgb,var(--on-surface)_6%,transparent)]"
                                                style="color: var(--on-error-container);"
                                                data-class-id="{{ $c['id'] }}"
                                                data-class-name="{{ e($c['class_name']) }}"
                                                data-class-students="{{ (int)($c['user_count'] ?? 0) }}"
                                                onclick="openDeleteClassModal(this)">
                                            <i class="fas fa-trash-alt text-xs" aria-hidden="true"></i>
                                            <span>Delete class</span>
                                        </button>
                                    </div>

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
                                        <a href="{{ route('admin.classes', ['class' => $c['class_name']]) }}"
                                           class="btn-primary flex items-center justify-center gap-2 px-6 py-3 rounded-2xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98] shadow-sm w-full"
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
                                     style="background: var(--surface-container-lowest); border: 1px solid var(--outline-variant); box-shadow: var(--elevation-1);">
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
                <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden"
                     style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                    <div
                        class="flex flex-col gap-3 px-4 sm:px-6 py-3 border-b sm:flex-row sm:flex-wrap sm:items-center sm:justify-between"
                        style="border-color: var(--outline-variant); background: var(--surface-container-low);">
                        <div
                            class="flex flex-col sm:flex-row sm:items-center gap-2 w-full sm:w-auto sm:flex-1 sm:max-w-md">
                            <form method="GET" action="{{ route('admin.classes') }}"
                                  class="flex items-center gap-2 flex-1 min-w-0">
                                <input type="hidden" name="class" value="{{ e($selectedClass) }}">
                                <label for="classes-students-search" class="sr-only">Search students</label>
                                <div
                                    class="flex-1 min-w-0 flex items-center gap-2 rounded-xl pl-3 pr-2 py-2 border transition-colors"
                                    style="background: var(--surface-container); border-color: var(--outline-variant);">
                                    <i class="fas fa-search text-sm flex-shrink-0"
                                       style="color: var(--on-surface-variant);"></i>
                                    <input type="search" id="classes-students-search" name="q"
                                           value="{{ e($searchQuery ?? '') }}"
                                           placeholder="Search by name or reg. number..."
                                           class="flex-1 min-w-0 border-0 bg-transparent py-1 text-sm focus:ring-0 focus:outline-none"
                                           style="color: var(--on-surface);" autocomplete="off">
                                    @if(!empty($searchQuery))
                                        <a href="{{ route('admin.classes', ['class' => $selectedClass]) }}"
                                           class="flex-shrink-0 p-1 rounded-lg transition-opacity hover:opacity-80"
                                           style="color: var(--on-surface-variant);" aria-label="Clear search"><i
                                                class="fas fa-times text-xs"></i></a>
                                    @endif
                                </div>
                                <button type="submit"
                                        class="flex-shrink-0 px-4 py-2 rounded-xl text-sm font-medium transition-opacity hover:opacity-90"
                                        style="background: var(--primary); color: var(--on-primary);">Search
                                </button>
                            </form>
                        </div>

                        <a href="{{ route('admin.students.classlist.pdf', ['class' => $selectedClass]) }}"
                           target="_blank" rel="noopener noreferrer"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-3 sm:py-2 rounded-xl text-sm font-medium transition-opacity hover:opacity-90 min-h-[44px] sm:min-h-0"
                           style="color: var(--on-primary); background: var(--primary); border-radius: 12px;">
                            <i class="fas fa-file-pdf text-sm" aria-hidden="true"></i>
                            Export class list (PDF)
                        </a>

                        <div class="flex flex-col gap-3 w-full sm:w-auto sm:flex-row sm:flex-wrap sm:gap-2">
                            <form method="POST" action="{{ route('admin.students.bulk-fee-status') }}"
                                  class="grid grid-cols-2 gap-2 sm:inline-flex sm:flex-wrap sm:gap-2 fee-status-form"
                                  id="fee-selected-form">
                                @csrf
                                <input type="hidden" name="class" value="{{ e($selectedClass ?? '') }}">
                                <button type="submit" name="fee_status" value="1"
                                        data-confirm-message="Mark selected students as paid?"
                                        class="inline-flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-3 sm:py-2 rounded-xl text-xs sm:text-sm font-medium transition-opacity hover:opacity-90 min-h-[44px] sm:min-h-0 w-full sm:w-auto"
                                        style="background: var(--primary-container); color: var(--on-primary-container); border-radius: 12px;">
                                    <i class="fas fa-check text-xs sm:text-sm flex-shrink-0" aria-hidden="true"></i>
                                    <span class="sm:hidden truncate">Selected paid</span>
                                    <span class="hidden sm:inline">Mark selected as paid</span>
                                </button>
                                <button type="submit" name="fee_status" value="0"
                                        data-confirm-message="Mark selected students as unpaid?"
                                        class="inline-flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-3 sm:py-2 rounded-xl text-xs sm:text-sm font-medium transition-opacity hover:opacity-90 min-h-[44px] sm:min-h-0 w-full sm:w-auto"
                                        style="background: var(--surface-container-high); color: var(--on-surface); border: 1px solid var(--outline-variant); border-radius: 12px;">
                                    <i class="fas fa-times text-xs sm:text-sm flex-shrink-0" aria-hidden="true"></i>
                                    <span class="sm:hidden truncate">Selected unpaid</span>
                                    <span class="hidden sm:inline">Mark selected as unpaid</span>
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.students.bulk-fee-status') }}"
                                  class="grid grid-cols-2 gap-2 sm:inline-flex sm:flex-wrap sm:gap-2 fee-status-form">
                                @csrf
                                <input type="hidden" name="entire_class" value="1">
                                <input type="hidden" name="class" value="{{ e($selectedClass) }}">
                                <button type="submit" name="fee_status" value="1"
                                        data-confirm-message="Mark all students in {{ e($selectedClass) }} as paid?"
                                        class="inline-flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-3 sm:py-2 rounded-xl text-xs sm:text-sm font-medium transition-opacity hover:opacity-90 min-h-[44px] sm:min-h-0 w-full sm:w-auto"
                                        style="background: var(--primary); color: var(--on-primary); border-radius: 12px;">
                                    <i class="fas fa-check-double text-xs sm:text-sm flex-shrink-0"
                                       aria-hidden="true"></i>
                                    <span class="sm:hidden truncate">Class paid</span>
                                    <span class="hidden sm:inline">Mark entire class paid</span>
                                </button>
                                <button type="submit" name="fee_status" value="0"
                                        data-confirm-message="Mark all students in {{ e($selectedClass) }} as unpaid?"
                                        class="inline-flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-3 sm:py-2 rounded-xl text-xs sm:text-sm font-medium transition-opacity hover:opacity-90 min-h-[44px] sm:min-h-0 w-full sm:w-auto"
                                        style="background: var(--error-container); color: var(--on-error-container); border-radius: 12px;">
                                    <i class="fas fa-undo text-xs sm:text-sm flex-shrink-0" aria-hidden="true"></i>
                                    <span class="sm:hidden truncate">Class unpaid</span>
                                    <span class="hidden sm:inline">Mark entire class unpaid</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    @if($students->isEmpty())
                        <div class="flex flex-col items-center justify-center py-16 px-6">
                            <div
                                class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center"
                                style="border-radius: 16px;">
                                <i class="fas fa-user-graduate text-3xl" aria-hidden="true"></i>
                            </div>
                            @if(!empty($searchQuery))
                                <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No students
                                    found</h2>
                                <p class="text-sm text-center max-w-sm mb-6" style="color: var(--on-surface-variant);">
                                    No students in {{ e($selectedClass) }} match "{{ e($searchQuery) }}". Try a
                                    different search or clear the search.</p>
                                <a href="{{ route('admin.classes', ['class' => $selectedClass]) }}"
                                   class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-medium text-sm transition-opacity hover:opacity-90"
                                   style="background: var(--primary); color: var(--on-primary); border-radius: 12px;">Clear
                                    search</a>
                            @else
                                <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No students in
                                    this class</h2>
                                <p class="text-sm text-center max-w-sm mb-6" style="color: var(--on-surface-variant);">
                                    There are no students in {{ e($selectedClass) }}. Add students or select another
                                    class.</p>
                                <div class="flex justify-center">
                                    <a href="{{ route('admin.students.create') }}"
                                       class="btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] rounded-xl font-medium text-sm transition-all duration-200 hover:opacity-95 active:scale-[0.98]"
                                       style="border-radius: 12px;">
                                        <i class="fas fa-plus text-sm" aria-hidden="true"></i>
                                        Add Student
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0 border-x border-b md:border-x md:border-b"
                             style="border-color: var(--outline-variant);">
                            <ul class="flex flex-col gap-3 md:gap-0 md:divide-y divide-[var(--outline-variant)] p-4 sm:px-6 md:p-0 list-none min-w-0" role="list">
                                <li class="hidden md:flex items-center gap-3 sm:gap-4 px-5 sm:px-6 py-3"
                                    style="background: var(--surface-container); border-color: var(--outline-variant);">
                                    <label class="flex items-center flex-shrink-0 cursor-pointer">
                                        <input type="checkbox"
                                               class="student-fee-checkbox form-checkbox-input w-4 h-4 rounded border-2 cursor-pointer focus:ring-2 focus:ring-offset-0"
                                               style="border-color: var(--outline); accent-color: var(--primary);"
                                               id="select-all-fee" aria-label="Select all on page">
                                    </label>
                                    <span class="text-xs font-medium w-6 flex-shrink-0"
                                          style="color: var(--on-surface-variant);">#</span>
                                    <span class="w-10 flex-shrink-0" aria-hidden="true"></span>
                                    <span class="text-xs font-medium flex-1 min-w-0"
                                          style="color: var(--on-surface-variant);">Name</span>
                                    <span class="text-xs font-medium flex-shrink-0 w-16"
                                          style="color: var(--on-surface-variant);">Class</span>
                                    <span class="text-xs font-medium flex-shrink-0 w-20"
                                          style="color: var(--on-surface-variant);">Fee</span>
                                </li>
                                @foreach($students as $index => $s)
                                    @php
                                        $fullName = trim(($s->firstname ?? '') . ' ' . ($s->lastname ?? '') . ' ' . ($s->othername ?? ''));
                                        $isPaid = (int)($s->fee_status ?? 0) === 1;
                                        $avatarSrc = $s->imagelocation
                                            ? (str_starts_with($s->imagelocation, 'students/') ? asset('storage/' . $s->imagelocation) : asset('storage/students/' . $s->imagelocation))
                                            : asset('storage/students/default.png');
                                        $avatarInitial = $fullName ? mb_substr($fullName, 0, 1) : 'S';
                                    @endphp
                                    <li class="flex flex-col gap-0 rounded-2xl border p-4 md:rounded-none md:border-0 md:border-b md:border-t-0 md:flex-row md:items-center md:gap-4 md:py-4 md:px-5 lg:px-6 md:min-w-0 md:p-0 transition-[background-color] duration-200" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                        <div class="flex items-center gap-3 md:contents">
                                            <label class="flex items-center flex-shrink-0 cursor-pointer">
                                                <input type="checkbox" form="fee-selected-form" name="ids[]"
                                                       value="{{ $s->id }}"
                                                       class="student-fee-checkbox form-checkbox-input w-4 h-4 rounded border-2 cursor-pointer focus:ring-2 focus:ring-offset-0"
                                                       style="border-color: var(--outline); accent-color: var(--primary);"
                                                       aria-label="Select {{ $fullName ?: $s->reg_number }}">
                                            </label>
                                            <span class="text-sm font-medium w-6 flex-shrink-0 md:block" style="color: var(--on-surface-variant);">{{ ($students->currentPage() - 1) * $students->perPage() + $index + 1 }}</span>
                                            <img src="{{ $avatarSrc }}" alt=""
                                                 class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2"
                                                 style="border-color: var(--outline-variant);"
                                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($avatarInitial) }}&size=80'">
                                            <div class="min-w-0 flex-1 md:min-w-0 md:flex-1">
                                                <span class="text-xs font-medium md:sr-only" style="color: var(--on-surface-variant);">Name</span>
                                                <p class="text-sm font-medium truncate" style="color: var(--on-surface);">
                                                    <a href="{{ route('admin.students.show', $s) }}"
                                                       class="transition-opacity hover:opacity-80"
                                                       style="color: var(--primary);">{{ $fullName ?: '—' }}</a>
                                                </p>
                                                <p class="text-xs truncate mt-0.5" style="color: var(--on-surface-variant);">{{ $s->reg_number ?? '' }}</p>
                                            </div>
                                        </div>
                                        <div class="mt-3 pt-3 border-t md:border-t-0 md:mt-0 md:pt-0 flex flex-wrap items-baseline gap-x-4 gap-y-1 md:contents" style="border-color: var(--outline-variant);">
                                            <span class="w-full text-xs font-medium mb-1 md:sr-only" style="color: var(--on-surface-variant);">Class · Fee</span>
                                            <span class="text-xs md:flex-shrink-0 md:w-24"><span class="md:sr-only" style="color: var(--on-surface-variant);">Class </span><span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium" style="background: var(--surface-container-high); color: var(--on-surface-variant);">{{ e($s->class ?? '') }}</span></span>
                                            <span class="text-xs md:flex-shrink-0 md:w-20">
                                                @if($isPaid)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium" style="background: var(--primary-container); color: var(--on-primary-container);">Paid</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium" style="background: var(--error-container); color: var(--on-error-container);">Unpaid</span>
                                                @endif
                                            </span>
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

                    <div id="fee-confirm-modal" class="fixed inset-0 z-50 hidden overflow-y-auto overscroll-contain"
                         aria-modal="true" role="dialog" aria-labelledby="fee-confirm-modal-title">
                        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="fee-confirm-modal"
                             aria-hidden="true"></div>
                        <div
                            class="relative min-h-full min-h-[100dvh] flex items-center justify-center p-4 py-6 sm:p-6">
                            <div
                                class="relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-xl py-5 px-4 sm:py-6 sm:px-6 shadow-xl border my-auto"
                                style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                <h3 id="fee-confirm-modal-title" class="text-lg font-semibold mb-2"
                                    style="color: var(--on-surface);">Confirm action</h3>
                                <p id="fee-confirm-modal-message" class="text-sm mb-6"
                                   style="color: var(--on-surface-variant);"></p>
                                <div class="flex flex-col-reverse sm:flex-row justify-end gap-2">
                                    <button type="button"
                                            class="btn-secondary px-4 py-2.5 rounded-full text-sm w-full sm:w-auto"
                                            data-close="fee-confirm-modal">Cancel
                                    </button>
                                    <button type="button" id="fee-confirm-modal-confirm"
                                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full text-sm font-medium w-full sm:w-auto transition-opacity hover:opacity-95"
                                            style="background: var(--primary); color: var(--on-primary);">Confirm
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>

    <div id="add-class-modal"
         class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 sm:p-6 bg-black/50 backdrop-blur-sm"
         aria-modal="true" role="dialog" aria-labelledby="add-class-modal-title" aria-hidden="true">
        <div
            class="relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-2xl shadow-2xl flex flex-col"
            style="background: var(--surface-container-lowest); border: 1px solid var(--outline-variant);">
            <div class="flex-shrink-0 px-5 sm:px-6 pt-5 sm:pt-6 pb-3 flex items-start justify-between gap-3"
                 style="border-bottom: 1px solid var(--outline-variant);">
                <div class="min-w-0 flex-1">
                    <h3 id="add-class-modal-title" class="text-lg font-semibold" style="color: var(--on-surface);">Add
                        class</h3>
                    <p class="text-sm mt-1" style="color: var(--on-surface-variant);">Enter the name of the new class
                        (e.g. JSS 1A, SSS 2B).</p>
                </div>
                <button type="button" onclick="closeModal('add-class-modal')"
                        class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" aria-label="Close"
                        style="color: var(--on-surface);">
                    <i class="fas fa-times text-sm" aria-hidden="true"></i>
                </button>
            </div>

            <form id="add-class-form" method="post" action="{{ route('admin.add.class') }}"
                  class="flex flex-col flex-1 min-h-0">
                @csrf
                <div class="flex-1 min-h-0 overflow-y-auto px-5 sm:px-6 py-4">
                    <div class="form-group min-w-0">
                        <label for="add-class-name" class="form-label">Class name</label>
                        <input type="text" id="add-class-name" name="class_name" class="form-input w-full min-w-0"
                               placeholder="e.g. JSS 1A" value="{{ old('class_name') }}" maxlength="100"
                               autocomplete="off">
                        <p id="class_name-error" class="form-error mt-1 text-sm hidden" aria-live="polite"></p>
                    </div>
                </div>
                <div class="flex-shrink-0 flex flex-col-reverse sm:flex-row justify-end gap-2 px-5 sm:px-6 py-4"
                     style="border-top: 1px solid var(--outline-variant); background: var(--surface-container-lowest);">
                    <button type="button" onclick="closeModal('add-class-modal')"
                            class="btn-secondary px-4 py-2.5 rounded-xl text-sm w-full sm:w-auto">Cancel
                    </button>
                    <button type="submit" id="add-class-submit-btn"
                            class="btn-primary inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium w-full sm:w-auto transition-opacity hover:opacity-95"
                            style="border-radius: 12px;">Add class
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="edit-class-modal"
         class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 sm:p-6 bg-black/50 backdrop-blur-sm"
         aria-modal="true" role="dialog" aria-labelledby="edit-class-modal-title" aria-hidden="true">
        <div
            class="relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-2xl shadow-2xl flex flex-col"
            style="background: var(--surface-container-lowest); border: 1px solid var(--outline-variant);">
            <div class="flex-shrink-0 px-5 sm:px-6 pt-5 sm:pt-6 pb-3 flex items-start justify-between gap-3"
                 style="border-bottom: 1px solid var(--outline-variant);">
                <div class="min-w-0 flex-1">
                    <h3 id="edit-class-modal-title" class="text-lg font-semibold" style="color: var(--on-surface);">
                        Edit class
                    </h3>
                    <p class="text-sm mt-1" style="color: var(--on-surface-variant);">
                        Update the class name. This will be used across the app for this class.
                    </p>
                </div>
                <button type="button" onclick="closeModal('edit-class-modal')"
                        class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" aria-label="Close"
                        style="color: var(--on-surface);">
                    <i class="fas fa-times text-sm" aria-hidden="true"></i>
                </button>
            </div>

            <form id="edit-class-form" method="post"
                  action="#"
                  class="flex flex-col flex-1 min-h-0">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-class-id" name="id">
                <div class="flex-1 min-h-0 overflow-y-auto px-5 sm:px-6 py-4">
                    <div class="form-group min-w-0">
                        <label for="edit-class-name" class="form-label">Class name</label>
                        <input type="text" id="edit-class-name" name="class_name" class="form-input w-full min-w-0"
                               placeholder="e.g. JSS 1A" value="" maxlength="100"
                               autocomplete="off">
                        <p id="edit_class_name-error" class="form-error mt-1 text-sm hidden" aria-live="polite"></p>
                    </div>
                </div>
                <div class="flex-shrink-0 flex flex-col-reverse sm:flex-row justify-end gap-2 px-5 sm:px-6 py-4"
                     style="border-top: 1px solid var(--outline-variant); background: var(--surface-container-lowest);">
                    <button type="button" onclick="closeModal('edit-class-modal')"
                            class="btn-secondary px-4 py-2.5 rounded-xl text-sm w-full sm:w-auto">Cancel
                    </button>
                    <button type="submit" id="edit-class-submit-btn"
                            class="btn-primary inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium w-full sm:w-auto transition-opacity hover:opacity-95"
                            style="border-radius: 12px;">Save changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="delete-class-modal"
         class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 sm:p-6 bg-black/50 backdrop-blur-sm"
         aria-modal="true" role="dialog" aria-labelledby="delete-class-modal-title" aria-hidden="true">
        <div
            class="relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-2xl shadow-2xl flex flex-col"
            style="background: var(--surface-container-lowest); border: 1px solid var(--outline-variant);">
            <div class="flex-shrink-0 px-5 sm:px-6 pt-5 sm:pt-6 pb-3 flex items-start justify-between gap-3"
                 style="border-bottom: 1px solid var(--outline-variant);">
                <div class="min-w-0 flex-1">
                    <h3 id="delete-class-modal-title" class="text-lg font-semibold" style="color: var(--on-surface);">
                        Delete class
                    </h3>
                    <p class="text-sm mt-1" style="color: var(--on-surface-variant);">
                        You can only delete a class that has no students assigned to it.
                    </p>
                </div>
                <button type="button" onclick="closeModal('delete-class-modal')"
                        class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" aria-label="Close"
                        style="color: var(--on-surface);">
                    <i class="fas fa-times text-sm" aria-hidden="true"></i>
                </button>
            </div>

            <form id="delete-class-form" method="post"
                  action="#"
                  class="flex flex-col flex-1 min-h-0">
                @csrf
                @method('DELETE')
                <input type="hidden" id="delete-class-id" name="id">
                <input type="hidden" id="delete-class-name" name="class_name">
                <div class="flex-1 min-h-0 overflow-y-auto px-5 sm:px-6 py-4">
                    <p class="text-sm" style="color: var(--on-surface-variant);">
                        Are you sure you want to delete
                        <span id="delete-class-label" class="font-medium" style="color: var(--on-surface);"></span>?
                        This action cannot be undone.
                    </p>
                </div>
                <div class="flex-shrink-0 flex flex-col-reverse sm:flex-row justify-end gap-2 px-5 sm:px-6 py-4"
                     style="border-top: 1px solid var(--outline-variant); background: var(--surface-container-lowest);">
                    <button type="button" onclick="closeModal('delete-class-modal')"
                            class="btn-secondary px-4 py-2.5 rounded-xl text-sm w-full sm:w-auto">Cancel
                    </button>
                    <button type="submit" id="delete-class-submit-btn"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium w-full sm:w-auto transition-opacity hover:opacity-95"
                            style="border-radius: 12px; background: var(--error-container); color: var(--on-error-container);">
                        Delete class
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const addClassBtn = document.getElementById('add-class-btn');
                const addClassModal = document.getElementById('add-class-modal');
                const addClassForm = document.getElementById('add-class-form');
                const addClassSubmitBtn = document.getElementById('add-class-submit-btn');
                const classNameError = document.getElementById('class_name-error');

                function clearAddClassErrors() {
                    if (classNameError) {
                        classNameError.textContent = '';
                        classNameError.classList.add('hidden');
                    }
                }

                if (addClassBtn && addClassModal) {
                    addClassBtn.addEventListener('click', function () {
                        clearAddClassErrors();
                        if (addClassForm) addClassForm.reset();
                        openModal('add-class-modal');
                        document.getElementById('add-class-name') && document.getElementById('add-class-name').focus();
                    });
                }

                if (addClassForm && addClassSubmitBtn) {
                    addClassForm.addEventListener('submit', function (e) {
                        e.preventDefault();
                        clearAddClassErrors();
                        let token = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        if (!token) token = addClassForm.querySelector('input[name="_token"]') && addClassForm.querySelector('input[name="_token"]').value;
                        if (!token) {
                            if (typeof flashError === 'function') flashError('Security token missing. Please refresh the page.');
                            return;
                        }
                        if (typeof setButtonLoading === 'function') setButtonLoading(addClassSubmitBtn, true);
                        const formData = new FormData(addClassForm);
                        fetch(addClassForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                            .then(function (r) {
                                if (r.status === 422) {
                                    return r.json().then(function (data) {
                                        if (data.errors && typeof showLaravelErrors === 'function') {
                                            // Map server field -> add modal error element id prefix (class_name-error)
                                            showLaravelErrors(data.errors, {class_name: 'class_name'});
                                        } else if (data.message && typeof flashError === 'function') {
                                            flashError(data.message);
                                        }
                                        throw new Error('Validation failed');
                                    });
                                }
                                return r.json();
                            })
                            .then(function (data) {
                                if (data.status === 'success') {
                                    if (typeof flashSuccess === 'function') flashSuccess(data.message || 'Class added.');
                                    closeModal('add-class-modal');
                                    setTimeout(function() { window.location.reload(); }, 2800);
                                } else {
                                    if (typeof flashError === 'function') flashError(data.message || 'Could not add class.');
                                }
                            })
                            .catch(function (err) {
                                if (err.message !== 'Validation failed' && typeof flashError === 'function') {
                                    flashError('An error occurred. Please try again.');
                                }
                            })
                            .finally(function () {
                                if (typeof setButtonLoading === 'function') setButtonLoading(addClassSubmitBtn, false);
                            });
                    });
                }
            })();

            function openEditClassModal(button) {
                const id = button.getAttribute('data-class-id');
                const name = button.getAttribute('data-class-name') || '';
                const modalId = 'edit-class-modal';
                const form = document.getElementById('edit-class-form');
                const nameInput = document.getElementById('edit-class-name');
                const idInput = document.getElementById('edit-class-id');
                const errorEl = document.getElementById('edit_class_name-error');

                if (errorEl) {
                    errorEl.textContent = '';
                    errorEl.classList.add('hidden');
                }

                if (form && id && nameInput && idInput) {
                    form.action = "{{ route('admin.classes.update', ['schoolClass' => 'CLASS_ID']) }}".replace('CLASS_ID', id);
                    idInput.value = id;
                    nameInput.value = name;
                }

                openModal(modalId);
                setTimeout(function () {
                    nameInput && nameInput.focus();
                }, 50);
            }

            function openDeleteClassModal(button) {
                const id = button.getAttribute('data-class-id');
                const name = button.getAttribute('data-class-name') || '';
                const students = parseInt(button.getAttribute('data-class-students') || '0', 10);
                const modalId = 'delete-class-modal';
                const form = document.getElementById('delete-class-form');
                const idInput = document.getElementById('delete-class-id');
                const nameInput = document.getElementById('delete-class-name');
                const label = document.getElementById('delete-class-label');

                if (students > 0) {
                    if (typeof flashError === 'function') {
                        flashError('You can only delete an empty class with no students.');
                    }
                    return;
                }

                if (form && id && idInput && nameInput) {
                    form.action = "{{ route('admin.classes.destroy', ['schoolClass' => 'CLASS_ID']) }}".replace('CLASS_ID', id);
                    idInput.value = id;
                    nameInput.value = name;
                }

                if (label) {
                    label.textContent = name;
                }

                openModal(modalId);
            }

            (function () {
                // Per-card actions menu (ellipsis dropdown)
                document.querySelectorAll('[data-class-menu-toggle]').forEach(function (btn) {
                    btn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        const id = btn.getAttribute('data-class-menu-toggle');
                        const menu = document.querySelector('[data-class-menu="' + id + '"]');
                        if (!menu) return;
                        const isHidden = menu.classList.contains('hidden');
                        document.querySelectorAll('[data-class-menu]').forEach(function (m) {
                            m.classList.add('hidden');
                        });
                        if (isHidden) {
                            menu.classList.remove('hidden');
                        }
                    });
                });

                document.addEventListener('click', function () {
                    document.querySelectorAll('[data-class-menu]').forEach(function (m) {
                        m.classList.add('hidden');
                    });
                });

                const editForm = document.getElementById('edit-class-form');
                const editSubmitBtn = document.getElementById('edit-class-submit-btn');
                const editError = document.getElementById('edit_class_name-error');

                function clearEditErrors() {
                    if (editError) {
                        editError.textContent = '';
                        editError.classList.add('hidden');
                    }
                }

                if (editForm && editSubmitBtn) {
                    editForm.addEventListener('submit', function (e) {
                        e.preventDefault();
                        clearEditErrors();
                        let token = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        if (!token) token = editForm.querySelector('input[name="_token"]') && editForm.querySelector('input[name="_token"]').value;
                        if (!token) {
                            if (typeof flashError === 'function') flashError('Security token missing. Please refresh the page.');
                            return;
                        }
                        if (typeof setButtonLoading === 'function') setButtonLoading(editSubmitBtn, true);
                        const formData = new FormData(editForm);
                        fetch(editForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                            .then(function (r) {
                                if (r.status === 422) {
                                    return r.json().then(function (data) {
                                        if (data.errors && typeof showLaravelErrors === 'function') {
                                            // Map server field -> edit modal error element id prefix
                                            showLaravelErrors(data.errors, {class_name: 'edit_class_name'});
                                        } else if (data.message && typeof flashError === 'function') {
                                            flashError(data.message);
                                        }
                                        throw new Error('Validation failed');
                                    });
                                }
                                return r.json();
                            })
                            .then(function (data) {
                                if (data.status === 'success') {
                                    if (typeof flashSuccess === 'function') flashSuccess(data.message || 'Class updated.');
                                    closeModal('edit-class-modal');
                                    setTimeout(function () { window.location.reload(); }, 2800);
                                } else {
                                    if (typeof flashError === 'function') flashError(data.message || 'Could not update class.');
                                }
                            })
                            .catch(function (err) {
                                if (err.message !== 'Validation failed' && typeof flashError === 'function') {
                                    flashError('An error occurred. Please try again.');
                                }
                            })
                            .finally(function () {
                                if (typeof setButtonLoading === 'function') setButtonLoading(editSubmitBtn, false);
                            });
                    });
                }

                const deleteForm = document.getElementById('delete-class-form');
                const deleteSubmitBtn = document.getElementById('delete-class-submit-btn');

                if (deleteForm && deleteSubmitBtn) {
                    deleteForm.addEventListener('submit', function (e) {
                        e.preventDefault();
                        let token = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        if (!token) token = deleteForm.querySelector('input[name="_token"]') && deleteForm.querySelector('input[name="_token"]').value;
                        if (!token) {
                            if (typeof flashError === 'function') flashError('Security token missing. Please refresh the page.');
                            return;
                        }
                        if (typeof setButtonLoading === 'function') setButtonLoading(deleteSubmitBtn, true);
                        const formData = new FormData(deleteForm);
                        fetch(deleteForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                            .then(function (r) {
                                return r.json().then(function (data) {
                                    return {status: r.status, ok: r.ok, data: data};
                                });
                            })
                            .then(function (res) {
                                if (res.ok && res.data.status === 'success') {
                                    if (typeof flashSuccess === 'function') flashSuccess(res.data.message || 'Class deleted.');
                                    closeModal('delete-class-modal');
                                    setTimeout(function () { window.location.reload(); }, 2800);
                                } else if (res.status === 422) {
                                    if (typeof flashError === 'function') {
                                        flashError(res.data.message || 'You can only delete an empty class with no students.');
                                    }
                                } else {
                                    if (typeof flashError === 'function') flashError(res.data.message || 'Could not delete class.');
                                }
                            })
                            .catch(function () {
                                if (typeof flashError === 'function') flashError('An error occurred. Please try again.');
                            })
                            .finally(function () {
                                if (typeof setButtonLoading === 'function') setButtonLoading(deleteSubmitBtn, false);
                            });
                    });
                }
            })();

            @if($students !== null && !$students->isEmpty())
            document.getElementById('select-all-fee') && document.getElementById('select-all-fee').addEventListener('change', function () {
                document.querySelectorAll('.student-fee-checkbox[name="ids[]"]').forEach(function (cb) {
                    cb.checked = this.checked;
                }, this);
            });

            (function () {
                const modal = document.getElementById('fee-confirm-modal');
                const messageEl = document.getElementById('fee-confirm-modal-message');
                const confirmBtn = document.getElementById('fee-confirm-modal-confirm');
                let pendingForm = null;
                let pendingSubmitter = null;

                function openFeeConfirmModal() {
                    if (modal) modal.classList.remove('hidden');
                }

                function closeFeeConfirmModal() {
                    if (modal) {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                    pendingForm = null;
                    pendingSubmitter = null;
                }

                document.querySelectorAll('.fee-status-form').forEach(function (form) {
                    form.addEventListener('submit', function (e) {
                        const submitter = e.submitter;
                        if (submitter && submitter.getAttribute('data-confirm-message')) {
                            e.preventDefault();
                            if (messageEl) messageEl.textContent = submitter.getAttribute('data-confirm-message');
                            pendingForm = form;
                            pendingSubmitter = submitter;
                            openFeeConfirmModal();
                        }
                    });
                });

                if (confirmBtn) {
                    confirmBtn.addEventListener('click', function () {
                        if (!pendingForm || !pendingSubmitter) return;
                        const form = pendingForm;
                        const btn = this;
                        const csrf = form.querySelector('input[name="_token"]') && form.querySelector('input[name="_token"]').value;
                        const body = new URLSearchParams(new FormData(form));
                        body.append(pendingSubmitter.name, pendingSubmitter.value);
                        setButtonLoading(btn, true);
                        fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: body
                        })
                            .then(function (r) {
                                return r.json().then(function (data) {
                                    return {status: r.status, ok: r.ok, data: data};
                                });
                            })
                            .then(function (res) {
                                if (res.ok && res.data.status === 'success') {
                                    closeFeeConfirmModal();
                                    flashSuccess(res.data.message || 'Fee status updated.');
                                    if (res.data.redirect) setTimeout(function () {
                                        window.location.href = res.data.redirect;
                                    }, 2800);
                                } else if (res.status === 422 && res.data.errors) {
                                    const err = res.data.errors;
                                    const first = (err.ids && err.ids[0]) || (err.class && err.class[0]) || (err.fee_status && err.fee_status[0]) || res.data.message;
                                    flashError(typeof first === 'string' ? first : (Array.isArray(first) ? first[0] : 'Validation failed.'));
                                } else {
                                    flashError(Array.isArray(res.data.message) ? res.data.message.join(' ') : (res.data.message || 'Could not update fee status.'));
                                }
                            })
                            .catch(function () {
                                flashError('An error occurred. Please try again.');
                            })
                            .finally(function () {
                                setButtonLoading(btn, false);
                            });
                    });
                }

                document.querySelectorAll('[data-close="fee-confirm-modal"]').forEach(function (el) {
                    el.addEventListener('click', function () {
                        closeFeeConfirmModal();
                    });
                });
            })();
            @endif
        </script>
    @endpush
@endsection
