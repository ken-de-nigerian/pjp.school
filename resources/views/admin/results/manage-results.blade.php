@extends('layouts.app')

@section('content')
@if(session('success'))
<div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-2">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="mb-4 rounded bg-red-100 text-red-800 px-4 py-2">{{ session('error') }}</div>
@endif
<div class="mb-6">
    <h1 class="text-2xl font-semibold">Manage Results by Name or Reg Number</h1>
    <p class="text-gray-600 text-sm mt-1">Search for results by student name or registration number.</p>
</div>
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.results-by-params') }}" method="GET" class="flex gap-3 flex-wrap items-end">
        <div class="flex-1 min-w-[200px]">
            <label for="param" class="block text-sm font-medium text-gray-700 mb-1">Name or Reg Number</label>
            <input type="text" id="param" name="param" value="{{ request('param') }}"
                   class="w-full rounded border border-gray-300 px-3 py-2 focus:ring focus:ring-indigo-500 focus:border-indigo-500"
                   placeholder="Enter name or reg number">
        </div>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Search</button>
    </form>
</div>
@endsection
