@extends('layouts.guest', ['title' => $title])

@section('content')
    <x-guest.content-page :title="$title" headline="Admission process">
        <ol class="list-decimal pl-5 space-y-4">
            <li>Enquire about available places and collect or request the admission information pack.</li>
            <li>Complete the application form and submit required documents (birth certificate, previous records, etc.).</li>
            <li>Attend any scheduled interview or placement assessment as communicated by the school.</li>
            <li>Upon offer, complete registration and fee payment within the stated deadline.</li>
        </ol>
        <p class="pt-4">
            For the next step, see
            <a href="{{ route('apply_online') }}" class="font-semibold text-educave-800 hover:text-educave-600 underline underline-offset-2">Apply online</a>
            or contact us by phone or email.
        </p>
    </x-guest.content-page>
@endsection
