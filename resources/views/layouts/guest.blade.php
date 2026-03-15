<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Check Result' }} — {{ config('app.name') }}</title>
    @unless(app()->runningUnitTests())
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endunless
    @stack('styles')
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 antialiased">
    @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded shadow z-50" role="alert">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded shadow z-50" role="alert">{{ session('error') }}</div>
    @endif
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-14 items-center">
                <a href="{{ route('home') }}" class="text-lg font-semibold">{{ config('app.name') }}</a>
                <div class="flex gap-4">
                    <a href="{{ route('result.check') }}" class="text-gray-600 hover:text-gray-900">Check Result</a>
                    <a href="{{ route('admin.login') }}" class="text-gray-600 hover:text-gray-900">Admin</a>
                    <a href="{{ route('teacher.login') }}" class="text-gray-600 hover:text-gray-900">Teacher</a>
                </div>
            </div>
        </div>
    </nav>
    <main class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
