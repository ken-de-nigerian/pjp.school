@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-semibold">Student: {{ e($student->firstname . ' ' . $student->lastname) }}</h1>
    <div class="flex gap-2">
        <a href="{{ route('admin.students.edit', $student->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</a>
        <a href="{{ route('admin.classes') }}" class="text-blue-600 hover:underline">Back to list</a>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6 space-y-4">
    <p><strong>Reg #:</strong> {{ e($student->reg_number) }}</p>
    <p><strong>Class:</strong> {{ e($student->class) }}</p>
    <p><strong>Status:</strong> {{ $student->status === 2 ? 'Active' : 'Inactive' }}</p>
    <p><strong>Phone:</strong> {{ e($student->contact_phone ?? '—') }}</p>
    <p><strong>Address:</strong> {{ e($student->address ?? '—') }}</p>
</div>
@endsection
