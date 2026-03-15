@php
    $notifications = $layoutNotifications ?? collect();
@endphp
{{-- Legacy structure: nav.navbar.top-bar; notification .nav-notification; profile #profileDropdown; modal #deleteNotificationsModal --}}
<nav class="navbar top-bar navbar-light sticky top-0 z-20 flex h-14 flex-shrink-0 items-center justify-between border-b border-gray-200 bg-white py-0 px-4 lg:py-3 lg:px-6">
    <div class="container-fluid p-0 flex flex-1 items-center w-full">
        <div class="d-flex align-items-center flex items-center gap-4 w-full">
            <div class="d-flex align-items-center lg:hidden">
                <a class="navbar-brand text-lg font-semibold text-gray-900" href="{{ route('admin.dashboard') }}">{{ config('app.name') }}</a>
            </div>
            <div class="navbar-expand-lg ms-auto lg:ms-0 hidden lg:block">
                <div class="collapse navbar-collapse w-100" id="navbarTopContent">
                    <div class="nav my-3 lg:my-0 flex-nowrap align-items-center">
                        <div class="nav-item w-full w-64">
                            <form class="position-relative relative" action="#" method="get">
                                <input class="form-control bg-light w-full rounded-lg border border-gray-200 bg-gray-50 py-2 pl-3 pr-9 text-sm" type="search" placeholder="Search" aria-label="Search">
                                <button class="bg-transparent border-0 px-2 py-0 absolute top-1/2 right-0 -translate-y-1/2 text-gray-400 hover:text-gray-600" type="submit" aria-label="Submit"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="nav flex-row align-items-center list-unstyled ms-auto gap-2">
                <li class="nav-item dropdown relative dropdown-wrap">
                    <a class="nav-notification btn btn-light relative rounded-lg p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 mb-0" href="#" role="button" data-dropdown-trigger="notifications" aria-expanded="false">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </a>
                    @if($notifications->isNotEmpty())
                        <span class="notif-badge absolute right-1 top-1 h-2 w-2 rounded-full bg-red-500" aria-hidden="true"></span>
                    @endif
                    <div id="dropdown-notifications" class="dropdown-menu dropdown-menu-end absolute right-0 top-full z-50 mt-1 hidden w-80 rounded-lg border border-gray-200 bg-white py-2 shadow-lg">
                        <div class="flex items-center justify-between border-b border-gray-100 px-3 pb-2">
                            <span class="text-sm font-medium text-gray-900">Notifications</span>
                            <a class="small text-xs text-blue-600 hover:underline" href="#" data-modal-open="deleteNotificationsModal">Clear all</a>
                        </div>
                <div class="max-h-64 overflow-y-auto">
                    @forelse($notifications as $notify)
                        <a href="#" class="block border-b border-gray-50 px-3 py-2 text-left hover:bg-gray-50 last:border-0">
                            <p class="text-sm font-medium text-gray-900">{{ $notify->title }}</p>
                            <p class="text-xs text-gray-600">{{ Str::limit($notify->message, 80) }}</p>
                            <p class="mt-1 text-xs text-gray-400">{{ $notify->date_added?->diffForHumans() }}</p>
                        </a>
                    @empty
                        <p class="px-3 py-4 text-center text-sm text-gray-500">No notifications.</p>
                    @endforelse
                </div>
                <div class="border-t border-gray-100 px-3 pt-2 text-center">
                    <a href="{{ route('admin.notifications.index') }}" class="text-sm text-blue-600 hover:underline">See all incoming activity</a>
                </div>
                    </div>
                </li>
                <li class="nav-item dropdown relative dropdown-wrap">
                    <a class="avatar avatar-sm p-0 flex items-center gap-2 rounded-lg p-1 text-gray-700 hover:bg-gray-100" href="#" id="profileDropdown" role="button" data-dropdown-trigger="profile" aria-expanded="false">
                @if($layoutAdmin && $layoutAdmin->profileImage)
                    <img src="{{ asset('uploads/staffs/' . $layoutAdmin->profileImage) }}" alt="" class="avatar-img h-8 w-8 rounded-lg object-cover">
                @else
                    <span class="avatar-img flex h-8 w-8 items-center justify-center rounded-lg bg-gray-200 text-sm font-medium text-gray-600">{{ substr($layoutAdmin->name ?? 'A', 0, 1) }}</span>
                @endif
                    </a>
                    <div id="dropdown-profile" class="dropdown-menu dropdown-menu-end absolute right-0 top-full z-50 mt-1 hidden w-56 rounded-lg border border-gray-200 bg-white py-2 shadow-lg" aria-labelledby="profileDropdown">
                <div class="border-b border-gray-100 px-3 pb-2">
                    <p class="text-sm font-medium text-gray-900">{{ $layoutAdmin->name ?? 'Admin' }}</p>
                    <p class="text-xs text-gray-500">{{ $layoutAdmin->email ?? '' }}</p>
                </div>
                <a href="{{ route('admin.profile.show') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">My Profile</a>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Settings</a>
                <form method="POST" action="{{ route('admin.logout') }}" class="border-t border-gray-100">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2 px-3 py-2 text-left text-sm text-red-600 hover:bg-red-50">Sign Out</button>
                </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- Legacy: Clear Notifications modal (id and form for JS #delete-notifications-form) --}}
<div class="modal modal-overly fixed inset-0 z-[100] hidden items-center justify-center bg-black/50" id="deleteNotificationsModal" aria-labelledby="edit-modal-header" aria-hidden="true" role="dialog" tabindex="-1">
    <div class="modal-dialog relative max-w-md rounded-lg bg-white shadow-xl">
        <div class="modal-content rounded-lg">
            <div class="modal-header flex items-center justify-between border-b border-gray-200 px-4 py-3">
                <h1 class="modal-title text-lg font-medium" id="exampleModalLabel">Clear Notifications</h1>
                <button type="button" class="btn-close rounded p-1 hover:bg-gray-100 text-gray-500" data-modal-close="deleteNotificationsModal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body px-4 py-3">
                <p class="mb-0 text-gray-700">Are you sure you want to clear all notifications?</p>
            </div>
            <div class="modal-footer flex gap-2 justify-end border-t border-gray-200 px-4 py-3">
                <button type="button" class="btn btn-secondary rounded bg-gray-200 px-4 py-2 hover:bg-gray-300" data-modal-close="deleteNotificationsModal">Close</button>
                <form id="delete-notifications-form" method="POST" action="{{ route('legacy.requests.delete_notifications') }}" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-primary rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 mb-0" id="deleteNotificationsBtn">Yes, Clear</button>
                </form>
            </div>
        </div>
    </div>
</div>
