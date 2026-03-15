@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold">Published Results</h1>
    <p class="text-gray-600 text-sm mt-1">View published result positions by class, term, and session.</p>
</div>

<form method="GET" action="{{ route('admin.publish-results') }}" class="bg-white rounded-lg shadow p-4 max-w-lg space-y-4 mb-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Class</label>
        <select name="class" class="w-full rounded border border-gray-300 px-3 py-2">
            <option value="">Select class</option>
            @foreach($classes as $c)
                <option value="{{ e($c->class_name) }}" {{ ($class ?? '') === $c->class_name ? 'selected' : '' }}>{{ e($c->class_name) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Term</label>
        <input type="text" name="term" value="{{ e($term ?? '') }}" placeholder="e.g. First Term" class="w-full rounded border border-gray-300 px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Session</label>
        <select name="session" class="w-full rounded border border-gray-300 px-3 py-2">
            <option value="">Select session</option>
            @foreach($sessions as $s)
                <option value="{{ e($s->year ?? $s->id) }}" {{ ($session ?? '') === ($s->year ?? (string)$s->id) ? 'selected' : '' }}>{{ e($s->year ?? $s->id) }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">View Published</button>
</form>

@if($class !== '' && $term !== '' && $session !== '')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <h2 class="px-4 py-2 font-medium border-b border-gray-200">{{ e($class) }} — {{ e($term) }} — {{ e($session) }}</h2>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reg #</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Average</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($positions as $p)
                <tr>
                    <td class="px-4 py-3 text-sm">{{ $p->class_position }}</td>
                    <td class="px-4 py-3 text-sm">{{ e($p->reg_number) }}</td>
                    <td class="px-4 py-3 text-sm">{{ e($p->name) }}</td>
                    <td class="px-4 py-3 text-sm">{{ $p->students_sub_total }}</td>
                    <td class="px-4 py-3 text-sm">{{ number_format((float)$p->students_sub_average, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">No published results for this selection.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endif
@endsection
