@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold">Class | Subjects</h1>
    <p class="text-gray-600 text-sm mt-1">View students by class.</p>
</div>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <ul class="divide-y">
        @foreach($classList as $c)
            <li class="px-4 py-3 flex justify-between items-center">
                <span>{{ $c['class_name'] ?? '' }}</span>
                <span class="text-gray-500 text-sm">{{ $c['user_count'] ?? 0 }} students</span>
                <a href="{{ route('teacher.class.find-students', ['class' => $c['class_name']]) }}" class="text-indigo-600 hover:underline">Open class</a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
