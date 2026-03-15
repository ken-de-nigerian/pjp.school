@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-semibold">Staff: {{ e($staff->name) }}</h1>
    <div class="flex gap-2">
        <a href="{{ route('admin.staff.edit', $staff->adminId) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</a>
        <a href="{{ route('admin.staff.index') }}" class="text-blue-600 hover:underline">Back to list</a>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6 space-y-4">
    <p><strong>ID:</strong> {{ e($staff->adminId) }}</p>
    <p><strong>Name:</strong> {{ e($staff->name) }}</p>
    <p><strong>Email:</strong> {{ e($staff->email) }}</p>
    <p><strong>Phone:</strong> {{ e($staff->phone ?? '—') }}</p>
    <p><strong>Role:</strong> {{ e($staff->role->name ?? '—') }}</p>
    <p><strong>Joined:</strong> {{ $staff->joined?->format('Y-m-d H:i') ?? '—' }}</p>
</div>

<div class="mt-6 bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-medium mb-2">Reset password</h2>
    <form method="POST" action="{{ route('admin.staff.reset-password', $staff->adminId) }}">
        @csrf
        @method('PUT')
        <div class="flex gap-2 flex-wrap items-end">
            <div>
                <label class="block text-sm text-gray-700 mb-1">New password</label>
                <input type="password" name="password" required minlength="8" class="rounded border border-gray-300 px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-gray-700 mb-1">Confirm password</label>
                <input type="password" name="password_confirmation" required minlength="8" class="rounded border border-gray-300 px-3 py-2">
            </div>
            <div><button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded">Reset password</button></div>
        </div>
        @error('password')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
    </form>
</div>

@can('delete', $staff)
<div class="mt-6">
    <form method="POST" action="{{ route('admin.staff.destroy', $staff->adminId) }}" onsubmit="return confirm('Delete this staff member?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete staff</button>
    </form>
</div>
@endcan
@endsection
