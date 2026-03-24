@extends('layouts.guest', ['title' => $title])

@section('content')
    <x-guest.content-page :title="$title" headline="Academic curriculum">
        <p>
            The curriculum follows the Nigerian secondary school scheme for JSS and SS classes. Core areas typically
            include English, mathematics, basic science and technology, social studies, and Nigerian languages at the
            junior level, expanding into arts, sciences, and commercial subjects as students move to senior secondary
            and choose examination-focused combinations.
        </p>
        <p>
            Teaching is aligned with WAEC and NECO expectations, with continuous assessment and terminal examinations.
            Subject teachers work with the school leadership to update schemes of work and support students who need
            extra help.
        </p>
        <p>
            <a href="{{ route('academic_overview') }}" class="font-semibold text-educave-800 hover:text-educave-600 underline underline-offset-2">Academic overview</a>
            ·
            <a href="{{ route('apply_online') }}" class="font-semibold text-educave-800 hover:text-educave-600 underline underline-offset-2">Apply online</a>
        </p>
    </x-guest.content-page>
@endsection
