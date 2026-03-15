@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold">Published results</h1>
    <p class="text-gray-600 text-sm mt-1">View published results by class, term and session.</p>
</div>
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('teacher.published.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
        <div><label class="block text-sm text-gray-700">Class</label><input type="text" name="class" class="rounded border border-gray-300 px-2 py-1" placeholder="Class" value="{{ request('class') }}"></div>
        <div><label class="block text-sm text-gray-700">Term</label><input type="text" name="term" class="rounded border border-gray-300 px-2 py-1" placeholder="e.g. First Term" value="{{ request('term', $settings['term'] ?? '') }}"></div>
        <div><label class="block text-sm text-gray-700">Session</label><input type="text" name="session" class="rounded border border-gray-300 px-2 py-1" placeholder="e.g. 2024/2025" value="{{ request('session', $settings['session'] ?? '') }}"></div>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">View</button>
    </form>
</div>
@endsection
