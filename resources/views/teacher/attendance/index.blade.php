@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Teacher attendance overview"
                pill="Teacher"
                title="Attendance"
                description="Select a class to take attendance for the current term."
            >
                <x-slot name="actions">
                    <a href="{{ route('teacher.attendance.view') }}" class="admin-dashboard-hero__btn admin-dashboard-hero__btn--primary w-full lg:w-auto justify-center min-h-[44px] sm:min-h-0">
                        <i class="fas fa-eye text-[10px] sm:text-xs" aria-hidden="true"></i>
                        <span>View Attendance</span>
                    </a>
                </x-slot>
            </x-admin.hero-page>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl p-5 sm:p-6 lg:p-8" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-6">
                    @forelse($classes as $c)
                        <div class="h-full min-h-[200px]">
                            <div class="flex flex-col h-full overflow-hidden rounded-2xl transition-all duration-200 hover:shadow-[var(--elevation-2)]" style="background: var(--surface-container-lowest); border: 1px solid var(--outline-variant); box-shadow: var(--elevation-1);">
                                <div class="p-5 sm:p-6 text-center flex-1 flex flex-col items-center justify-center gap-3">
                                    <div class="dashboard-quick-icon dashboard-quick-icon--blue w-14 h-14 rounded-2xl flex-shrink-0" style="border-radius: 16px;">
                                        <i class="fas fa-calendar-check text-xl" aria-hidden="true"></i>
                                    </div>
                                    <h2 class="text-base sm:text-lg font-medium" style="color: var(--on-surface);">{{ $c['class_name'] }}</h2>
                                    <p class="text-2xl sm:text-3xl font-normal tracking-tight mb-0" style="color: var(--on-surface);">{{ $c['user_count'] }}</p>
                                    <p class="text-sm font-normal" style="color: var(--on-surface-variant);">Student(s) Found</p>
                                </div>

                                <div class="p-4 sm:p-5 pt-0 flex justify-center" style="border-top: 1px solid var(--outline-variant);">
                                    @php
                                        $raw = fn ($v) => str_replace(['%', '&', '=', ' '], ['%25', '%26', '%3D', '+'], (string) $v);
                                        $takeUrl = route('teacher.attendance.take') . '?' . 'class=' . $raw($c['class_name']) . '&term=' . $raw($settings['term'] ?? 'First Term') . '&session=' . $raw($settings['session'] ?? '');
                                    @endphp
                                    <a href="{{ $takeUrl }}" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;margin-top: 10px;">
                                        <i class="fas fa-door-open text-sm" aria-hidden="true"></i>
                                        Open Class
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex-1 flex flex-col items-center justify-center min-h-[min(400px,50vh)] py-12 sm:py-16">
                            <div class="rounded-3xl p-8 sm:p-12 text-center w-full max-w-lg" style="background: var(--surface-container-lowest); border: 1px solid var(--outline-variant); box-shadow: var(--elevation-1);">
                                <div class="dashboard-stat-icon dashboard-stat-icon--blue w-24 h-24 rounded-2xl mx-auto mb-6" style="border-radius: 16px;">
                                    <i class="fas fa-calendar-check text-4xl" aria-hidden="true"></i>
                                </div>
                                <h2 class="text-xl font-normal tracking-tight mb-2" style="color: var(--on-surface);">No assigned classes</h2>
                                <p class="text-sm font-normal mb-0" style="color: var(--on-surface-variant);">You don’t have any assigned class yet. Please contact the admin.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
@endsection
