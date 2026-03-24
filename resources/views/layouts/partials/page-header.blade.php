<header class="w-full z-[1000] relative">
    <div class="bg-educave-900 text-white text-xs py-2 px-4 md:px-8 lg:px-16 flex justify-between items-center" role="banner">
        <div class="flex items-center space-x-4">
            <span class="hidden md:inline text-gray-300">Welcome To <span class="text-white font-semibold">{{ site_settings()?->name ?? config('app.name') }}</span></span>
        </div>

        <div class="flex items-center space-x-6">
            <div class="flex items-center space-x-1 hover:text-educave-200 cursor-pointer transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone" aria-hidden="true">
                    <path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"/>
                </svg>
                <span>
                    <a href="tel:{{ preg_replace('/\s+/', '', (string) config('school.school_phone')) }}">
                        {{ config('school.school_phone') }}
                    </a>
                </span>
            </div>

            <div class="flex items-center space-x-1 hover:text-educave-200 cursor-pointer transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail" aria-hidden="true">
                    <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/>
                </svg>
                <span>
                    <a href="mailto:{{ config('school.school_email') }}">
                        {{ config('school.school_email') }}
                    </a>
                </span>
            </div>

            <div class="flex items-center space-x-1 cursor-pointer">
                <span>English</span>
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m6 9 6 6 6-6"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="transition-all duration-300 border-b bg-white border-gray-100 relative py-4">
        <div class="container mx-auto px-4 md:px-8 lg:px-16 flex justify-between items-center gap-4">
            <div class="min-w-0 shrink">
                <x-site-logo
                    href="{{ route('home') }}"
                    variant="guest"
                    loading="eager"
                    :aria-label="__('School home')"
                />
            </div>

            <nav class="hidden lg:flex items-center space-x-8 text-sm tracking-wide font-medium text-gray-800" role="navigation" aria-label="Main navigation">

                {{-- HOME mega menu --}}
                <a href="{{ route('home') }}" class="hover:text-educave-800 transition-colors relative group inline-block" aria-label="View news page">
                    HOME.<span class="absolute -bottom-1 left-0 h-0.5 bg-educave-800 transition-all duration-300 w-0 group-hover:w-full"></span>
                </a>

                {{-- CAMPUS dropdown --}}
                <div class="relative group">
                    <button class="flex items-center gap-1 hover:text-educave-800 transition-colors relative" aria-expanded="false" aria-haspopup="true" aria-label="Campus pages dropdown menu">
                        CAMPUS.
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                        <span class="absolute -bottom-1 left-0 h-0.5 bg-educave-800 transition-all duration-300 w-0 group-hover:w-full"></span>
                    </button>
                    <div class="guest-nav-dd absolute top-full left-0 w-56 bg-white shadow-xl border-t-2 border-educave-800 py-2 transform transition-all duration-200 origin-top-left z-[100]" role="menu" aria-label="Campus pages">
                        @foreach ([
                            ['route' => 'about_us', 'label' => 'About us'],
                            ['route' => 'faqs', 'label' => 'FAQs'],
                            ['route' => 'admin_process', 'label' => 'Admission process'],
                        ] as $item)
                            <a href="{{ route($item['route']) }}" class="block w-full text-left px-6 py-3 text-xs font-bold uppercase tracking-widest text-gray-600 hover:bg-educave-50 hover:text-educave-800 transition-colors" role="menuitem">{{ $item['label'] }}</a>
                        @endforeach
                    </div>
                </div>

                {{-- PROGRAMS dropdown --}}
                <div class="relative group">
                    <button class="flex items-center gap-1 hover:text-educave-800 transition-colors relative" aria-expanded="false" aria-haspopup="true" aria-label="Programs dropdown menu">
                        PROGRAMS.
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                        <span class="absolute -bottom-1 left-0 h-0.5 bg-educave-800 transition-all duration-300 w-0 group-hover:w-full"></span>
                    </button>

                    <div class="guest-nav-dd absolute top-full left-0 w-64 bg-white shadow-xl border-t-2 border-educave-800 py-2 transform transition-all duration-200 origin-top-left z-[100]" role="menu" aria-label="Program pages">
                        @foreach ([
                            ['route' => 'academic_overview', 'label' => 'Academic overview'],
                            ['route' => 'academic_curriculum', 'label' => 'Academic curriculum'],
                            ['route' => 'news', 'label' => 'News'],
                        ] as $item)
                            <a href="{{ route($item['route']) }}" class="block w-full text-left px-6 py-3 text-xs font-bold uppercase tracking-widest text-gray-600 hover:bg-educave-50 hover:text-educave-800 transition-colors" role="menuitem">{{ $item['label'] }}</a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('news') }}" class="hover:text-educave-800 transition-colors relative group inline-block" aria-label="View news page">
                    NEWS.<span class="absolute -bottom-1 left-0 h-0.5 bg-educave-800 transition-all duration-300 w-0 group-hover:w-full"></span>
                </a>

                <a href="{{ route('apply_online') }}" class="hover:text-educave-800 transition-colors relative group inline-block" aria-label="Apply online">
                    APPLY.<span class="absolute -bottom-1 left-0 h-0.5 bg-educave-800 transition-all duration-300 w-0 group-hover:w-full"></span>
                </a>

                <a href="mailto:{{ config('school.school_email') }}" class="hover:text-educave-800 transition-colors relative group inline-block" aria-label="Contact by email">
                    CONTACT.<span class="absolute -bottom-1 left-0 h-0.5 bg-educave-800 transition-all duration-300 w-0 group-hover:w-full"></span>
                </a>
            </nav>

            <div class="hidden lg:flex items-center gap-6 shrink-0">
                <div class="flex items-center gap-4">
                    @if (Route::has('teacher.login'))
                        <a href="{{ route('teacher.login') }}" class="text-xs font-bold tracking-wider text-gray-600 hover:text-educave-800 transition-colors whitespace-nowrap leading-none py-2.5">TEACHERS</a>
                    @endif

                    @if (Route::has('teacher.login') && Route::has('admin.login'))
                        <span class="w-px h-4 shrink-0 bg-gray-200 self-center" aria-hidden="true"></span>
                    @endif

                    @if (Route::has('admin.login'))
                        <a href="{{ route('admin.login') }}" class="text-xs font-bold tracking-wider text-gray-600 hover:text-educave-800 transition-colors whitespace-nowrap leading-none py-2.5">ADMIN</a>
                    @endif
                </div>

                <a href="{{ route('result.check') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 min-h-[44px] border border-gray-300 text-xs font-bold tracking-wider hover:bg-educave-800 hover:text-white hover:border-educave-800 transition-all duration-300 group whitespace-nowrap" aria-label="{{ __('Result portal') }}">
                    {{ __('RESULT PORTAL') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-right group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform shrink-0" aria-hidden="true"><path d="M7 7h10v10"/><path d="M7 17 17 7"/></svg>
                </a>
            </div>

            <div class="flex items-center gap-2 lg:hidden shrink-0">
                <a href="{{ route('result.check') }}" class="flex items-center gap-1.5 px-3 py-2.5 border border-gray-300 text-[10px] font-bold tracking-wider hover:bg-educave-800 hover:text-white hover:border-educave-800 transition-all duration-300 group" aria-label="{{ __('Result portal') }}">
                    <span class="truncate max-w-[7rem] sm:max-w-none">{{ __('Result portal') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-right group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform shrink-0 sm:w-4 sm:h-4" aria-hidden="true"><path d="M7 7h10v10"/><path d="M7 17 17 7"/></svg>
                </a>

                <button type="button" id="mobile-menu-toggle" class="text-gray-800 p-1 -mr-1 rounded-md hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-educave-800" aria-expanded="false" aria-label="Open menu" aria-haspopup="dialog" aria-controls="guest-mobile-menu-overlay">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu" aria-hidden="true" data-icon-open>
                        <path d="M4 5h16"/><path d="M4 12h16"/><path d="M4 19h16"/>
                    </svg>

                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x hidden" aria-hidden="true" data-icon-close>
                        <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

</header>

{{-- Mobile navigation: admin/teacher sidebar layout (icons, rows, expandable sections) --}}
<div
    id="guest-mobile-menu-overlay"
    class="guest-mm-overlay lg:hidden"
    aria-hidden="true"
    role="presentation"
>
    <div class="guest-mm-backdrop" onclick="window.closeGuestMobileMenu && window.closeGuestMobileMenu()"></div>
    <div
        id="guest-mobile-menu-panel"
        class="guest-mm-panel"
        role="dialog"
        aria-modal="true"
        aria-label="{{ __('Main navigation') }}"
        onclick="event.stopPropagation()"
    >
        <div class="mobile-menu-header">
            <div class="flex min-w-0 items-center gap-3">
                <x-site-logo
                    href="{{ route('home') }}"
                    onclick="window.closeGuestMobileMenu && window.closeGuestMobileMenu()"
                    variant="guest"
                    loading="eager"
                    :aria-label="__('School home')"
                />
            </div>
            <button
                type="button"
                class="guest-mm-nav-icon-btn"
                onclick="window.closeGuestMobileMenu && window.closeGuestMobileMenu()"
                aria-label="{{ __('Close menu') }}"
            >
                <i class="fas fa-times text-lg" aria-hidden="true"></i>
            </button>
        </div>

        <nav class="mobile-menu-content" aria-label="{{ __('Mobile main navigation') }}">
            <a href="{{ route('home') }}" class="mobile-menu-item" onclick="window.closeGuestMobileMenu && window.closeGuestMobileMenu()">
                <div class="mobile-menu-item-icon"><i class="fas fa-home" aria-hidden="true"></i></div>
                <div class="mobile-menu-item-content">
                    <div class="mobile-menu-item-title">{{ __('Home') }}</div>
                    <div class="mobile-menu-item-subtitle">{{ __('School landing page') }}</div>
                </div>
                <i class="fas fa-chevron-right mobile-menu-item-arrow" aria-hidden="true"></i>
            </a>

            <div class="mobile-menu-dropdown">
                <div class="mobile-menu-item" role="button" tabindex="0" onclick="toggleMobileDropdown('guest-campus-mobile-dropdown')" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();toggleMobileDropdown('guest-campus-mobile-dropdown');}">
                    <div class="mobile-menu-item-icon"><i class="fas fa-building-columns" aria-hidden="true"></i></div>
                    <div class="mobile-menu-item-content">
                        <div class="mobile-menu-item-title">{{ __('Campus') }}</div>
                        <div class="mobile-menu-item-subtitle">{{ __('About, vision & admissions') }}</div>
                    </div>
                    <i class="fas fa-chevron-down mobile-menu-item-arrow mobile-dropdown-arrow" id="guest-campus-mobile-arrow" aria-hidden="true"></i>
                </div>
                <div id="guest-campus-mobile-dropdown" class="mobile-menu-dropdown-content hidden">
                    <a href="{{ route('about_us') }}" class="mobile-menu-subitem" onclick="window.closeGuestMobileMenu && window.closeGuestMobileMenu()"><i class="fas fa-school" aria-hidden="true"></i><span>{{ __('About us') }}</span></a>
                    <a href="{{ route('vision_mission') }}" class="mobile-menu-subitem" onclick="window.closeGuestMobileMenu && window.closeGuestMobileMenu()"><i class="fas fa-compass" aria-hidden="true"></i><span>{{ __('Vision & mission') }}</span></a>
                    <a href="{{ route('faqs') }}" class="mobile-menu-subitem" onclick="window.closeGuestMobileMenu && window.closeGuestMobileMenu()"><i class="fas fa-circle-question" aria-hidden="true"></i><span>{{ __('FAQs') }}</span></a>
                    <a href="{{ route('admin_process') }}" class="mobile-menu-subitem" onclick="window.closeGuestMobileMenu && window.closeGuestMobileMenu()"><i class="fas fa-route" aria-hidden="true"></i><span>{{ __('Admission process') }}</span></a>
                </div>
            </div>

            <div class="mobile-menu-dropdown">
                <div class="mobile-menu-item" role="button" tabindex="0" onclick="toggleMobileDropdown('guest-programs-mobile-dropdown')" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();toggleMobileDropdown('guest-programs-mobile-dropdown');}">
                    <div class="mobile-menu-item-icon"><i class="fas fa-book-open" aria-hidden="true"></i></div>
                    <div class="mobile-menu-item-content">
                        <div class="mobile-menu-item-title">{{ __('Programs') }}</div>
                        <div class="mobile-menu-item-subtitle">{{ __('Academics & news') }}</div>
                    </div>
                    <i class="fas fa-chevron-down mobile-menu-item-arrow mobile-dropdown-arrow" id="guest-programs-mobile-arrow" aria-hidden="true"></i>
                </div>
                <div id="guest-programs-mobile-dropdown" class="mobile-menu-dropdown-content hidden">
                    <a href="{{ route('academic_overview') }}" class="mobile-menu-subitem" onclick="window.closeGuestMobileMenu && window.closeGuestMobileMenu()"><i class="fas fa-clipboard-list" aria-hidden="true"></i><span>{{ __('Academic overview') }}</span></a>
                    <a href="{{ route('academic_curriculum') }}" class="mobile-menu-subitem" onclick="window.closeGuestMobileMenu && window.closeGuestMobileMenu()"><i class="fas fa-book" aria-hidden="true"></i><span>{{ __('Academic curriculum') }}</span></a>
                    <a href="{{ route('news') }}" class="mobile-menu-subitem" onclick="window.closeGuestMobileMenu && window.closeGuestMobileMenu()"><i class="fas fa-newspaper" aria-hidden="true"></i><span>{{ __('News') }}</span></a>
                </div>
            </div>

            <a href="{{ route('news') }}" class="mobile-menu-item" onclick="window.closeGuestMobileMenu && window.closeGuestMobileMenu()">
                <div class="mobile-menu-item-icon"><i class="fas fa-bullhorn" aria-hidden="true"></i></div>
                <div class="mobile-menu-item-content">
                    <div class="mobile-menu-item-title">{{ __('News') }}</div>
                    <div class="mobile-menu-item-subtitle">{{ __('Updates & announcements') }}</div>
                </div>
                <i class="fas fa-chevron-right mobile-menu-item-arrow" aria-hidden="true"></i>
            </a>

            <a href="{{ route('apply_online') }}" class="mobile-menu-item" onclick="window.closeGuestMobileMenu && window.closeGuestMobileMenu()">
                <div class="mobile-menu-item-icon"><i class="fas fa-file-signature" aria-hidden="true"></i></div>
                <div class="mobile-menu-item-content">
                    <div class="mobile-menu-item-title">{{ __('Apply online') }}</div>
                    <div class="mobile-menu-item-subtitle">{{ __('Start your application') }}</div>
                </div>
                <i class="fas fa-chevron-right mobile-menu-item-arrow" aria-hidden="true"></i>
            </a>

            <a href="mailto:{{ config('school.school_email') }}" class="mobile-menu-item" onclick="window.closeGuestMobileMenu && window.closeGuestMobileMenu()">
                <div class="mobile-menu-item-icon"><i class="fas fa-envelope" aria-hidden="true"></i></div>
                <div class="mobile-menu-item-content">
                    <div class="mobile-menu-item-title">{{ __('Contact') }}</div>
                    <div class="mobile-menu-item-subtitle">{{ config('school.school_email') }}</div>
                </div>
                <i class="fas fa-chevron-right mobile-menu-item-arrow" aria-hidden="true"></i>
            </a>

            <div class="mobile-menu-divider"></div>

            <a href="{{ route('result.check') }}" class="mobile-menu-item" onclick="window.closeGuestMobileMenu && window.closeGuestMobileMenu()">
                <div class="mobile-menu-item-icon"><i class="fas fa-clipboard-check" aria-hidden="true"></i></div>
                <div class="mobile-menu-item-content">
                    <div class="mobile-menu-item-title">{{ __('Result portal') }}</div>
                    <div class="mobile-menu-item-subtitle">{{ __('Term report cards by class & student ID') }}</div>
                </div>
                <i class="fas fa-chevron-right mobile-menu-item-arrow" aria-hidden="true"></i>
            </a>

            @if (Route::has('teacher.login'))
                <a href="{{ route('teacher.login') }}" class="mobile-menu-item" onclick="window.closeGuestMobileMenu && window.closeGuestMobileMenu()">
                    <div class="mobile-menu-item-icon"><i class="fas fa-chalkboard-teacher" aria-hidden="true"></i></div>
                    <div class="mobile-menu-item-content">
                        <div class="mobile-menu-item-title">{{ __('Teacher login') }}</div>
                        <div class="mobile-menu-item-subtitle">{{ __('Attendance, class results, and student records') }}</div>
                    </div>
                    <i class="fas fa-chevron-right mobile-menu-item-arrow" aria-hidden="true"></i>
                </a>
            @endif

            @if (Route::has('admin.login'))
                <a href="{{ route('admin.login') }}" class="mobile-menu-item" onclick="window.closeGuestMobileMenu && window.closeGuestMobileMenu()">
                    <div class="mobile-menu-item-icon"><i class="fas fa-user-shield" aria-hidden="true"></i></div>
                    <div class="mobile-menu-item-content">
                        <div class="mobile-menu-item-title">{{ __('Admin login') }}</div>
                        <div class="mobile-menu-item-subtitle">{{ __('School administration dashboard') }}</div>
                    </div>
                    <i class="fas fa-chevron-right mobile-menu-item-arrow" aria-hidden="true"></i>
                </a>
            @endif
        </nav>
    </div>
</div>
