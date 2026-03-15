@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-semibold">{{ e($news->title) }}</h1>
    <div class="flex gap-2">
        <a href="{{ route('admin.news.edit', $news->newsid) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</a>
        <a href="{{ route('admin.news.index') }}" class="text-blue-600 hover:underline py-2">Back to News</a>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden max-w-4xl">
    @if($news->cover_image)
        <img src="{{ asset('storage/news/'.$news->cover_image) }}" alt="{{ e($news->title) }}" class="w-full max-h-80 object-cover" onerror="this.style.display='none';">
    @endif
    <div class="p-6">
        <p class="text-sm text-gray-500">
            {{ $news->created_at?->format('M j, Y H:i') ?? $news->date_added?->format('M j, Y H:i') }}
            @if($news->category)
                · {{ e($news->category) }}
            @endif
            @if($news->author)
                · {{ e($news->author) }}
            @endif
        </p>
        <div class="prose max-w-none mt-4">
            {!! nl2br(e($news->content)) !!}
        </div>
    </div>
    <div class="px-6 pb-6 flex gap-2">
        <form action="{{ route('admin.news.destroy', $news->newsid) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this news?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:underline text-sm">Delete</button>
        </form>
    </div>
</div>
@endsection
