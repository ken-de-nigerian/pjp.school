@extends('layouts.guest')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-md mx-auto text-center">
    <h1 class="text-xl font-semibold text-red-600 mb-2">Fee not approved</h1>
    <p class="text-gray-600 mb-4">Your fee status must be approved before you can view results. Please contact the school.</p>
    <a href="{{ route('result.check') }}" class="inline-block px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Back to Check Result</a>
</div>
@endsection
