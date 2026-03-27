@php use Illuminate\Support\Carbon; use Illuminate\Support\Str; @endphp
@extends('layouts.guest', ['title' => $title])

@section('content')
    <main id="main-content">
        <div class="animate-in fade-in duration-500 font-sans bg-educave-50">
            <section class="bg-white border-b border-gray-200 pt-24 pb-12 relative overflow-visible">
                <div class="container mx-auto px-4 md:px-8 lg:px-16 text-center relative z-10">
                    <div class="flex items-center justify-center gap-3 mb-6">
                        <div class="h-px w-12 bg-educave-900"></div>
                        <span class="text-xs font-bold uppercase tracking-[0.3em] text-educave-900">LATEST NEWS</span>
                        <div class="h-px w-12 bg-educave-900"></div>
                    </div>

                    <h1 class="text-5xl sm:text-6xl md:text-8xl lg:text-9xl font-serif font-bold text-educave-900 tracking-tighter leading-[0.95] md:leading-none mb-8">
                        SCHOOL CHRONICLES
                    </h1>

                    <p class="text-gray-500 max-w-xl mx-auto text-lg font-serif italic">
                        "Stories of faith, achievement and community from {{ site_settings()->name }}."
                    </p>
                </div>
            </section>

            @if ($featured)
                @php
                    $featuredDate = $featured->created_at ?? $featured->date_added;
                    $fd = $featuredDate ? Carbon::parse($featuredDate) : null;
                    $featuredImg = $featured->cover_image
                        ? asset('storage/news/' . $featured->cover_image)
                        : asset('storage/news/default.png');
                @endphp
                <section class="py-16 container mx-auto px-4 md:px-8 lg:px-16">
                    <a href="{{ route('news.show', $featured) }}" class="group cursor-pointer grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-8 relative overflow-hidden rounded-2xl">
                            <img alt="{{ $featured->title }}" class="w-full h-[500px] object-cover transition-transform duration-1000 group-hover:scale-105" loading="lazy" decoding="async" src="{{ $featuredImg }}" onerror="this.src='{{ asset('storage/news/default.png') }}'; this.onerror=null;"/>
                            <div class="absolute top-6 left-6 bg-educave-900 text-white px-4 py-2 text-xs font-bold uppercase tracking-widest shadow-lg">Featured Story</div>
                        </div>

                        <div class="lg:col-span-4 flex flex-col justify-center h-full">
                            <div class="flex items-center gap-3 text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">
                                <span class="text-educave-800">{{ $featured->category ?: 'News' }}</span>
                                @if($fd)
                                    <span class="w-1 h-1 bg-gray-400 rounded-full"></span>
                                    <time datetime="{{ $fd->toDateString() }}">{{ $fd->format('M j, Y') }}</time>
                                @endif
                            </div>

                            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-serif font-bold text-educave-900 leading-snug mb-6 group-hover:text-educave-600 transition-colors">{{ $featured->title }}</h2>

                            <p class="text-gray-500 leading-relaxed mb-8 line-clamp-3">{{ Str::limit(strip_tags((string) ($featured->content ?? '')), 180) }}</p>

                            <div class="flex items-center justify-between border-t border-gray-200 pt-6 mt-auto">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-educave-100 overflow-hidden border border-gray-200 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-educave-800" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </div>

                                    <div>
                                        <p class="text-xs font-bold text-educave-900 uppercase">{{ $featured->author ?? 'PJP News Desk' }}</p>
                                        <p class="text-[10px] text-gray-400 uppercase tracking-wide">School Update</p>
                                    </div>
                                </div>

                                <span class="text-xs font-bold uppercase tracking-widest text-educave-500 group-hover:translate-x-1 transition-transform flex items-center gap-1">
                                    Read<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                </span>
                            </div>
                        </div>
                    </a>
                </section>
            @endif

            <section class="pb-16 container mx-auto px-4 md:px-8 lg:px-16">
                @if (!$featured && $news->isEmpty())
                    <p class="text-gray-500 text-center py-12 font-serif italic">No news published yet. Check back soon.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12" id="news-grid">
                        @foreach ($news as $item)
                            @php
                                $itemDate = $item->created_at ?? $item->date_added;
                                $d = $itemDate ? Carbon::parse($itemDate) : null;
                                $img = $item->cover_image
                                    ? asset('storage/news/' . $item->cover_image)
                                    : asset('storage/news/default.png');
                            @endphp
                            <article class="news-card flex flex-col group cursor-pointer h-full" data-cat="{{ $item->category }}" data-title="{{ strtolower($item->title) }}">
                                <a href="{{ route('news.show', $item) }}" class="relative overflow-hidden rounded-xl mb-6 aspect-[3/2] block">
                                    <img alt="{{ $item->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy" decoding="async" src="{{ $img }}" onerror="this.src='{{ asset('storage/news/default.png') }}'; this.onerror=null;"/>
                                    @if($item->category)
                                        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-educave-900">{{ $item->category }}</div>
                                    @endif
                                </a>

                                <div class="flex-1 flex flex-col">
                                    <div class="flex items-center gap-2 text-xs text-gray-400 font-medium mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                                        @if($d)
                                            <time datetime="{{ $d->toDateString() }}">{{ $d->format('M j, Y') }}</time>
                                        @endif
                                    </div>

                                    <h3 class="text-2xl font-serif font-bold text-educave-900 mb-3 leading-tight group-hover:text-educave-600 transition-colors">
                                        <a href="{{ route('news.show', $item) }}">{{ $item->title }}</a>
                                    </h3>

                                    <p class="text-gray-500 text-sm leading-relaxed mb-6 line-clamp-3 flex-grow">{{ Str::limit(strip_tags((string) ($item->content ?? '')), 140) }}</p>

                                    <div class="flex items-center justify-between border-t border-gray-100 pt-4 mt-auto">
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                            <span class="text-xs font-bold text-gray-600 uppercase">{{ $item->author ?? 'PJP News Desk' }}</span>
                                        </div>

                                        <a href="{{ route('news.show', $item) }}" class="text-xs font-bold uppercase tracking-widest text-educave-500 group-hover:translate-x-1 transition-transform flex items-center gap-1">
                                            Read
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    @if($news->hasPages())
                        <div class="py-4">
                            <x-pagination :paginator="$news"/>
                        </div>
                    @endif
                @endif
            </section>
        </div>
    </main>
@endsection
