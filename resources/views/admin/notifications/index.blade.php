@extends('layouts.app')

@section('content')
@if(session('success'))
<div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-2">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="mb-4 rounded bg-red-100 text-red-800 px-4 py-2">{{ session('error') }}</div>
@endif
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-semibold">Notifications</h1>
    <form action="{{ route('admin.notifications.clear') }}" method="POST" class="inline" onsubmit="return confirm('Clear all notifications?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Clear all</button>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Message</th>
                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($notifications as $n)
            <tr>
                <td class="px-4 py-3 text-sm text-gray-600">{{ $n->date_added?->format('d M Y H:i') }}</td>
                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ e($n->title) }}</td>
                <td class="px-4 py-3 text-sm text-gray-600">{{ e($n->message) }}</td>
                <td class="px-4 py-3 text-sm text-right">
                    <form action="{{ route('admin.notifications.destroy', $n) }}" method="POST" class="inline" onsubmit="return confirm('Delete this notification?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-4 py-6 text-center text-gray-500">No notifications.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($notifications->hasPages())
    <div class="px-4 py-2 border-t border-gray-200">
        {{ $notifications->links() }}
    </div>
    @endif
</div>
@endsection
