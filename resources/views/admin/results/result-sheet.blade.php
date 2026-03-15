@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.status.index') }}" class="text-blue-600 hover:underline">← Back to Status</a>
</div>
<h1 class="text-2xl font-semibold mb-4">Result Sheet — {{ e($class) }} ({{ e($segment) }})</h1>
<p class="text-gray-600 mb-2">Term: {{ e($term) }}, Session: {{ e($session) }}</p>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reg #</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">CA</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Exam</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($getResults as $r)
            <tr>
                <td class="px-4 py-2 text-sm">{{ e($r->name) }}</td>
                <td class="px-4 py-2 text-sm">{{ e($r->reg_number) }}</td>
                <td class="px-4 py-2 text-sm">{{ e($r->subjects) }}</td>
                <td class="px-4 py-2 text-sm">{{ e($r->ca ?? $r->ca1 ?? $r->ca2 ?? $r->ca3 ?? '-') }}</td>
                <td class="px-4 py-2 text-sm">{{ e($r->exam ?? $r->exam1 ?? $r->exam2 ?? $r->exam3 ?? '-') }}</td>
                <td class="px-4 py-2 text-sm">{{ e($r->total ?? '-') }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">No results found for this selection.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
