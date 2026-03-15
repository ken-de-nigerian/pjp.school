@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-semibold">Scratch Card</h1>
    <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Back to Dashboard</a>
</div>

<div class="grid gap-4 md:grid-cols-2 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-medium text-gray-700">Unused Pins</h2>
        <p class="text-2xl font-semibold mt-1">{{ $unused_count }}</p>
        <p class="text-sm text-gray-500 mt-1">Session: {{ e($settings['session'] ?? '—') }}</p>
        <a href="{{ route('admin.card.unused-pins') }}" class="inline-block mt-3 text-blue-600 hover:underline">View unused pins</a>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-medium text-gray-700">Used Pins</h2>
        <p class="text-2xl font-semibold mt-1">{{ $used_count }}</p>
        <p class="text-sm text-gray-500 mt-1">Session: {{ e($settings['session'] ?? '—') }}</p>
        <a href="{{ route('admin.card.used-pins') }}" class="inline-block mt-3 text-blue-600 hover:underline">View used pins</a>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <a href="{{ route('admin.card.generate-pins') }}" class="inline-flex items-center px-4 py-2 rounded bg-amber-100 text-amber-800 hover:bg-amber-200">Generate Pins</a>
</div>
@endsection
