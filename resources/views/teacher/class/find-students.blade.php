@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('teacher.class.index') }}" class="text-indigo-600 hover:underline">← Back to Class</a>
    <h1 class="text-2xl font-semibold mt-2">Students {{ $class ? "— {$class}" : '' }}</h1>
</div>
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form action="{{ route('teacher.class.find-students') }}" method="GET" class="flex gap-3 flex-wrap items-end">
        <div><label class="block text-sm text-gray-700">Class</label><select name="class" class="rounded border border-gray-300 px-2 py-1">
            @foreach($getClasses ?? [] as $cn)
                @php $val = is_object($cn) ? ($cn->class_name ?? '') : $cn; @endphp
                <option value="{{ $val }}" {{ (isset($class) && $val === $class) ? 'selected' : '' }}>{{ $val }}</option>
            @endforeach
        </select></div>
        <div><label class="block text-sm text-gray-700">Search</label><input type="text" name="search" value="{{ $search ?? '' }}" class="rounded border border-gray-300 px-2 py-1" placeholder="Name or reg number"></div>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Search</button>
    </form>
</div>
@if(isset($students) && (is_array($students) ? count($students) : $students->count()) > 0)
<div class="bg-white rounded-lg shadow overflow-hidden">
    <p class="px-4 py-2 text-sm text-gray-600">{{ $totalItems }} student(s) found.</p>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reg #</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $s)
                <tr>
                    <td class="px-4 py-2">{{ is_object($s) ? ($s->reg_number ?? '') : ($s['reg_number'] ?? '') }}</td>
                    <td class="px-4 py-2">{{ is_object($s) ? (($s->firstname ?? '') . ' ' . ($s->lastname ?? '')) : (($s['firstname'] ?? '') . ' ' . ($s['lastname'] ?? '')) }}</td>
                    <td class="px-4 py-2">{{ is_object($s) ? ($s->class ?? '') : ($s['class'] ?? '') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<p class="text-gray-500">No students found.</p>
@endif
@endsection
