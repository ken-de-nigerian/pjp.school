@extends('layouts.app', ['title' => 'Online entrance'])

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Entrance examination applicants"
                pill="Admin"
                title="Entrance examination applicants"
                description="View and manage online entrance applications. Export the full list to PDF for printing."
            >
                @if(!$applicants->isEmpty() && Route::has('admin.online_entrance.pdf'))
                    <x-slot name="actions">
                        <a href="{{ route('admin.online_entrance.pdf') }}" class="admin-dashboard-hero__btn admin-dashboard-hero__btn--primary w-full lg:w-auto justify-center min-h-[44px] sm:min-h-0">
                            <i class="fas fa-file-pdf text-[10px] sm:text-xs" aria-hidden="true"></i>
                            <span>Export to PDF</span>
                        </a>
                    </x-slot>
                @endif
            </x-admin.hero-page>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                @if($applicants->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 md:py-40 px-6">
                        <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center" style="border-radius: 16px;">
                            <i class="fas fa-file-alt text-3xl" aria-hidden="true"></i>
                        </div>
                        <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No applicants yet</h2>
                        <p class="text-sm text-center max-w-sm" style="color: var(--on-surface-variant);">Entrance applications will appear here when candidates submit online.</p>
                    </div>
                @else
                    <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0 border-x border-b" style="border-color: var(--outline-variant);">
                        <ul class="divide-y divide-[var(--outline-variant)]" role="list">
                            <li class="flex items-center gap-3 sm:gap-4 px-5 sm:px-6 py-3" style="background: var(--surface-container); border-color: var(--outline-variant);">
                                <span class="text-xs font-medium w-8 flex-shrink-0" style="color: var(--on-surface-variant);">#</span>
                                <span class="w-10 flex-shrink-0 text-xs font-medium text-center sm:text-left" style="color: var(--on-surface-variant);" aria-hidden="true"></span>
                                <span class="text-xs font-medium flex-1 min-w-0" style="color: var(--on-surface-variant);">ID / Name</span>
                                <span class="text-xs font-medium flex-1 min-w-0 hidden sm:block" style="color: var(--on-surface-variant);">Gender & DOB</span>
                                <span class="text-xs font-medium flex-1 min-w-0 hidden md:block" style="color: var(--on-surface-variant);">School / Class</span>
                                <span class="text-xs font-medium flex-shrink-0 w-24 text-right" style="color: var(--on-surface-variant);">Actions</span>
                            </li>
                            @foreach($applicants as $index => $app)
                                @php
                                    $name = trim(($app->candidates_surname ?? '') . ', ' . ($app->candidates_firstname ?? '') . ' ' . ($app->candidates_middlename ?? ''));
                                    $avatarForInitial = trim(($app->candidates_firstname ?? '') . ' ' . ($app->candidates_surname ?? ''));
                                    $avatarInitial = $avatarForInitial !== '' ? mb_substr($avatarForInitial, 0, 1) : 'A';
                                    $avatarSrc = asset('storage/students/default.png');
                                @endphp
                                <li class="flex items-center gap-3 sm:gap-4 px-5 sm:px-6 py-4 transition-colors" style="background: var(--surface-container-lowest);">
                                    <span class="text-sm font-medium w-8 flex-shrink-0" style="color: var(--on-surface-variant);">{{ $index + 1 }}</span>
                                    <img src="{{ $avatarSrc }}" alt="" width="40" height="40" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($avatarInitial) }}&size=80'">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium truncate" style="color: var(--on-surface);">
                                            @if(isset($app->id))
                                                <a href="{{ route('admin.online_entrance.show', $app) }}" class="hover:underline" style="color: var(--primary);">{{ e($app->uniqueID) }}</a>
                                            @else
                                                {{ e($app->uniqueID) }}
                                            @endif
                                        </p>
                                        <p class="text-xs truncate" style="color: var(--on-surface-variant);">{{ $name ?: '—' }}</p>
                                    </div>

                                    <div class="min-w-0 flex-1 hidden sm:block">
                                        <p class="text-sm truncate" style="color: var(--on-surface);"><span class="rounded px-2 py-0.5 text-xs" style="background: var(--surface-container-high); color: var(--on-surface-variant);">{{ e($app->selectgender ?? '—') }}</span></p>
                                        <p class="text-xs truncate" style="color: var(--on-surface-variant);">{{ e($app->candidates_date_of_birth ?? '—') }}</p>
                                    </div>

                                    <div class="min-w-0 flex-1 hidden md:block">
                                        <p class="text-sm truncate" style="color: var(--on-surface);">{{ e($app->candidates_current_school ?? '—') }}</p>
                                        <p class="text-xs truncate" style="color: var(--on-surface-variant);">Class: {{ e($app->candidates_current_class ?? '—') }}</p>
                                    </div>

                                    <div class="flex-shrink-0 w-24 text-right">
                                        @if(isset($app->id))
                                            <a href="{{ route('admin.online_entrance.show', $app) }}" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl text-xs sm:text-sm font-medium transition-opacity hover:opacity-90" style="background: var(--primary-container); color: var(--on-primary-container); border-radius: 12px;">
                                                <i class="fas fa-eye text-xs" aria-hidden="true"></i>
                                                <span class="hidden sm:inline">View</span>
                                            </a>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection
