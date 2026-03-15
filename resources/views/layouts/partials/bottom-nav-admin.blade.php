<nav class="lg:hidden fixed bottom-0 left-0 right-0 backdrop-blur-xl border-t px-2 py-2 safe-area-bottom z-50 shadow-lg" style="background: var(--mobile-nav-bg); border-color: var(--border);">
    <div class="flex items-center justify-around">
        <a href="{{ route('admin.dashboard') }}"
           class="mobile-bottom-nav-item flex flex-col items-center gap-1 transition-all min-h-[60px] px-2 py-2 rounded-xl active:scale-95"
           style="color: var(--text-primary);"
           data-page="dashboard">
            <div class="w-12 h-12 rounded-xl card-refined flex items-center justify-center">
                <i class="fas fa-chart-pie text-lg"></i>
            </div>
            <span class="text-[10px] font-medium">Dashboard</span>
        </a>

        @if(Route::has('admin.classes'))
        <a href="{{ route('admin.classes') }}"
           class="mobile-bottom-nav-item flex flex-col items-center gap-1 transition-all min-h-[60px] px-2 py-2 rounded-xl active:scale-95"
           style="color: var(--text-primary);"
           data-page="classes">
            <div class="w-12 h-12 rounded-xl card-refined flex items-center justify-center">
                <i class="fas fa-user-graduate text-lg"></i>
            </div>
            <span class="text-[10px] font-medium leading-tight text-center px-0.5">Students / Classes</span>
        </a>
        @endif

        <a href="{{ route('admin.upload-results') }}"
           class="mobile-bottom-nav-item flex flex-col items-center gap-1 transition-all min-h-[60px] px-2 py-2 rounded-xl active:scale-95"
           style="color: var(--text-primary);"
           data-page="results">
            <div class="w-12 h-12 rounded-xl card-refined flex items-center justify-center">
                <i class="fas fa-poll text-lg"></i>
            </div>
            <span class="text-[10px] font-medium leading-tight text-center px-0.5">Upload results</span>
        </a>

        <a href="{{ route('admin.profile.show') }}"
           class="mobile-bottom-nav-item flex flex-col items-center gap-1 transition-all min-h-[60px] px-2 py-2 rounded-xl active:scale-95"
           style="color: var(--text-primary);"
           data-page="profile">
            <div class="w-12 h-12 rounded-xl card-refined flex items-center justify-center">
                <i class="fas fa-cogs text-lg"></i>
            </div>
            <span class="text-[10px] font-medium">Settings</span>
        </a>
    </div>
</nav>
