@extends('layouts.guest')

@section('content')
<div class="text-center py-12">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ config('app.name') }}</h1>
    <p class="text-gray-600 mb-8">Student result portal</p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('result.check') }}" class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700">Check Result</a>
        <a href="{{ route('admin.login') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50">Admin Login</a>
        <a href="{{ route('teacher.login') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50">Teacher Login</a>
    </div>
</div>
@endsection
