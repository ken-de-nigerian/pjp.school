@php
    $route = $layoutRoute ?? '';
    $teacher = $layoutTeacher ?? null;
    $formTeacher = (int) ($teacher->form_teacher ?? 0) === 1;
@endphp
{{-- Legacy: .offcanvas-lg.offcanvas-end #offcanvasSidebar; .nav-item, .nav-link --}}
<div class="col-lg-4 col-xl-3 w-full lg:w-1/4 xl:w-1/4 shrink-0">
    <div class="offcanvas-lg offcanvas-end sidebar-right fixed right-0 top-0 z-40 h-screen w-72 border-l border-gray-200 bg-white transition-transform lg:static lg:block lg:translate-x-0 translate-x-full" tabindex="-1" id="offcanvasSidebar">
        <div class="offcanvas-header justify-end p-3 lg:hidden">
            <button type="button" class="btn-close rounded p-1 hover:bg-gray-100" id="teacher-sidebar-close" aria-label="Close">&times;</button>
        </div>
        <div class="offcanvas-body p-3 lg:p-0 h-full overflow-y-auto">
            <div class="card bg-light w-100 rounded-lg border border-gray-200">
                <div class="card-body p-3">
                    <div class="text-center mb-3">
                        @if($teacher && $teacher->profileImage)
                            <img class="h-16 w-16 rounded-full border-2 border-white object-cover mx-auto mb-2" src="{{ asset('uploads/teachers/' . $teacher->profileImage) }}" alt="">
                        @else
                            <span class="flex h-16 w-16 items-center justify-center rounded-full border-2 border-white bg-gray-200 text-xl font-medium text-gray-600 mx-auto mb-2">{{ substr($teacher->firstname ?? 'T', 0, 1) }}</span>
                        @endif
                        <h6 class="mb-0 font-medium text-gray-900">{{ $teacher->firstname ?? '' }} {{ $teacher->lastname ?? '' }}</h6>
                        <a href="{{ route('teacher.profile.index') }}" class="text-sm text-blue-600 hover:underline">{{ $teacher->email ?? '' }}</a>
                        <hr class="my-2 border-gray-200">
                    </div>
                    <ul class="nav flex-column space-y-0">
                        <li class="nav-item">
                            <a class="nav-link flex items-center gap-2 rounded-lg px-3 py-2 text-sm {{ $route === 'teacher.dashboard' ? 'active bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}" href="{{ route('teacher.dashboard') }}">Dashboard</a>
                        </li>
                        @if($formTeacher)
                            <li class="nav-item"><a class="nav-link flex items-center gap-2 rounded-lg px-3 py-2 text-sm {{ str_starts_with($route ?? '', 'teacher.attendance') ? 'active bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}" href="{{ route('teacher.attendance.index') }}">Attendance</a></li>
                            <li class="nav-item"><a class="nav-link flex items-center gap-2 rounded-lg px-3 py-2 text-sm {{ str_starts_with($route ?? '', 'teacher.behavioral') ? 'active bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}" href="{{ route('teacher.behavioral.index') }}">Behavioural</a></li>
                        @endif
                        <li class="nav-item"><a class="nav-link flex items-center gap-2 rounded-lg px-3 py-2 text-sm {{ str_starts_with($route ?? '', 'teacher.class') ? 'active bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}" href="{{ route('teacher.class.index') }}">Class | Subjects</a></li>
                        <li class="nav-item"><a class="nav-link flex items-center gap-2 rounded-lg px-3 py-2 text-sm {{ str_starts_with($route ?? '', 'teacher.subjects') ? 'active bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}" href="{{ route('teacher.subjects.index') }}">Subject Management</a></li>
                        <li class="nav-item"><a class="nav-link flex items-center gap-2 rounded-lg px-3 py-2 text-sm {{ str_starts_with($route ?? '', 'teacher.results') ? 'active bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}" href="{{ route('teacher.results.index') }}">Upload</a></li>
                        <li class="nav-item"><a class="nav-link flex items-center gap-2 rounded-lg px-3 py-2 text-sm {{ str_starts_with($route ?? '', 'teacher.uploaded') ? 'active bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}" href="{{ route('teacher.uploaded.index') }}">Uploaded Results</a></li>
                    </ul>
                    <hr class="my-2 border-gray-200">
                    <ul class="nav flex-column space-y-0">
                        <li class="nav-item"><a class="nav-link flex items-center gap-2 rounded-lg px-3 py-2 text-sm {{ str_starts_with($route ?? '', 'teacher.profile') ? 'active bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}" href="{{ route('teacher.profile.index') }}">Edit Profile</a></li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('teacher.logout') }}">
                                @csrf
                                <button type="submit" class="nav-link w-full text-left flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-red-600 hover:bg-red-50 bg-transparent border-0 cursor-pointer">Sign Out</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="teacher-sidebar-backdrop" class="fixed inset-0 z-30 hidden bg-black/50 lg:hidden" aria-hidden="true"></div>
