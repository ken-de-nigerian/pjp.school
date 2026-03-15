@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-semibold">Edit News</h1>
    <a href="{{ route('admin.news.show', $news->newsid) }}" class="text-blue-600 hover:underline">Back to News</a>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    <form action="{{ route('admin.news.update', $news->newsid) }}" method="POST" enctype="multipart/form-data" id="news-edit-form">
        @csrf
        @method('PUT')
        <input type="hidden" name="newsId" value="{{ $news->newsid }}">
        <div class="space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title', $news->title) }}" placeholder="e.g. School Resumption Announcement" required class="mt-1 block w-full rounded border border-gray-300 px-3 py-2">
                @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                <input type="text" name="category" id="category" value="{{ old('category', $news->category) }}" placeholder="e.g. Announcement" required class="mt-1 block w-full rounded border border-gray-300 px-3 py-2">
                @error('category')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700">Content <span class="text-red-500">*</span></label>
                <textarea name="message" id="message" rows="8" placeholder="e.g. School resumption date has been announced..." required class="mt-1 block w-full rounded border border-gray-300 px-3 py-2">{{ old('message', old('content', $news->content)) }}</textarea>
                @error('message')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Cover image</label>
                @if($news->cover_image)
                    <img src="{{ asset('storage/news/'.$news->cover_image) }}" alt="" class="mt-1 h-24 object-cover rounded" onerror="this.style.display='none';">
                @endif
                <p class="mt-1 text-xs text-gray-500">To change cover, use the upload below.</p>
            </div>
            <div>
                <label for="photoimg" class="block text-sm font-medium text-gray-700">Upload new cover image</label>
                <input type="file" name="photoimg" id="photoimg" accept="image/jpeg,image/png,image/jpg" class="mt-1 block w-full text-sm">
                <p class="mt-1 text-xs text-gray-500">Allowed: jpg, jpeg, png. Max 2MB.</p>
                @error('photoimg')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="mt-6 flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update News</button>
            <a href="{{ route('admin.news.show', $news->newsid) }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">Cancel</a>
        </div>
    </form>
</div>
@endsection
