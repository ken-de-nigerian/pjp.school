@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('teacher.subjects.index') }}" class="text-indigo-600 hover:underline">← Back to Subjects</a>
    <h1 class="text-2xl font-semibold mt-2">Registered students — {{ $class }} / {{ $subjects }}</h1>
</div>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reg #</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $s)
                <tr>
                    <td class="px-4 py-2">{{ $s->reg_number ?? '' }}</td>
                    <td class="px-4 py-2">{{ $s->firstname ?? '' }} {{ $s->lastname ?? '' }}</td>
                    <td class="px-4 py-2">{{ $s->class ?? '' }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="px-4 py-4 text-gray-500">No students found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
