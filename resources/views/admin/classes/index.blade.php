@php use App\Models\Student; @endphp
@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <div class="mb-4 sm:mb-6 w-fit">
                @if($students !== null)
                    <a href="{{ route('admin.classes') }}"
                       class="inline-flex items-center gap-2 text-sm font-medium transition-opacity hover:opacity-80"
                       style="color: var(--on-surface-variant);">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Change class
                    </a>
                @endif
            </div>

            <header class="mb-6 lg:mb-8 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                <div class="min-w-0 flex-1">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-normal tracking-tight mb-1.5"
                        style="color: var(--on-surface); letter-spacing: -0.02em;">
                        @if($students !== null)
                            {{ e($selectedClass) }}
                        @else
                            Students / Classes
                        @endif
                    </h1>
                    <p class="text-sm sm:text-base font-normal" style="color: var(--on-surface-variant);">
                        @if($students !== null)
                            Students in this class — search, fees, and export below.
                        @else
                            Open a class to see students, or register a new student. Add a class if needed.
                        @endif
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row flex-wrap gap-2 w-full lg:w-auto lg:flex-shrink-0">
                    @if($students === null)
                        @can('create', Student::class)
                            <a href="{{ route('admin.students.create') }}"
                               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-3 sm:py-2.5 rounded-xl text-sm font-medium transition-opacity hover:opacity-95 border"
                               style="border-radius: 12px; background: var(--surface-container-high); color: var(--on-surface); border-color: var(--outline-variant);">
                                <i class="fas fa-user-plus text-xs sm:text-sm" aria-hidden="true"></i>
                                <span>Register student</span>
                            </a>
                        @endcan
                    @endif
                    @if($students === null && Route::has('admin.add.class'))
                        <button type="button" id="add-class-btn"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-3 sm:py-2.5 rounded-xl text-sm font-medium transition-colors border border-dashed border-gray-300 sm:border-solid"
                                style="border-radius: 12px; background-color: var(--primary); color: var(--on-primary);"
                                aria-haspopup="dialog" aria-expanded="false" aria-controls="add-class-modal">
                            <i class="fas fa-plus text-[10px] sm:text-xs" aria-hidden="true"></i>
                            <span>Add class</span>
                        </button>
                    @endif
                </div>
            </header>

            @if($students === null)
                <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl p-5 sm:p-6 lg:p-8"
                     style="background: var(--surface-container-low); box-shadow: var(--elevation-1);">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-6">
                        @forelse($classesWithCounts as $c)
                            <div class="h-full min-h-[200px]">
                                <div
                                    class="flex flex-col h-full overflow-hidden rounded-2xl transition-all duration-200 hover:shadow-[var(--elevation-2)]"
                                    style="background: var(--surface-container-lowest); border: 1px solid var(--outline-variant); box-shadow: var(--elevation-1);">
                                    <div
                                        class="p-5 sm:p-6 text-center flex-1 flex flex-col items-center justify-center gap-3">
                                        <div
                                            class="dashboard-quick-icon dashboard-quick-icon--blue w-14 h-14 rounded-2xl flex-shrink-0 flex items-center justify-center"
                                            style="border-radius: 16px;">
                                            <i class="fas fa-chalkboard text-xl" aria-hidden="true"></i>
                                        </div>
                                        <h2 class="text-base sm:text-lg font-medium mb-0"
                                            style="color: var(--on-surface);">{{ e($c['class_name']) }}</h2>
                                        <p class="text-2xl sm:text-3xl font-normal tracking-tight mb-0"
                                           style="color: var(--on-surface);">{{ $c['user_count'] ?? 0 }}</p>
                                        <p class="text-sm font-normal mb-0" style="color: var(--on-surface-variant);">
                                            Student(s)</p>
                                    </div>

                                    <div class="p-4 sm:p-5 pt-0 flex justify-center"
                                         style="border-top: 1px solid var(--outline-variant);">
                                        <a href="{{ route('admin.classes', ['class' => $c['class_name']]) }}"
                                           class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]"
                                           style="border-radius: 12px; margin-top: 10px;">
                                            <i class="fas fa-door-open text-sm" aria-hidden="true"></i>
                                            Open class
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
                        <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0 border-x border-b"
                             style="border-color: var(--outline-variant);">
                            <ul class="divide-y divide-[var(--outline-variant)]" role="list">
                                <li class="flex items-center gap-3 sm:gap-4 px-5 sm:px-6 py-3"
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
                                    <li class="flex items-center gap-3 sm:gap-4 px-5 sm:px-6 py-4 transition-colors"
                                        style="background: var(--surface-container-lowest);">
                                        <label class="flex items-center flex-shrink-0 cursor-pointer">
                                            <input type="checkbox" form="fee-selected-form" name="ids[]"
                                                   value="{{ $s->id }}"
                                                   class="student-fee-checkbox form-checkbox-input w-4 h-4 rounded border-2 cursor-pointer focus:ring-2 focus:ring-offset-0"
                                                   style="border-color: var(--outline); accent-color: var(--primary);"
                                                   aria-label="Select {{ $fullName ?: $s->reg_number }}">
                                        </label>
                                        <span class="text-sm font-medium w-6 flex-shrink-0"
                                              style="color: var(--on-surface-variant);">{{ ($students->currentPage() - 1) * $students->perPage() + $index + 1 }}</span>
                                        <img src="{{ $avatarSrc }}" alt=""
                                             class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2"
                                             style="border-color: var(--outline-variant);"
                                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($avatarInitial) }}&size=80'">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium truncate" style="color: var(--on-surface);">
                                                <a href="{{ route('admin.students.show', $s->id) }}"
                                                   class="transition-opacity hover:opacity-80"
                                                   style="color: var(--primary);">{{ $fullName ?: '—' }}</a>
                                            </p>
                                            <p class="text-xs truncate"
                                               style="color: var(--on-surface-variant);">{{ $s->reg_number ?? '' }}</p>
                                        </div>
                                        <div class="flex-shrink-0 w-24">
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium"
                                                style="background: var(--surface-container-high); color: var(--on-surface-variant);">{{ e($s->class ?? '') }}</span>
                                        </div>
                                        <div class="flex-shrink-0 w-20">
                                            @if($isPaid)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium"
                                                    style="background: var(--primary-container); color: var(--on-primary-container);">Paid</span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium"
                                                    style="background: var(--error-container); color: var(--on-error-container);">Unpaid</span>
                                            @endif
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
                                            showLaravelErrors(data.errors);
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
