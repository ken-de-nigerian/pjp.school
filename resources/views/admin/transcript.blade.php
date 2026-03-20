@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Transcripts"
                pill="Admin"
                title="Transcripts"
                description="Generate official student transcripts and manage transcript-related settings."
            />

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden items-center justify-center py-16 md:py-24 px-6" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center" style="border-radius: 16px;">
                    <i class="fas fa-file-alt text-3xl" aria-hidden="true"></i>
                </div>
                <h2 class="text-xl sm:text-2xl font-semibold mb-2 text-center" style="color: var(--on-surface);">
                    Transcript module coming soon
                </h2>

                <p class="text-sm sm:text-base text-center max-w-md mb-4" style="color: var(--on-surface-variant);">
                    We&apos;re working on a dedicated transcript experience where you&apos;ll be able to generate official transcripts and track requests in one place.
                </p>
            </div>
        </div>
    </main>
@endsection
