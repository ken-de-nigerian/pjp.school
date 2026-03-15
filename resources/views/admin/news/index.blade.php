@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-semibold">News</h1>
    <a href="{{ route('admin.news.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add News</a>
</div>

<div class="bg-white rounded-lg shadow divide-y">
    @forelse($news as $item)
        <div class="p-4 flex gap-4">
            @if($item->cover_image)
                <img src="{{ asset('storage/news/'.$item->cover_image) }}" alt="" class="w-24 h-16 object-cover rounded" onerror="this.src='{{ asset('storage/news/default.png') }}'; this.onerror=null;">
            @endif
            <div class="flex-1 min-w-0">
                <h3 class="font-medium">
                    <a href="{{ route('admin.news.show', $item->newsid) }}" class="text-blue-600 hover:underline">{{ e($item->title) }}</a>
                </h3>
                <p class="text-sm text-gray-500">{{ $item->created_at?->format('M j, Y') ?? $item->date_added?->format('M j, Y') }}</p>
                @if($item->category)
                    <span class="text-xs text-gray-400">{{ e($item->category) }}</span>
                @endif
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.news.edit', $item->newsid) }}" class="text-blue-600 hover:underline text-sm">Edit</a>
                <form action="{{ route('admin.news.destroy', $item->newsid) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this news?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:underline text-sm">Delete</button>
                </form>
            </div>
        </div>
    @empty
        <div class="p-4 text-gray-500">No news.</div>
    @endforelse
</div>
<div class="mt-4">{{ $news->links() }}</div>
@endsection
