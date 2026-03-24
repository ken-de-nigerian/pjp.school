<div id="mobile-menu-overlay" class="mobile-menu-overlay" onclick="closeMobileMenu()">
    <div id="mobile-menu-panel" class="mobile-menu-panel" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="mobile-menu-header">
            <div class="flex min-w-0 items-center gap-3">
                <x-site-logo
                    :href="route('home')"
                    onclick="closeMobileMenu()"
                    variant="app"
                    :aria-label="__('Teacher dashboard')"
                />
            </div>

            <button type="button" onclick="closeMobileMenu()" class="w-10 h-10 flex items-center justify-center rounded-xl nav-icon-btn active:scale-95 transition-transform" aria-label="Close menu">
                <i class="fas fa-times text-charcoal-700 text-lg"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="mobile-menu-content">
            <a href="{{ route('teacher.dashboard') }}" class="mobile-menu-item" onclick="closeMobileMenu()">
                <div class="mobile-menu-item-icon"><i class="fas fa-chart-pie"></i></div>
                <div class="mobile-menu-item-content">
                    <div class="mobile-menu-item-title">Dashboard</div>
                    <div class="mobile-menu-item-subtitle">Teacher overview</div>
                </div>
                <i class="fas fa-chevron-right mobile-menu-item-arrow"></i>
            </a>

            <a href="{{ route('teacher.attendance.index') }}" class="mobile-menu-item" onclick="closeMobileMenu()">
                <div class="mobile-menu-item-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="mobile-menu-item-content">
                    <div class="mobile-menu-item-title">Attendance</div>
                    <div class="mobile-menu-item-subtitle">Take & view</div>
                </div>
                <i class="fas fa-chevron-right mobile-menu-item-arrow"></i>
            </a>

            <a href="{{ route('teacher.behavioral.index') }}" class="mobile-menu-item" onclick="closeMobileMenu()">
                <div class="mobile-menu-item-icon"><i class="fas fa-brain"></i></div>
                <div class="mobile-menu-item-content">
                    <div class="mobile-menu-item-title">Behavioural</div>
                    <div class="mobile-menu-item-subtitle">Behaviour analysis</div>
                </div>
                <i class="fas fa-chevron-right mobile-menu-item-arrow"></i>
            </a>

            <a href="{{ route('teacher.class.index') }}" class="mobile-menu-item" onclick="closeMobileMenu()">
                <div class="mobile-menu-item-icon"><i class="fas fa-chalkboard"></i></div>
                <div class="mobile-menu-item-content">
                    <div class="mobile-menu-item-title">Class | Students</div>
                    <div class="mobile-menu-item-subtitle">Students by class</div>
                </div>
                <i class="fas fa-chevron-right mobile-menu-item-arrow"></i>
            </a>

            <a href="{{ route('teacher.results.index') }}" class="mobile-menu-item" onclick="closeMobileMenu()">
                <div class="mobile-menu-item-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                <div class="mobile-menu-item-content">
                    <div class="mobile-menu-item-title">Upload</div>
                    <div class="mobile-menu-item-subtitle">Enter scores</div>
                </div>
                <i class="fas fa-chevron-right mobile-menu-item-arrow"></i>
            </a>

            <a href="{{ route('teacher.uploaded.index') }}" class="mobile-menu-item" onclick="closeMobileMenu()">
                <div class="mobile-menu-item-icon"><i class="fas fa-save"></i></div>
                <div class="mobile-menu-item-content">
                    <div class="mobile-menu-item-title">Uploaded Results</div>
                    <div class="mobile-menu-item-subtitle">View & edit</div>
                </div>
                <i class="fas fa-chevron-right mobile-menu-item-arrow"></i>
            </a>

            <div class="mobile-menu-divider"></div>

            <a href="{{ route('teacher.profile.index') }}" class="mobile-menu-item" onclick="closeMobileMenu()">
                <div class="mobile-menu-item-icon"><i class="fas fa-user-edit"></i></div>
                <div class="mobile-menu-item-content">
                    <div class="mobile-menu-item-title">Edit Profile</div>
                    <div class="mobile-menu-item-subtitle">Your account</div>
                </div>
                <i class="fas fa-chevron-right mobile-menu-item-arrow"></i>
            </a>

            <div class="mobile-menu-item">
                <div class="mobile-menu-item-icon"><i class="fas fa-sign-out-alt text-red-500"></i></div>
                <div class="mobile-menu-item-content flex-1">
                    <form action="{{ route('teacher.logout') }}" method="POST" class="block">
                        @csrf
                        <button type="submit"
                                class="mobile-menu-item-title text-left w-full text-red-600 font-semibold">
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mobile-menu-footer">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    @if($layoutTeacher)
                        <img src="{{ !empty($layoutTeacher->imagelocation) ? asset('storage/teachers/' . $layoutTeacher->imagelocation) : asset('storage/teachers/default.png') }}" alt="Profile" class="w-10 h-10 rounded-xl border-2 border-white shadow object-cover bg-gray-200">
                        <div>
                            <div class="font-semibold text-sm" style="color: var(--text-primary);">{{ $layoutTeacher->firstname ?? 'Class' }} {{ $layoutTeacher->lastname ?? 'Teacher' }}</div>
                            <div class="text-xs font-medium" style="color: var(--text-secondary);">{{ $layoutTeacher->email ?? '' }}</div>
                        </div>
                    @else
                        <div class="font-semibold text-sm" style="color: var(--text-primary);">{{ config('app.name') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
