@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <div class="mb-4 sm:mb-6 w-fit">
                <a href="{{ route('admin.subjects.index', ['grade' => 'Junior']) }}" class="inline-flex items-center gap-2 text-sm font-medium transition-opacity hover:opacity-80" style="color: var(--on-surface-variant);">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    Back to Subjects
                </a>
            </div>

            <header class="mb-6 lg:mb-8 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-normal tracking-tight mb-1.5" style="color: var(--on-surface); letter-spacing: -0.02em;">
                        Register students to subjects
                    </h1>
                    <p class="text-sm sm:text-base font-normal" style="color: var(--on-surface-variant);">
                        Choose a class to register students to their subjects.
                    </p>
                </div>

                @if(Route::has('admin.subjects.registered'))
                    <a href="{{ route('admin.subjects.registered') }}"
                       class="w-full lg:w-auto inline-flex items-center justify-center gap-2 px-4 py-3 sm:py-2.5 rounded-xl text-sm font-medium transition-colors border border-dashed border-gray-300 lg:border-solid"
                       style="background-color: var(--primary); color: var(--on-primary);">
                        <i class="fas fa-eye text-xs" aria-hidden="true"></i>
                        <span>View Registered</span>
                    </a>
                @endif
            </header>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl p-5 sm:p-6 lg:p-8" style="background: var(--surface-container-low); box-shadow: var(--elevation-1);">
                <div class="col-span-full flex-1 flex flex-col items-center justify-center min-h-[min(400px,50vh)] py-12 sm:py-16">
                    <div class="rounded-3xl p-4 sm:p-6 lg:p-8 overflow-hidden min-w-0 w-full" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                        <form method="GET" action="{{ route('admin.subjects.fetch-classes') }}" class="space-y-5 sm:space-y-6">
                            <div class="form-group min-w-0">
                                <label for="class" class="form-label">Select class</label>
                                <select id="class" name="class" class="form-select w-full min-w-0">
                                    <option value="">Choose class</option>
                                    @foreach($getClasses as $c)
                                        @php $className = is_object($c) ? $c->class_name : $c; @endphp
                                        <option value="{{ e($className) }}" {{ old('class') === $className ? 'selected' : '' }}>{{ e($className) }}</option>
                                    @endforeach
                                </select>
                                <p id="class-error" class="form-error mt-1 text-sm {{ $errors->has('class') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('class') }}</p>
                            </div>

                            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2 min-w-0" style="border-top: 1px solid var(--outline-variant); padding-top: 1.25rem;">
                                <a href="{{ route('admin.subjects.index', ['grade' => 'Junior']) }}" class="btn-secondary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 sm:min-w-[120px]" style="border-radius: 12px;">
                                    <i class="fas fa-times text-sm" aria-hidden="true"></i>
                                    Cancel
                                </a>

                                <button type="submit" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" data-preloader style="border-radius: 12px;">
                                    <i class="fas fa-arrow-right text-sm" aria-hidden="true"></i>
                                    Continue
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
