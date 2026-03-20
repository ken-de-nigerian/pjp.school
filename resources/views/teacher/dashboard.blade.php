@php use Carbon\Carbon; @endphp
@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto pb-24 lg:pb-8 scrollbar-hide" style="background: var(--bg-primary);">
        <div id="page-home" class="page-content max-w-7xl mx-auto px-6 lg:px-8 py-8 lg:py-12">
            <x-admin.hero-shell aria-label="Teacher dashboard overview">
                <header class="admin-dashboard-hero__header">
                    <div class="admin-dashboard-hero__welcome">
                        <div class="dashboard-profile-avatar admin-dashboard-hero__avatar flex-shrink-0 overflow-hidden rounded-full border-2" style="width: 56px; height: 56px;">
                            <img class="h-full w-full object-cover" src="{{ asset('storage/teachers/' . ($layoutTeacher->imagelocation ?? '')) }}" alt="{{ e($layoutTeacher->firstname ?? 'Teacher') }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($layoutTeacher->name ?? 'Teacher') }}&size=112&background=bbdefb&color=0d47a1'; this.onerror=null;">
                        </div>
                        <div class="min-w-0 flex-1 flex flex-col gap-0.5">
                            <p class="admin-dashboard-hero__eyebrow">Welcome back</p>
                            <p class="admin-dashboard-hero__name text-base sm:text-lg font-semibold truncate">{{ $layoutTeacher->firstname ?? 'Class' }} {{ $layoutTeacher->lastname ?? 'Teacher' }}</p>
                            <p class="admin-dashboard-hero__email text-sm truncate">{{ $layoutTeacher->email ?? '' }}</p>
                        </div>
                    </div>
                </header>

                <nav class="admin-dashboard-hero__quick-nav" aria-label="Teacher quick navigation">
                    @if($canOperateActiveTeacherFeatures)
                        <a href="{{ route('teacher.attendance.index') }}" class="admin-dashboard-hero__tile group">
                            <span class="admin-dashboard-hero__tile-icon" aria-hidden="true"><i class="fas fa-calendar-check"></i></span>
                            <span class="admin-dashboard-hero__tile-label">Attendance</span>
                        </a>
                    @else
                        <div class="admin-dashboard-hero__tile opacity-50 pointer-events-none select-none" aria-hidden="true">
                            <span class="admin-dashboard-hero__tile-icon"><i class="fas fa-calendar-check"></i></span>
                            <span class="admin-dashboard-hero__tile-label">Attendance</span>
                        </div>
                    @endif

                    @if($canOperateActiveTeacherFeatures)
                        <a href="{{ route('teacher.behavioral.index') }}" class="admin-dashboard-hero__tile group">
                            <span class="admin-dashboard-hero__tile-icon" aria-hidden="true"><i class="fas fa-brain"></i></span>
                            <span class="admin-dashboard-hero__tile-label">Behavioural</span>
                        </a>
                    @else
                        <div class="admin-dashboard-hero__tile opacity-50 pointer-events-none select-none" aria-hidden="true">
                            <span class="admin-dashboard-hero__tile-icon"><i class="fas fa-brain"></i></span>
                            <span class="admin-dashboard-hero__tile-label">Behavioural</span>
                        </div>
                    @endif

                    <a href="{{ route('teacher.class.index') }}" class="admin-dashboard-hero__tile group">
                        <span class="admin-dashboard-hero__tile-icon" aria-hidden="true"><i class="fas fa-chalkboard"></i></span>
                        <span class="admin-dashboard-hero__tile-label">Classes</span>
                    </a>

                    <a href="{{ route('teacher.results.index') }}" class="admin-dashboard-hero__tile group">
                        <span class="admin-dashboard-hero__tile-icon" aria-hidden="true"><i class="fas fa-cloud-upload-alt"></i></span>
                        <span class="admin-dashboard-hero__tile-label">Results</span>
                    </a>
                </nav>
            </x-admin.hero-shell>

            <!-- Main Content (Quick Links are in header mega menu) -->
            <div class="grid grid-cols-1 gap-8">
                <!-- Recent Announcements -->
                <div class="space-y-6 lg:space-y-8">
                    <div class="card-refined rounded-2xl p-4 lg:p-6" style="border-color: var(--outline-variant);">
                        @if(empty($get_news))
                            <div class="flex flex-col items-center justify-center py-16 md:py-24 px-6">
                                <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center" style="border-radius: 16px;">
                                    <i class="fas fa-bullhorn text-3xl" aria-hidden="true"></i>
                                </div>

                                <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No announcements yet</h2>

                                <p class="text-sm text-center max-w-sm mb-6" style="color: var(--on-surface-variant);">
                                    Create your first announcement to keep staff, students and parents informed.
                                </p>
                            </div>
                        @else
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
                                <div>
                                    <h2 class="text-xl lg:text-2xl font-bold mb-1" style="color: var(--text-primary);">Recent Announcements</h2>
                                    <p class="text-sm" style="color: var(--text-secondary);">Latest school notices and updates</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4">
                                @foreach($get_news ?? [] as $item)
                                    <div class="card-refined rounded-2xl shadow-sm p-3 sm:p-4 overflow-hidden" style="border-color: var(--outline-variant);">
                                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                                            <!-- Card image: responsive aspect ratio -->
                                            <div
                                                class="w-full sm:w-1/4 flex-shrink-0 overflow-hidden rounded-xl aspect-video sm:aspect-square sm:min-w-[100px] max-sm:max-h-44">
                                                @if($item->imagelocation ?? null)
                                                    <img src="{{ asset('storage/news/'.$item->imagelocation) }}" alt="{{ e($item->title ?? '') }}" class="w-full h-full object-cover object-center" loading="lazy" onerror="this.src='{{ asset('storage/news/default.png') }}'; this.onerror=null;">
                                                @else
                                                    <div class="news-placeholder-bg w-full h-full rounded-xl flex items-center justify-center dashboard-stat-icon dashboard-stat-icon--blue">
                                                        <i class="fas fa-bullhorn text-xl sm:text-2xl"></i>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Card body -->
                                            <div class="flex-1 min-w-0 flex flex-col relative">
                                                <!-- Title -->
                                                <h5 class="font-semibold mb-0 text-sm sm:text-base line-clamp-2" style="color: var(--text-primary);">
                                                    <a>{{ e($item->title ?? '') }}</a>
                                                </h5>

                                                @if(!empty($item->category))
                                                    <small class="mt-1 flex items-center gap-1.5 text-xs" style="color: var(--text-secondary);">
                                                        <i class="fas fa-tag flex-shrink-0"></i><span class="truncate">{{ e($item->category) }}</span>
                                                    </small>
                                                @endif

                                                <!-- Date and buttons -->
                                                <div class="flex flex-col sm:flex-row flex-wrap justify-between items-start sm:items-center gap-2 mt-3 mt-auto pt-2 border-t" style="border-color: var(--card-border);">
                                                    <div class="flex items-center flex-wrap gap-1 text-xs sm:text-sm">
                                                        <span class="font-semibold" style="color: var(--text-primary);">Posted</span>
                                                        <span style="color: var(--text-secondary);">
                                                            /
                                                            @if(isset($item->created_at))
                                                                {{ Carbon::parse($item->created_at)->diffForHumans() }}
                                                            @else
                                                                {{ $item->date_added?->format('M j, Y') }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <x-pagination :paginator="$news ?? null" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
