@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold">Behavioural</h1>
    <p class="text-gray-600 text-sm mt-1">Take or view behavioural records.</p>
</div>
<div class="flex gap-4 flex-wrap">
    <a href="{{ route('teacher.behavioral.view-behavioral') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">View behavioural</a>
</div>
<div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
    <h2 class="px-4 py-2 bg-gray-50 font-medium">Take behavioural by class</h2>
    <ul class="divide-y">
        @foreach($classList as $c)
            <li class="px-4 py-3 flex justify-between items-center">
                <span>{{ $c['class_name'] ?? '' }}</span>
                <a href="{{ route('teacher.behavioral.take-behavioral', ['class' => $c['class_name'], 'term' => $settings['term'] ?? '', 'session' => $settings['session'] ?? '']) }}" class="hover:underline" style="color: var(--primary);">Take behavioural</a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
