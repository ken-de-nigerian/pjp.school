@php use Illuminate\Support\Carbon; @endphp
@extends('layouts.guest', ['title' => $title])

@section('content')
    @php
        $publishedAt = $news->created_at
            ? Carbon::parse($news->created_at)
            : ($news->date_added ? Carbon::parse($news->date_added) : null);
        $heroImage = $news->cover_image
            ? asset('storage/news/' . $news->cover_image)
            : asset('storage/news/default.png');
        $currentUrl = url()->current();
        $encodedUrl = urlencode($currentUrl);
        $encodedTitle = urlencode($news->title);
    @endphp

    <main id="main-content">
        <div class="bg-white min-h-screen font-sans animate-in fade-in duration-500">
            <div class="sticky top-0 bg-white/90 backdrop-blur-md border-b border-gray-100 z-40 py-4">
                <div class="container mx-auto px-4 md:px-8 lg:px-16 flex items-center justify-between">
                    <a href="{{ route('news') }}" class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-educave-900 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                        Back to News
                    </a>
                    <div class="hidden md:flex items-center gap-4">
                        <span class="text-xs font-bold uppercase tracking-widest text-educave-900">Share</span>
                        <div class="flex gap-2">
                            <a href="https://twitter.com/intent/tweet?url={{ $encodedUrl }}&text={{ $encodedTitle }}" target="_blank" rel="noopener noreferrer" class="p-2 hover:bg-gray-100 transition-colors" aria-label="Share on Twitter">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
                            </a>

                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}" target="_blank" rel="noopener noreferrer" class="p-2 hover:bg-gray-100 transition-colors" aria-label="Share on Facebook">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                            </a>

                            <button onclick="navigator.clipboard.writeText('{{ $currentUrl }}').then(() => this.title = 'Copied!')" class="p-2 hover:bg-gray-100 transition-colors" aria-label="Copy link" title="Copy link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <header class="pt-20 pb-12 text-center max-w-4xl mx-auto px-4">
                @if($news->category)
                    <span class="inline-block px-4 py-1.5 bg-educave-50 text-educave-800 text-[10px] font-bold uppercase tracking-widest mb-6 border border-educave-200">{{ $news->category }}</span>
                @endif

                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-educave-900 leading-tight mb-8 break-words [overflow-wrap:anywhere]">{{ $news->title }}</h1>

                @if($news->excerpt ?? null)
                    <p class="text-xl md:text-2xl text-gray-500 font-serif italic leading-relaxed mb-10">{{ $news->excerpt }}</p>
                @endif

                <div class="flex flex-col md:flex-row items-center justify-center gap-6 md:gap-12 text-sm text-gray-500 border-y border-gray-100 py-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-educave-100 border border-gray-200 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-educave-800" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div class="text-left">
                            <p class="font-bold text-educave-900">{{ $news->author ?? 'PJP News Desk' }}</p>
                            <p class="text-xs uppercase tracking-wide text-gray-400">{{ site_settings()->name }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-6">
                        @if($publishedAt)
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                                <time datetime="{{ $publishedAt->toDateString() }}">{{ $publishedAt->format('F j, Y') }}</time>
                            </div>
                        @endif
                    </div>
                </div>
            </header>

            <div class="container mx-auto px-4 md:px-8 lg:px-16 mb-20">
                <div class="relative overflow-hidden rounded-2xl shadow-2xl">
                    <img alt="{{ $news->title }}" class="w-full h-[260px] sm:h-[340px] md:h-[420px] lg:h-[480px] object-cover" loading="eager" decoding="async" src="{{ $heroImage }}" onerror="this.src='{{ asset('storage/news/default.png') }}'; this.onerror=null;"/>
                </div>
            </div>

            <div class="container mx-auto px-4 md:px-8 lg:px-16 mb-24">
                <article id="article-content" class="w-full prose prose-xl max-w-none prose-headings:font-serif prose-headings:font-bold prose-headings:text-educave-900 prose-p:text-gray-600 prose-p:leading-relaxed prose-a:text-educave-800 prose-a:underline prose-blockquote:border-l-4 prose-blockquote:border-educave-900 prose-blockquote:pl-8 prose-blockquote:italic prose-blockquote:text-educave-900 prose-blockquote:font-serif prose-blockquote:text-2xl prose-img:rounded-sm prose-img:shadow-md prose-ul:list-disc prose-ol:list-decimal">
                    {!! $news->content !!}
                </article>
            </div>

            @if(isset($relatedNews) && $relatedNews->isNotEmpty())
                <section class="bg-educave-50 py-20 border-t border-gray-200">
                    <div class="container mx-auto px-4 md:px-8 lg:px-16">
                        <h2 class="text-3xl font-serif font-bold text-educave-900 mb-12 text-center">Read Next</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            @foreach ($relatedNews->take(3) as $related)
                                @php
                                    $relImg = $related->cover_image
                                        ? asset('storage/news/' . $related->cover_image)
                                        : asset('storage/news/default.png');
                                    $relDate = $related->created_at
                                        ? Carbon::parse($related->created_at)
                                        : ($related->date_added ? Carbon::parse($related->date_added) : null);
                                @endphp
                                <a href="{{ route('news.show', $related) }}" class="group cursor-pointer bg-white shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col">
                                    <div class="h-48 overflow-hidden">
                                        <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $related->title }}" loading="lazy" decoding="async" src="{{ $relImg }}" onerror="this.src='{{ asset('storage/news/default.png') }}'; this.onerror=null;"/>
                                    </div>

                                    <div class="p-6 flex flex-col flex-1">
                                        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-educave-800 mb-2">
                                            {{ $related->category ?: 'News' }}
                                            @if($relDate)
                                                <span class="text-gray-300">•</span>
                                                <time datetime="{{ $relDate->toDateString() }}" class="text-gray-400 font-medium normal-case tracking-normal">{{ $relDate->format('M j, Y') }}</time>
                                            @endif
                                        </div>

                                        <h3 class="text-xl font-bold font-serif text-educave-900 group-hover:text-educave-700 transition-colors mb-4 flex-1">{{ $related->title }}</h3>

                                        <div class="flex items-center gap-2 text-xs font-bold text-gray-400 group-hover:text-educave-900 transition-colors uppercase mt-auto">
                                            Read Article
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif
        </div>
    </main>
@endsection
