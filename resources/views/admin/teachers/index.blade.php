@extends('layouts.app', ['title' => 'Teachers'])

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Teachers overview"
                pill="Admin"
                title="Teachers & Classes"
                description="View and manage teachers. Register a new teacher or assign teachers to classes."
            >
                @if(Route::has('admin.assign_teacher_to_class.form') || Route::has('admin.register_teacher.form'))
                    <x-slot name="actions">
                        <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
                            @if(Route::has('admin.assign_teacher_to_class.form'))
                                <a href="{{ route('admin.assign_teacher_to_class.form') }}" class="admin-dashboard-hero__btn w-full lg:w-auto justify-center min-h-[44px] sm:min-h-0">
                                    <i class="fas fa-link text-[10px] sm:text-xs" aria-hidden="true"></i>
                                    <span>Assign To Class</span>
                                </a>
                            @endif
                            @if(Route::has('admin.register_teacher.form'))
                                <a href="{{ route('admin.register_teacher.form') }}" class="admin-dashboard-hero__btn admin-dashboard-hero__btn--primary w-full lg:w-auto justify-center min-h-[44px] sm:min-h-0">
                                    <i class="fas fa-plus text-[10px] sm:text-xs" aria-hidden="true"></i>
                                    <span>Register Teacher</span>
                                </a>
                            @endif
                        </div>
                    </x-slot>
                @endif
            </x-admin.hero-page>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                @if($teachers->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 md:py-40 px-6">
                        <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5" style="border-radius: 16px;">
                            <i class="fas fa-user-graduate text-3xl" aria-hidden="true"></i>
                        </div>
                        <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No teachers yet</h2>
                        <p class="text-sm text-center max-w-md mb-6" style="color: var(--on-surface-variant);">Register a teacher to get started.</p>
                        @if(Route::has('admin.register_teacher.form'))
                            <div class="flex justify-center">
                                <a href="{{ route('admin.register_teacher.form') }}" class="btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] rounded-xl font-medium text-sm transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;">
                                    <i class="fas fa-plus text-sm" aria-hidden="true"></i>
                                    Register Teacher
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0 border-x border-b" style="border-color: var(--outline-variant);">
                        <ul class="divide-y divide-[var(--outline-variant)]" role="list">
                            <li class="flex items-center gap-3 sm:gap-4 px-5 sm:px-6 py-3" style="background: var(--surface-container); border-color: var(--outline-variant);">
                                <span class="text-xs font-medium w-10 flex-shrink-0" style="color: var(--on-surface-variant);">#</span>
                                <span class="w-10 flex-shrink-0" aria-hidden="true"></span>
                                <span class="text-xs font-medium flex-1 min-w-0" style="color: var(--on-surface-variant);">Name</span>
                                <span class="text-xs font-medium flex-1 min-w-0 hidden sm:block" style="color: var(--on-surface-variant);">Email</span>
                                <span class="text-xs font-medium flex-shrink-0 w-24 text-right" style="color: var(--on-surface-variant);">Actions</span>
                            </li>
                            @foreach($teachers as $index => $t)
                                @php
                                    $fullName = trim(($t->firstname ?? '') . ' ' . ($t->lastname ?? '') . ' ' . ($t->othername ?? ''));
                                    $avatarSrc = $t->imagelocation
                                        ? (str_starts_with($t->imagelocation, 'teachers/') ? asset('storage/' . $t->imagelocation) : asset('storage/teachers/' . $t->imagelocation))
                                        : asset('storage/teachers/default.png');
                                    $avatarInitial = $fullName ? mb_substr($fullName, 0, 1) : 'T';
                                @endphp
                                <li class="flex items-center gap-3 sm:gap-4 px-5 sm:px-6 py-4 transition-colors" style="background: var(--surface-container-lowest);">
                                    <span class="text-sm font-medium w-10 flex-shrink-0" style="color: var(--on-surface-variant);">{{ ($teachers->currentPage() - 1) * $teachers->perPage() + $index + 1 }}</span>
                                    <img src="{{ $avatarSrc }}" alt="" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($avatarInitial) }}&size=80'">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium truncate" style="color: var(--on-surface);">{{ $fullName ?: '—' }}</p>
                                        <p class="text-xs truncate sm:hidden" style="color: var(--on-surface-variant);">{{ $t->email ?? '' }}</p>
                                    </div>
                                    <div class="min-w-0 flex-1 hidden sm:block">
                                        <p class="text-sm truncate" style="color: var(--on-surface-variant);">{{ $t->email ?? '—' }}</p>
                                    </div>
                                    <div class="flex-shrink-0 w-24 text-right">
                                        <a href="{{ route('admin.teachers.edit', $t) }}" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl text-xs sm:text-sm font-medium transition-opacity hover:opacity-90" style="background: var(--primary-container); color: var(--on-primary-container); border-radius: 12px;">
                                            <i class="fas fa-pen text-xs" aria-hidden="true"></i>
                                            <span class="hidden sm:inline">Edit</span>
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @if($teachers->hasPages())
                        <div class="px-5 sm:px-6 py-4" style="border-top: 1px solid var(--outline-variant); background: var(--surface-container-low);">
                            <x-pagination :paginator="$teachers" />
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </main>
@endsection
