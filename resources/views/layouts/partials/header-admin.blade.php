<style>
    /* M3: logo – no glow, no scale/translate, state layer only */
    .logo-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 8px 12px;
        margin: -8px -12px;
        border-radius: 20px;
        transition: background-color 0.2s ease;
    }

    .logo-container:hover {
        background: rgba(var(--primary-rgb), 0.08);
    }

    [data-theme="dark"] .logo-container:hover {
        background: rgba(255, 255, 255, 0.08);
    }

    .logo-icon-container {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
        overflow: hidden;
        background: var(--primary-container);
        border: none;
        box-shadow: none;
    }

    .logo-glow {
        display: none;
    }

    .logo-icon {
        color: var(--on-primary-container);
        fill: var(--on-primary-container);
    }

    .logo-text {
        font-family: 'Roboto', sans-serif;
        font-weight: 500;
        font-size: 1.25rem;
        letter-spacing: 0;
        color: var(--on-surface);
    }

    .logo-text-accent {
        font-weight: 400;
        color: var(--primary);
    }

    /* Responsive Sizes */
    .logo-icon-container {
        width: 2.5rem;
        height: 2.5rem;
    }

    .logo-icon {
        width: 1.25rem;
        height: 1.25rem;
    }

    .logo-text {
        font-size: 1.25rem;
        line-height: 1.2;
    }

    @media (min-width: 640px) {
        .logo-icon-container {
            width: 2.75rem;
            height: 2.75rem;
        }

        .logo-icon {
            width: 1.5rem;
            height: 1.5rem;
        }

        .logo-text {
            font-size: 1.5rem;
        }
    }

    @media (min-width: 1024px) {
        .logo-icon-container {
            width: 3rem;
            height: 3rem;
        }

        .logo-icon {
            width: 1.75rem;
            height: 1.75rem;
        }

        .logo-text {
            font-size: 1.75rem;
        }
    }

    [data-theme="dark"] .logo-icon-container {
        background: var(--primary-container);
    }
    [data-theme="dark"] .logo-icon {
        color: var(--on-primary-container);
        fill: var(--on-primary-container);
    }

    /* Mega menu: centered wrapper + panel with open animation */
    #mega-menu-dropdown {
        visibility: hidden;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s ease, visibility 0.25s ease;
    }
    #mega-menu-dropdown.mega-menu-open {
        visibility: visible;
        opacity: 1;
        pointer-events: auto;
    }
    .mega-menu-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        z-index: 0;
    }
    [data-theme="dark"] .mega-menu-backdrop {
        background: rgba(0, 0, 0, 0.35);
    }
    .mega-menu-panel {
        background: transparent;
        border: none;
        box-shadow: none;
        transform: translateY(-16px) scale(0.98);
        opacity: 0;
        transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.25s ease;
    }
    .mega-menu-inner {
        background: var(--surface-container-lowest);
        border: 1px solid var(--outline-variant);
        border-radius: 1rem;
        box-shadow: 0 2px 6px 2px rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }
    [data-theme="dark"] .mega-menu-inner {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
    }
    #mega-menu-dropdown.mega-menu-open .mega-menu-panel {
        transform: translateY(0) scale(1);
        opacity: 1;
    }
    .mega-menu-panel .mega-menu-col {
        opacity: 0;
        transform: translateY(8px);
        transition: opacity 0.3s ease, transform 0.3s ease;
    }
    #mega-menu-dropdown.mega-menu-open .mega-menu-panel .mega-menu-col:nth-child(1) { transition-delay: 0.05s; }
    #mega-menu-dropdown.mega-menu-open .mega-menu-panel .mega-menu-col:nth-child(2) { transition-delay: 0.1s; }
    #mega-menu-dropdown.mega-menu-open .mega-menu-panel .mega-menu-col:nth-child(3) { transition-delay: 0.15s; }
    #mega-menu-dropdown.mega-menu-open .mega-menu-panel .mega-menu-col:nth-child(4) { transition-delay: 0.2s; }
    #mega-menu-dropdown.mega-menu-open .mega-menu-panel .mega-menu-col {
        opacity: 1;
        transform: translateY(0);
    }
    .mega-menu-link {
        transition: background 0.15s ease, transform 0.15s ease;
    }
    .mega-menu-link:hover {
        background: var(--surface-container);
    }
    [data-theme="dark"] .mega-menu-link:hover {
        background: var(--surface-container);
    }

    /* Nav links: M3 text button style */
    .nav-link-header {
        color: var(--on-surface);
        transition: background-color 0.2s ease;
    }
    .nav-link-header:hover {
        background: var(--surface-container);
    }

    /* Profile trigger */
    .profile-trigger-header:hover {
        background: var(--surface-container);
    }

    /* Profile dropdown */
    .profile-dropdown-panel {
        background: var(--surface-container-lowest);
        border: 1px solid var(--outline-variant);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    [data-theme="dark"] .profile-dropdown-panel {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    }
    .profile-dropdown-link {
        color: var(--on-surface);
        transition: background-color 0.2s ease;
    }
    .profile-dropdown-link:hover {
        background: var(--surface-container);
    }
    .profile-dropdown-link--danger {
        color: #b3261e;
    }
    .profile-dropdown-link--danger:hover {
        background: rgba(179, 38, 30, 0.08);
    }
    .profile-dropdown-divider {
        height: 1px;
        background: var(--outline-variant);
    }

    /* Header divider */
    .header-nav-divider {
        height: 24px;
        width: 1px;
        background: var(--outline-variant);
        margin: 0 8px;
    }

    .search-wrap-header input::placeholder {
        color: var(--on-surface-variant);
        opacity: 0.9;
    }
</style>

<nav class="sticky top-0 z-50 border-b" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="flex justify-between items-center h-16 lg:h-20">
            <div class="flex items-center gap-8 lg:gap-8">
                <!-- Menu (mobile: hamburger; desktop: opens centered modal) -->
                <button onclick="toggleMobileMenu()" class="header-icon-btn" aria-label="Open menu">
                    <i class="fas fa-bars text-sm"></i>
                </button>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="logo-container">
                        <div class="logo-icon-container">
                            <div class="logo-glow"></div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="logo-icon">
                                <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path>
                            </svg>
                        </div>
                        <span class="logo-text">{{ config('app.name') }}</span>
                    </a>
                </div>

                <!-- Desktop: Dashboard + Quick Links (mega menu) -->
                <div class="hidden lg:flex items-center gap-1 relative">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link-header px-4 py-2 rounded-full text-sm font-medium">Dashboard</a>
                    <div>
                        <button onclick="toggleDropdown('mega-menu-dropdown')" class="nav-link-header px-4 py-2 rounded-full text-sm font-medium flex items-center gap-1.5">
                            <i class="fas fa-th-large text-sm opacity-80"></i>
                            Quick Links
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200" id="mega-menu-arrow"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-2 lg:gap-4">
                    <!-- Search (Desktop only) -->
                    <div class="hidden lg:flex items-center mr-4">
                        <div class="search-wrap-header flex items-center gap-2 rounded-full pl-4 pr-3 py-2 w-48 focus-within:w-64 transition-[width] duration-200" style="background: var(--surface-container);">
                            <i class="fas fa-search text-sm" style="color: var(--on-surface-variant);"></i>
                            <input type="text" placeholder="Search..." class="flex-1 min-w-0 border-0 bg-transparent py-0.5 text-sm focus:ring-0 focus:outline-none" style="color: var(--on-surface);">
                        </div>
                    </div>

                    <!-- Theme Toggle -->
                    <button onclick="toggleTheme()" class="header-icon-btn relative" title="Toggle Theme">
                        <i id="theme-icon" class="fas fa-moon text-sm"></i>
                    </button>

                    <!-- Notifications -->
                    @if(Route::has('admin.notifications.index'))
                        @php $layoutNotifications = $layoutNotifications ?? collect(); @endphp
                        <button onclick="toggleModal('notifications-modal')" class="header-icon-btn relative" aria-label="Notifications">
                            <i class="fas fa-bell text-sm"></i>
                            @if($layoutNotifications->isNotEmpty())
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full" aria-hidden="true"></span>
                            @endif
                        </button>
                    @endif

                    <div class="header-nav-divider"></div>

                    <!-- Profile Dropdown (pushed to extreme right on mobile) -->
                    <div class="relative ml-auto lg:ml-0" id="profile-container">
                        <button onclick="toggleProfileDropdown()" class="profile-trigger-header flex items-center gap-2 lg:gap-3 px-2 lg:px-3 py-2 rounded-full min-h-[44px] transition-colors">
                            <img src="{{ asset('storage/staffs/' . ($layoutAdmin->profileImage ?? '')) }}" alt="Profile" class="w-9 h-9 rounded-full object-cover ring-2 ring-offset-2" style="ring-color: var(--surface-container-lowest);">
                            <div class="hidden xl:block text-left">
                                <div class="font-medium text-sm leading-tight" style="color: var(--on-surface);">{{ $layoutAdmin->name ?? 'Admin' }}</div>
                                <div class="text-[10px] font-medium uppercase tracking-wider" style="color: var(--on-surface-variant);">{{ $layoutAdmin->email ?? '' }}</div>
                            </div>
                        </button>

                        <div id="profile-dropdown" class="profile-dropdown-panel hidden absolute right-0 mt-2 w-56 rounded-xl py-2 z-50">
                            <a href="{{ route('admin.profile.show') }}" class="profile-dropdown-link flex items-center gap-3 px-4 py-2.5 text-sm">
                                <i class="fas fa-user w-5 text-center" style="color: var(--on-surface-variant);"></i> Profile
                            </a>
                            <a href="{{ route('admin.settings.index') }}" class="profile-dropdown-link flex items-center gap-3 px-4 py-2.5 text-sm">
                                <i class="fas fa-cog w-5 text-center" style="color: var(--on-surface-variant);"></i> Settings
                            </a>
                            <div class="profile-dropdown-divider my-2"></div>
                            <form action="{{ route('admin.logout') }}" method="POST" class="block">
                                @csrf
                                <button type="submit" class="profile-dropdown-link profile-dropdown-link--danger w-full flex items-center gap-3 px-4 py-2.5 text-sm text-left">
                                    <i class="fas fa-sign-out-alt w-5 text-center"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mega Menu (full width, centered, below nav row) -->
            <div id="mega-menu-dropdown" class="absolute left-0 right-0 top-full flex justify-center pt-2 z-50 px-4">
                <div class="mega-menu-backdrop" onclick="toggleDropdown('mega-menu-dropdown')" aria-hidden="true"></div>
                <div class="mega-menu-panel relative z-10 w-full max-w-[920px] py-2">
                    <div class="mega-menu-inner rounded-2xl py-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-0 px-4">
                        <!-- Column 1: Students -->
                        <div class="p-4 mega-menu-col">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="mega-col-icon mega-col-icon--blue w-10 h-10 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-graduate text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-sm" style="color: var(--text-primary);">Students / Classes</h3>
                                    <p class="text-xs" style="color: var(--text-secondary);">Browse by class</p>
                                </div>
                            </div>
                            <div class="space-y-0.5">
                                @if(Route::has('admin.classes'))<a href="{{ route('admin.classes') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-users w-5 text-blue-500 text-center"></i> Students / Classes</a>@endif
                                <a href="{{ route('admin.students.create') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-user-plus w-5 text-emerald-500 text-center"></i> Register</a>
                                <a href="{{ route('admin.students.houses') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-chalkboard w-5 text-purple-500 text-center"></i> Houses</a>
                                <a href="{{ route('admin.students.academic_advancement') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-arrow-up w-5 text-amber-500 text-center"></i> Promote / Demote</a>
                                <a href="{{ route('admin.graduated') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-graduation-cap w-5 text-slate-500 text-center"></i> Graduated</a>
                                <a href="{{ route('admin.left_school') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-sign-out-alt w-5 text-gray-500 text-center"></i> Left School</a>
                            </div>
                        </div>
                        <!-- Column 2: Teachers & Staff -->
                        <div class="p-4 border-l mega-menu-col" style="border-color: var(--outline-variant);">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="mega-col-icon mega-col-icon--orange w-10 h-10 rounded-full flex items-center justify-center">
                                    <i class="fas fa-chalkboard-teacher text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-sm" style="color: var(--text-primary);">Teachers & Staff</h3>
                                    <p class="text-xs" style="color: var(--text-secondary);">Staff & roles</p>
                                </div>
                            </div>
                            <div class="space-y-0.5">
                                <a href="{{ route('admin.teachers.index') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-users w-5 text-orange-500 text-center"></i> Teachers & Classes</a>
                                <a href="{{ route('admin.register_teacher.form') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-user-plus w-5 text-amber-500 text-center"></i> Register Teacher</a>
                                <a href="{{ route('admin.assign_teacher_to_class.form') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-link w-5 text-amber-600 text-center"></i> Assign To Class</a>
                                @if(Route::has('admin.staff.index'))<a href="{{ route('admin.staff.index') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-user-tie w-5 text-slate-500 text-center"></i> All Staff</a>@endif
                                @if(Route::has('admin.roles.index'))<a href="{{ route('admin.roles.index') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-user-shield w-5 text-slate-600 text-center"></i> Roles & Permissions</a>@endif
                            </div>
                        </div>
                        <!-- Column 3: Academics -->
                        <div class="p-4 border-l mega-menu-col" style="border-color: var(--outline-variant);">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="mega-col-icon mega-col-icon--indigo w-10 h-10 rounded-full flex items-center justify-center">
                                    <i class="fas fa-book-open text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-sm" style="color: var(--text-primary);">Academics</h3>
                                    <p class="text-xs" style="color: var(--text-secondary);">Subjects & results</p>
                                </div>
                            </div>
                            <div class="space-y-0.5">
                                <a href="{{ route('admin.subjects.index') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-book w-5 text-indigo-500 text-center"></i> All Subjects</a>
                                <a href="{{ route('admin.subjects.fetch-classes') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-edit w-5 text-violet-500 text-center"></i> Register Students</a>
                                <a href="{{ route('admin.subjects.registered') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-list w-5 text-violet-600 text-center"></i> View Registered</a>
                                <div class="flex items-center gap-2 mt-3 mb-2 px-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: var(--primary-container); color: var(--on-primary-container);">
                                        <i class="fas fa-chart-line text-xs"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-xs" style="color: var(--text-primary);">Results</h4>
                                        <p class="text-[10px] leading-tight" style="color: var(--text-secondary);">Upload &amp; publish</p>
                                    </div>
                                </div>
                                <a href="{{ route('admin.upload-results') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-upload w-5 text-cyan-500 text-center"></i> Upload results</a>
                                <a href="{{ route('admin.publish-results') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-globe w-5 text-cyan-600 text-center"></i> Publish results</a>
                                @if(Route::has('admin.results-by-params'))<a href="{{ route('admin.results-by-params') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-search w-5 text-cyan-400 text-center"></i> Search Results</a>@endif
                                @if(Route::has('admin.transcript'))<a href="{{ route('admin.transcript') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-file-alt w-5 text-teal-500 text-center"></i> Transcript</a>@endif
                                @if(Route::has('admin.status.index'))<a href="{{ route('admin.status.index') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-check-circle w-5 text-teal-600 text-center"></i> Check Status</a>@endif
                            </div>
                        </div>
                        <!-- Column 4: Operations & System -->
                        <div class="p-4 border-l mega-menu-col" style="border-color: var(--outline-variant);">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="mega-col-icon mega-col-icon--emerald w-10 h-10 rounded-full flex items-center justify-center">
                                    <i class="fas fa-cogs text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-sm" style="color: var(--text-primary);">Operations</h3>
                                    <p class="text-xs" style="color: var(--text-secondary);">Daily & settings</p>
                                </div>

                            </div>
                            <div class="space-y-0.5">
                                @if(Route::has('admin.attendance.index'))<a href="{{ route('admin.attendance.index') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-calendar-check w-5 text-teal-500 text-center"></i> Attendance</a>@endif
                                @if(Route::has('admin.behavioral.index'))<a href="{{ route('admin.behavioral.index') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-clipboard-list w-5 text-teal-600 text-center"></i> Behavioural Analysis</a>@endif
                                @if(Route::has('admin.news.index'))<a href="{{ route('admin.news.index') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-bullhorn w-5 text-sky-500 text-center"></i> News</a>@endif
                                @if(Route::has('admin.notifications.index'))<a href="{{ route('admin.notifications.index') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-bell w-5 text-rose-500 text-center"></i> Notifications</a>@endif
                                @if(Route::has('admin.online_entrance.index'))<a href="{{ route('admin.online_entrance.index') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-file-alt w-5 text-lime-500 text-center"></i> Online Entrance</a>@endif
                                @if(Route::has('admin.bulk.index'))<a href="{{ route('admin.bulk.index') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-sms w-5 text-blue-600 text-center"></i> Bulk SMS</a>@endif
                                @if(Route::has('admin.card.index'))<a href="{{ route('admin.card.index') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-ticket-alt w-5 text-amber-500 text-center"></i> Scratch Card</a>@endif
                                @if(Route::has('admin.fees.index'))<a href="{{ route('admin.fees.index') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-money-bill-wave w-5 text-amber-500 text-center"></i> Fees</a>@endif
                                @if(Route::has('admin.timetable.index'))<a href="{{ route('admin.timetable.index') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-calendar-alt w-5 text-pink-500 text-center"></i> Timetable</a>@endif
                                <a href="{{ route('admin.settings.index') }}" class="mega-menu-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm" style="color: var(--text-primary);"><i class="fas fa-cog w-5 text-gray-500 text-center"></i> Settings</a>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Offcanvas Notifications: full-screen on mobile (scrollable above bottom nav), right panel on desktop -->
@if(Route::has('admin.notifications.index'))
    <div id="notifications-modal" class="fixed inset-0 bg-black/80 backdrop-blur-xl z-[60] hidden flex items-stretch lg:items-center lg:justify-end p-0">
        <div class="notifications-modal-panel card-refined flex flex-col w-full h-full max-h-screen lg:max-w-md lg:max-h-screen lg:rounded-l-2xl lg:rounded-r-none rounded-none" style="background: var(--card-bg); box-shadow: var(--elevation-2);">
            <div class="flex-shrink-0 p-4 sm:p-6 border-b flex items-center justify-between" style="background: var(--card-bg); border-color: var(--outline-variant);">
                <h2 class="text-xl sm:text-2xl font-bold" style="color: var(--on-surface);">Notifications</h2>
                <button type="button" onclick="closeModal('notifications-modal')" class="header-icon-btn w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" aria-label="Close">
                    <i class="fas fa-times text-sm" style="color: var(--on-surface);"></i>
                </button>
            </div>

            <div class="notifications-modal-content flex-1 min-h-0 overflow-y-auto overflow-x-hidden p-4 sm:p-6 scrollbar-hide">
                @php $layoutNotifications = $layoutNotifications ?? collect(); @endphp
                @if($layoutNotifications->isEmpty())
                    <div class="flex flex-col items-center justify-center py-8 sm:py-12">
                        <i class="fas fa-bell-slash text-4xl mb-4" style="color: var(--on-surface-variant); opacity: 0.5;"></i>
                        <p class="text-sm sm:text-base" style="color: var(--on-surface-variant);">No notifications.</p>
                    </div>
                @else
                    <div class="space-y-2 sm:space-y-3">
                        @foreach($layoutNotifications as $notify)
                            <div class="rounded-xl p-3 sm:p-4 border transition" style="background: var(--surface-container-low); border-color: var(--outline-variant);">
                                <p class="text-xs mb-2 break-words" style="color: var(--on-surface-variant);">
                                    {{ $notify->date_added?->diffForHumans() ?? '' }}
                                </p>
                                <p class="font-semibold text-sm sm:text-base mb-1 break-words" style="color: var(--on-surface);">{{ e($notify->title) }}</p>
                                <p class="text-xs sm:text-sm break-words leading-relaxed" style="color: var(--on-surface-variant);">{{ e(Str::limit($notify->message, 120)) }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-4 border-t" style="border-color: var(--outline-variant);">
                        <a href="{{ route('admin.notifications.index') }}" class="notifications-see-all-btn inline-flex items-center justify-center gap-2 rounded-full px-5 py-2.5 text-sm font-medium transition shadow-sm" style="background: var(--primary); color: var(--on-primary);">
                            <span>See all notifications</span>
                            <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
