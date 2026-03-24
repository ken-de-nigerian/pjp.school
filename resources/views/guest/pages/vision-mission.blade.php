@extends('layouts.guest', ['title' => $title])

@section('content')
    <x-guest.content-page :title="$title" headline="Vision &amp; mission">
        <div class="space-y-8">
            <div>
                <h2 class="text-xl font-serif font-bold text-educave-900 mb-3">Vision</h2>
                <p>
                    To be a leading Catholic secondary school that forms young people of integrity, excellence, and
                    faith—ready to lead with courage and compassion in Nigeria and beyond.
                </p>
            </div>
            <div>
                <h2 class="text-xl font-serif font-bold text-educave-900 mb-3">Mission</h2>
                <p>
                    We provide a safe, disciplined, and nurturing environment where students pursue rigorous academics,
                    grow in virtue, and are encouraged to live out the Gospel values in daily life.
                </p>
            </div>
        </div>
        <p class="pt-4">
            <a href="{{ route('about_us') }}" class="font-semibold text-educave-800 hover:text-educave-600 underline underline-offset-2">Back to About us</a>
        </p>
    </x-guest.content-page>
@endsection
