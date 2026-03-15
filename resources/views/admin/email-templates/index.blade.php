@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold">Email Templates</h1>
    <p class="text-gray-600 text-sm mt-1">Templates used for behavioral, attendance, and result notifications.</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($templates as $template)
                <tr>
                    <td class="px-4 py-3 text-sm">{{ $template->id }}</td>
                    <td class="px-4 py-3 text-sm">{{ e($template->name ?: '—') }}</td>
                    <td class="px-4 py-3 text-sm">{{ Str::limit(e($template->subject), 50) }}</td>
                    <td class="px-4 py-3 text-sm">
                        @if((int)$template->email_status === 1)
                            <span class="text-green-600">Active</span>
                        @else
                            <span class="text-gray-500">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-right">
                        <a href="{{ route('admin.email-templates.edit', $template) }}" class="text-blue-600 hover:underline">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">No email templates found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
