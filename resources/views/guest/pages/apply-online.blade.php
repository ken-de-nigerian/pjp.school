@extends('layouts.guest', ['title' => $title])

@section('content')
    <x-guest.content-page :title="$title" headline="Apply online">
        <p>
            Thank you for your interest in Pope John Paul II Model Secondary School. Online applications are handled
            through our admissions office. Please reach out using the details below and we will guide you through
            forms, fees, and required documents.
        </p>
        <ul class="list-none space-y-3 pl-0">
            <li>
                <span class="font-semibold text-educave-900">Phone:</span>
                <a href="tel:{{ preg_replace('/\s+/', '', (string) config('school.school_phone')) }}" class="text-educave-800 hover:text-educave-600 underline underline-offset-2 ml-1">{{ config('school.school_phone') }}</a>
            </li>
            <li>
                <span class="font-semibold text-educave-900">Email:</span>
                <a href="mailto:{{ config('school.school_email') }}" class="text-educave-800 hover:text-educave-600 underline underline-offset-2 ml-1">{{ config('school.school_email') }}</a>
            </li>
        </ul>
        <p>
            You may also review the
            <a href="{{ route('admin_process') }}" class="font-semibold text-educave-800 hover:text-educave-600 underline underline-offset-2">admission process</a>
            before you apply.
        </p>
    </x-guest.content-page>
@endsection
