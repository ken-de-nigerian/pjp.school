@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-semibold">Edit Email Template</h1>
    <a href="{{ route('admin.email-templates.index') }}" class="text-blue-600 hover:underline">Back to list</a>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    <form action="{{ route('admin.email-templates.update', $template) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name (optional)</label>
                <input type="text" name="name" id="name" value="{{ old('name', $template->name) }}" placeholder="e.g. Welcome Email" class="mt-1 block w-full rounded border border-gray-300 px-3 py-2">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700">Subject <span class="text-red-500">*</span></label>
                <input type="text" name="subject" id="subject" value="{{ old('subject', $template->subject) }}" placeholder="e.g. Welcome to PJP School" required class="mt-1 block w-full rounded border border-gray-300 px-3 py-2">
                @error('subject')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="email_body" class="block text-sm font-medium text-gray-700">Body <span class="text-red-500">*</span></label>
                <textarea name="email_body" id="email_body" rows="10" placeholder="e.g. Dear {{name}}, welcome to our school..." required class="mt-1 block w-full rounded border border-gray-300 px-3 py-2">{{ old('email_body', $template->email_body) }}</textarea>
                @error('email_body')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-gray-500">Placeholders (e.g. {{name}}, {{student_name}}) may be used depending on the notification type.</p>
            </div>
            <div>
                <label class="flex items-center gap-2">
                    <input type="hidden" name="email_status" value="0">
                    <input type="checkbox" name="email_status" value="1" {{ old('email_status', (string)$template->email_status) === '1' ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-gray-700">Active (use this template for sending)</span>
                </label>
                @error('email_status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="mt-6 flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update template</button>
            <a href="{{ route('admin.email-templates.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">Cancel</a>
        </div>
    </form>
</div>
@endsection
