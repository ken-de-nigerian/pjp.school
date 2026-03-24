@php use Illuminate\Support\Carbon;use Illuminate\Support\Str; @endphp
@extends('layouts.guest', ['title' => $title])

@section('content')
    <main id="main-content" class="pt-20">
        <section class="relative bg-educave-900 text-white py-16 md:py-20 overflow-hidden">
            <div class="absolute inset-0 opacity-[0.12] bg-[radial-gradient(circle_at_30%_20%,theme(colors.educave.400),transparent_55%)]"></div>
            <div class="container mx-auto px-4 md:px-8 lg:px-16 relative z-10">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-educave-300 mb-4">PJP Great</p>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold tracking-tight">News &amp; updates</h1>
            </div>
        </section>

        <section class="bg-white py-14 md:py-20">
            <div class="container mx-auto px-4 md:px-8 lg:px-16">
                @if ($news->isEmpty())
                    <p class="text-gray-500 text-center py-12">{{ __('No news published yet.') }}</p>
                @else
                    <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($news as $item)
                            @php
                                $itemDate = $item->created_at ?? $item->date_added;
                                $d = $itemDate ? Carbon::parse($itemDate) : null;
                                $img = $item->cover_image
                                    ? asset('storage/news/'.$item->cover_image)
                                    : asset('storage/news/default.png');
                            @endphp
                            <article class="group flex flex-col border border-gray-100 rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow bg-white">
                                <a href="{{ route('news.show', $item) }}" class="block overflow-hidden aspect-[16/10] bg-gray-100">
                                    <img
                                        src="{{ $img }}"
                                        alt="{{ $item->title }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        loading="lazy"
                                        decoding="async"
                                        onerror="this.src='{{ asset('storage/news/default.png') }}'; this.onerror=null;"
                                    />
                                </a>
                                <div class="p-6 flex flex-col flex-1">
                                    <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">
                                        <span class="text-educave-800">{{ $item->category ?: __('News') }}</span>
                                        @if ($d)
                                            <span>•</span>
                                            <time datetime="{{ $d->toDateString() }}">{{ $d->format('M j, Y') }}</time>
                                        @endif
                                    </div>
                                    <h2 class="text-xl font-serif font-bold text-educave-900 mb-3 group-hover:text-educave-700">
                                        <a href="{{ route('news.show', $item) }}">{{ $item->title }}</a>
                                    </h2>
                                    <p class="text-gray-600 text-sm leading-relaxed flex-1">{{ Str::limit(strip_tags((string) ($item->content ?? '')), 140) }}</p>
                                    <a href="{{ route('news.show', $item) }}" class="mt-4 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-educave-800 hover:text-educave-600">
                                        {{ __('Read more') }}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="mt-12 flex justify-center">
                        {{ $news->links() }}
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection
