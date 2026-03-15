{{-- Legacy: header with navbar, logo, profile dropdown #profileDropdown --}}
<header class="navbar-light header-sticky border-b border-gray-200 bg-white">
    <nav class="navbar navbar-expand-xl">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <a class="navbar-brand text-lg font-semibold text-gray-900" href="{{ url('/') }}">
                {{ config('app.name') }}
            </a>
            <ul class="nav flex-row align-items-center list-unstyled ms-xl-auto gap-2">
                <li class="nav-item dropdown relative dropdown-wrap">
                    <button type="button" class="avatar avatar-xs p-0 flex items-center rounded-full border-0 bg-transparent cursor-pointer" id="profileDropdown" data-dropdown-trigger="profile" aria-expanded="false">
                        @if($layoutTeacher && $layoutTeacher->profileImage)
                            <img class="avatar-img h-8 w-8 rounded-full object-cover" src="{{ asset('uploads/teachers/' . $layoutTeacher->profileImage) }}" alt="">
                        @else
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-200 text-sm font-medium text-gray-600">{{ substr($layoutTeacher->firstname ?? 'T', 0, 1) }}</span>
                        @endif
                    </button>
                    <div id="dropdown-profile" class="dropdown-menu dropdown-menu-end absolute right-0 top-full z-50 mt-1 hidden w-56 rounded-lg border border-gray-200 bg-white py-2 shadow-lg" aria-labelledby="profileDropdown">
                        <div class="px-3 pb-2 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900">{{ $layoutTeacher->firstname ?? '' }} {{ $layoutTeacher->lastname ?? '' }}</p>
                            <p class="text-xs text-gray-500">{{ $layoutTeacher->email ?? '' }}</p>
                        </div>
                        <a class="dropdown-item block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50" href="{{ route('teacher.profile.index') }}">Settings</a>
                        <form method="POST" action="{{ route('teacher.logout') }}" class="border-t border-gray-100">
                            @csrf
                            <button type="submit" class="dropdown-item block w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50">Sign Out</button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>
