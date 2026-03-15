@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <div class="mb-4 sm:mb-6 w-fit">
                <a href="{{ route('admin.attendance.index') }}" class="inline-flex items-center gap-2 text-sm font-medium transition-opacity hover:opacity-80" style="color: var(--on-surface-variant);">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    Back to Attendance
                </a>
            </div>

            <header class="mb-6 lg:mb-8">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-normal tracking-tight mb-1.5" style="color: var(--on-surface); letter-spacing: -0.02em;">View Attendance</h1>
                <p class="text-sm sm:text-base font-normal" style="color: var(--on-surface-variant);">Filter by class, term, session, and segment to view uploaded records. After submitting, use the attendance sheet to browse by date.</p>
            </header>

            <div class="flex-1 flex flex-col min-h-0 w-full">
                <div class="rounded-3xl p-5 sm:p-6 lg:p-8 overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                    <div class="flex items-center gap-4 mb-6 sm:mb-8">
                        <div>
                            <h2 class="text-base sm:text-lg font-medium" style="color: var(--on-surface);">Filter records</h2>
                            <p class="text-sm font-normal" style="color: var(--on-surface-variant);">Choose date, class, term, session and segment</p>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('admin.attendance.record') }}" class="space-y-5 sm:space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                            <div class="form-group sm:col-span-2">
                                <label for="view-attendance-date" class="form-label">Date</label>
                                <input type="date" id="view-attendance-date" name="date" class="form-input" value="{{ old('date', request('date', date('Y-m-d'))) }}">
                                <p id="date-error" class="form-error mt-1 text-sm {{ $errors->has('date') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('date') }}</p>
                            </div>
                            <div class="form-group">
                                <label for="view-attendance-class" class="form-label">Class</label>
                                <select id="view-attendance-class" name="class" class="form-select">
                                    <option value="">Select class</option>
                                    @foreach($classes as $c)
                                        <option value="{{ e($c->class_name) }}" {{ old('class', request('class')) === $c->class_name ? 'selected' : '' }}>{{ e($c->class_name) }}</option>
                                    @endforeach
                                </select>
                                <p id="class-error" class="form-error mt-1 text-sm {{ $errors->has('class') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('class') }}</p>
                            </div>

                            <div class="form-group">
                                <label for="view-attendance-term" class="form-label">Term</label>
                                <select id="view-attendance-term" name="term" class="form-select">
                                    <option value="First Term" {{ old('term', $settings['term'] ?? '') === 'First Term' ? 'selected' : '' }}>First Term</option>
                                    <option value="Second Term" {{ old('term', $settings['term'] ?? '') === 'Second Term' ? 'selected' : '' }}>Second Term</option>
                                    <option value="Third Term" {{ old('term', $settings['term'] ?? '') === 'Third Term' ? 'selected' : '' }}>Third Term</option>
                                </select>
                                <p id="term-error" class="form-error mt-1 text-sm {{ $errors->has('term') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('term') }}</p>
                            </div>

                            <div class="form-group">
                                <label for="view-attendance-session" class="form-label">Session</label>
                                <select id="view-attendance-session" name="session" class="form-select">
                                    <option value="">Select session</option>
                                    @foreach(range((int)date('Y') - 5, (int)date('Y') + 5) as $y)
                                        @php $opt = $y . '/' . ($y + 1); @endphp
                                        <option value="{{ $opt }}" {{ old('session', $settings['session'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                                <p id="session-error" class="form-error mt-1 text-sm {{ $errors->has('session') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('session') }}</p>
                            </div>

                            <div class="form-group">
                                <label for="view-attendance-segment" class="form-label">Segment</label>
                                <select id="view-attendance-segment" name="segment" class="form-select">
                                    <option value="First" {{ old('segment', $settings['segment'] ?? '') === 'First' ? 'selected' : '' }}>First Segment</option>
                                    <option value="Second" {{ old('segment', $settings['segment'] ?? '') === 'Second' ? 'selected' : '' }}>Second Segment</option>
                                    <option value="Third" {{ old('segment', $settings['segment'] ?? '') === 'Third' ? 'selected' : '' }}>Third Segment</option>
                                </select>
                                <p id="segment-error" class="form-error mt-1 text-sm {{ $errors->has('segment') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('segment') }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2" style="border-top: 1px solid var(--outline-variant); padding-top: 1.25rem;">
                            <a href="{{ route('admin.attendance.index') }}" class="btn-secondary inline-flex items-center justify-center gap-2 px-6 py-3 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 sm:min-w-[120px]" style="border-radius: 12px;">
                                <i class="fas fa-times text-sm" aria-hidden="true"></i>
                                Cancel
                            </a>

                            <button type="submit" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" data-preloader style="border-radius: 12px;">
                                <i class="fas fa-eye text-sm" aria-hidden="true"></i>
                                View attendance
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
