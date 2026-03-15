{{-- Legacy: .navbar.navbar-mobile; .navbar-nav; .nav-item; .nav-link; Menu opens #offcanvasSidebar --}}
<div class="navbar navbar-mobile fixed bottom-0 left-0 right-0 z-30 flex justify-around border-t border-gray-200 bg-white py-2 lg:hidden">
    <ul class="navbar-nav flex list-unstyled m-0 p-0 w-full justify-around">
        <li class="nav-item">
            <a class="nav-link flex flex-col items-center gap-0.5 text-gray-600 hover:text-gray-900 {{ ($layoutRoute ?? '') === 'teacher.dashboard' ? 'text-blue-600' : '' }}" href="{{ route('teacher.dashboard') }}">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="nav-text mb-0 text-xs">Home</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link flex flex-col items-center gap-0.5 text-gray-600 hover:text-gray-900 {{ str_starts_with($layoutRoute ?? '', 'teacher.class') ? 'text-blue-600' : '' }}" href="{{ route('teacher.class.index') }}">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span class="nav-text mb-0 text-xs">Class | Subjects</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link flex flex-col items-center gap-0.5 text-gray-600 hover:text-gray-900 {{ str_starts_with($layoutRoute ?? '', 'teacher.results') ? 'text-blue-600' : '' }}" href="{{ route('teacher.results.index') }}">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                <span class="nav-text mb-0 text-xs">Upload</span>
            </a>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link flex flex-col items-center gap-0.5 text-gray-600 hover:text-gray-900 w-full bg-transparent border-0 cursor-pointer" id="teacher-sidebar-open-mobile" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar" aria-label="Menu">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <span class="nav-text mb-0 text-xs">Menu</span>
            </button>
        </li>
    </ul>
</div>
