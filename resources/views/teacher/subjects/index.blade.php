@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold">Subject management</h1>
    <p class="text-gray-600 text-sm mt-1">View students registered for a subject by class.</p>
</div>
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('teacher.subjects.registered') }}" method="GET" class="flex flex-wrap gap-4 items-end">
        <div><label class="block text-sm text-gray-700">Class</label><select name="class" class="rounded border border-gray-300 px-2 py-1">@foreach($getClasses as $cn)<option value="{{ is_object($cn) ? ($cn->class_name ?? '') : $cn }}">{{ is_object($cn) ? ($cn->class_name ?? '') : $cn }}</option>@endforeach</select></div>
        <div><label class="block text-sm text-gray-700">Subject</label><select name="subjects" class="rounded border border-gray-300 px-2 py-1">@foreach($subjects as $sub)<option value="{{ $sub->subject_name ?? '' }}">{{ $sub->subject_name ?? '' }}</option>@endforeach</select></div>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">View registered</button>
    </form>
</div>
@endsection
