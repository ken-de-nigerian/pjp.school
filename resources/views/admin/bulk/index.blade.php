@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">← Dashboard</a>
</div>
<h1 class="text-2xl font-semibold mb-4">Bulk SMS</h1>
<div class="bg-white rounded-lg shadow p-6 max-w-lg">
    <p class="text-gray-600">Use this page to send bulk SMS to students or parents. (Configure your SMS provider in settings.)</p>
</div>
@endsection
