@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold">Attendance</h1>
    <p class="text-gray-600 text-sm mt-1">Select an option below.</p>
</div>
<div class="flex gap-4 flex-wrap">
    <a href="{{ route('teacher.attendance.view-attendance') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">View Attendance</a>
</div>
<div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
    <h2 class="px-4 py-2 bg-gray-50 font-medium">Take attendance by class</h2>
    <p class="px-4 py-2 text-sm text-gray-600">Select a class to take attendance. Term: {{ $settings['term'] ?? '—' }}, Session: {{ $settings['session'] ?? '—' }}, Segment: {{ $settings['segment'] ?? '—' }}.</p>
    <ul class="divide-y">
        @foreach($classList as $c)
            <li class="px-4 py-3 flex justify-between items-center">
                <span>{{ $c['class_name'] ?? '' }}</span>
                <a href="{{ route('teacher.attendance.take-attendance', ['class' => $c['class_name'], 'term' => $settings['term'] ?? '', 'session' => $settings['session'] ?? '', 'segment' => $settings['segment'] ?? 'First']) }}" class="text-indigo-600 hover:underline">Take attendance</a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
