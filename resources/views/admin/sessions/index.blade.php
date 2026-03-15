@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-semibold">Academic Sessions</h1>
    <a href="{{ route('admin.sessions.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Session</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Year</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Current</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($sessions as $s)
                <tr>
                    <td class="px-4 py-2">{{ e($s->year) }}</td>
                    <td class="px-4 py-2">
                        @if(($currentYear ?? '') === $s->year)
                            <span class="text-green-600 font-medium">Current</span>
                        @else
                            <form action="{{ route('admin.sessions.activate', $s->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-blue-600 hover:underline text-sm">Set as current</button>
                            </form>
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        <a href="{{ route('admin.sessions.edit', $s->id) }}" class="text-blue-600 hover:underline text-sm">Edit</a>
                        <form action="{{ route('admin.sessions.destroy', $s->id) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Delete this session?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="px-4 py-4 text-gray-500">No academic sessions. Add one to get started.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
