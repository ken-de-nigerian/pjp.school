@extends('layouts.app')

@section('content')
@if(session('success'))
<div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-2">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="mb-4 rounded bg-red-100 text-red-800 px-4 py-2">{{ session('error') }}</div>
@endif
<div class="mb-6 flex justify-between items-center flex-wrap gap-2">
    <div>
        <h1 class="text-2xl font-semibold">Results for: {{ e($param) }}</h1>
        <p class="text-gray-600 text-sm mt-1">Matching name or reg number.</p>
    </div>
    <a href="{{ route('admin.results-by-params') }}" class="text-indigo-600 hover:underline">← Back to search</a>
</div>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reg #</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Term</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Session</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Segment</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">CA</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Exam</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($results as $r)
                <tr>
                    <td class="px-4 py-2">{{ $r->id }}</td>
                    <td class="px-4 py-2">{{ e($r->name) }}</td>
                    <td class="px-4 py-2">{{ e($r->reg_number) }}</td>
                    <td class="px-4 py-2">{{ e($r->class ?? $r->class_arm) }}</td>
                    <td class="px-4 py-2">{{ e($r->term) }}</td>
                    <td class="px-4 py-2">{{ e($r->session) }}</td>
                    <td class="px-4 py-2">{{ e($r->subjects) }}</td>
                    <td class="px-4 py-2">{{ e($r->segment) }}</td>
                    <td class="px-4 py-2">{{ $r->ca }}</td>
                    <td class="px-4 py-2">{{ $r->exam }}</td>
                    <td class="px-4 py-2">{{ $r->total }}</td>
                    <td class="px-4 py-2">{{ $r->status === 1 ? 'Approved' : ($r->status === 3 ? 'Rejected' : 'Pending') }}</td>
                </tr>
            @empty
                <tr><td colspan="12" class="px-4 py-4 text-gray-500">No results found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
