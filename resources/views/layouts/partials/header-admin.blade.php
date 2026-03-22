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
    #mega-menu-dropdown.mega-menu-open .mega-menu-panel .mega-menu-col:nth-child(2) { transition-delay: 0.08s; }
    #mega-menu-dropdown.mega-menu-open .mega-menu-panel .mega-menu-col:nth-child(3) { transition-delay: 0.11s; }
    #mega-menu-dropdown.mega-menu-open .mega-menu-panel .mega-menu-col:nth-child(4) { transition-delay: 0.14s; }
    #mega-menu-dropdown.mega-menu-open .mega-menu-panel .mega-menu-col:nth-child(5) { transition-delay: 0.17s; }
    #mega-menu-dropdown.mega-menu-open .mega-menu-panel .mega-menu-col:nth-child(6) { transition-delay: 0.2s; }
    #mega-menu-dropdown.mega-menu-open .mega-menu-panel .mega-menu-col:nth-child(7) { transition-delay: 0.23s; }
    #mega-menu-dropdown.mega-menu-open .mega-menu-panel .mega-menu-col {
        opacity: 1;
        transform: translateY(0);
    }
    /* Bento panel shell: hero banner look lives in app.css (.admin-mega-menu-banner) */
    .mega-menu-bento-panel {
        min-width: 0;
    }

    /* Admin Hub (primary tonal) */
    .mega-menu-bento-panel .mega-menu-col--hero {
        background: var(--primary);
        border-color: color-mix(in srgb, var(--primary) 60%, transparent);
        box-shadow: none;
    }
    .mega-menu-bento-panel .mega-menu-col--hero h3 {
        color: var(--on-primary);
    }

    .mega-menu-bento-panel .mega-menu-col h3 {
        color: var(--on-surface);
    }
    .mega-menu-bento-panel .mega-menu-col--hero p {
        color: color-mix(in srgb, var(--on-primary) 90%, transparent);
    }

    .mega-menu-bento-panel .mega-menu-col--hero .mega-bento-tile-icon {
        background: color-mix(in srgb, var(--on-primary) 18%, transparent);
        color: var(--on-primary);
    }
    .mega-menu-bento-panel .mega-menu-col--hero .mega-bento-tile-link {
        background: color-mix(in srgb, var(--on-primary) 10%, transparent);
        border-color: color-mix(in srgb, var(--on-primary) 18%, transparent);
        box-shadow: none;
    }
    .mega-menu-bento-panel .mega-menu-col--hero .mega-bento-tile-link:hover {
        background: color-mix(in srgb, var(--on-primary) 16%, transparent);
        border-color: color-mix(in srgb, var(--on-primary) 25%, transparent);
        box-shadow: none;
    }
    .mega-menu-bento-panel .mega-menu-col--hero .mega-bento-tile-link:hover .mega-bento-tile-title {
        color: var(--on-primary);
    }
    .mega-menu-bento-panel .mega-menu-col--hero .mega-bento-tile-link:hover .mega-bento-tile-arrow {
        color: color-mix(in srgb, var(--on-primary) 85%, transparent);
    }
    .mega-menu-bento-panel .mega-menu-col--hero .mega-bento-tile-title {
        color: var(--on-primary);
    }
    .mega-menu-bento-panel .mega-menu-col--hero .mega-bento-tile-arrow {
        color: color-mix(in srgb, var(--on-primary) 85%, transparent);
    }


    /* Viewport + scrollbar: see app.css #mega-menu-dropdown .admin-mega-menu-banner */
    /* Bento row-link (card list item with arrow) */
    .mega-bento-row-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        border-radius: 1rem;
        background: white;
        border: 1px solid rgb(241 245 249);
        transition: border-color 0.2s ease, background-color 0.2s ease;
        text-decoration: none;
        min-width: 0;
    }
    .mega-bento-row-link span {
        font-size: 0.875rem;
        font-weight: 500;
        color: rgb(71 85 105);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    @media (min-width: 640px) {
        .mega-bento-row-link span {
            white-space: normal;
            word-break: break-word;
        }
    }
    .mega-bento-row-link:hover {
        border-color: rgb(167 243 208);
        background: rgba(16, 185, 129, 0.08);
    }
    .mega-bento-row-link:hover span { color: rgb(4 120 87); }
    .mega-bento-row-link i.fa-arrow-right,
    .mega-bento-row-link i.fa-chevron-right,
    .mega-bento-row-link i.fa-history {
        font-size: 0.75rem;
        color: rgb(203 213 225);
        transition: transform 0.2s ease, color 0.2s ease;
        flex-shrink: 0;
    }
    .mega-bento-row-link:hover i.fa-arrow-right,
    .mega-bento-row-link:hover i.fa-chevron-right,
    .mega-bento-row-link:hover i.fa-history {
        color: rgb(16, 185, 129);
        transform: translateX(2px);
    }
    [data-theme="dark"] .mega-bento-row-link {
        background: var(--surface-container-lowest);
        border-color: var(--outline-variant);
    }
    [data-theme="dark"] .mega-bento-row-link span { color: var(--on-surface-variant); }
    [data-theme="dark"] .mega-bento-row-link:hover {
        border-color: rgba(52, 211, 153, 0.4);
        background: rgba(52, 211, 153, 0.1);
    }
    [data-theme="dark"] .mega-bento-row-link:hover span { color: var(--on-surface); }

    /* Bento tiles: base styles in app.css (shared with teacher mega menu) */
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

<nav class="sticky top-0 z-50" style="background: var(--surface-container-lowest);">
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
                            <img src="{{ asset('storage/logo/logo.jpg') }}" alt="Logo" class="w-9 h-9 rounded-full object-cover ring-2 ring-offset-2">
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
            </div>

            <div class="flex items-center gap-2 lg:gap-4 flex-shrink-0">
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

            <!-- Mega Menu (bento layout) — sibling of header row so top-full anchors to bar, not a stretched flex column -->
            <div id="mega-menu-dropdown" class="absolute left-0 right-0 top-full z-50 flex justify-center items-start pt-1 sm:pt-2 px-4 sm:px-6">
                <div class="mega-menu-backdrop" onclick="toggleDropdown('mega-menu-dropdown')" aria-hidden="true"></div>
                <div class="mega-menu-panel relative z-10 w-full max-w-[1400px]">
                    <div class="mega-menu-bento-panel admin-mega-menu-banner rounded-[2.25rem] p-0">
                        <div class="admin-mega-menu-banner__bg" aria-hidden="true"></div>
                        <div class="admin-mega-menu-banner__inner p-2.5 sm:p-4">
                            <div class="admin-mega-menu-intro flex flex-wrap items-center gap-2 sm:gap-3 mb-2 sm:mb-3">
                                <span class="admin-mega-menu-intro__brand">
                                    <span class="h-2 w-2 rounded-full bg-emerald-400 shadow-[0_0_0_4px_rgba(16,185,129,0.18)]" aria-hidden="true"></span>
                                    Admin
                                </span>
                                <span class="text-xs font-medium text-white/55">Quick links — jump to any module</span>
                            </div>
                        <div class="grid grid-cols-12 gap-2">
                            <!-- Hero: Admin Hub -->
                            <div class="mega-menu-col mega-menu-col--hero col-span-12 lg:col-span-4 rounded-3xl p-3 sm:p-4 flex flex-col justify-between min-h-[140px] sm:min-h-[156px] transition-all hover:shadow-lg">
                                <div class="min-w-0">
                                    <div class="mega-bento-tile-icon mb-3 sm:mb-4" style="width: 3rem; height: 3rem; border-radius: 1.1rem;">
                                        <i class="fas fa-graduation-cap text-sm"></i>
                                    </div>
                                    <h3 class="text-lg sm:text-xl font-bold mb-1">Admin Hub</h3>
                                    <p class="text-xs sm:text-sm opacity-90">Manage students, staff, academics and daily operations from one place.</p>
                                </div>

                                <div class="mt-4 grid grid-cols-2 gap-2">
                                    <a href="{{ route('admin.dashboard') }}" class="mega-bento-tile-link group">
                                        <div class="mega-bento-tile-meta">
                                            <div class="mega-bento-tile-icon"><i class="fas fa-th-large text-sm"></i></div>
                                        </div>
                                        <span class="mega-bento-tile-title">Dashboard</span>
                                    </a>

                                    @if(Route::has('admin.classes'))
                                        <a href="{{ route('admin.classes') }}" class="mega-bento-tile-link group">
                                            <div class="mega-bento-tile-meta">
                                                <div class="mega-bento-tile-icon"><i class="fas fa-graduation-cap text-sm"></i></div>
                                            </div>
                                            <span class="mega-bento-tile-title">Classes</span>
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Card: Students & Classes -->
                            <div class="mega-menu-col mega-menu-col--students col-span-12 md:col-span-6 lg:col-span-4 flex h-full min-h-0 flex-col rounded-3xl border p-3 transition-all sm:p-4">
                                <div class="flex min-h-0 min-w-0 flex-1 flex-col">
                                    <h3 class="mega-menu-section-title flex shrink-0 items-center gap-2 text-sm font-bold sm:text-base">
                                        <span class="h-2 w-2 flex-shrink-0 rounded-full bg-blue-500"></span>
                                        Students &amp; Classes
                                    </h3>

                                    <div class="mt-2 grid min-h-0 flex-1 grid-cols-2 gap-1.5 self-stretch sm:mt-3 sm:grid-cols-3 sm:gap-2 xl:grid-cols-3">
                                        @if(Route::has('admin.classes'))
                                            <a href="{{ route('admin.classes') }}" class="mega-bento-tile-link group">
                                                <div class="mega-bento-tile-meta">
                                                    <div class="mega-bento-tile-icon"><i class="fas fa-graduation-cap text-sm"></i></div>
                                                </div>
                                                <span class="mega-bento-tile-title">Students / Classes</span>
                                            </a>
                                        @endif

                                        <a href="{{ route('admin.students.create') }}" class="mega-bento-tile-link group">
                                            <div class="mega-bento-tile-meta">
                                                <div class="mega-bento-tile-icon"><i class="fas fa-user-plus text-sm"></i></div>
                                            </div>
                                            <span class="mega-bento-tile-title">Register student</span>
                                        </a>

                                        <a href="{{ route('admin.students.houses') }}" class="mega-bento-tile-link group">
                                            <div class="mega-bento-tile-meta">
                                                <div class="mega-bento-tile-icon"><i class="fas fa-home text-sm"></i></div>
                                            </div>
                                            <span class="mega-bento-tile-title">Houses</span>
                                        </a>

                                        <a href="{{ route('admin.students.academic_advancement') }}" class="mega-bento-tile-link group">
                                            <div class="mega-bento-tile-meta">
                                                <div class="mega-bento-tile-icon"><i class="fas fa-exchange-alt text-sm"></i></div>
                                            </div>
                                            <span class="mega-bento-tile-title">Promote / Demote</span>
                                        </a>

                                        <a href="{{ route('admin.graduated') }}" class="mega-bento-tile-link group">
                                            <div class="mega-bento-tile-meta">
                                                <div class="mega-bento-tile-icon"><i class="fas fa-user-check text-sm"></i></div>
                                            </div>
                                            <span class="mega-bento-tile-title">Graduated</span>
                                        </a>

                                        <a href="{{ route('admin.left_school') }}" class="mega-bento-tile-link group">
                                            <div class="mega-bento-tile-meta">
                                                <div class="mega-bento-tile-icon"><i class="fas fa-user-minus text-sm"></i></div>
                                            </div>
                                            <span class="mega-bento-tile-title">Left School</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Card: Teachers & Staff -->
                            <div class="mega-menu-col mega-menu-col--teachers col-span-12 md:col-span-6 lg:col-span-4 flex h-full min-h-0 flex-col rounded-3xl border p-3 transition-all sm:p-4">
                                <div class="flex min-h-0 min-w-0 flex-1 flex-col">
                                    <h3 class="mega-menu-section-title flex shrink-0 items-center gap-2 text-sm font-bold sm:text-base">
                                        <span class="h-2 w-2 flex-shrink-0 rounded-full bg-amber-500"></span>
                                        Teachers &amp; Staff
                                    </h3>
                                    <div class="mt-2 grid min-h-0 flex-1 grid-cols-2 gap-1.5 self-stretch sm:mt-3 sm:grid-cols-3 sm:gap-2 xl:grid-cols-3">
                                        <a href="{{ route('admin.teachers.index') }}" class="mega-bento-tile-link group">
                                            <div class="mega-bento-tile-meta">
                                                <div class="mega-bento-tile-icon"><i class="fas fa-chalkboard-teacher text-sm"></i></div>
                                            </div>
                                            <span class="mega-bento-tile-title">Teachers &amp; Classes</span>
                                        </a>

                                        <a href="{{ route('admin.register_teacher.form') }}" class="mega-bento-tile-link group">
                                            <div class="mega-bento-tile-meta">
                                                <div class="mega-bento-tile-icon"><i class="fas fa-user-plus text-sm"></i></div>
                                            </div>
                                            <span class="mega-bento-tile-title">Register teacher</span>
                                        </a>

                                        <a href="{{ route('admin.assign_teacher_to_class.form') }}" class="mega-bento-tile-link group">
                                            <div class="mega-bento-tile-meta">
                                                <div class="mega-bento-tile-icon"><i class="fas fa-user-tag text-sm"></i></div>
                                            </div>
                                            <span class="mega-bento-tile-title">Assign to class</span>
                                        </a>

                                        @if(Route::has('admin.staff.index'))
                                            <a href="{{ route('admin.staff.index') }}" class="mega-bento-tile-link group">
                                                <div class="mega-bento-tile-meta">
                                                    <div class="mega-bento-tile-icon"><i class="fas fa-users-cog text-sm"></i></div>
                                                </div>
                                                <span class="mega-bento-tile-title">All staff</span>
                                            </a>
                                        @endif

                                        @if(Route::has('admin.roles.index'))
                                            <a href="{{ route('admin.roles.index') }}" class="mega-bento-tile-link group">
                                                <div class="mega-bento-tile-meta">
                                                    <div class="mega-bento-tile-icon"><i class="fas fa-user-shield text-sm"></i></div>
                                                </div>
                                                <span class="mega-bento-tile-title">Roles &amp; permissions</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Card: Academics -->
                            <div class="mega-menu-col mega-menu-col--academics col-span-12 lg:col-span-6 rounded-3xl p-3 sm:p-4 flex flex-col justify-between transition-all border">
                                <div class="min-w-0">
                                    <h3 class="mega-menu-section-title font-bold flex items-center gap-2 text-sm sm:text-base">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></span>
                                        Academics &amp; Results
                                    </h3>

                                    <div class="mt-2 sm:mt-3 grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-1.5 sm:gap-2">
                                        <a href="{{ route('admin.subjects.index') }}" class="mega-bento-tile-link group">
                                            <div class="mega-bento-tile-meta">
                                                <div class="mega-bento-tile-icon"><i class="fas fa-book-open text-sm"></i></div>
                                            </div>
                                            <span class="mega-bento-tile-title">All subjects</span>
                                        </a>

                                        <a href="{{ route('admin.subjects.fetch-classes') }}" class="mega-bento-tile-link group">
                                            <div class="mega-bento-tile-meta">
                                                <div class="mega-bento-tile-icon"><i class="fas fa-clipboard-list text-sm"></i></div>
                                            </div>
                                            <span class="mega-bento-tile-title">Register students (subjects)</span>
                                        </a>

                                        <a href="{{ route('admin.subjects.registered') }}" class="mega-bento-tile-link group">
                                            <div class="mega-bento-tile-meta">
                                                <div class="mega-bento-tile-icon"><i class="fas fa-eye text-sm"></i></div>
                                            </div>
                                            <span class="mega-bento-tile-title">View registered</span>
                                        </a>

                                        <a href="{{ route('admin.upload-results') }}" class="mega-bento-tile-link group">
                                            <div class="mega-bento-tile-meta">
                                                <div class="mega-bento-tile-icon"><i class="fas fa-upload text-sm"></i></div>
                                            </div>
                                            <span class="mega-bento-tile-title">Upload results</span>
                                        </a>

                                        <a href="{{ route('admin.publish-results') }}" class="mega-bento-tile-link group">
                                            <div class="mega-bento-tile-meta">
                                                <div class="mega-bento-tile-icon"><i class="fas fa-bullhorn text-sm"></i></div>
                                            </div>
                                            <span class="mega-bento-tile-title">Publish results</span>
                                        </a>

                                        @if(Route::has('admin.results-by-params'))
                                            <a href="{{ route('admin.results-by-params') }}" class="mega-bento-tile-link group">
                                                <div class="mega-bento-tile-meta">
                                                    <div class="mega-bento-tile-icon"><i class="fas fa-search text-sm"></i></div>
                                                </div>
                                                <span class="mega-bento-tile-title">Search results</span>
                                            </a>
                                        @endif

                                        @if(Route::has('admin.transcript'))
                                            <a href="{{ route('admin.transcript') }}" class="mega-bento-tile-link group">
                                                <div class="mega-bento-tile-meta">
                                                    <div class="mega-bento-tile-icon"><i class="fas fa-file-alt text-sm"></i></div>
                                                </div>
                                                <span class="mega-bento-tile-title">Transcript</span>
                                            </a>
                                        @endif

                                        @if(Route::has('admin.status.index'))
                                            <a href="{{ route('admin.status.index') }}" class="mega-bento-tile-link group">
                                                <div class="mega-bento-tile-meta">
                                                    <div class="mega-bento-tile-icon"><i class="fas fa-clipboard-check text-sm"></i></div>
                                                </div>
                                                <span class="mega-bento-tile-title">Check status</span>
                                            </a>
                                        @endif

                                        @if(Route::has('admin.fees.index'))
                                            <a href="{{ route('admin.fees.index') }}" class="mega-bento-tile-link group">
                                                <div class="mega-bento-tile-meta">
                                                    <div class="mega-bento-tile-icon"><i class="fas fa-money-bill-wave text-sm"></i></div>
                                                </div>
                                                <span class="mega-bento-tile-title">Result fees</span>
                                            </a>
                                        @endif

                                        @if(Route::has('admin.checklists.index'))
                                            <a href="{{ route('admin.checklists.index') }}" class="mega-bento-tile-link group">
                                                <div class="mega-bento-tile-meta">
                                                    <div class="mega-bento-tile-icon"><i class="fas fa-list-check text-sm"></i></div>
                                                </div>
                                                <span class="mega-bento-tile-title">Result checklist</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Card: Operations -->
                            <div class="mega-menu-col mega-menu-col--operations col-span-12 lg:col-span-6 rounded-3xl p-3 sm:p-4 flex flex-col justify-between transition-all border">
                                <div class="min-w-0">
                                    <h3 class="mega-menu-section-title font-bold flex items-center gap-2 text-sm sm:text-base">
                                        <span class="w-2 h-2 rounded-full bg-indigo-500 flex-shrink-0"></span>
                                        Operations
                                    </h3>
                                    <div class="mt-2 sm:mt-3 grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-3 gap-1.5 sm:gap-2">
                                        @if(Route::has('admin.attendance.index'))
                                            <a href="{{ route('admin.attendance.index') }}" class="mega-bento-tile-link group">
                                                <div class="mega-bento-tile-meta">
                                                    <div class="mega-bento-tile-icon"><i class="fas fa-calendar-check text-sm"></i></div>
                                                </div>
                                                <span class="mega-bento-tile-title">Attendance</span>
                                            </a>
                                        @endif

                                        @if(Route::has('admin.behavioral.index'))
                                            <a href="{{ route('admin.behavioral.index') }}" class="mega-bento-tile-link group">
                                                <div class="mega-bento-tile-meta">
                                                    <div class="mega-bento-tile-icon"><i class="fas fa-brain text-sm"></i></div>
                                                </div>
                                                <span class="mega-bento-tile-title">Behavioural analysis</span>
                                            </a>
                                        @endif

                                        @if(Route::has('admin.news.index'))
                                            <a href="{{ route('admin.news.index') }}" class="mega-bento-tile-link group">
                                                <div class="mega-bento-tile-meta">
                                                    <div class="mega-bento-tile-icon"><i class="fas fa-newspaper text-sm"></i></div>
                                                </div>
                                                <span class="mega-bento-tile-title">News</span>
                                            </a>
                                        @endif

                                        @if(Route::has('admin.notifications.index'))
                                            <a href="{{ route('admin.notifications.index') }}" class="mega-bento-tile-link group">
                                                <div class="mega-bento-tile-meta">
                                                    <div class="mega-bento-tile-icon"><i class="fas fa-bell text-sm"></i></div>
                                                </div>
                                                <span class="mega-bento-tile-title">Notifications</span>
                                            </a>
                                        @endif

                                        @if(Route::has('admin.online_entrance.index'))
                                            <a href="{{ route('admin.online_entrance.index') }}" class="mega-bento-tile-link group">
                                                <div class="mega-bento-tile-meta">
                                                    <div class="mega-bento-tile-icon"><i class="fas fa-door-open text-sm"></i></div>
                                                </div>
                                                <span class="mega-bento-tile-title">Online entrance</span>
                                            </a>
                                        @endif

                                        @if(Route::has('admin.bulk.index'))
                                            <a href="{{ route('admin.bulk.index') }}" class="mega-bento-tile-link group">
                                                <div class="mega-bento-tile-meta">
                                                    <div class="mega-bento-tile-icon"><i class="fas fa-comment-dots text-sm"></i></div>
                                                </div>
                                                <span class="mega-bento-tile-title">Bulk SMS</span>
                                            </a>
                                        @endif

                                        @if(Route::has('admin.card.index'))
                                            <a href="{{ route('admin.card.index') }}" class="mega-bento-tile-link group">
                                                <div class="mega-bento-tile-meta">
                                                    <div class="mega-bento-tile-icon"><i class="fas fa-ticket-alt text-sm"></i></div>
                                                </div>
                                                <span class="mega-bento-tile-title">Scratch card</span>
                                            </a>
                                        @endif

                                        @if(Route::has('admin.timetable.index'))
                                            <a href="{{ route('admin.timetable.index') }}" class="mega-bento-tile-link group">
                                                <div class="mega-bento-tile-meta">
                                                    <div class="mega-bento-tile-icon"><i class="fas fa-calendar text-sm"></i></div>
                                                </div>
                                                <span class="mega-bento-tile-title">Timetable</span>
                                            </a>
                                        @endif

                                        <a href="{{ route('admin.settings.index') }}" class="mega-bento-tile-link group">
                                            <div class="mega-bento-tile-meta">
                                                <div class="mega-bento-tile-icon"><i class="fas fa-cog text-sm"></i></div>
                                            </div>
                                            <span class="mega-bento-tile-title">Settings</span>
                                        </a>
                                    </div>
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
        <div class="notifications-modal-panel card-refined flex flex-col w-full h-full max-h-screen lg:max-w-md lg:max-h-screen lg:rounded-l-2xl lg:rounded-r-none rounded-none" style="background: var(--card-bg);">
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
