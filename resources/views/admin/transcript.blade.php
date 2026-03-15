@extends('layouts.app')

@section('content')
@if(session('success'))
<div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-2">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="mb-4 rounded bg-red-100 text-red-800 px-4 py-2">{{ session('error') }}</div>
@endif
<div class="mb-6">
    <h1 class="text-2xl font-semibold">Transcript</h1>
    <p class="text-gray-600 text-sm mt-1">Transcript and scratch card management.</p>
</div>
<div class="bg-white rounded-lg shadow p-6">
    <p class="text-gray-600">Use this page for transcript generation and scratch card settings. Configure options below or use the result check flow for students.</p>
</div>
@endsection
