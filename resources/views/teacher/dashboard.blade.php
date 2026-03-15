@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold">Teacher Dashboard</h1>
    <p class="text-gray-600">Welcome, {{ $user->firstname ?? 'Teacher' }} {{ $user->lastname ?? '' }}</p>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b">
        <h2 class="text-lg font-medium">News</h2>
    </div>
    <div class="p-4">
        @php
            $newsItems = $get_news ?? $news?->items() ?? [];
        @endphp
        @if(empty($newsItems))
            <p class="text-gray-500 text-center py-4">No news found.</p>
        @else
            <ul class="divide-y space-y-4">
                @foreach($newsItems as $item)
                    <li class="py-2">
                        <span class="font-medium">{{ e($item->title ?? '') }}</span>
                        <span class="text-sm text-gray-500 ml-2">
                            @if(isset($item->created_at))
                                {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                            @else
                                {{ $item->date_added?->format('M j, Y') ?? '' }}
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
            <x-pagination :paginator="$news ?? null" />
        @endif
    </div>
</div>
@endsection
