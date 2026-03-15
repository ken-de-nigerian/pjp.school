@php use Carbon\Carbon; @endphp
@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto pb-24 lg:pb-8 scrollbar-hide" style="background: var(--bg-primary);">
        <div id="page-home" class="page-content max-w-7xl mx-auto px-6 lg:px-8 py-8 lg:py-12">
            <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 lg:mb-8">
                <div class="flex items-center gap-4 min-w-0 flex-1">
                    <div class="dashboard-profile-avatar flex-shrink-0 overflow-hidden rounded-full border-2"
                         style="width: 56px; height: 56px; border-color: var(--outline-variant); background: var(--surface-container-low);">
                        <img class="h-full w-full object-cover"
                             src="{{ asset('storage/staffs/' . ($layoutAdmin->profileImage ?? '')) }}"
                             alt="{{ e($layoutAdmin->name ?? 'Admin') }}"
                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($layoutAdmin->name ?? 'Admin') }}&size=112&background=bbdefb&color=0d47a1'; this.onerror=null;">
                    </div>
                    <div class="min-w-0 flex-1 flex flex-col gap-0.5">
                        <p class="text-xs font-medium uppercase tracking-wide truncate" style="color: var(--text-secondary);">Welcome back</p>
                        <p class="text-base sm:text-lg font-semibold truncate" style="color: var(--on-surface);">{{ $layoutAdmin->name ?? 'Admin' }}</p>
                        <p class="text-sm truncate" style="color: var(--on-surface-variant);">{{ $layoutAdmin->email ?? '' }}</p>
                    </div>
                </div>

                @if(isset($role) && (($role->manage_scratch_card ?? 0) == 1 || ($role->bulk_sms ?? 0) == 1))
                    <div class="flex gap-2">
                        @if(($role->manage_scratch_card ?? 0) == 1 && Route::has('admin.card.index'))
                            <a href="{{ route('admin.card.index') }}"
                               class="btn-primary-tonal inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium">
                                <i class="fas fa-ticket-alt" aria-hidden="true"></i>Scratch Card
                            </a>
                        @endif

                        @if(($role->bulk_sms ?? 0) == 1 && Route::has('admin.bulk.index'))
                            <a href="{{ route('admin.bulk.index') }}"
                               class="btn-primary-tonal inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium">
                                <i class="fas fa-sms" aria-hidden="true"></i>Bulk SMS
                            </a>
                        @endif
                    </div>
                @endif
            </header>

            <!-- Top Quick Nav -->
            <div class="grid grid-cols-4 gap-3 lg:mb-8">
                <a href="{{ route('admin.classes') }}"
                   class="card-refined rounded-xl p-4 flex flex-col items-center gap-3 hover-lift transition-all group" style="border-color: var(--outline-variant);">
                    <div class="dashboard-quick-icon dashboard-quick-icon--blue w-12 h-12">
                        <i class="fas fa-chalkboard text-lg"></i>
                    </div>
                    <span class="font-medium text-sm" style="color: var(--text-primary);">Classes</span>
                </a>

                <a href="{{ route('admin.teachers.index') }}"
                   class="card-refined rounded-xl p-4 flex flex-col items-center gap-3 hover-lift transition-all group" style="border-color: var(--outline-variant);">
                    <div class="dashboard-quick-icon dashboard-quick-icon--blue w-12 h-12">
                        <i class="fas fa-user-tie text-lg"></i>
                    </div>
                    <span class="font-medium text-sm" style="color: var(--text-primary);">Teachers</span>
                </a>

                <a href="{{ route('admin.subjects.index') }}"
                   class="card-refined rounded-xl p-4 flex flex-col items-center gap-3 hover-lift transition-all group" style="border-color: var(--outline-variant);">
                    <div class="dashboard-quick-icon dashboard-quick-icon--blue w-12 h-12">
                        <i class="fas fa-book-open text-lg"></i>
                    </div>
                    <span class="font-medium text-sm" style="color: var(--text-primary);">Subjects</span>
                </a>

                <a href="{{ route('admin.upload-results') }}"
                   class="card-refined rounded-xl p-4 flex flex-col items-center gap-3 hover-lift transition-all group" style="border-color: var(--outline-variant);">
                    <div class="dashboard-quick-icon dashboard-quick-icon--blue w-12 h-12">
                        <i class="fas fa-poll text-lg"></i>
                    </div>
                    <span class="font-medium text-sm text-center leading-tight" style="color: var(--text-primary);">Results</span>
                </a>
            </div>

            <div class="grid grid-cols-12 gap-3 lg:gap-4 mb-6 lg:mb-8">
                <div class="col-span-12 sm:col-span-4 card-refined rounded-xl p-4 lg:p-5 hover-lift transition-all" style="border-color: var(--outline-variant);">
                    <div class="flex items-center justify-between mb-3">
                        <div class="dashboard-stat-icon dashboard-stat-icon--blue w-10 h-10">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="text-xs font-medium" style="color: var(--text-secondary);">All Students</span>
                    </div>
                    <div class="text-xl lg:text-2xl font-bold mb-1" style="color: var(--text-primary);">{{ $count_all_students ?? 0 }}</div>
                    <div class="text-xs" style="color: var(--text-secondary);">Total Students</div>
                </div>

                <div class="col-span-12 sm:col-span-4 card-refined rounded-xl p-4 lg:p-5 hover-lift transition-all" style="border-color: var(--outline-variant);">
                    <div class="flex items-center justify-between mb-3">
                        <div class="dashboard-stat-icon dashboard-stat-icon--blue w-10 h-10">
                            <i class="fas fa-bed"></i>
                        </div>
                        <span class="text-xs font-medium" style="color: var(--text-secondary);">Boarding</span>
                    </div>
                    <div class="text-xl lg:text-2xl font-bold mb-1" style="color: var(--text-primary);">{{ $count_boarding_students ?? 0 }}</div>
                    <div class="text-xs" style="color: var(--text-secondary);">Boarding Students</div>
                </div>

                <div class="col-span-12 sm:col-span-4 card-refined rounded-xl p-4 lg:p-5 hover-lift transition-all" style="border-color: var(--outline-variant);">
                    <div class="flex items-center justify-between mb-3">
                        <div class="dashboard-stat-icon dashboard-stat-icon--blue w-10 h-10">
                            <i class="fas fa-sun"></i>
                        </div>
                        <span class="text-xs font-medium" style="color: var(--text-secondary);">Day</span>
                    </div>
                    <div class="text-xl lg:text-2xl font-bold mb-1" style="color: var(--text-primary);">{{ $count_day_students ?? 0 }}</div>
                    <div class="text-xs" style="color: var(--text-secondary);">Day Students</div>
                </div>

                <div class="col-span-12 sm:col-span-6 card-refined rounded-xl p-4 lg:p-5 hover-lift transition-all" style="border-color: var(--outline-variant);">
                    <div class="flex items-center justify-between mb-3">
                        <div class="dashboard-stat-icon dashboard-stat-icon--blue w-10 h-10">
                            <i class="fas fa-book"></i>
                        </div>
                        <span class="text-xs font-medium" style="color: var(--text-secondary);">Subjects</span>
                    </div>
                    <div class="text-xl lg:text-2xl font-bold mb-1" style="color: var(--text-primary);">{{ $count_subjects ?? 0 }}</div>
                    <div class="text-xs" style="color: var(--text-secondary);">Junior &amp; Senior Subjects</div>
                </div>

                <div class="col-span-12 sm:col-span-6 card-refined rounded-xl p-4 lg:p-5 hover-lift transition-all" style="border-color: var(--outline-variant);">
                    <div class="flex items-center justify-between mb-3">
                        <div class="dashboard-stat-icon dashboard-stat-icon--blue w-10 h-10">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <span class="text-xs font-medium" style="color: var(--text-secondary);">Teachers</span>
                    </div>
                    <div class="text-xl lg:text-2xl font-bold mb-1" style="color: var(--text-primary);">{{ $count_teachers ?? 0 }}</div>
                    <div class="text-xs" style="color: var(--text-secondary);">Total Teachers</div>
                </div>
            </div>

            <!-- Main Content (Quick Links are in header mega menu) -->
            <div class="grid grid-cols-1 gap-8">

                <!-- Recent Announcements -->
                <div class="space-y-6 lg:space-y-8">
                    <div class="card-refined rounded-2xl p-4 lg:p-6" style="border-color: var(--outline-variant);">
                        <div
                            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
                            <div>
                                <h2 class="text-xl lg:text-2xl font-bold mb-1" style="color: var(--text-primary);">
                                    Recent Announcements</h2>
                                <p class="text-sm" style="color: var(--text-secondary);">Latest school notices and
                                    updates</p>
                            </div>

                            @if(Route::has('admin.news.index'))
                                <a href="{{ route('admin.news.index') }}"
                                   class="text-primary-400 hover:text-primary-300 text-sm font-semibold flex items-center gap-2 transition min-h-[44px]">
                                    View All <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            @endif
                        </div>

                        @if(empty($get_news))
                            <div class="text-center py-12">
                                <div class="dashboard-stat-icon dashboard-stat-icon--blue w-16 h-16 rounded-full mx-auto mb-4">
                                    <i class="fas fa-bullhorn text-2xl"></i>
                                </div>
                                <p class="mb-4" style="color: var(--text-secondary);">No announcements yet</p>
                                @if(Route::has('admin.news.create'))
                                    <div class="flex justify-center w-full">
                                        <a href="{{ route('admin.news.create') }}"
                                           class="btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] rounded-xl font-medium text-sm transition-all duration-200 hover:opacity-95 active:scale-[0.98]"
                                           style="border-radius: 12px; width: fit-content;">
                                            <i class="fas fa-plus text-sm" aria-hidden="true"></i>
                                            Add Announcement
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4">
                                @foreach($get_news ?? [] as $item)
                                    <div class="card-refined rounded-2xl shadow-sm p-3 sm:p-4 overflow-hidden" style="border-color: var(--outline-variant);">
                                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                                            <!-- Card image: responsive aspect ratio -->
                                            <div
                                                class="w-full sm:w-1/4 flex-shrink-0 overflow-hidden rounded-xl aspect-video sm:aspect-square sm:min-w-[100px] max-sm:max-h-44">
                                                @if($item->imagelocation ?? null)
                                                    <img src="{{ asset('storage/news/'.$item->imagelocation) }}"
                                                         alt="{{ e($item->title ?? '') }}"
                                                         class="w-full h-full object-cover object-center"
                                                         loading="lazy"
                                                         onerror="this.src='{{ asset('storage/news/default.png') }}'; this.onerror=null;">
                                                @else
                                                    <div class="news-placeholder-bg w-full h-full rounded-xl flex items-center justify-center dashboard-stat-icon dashboard-stat-icon--blue">
                                                        <i class="fas fa-bullhorn text-xl sm:text-2xl"></i>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Card body -->
                                            <div class="flex-1 min-w-0 flex flex-col relative">
                                                <!-- Title -->
                                                <h5 class="font-semibold mb-0 text-sm sm:text-base line-clamp-2"
                                                    style="color: var(--text-primary);">
                                                    <a href="{{ route('admin.news.show', $item->id) }}"
                                                       class="hover:underline">{{ e($item->title ?? '') }}</a>
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
                                                    <div class="flex flex-wrap gap-1.5 sm:gap-2">
                                                        @if(Route::has('admin.news.edit'))
                                                            <a href="{{ route('admin.news.edit', $item->id) }}"
                                                               class="inline-flex items-center gap-1 px-2 py-1 sm:py-1.5 rounded-lg text-xs sm:text-sm font-medium bg-blue-500/10 text-blue-600 hover:bg-blue-500/20 transition"><i class="fas fa-pen-square text-xs"></i> Edit</a>
                                                        @endif
                                                        @if(Route::has('admin.news.destroy'))
                                                            <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" class="inline"
                                                                  onsubmit="return confirm('Are you sure you want to delete this news?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                        class="inline-flex items-center gap-1 px-2 py-1 sm:py-1.5 rounded-lg text-xs sm:text-sm font-medium bg-red-500/10 text-red-600 hover:bg-red-500/20 transition">
                                                                    <i class="fas fa-trash-alt text-xs"></i> Delete
                                                                </button>
                                                            </form>
                                                        @endif
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
