@extends('layouts.app')

@section('content')
    <main class="flex-1 overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
            <div class="mb-4 sm:mb-6 w-fit">
                <a href="{{ route('admin.news.index') }}" class="inline-flex items-center gap-2 text-sm font-medium transition-opacity hover:opacity-80" style="color: var(--on-surface-variant);">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    Back to Announcements
                </a>
            </div>

            <header class="mb-6 lg:mb-8">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-normal tracking-tight mb-1.5" style="color: var(--on-surface); letter-spacing: -0.02em;">
                    Create announcement
                </h1>
                <p class="text-sm sm:text-base font-normal" style="color: var(--on-surface-variant);">
                    Publish a new school-wide announcement with title, category, content and optional cover image.
                </p>
            </header>

            <div class="space-y-4 sm:space-y-6">
                <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                    <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                        <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Announcement details</h2>
                    </div>

                    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" id="news-form" class="p-4 sm:p-5 min-w-0 space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                            <div class="form-group sm:col-span-2">
                                <label for="title" class="form-label">Title <span style="color: var(--primary);">*</span></label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="e.g. School Resumption Announcement" required class="form-input w-full min-w-0">
                                @error('title')
                                <p class="form-error mt-1 text-sm" aria-live="polite">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group sm:col-span-2">
                                <label for="category" class="form-label">Category <span style="color: var(--primary);">*</span></label>
                                <input type="text" name="category" id="category" value="{{ old('category') }}" placeholder="e.g. Announcement, Holiday, Exam" required class="form-input w-full min-w-0">
                                @error('category')
                                <p class="form-error mt-1 text-sm" aria-live="polite">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Author</label>
                            <p class="text-sm" style="color: var(--on-surface-variant);">
                                {{ $layoutAdmin->name ?? 'Admin' }} <span class="text-xs">(set automatically from the logged in admin)</span>
                            </p>
                        </div>

                        <div class="form-group">
                            <label for="content" class="form-label">Content <span style="color: var(--primary);">*</span></label>
                            <textarea name="content" id="content" rows="8" placeholder="Write the announcement content..." required class="form-input w-full min-w-0" style="min-height: 180px;">{{ old('content') }}</textarea>
                            @error('content')
                            <p class="form-error mt-1 text-sm" aria-live="polite">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="photoimg" class="form-label">Cover image (optional)</label>
                            <input type="file" name="photoimg" id="photoimg" accept="image/jpeg,image/png,image/jpg" class="form-input w-full min-w-0 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary">
                            <p class="mt-1 text-xs" style="color: var(--on-surface-variant);">
                                Allowed: jpg, jpeg, png. Max 2MB. Will be resized to 556×350.
                            </p>
                            @error('photoimg')
                            <p class="form-error mt-1 text-sm" aria-live="polite">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 pt-2">
                            <a href="{{ route('admin.news.index') }}" class="btn-secondary px-6 py-2.5 rounded-full text-sm w-full sm:w-auto text-center">
                                Cancel
                            </a>
                            <button type="submit" class="btn-primary px-6 py-2.5 rounded-full text-sm w-full sm:w-auto" data-preloader>
                                Post announcement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
