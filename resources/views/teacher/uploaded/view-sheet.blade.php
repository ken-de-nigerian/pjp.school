@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('teacher.uploaded.index') }}" class="text-indigo-600 hover:underline">← Back to Uploaded</a>
    <h1 class="text-2xl font-semibold mt-2">Uploaded results — {{ $class }} / {{ $subjects }} ({{ $term }}, {{ $session }})</h1>
</div>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reg #</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">CA</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Assignment</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Exam</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $r)
                <tr>
                    <td class="px-4 py-2">{{ $r->name ?? '' }}</td>
                    <td class="px-4 py-2">{{ $r->reg_number ?? '' }}</td>
                    <td class="px-4 py-2">{{ $r->ca ?? '' }}</td>
                    <td class="px-4 py-2">{{ $r->assignment ?? '' }}</td>
                    <td class="px-4 py-2">{{ $r->exam ?? '' }}</td>
                    <td class="px-4 py-2">{{ $r->total ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
