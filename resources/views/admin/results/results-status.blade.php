@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">← Dashboard</a>
</div>
<h1 class="text-2xl font-semibold mb-4">Check Result Status</h1>
<form method="GET" action="{{ route('admin.status.index') }}" class="bg-white rounded-lg shadow p-4 max-w-lg space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Class</label>
        <select name="class" class="w-full rounded border border-gray-300 px-3 py-2" required>
            <option value="">Select class</option>
            @foreach($getClasses as $c)
            <option value="{{ e($c->class_name) }}">{{ e($c->class_name) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Term</label>
        <input type="text" name="term" value="{{ $settings['term'] ?? '1' }}" placeholder="e.g. 1 or First Term" class="w-full rounded border border-gray-300 px-3 py-2" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Session</label>
        <select name="session" class="w-full rounded border border-gray-300 px-3 py-2" required>
            <option value="">Select session</option>
            @foreach($sessions as $s)
            <option value="{{ e($s->year ?? $s->id) }}">{{ e($s->year ?? $s->id) }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">View Results</button>
</form>
@endsection
