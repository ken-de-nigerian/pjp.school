@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-semibold">Add Academic Session</h1>
    <a href="{{ route('admin.sessions.index') }}" class="text-blue-600 hover:underline">Back to Sessions</a>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-md">
    <form action="{{ route('admin.sessions.store') }}" method="POST">
        @csrf
        <div>
            <label for="year" class="block text-sm font-medium text-gray-700">Year <span class="text-red-500">*</span></label>
            <input type="text" name="year" id="year" value="{{ old('year') }}" required placeholder="e.g. 2024/2025" class="mt-1 block w-full rounded border border-gray-300 px-3 py-2">
            @error('year')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="mt-4 flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create</button>
            <a href="{{ route('admin.sessions.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">Cancel</a>
        </div>
    </form>
</div>
@endsection
