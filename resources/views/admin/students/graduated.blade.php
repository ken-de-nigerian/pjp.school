@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <div class="mb-4 sm:mb-6 w-fit">
                <a href="{{ route('admin.classes') }}" class="inline-flex items-center gap-2 text-sm font-medium transition-opacity hover:opacity-80" style="color: var(--on-surface-variant);">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    Back to Students
                </a>
            </div>

            <header class="mb-6 lg:mb-8 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-normal tracking-tight mb-1.5" style="color: var(--on-surface); letter-spacing: -0.02em;">
                        Graduated Students
                    </h1>
                    <p class="text-sm sm:text-base font-normal" style="color: var(--on-surface-variant);">
                        View students by graduation year. Select a year to see graduates.
                    </p>
                </div>
            </header>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl p-5 sm:p-6 lg:p-8" style="background: var(--surface-container-low); box-shadow: var(--elevation-1);">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-6">
                    @forelse($graduationYearsWithCounts as $row)
                        <div class="h-full min-h-[200px]">
                            <div class="flex flex-col h-full overflow-hidden rounded-2xl transition-all duration-200 hover:shadow-[var(--elevation-2)]" style="background: var(--surface-container-lowest); border: 1px solid var(--outline-variant); box-shadow: var(--elevation-1);">
                                <div class="p-5 sm:p-6 text-center flex-1 flex flex-col items-center justify-center gap-3">
                                    <div class="dashboard-quick-icon dashboard-quick-icon--blue w-14 h-14 rounded-2xl flex-shrink-0 flex items-center justify-center" style="border-radius: 16px;">
                                        <i class="fas fa-graduation-cap text-xl" aria-hidden="true"></i>
                                    </div>
                                    <h2 class="text-base sm:text-lg font-medium mb-0" style="color: var(--on-surface);">{{ e($row['year']) }}</h2>
                                    <p class="text-2xl sm:text-3xl font-normal tracking-tight mb-0" style="color: var(--on-surface);">{{ $row['user_count'] ?? 0 }}</p>
                                    <p class="text-sm font-normal mb-0" style="color: var(--on-surface-variant);">Graduate(s)</p>
                                </div>

                                <div class="p-4 sm:p-5 pt-0 flex justify-center" style="border-top: 1px solid var(--outline-variant);">
                                    <a href="{{ route('admin.graduated.view', ['year' => $row['year']]) }}" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px; margin-top: 10px;">
                                        <i class="fas fa-list text-sm" aria-hidden="true"></i>
                                        View Year
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center min-h-[min(400px,50vh)] py-12 sm:py-16 px-4">
                            <div class="rounded-3xl p-8 sm:p-12 text-center w-full max-w-lg">
                                <div class="dashboard-stat-icon dashboard-stat-icon--blue w-24 h-24 rounded-2xl mx-auto mb-6 flex items-center justify-center" style="border-radius: 16px;">
                                    <i class="fas fa-graduation-cap text-4xl" aria-hidden="true"></i>
                                </div>
                                <h2 class="text-xl font-normal tracking-tight mb-2" style="color: var(--on-surface);">No graduation years found</h2>
                                <p class="text-sm font-normal mb-0 leading-relaxed" style="color: var(--on-surface-variant);">There are no graduated students on record. Graduation dates are set when promoting students to &quot;Graduated&quot; or when editing a student.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
@endsection
