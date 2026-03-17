@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('teacher.attendance.index') }}" class="text-indigo-600 hover:underline">← Back to Attendance</a>
    <h1 class="text-2xl font-semibold mt-2">View attendance</h1>
</div>
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form action="{{ route('teacher.attendance.view-attendance') }}" method="GET" class="flex flex-wrap gap-4 items-end">
        <div><label class="block text-sm text-gray-700">Date</label><input type="date" name="date" value="{{ $date ?? '' }}" class="rounded border border-gray-300 px-2 py-1"></div>
        <div><label class="block text-sm text-gray-700">Class</label><select name="class" class="rounded border border-gray-300 px-2 py-1">@foreach($classList as $c)<option value="{{ $c['class_name'] ?? '' }}" {{ (isset($class) && ($c['class_name'] ?? '') === $class) ? 'selected' : '' }}>{{ $c['class_name'] ?? '' }}</option>@endforeach</select></div>
        <div><label class="block text-sm text-gray-700">Term</label><input type="text" name="term" value="{{ $term ?? $settings['term'] ?? '' }}" class="rounded border border-gray-300 px-2 py-1" placeholder="e.g. First Term"></div>
        <div><label class="block text-sm text-gray-700">Session</label><input type="text" name="session" value="{{ $session ?? $settings['session'] ?? '' }}" class="rounded border border-gray-300 px-2 py-1" placeholder="e.g. 2024/2025"></div>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">View</button>
    </form>
</div>
@if(isset($students) && $students->isNotEmpty())
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50"><tr><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reg #</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th></tr></thead>
        <tbody>
            @foreach($students as $s)
                <tr><td class="px-4 py-2">{{ $s->name ?? '' }}</td><td class="px-4 py-2">{{ $s->reg_number ?? '' }}</td><td class="px-4 py-2">{{ $s->class_roll_call ?? '' }}</td></tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<p class="text-gray-500">No attendance record for the selected criteria.</p>
@endif
@endsection
