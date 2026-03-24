@php use Illuminate\Support\Carbon; @endphp
@extends('layouts.guest', ['title' => $title])

@section('content')
    @php
        $publishedAt = $news->created_at
            ? Carbon::parse($news->created_at)
            : ($news->date_added ? Carbon::parse($news->date_added) : null);
        $heroImage = $news->cover_image
            ? asset('storage/news/'.$news->cover_image)
            : asset('storage/news/default.png');
    @endphp
    <main id="main-content" class="pt-20">
        <section class="relative bg-educave-900 text-white py-12 md:py-16 overflow-hidden">
            <div class="absolute inset-0 opacity-[0.12] bg-[radial-gradient(circle_at_30%_20%,theme(colors.educave.400),transparent_55%)]"></div>
            <div class="container mx-auto px-4 md:px-8 lg:px-16 relative z-10 max-w-4xl">
                <p class="mb-4">
                    <a href="{{ route('news') }}" class="text-xs font-bold uppercase tracking-widest text-educave-300 hover:text-white underline-offset-4 hover:underline">{{ __('All news') }}</a>
                </p>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-educave-300 mb-3">{{ $news->category ?: __('News') }}</p>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif font-bold tracking-tight">{{ $news->title }}</h1>
                <div class="mt-4 flex flex-wrap gap-3 text-sm text-educave-200/90">
                    @if ($publishedAt)
                        <time datetime="{{ $publishedAt->toDateString() }}">{{ $publishedAt->format('F j, Y') }}</time>
                    @endif
                    @if ($news->author)
                        <span>·</span>
                        <span>{{ $news->author }}</span>
                    @endif
                </div>
            </div>
        </section>

        <article class="bg-white py-12 md:py-16">
            <div class="container mx-auto px-4 md:px-8 lg:px-16 max-w-3xl">
                <div class="rounded-xl overflow-hidden mb-10 border border-gray-100 shadow-sm">
                    <img
                        src="{{ $heroImage }}"
                        alt=""
                        class="w-full max-h-[420px] object-cover"
                        loading="eager"
                        decoding="async"
                        onerror="this.src='{{ asset('storage/news/default.png') }}'; this.onerror=null;"
                    />
                </div>
                <div class="text-gray-700 leading-relaxed space-y-4 [&_a]:text-educave-800 [&_a]:underline [&_img]:max-w-full [&_img]:rounded-lg [&_ul]:list-disc [&_ul]:pl-6 [&_ol]:list-decimal [&_ol]:pl-6">
                    {!! $news->content !!}
                </div>
            </div>
        </article>
    </main>
@endsection
