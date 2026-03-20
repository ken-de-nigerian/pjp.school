@php
    $r = $layoutRole ?? null;
    $route = $layoutRoute ?? '';
    $can = function ($key) use ($r) {
        if ($r === null) return true;
        return (int) ($r->$key ?? 0) === 1;
    };
@endphp
<div id="mobile-menu-overlay" class="mobile-menu-overlay" onclick="closeMobileMenu()">
    <div id="mobile-menu-panel" class="mobile-menu-panel" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="mobile-menu-header">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="logo-container" onclick="closeMobileMenu()">
                    <div class="logo-icon-container">
                        <img src="{{ asset('storage/logo/logo.jpg') }}" alt="Logo" class="w-9 h-9 rounded-full object-cover ring-2 ring-offset-2">
                    </div>
                    <span class="logo-text">{{ config('app.name') }}</span>
                </a>
            </div>
            <button type="button" onclick="closeMobileMenu()" class="w-10 h-10 flex items-center justify-center rounded-xl nav-icon-btn active:scale-95 transition-transform" aria-label="Close menu">
                <i class="fas fa-times text-charcoal-700 text-lg"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="mobile-menu-content">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="mobile-menu-item" onclick="closeMobileMenu()">
                <div class="mobile-menu-item-icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div class="mobile-menu-item-content">
                    <div class="mobile-menu-item-title">Dashboard</div>
                    <div class="mobile-menu-item-subtitle">Admin overview</div>
                </div>
                <i class="fas fa-chevron-right mobile-menu-item-arrow"></i>
            </a>

            @if($can('manage_students'))
                <!-- Students Dropdown -->
                <div class="mobile-menu-dropdown">
                    <div class="mobile-menu-item" onclick="toggleMobileDropdown('students-mobile-dropdown')">
                        <div class="mobile-menu-item-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="mobile-menu-item-content">
                            <div class="mobile-menu-item-title">Students / Classes</div>
                            <div class="mobile-menu-item-subtitle">Browse by class</div>
                        </div>
                        <i class="fas fa-chevron-down mobile-menu-item-arrow mobile-dropdown-arrow" id="students-mobile-arrow"></i>
                    </div>

                    <div id="students-mobile-dropdown" class="mobile-menu-dropdown-content hidden">
                        @if(Route::has('admin.classes'))<a href="{{ route('admin.classes') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()">
                            <i class="fas fa-users"></i>
                            <span>Students / Classes</span>
                        </a>@endif

                        <a href="{{ route('admin.students.create') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()">
                            <i class="fas fa-user-plus"></i>
                            <span>Register</span>
                        </a>

                        <a href="{{ route('admin.students.houses') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()">
                            <i class="fas fa-chalkboard"></i>
                            <span>Houses</span>
                        </a>

                        <a href="{{ route('admin.students.academic_advancement') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()">
                            <i class="fas fa-arrow-up"></i>
                            <span>Promote / Demote</span>
                        </a>

                        <a href="{{ route('admin.graduated') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()">
                            <i class="fas fa-graduation-cap"></i>
                            <span>Graduated</span>
                        </a>

                        <a href="{{ route('admin.left_school') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Left School</span>
                        </a>
                    </div>
                </div>
            @endif

            @if($can('manage_staffs') || $can('general_settings'))
                <!-- Staff Dropdown -->
                <div class="mobile-menu-dropdown">
                    <div class="mobile-menu-item" onclick="toggleMobileDropdown('staff-mobile-dropdown')">
                        <div class="mobile-menu-item-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="mobile-menu-item-content">
                            <div class="mobile-menu-item-title">Staff</div>
                            <div class="mobile-menu-item-subtitle">Manage staff & roles</div>
                        </div>
                        <i class="fas fa-chevron-down mobile-menu-item-arrow mobile-dropdown-arrow" id="staff-mobile-arrow"></i>
                    </div>
                    <div id="staff-mobile-dropdown" class="mobile-menu-dropdown-content hidden">
                        @if($can('manage_staffs'))<a href="{{ route('admin.staff.index') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()"><i class="fas fa-users"></i><span>All Staff</span></a>@endif
                        @if($can('general_settings'))<a href="{{ route('admin.roles.index') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()"><i class="fas fa-user-shield"></i><span>Roles & Permissions</span></a>@endif
                    </div>
                </div>
            @endif

            @if($can('manage_teachers'))
                <!-- Teachers Dropdown -->
                <div class="mobile-menu-dropdown">
                    <div class="mobile-menu-item" onclick="toggleMobileDropdown('teachers-mobile-dropdown')">
                        <div class="mobile-menu-item-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="mobile-menu-item-content">
                            <div class="mobile-menu-item-title">Teachers</div>
                            <div class="mobile-menu-item-subtitle">Manage teachers & classes</div>
                        </div>
                        <i class="fas fa-chevron-down mobile-menu-item-arrow mobile-dropdown-arrow" id="teachers-mobile-arrow"></i>
                    </div>
                    <div id="teachers-mobile-dropdown" class="mobile-menu-dropdown-content hidden">
                        <a href="{{ route('admin.teachers.index') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()"><i class="fas fa-chalkboard-teacher"></i><span>Teachers & Classes</span></a>
                        <a href="{{ route('admin.register_teacher.form') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()"><i class="fas fa-user-plus"></i><span>Register</span></a>
                        <a href="{{ route('admin.assign_teacher_to_class.form') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()"><i class="fas fa-link"></i><span>Assign To Class</span></a>
                    </div>
                </div>
            @endif

            @if($can('manage_subjects'))
                <!-- Subjects Dropdown -->
                <div class="mobile-menu-dropdown">
                    <div class="mobile-menu-item" onclick="toggleMobileDropdown('subjects-mobile-dropdown')">
                        <div class="mobile-menu-item-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="mobile-menu-item-content">
                            <div class="mobile-menu-item-title">Subjects</div>
                            <div class="mobile-menu-item-subtitle">Manage subjects</div>
                        </div>
                        <i class="fas fa-chevron-down mobile-menu-item-arrow mobile-dropdown-arrow" id="subjects-mobile-arrow"></i>
                    </div>
                    <div id="subjects-mobile-dropdown" class="mobile-menu-dropdown-content hidden">
                        <a href="{{ route('admin.subjects.index') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()"><i class="fas fa-book"></i><span>All Subjects</span></a>
                        <a href="{{ route('admin.subjects.fetch-classes') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()"><i class="fas fa-edit"></i><span>Register Students</span></a>
                        <a href="{{ route('admin.subjects.registered') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()"><i class="fas fa-list"></i><span>View Registered</span></a>
                    </div>
                </div>
            @endif

            @if($can('upload_result') || $can('view_uploaded_results') || $can('publish_result') || $can('check_result_status') || $can('transcript'))
                <!-- Results Dropdown -->
                <div class="mobile-menu-dropdown">
                    <div class="mobile-menu-item" onclick="toggleMobileDropdown('results-mobile-dropdown')">
                        <div class="mobile-menu-item-icon">
                            <i class="fas fa-poll"></i>
                        </div>
                        <div class="mobile-menu-item-content">
                            <div class="mobile-menu-item-title">Results</div>
                            <div class="mobile-menu-item-subtitle">Upload &amp; publish results</div>
                        </div>
                        <i class="fas fa-chevron-down mobile-menu-item-arrow mobile-dropdown-arrow" id="results-mobile-arrow"></i>
                    </div>
                    <div id="results-mobile-dropdown" class="mobile-menu-dropdown-content hidden">
                        <a href="{{ route('admin.upload-results') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()"><i class="fas fa-upload"></i><span>Upload results</span></a>
                        <a href="{{ route('admin.publish-results') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()"><i class="fas fa-globe"></i><span>Publish results</span></a>
                        @if(Route::has('admin.results-by-params'))<a href="{{ route('admin.results-by-params') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()"><i class="fas fa-search"></i><span>Search Results</span></a>@endif
                        @if(Route::has('admin.transcript'))<a href="{{ route('admin.transcript') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()"><i class="fas fa-file-alt"></i><span>Transcript</span></a>@endif
                        @if(Route::has('admin.status.index'))<a href="{{ route('admin.status.index') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()"><i class="fas fa-check-circle"></i><span>Check Status</span></a>@endif
                    </div>
                </div>
            @endif

            @if($can('attendance') || $can('view_uploaded_attendance'))
                @if(Route::has('admin.attendance.view'))
                    <div class="mobile-menu-dropdown">
                        <div class="mobile-menu-item" onclick="toggleMobileDropdown('attendance-mobile-dropdown')">
                            <div class="mobile-menu-item-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="mobile-menu-item-content">
                                <div class="mobile-menu-item-title">Attendance</div>
                                <div class="mobile-menu-item-subtitle">Take & view attendance</div>
                            </div>
                            <i class="fas fa-chevron-down mobile-menu-item-arrow mobile-dropdown-arrow" id="attendance-mobile-arrow"></i>
                        </div>
                        <div id="attendance-mobile-dropdown" class="mobile-menu-dropdown-content hidden">
                            <a href="{{ route('admin.attendance.index') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()"><i class="fas fa-plus"></i><span>Add</span></a>
                            <a href="{{ route('admin.attendance.view') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()"><i class="fas fa-list"></i><span>View Uploaded</span></a>
                        </div>
                    </div>
                @else
                    <a href="{{ route('admin.attendance.index') }}" class="mobile-menu-item" onclick="closeMobileMenu()">
                        <div class="mobile-menu-item-icon"><i class="fas fa-calendar-check"></i></div>
                        <div class="mobile-menu-item-content">
                            <div class="mobile-menu-item-title">Attendance</div>
                            <div class="mobile-menu-item-subtitle">Take attendance</div>
                        </div>
                        <i class="fas fa-chevron-right mobile-menu-item-arrow"></i>
                    </a>
                @endif
            @endif

            @if($can('behavioural_analysis') || $can('view_uploaded_behavioural_analysis'))
                <div class="mobile-menu-dropdown">
                    <div class="mobile-menu-item" onclick="toggleMobileDropdown('behavioral-mobile-dropdown')">
                        <div class="mobile-menu-item-icon">
                            <i class="fas fa-brain"></i>
                        </div>
                        <div class="mobile-menu-item-content">
                            <div class="mobile-menu-item-title">Behavioural</div>
                            <div class="mobile-menu-item-subtitle">Behavioural analysis</div>
                        </div>
                        <i class="fas fa-chevron-down mobile-menu-item-arrow mobile-dropdown-arrow" id="behavioral-mobile-arrow"></i>
                    </div>
                    <div id="behavioral-mobile-dropdown" class="mobile-menu-dropdown-content hidden">
                        @if($can('behavioural_analysis'))<a href="{{ route('admin.behavioral.index') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()"><i class="fas fa-plus"></i><span>Add</span></a>@endif
                        @if($can('view_uploaded_behavioural_analysis'))<a href="{{ route('admin.behavioral.view') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()"><i class="fas fa-list"></i><span>View Uploaded</span></a>@endif
                    </div>
                </div>
            @endif

            @if(Route::has('admin.news.index'))
                <a href="{{ route('admin.news.index') }}" class="mobile-menu-item" onclick="closeMobileMenu()">
                    <div class="mobile-menu-item-icon"><i class="fas fa-bullhorn"></i></div>
                    <div class="mobile-menu-item-content">
                        <div class="mobile-menu-item-title">News</div>
                        <div class="mobile-menu-item-subtitle">Announcements & news</div>
                    </div>
                    <i class="fas fa-chevron-right mobile-menu-item-arrow"></i>
                </a>
            @endif

            @if($can('online_entrance') && Route::has('admin.online_entrance.index'))
                <a href="{{ route('admin.online_entrance.index') }}" class="mobile-menu-item" onclick="closeMobileMenu()">
                    <div class="mobile-menu-item-icon"><i class="fas fa-file-alt"></i></div>
                    <div class="mobile-menu-item-content">
                        <div class="mobile-menu-item-title">Online Entrance</div>
                        <div class="mobile-menu-item-subtitle">Entrance applications</div>
                    </div>
                    <i class="fas fa-chevron-right mobile-menu-item-arrow"></i>
                </a>
            @endif

            <div class="mobile-menu-divider"></div>

            <!-- More Dropdown -->
            <div class="mobile-menu-dropdown">
                <div class="mobile-menu-item" onclick="toggleMobileDropdown('more-mobile-dropdown')">
                    <div class="mobile-menu-item-icon">
                        <i class="fas fa-ellipsis-h"></i>
                    </div>
                    <div class="mobile-menu-item-content">
                        <div class="mobile-menu-item-title">More</div>
                        <div class="mobile-menu-item-subtitle">Notifications, settings & more</div>
                    </div>
                    <i class="fas fa-chevron-down mobile-menu-item-arrow mobile-dropdown-arrow" id="more-mobile-arrow"></i>
                </div>
                <div id="more-mobile-dropdown" class="mobile-menu-dropdown-content hidden">
                    @if(Route::has('admin.notifications.index'))<a href="{{ route('admin.notifications.index') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()"><i class="fas fa-bell"></i><span>Notifications</span></a>@endif
                    @if(Route::has('admin.settings.index'))<a href="{{ route('admin.settings.index') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()"><i class="fas fa-cog"></i><span>Settings</span></a>@endif
                    @if(Route::has('admin.bulk.index'))<a href="{{ route('admin.bulk.index') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()"><i class="fas fa-sms"></i><span>Bulk SMS</span></a>@endif
                    @if(Route::has('admin.card.index'))<a href="{{ route('admin.card.index') }}" class="mobile-menu-subitem" onclick="closeMobileMenu()"><i class="fas fa-ticket-alt"></i><span>Scratch Card</span></a>@endif
                </div>
            </div>

            <div class="mobile-menu-divider"></div>

            <!-- Profile & Logout -->
            <a href="{{ route('admin.profile.show') }}" class="mobile-menu-item" onclick="closeMobileMenu()">
                <div class="mobile-menu-item-icon"><i class="fas fa-user"></i></div>
                <div class="mobile-menu-item-content">
                    <div class="mobile-menu-item-title">Profile</div>
                    <div class="mobile-menu-item-subtitle">Your account</div>
                </div>
                <i class="fas fa-chevron-right mobile-menu-item-arrow"></i>
            </a>

            <div class="mobile-menu-item">
                <div class="mobile-menu-item-icon"><i class="fas fa-sign-out-alt text-red-500"></i></div>
                <div class="mobile-menu-item-content flex-1">
                    <form action="{{ route('admin.logout') }}" method="POST" class="block">
                        @csrf
                        <button type="submit" class="mobile-menu-item-title text-left w-full text-red-600 font-semibold">Logout</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mobile-menu-footer">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    @if($layoutAdmin)
                        <img src="{{ asset('storage/staffs/' . ($layoutAdmin->profileImage ?? '')) }}" alt="Profile" class="w-10 h-10 rounded-xl border-2 border-white shadow object-cover bg-gray-200">
                        <div>
                            <div class="font-semibold text-sm" style="color: var(--text-primary);">{{ $layoutAdmin->name ?? 'Admin' }}</div>
                            <div class="text-xs font-medium" style="color: var(--text-secondary);">{{ $layoutAdmin->email ?? '' }}</div>
                        </div>
                    @else
                        <div class="font-semibold text-sm" style="color: var(--text-primary);">{{ config('app.name') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
