<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <!-- title -->
        <title>{{ $title ?? config('app.name') }} — {{ config('app.name') }}</title>

        @unless(app()->runningUnitTests())
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endunless

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/intlTelInput.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/iziToast.min.css') }}">

        @stack('styles')
    </head>
    <body>
        @auth('admin')
            <div class="flex h-screen overflow-hidden" style="background: var(--bg-primary);">
                <!-- Main Content Area -->
                <div class="flex-1 flex flex-col overflow-hidden">
                    <!-- Header -->
                    @include('layouts.partials.header-admin')
                    <!-- Header -->

                    <!-- Sidebar -->
                    @include('layouts.partials.sidebar-admin')
                    <!-- Sidebar -->

                    <!-- Main -->
                    @yield('content')
                    <!-- Main -->
                </div>
            </div>

            <!-- Premium Mobile Bottom Navigation -->
            @include('layouts.partials.bottom-nav-admin')
        @elseauth('teacher')
            <div class="flex h-screen overflow-hidden" style="background: var(--bg-primary);">
                <!-- Main Content Area -->
                <div class="flex-1 flex flex-col overflow-hidden">
                    <!-- Header -->
                    @include('layouts.partials.header-teacher')
                    <!-- Header -->

                    <!-- Sidebar -->
                    @include('layouts.partials.sidebar-teacher')
                    <!-- Sidebar -->

                    <!-- Main -->
                    @yield('content')
                    <!-- Main -->
                </div>
            </div>

            <!-- Premium Mobile Bottom Navigation -->
            @include('layouts.partials.bottom-nav-teacher')
        @endauth

        @guest
            <!-- Main -->
            @yield('content')
            <!-- Main -->
        @endguest

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>

        <script src="{{ asset('assets/js/iziToast.min.js') }}"></script>

        <!-- intlTelInput init -->
        <script src="{{ asset('assets/js/intlTelInput.min.js') }}"></script>
        <script src="{{ asset('assets/js/utils.js') }}"></script>
        <script src="{{ asset('assets/js/validate-phone.js') }}"></script>

        <script src="{{ asset('assets/js/custom.js') }}"></script>

        @stack('scripts')
        @include('partials.message')

        <script>
            const iziToastSettings = {
                position: "topRight",
                timeout: 5000,
                resetOnHover: true,
                transitionIn: "flipInX",
                transitionOut: "flipOutX"
            };
        </script>
    </body>
</html>
