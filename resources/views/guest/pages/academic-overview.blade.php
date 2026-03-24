@extends('layouts.guest', ['title' => $title])

@section('content')
    <x-guest.content-page :title="$title" headline="Academic overview">
        <p>
            Our academic programme spans junior and senior secondary levels, with qualified teachers, structured
            lesson plans, and regular assessment to track progress. We emphasise literacy, numeracy, critical
            thinking, and exam readiness alongside moral and spiritual formation.
        </p>
        <p>
            Students benefit from classroom instruction, assignments, tests, and termly reports. Subject offerings and
            progression follow national standards while leaving room for co-curricular growth.
        </p>
        <p>
            <a href="{{ route('academic_curriculum') }}" class="font-semibold text-educave-800 hover:text-educave-600 underline underline-offset-2">View curriculum highlights</a>
            or <a href="{{ route('news') }}" class="font-semibold text-educave-800 hover:text-educave-600 underline underline-offset-2">read school news</a>.
        </p>
    </x-guest.content-page>
@endsection
