@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-semibold">Unused Pins</h1>
    <a href="{{ route('admin.card.index') }}" class="text-blue-600 hover:underline">Back to Scratch Card</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-4 border-b border-gray-200">
        <p class="text-sm text-gray-500">Session: {{ e($settings['session'] ?? '—') }}</p>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pin</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Session</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Uploaded</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($unused as $row)
                    <tr>
                        <td class="px-4 py-2">{{ e($row->pins) }}</td>
                        <td class="px-4 py-2">{{ e($row->session) }}</td>
                        <td class="px-4 py-2">{{ $row->upload_date ? \Carbon\Carbon::parse($row->upload_date)->format('M j, Y H:i') : '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-4 text-gray-500">No unused pins.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
